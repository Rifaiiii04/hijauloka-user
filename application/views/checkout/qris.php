<?php $this->load->view('templates/header2'); ?>

<style>
@keyframes plant-bounce {
  0%, 100% { transform: translateY(0);}
  50% { transform: translateY(-10px);}
}
#successModal svg {
  animation: plant-bounce 1.2s infinite;
}
</style>


<div class="container mx-auto max-w-lg py-12 text-center">
    <h2 class="text-2xl font-bold text-green-800 mb-4">Pembayaran DANA/QRIS</h2>
    <p class="mb-4 text-gray-700">Silakan scan QRIS di bawah ini menggunakan aplikasi DANA, OVO, GoPay, atau aplikasi pembayaran lain yang mendukung QRIS.</p>
    
    <!-- QRIS Display -->
    <div class="flex justify-center mb-6">
        <img src="<?= base_url('assets/img/dana.jpg') ?>" alt="QRIS DANA" class="rounded-lg shadow-lg w-72 h-72 object-contain border-4 border-blue-200">
    </div>

    <!-- Timer -->
    <div class="mb-6">
        <div class="inline-flex items-center space-x-2 bg-yellow-100 px-4 py-2 rounded-full">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-yellow-800 font-semibold">Selesaikan dalam <span id="timer" class="text-red-600">10:00</span></span>
        </div>
    </div>

    <p class="text-gray-600 mb-6">Setelah pembayaran, pesanan Anda akan diproses secara otomatis.</p>

    <!-- Payment Verification Buttons -->
    <div class="mt-8 flex flex-col items-center space-y-4">
        <button id="verifyBtn" class="group relative px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold w-64 transition-all duration-300 transform hover:scale-105">
            <span class="flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Verifikasi dengan Kamera
            </span>
        </button>
        <div class="flex items-center">
            <span class="text-gray-600 mx-2">atau</span>
        </div>
        <button id="fileUpload" class="group relative px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold w-64 transition-all duration-300 transform hover:scale-105">
            <span class="flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Upload Bukti Pembayaran
            </span>
        </button>
    </div>

    <!-- Enhanced Camera Modal -->
    <div id="cameraModal" class="fixed inset-0 backdrop-blur-sm bg-white/30 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl mx-4 relative">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h3 class="text-xl font-semibold mb-4 text-center" id="modalTitle">Ambil Foto Bukti Pembayaran</h3>
            <!-- Camera View -->
            <div id="cameraView" class="space-y-4">
                <div class="relative mx-auto">
                    <video id="video" class="w-full h-auto rounded-lg border-2 border-gray-300" autoplay playsinline></video>
                    <!-- Overlay garis kotak dan titik-titik -->
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <svg width="100%" height="100%" viewBox="0 0 400 300" style="position:absolute;top:0;left:0;">
                            <!-- Kotak guideline -->
                            <rect x="60" y="40" width="280" height="220" rx="16" fill="none" stroke="#2563eb" stroke-width="3" stroke-dasharray="10,10"/>
                            <!-- Titik-titik di sudut -->
                            <circle cx="60" cy="40" r="5" fill="#2563eb"/>
                            <circle cx="340" cy="40" r="5" fill="#2563eb"/>
                            <circle cx="60" cy="260" r="5" fill="#2563eb"/>
                            <circle cx="340" cy="260" r="5" fill="#2563eb"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-blue-700 mt-2">Pastikan bukti pembayaran berada di dalam kotak biru</div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">Instruksi Pengambilan Foto:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Pastikan layar HP dalam posisi tegak</li>
                        <li>• Pastikan tulisan dan nominal terlihat jelas</li>
                        <li>• Hindari pantulan cahaya pada layar</li>
                        <li>• Jaga jarak kamera agar tidak terlalu dekat/terlalu jauh</li>
                    </ul>
                </div>
                <div class="flex justify-center space-x-4">
                    <button id="captureBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-all duration-300 transform hover:scale-105">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ambil Foto
                        </span>
                    </button>
                </div>
            </div>
            <!-- Photo Preview -->
            <div id="photoPreview" class="hidden space-y-4">
                <div class="relative flex justify-center">
                    <img id="previewImageManual" class="rounded-lg border-2 border-gray-300 mx-auto my-4 w-64 h-auto object-contain" />
                    <div class="absolute inset-0 border-2 border-dashed border-green-400 rounded-lg pointer-events-none"></div>
                </div>
                <div class="flex justify-center space-x-4">
                    <button id="retakeBtn" class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold transition-all duration-300 transform hover:scale-105">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Ambil Ulang
                        </span>
                    </button>
                    <button id="verifyBtnManual" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-all duration-300 transform hover:scale-105">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Verifikasi
                        </span>
                    </button>
                </div>
            </div>
            <!-- Verification Result -->
            <div id="verifyResult" class="mt-4 text-center font-semibold"></div>
            <!-- Manual Verification Option -->
            <div id="manualVerifyOption" class="mt-4 hidden">
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-yellow-800 mb-2">Jika bukti pembayaran valid tapi tidak terdeteksi:</p>
                    <button id="manualVerifyBtn" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold transition-all duration-300 transform hover:scale-105">
                        Verifikasi Manual
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loader Animasi Teman Tanaman -->
    <div id="successLoader" class="hidden flex flex-col items-center justify-center py-8">
        <img src="<?= base_url('assets/img/plant_loader.gif') ?>" alt="Loading..." class="w-32 h-32 mb-4">
        <div class="text-green-700 font-bold text-lg">Pembayaran Berhasil! Pesanan Anda sedang diproses...</div>
    </div>

    <!-- Modal Loader Verifikasi Berhasil -->
    <div id="successModal" class="fixed inset-0 backdrop-blur-sm bg-white/30 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl px-8 py-10 flex flex-col items-center relative animate__animated animate__fadeInDown">
            <!-- SVG Animasi Tanaman Hias -->
            <div class="mb-4">
                <!-- Contoh SVG tanaman, bisa diganti dengan SVG lain atau GIF -->
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none">
                    <ellipse cx="50" cy="90" rx="30" ry="8" fill="#A3D9A5"/>
                    <rect x="40" y="60" width="20" height="30" rx="8" fill="#7BC47F"/>
                    <path d="M50 60 Q45 40 30 50" stroke="#4F8A4B" stroke-width="4" fill="none"/>
                    <path d="M50 60 Q55 35 70 55" stroke="#4F8A4B" stroke-width="4" fill="none"/>
                    <circle cx="30" cy="50" r="6" fill="#A3D9A5"/>
                    <circle cx="70" cy="55" r="7" fill="#A3D9A5"/>
                </svg>
            </div>
            <div class="text-green-700 font-bold text-xl mb-2">Pembayaran Berhasil!</div>
            <div class="text-gray-600 mb-4">Pesanan Anda sedang diproses oleh sistem.</div>
            <div class="loader-plant mb-2"></div>
        </div>
    </div>

    <script>
    // Countdown timer with better formatting
    let timeLeft = 600;
    const timerEl = document.getElementById('timer');
    const interval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 60) { // Last minute warning
            timerEl.classList.add('animate-pulse');
        }
        
        if (timeLeft <= 0) {
            clearInterval(interval);
            timerEl.textContent = '00:00';
            Swal.fire({
                title: 'Waktu Habis!',
                text: 'Waktu pembayaran telah habis. Silakan lakukan pemesanan ulang.',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '<?= base_url('orders') ?>';
            });
        }
        timeLeft--;
    }, 1000);
    
    // Enhanced camera handling
    const verifyBtn = document.getElementById('verifyBtn');
    const fileUpload = document.getElementById('fileUpload');
    const cameraModal = document.getElementById('cameraModal');
    const closeModal = document.getElementById('closeModal');
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const verifyResult = document.getElementById('verifyResult');
    const cameraView = document.getElementById('cameraView');
    const photoPreview = document.getElementById('photoPreview');
    const previewImageManual = document.getElementById('previewImageManual');
    const retakeBtn = document.getElementById('retakeBtn');
    const verifyBtnManual = document.getElementById('verifyBtnManual');
    const manualVerifyOption = document.getElementById('manualVerifyOption');
    
    let stream;
    let imageData;
    
    // Camera button click
    verifyBtn.onclick = async () => {
        openCameraModal();
        await startCamera();
    };
    
    // File upload button click
    fileUpload.onclick = () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    imageData = event.target.result;
                    openCameraModal();
                    showPreview(imageData);
                };
                reader.readAsDataURL(file);
            }
        };
        input.click();
    };
    
    function openCameraModal() {
        cameraModal.classList.remove('hidden');
        verifyResult.textContent = '';
        verifyResult.classList.remove('text-green-600', 'text-red-600');
        cameraView.classList.remove('hidden');
        photoPreview.classList.add('hidden');
        manualVerifyOption.classList.add('hidden');
    }
    
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'environment'
                } 
            });
            video.srcObject = stream;
        } catch (err) {
            showError('Error mengakses kamera: ' + err.message);
        }
    }
    
    function showPreview(data) {
        previewImageManual.src = data;
        cameraView.classList.add('hidden');
        photoPreview.classList.remove('hidden');
    }
    
    closeModal.onclick = () => {
        cameraModal.classList.add('hidden');
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    };
    
    captureBtn.onclick = () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        imageData = canvas.toDataURL('image/jpeg', 0.8);
        showPreview(imageData);
    };
    
    retakeBtn.onclick = () => {
        cameraView.classList.remove('hidden');
        photoPreview.classList.add('hidden');
    };
    
    verifyBtnManual.onclick = async () => {
        verifyResult.textContent = 'Memverifikasi...';
        verifyResult.classList.add('animate-pulse');
        verifyBtnManual.disabled = true;
        retakeBtn.disabled = true;
        
        try {
            const res = await fetch('http://localhost:5000/verify-payment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    image: imageData,
                    order_id: "<?= $order['id_order'] ?>"
                })
            });
            
            const data = await res.json();
            verifyResult.classList.remove('animate-pulse');
            verifyBtnManual.disabled = false;
            retakeBtn.disabled = false;
            
            if (data.success) {
                // Sembunyikan preview, tampilkan modal loader tanaman
                photoPreview.classList.add('hidden');
                document.getElementById('successLoader').classList.add('hidden');
                document.getElementById('successModal').classList.remove('hidden');
                // Redirect otomatis setelah 2.5 detik
                setTimeout(() => {
                    window.location.href = '<?= base_url('checkout/sukses') ?>';
                }, 2500);
            } else {
                verifyResult.textContent = 'Bukti pembayaran tidak valid. Silakan ulangi foto.';
                verifyResult.classList.add('text-red-600');
                manualVerifyOption.classList.remove('hidden');
            }
        } catch (error) {
            verifyResult.classList.remove('animate-pulse');
            verifyBtnManual.disabled = false;
            retakeBtn.disabled = false;
            showError('Error: ' + error.message);
        }
    };
    
    function showError(message) {
        Swal.fire({
            title: 'Error!',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
    </script>
</div>