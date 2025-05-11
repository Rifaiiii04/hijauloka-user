<?php $this->load->view('templates/header2') ?>

<style>
@keyframes plant-bounce {
  0%, 100% { transform: translateY(0);}
  50% { transform: translateY(-10px);}
}
#successModalCOD svg {
  animation: plant-bounce 1.2s infinite;
}
</style>

<div class="container mx-auto max-w-4xl py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-green-800">Checkout</h1>
        <p class="text-gray-600">Lengkapi informasi pengiriman dan pembayaran</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column: Shipping Address -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Alamat Pengiriman</h2>
                
                <?php if (!empty($shipping_addresses)): ?>
                    <div class="space-y-4">
                        <?php foreach ($shipping_addresses as $address): ?>
                            <div class="border rounded-lg p-4 <?= $address['is_primary'] ? 'border-green-500 bg-green-50' : '' ?>">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="font-medium text-gray-900"><?= $address['recipient_name'] ?></div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            <?= $address['address'] ?>, RT <?= $address['rt'] ?>/RW <?= $address['rw'] ?>, 
                                            No. <?= $address['house_number'] ?>, <?= $address['postal_code'] ?>
                                        </div>
                                        <?php if (!empty($address['detail_address'])): ?>
                                            <div class="text-sm text-gray-500 mt-1">Catatan: <?= $address['detail_address'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!$address['is_primary']): ?>
                                        <form action="<?= base_url('checkout/set_primary_address') ?>" method="POST" class="ml-4">
                                            <input type="hidden" name="primary_id" value="<?= $address['id'] ?>">
                                            <button type="submit" class="text-green-600 hover:text-green-700 text-sm font-medium">
                                                Jadikan Utama
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-gray-500 mb-4">Belum ada alamat pengiriman</p>
                        <a href="<?= base_url('profile/address/add') ?>" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Tambah Alamat
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Shipping Method Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Metode Pengiriman</h2>
                
                <div class="space-y-4">
                    <!-- HijauLoka Kurir -->
                    <div class="flex items-center p-4 border rounded-lg hover:border-green-500 cursor-pointer">
                        <input type="radio" name="kurir" value="hijauloka" id="kurir-hijauloka" class="w-4 h-4 text-green-600" checked>
                        <label for="kurir-hijauloka" class="ml-3 flex-grow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-900">HijauLoka Kurir</span>
                                    <p class="text-sm text-gray-500">Pengiriman dalam 1-2 hari kerja</p>
                                    <?php if (!empty($primary_address)): ?>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Jarak: <?= number_format($primary_address['jarak'], 1) ?> KM
                                            (<?= $primary_address['jarak'] <= 1 ? 'Rp 5.000' : 'Rp 10.000' ?>)
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <span class="font-semibold text-green-600" id="shipping-cost-display">
                                    Rp <?= !empty($primary_address) ? number_format($primary_address['jarak'] <= 1 ? 5000 : 10000, 0, ',', '.') : '5.000' ?>
                                </span>
                            </div>
                        </label>
                    </div>

                    <!-- JNE (Coming Soon) -->
                    <div class="flex items-center p-4 border rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
                        <input type="radio" name="kurir" value="jne" id="kurir-jne" class="w-4 h-4 text-gray-400" disabled>
                        <label for="kurir-jne" class="ml-3 flex-grow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-900">JNE</span>
                                    <p class="text-sm text-gray-500">Coming Soon</p>
                                </div>
                                <span class="font-semibold text-gray-400">-</span>
                            </div>
                        </label>
                    </div>

                    <!-- JNT (Coming Soon) -->
                    <div class="flex items-center p-4 border rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
                        <input type="radio" name="kurir" value="jnt" id="kurir-jnt" class="w-4 h-4 text-gray-400" disabled>
                        <label for="kurir-jnt" class="ml-3 flex-grow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-900">JNT</span>
                                    <p class="text-sm text-gray-500">Coming Soon</p>
                                </div>
                                <span class="font-semibold text-gray-400">-</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h2>
                
                <div class="space-y-4">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="flex gap-4">
                            <div class="w-20 h-20 flex-shrink-0">
                                <img src="http://localhost/hijauloka/uploads/<?= $item['gambar'] ?>" 
                                     alt="<?= $item['nama_product'] ?>" 
                                     class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div class="flex-grow">
                                <div class="font-medium text-gray-900"><?= $item['nama_product'] ?></div>
                                <div class="text-sm text-gray-500">Qty: <?= $item['jumlah'] ?></div>
                                <div class="text-green-600 font-semibold">
                                    Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="border-t mt-4 pt-4 space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-cost">
                            Rp <?= !empty($primary_address) ? number_format($primary_address['jarak'] <= 1 ? 5000 : 10000, 0, ',', '.') : '5.000' ?>
                        </span>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-900 text-lg">
                        <span>Total</span>
                        <span id="total-amount">
                            Rp <?= number_format($total + (!empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 5000 : 10000) : 5000), 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Metode Pembayaran</h2>
                
                <form action="<?= base_url('checkout/proses_checkout') ?>" method="POST" id="checkout-form">
                    <input type="hidden" name="kurir" id="selected-kurir" value="hijauloka">
                    
                    <div class="space-y-4">
                        <!-- COD -->
                        <div class="flex items-center p-4 border rounded-lg hover:border-green-500 cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="cod" id="cod" class="w-4 h-4 text-green-600" checked>
                            <label for="cod" class="ml-3 flex-grow">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium text-gray-900">Cash on Delivery (COD)</span>
                                        <p class="text-sm text-gray-500">Bayar di tempat saat barang diterima</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Midtrans Payment Gateway -->
                        <div class="flex items-center p-4 border rounded-lg hover:border-green-500 cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="midtrans" id="midtrans" class="w-4 h-4 text-green-600">
                            <label for="midtrans" class="ml-3 flex-grow">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium text-gray-900">Pembayaran Online</span>
                                        <p class="text-sm text-gray-500">QRIS, Transfer Bank, E-Wallet, Kartu Kredit</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Transfer Bank (Coming Soon) -->
                        <div class="flex items-center p-4 border rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
                            <input type="radio" name="metode_pembayaran" value="transfer" id="transfer" class="w-4 h-4 text-gray-400" disabled>
                            <label for="transfer" class="ml-3 flex-grow">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium text-gray-900">Transfer Bank Manual</span>
                                        <p class="text-sm text-gray-500">Coming Soon</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                       Beli Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loader Verifikasi Berhasil untuk COD -->
<div id="successModalCOD" class="fixed inset-0 backdrop-blur-sm bg-white/30 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl px-8 py-10 flex flex-col items-center relative animate__animated animate__fadeInDown">
        <div class="mb-4">
            <svg width="100" height="100" viewBox="0 0 100 100" fill="none">
                <ellipse cx="50" cy="90" rx="30" ry="8" fill="#A3D9A5"/>
                <rect x="40" y="60" width="20" height="30" rx="8" fill="#7BC47F"/>
                <path d="M50 60 Q45 40 30 50" stroke="#4F8A4B" stroke-width="4" fill="none"/>
                <path d="M50 60 Q55 35 70 55" stroke="#4F8A4B" stroke-width="4" fill="none"/>
                <circle cx="30" cy="50" r="6" fill="#A3D9A5"/>
                <circle cx="70" cy="55" r="7" fill="#A3D9A5"/>
            </svg>
        </div>
        <div class="text-green-700 font-bold text-xl mb-2">Pesanan Berhasil!</div>
        <div class="text-gray-600 mb-4">Pesanan COD Anda sedang diproses oleh sistem.</div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="kurir"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingCost = this.value === 'hijauloka' ? 
            (<?= !empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 5000 : 10000) : 5000 ?>) : 0;
        document.getElementById('shipping-cost').textContent = `Rp ${shippingCost.toLocaleString('id-ID')}`;
        document.getElementById('total-amount').textContent = `Rp ${(<?= $total ?> + shippingCost).toLocaleString('id-ID')}`;
        document.getElementById('selected-kurir').value = this.value;
    });
});

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ambil metode pembayaran yang dipilih
    const codRadio = document.getElementById('cod');
    const midtransRadio = document.getElementById('midtrans');
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    
    // Buat form data
    const formData = new FormData(this);
    
    if (codRadio && codRadio.checked) {
        // Proses COD
        fetch('<?= base_url('checkout/proses_checkout') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.success) {
                    // Show success modal
                    document.getElementById('successModalCOD').classList.remove('hidden');
                    setTimeout(() => {
                        window.location.href = "<?= base_url('checkout/sukses') ?>";
                    }, 2500);
                } else {
                    // Show error message
                    alert(jsonData.message || 'Terjadi kesalahan saat memproses pesanan');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            } catch (e) {
                // If response is not JSON, assume it's a redirect or success
                document.getElementById('successModalCOD').classList.remove('hidden');
                setTimeout(() => {
                    window.location.href = "<?= base_url('checkout/sukses') ?>";
                }, 2500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    } else if (midtransRadio && midtransRadio.checked) {
        // Perbaikan untuk Midtrans: Gunakan form baru dan submit langsung
        const midtransForm = document.createElement('form');
        midtransForm.method = 'POST';
        midtransForm.action = '<?= base_url('midtrans/process_payment') ?>';
        
        // Salin semua field dari form asli
        for (const pair of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = pair[0];
            input.value = pair[1];
            midtransForm.appendChild(input);
        }
        
        // Tambahkan field tambahan untuk Midtrans
        const amountInput = document.createElement('input');
        amountInput.type = 'hidden';
        amountInput.name = 'amount';
        amountInput.value = <?= $total + (!empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 5000 : 10000) : 5000) ?>;
        midtransForm.appendChild(amountInput);
        
        const shippingInput = document.createElement('input');
        shippingInput.type = 'hidden';
        shippingInput.name = 'shipping_cost';
        shippingInput.value = <?= !empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 5000 : 10000) : 5000 ?>;
        midtransForm.appendChild(shippingInput);
        
        // Tambahkan form ke body dan submit
        document.body.appendChild(midtransForm);
        midtransForm.submit();
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>