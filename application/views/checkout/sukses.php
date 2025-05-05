<?php $this->load->view('templates/header2'); ?>
<div class="container mx-auto max-w-md py-16 flex flex-col items-center justify-center min-h-[60vh]">
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <div class="flex justify-center mb-4">
            <i class="fas fa-check-circle text-green-500 text-6xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-green-800 mb-2">Pesanan Berhasil Dibuat!</h2>
        <p class="text-gray-700 mb-4">Terima kasih telah berbelanja di <span class="font-semibold text-green-700">HijauLoka</span>.<br>Pesanan Anda sedang diproses.</p>
        <div class="bg-green-50 rounded-lg p-4 mb-4">
            <div class="text-green-700 font-semibold mb-1">Ringkasan Pesanan</div>
            <ul class="text-left text-sm text-gray-700 space-y-1">
                <li><span class="font-medium">Tanggal:</span> <?= date('d M Y, H:i') ?></li>
                <li><span class="font-medium">Status:</span> <span class="text-green-600">Berhasil</span></li>
                <!-- Tambahkan info lain jika perlu -->
            </ul>
        </div>
        <div class="flex flex-col gap-3 mt-6">
            <a href="<?= base_url() ?>" class="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <a href="<?= base_url('orders') ?>" class="w-full py-2 bg-gray-100 text-green-700 rounded-lg hover:bg-gray-200 font-semibold transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-list"></i> Lihat Pesanan Saya
            </a>
        </div>
    </div>
</div>
<?php $this->load->view('templates/footer'); ?> 