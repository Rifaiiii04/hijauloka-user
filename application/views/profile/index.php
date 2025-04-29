<?php $this->load->view('templates/header'); ?>
<div class="min-h-screen flex items-center">
    <div class="container mx-auto px-4 py-16">
        <!-- Compact Profile Header -->
        <div class="max-w-2xl mx-auto mt-16 mb-3">
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 flex items-center gap-4">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center shadow">
                        <i class="fas fa-user-circle text-4xl text-gray-300"></i>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-gray-800"><?= $user['nama'] ?></h1>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-envelope text-green-600"></i><?= $user['email'] ?>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-phone text-green-600"></i>
                                <?= $user['no_tlp'] ?? 'Belum diatur' ?>
                            </div>
                        </div>
                    </div>
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-2xl mx-auto space-y-4">
            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow p-4">
                <!-- Bagian status pesanan -->
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Status Pesanan Saat Ini</h2>
                <div class="grid grid-cols-4 gap-2 text-center text-sm">
                    <div class="p-2 rounded bg-gray-50">
                        <div class="text-xl font-bold text-green-600 mb-1">0</div>
                        <div class="text-gray-600">Menunggu</div>
                    </div>
                    <div class="p-2 rounded bg-gray-50">
                        <div class="text-xl font-bold text-green-600 mb-1">0</div>
                        <div class="text-gray-600">Diproses</div>
                    </div>
                    <div class="p-2 rounded bg-gray-50">
                        <div class="text-xl font-bold text-green-600 mb-1">0</div>
                        <div class="text-gray-600">Dikirim</div>
                    </div>
                    <div class="p-2 rounded bg-gray-50">
                        <div class="text-xl font-bold text-green-600 mb-1">0</div>
                        <div class="text-gray-600">Selesai</div>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold text-gray-800">Main Address</h2>
                    <button class="text-green-600 hover:text-green-700 text-sm">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <!-- Bagian alamat utama -->
                <h2 class="text-lg font-semibold text-gray-800">Alamat Utama</h2>
                <?php if (isset($user['alamat']) && !empty($user['alamat'])): ?>
                    <p class="text-gray-700 text-sm"><?= $user['alamat'] ?></p>
                <?php else: ?>
                    <p class="text-gray-500 text-sm text-center py-2">Belum ada alamat utama</p>
                <?php endif; ?>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow p-4">
                <!-- Perbaiki bagian tombol tambah -->
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold text-gray-800">Shipping Address</h2>
                    <button type="button" onclick="openShippingModal()" class="text-green-600 hover:text-green-700 text-sm">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <?php if (!empty($shipping_addresses)): ?>
                    <div class="space-y-3">
                        <?php foreach ($shipping_addresses as $address): ?>
                            <div class="border rounded-lg p-3">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium"><?= $address['recipient_name'] ?></h4>
                                        <p class="text-sm text-gray-600"><?= $address['phone'] ?></p>
                                    </div>
                                    <?php if ($address['is_primary']): ?>
                                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">Primary</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <?= $address['address'] ?>, RT <?= $address['rt'] ?>/RW <?= $address['rw'] ?>, 
                                    No. <?= $address['house_number'] ?>, <?= $address['postal_code'] ?>
                                </p>
                                <?php if (!empty($address['detail_address'])): ?>
                                    <p class="text-sm text-gray-600 mt-1"><?= $address['detail_address'] ?></p>
                                <?php endif; ?>
                                <div class="flex gap-2 mt-2">
                                    <button onclick="editShippingAddress(<?= $address['id'] ?>)" class="text-xs text-blue-600">Edit</button>
                                    <?php if (!$address['is_primary']): ?>
                                        <button onclick="deleteShippingAddress(<?= $address['id'] ?>)" class="text-xs text-red-600">Delete</button>
                                        <button onclick="setPrimaryAddress(<?= $address['id'] ?>)" class="text-xs text-green-600">Set as Primary</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm mb-2">Belum ada alamat pengiriman</p>
                        <button onclick="openShippingModal()" class="text-green-600 hover:text-green-700 text-sm">
                            <i class="fas fa-plus mr-2"></i>Tambah Alamat Baru
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Riwayat Pesanan -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Pesanan</h2>
                <?php if (!empty($orders)): ?>
                    <div class="space-y-3">
                        <?php foreach ($orders as $order): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div class="text-sm">
                                <div class="font-medium text-gray-800">#<?= $order['order_id'] ?></div>
                                <div class="text-gray-600"><?= $order['date'] ?></div>
                            </div>
                            <div class="text-right text-sm">
                                <div class="font-medium text-gray-800">Rp <?= number_format($order['total'], 0, ',', '.') ?></div>
                                <div class="text-green-600"><?= $order['status'] ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-sm text-gray-500">
                        <i class="fas fa-shopping-bag text-gray-300 text-2xl mb-2"></i>
                        <p>Belum ada pesanan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div id="editProfileModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Edit Profil</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="<?= base_url('profile/update') ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $user['nama'] ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="no_tlp" value="<?= $user['no_tlp'] ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Utama</label>
                    <textarea name="alamat" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"><?= $user['alamat'] ?></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Alamat Pengiriman -->
<div id="shippingAddressModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Alamat Pengiriman</h3>
                <button onclick="closeShippingModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="<?= base_url('profile/add_shipping_address') ?>" method="POST" class="space-y-4">
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

<!-- Di bagian paling atas file, setelah header -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function openEditModal() {
    document.getElementById('editProfileModal').classList.remove('hidden');
    document.getElementById('editProfileModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
    document.getElementById('editProfileModal').classList.remove('flex');
}

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

function deleteShippingAddress(id) {
    if (confirm('Anda yakin ingin menghapus alamat ini?')) {
        window.location.href = '<?= base_url('profile/delete_shipping_address/') ?>' + id;
    }
}

function setPrimaryAddress(id) {
    window.location.href = '<?= base_url('profile/set_primary_address/') ?>' + id;
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

document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.querySelector('.fa-edit').parentElement;
    if (editButton) {
        editButton.onclick = openEditModal;
    }
});
</script>