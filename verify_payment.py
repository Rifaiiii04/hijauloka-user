from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64
import os
import time
from flask_cors import CORS
import pytesseract
import re
import logging
from datetime import datetime
import sys

if sys.platform.startswith('win'):
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

app = Flask(__name__)
CORS(app)

logging.basicConfig(
    filename='payment_verification.log',
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

DATASET_PATHS = [
    'assets/img/dataset/buktipembayaran.jpg',
    'assets/img/dataset/buktipembayaran2.jpg',
    'assets/img/dataset/buktipembayaran3.jpg',
    'assets/img/dataset/image.png',
]

DATASET_DIR = 'assets/img/dataset/'
os.makedirs(DATASET_DIR, exist_ok=True)

def preprocess_image(img):
    try:
        img = cv2.resize(img, (640, 480))
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        thresh = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, 
                                     cv2.THRESH_BINARY, 11, 2)
        denoised = cv2.fastNlMeansDenoising(thresh)
        return denoised
    except Exception as e:
        logging.error(f"Error in preprocessing: {str(e)}")
        return img

def extract_features(img):
    try:
        height, width = img.shape[:2]
        mean = np.mean(img)
        std = np.std(img)
        edges = cv2.Canny(img, 100, 200)
        edge_ratio = np.sum(edges) / (height * width * 255)
        hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV)
        blue_mask = cv2.inRange(hsv, np.array([90, 40, 40]), np.array([140, 255, 255]))
        blue_ratio = np.sum(blue_mask) / (height * width * 255)
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        texture = cv2.Laplacian(gray, cv2.CV_64F).var()
        return {
            'mean': mean,
            'std': std,
            'edge_ratio': edge_ratio,
            'blue_ratio': blue_ratio,
            'texture': texture
        }
    except Exception as e:
        logging.error(f"Error in feature extraction: {str(e)}")
        return {
            'mean': 0,
            'std': 0,
            'edge_ratio': 0,
            'blue_ratio': 0,
            'texture': 0
        }

def read_image_from_base64(data):
    try:
        header, encoded = data.split(',', 1)
        img_bytes = base64.b64decode(encoded)
        img_array = np.frombuffer(img_bytes, np.uint8)
        return cv2.imdecode(img_array, cv2.IMREAD_COLOR)
    except Exception as e:
        logging.error(f"Error reading image from base64: {str(e)}")
        return None

def save_to_dataset(img, is_verified=False):
    try:
        timestamp = int(time.time())
        prefix = "verified_" if is_verified else "unverified_"
        filename = f"{prefix}payment_{timestamp}.jpg"
        filepath = os.path.join(DATASET_DIR, filename)
        cv2.imwrite(filepath, img)
        if is_verified:
            DATASET_PATHS.append(filepath)
            with open(os.path.join(DATASET_DIR, 'dataset_list.txt'), 'w') as f:
                for path in DATASET_PATHS:
                    f.write(f"{path}\n")
            logging.info(f"Saved verified payment proof: {filename}")
        return filename
    except Exception as e:
        logging.error(f"Error saving to dataset: {str(e)}")
        return None

def is_payment_proof(upload_img, manual_verify=False):
    if manual_verify:
        return True
    try:
        hsv = cv2.cvtColor(upload_img, cv2.COLOR_BGR2HSV)
        lower_blue = np.array([90, 50, 50])
        upper_blue = np.array([130, 255, 255])
        blue_mask = cv2.inRange(hsv, lower_blue, upper_blue)
        blue_ratio = np.sum(blue_mask) / (upload_img.shape[0] * upload_img.shape[1] * 255)
        processed_img = preprocess_image(upload_img)
        ocr_text = pytesseract.image_to_string(
            processed_img, 
            config='--psm 6 -l ind+eng --oem 3'
        ).lower()
        has_dana_or_bisnis = ("dana" in ocr_text) or ("bisnis" in ocr_text)
        is_valid = (blue_ratio >= 0.01) and has_dana_or_bisnis
        logging.info(f"OCR Text: {ocr_text}")
        logging.info(f"blue_ratio: {blue_ratio:.3f}, has_dana_or_bisnis: {has_dana_or_bisnis}, valid: {is_valid}")
        return is_valid
    except Exception as e:
        logging.error(f"Error in payment proof detection: {str(e)}")
        return False

@app.route('/verify-payment', methods=['POST'])
def verify_payment():
    try:
        data = request.get_json()
        if not data or 'image' not in data:
            return jsonify({'success': False, 'error': 'No image data provided'})
        image_data = data['image']
        order_id = data.get('order_id', '')
        manual_verify = data.get('manual_verify', False)
        
        # Log the order ID for debugging
        logging.info(f"Processing payment verification for order ID: {order_id}")
        
        img = read_image_from_base64(image_data)
        if img is None:
            return jsonify({'success': False, 'error': 'Invalid image data'})
        result = is_payment_proof(img, manual_verify=manual_verify)
        if result:
            filename = save_to_dataset(img, is_verified=True)
            logging.info(f"Payment verified and saved: {filename}")
            
            # If we have an order ID, update the order status in the database
            if order_id:
                try:
                    # Connect to MySQL database
                    import mysql.connector
                    db = mysql.connector.connect(
                        host="localhost",
                        user="root",
                        password="",
                        database="hijauloka"
                    )
                    cursor = db.cursor()
                    
                    # Update order status
                    cursor.execute(
                        "UPDATE orders SET stts_pembayaran = 'lunas' WHERE id_order = %s",
                        (order_id,)
                    )
                    
                    # Update transaction status
                    cursor.execute(
                        "UPDATE transaksi SET status_pembayaran = 'success' WHERE order_id = %s",
                        (order_id,)
                    )
                    
                    # Get user ID from order
                    cursor.execute(
                        "SELECT id_user FROM orders WHERE id_order = %s",
                        (order_id,)
                    )
                    result = cursor.fetchone()
                    
                    if result:
                        user_id = result[0]
                        # Clear user's cart
                        cursor.execute(
                            "DELETE FROM cart WHERE id_user = %s",
                            (user_id,)
                        )
                        logging.info(f"Cleared cart for user ID: {user_id}")
                    
                    db.commit()
                    cursor.close()
                    db.close()
                    logging.info(f"Updated order status for order ID: {order_id}")
                except Exception as e:
                    logging.error(f"Database error: {str(e)}")
        else:
            filename = save_to_dataset(img, is_verified=False)
            logging.info(f"Payment not verified, saved for review: {filename}")
        if result:
            return jsonify({
                'success': True,
                'redirect_url': '/checkout/sukses',
                'saved_as_training': True
            })
        else:
            return jsonify({'success': False})
    except Exception as e:
        logging.error(f"Error processing payment verification: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

dataset_list_path = os.path.join(DATASET_DIR, 'dataset_list.txt')
if os.path.exists(dataset_list_path):
    with open(dataset_list_path, 'r') as f:
        additional_paths = [line.strip() for line in f.readlines() if line.strip()]
        for path in additional_paths:
            if path not in DATASET_PATHS and os.path.exists(path):
                DATASET_PATHS.append(path)

if __name__ == '__main__':
    app.run(port=5000, debug=True)
