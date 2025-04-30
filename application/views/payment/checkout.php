<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
        <p class="text-sm text-gray-600">Selesaikan pembelian Anda</p>
    </div>

    <?php if($this->session->flashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p><?= $this->session->flashdata('error') ?></p>
    </div>
    <?php endif; ?>

    <div class="md:flex md:gap-6">
        <!-- Order Summary -->
        <div class="md:w-2/3">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h2>
                
                <div class="divide-y divide-gray-200">
                    <?php foreach($items as $item): ?>
                    <div class="py-4 flex items-center">
                        <?php 
                        $image = $item['gambar'];
                        if (strpos($image, ',') !== false) {
                            $images = array_map('trim', explode(',', $image));
                            $image = $images[0];
                        }
                        ?>
                        <div class="w-16 h-16 flex-shrink-0">
                            <img src="http://localhost/hijauloka/uploads/<?= $image ?>" alt="<?= $item['nama_product'] ?>" class="w-full h-full object-cover rounded">
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium"><?= $item['nama_product'] ?></h3>
                            <p class="text-xs text-gray-500">Rp<?= number_format($item['harga'], 0, ',', '.') ?> x <?= $item['quantity'] ?></p>
                        </div>
                        <div class="text-sm font-medium text-gray-900">
                            Rp<?= number_format($item['subtotal'], 0, ',', '.') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <div class="flex justify-between">
                        <span class="text-base font-medium text-gray-900">Total</span>
                        <span class="text-base font-medium text-gray-900">Rp<?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Form -->
        <div class="md:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Informasi Pengiriman & Pembayaran</h2>
                
                <form action="<?= base_url('payment/process') ?>" method="post">
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                        <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm" required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <span class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</span>
                        
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="dana" class="h-4 w-4 text-green-600 focus:ring-green-500" required>
                                <span class="ml-3">
                                    <span class="block text-sm font-medium text-gray-700">DANA</span>
                                    <span class="block text-xs text-gray-500">Transfer ke nomor DANA yang tertera</span>
                                </span>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="qris" class="h-4 w-4 text-green-600 focus:ring-green-500">
                                <span class="ml-3">
                                    <span class="block text-sm font-medium text-gray-700">QRIS</span>
                                    <span class="block text-xs text-gray-500">Scan kode QR untuk pembayaran</span>
                                </span>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cod" class="h-4 w-4 text-green-600 focus:ring-green-500">
                                <span class="ml-3">
                                    <span class="block text-sm font-medium text-gray-700">COD (Cash On Delivery)</span>
                                    <span class="block text-xs text-gray-500">Bayar saat barang diterima</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>