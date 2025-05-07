<?php $this->load->view('templates/header2'); ?>

<div class="container mx-auto max-w-lg py-12 text-center">
    <h2 class="text-2xl font-bold text-green-800 mb-4">Pembayaran DANA/QRIS</h2>
    <p class="mb-4 text-gray-700">Silakan scan QRIS di bawah ini menggunakan aplikasi DANA, OVO, GoPay, atau aplikasi pembayaran lain yang mendukung QRIS.</p>
    <div class="flex justify-center mb-6">
        <img src="<?= base_url('assets/img/dana.jpg') ?>" alt="QRIS DANA" class="rounded-lg shadow-lg w-72 h-72 object-contain border-4 border-blue-200">
    </div>
    <div class="mb-4">
        <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded text-lg font-semibold">
            Selesaikan pembayaran dalam <span id="timer">10:00</span>
        </span>
    </div>
    <p class="text-gray-600 mb-6">Setelah pembayaran, pesanan Anda akan diproses secara otomatis.</p>
    <a href="<?= base_url('orders') ?>" class="inline-block mt-4 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">Cek Status Pesanan</a>

    <div class="mt-8 flex flex-col items-center space-y-3">
        <button id="verifyBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold w-64">
            Verifikasi dengan Kamera
        </button>
        
        <div class="flex items-center">
            <span class="text-gray-600 mx-2">atau</span>
        </div>
        
        <button id="fileUpload" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold cursor-pointer w-64 text-center">
            Upload Bukti Pembayaran
        </button>
        <!-- Remove the hidden input as we'll use the one in the modal -->
    </div>

    <!-- Modal Kamera -->
    <div id="cameraModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl text-center relative">
            <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-semibold mb-4" id="modalTitle">Ambil Foto Bukti Pembayaran</h3>
            
            <!-- Camera view (will be hidden for file upload) -->
            <div id="cameraView">
                <video id="video" width="480" height="360" autoplay class="mx-auto rounded border-2 border-gray-300"></video>
                <div class="mt-4 flex justify-center space-x-3">
                    <button id="captureBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Ambil Foto Manual</button>
                    <button id="autoDetectBtn" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Mulai Deteksi Otomatis</button>
                </div>
                <div id="autoDetectStatus" class="mt-2 text-sm text-gray-600 hidden">
                    Mendeteksi bukti pembayaran secara otomatis... <span class="inline-block animate-pulse">⚡</span>
                </div>
            </div>
            
            <!-- File preview (for uploaded files) -->
            <div id="filePreview" class="hidden">
                <div class="mb-4">
                    <label for="modalFileUpload" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold cursor-pointer inline-block">
                        Pilih File Bukti Pembayaran
                    </label>
                    <input type="file" id="modalFileUpload" accept="image/*" class="hidden">
                </div>
                <div id="previewContainer" class="hidden">
                    <img id="previewImage" class="mx-auto rounded border-2 border-gray-300 max-w-full max-h-[360px] object-contain" />
                    <p class="mt-2 text-sm text-gray-600">File yang dipilih: <span id="fileName">-</span></p>
                </div>
            </div>
            
            <!-- Shared elements -->
            <canvas id="canvas" width="480" height="360" class="hidden"></canvas>
            <div class="mt-4">
                <button id="uploadBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold hidden">
                    Verifikasi Pembayaran
                </button>
            </div>
            
            <div id="verifyResult" class="mt-4 text-lg font-semibold"></div>
            
            <!-- Manual verification option (for admin or when auto-detection fails) -->
            <div id="manualVerifyOption" class="mt-4 hidden">
                <p class="text-sm text-gray-600 mb-2">Jika bukti pembayaran valid tapi tidak terdeteksi:</p>
                <button id="manualVerifyBtn" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Verifikasi Manual & Simpan sebagai Training
                </button>
            </div>
        </div>
    </div>

    <script>
    // Countdown timer 10 menit
    let timeLeft = 600; // 10 menit dalam detik
    const timerEl = document.getElementById('timer');
    const interval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        if (timeLeft <= 0) {
            clearInterval(interval);
            timerEl.textContent = '00:00';
            alert('Waktu pembayaran telah habis. Silakan lakukan pemesanan ulang.');
            window.location.href = '<?= base_url('orders') ?>';
        }
        timeLeft--;
    }, 1000);
    
    // Kamera & Verifikasi
    const verifyBtn = document.getElementById('verifyBtn');
    const fileUpload = document.getElementById('fileUpload');
    const modalFileUpload = document.getElementById('modalFileUpload');
    const cameraModal = document.getElementById('cameraModal');
    const closeModal = document.getElementById('closeModal');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const uploadBtn = document.getElementById('uploadBtn');
    const autoDetectBtn = document.getElementById('autoDetectBtn');
    const autoDetectStatus = document.getElementById('autoDetectStatus');
    const verifyResult = document.getElementById('verifyResult');
    const cameraView = document.getElementById('cameraView');
    const filePreview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const previewContainer = document.getElementById('previewContainer');
    const fileName = document.getElementById('fileName');
    const modalTitle = document.getElementById('modalTitle');
    let stream;
    let imageData;
    let autoDetectInterval;
    let isAutoDetecting = false;
    let isFileUpload = false;
    
    // Camera button click
    verifyBtn.onclick = async () => {
        isFileUpload = false;
        modalTitle.textContent = 'Ambil Foto Bukti Pembayaran';
        cameraView.classList.remove('hidden');
        filePreview.classList.add('hidden');
        previewContainer.classList.add('hidden');
        
        openCameraModal();
    };
    
    // File upload button click - just open modal
    fileUpload.onclick = (e) => {
        e.preventDefault(); // Prevent default behavior
        isFileUpload = true;
        modalTitle.textContent = 'Upload Bukti Pembayaran';
        cameraView.classList.add('hidden');
        filePreview.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        uploadBtn.classList.add('hidden');
        
        openCameraModal();
    };
    
    // Modal file upload handling
    modalFileUpload.onchange = (e) => {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            fileName.textContent = file.name;
            
            const reader = new FileReader();
            reader.onload = (event) => {
                previewImage.src = event.target.result;
                imageData = event.target.result;
                
                // Show the preview and verify button
                previewContainer.classList.remove('hidden');
                uploadBtn.classList.remove('hidden');
                uploadBtn.textContent = 'Verifikasi Pembayaran';
            };
            reader.readAsDataURL(file);
        }
    };
    
    function openCameraModal() {
        cameraModal.classList.remove('hidden');
        verifyResult.textContent = '';
        verifyResult.classList.remove('text-green-600');
        
        // Show or hide upload button based on context
        if (isFileUpload) {
            uploadBtn.classList.remove('hidden');
        } else {
            uploadBtn.classList.add('hidden');
        }
        
        canvas.classList.add('hidden');
        document.getElementById('manualVerifyOption').classList.add('hidden');
        autoDetectStatus.classList.add('hidden');
        
        if (!isFileUpload) {
            // Only start camera if not file upload
            startCamera();
        }
    }
    
    async function startCamera() {
        try {
            // Request camera with higher resolution
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'environment' // Prefer back camera on mobile
                } 
            });
            video.srcObject = stream;
            video.classList.remove('hidden');
        } catch (err) {
            verifyResult.textContent = 'Error accessing camera: ' + err.message;
        }
    }
    
    closeModal.onclick = () => {
        cameraModal.classList.add('hidden');
        stopAutoDetection();
        if (stream) stream.getTracks().forEach(track => track.stop());
        // Reset file input
        fileUpload.value = '';
    };
    
    captureBtn.onclick = () => {
        stopAutoDetection();
        captureImage();
    };
    
    function captureImage() {
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        imageData = canvas.toDataURL('image/jpeg');
        canvas.classList.remove('hidden');
        video.classList.add('hidden');
        uploadBtn.textContent = 'Verifikasi Pembayaran';
        uploadBtn.classList.remove('hidden');
        autoDetectBtn.classList.add('hidden');
    }
    
    uploadBtn.onclick = async () => {
        // Reset manual verify option
        document.getElementById('manualVerifyOption').classList.add('hidden');
        await verifyPaymentImage(false);
    };
    
    // Auto detection functionality
    autoDetectBtn.onclick = () => {
        if (isAutoDetecting) {
            stopAutoDetection();
        } else {
            startAutoDetection();
        }
    };
    
    function startAutoDetection() {
        isAutoDetecting = true;
        autoDetectStatus.classList.remove('hidden');
        autoDetectBtn.textContent = 'Hentikan Deteksi Otomatis';
        autoDetectBtn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
        autoDetectBtn.classList.add('bg-red-600', 'hover:bg-red-700');
        
        // Start auto detection interval
        autoDetectInterval = setInterval(() => {
            // Capture current frame
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = video.videoWidth;
            tempCanvas.height = video.videoHeight;
            tempCanvas.getContext('2d').drawImage(video, 0, 0, tempCanvas.width, tempCanvas.height);
            const frameData = tempCanvas.toDataURL('image/jpeg');
            
            // Send for verification
            verifyFrame(frameData);
        }, 2000); // Check every 2 seconds
    }
    
    function stopAutoDetection() {
        if (autoDetectInterval) {
            clearInterval(autoDetectInterval);
        }
        isAutoDetecting = false;
        autoDetectStatus.classList.add('hidden');
        autoDetectBtn.textContent = 'Mulai Deteksi Otomatis';
        autoDetectBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
        autoDetectBtn.classList.add('bg-purple-600', 'hover:bg-purple-700');
    }
    
    async function verifyFrame(frameData) {
        try {
            const res = await fetch('http://localhost:5000/verify-payment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    image: frameData,
                    order_id: "<?= $order['id_order'] ?>"
                })
            });
            
            const data = await res.json();
            if (data.success) {
                // Payment detected! Stop auto detection and process
                stopAutoDetection();
                imageData = frameData;
                captureImage(); // Save the successful frame
                await processSuccessfulPayment();
            }
        } catch (error) {
            console.error('Auto detection error:', error);
        }
    }
    
    // Update verifyPaymentImage to handle both camera and file uploads
    async function verifyPaymentImage(manualVerify = false) {
        verifyResult.textContent = 'Memverifikasi...';
        try {
            // Kirim ke backend Python
            const res = await fetch('http://localhost:5000/verify-payment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    image: imageData,
                    order_id: "<?= $order['id_order'] ?>",
                    manual_verify: manualVerify
                })
            });
            
            const data = await res.json();
            if (data.success) {
                let successMessage = 'Pembayaran terverifikasi!';
                if (data.saved_as_training) {
                    successMessage += ' Bukti pembayaran disimpan untuk training.';
                }
                verifyResult.textContent = successMessage;
                await processSuccessfulPayment();
            } else {
                verifyResult.textContent = 'Bukti pembayaran tidak valid. Coba lagi.';
                // Show manual verification option
                document.getElementById('manualVerifyOption').classList.remove('hidden');
                
                if (!isFileUpload) {
                    // Only reset to camera view if not file upload
                    canvas.classList.add('hidden');
                    video.classList.remove('hidden');
                    uploadBtn.classList.add('hidden');
                    autoDetectBtn.classList.remove('hidden');
                }
            }
        } catch (error) {
            verifyResult.textContent = 'Error: ' + error.message;
        }
    }
    
    // Add event listener for manual verification button
    document.getElementById('manualVerifyBtn').addEventListener('click', async () => {
        // Capture current frame if not already captured
        if (!imageData) {
            captureImage();
        }
        // Process with manual verification flag
        await verifyPaymentImage(true);
    });
    
    async function processSuccessfulPayment() {
        verifyResult.textContent = 'Pembayaran terverifikasi!';
        verifyResult.classList.add('text-green-600');
        
        // Update status pembayaran di backend PHP
        try {
            await fetch('<?= base_url('orders/mark_paid') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `order_id=<?= $order['id_order'] ?>`
            });
            
            // Automatic navigation to success page
            setTimeout(() => window.location.href = '<?= base_url('checkout/suksesqris') ?>', 1500);
        } catch (error) {
            console.error('Error updating payment status:', error);
        }
    }
    </script>
    
    <?php $this->load->view('templates/footer'); ?>