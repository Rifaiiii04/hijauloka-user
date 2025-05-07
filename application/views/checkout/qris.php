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

    <div class="mt-8">
        <button id="verifyBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">Verifikasi Pembayaran (Kamera)</button>
    </div>
</div>

<!-- Modal Kamera -->
<div id="cameraModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md text-center relative">
        <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
        <h3 class="text-lg font-semibold mb-4">Ambil Foto Bukti Pembayaran</h3>
        <video id="video" width="320" height="240" autoplay class="mx-auto rounded"></video>
        <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
        <div class="mt-4">
            <button id="captureBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Ambil Foto</button>
            <button id="uploadBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 ml-2 hidden">Upload & Verifikasi</button>
        </div>
        <div id="verifyResult" class="mt-4 text-lg font-semibold"></div>
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
const cameraModal = document.getElementById('cameraModal');
const closeModal = document.getElementById('closeModal');
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureBtn = document.getElementById('captureBtn');
const uploadBtn = document.getElementById('uploadBtn');
const verifyResult = document.getElementById('verifyResult');
let stream;
let imageData;

verifyBtn.onclick = async () => {
    cameraModal.classList.remove('hidden');
    verifyResult.textContent = '';
    uploadBtn.classList.add('hidden');
    canvas.classList.add('hidden');
    video.classList.remove('hidden');
    stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
};
closeModal.onclick = () => {
    cameraModal.classList.add('hidden');
    if (stream) stream.getTracks().forEach(track => track.stop());
};
captureBtn.onclick = () => {
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    imageData = canvas.toDataURL('image/jpeg');
    canvas.classList.remove('hidden');
    video.classList.add('hidden');
    uploadBtn.classList.remove('hidden');
};
uploadBtn.onclick = async () => {
    verifyResult.textContent = 'Memverifikasi...';
    // Kirim ke backend Python
    const res = await fetch('http://localhost:5000/verify-payment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            image: imageData,
            order_id: "<?= $order['id_order'] ?>"
        })
    });
    const data = await res.json();
    if (data.success) {
        verifyResult.textContent = 'Pembayaran terverifikasi!';
        // Update status pembayaran di backend PHP
        fetch('<?= base_url('orders/mark_paid') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `order_id=<?= $order['id_order'] ?>`
        });
        setTimeout(() => window.location.href = '<?= base_url('orders') ?>', 2000);
    } else {
        verifyResult.textContent = 'Bukti pembayaran tidak valid. Coba lagi.';
    }
};
</script>

<?php $this->load->view('templates/footer'); ?> 