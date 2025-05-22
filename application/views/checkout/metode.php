<?php $this->load->view('templates/header2') ?>

<!-- Add Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
@keyframes plant-bounce {
  0%, 100% { transform: translateY(0);}
  50% { transform: translateY(-10px);}
}
#successModalCOD svg {
  animation: plant-bounce 1.2s infinite;
}
</style>

<div class="container mx-auto max-w-4xl py-8 p-3">
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
                        
                        <!-- Add button to add new address -->
                        <div class="flex justify-center mt-4">
                            <button type="button" onclick="openShippingModal()" class="px-4 py-2 text-sm text-green-600 border border-green-500 rounded-lg hover:bg-green-50">
                                <i class="fas fa-plus mr-1"></i> Tambah Alamat Baru
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-gray-500 mb-4">Belum ada alamat pengiriman</p>
                        <button type="button" onclick="openShippingModal()" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Tambah Alamat
                        </button>
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
                                            (<?= $primary_address['jarak'] <= 1 ? 'Rp 10' : 'Rp 10.000' ?>)
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <span class="font-semibold text-green-600" id="shipping-cost-display">
                                    Rp <?= !empty($primary_address) ? number_format($primary_address['jarak'] <= 1 ? 10 : 10000, 0, ',', '.') : '10' ?>
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
                                <img src="http://admin.hijauloka.my.id/uploads<?= $item['gambar'] ?>" 
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
                            Rp <?= !empty($primary_address) ? number_format($primary_address['jarak'] <= 1 ? 10 : 10000, 0, ',', '.') : '10' ?>
                        </span>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-900 text-lg">
                        <span>Total</span>
                        <span id="total-amount">
                            Rp <?= number_format($total + (!empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 10 : 10000) : 10), 0, ',', '.') ?>
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

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="confirmReturn()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                            Kembali
                        </button>
                        <button type="submit" class="px-6 py-3  bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                           Beli Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Alamat Pengiriman -->
<div id="shippingAddressModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Alamat Pengiriman</h3>
                <button onclick="closeShippingModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="<?= base_url('checkout/add_shipping_address') ?>" method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Label Alamat</label>
                        <input type="text" name="address_label" required placeholder="Rumah, Kantor, dll" class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="tel" name="phone" required class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                    </div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-700">Nama Penerima</label>
                    <input type="text" name="recipient_name" required class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <div class="flex gap-2">
                        <textarea id="address" name="address" required rows="2" class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Masukkan alamat lengkap"></textarea>
                        <button type="button" onclick="getCurrentLocation()" class="mt-1 px-3 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                    </div>
                    <div id="map" class="mt-2 h-48 w-full rounded-lg hidden"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">RT</label>
                        <input type="text" name="rt" required class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">RW</label>
                        <input type="text" name="rw" required class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm font-medium text-gray-700">No. Rumah</label>
                        <input type="text" name="house_number" required class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Kode Pos</label>
                    <input type="text" name="postal_code" required class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Detail Tambahan</label>
                    <textarea name="detail_address" rows="2" class="mt-1 w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Patokan, warna rumah, atau instruksi khusus lainnya"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeShippingModal()" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900 border rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Simpan Alamat
                    </button>
                </div>
            </form>
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

<!-- Modal Konfirmasi Kembali ke Keranjang -->
<div id="returnConfirmModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Konfirmasi</h3>
            </div>
            
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin kembali ke keranjang? Semua informasi checkout yang telah diisi tidak akan disimpan.</p>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeReturnModal()" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900 border rounded-lg">
                    Batal
                </button>
                <button type="button" onclick="returnToCart()" class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Ya, Kembali ke Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="kurir"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingCost = this.value === 'hijauloka' ? 
            (<?= !empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 10 : 10000) : 10 ?>) : 0;
        document.getElementById('shipping-cost').textContent = `Rp ${shippingCost.toLocaleString('id-ID')}`;
        document.getElementById('total-amount').textContent = `Rp ${(<?= $total ?> + shippingCost).toLocaleString('id-ID')}`;
        document.getElementById('selected-kurir').value = this.value;
    });
});

// Add shipping address modal functions
function openShippingModal() {
    const modal = document.getElementById('shippingAddressModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            initMap();
            const form = modal.querySelector('form');
            if (form) form.reset();
        }, 100);
    }
}

function closeShippingModal() {
    document.getElementById('shippingAddressModal').classList.add('hidden');
    document.getElementById('shippingAddressModal').classList.remove('flex');
}

let map, marker;

function initMap() {
    if (!document.getElementById('map')) return;
    
    const defaultLocation = [-6.200000, 106.816666];
    
    map = L.map('map').setView(defaultLocation, 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    marker = L.marker(defaultLocation, {
        draggable: true
    }).addTo(map);

    marker.on('dragend', function() {
        const pos = marker.getLatLng();
        updateAddressFromMarker(pos);
    });
}

function getCurrentLocation() {
    document.getElementById('map').classList.remove('hidden');
    
    if (!map) initMap();
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const pos = [
                    position.coords.latitude,
                    position.coords.longitude
                ];
                
                map.setView(pos, 16);
                marker.setLatLng(pos);
                updateAddressFromMarker(marker.getLatLng());
            },
            function() {
                alert('Tidak dapat mengakses lokasi Anda. Pastikan GPS aktif dan izin lokasi diberikan.');
            }
        );
    } else {
        alert('Browser Anda tidak mendukung geolokasi');
    }
}

function updateAddressFromMarker(position) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.lat}&lon=${position.lng}&addressdetails=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById('address').value = data.display_name;
                
                if (data.address && data.address.postcode) {
                    document.querySelector('input[name="postal_code"]').value = data.address.postcode;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Add confirmation function for returning to cart
function confirmReturn() {
    const modal = document.getElementById('returnConfirmModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeReturnModal() {
    const modal = document.getElementById('returnConfirmModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function returnToCart() {
    window.location.href = '<?= base_url('cart') ?>';
}

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ambil metode pembayaran yang dipilih
    const selectedPaymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked').value;
    
    // Validasi metode pembayaran
    if (!['cod', 'midtrans', 'transfer'].includes(selectedPaymentMethod)) {
        alert('Metode pembayaran tidak valid');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    
    // Buat form data
    const formData = new FormData(this);
    
    if (selectedPaymentMethod === 'cod') {
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
    } else if (selectedPaymentMethod === 'midtrans') {
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
        amountInput.value = <?= $total + (!empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 10 : 10000) : 10) ?>;
        midtransForm.appendChild(amountInput);
        
        const shippingInput = document.createElement('input');
        shippingInput.type = 'hidden';
        shippingInput.name = 'shipping_cost';
        shippingInput.value = <?= !empty($primary_address) ? ($primary_address['jarak'] <= 1 ? 10 : 10000) : 10 ?>;
        midtransForm.appendChild(shippingInput);
        
        // Tambahkan metode pembayaran secara eksplisit
        const paymentMethodInput = document.createElement('input');
        paymentMethodInput.type = 'hidden';
        paymentMethodInput.name = 'metode_pembayaran';
        paymentMethodInput.value = selectedPaymentMethod; // Use the actual selected value
        midtransForm.appendChild(paymentMethodInput);
        
        // Tambahkan form ke body dan submit
        document.body.appendChild(midtransForm);
        midtransForm.submit();
    }
});
</script>