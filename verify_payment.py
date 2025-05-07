from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64
import os
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# Path dataset
DATASET_PATHS = [
    'assets/img/dataset/buktipembayaran.jpg',
    'assets/img/dataset/buktipembayaran2.jpg' 
    'assets/img/dataset/buktipembayaran3.jpg' 
]

def read_image_from_base64(data):
    header, encoded = data.split(',', 1)
    img_bytes = base64.b64decode(encoded)
    img_array = np.frombuffer(img_bytes, np.uint8)
    return cv2.imdecode(img_array, cv2.IMREAD_COLOR)

def is_payment_proof(upload_img):
    # Sederhana: cocokkan histogram dengan dataset
    for path in DATASET_PATHS:
        if not os.path.exists(path):
            continue
        dataset_img = cv2.imread(path)
        dataset_img = cv2.resize(dataset_img, (upload_img.shape[1], upload_img.shape[0]))
        # Histogram comparison
        hist1 = cv2.calcHist([upload_img], [0], None, [256], [0,256])
        hist2 = cv2.calcHist([dataset_img], [0], None, [256], [0,256])
        score = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
        if score > 0.85:  # threshold, bisa diatur
            return True
    return False

@app.route('/verify-payment', methods=['POST'])
def verify_payment():
    data = request.get_json()
    image_data = data['image']
    img = read_image_from_base64(image_data)
    if is_payment_proof(img):
        return jsonify({'success': True})
    else:
        return jsonify({'success': False})

if __name__ == '__main__':
    app.run(port=5000, debug=True)
