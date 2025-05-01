<div class="container mx-auto px-4 py-8 mt-16 max-w-5xl">
    <h1 class="text-2xl font-bold text-green-800 mb-6">Keranjang Belanja</h1>

    <?php if (empty($cart_items)): ?>
        <div class="text-center py-8">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Keranjang belanja Anda kosong</p>
            <a href="<?= base_url('popular') ?>" class="inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Mulai Belanja
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <?php foreach ($cart_items as $item): ?>
                    <?php 
                    $gambar = 'default.jpg';
                    if (!empty($item['gambar'])) {
                        $gambarArr = explode(',', $item['gambar']);
                        $gambar = trim($gambarArr[0]);
                    }
                    ?>
                    <div class="bg-white rounded-lg shadow-md p-4 mb-4 flex items-center">
                        <img src="http://localhost/hijauloka/uploads/<?= $gambar ?>" 
                             alt="<?= $item['nama_product'] ?>" 
                             class="w-20 h-20 object-cover rounded-lg">
                        
                        <div class="ml-4 flex-grow">
                            <h3 class="font-semibold text-base"><?= $item['nama_product'] ?></h3>
                            <p class="text-green-600 font-bold">Rp<?= number_format($item['harga'], 0, ',', '.') ?></p>
                            
                            <div class="flex items-center mt-2">
                                <button onclick="updateQuantity(<?= $item['id_cart'] ?>, 'decrease')" 
                                        class="px-3 py-1 bg-gray-200 rounded-l-lg hover:bg-gray-300">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       value="<?= $item['jumlah'] ?>" 
                                       min="1" 
                                       class="w-16 text-center border-y border-gray-200 py-1"
                                       data-cart-id="<?= $item['id_cart'] ?>"
                                       onchange="updateQuantity(<?= $item['id_cart'] ?>, 'input', this.value)">
                                <button onclick="updateQuantity(<?= $item['id_cart'] ?>, 'increase')" 
                                        class="px-3 py-1 bg-gray-200 rounded-r-lg hover:bg-gray-300">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button onclick="removeItem(<?= $item['id_cart'] ?>)" 
                                class="text-red-500 hover:text-red-700 ml-4">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
                <div class="flex justify-between mb-2">
                    <span>Total Barang</span>
                    <span><?= count($cart_items) ?></span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                    <span>Total</span>
                    <span class="text-green-600">Rp<?= number_format($total, 0, ',', '.') ?></span>
                </div>
                <button class="w-full mt-4 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(cartId, action, value = null) {
    let input = document.querySelector(`input[data-cart-id="${cartId}"]`);
    let currentQty = parseInt(input.value);
    let newQty;

    if (action === 'increase') {
        newQty = currentQty + 1;
    } else if (action === 'decrease') {
        newQty = Math.max(1, currentQty - 1);
    } else {
        newQty = parseInt(value);
    }

    if (newQty < 1) newQty = 1;
    input.value = newQty;

    // Update in database
    fetch('<?= base_url('cart/update') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `cart_id=${cartId}&quantity=${newQty}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update totals
        }
    });
}

function removeItem(cartId) {
    if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) return;

    fetch('<?= base_url('cart/remove') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `cart_id=${cartId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>