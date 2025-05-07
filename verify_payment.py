from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64
import os
import time
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# Path dataset
DATASET_PATHS = [
    'assets/img/dataset/buktipembayaran.jpg',
    'assets/img/dataset/buktipembayaran2.jpg', # Fixed missing comma
    'assets/img/dataset/buktipembayaran3.jpg',
    'assets/img/dataset/image.png',  # Fixed typo in filename
]

# Ensure dataset directory exists
DATASET_DIR = 'assets/img/dataset/'
os.makedirs(DATASET_DIR, exist_ok=True)

def read_image_from_base64(data):
    header, encoded = data.split(',', 1)
    img_bytes = base64.b64decode(encoded)
    img_array = np.frombuffer(img_bytes, np.uint8)
    return cv2.imdecode(img_array, cv2.IMREAD_COLOR)

def save_to_dataset(img, is_verified=False):
    """Save image to dataset folder with timestamp"""
    timestamp = int(time.time())
    prefix = "verified_" if is_verified else "unverified_"
    filename = f"{prefix}payment_{timestamp}.jpg"
    filepath = os.path.join(DATASET_DIR, filename)
    
    # Save the image
    cv2.imwrite(filepath, img)
    
    # If verified, add to dataset paths
    if is_verified:
        DATASET_PATHS.append(os.path.join(DATASET_DIR, filename))
        # Save updated dataset list to a file for persistence
        with open(os.path.join(DATASET_DIR, 'dataset_list.txt'), 'w') as f:
            for path in DATASET_PATHS:
                f.write(f"{path}\n")
    
    return filename

def is_payment_proof(upload_img):
    # Improved matching with multiple techniques for better detection
    best_score = 0
    best_path = None
    
    # Apply some preprocessing to improve matching
    # Convert to grayscale for more robust comparison
    upload_img_gray = cv2.cvtColor(upload_img, cv2.COLOR_BGR2GRAY)
    
    # Apply slight Gaussian blur to reduce noise
    upload_img_gray = cv2.GaussianBlur(upload_img_gray, (5, 5), 0)
    
    # Check for text indicators of payment success
    # This can help identify payment proofs even with limited dataset
    try:
        # Look for common text in payment receipts
        payment_keywords = ['berhasil', 'sukses', 'pembayaran', 'payment', 'dana', 'ovo', 'gopay']
        has_payment_keyword = False
        
        # We could use OCR here, but for simplicity we'll rely on other methods
        # and just note this as a potential enhancement
    except:
        pass
    
    for path in DATASET_PATHS:
        if not os.path.exists(path):
            print(f"Warning: Dataset file not found: {path}")
            continue
        dataset_img = cv2.imread(path)
        if dataset_img is None:
            print(f"Warning: Could not read image: {path}")
            continue
            
        # Resize for comparison
        dataset_img = cv2.resize(dataset_img, (upload_img.shape[1], upload_img.shape[0]))
        dataset_img_gray = cv2.cvtColor(dataset_img, cv2.COLOR_BGR2GRAY)
        dataset_img_gray = cv2.GaussianBlur(dataset_img_gray, (5, 5), 0)
        
        # 1. Multi-channel histogram comparison
        color_score = 0
        for i in range(3):  # RGB channels
            hist1 = cv2.calcHist([upload_img], [i], None, [256], [0,256])
            hist2 = cv2.calcHist([dataset_img], [i], None, [256], [0,256])
            color_score += cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
        color_score = color_score / 3
        
        # 2. Template matching on grayscale
        # This helps detect structural similarities
        result = cv2.matchTemplate(upload_img_gray, dataset_img_gray, cv2.TM_CCOEFF_NORMED)
        template_score = np.max(result)
        
        # 3. Feature matching using ORB
        # This detects specific features that might be common in payment screenshots
        try:
            orb = cv2.ORB_create()
            kp1, des1 = orb.detectAndCompute(upload_img_gray, None)
            kp2, des2 = orb.detectAndCompute(dataset_img_gray, None)
            
            # If features were found in both images
            if des1 is not None and des2 is not None and len(des1) > 0 and len(des2) > 0:
                # Create BFMatcher object
                bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)
                # Match descriptors
                matches = bf.match(des1, des2)
                
                # Calculate feature matching score based on number of good matches
                feature_score = len(matches) / max(len(kp1), len(kp2)) if max(len(kp1), len(kp2)) > 0 else 0
            else:
                feature_score = 0
        except:
            feature_score = 0
        
        # Combine scores with different weights
        combined_score = (color_score * 0.3) + (template_score * 0.4) + (feature_score * 0.3)
        
        if combined_score > best_score:
            best_score = combined_score
            best_path = path
        
        # Print scores for debugging
        print(f"Path: {path}, Color: {color_score:.2f}, Template: {template_score:.2f}, Feature: {feature_score:.2f}, Combined: {combined_score:.2f}")
        
        # Lower threshold for detection with limited dataset
        if combined_score > 0.6:
            print(f"Match found with {path}, score: {combined_score:.2f}")
            return True
    
    print(f"Best score: {best_score:.2f} with {best_path}")
    return False

@app.route('/verify-payment', methods=['POST'])
def verify_payment():
    data = request.get_json()
    image_data = data['image']
    order_id = data.get('order_id', '')  # Get order_id if available
    manual_verify = data.get('manual_verify', False)  # Check if manually verified
    
    try:
        img = read_image_from_base64(image_data)
        if img is None:
            return jsonify({'success': False, 'error': 'Invalid image data'})
            
        result = is_payment_proof(img) or manual_verify
        
        # Save the image to dataset
        if result:
            # This is a valid payment proof - save to dataset for future training
            filename = save_to_dataset(img, is_verified=True)
            print(f"Saved verified payment proof: {filename}")
        else:
            # Optionally save unverified images for review
            filename = save_to_dataset(img, is_verified=False)
            print(f"Saved unverified image: {filename}")
        
        # Return success status and redirect URL for automatic navigation
        if result:
            return jsonify({
                'success': True,
                'redirect_url': '/checkout/suksesqris.php',  # URL to redirect to
                'saved_as_training': True
            })
        else:
            return jsonify({'success': False})
    except Exception as e:
        print(f"Error processing payment verification: {str(e)}")
        return jsonify({'success': False, 'error': str(e)})

# Load existing dataset list if available
dataset_list_path = os.path.join(DATASET_DIR, 'dataset_list.txt')
if os.path.exists(dataset_list_path):
    with open(dataset_list_path, 'r') as f:
        additional_paths = [line.strip() for line in f.readlines() if line.strip()]
        for path in additional_paths:
            if path not in DATASET_PATHS and os.path.exists(path):
                DATASET_PATHS.append(path)

if __name__ == '__main__':
    app.run(port=5000, debug=True)
