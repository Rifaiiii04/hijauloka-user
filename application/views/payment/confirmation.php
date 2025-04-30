<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Konfirmasi Pembayaran</h1>
        <p class="text-sm text-gray-600">Pesanan #<?= $order['id_order'] ?></p>
    </div>

    <?php if($this->session->flashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p><?= $this->session->flashdata('error') ?></p>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold">Detail Pesanan</h2>
                <p class="text-sm text-gray-500">Tanggal: <?= date('d M Y H:i', strtotime($order['tgl_pemesanan'])) ?></p>
            </div>
            <div class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                <?= $order['stts_pemesanan'] ?>
            </div>
        </div>

        <div class="border-t border-b border-gray-200 py-4 mb-4">
            <div class="flex justify-between mb-2">
                <span class="text-sm text-gray-600">Total Pembayaran</span>
                <span class="text-base font-medium">Rp<?= number_format($order['total_harga'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Status Pembayaran</span>
                <span class="text-sm font-medium <?= ($order['stts_pembayaran'] == 'Sudah Dibayar') ? 'text-green-600' : 'text-yellow-600' ?>">
                    <?= $order['stts_pembayaran'] ?>
                </span>
            </div>
        </div>

        <?php if($payment['payment_method'] != 'cod'): ?>
            <?php if(in_array($order['stts_pembayaran'], ['Menunggu Pembayaran'])): ?>
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-base font-medium mb-4">Instruksi Pembayaran</h3>
                    
                    <?php if($payment['payment_method'] == 'dana'): ?>
                        <div class="text-center mb-4">
                            <img src="<?= base_url('assets/images/dana-logo.png') ?>" alt="DANA" class="h-12 mx-auto mb-2">
                            <p class="text-sm font-medium">DANA</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
                            <p class="text-sm text-gray-700 mb-2">Silakan transfer ke nomor DANA berikut:</p>
                            <div class="flex items-center justify-between bg-gray-100 p-3 rounded">
                                <span class="text-base font-medium">085123456789</span>
                                <button onclick="copyToClipboard('085123456789')" class="text-green-600 text-sm hover:text-green-700">
                                    <i class="fas fa-copy"></i> Salin
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">a/n HijauLoka Official</p>
                        </div>
                        
                    <?php elseif($payment['payment_method'] == 'qris'): ?>
                        <div class="text-center mb-4">
                            <img src="<?= base_url('assets/images/qris-logo.png') ?>" alt="QRIS" class="h-12 mx-auto mb-2">
                            <p class="text-sm font-medium">QRIS</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4 text-center">
                            <p class="text-sm text-gray-700 mb-3">Scan kode QR berikut untuk melakukan pembayaran:</p>
                            <img src="<?= base_url('assets/images/qris-code.png') ?>" alt="QRIS Code" class="h-48 mx-auto mb-2">
                            <p class="text-xs text-gray-500">Kode QR berlaku selama 24 jam</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                        <p class="text-sm text-yellow-700">
                            <i class="fas fa-info-circle mr-1"></i> Setelah melakukan pembayaran, silakan unggah bukti pembayaran di bawah ini.
                        </p>
                    </div>
                </div>
                
                <form action="<?= base_url('payment/upload_proof/' . $order['id_order']) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">Unggah Bukti Pembayaran</label>
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm" required>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            <?php elseif($order['stts_pembayaran'] == 'Menunggu Verifikasi'): ?>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 text-center">
                    <i class="fas fa-clock text-yellow-500 text-2xl mb-2"></i>
                    <h3 class="text-base font-medium text-yellow-700 mb-1">Pembayaran Sedang Diverifikasi</h3>
                    <p class="text-sm text-yellow-600">Bukti pembayaran Anda sedang diverifikasi oleh admin. Mohon tunggu konfirmasi selanjutnya.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 text-center">
                <i class="fas fa-truck text-blue-500 text-2xl mb-2"></i>
                <h3 class="text-base font-medium text-blue-700 mb-1">Pembayaran COD (Cash On Delivery)</h3>
                <p class="text-sm text-blue-600">Anda akan membayar saat barang diterima. Pesanan Anda sedang diproses.</p>
            </div>
        <?php endif; ?>
        
        <div class="mt-6 text-center">
            <a href="<?= base_url('account/orders') ?>" class="text-green-600 hover:text-green-700 text-sm font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    const el = document.createElement('textarea');
    el.value = text;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    
    alert('Nomor berhasil disalin!');
}
</script>