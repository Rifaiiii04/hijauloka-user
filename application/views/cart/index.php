<?php $this->load->view('templates/header') ?>
<div class="container mx-auto max-w-6xl py-12 mt-22">
    <h2 class="text-2xl font-bold text-green-800 mb-6">Keranjang Belanja</h2>
    
    <?php if (empty($cart_items)): ?>
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center">
            <i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 text-lg mb-2">Keranjang belanja Anda kosong</p>
            <a href="<?= base_url('popular') ?>" class="inline-block mt-4 px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all font-semibold text-lg shadow-md">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Cart Items -->
            <div class="flex-grow">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Produk</h3>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                            <label for="selectAll" class="text-sm text-gray-600">Pilih Semua</label>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="py-4 flex items-center gap-4">
                                <input type="checkbox" name="selected_items[]" value="<?= $item['id_cart'] ?>" 
                                       class="item-checkbox w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                                <div class="w-20 h-20 flex-shrink-0">
                                    <?php 
                                    $gambar = !empty($item['gambar']) ? 
                                        (strpos($item['gambar'], ',') !== false ? 
                                            explode(',', $item['gambar'])[0] : 
                                            $item['gambar']) : 
                                        'default.jpg';
                                    ?>
                                    <img src="http://localhost/hijauloka/uploads/<?= $gambar ?>" 
                                         alt="<?= $item['nama_product'] ?>" 
                                         class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-medium text-gray-900 mb-1"><?= $item['nama_product'] ?></h4>
                                    <div class="text-sm text-gray-600 mb-2">Rp<?= number_format($item['harga'], 0, ',', '.') ?></div>
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center border rounded-lg">
                                            <button onclick="updateQuantity(<?= $item['id_cart'] ?>, 'decrease')" 
                                                    class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-lg">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <input type="number" 
                                                   value="<?= $item['jumlah'] ?>" 
                                                   min="1" 
                                                   max="<?= $item['stok'] ?>"
                                                   data-cart-id="<?= $item['id_cart'] ?>"
                                                   class="w-12 text-center border-x py-1 focus:outline-none"
                                                   onchange="updateQuantity(<?= $item['id_cart'] ?>, 'set', this.value)">
                                            <button onclick="updateQuantity(<?= $item['id_cart'] ?>, 'increase')" 
                                                    class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-lg">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                        <button onclick="removeItem(<?= $item['id_cart'] ?>)" 
                                                class="text-red-600 hover:text-red-700 text-sm">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-700">
                                        Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 h-fit lg:w-80">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Ringkasan Pesanan</h2>
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center pb-2">
                        <span class="text-gray-600">Total Barang</span>
                        <span class="font-medium"><span id="selectedCount">0</span> item</span>
                    </div>
                    
                    <div class="border-t border-dashed border-gray-200 pt-3 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">Rp<span id="selectedTotal">0</span></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-4 mt-4">
                    <span>Total</span>
                    <span class="text-green-700">Rp<span id="finalTotal">0</span></span>
                </div>
                
                <form action="<?= base_url('checkout/metode') ?>" method="get" id="checkoutForm">
                    <input type="hidden" name="selected_items" id="selectedItemsInput">
                    <button type="submit" id="checkoutBtn" class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 font-medium" disabled>
                        <i class="fas fa-credit-card"></i>
                        <span>Lanjut ke Pembayaran</span>
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateOrderSummary();
});

// Individual checkbox change
document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateOrderSummary);
});

function updateOrderSummary() {
    const selectedItems = [];
    let totalItems = 0;
    let totalAmount = 0;

    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        const cartItem = checkbox.closest('.py-4');
        const quantity = parseInt(cartItem.querySelector('input[type="number"]').value);
        const price = parseInt(cartItem.querySelector('.text-green-700').textContent.replace(/[^0-9]/g, ''));
        
        selectedItems.push(checkbox.value);
        totalItems += quantity;
        totalAmount += price;
    });

    // Update UI
    document.getElementById('selectedCount').textContent = totalItems;
    document.getElementById('selectedTotal').textContent = totalAmount.toLocaleString('id-ID');
    document.getElementById('finalTotal').textContent = totalAmount.toLocaleString('id-ID');
    document.getElementById('selectedItemsInput').value = selectedItems.join(',');
    
    // Enable/disable checkout button
    document.getElementById('checkoutBtn').disabled = selectedItems.length === 0;
}

function updateQuantity(cartId, action, value = null) {
    let input = document.querySelector(`input[data-cart-id="${cartId}"]`);
    let currentQty = parseInt(input.value);
    let newQty;
    
    if (action === 'increase') {
        newQty = currentQty + 1;
    } else if (action === 'decrease') {
        newQty = Math.max(1, currentQty - 1);
    } else if (action === 'set') {
        newQty = parseInt(value);
    }
    
    // Update quantity via AJAX
    fetch('<?= base_url('cart/update_quantity') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_id=${cartId}&quantity=${newQty}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = newQty;
            updateOrderSummary();
        } else {
            alert(data.message || 'Gagal mengupdate jumlah');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate jumlah');
    });
}

function removeItem(cartId) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        fetch('<?= base_url('cart/remove') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cart_id=${cartId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus item');
        });
    }
}
</script>
<?php $this->load->view('templates/footer') ?>;