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

def is_payment_proof(upload_img, manual_verify=False):
    # Simplified matching with focus on speed
    best_score = 0
    
    # If manual verification, return true immediately
    if manual_verify:
        return True
    
    # Resize image to smaller dimensions for faster processing
    max_dimension = 480  # Even smaller for faster processing
    h, w = upload_img.shape[:2]
    if max(h, w) > max_dimension:
        scale = max_dimension / max(h, w)
        upload_img = cv2.resize(upload_img, (int(w * scale), int(h * scale)))
    
    # Quick check for DANA/QRIS app indicators - blue color detection
    try:
        # Check for blue color (DANA app color) - expanded range for better detection
        hsv = cv2.cvtColor(upload_img, cv2.COLOR_BGR2HSV)
        
        # Multiple blue ranges to catch different shades of DANA blue
        blue_ranges = [
            # DANA primary blue
            (np.array([100, 50, 50]), np.array([130, 255, 255])),
            # Lighter blue
            (np.array([90, 40, 40]), np.array([110, 255, 255])),
            # Darker blue
            (np.array([110, 50, 50]), np.array([140, 255, 255]))
        ]
        
        blue_ratio_total = 0
        for lower_blue, upper_blue in blue_ranges:
            blue_mask = cv2.inRange(hsv, lower_blue, upper_blue)
            blue_ratio = np.sum(blue_mask) / (upload_img.shape[0] * upload_img.shape[1] * 255)
            blue_ratio_total += blue_ratio
            
        if blue_ratio_total > 0.05:  # If more than 5% of the image has DANA blue colors
            print(f"DANA blue color detected: {blue_ratio_total:.2f} ratio")
            best_score += 0.3  # Boost the score more
            
            # If strong blue signal, return early
            if blue_ratio_total > 0.1:
                print(f"Strong DANA blue signal detected: {blue_ratio_total:.2f}")
                return True
    except Exception as e:
        print(f"Error in color detection: {str(e)}")
    
    # Apply preprocessing to improve matching
    upload_img_gray = cv2.cvtColor(upload_img, cv2.COLOR_BGR2GRAY)
    
    # Only process the first dataset image for speed
    if len(DATASET_PATHS) > 0:
        path = DATASET_PATHS[0]
        if not os.path.exists(path):
            print(f"Warning: Dataset file not found: {path}")
            return best_score > 0.3  # Return based on color detection only
            
        dataset_img = cv2.imread(path)
        if dataset_img is None:
            print(f"Warning: Could not read image: {path}")
            return best_score > 0.3  # Return based on color detection only
            
        # Resize for comparison
        dataset_img = cv2.resize(dataset_img, (upload_img.shape[1], upload_img.shape[0]))
        dataset_img_gray = cv2.cvtColor(dataset_img, cv2.COLOR_BGR2GRAY)
        
        # 1. Fast histogram comparison (grayscale only)
        hist1 = cv2.calcHist([upload_img_gray], [0], None, [32], [0,256])  # Even fewer bins
        hist2 = cv2.calcHist([dataset_img_gray], [0], None, [32], [0,256])
        hist_score = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
        
        # 2. Fast template matching
        result = cv2.matchTemplate(upload_img_gray, dataset_img_gray, cv2.TM_CCOEFF_NORMED)
        template_score = np.max(result)
        
        # Combine scores
        combined_score = (hist_score * 0.3) + (template_score * 0.3) + best_score
        
        print(f"Path: {path}, Hist: {hist_score:.2f}, Template: {template_score:.2f}, Combined: {combined_score:.2f}")
        
        # Lower threshold for easier detection
        return combined_score > 0.35
    
    # If no dataset images processed, return based on color detection
    return best_score > 0.25  # Lower threshold for easier detection

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
            
        # Pass manual_verify to is_payment_proof
        result = is_payment_proof(img, manual_verify=manual_verify)
        
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
                'redirect_url': '/checkout/sukses',  # URL to redirect to
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
