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
</script>

<?php $this->load->view('templates/footer'); ?> 