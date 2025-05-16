<?php $this->load->view('templates/header2'); ?>
<div class="container mx-auto max-w-xl py-10 min-h-[60vh]">
    <div class="mb-6 flex items-center gap-3">
        <a href="<?= base_url('orders') ?>" class="text-green-700 hover:underline flex items-center gap-1"><i class="fas fa-arrow-left"></i> Kembali</a>
        <a href="<?= base_url() ?>" class="text-green-700 hover:underline flex items-center gap-1"><i class="fas fa-home"></i> Beranda</a>
        <h2 class="text-2xl font-bold text-green-800 ml-2">Detail Pesanan #<?= $order['id_order'] ?></h2>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-wrap gap-3 items-center mb-2">
            <span class="font-semibold text-green-700">Tanggal:</span>
            <span><?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></span>
            <span class="font-semibold text-green-700">Status:</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $order['stts_pemesanan'] == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?> capitalize"><?= $order['stts_pemesanan'] ?></span>
            <span class="font-semibold text-green-700">Pembayaran:</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $order['stts_pembayaran'] == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?> capitalize"><?= $order['stts_pembayaran'] ?></span>
        </div>
        <div class="mt-2">
            <div class="font-semibold text-green-700 mb-1">Alamat Pengiriman</div>
            <?php if ($shipping_address): ?>
                <div class="text-sm text-gray-700 mb-1"><?= $shipping_address['recipient_name'] ?> (<?= $shipping_address['phone'] ?>)</div>
                <div class="text-xs text-gray-600 mb-1"><?= $shipping_address['address'] ?>, RT <?= $shipping_address['rt'] ?>/RW <?= $shipping_address['rw'] ?>, No. <?= $shipping_address['house_number'] ?>, <?= $shipping_address['postal_code'] ?></div>
                <?php if (!empty($shipping_address['detail_address'])): ?>
                    <div class="text-xs text-gray-500 mb-1">Catatan: <?= $shipping_address['detail_address'] ?></div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-xs text-gray-500">Alamat tidak ditemukan.</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="font-semibold text-green-700 mb-3">Daftar Produk</div>
        <div class="divide-y divide-gray-100">
            <?php $total = 0; foreach ($order_items as $item): $total += $item['subtotal']; ?>
                <div class="py-4">
                    <div class="flex gap-4">
                        <div class="w-20 h-20 flex-shrink-0">
                            <?php 
                            $gambar = !empty($item['gambar']) ? 
                                (strpos($item['gambar'], ',') !== false ? 
                                    explode(',', $item['gambar'])[0] : 
                                    $item['gambar']) : 
                                'default.jpg';
                            ?>
                            <!-- Replace the problematic lines with these versions that check if the index exists -->
                            <img src="<?= base_url('uploads/' . (isset($item['gambar']) ? $item['gambar'] : 'default.jpg')) ?>" alt="<?= isset($item['nama_product']) ? $item['nama_product'] : 'Product Image' ?>" class="w-full h-full object-cover rounded-lg">
                            
                            <h3 class="text-lg font-semibold text-gray-800"><?= isset($item['nama_product']) ? $item['nama_product'] : 'Product' ?></h3>
                            <p class="text-sm text-gray-600 mt-1"><?= isset($item['desk_product']) ? substr($item['desk_product'], 0, 100) . (strlen($item['desk_product']) > 100 ? '...' : '') : '' ?></p>
                        </div>
                        <div class="flex-grow">
                            <div class="font-medium text-gray-900 mb-1"><?= $item['nama_product'] ?></div>
                            <div class="text-sm text-gray-600 mb-2 line-clamp-2"><?= $item['desk_product'] ?></div>
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">Qty: <?= $item['quantity'] ?></div>
                                <div class="font-semibold text-green-700">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-between items-center border-t pt-4 mt-4 font-bold text-lg">
            <span>Total</span>
            <span class="text-green-700">Rp<?= number_format($total, 0, ',', '.') ?></span>
        </div>
    </div>
    <div class="flex justify-end">
        <a href="<?= base_url('orders') ?>" class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-all flex items-center gap-2"><i class="fas fa-list"></i> Daftar Pesanan</a>
    </div>
</div>
<?php $this->load->view('templates/footer'); ?>