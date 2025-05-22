<?php $this->load->view('templates/header'); ?>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-6">
            <i class="fas fa-check-circle text-5xl text-green-600 mb-4"></i>
            <h1 class="text-2xl font-bold text-gray-800">Pesanan Berhasil!</h1>
            <p class="text-gray-600 mt-2">Terima kasih telah berbelanja di Hijauloka</p>
        </div>
        
        <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                <p><?= $this->session->flashdata('success') ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                <p><?= $this->session->flashdata('error') ?></p>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-8">
            <p class="text-gray-600 mb-4">Status pembayaran Anda akan diperbarui secara otomatis</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= base_url('orders') ?>" class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 transition-colors">
                    Lihat Pesanan Saya
                </a>
                <a href="<?= base_url() ?>" class="bg-gray-200 text-gray-700 py-2 px-6 rounded-lg hover:bg-gray-300 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>