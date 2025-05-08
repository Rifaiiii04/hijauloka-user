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

def save_to_dataset(img, is_verified=False, order_id=None, user_id=None):
    try:
        timestamp = int(time.time())
        date_str = datetime.fromtimestamp(timestamp).strftime('%Y%m%d')
        
        # Create uploads/bukti directory if it doesn't exist
        uploads_dir = 'uploads/bukti'
        os.makedirs(uploads_dir, exist_ok=True)
        
        # Create more descriptive filename with order and user info if available
        prefix = "verified_" if is_verified else "unverified_"
        order_info = f"order_{order_id}_" if order_id else ""
        user_info = f"user_{user_id}_" if user_id else ""
        filename = f"{prefix}{order_info}{user_info}payment_{timestamp}.jpg"
        
        # Save to uploads/bukti folder for web access
        filepath = os.path.join(uploads_dir, filename)
        cv2.imwrite(filepath, img)
        
        # Also save to dataset folder for training purposes
        dataset_date_dir = os.path.join(DATASET_DIR, date_str)
        os.makedirs(dataset_date_dir, exist_ok=True)
        
        status_dir = os.path.join(dataset_date_dir, "verified" if is_verified else "unverified")
        os.makedirs(status_dir, exist_ok=True)
        
        dataset_filepath = os.path.join(status_dir, filename)
        cv2.imwrite(dataset_filepath, img)
        
        # Save metadata in JSON format for better tracking
        metadata_file = os.path.join(status_dir, f"{os.path.splitext(filename)[0]}.json")
        metadata = {
            "timestamp": timestamp,
            "datetime": datetime.fromtimestamp(timestamp).strftime('%Y-%m-%d %H:%M:%S'),
            "is_verified": is_verified,
            "order_id": order_id,
            "user_id": user_id,
            "image_path": filepath,
            "dataset_path": dataset_filepath
        }
        
        with open(metadata_file, 'w') as f:
            import json
            json.dump(metadata, f, indent=4)
        
        if is_verified:
            DATASET_PATHS.append(dataset_filepath)
            # Update the dataset list file with all verified images
            with open(os.path.join(DATASET_DIR, 'dataset_list.txt'), 'w') as f:
                for path in DATASET_PATHS:
                    f.write(f"{path}\n")
            logging.info(f"Saved verified payment proof: {filepath}")
        
        return filepath
    except Exception as e:
        logging.error(f"Error saving to dataset: {str(e)}")
        return None

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
        
        # Save the image first to get the filepath
        user_id = None
        filename = None
        
        # Save the image to dataset with order and user info
        if manual_verify:
            filename = save_to_dataset(img, is_verified=True, order_id=order_id, user_id=user_id)
            logging.info(f"Payment manually verified and saved: {filename}")
            result = True
        else:
            result = is_payment_proof(img, manual_verify=manual_verify)
            if result:
                filename = save_to_dataset(img, is_verified=True, order_id=order_id, user_id=user_id)
                logging.info(f"Payment verified and saved: {filename}")
            else:
                filename = save_to_dataset(img, is_verified=False, order_id=order_id, user_id=user_id)
                logging.info(f"Payment not verified, saved for review: {filename}")
        
        # If we have an order ID, update the order status in the database
        if result and order_id:
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
                
                # Get user ID from order
                cursor.execute(
                    "SELECT id_user FROM orders WHERE id_order = %s",
                    (order_id,)
                )
                user_result = cursor.fetchone()
                
                if user_result:
                    user_id = user_result[0]
                    # Clear user's cart
                    cursor.execute(
                        "DELETE FROM cart WHERE id_user = %s",
                        (user_id,)
                    )
                    logging.info(f"Cleared cart for user ID: {user_id}")
                
                # Update the filename with user_id if we have it now
                if user_id and filename:
                    new_filename = filename.replace("user_None_", f"user_{user_id}_")
                    if new_filename != filename:
                        import os
                        if os.path.exists(filename):
                            os.rename(filename, new_filename)
                            filename = new_filename
                            logging.info(f"Renamed file to include user ID: {filename}")
                
                # Update order status
                cursor.execute(
                    "UPDATE orders SET stts_pembayaran = 'lunas' WHERE id_order = %s",
                    (order_id,)
                )
                
                # Update transaction status and payment proof
                # Use the correct path format for the database
                if filename:
                    # Extract just the filename without the full path
                    just_filename = os.path.basename(filename)
                    # Create the web path that will be stored in the database
                    web_path = f"bukti/{just_filename}"
                    logging.info(f"Saving payment proof path to database: {web_path}")
                else:
                    web_path = None
                    
                cursor.execute(
                    "UPDATE transaksi SET status_pembayaran = 'success', bukti_pembayaran = %s WHERE order_id = %s",
                    (web_path, order_id)
                )
                
                # Save payment details to payment_proofs table
                cursor.execute(
                    """
                    INSERT INTO payment_proofs 
                    (order_id, user_id, verification_time, is_verified, verification_method, image_path) 
                    VALUES (%s, %s, %s, %s, %s, %s)
                    """,
                    (
                        order_id, 
                        user_id, 
                        datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
                        True,
                        'manual' if manual_verify else 'automatic',
                        web_path  # Store the web-friendly path
                    )
                )
                
                db.commit()
                cursor.close()
                db.close()
                logging.info(f"Updated order status and saved payment proof for order ID: {order_id}")
            except Exception as e:
                logging.error(f"Database error: {str(e)}")
        
        if result:
            return jsonify({
                'success': True,
                'redirect_url': '/checkout/sukses',
                'saved_as_training': True,
                'payment_proof_path': filename
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

def is_payment_proof(upload_img, manual_verify=False):
    # If manually verified, return true immediately
    if manual_verify:
        logging.info("Payment manually verified")
        return True
        
    try:
        # Extract features for detection
        features = extract_features(upload_img)
        blue_ratio = features['blue_ratio']
        
        # Process image for OCR
        processed_img = preprocess_image(upload_img)
        
        # Run OCR to detect text
        ocr_text = pytesseract.image_to_string(
            processed_img, 
            config='--psm 6 -l ind+eng --oem 3'
        ).lower()
        
        # Look for keywords related to DANA or payment
        payment_keywords = ["dana", "bisnis", "pembayaran", "berhasil", "sukses", 
                           "transaksi", "payment", "qris", "total", "bayar", 
                           "transfer", "diterima", "id", "detail", "penerima"]
        
        # Count how many keywords are found
        keyword_count = sum(1 for keyword in payment_keywords if keyword in ocr_text)
        
        # We're not checking for amount pattern anymore as per requirement
        # Just check for date/time pattern
        date_pattern = re.search(r'\d{1,2}[/-]\d{1,2}[/-]\d{2,4}|\d{1,2}:\d{2}', ocr_text)
        has_date_or_time = bool(date_pattern)
        
        # Calculate confidence score
        confidence = 0
        
        # Lower the blue ratio threshold to be more lenient
        if blue_ratio >= 0.001:  # Further reduced threshold
            confidence += 0.3
            logging.info(f"Blue ratio detected: {blue_ratio:.3f}")
        
        # Make keyword detection more lenient
        if keyword_count >= 1:
            confidence += 0.4 * min(keyword_count / 4, 1.0)  # Increased weight since we're not using amount
            logging.info(f"Found {keyword_count} payment keywords")
            
        if has_date_or_time:
            confidence += 0.2  # Increased weight since we're not using amount
            logging.info(f"Date/time pattern detected: {date_pattern.group(0)}")
        
        # Special case: If "dana" is explicitly found, boost confidence
        if "dana" in ocr_text:
            confidence += 0.3  # Increased boost
            logging.info("DANA keyword explicitly found")
            
        # Log detailed information for debugging
        logging.info(f"OCR Text: {ocr_text}")
        logging.info(f"Confidence score: {confidence:.2f}")
        
        # Lower the confidence threshold to be more lenient
        is_valid = confidence >= 0.4  # Reduced from 0.5
        
        # Fallback to the original simple check that worked before
        has_dana_or_bisnis = ("dana" in ocr_text) or ("bisnis" in ocr_text)
        if has_dana_or_bisnis and blue_ratio >= 0.005:
            is_valid = True
            logging.info("Using fallback detection method - found DANA/BISNIS with sufficient blue ratio")
        
        if is_valid:
            logging.info("Payment proof detected successfully")
        else:
            logging.info("Not a valid payment proof")
            
        return is_valid
        
    except Exception as e:
        logging.error(f"Error in payment proof detection: {str(e)}")
        return False

if __name__ == '__main__':
    app.run(port=5000, debug=True)
