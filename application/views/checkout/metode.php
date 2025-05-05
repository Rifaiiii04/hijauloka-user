<?php $this->load->view('templates/header2') ?>
<div class="container mx-auto max-w-md py-12">
    <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">Checkout</h2>

    <!-- Alamat Pengiriman -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-green-700">Alamat Pengiriman</span>
            <button type="button" onclick="openShippingModal()" class="text-sm text-green-600 hover:underline">Tambah Alamat</button>
        </div>
        <?php if (!empty($shipping_addresses)): ?>
            <form id="chooseAddressForm" action="<?= base_url('checkout/set_primary_address') ?>" method="post">
                <div class="space-y-3">
                    <?php foreach ($shipping_addresses as $address): ?>
                        <label class="flex items-start gap-2 cursor-pointer border rounded p-2 <?= $address['is_primary'] ? 'border-green-600' : 'border-gray-200' ?>">
                            <input type="radio" name="primary_id" value="<?= $address['id'] ?>" <?= $address['is_primary'] ? 'checked' : '' ?> onchange="document.getElementById('chooseAddressForm').submit()">
                            <div>
                                <div class="font-medium"><?= $address['recipient_name'] ?> (<?= $address['phone'] ?>)</div>
                                <div class="text-sm text-gray-700"><?= $address['address'] ?>, RT <?= $address['rt'] ?>/RW <?= $address['rw'] ?>, No. <?= $address['house_number'] ?>, <?= $address['postal_code'] ?></div>
                                <?php if (!empty($address['detail_address'])): ?>
                                    <div class="text-xs text-gray-500 mt-1"><?= $address['detail_address'] ?></div>
                                <?php endif; ?>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </form>
        <?php else: ?>
            <div class="text-gray-500 text-sm">Belum ada alamat pengiriman. <button type="button" onclick="openShippingModal()" class="text-green-600 underline">Tambah Alamat</button></div>
        <?php endif; ?>
    </div>

    <!-- Ringkasan Pesanan -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="font-semibold text-green-700 mb-2">Ringkasan Pesanan</div>
        <div class="flex justify-between text-sm mb-1">
            <span>Total Barang</span>
            <span><?= $total_items ?> item</span>
        </div>
        <div class="flex justify-between text-sm mb-1">
            <span>Subtotal</span>
            <span>Rp<?= number_format($total, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2 mt-2">
            <span>Total</span>
            <span class="text-green-700">Rp<?= number_format($total, 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Metode Pembayaran -->
    <form action="<?= base_url('checkout/proses_checkout') ?>" method="post" class="bg-white rounded-lg shadow-md p-4 space-y-4">
        <div class="font-semibold text-green-700 mb-2">Metode Pembayaran</div>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="metode_pembayaran" value="dana" required class="accent-green-600">
            <span class="font-medium">DANA (E-Wallet)</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="metode_pembayaran" value="transfer" class="accent-green-600">
            <span class="font-medium">Transfer Bank</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="metode_pembayaran" value="cod" class="accent-green-600">
            <span class="font-medium">Bayar di Tempat (COD)</span>
        </label>
        <button type="submit" class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-bold flex items-center justify-center gap-2">
            <i class="fas fa-credit-card"></i>
            Buat Pesanan
        </button>
    </form>
</div>

<!-- Modal Shipping Address -->
<div id="shippingModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Ubah Alamat Pengiriman</h3>
            <button onclick="closeShippingModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="<?= base_url('checkout/update_shipping_address') ?>" method="post" class="space-y-3">
            <input type="hidden" name="id" value="<?= $shipping_address['id'] ?? '' ?>">
            <div>
                <label class="block text-sm font-medium">Nama Penerima</label>
                <input type="text" name="recipient_name" required class="w-full border rounded px-3 py-2" value="<?= $shipping_address['recipient_name'] ?? '' ?>">
            </div>
            <div>
                <label class="block text-sm font-medium">No. Telepon</label>
                <input type="text" name="phone" required class="w-full border rounded px-3 py-2" value="<?= $shipping_address['phone'] ?? '' ?>">
            </div>
            <div>
                <label class="block text-sm font-medium">Alamat Lengkap</label>
                <textarea name="address" required class="w-full border rounded px-3 py-2"><?= $shipping_address['address'] ?? '' ?></textarea>
            </div>
            <div class="flex gap-2">
                <input type="text" name="rt" placeholder="RT" class="w-1/4 border rounded px-2 py-1" value="<?= $shipping_address['rt'] ?? '' ?>">
                <input type="text" name="rw" placeholder="RW" class="w-1/4 border rounded px-2 py-1" value="<?= $shipping_address['rw'] ?? '' ?>">
                <input type="text" name="house_number" placeholder="No. Rumah" class="w-1/2 border rounded px-2 py-1" value="<?= $shipping_address['house_number'] ?? '' ?>">
            </div>
            <div>
                <input type="text" name="postal_code" placeholder="Kode Pos" class="w-full border rounded px-3 py-2" value="<?= $shipping_address['postal_code'] ?? '' ?>">
            </div>
            <div>
                <textarea name="detail_address" placeholder="Detail tambahan" class="w-full border rounded px-3 py-2"><?= $shipping_address['detail_address'] ?? '' ?></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeShippingModal()" class="px-4 py-2 text-gray-700 border rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openShippingModal() {
    document.getElementById('shippingModal').classList.remove('hidden');
}
function closeShippingModal() {
    document.getElementById('shippingModal').classList.add('hidden');
}
</script> 