<?php $this->load->view('templates/header') ?>

<div class="container mx-auto max-w-6xl py-8 px-4 md:px-0 mt-22">
    <h2 class="text-2xl font-bold text-green-800 mb-6">Keranjang Belanja</h2>
    
    <?php if (empty($cart_items)): ?>
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-6"></i>
            <p class="text-gray-600 text-lg mb-4">Keranjang belanja Anda kosong</p>
            <a href="<?= base_url('popular') ?>" class="inline-block mt-4 px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all font-semibold text-lg shadow-md">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Cart Items -->
            <div class="flex-grow">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Produk</h3>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll" class="w-5 h-5 text-green-600 rounded border-gray-300 focus:ring-green-500 cursor-pointer">
                            <label for="selectAll" class="text-sm text-gray-600 cursor-pointer hover:text-gray-800">Pilih Semua</label>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" name="selected_items[]" value="<?= $item['id_cart'] ?>" 
                                           class="item-checkbox w-5 h-5 text-green-600 rounded border-gray-300 focus:ring-green-500 cursor-pointer">
                                    <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200">
                                        <?php 
                                        $gambar = !empty($item['gambar']) ? 
                                            (strpos($item['gambar'], ',') !== false ? 
                                                explode(',', $item['gambar'])[0] : 
                                                $item['gambar']) : 
                                            'default.jpg';
                                        ?>
                                        <img src="http://localhost/hijauloka/uploads/<?= $gambar ?>" 
                                             alt="<?= $item['nama_product'] ?>" 
                                             class="w-full h-full object-cover">
                                    </div>
                                </div>
                                
                                <div class="flex-grow">
                                    <h4 class="font-medium text-gray-900 mb-1 hover:text-green-700 transition-colors">
                                        <a href="<?= base_url('product/detail/' . $item['id_product']) ?>">
                                            <?= $item['nama_product'] ?>
                                        </a>
                                    </h4>
                                    <div class="text-sm text-gray-600 mb-3">Rp<?= number_format($item['harga'], 0, ',', '.') ?></div>
                                    
                                    <div class="flex flex-wrap items-center gap-4">
                                        <div class="flex items-center border rounded-lg shadow-sm">
                                            <button onclick="updateQuantity(<?= $item['id_cart'] ?>, 'decrease')" 
                                                    class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 rounded-l-lg transition-colors">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <input type="number" 
                                                   value="<?= $item['jumlah'] ?>" 
                                                   min="1" 
                                                   max="<?= $item['stok'] ?>"
                                                   data-cart-id="<?= $item['id_cart'] ?>"
                                                   class="w-14 text-center border-x py-1.5 focus:outline-none focus:ring-1 focus:ring-green-500"
                                                   onchange="updateQuantity(<?= $item['id_cart'] ?>, 'set', this.value)">
                                            <button onclick="updateQuantity(<?= $item['id_cart'] ?>, 'increase')" 
                                                    class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 rounded-r-lg transition-colors">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                        <button onclick="removeItem(<?= $item['id_cart'] ?>)" 
                                                class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1 transition-colors">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="text-right mt-3 sm:mt-0">
                                    <div class="font-semibold text-green-700 text-lg">
                                        Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Stok: <?= $item['stok'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-80">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-6 text-green-800 pb-2 border-b border-gray-100">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Barang</span>
                            <span class="font-medium"><span id="selectedCount" class="text-green-700">0</span> item</span>
                        </div>
                        
                        <div class="border-t border-dashed border-gray-200 pt-4">
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
                    
                    <form action="<?= base_url('checkout/metode') ?>" method="post" id="checkoutForm">
                        <input type="hidden" name="selected_items" id="selectedItemsInput">
                        <button type="submit" id="checkoutBtn" class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 font-medium shadow-md disabled:opacity-60 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-credit-card"></i>
                            <span>Lanjut ke Pembayaran</span>
                        </button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <a href="<?= base_url('popular') ?>" class="text-green-600 hover:text-green-800 text-sm inline-flex items-center gap-1">
                            <i class="fas fa-arrow-left text-xs"></i> Lanjutkan Belanja
                        </a>
                    </div>
                </div>
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
    
    // Visual feedback
    if (this.checked) {
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.closest('.p-6').classList.add('bg-green-50');
        });
    } else {
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.closest('.p-6').classList.remove('bg-green-50');
        });
    }
});

// Individual checkbox change
document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        updateOrderSummary();
        
        // Visual feedback for selection
        if (this.checked) {
            this.closest('.p-6').classList.add('bg-green-50');
        } else {
            this.closest('.p-6').classList.remove('bg-green-50');
        }
        
        // Update select all checkbox
        const allChecked = document.querySelectorAll('.item-checkbox:checked').length === 
                          document.querySelectorAll('.item-checkbox').length;
        document.getElementById('selectAll').checked = allChecked;
    });
});

function updateOrderSummary() {
    const selectedItems = [];
    let totalItems = 0;
    let totalAmount = 0;

    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        const cartItem = checkbox.closest('.p-6');
        const quantity = parseInt(cartItem.querySelector('input[type="number"]').value);
        const price = parseInt(cartItem.querySelector('.text-green-700').textContent.replace(/[^0-9]/g, ''));
        
        selectedItems.push(checkbox.value);
        totalItems += quantity;
        totalAmount += price;
    });

    // Update UI with animation
    animateCounter('selectedCount', totalItems);
    animateCounter('selectedTotal', totalAmount);
    animateCounter('finalTotal', totalAmount);
    
    document.getElementById('selectedItemsInput').value = selectedItems.join(',');
    
    // Enable/disable checkout button with visual feedback
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (selectedItems.length === 0) {
        checkoutBtn.disabled = true;
        checkoutBtn.classList.add('opacity-60', 'cursor-not-allowed');
    } else {
        checkoutBtn.disabled = false;
        checkoutBtn.classList.remove('opacity-60', 'cursor-not-allowed');
    }
}

function animateCounter(elementId, targetValue) {
    const element = document.getElementById(elementId);
    const startValue = parseInt(element.textContent.replace(/[^0-9]/g, '') || 0);
    const duration = 300; // ms
    const startTime = performance.now();
    
    function updateCounter(currentTime) {
        const elapsedTime = currentTime - startTime;
        if (elapsedTime < duration) {
            const progress = elapsedTime / duration;
            const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
            element.textContent = currentValue.toLocaleString('id-ID');
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = targetValue.toLocaleString('id-ID');
        }
    }
    
    requestAnimationFrame(updateCounter);
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
    
    // Visual feedback during update
    const cartItem = input.closest('.p-6');
    cartItem.classList.add('bg-gray-50');
    
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
            
            // Update subtotal with animation
            const priceElement = cartItem.querySelector('.text-green-700');
            const unitPrice = parseInt(cartItem.querySelector('.text-gray-600').textContent.replace(/[^0-9]/g, ''));
            const newTotal = unitPrice * newQty;
            
            // Animate the price change
            priceElement.classList.add('text-green-500');
            setTimeout(() => {
                priceElement.textContent = 'Rp' + newTotal.toLocaleString('id-ID');
                priceElement.classList.remove('text-green-500');
            }, 300);
            
            updateOrderSummary();
        } else {
            // Show error toast instead of alert
            showToast(data.message || 'Gagal mengupdate jumlah', 'error');
        }
        cartItem.classList.remove('bg-gray-50');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat mengupdate jumlah', 'error');
        cartItem.classList.remove('bg-gray-50');
    });
}

function removeItem(cartId) {
    const cartItem = document.querySelector(`input[data-cart-id="${cartId}"]`).closest('.p-6');
    
    // Create confirmation dialog
    const confirmDialog = document.createElement('div');
    confirmDialog.className = 'fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50';
    confirmDialog.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus item ini dari keranjang?</p>
            <div class="flex justify-end gap-3">
                <button id="cancelRemove" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button id="confirmRemove" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(confirmDialog);
    
    // Handle dialog buttons
    document.getElementById('cancelRemove').addEventListener('click', () => {
        document.body.removeChild(confirmDialog);
    });
    
    document.getElementById('confirmRemove').addEventListener('click', () => {
        document.body.removeChild(confirmDialog);
        
        // Visual feedback - fade out
        cartItem.style.transition = 'opacity 0.5s, transform 0.5s';
        cartItem.style.opacity = '0.5';
        cartItem.style.transform = 'translateX(20px)';
        
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
                // Smooth removal animation
                setTimeout(() => {
                    cartItem.style.height = cartItem.offsetHeight + 'px';
                    cartItem.style.overflow = 'hidden';
                    setTimeout(() => {
                        cartItem.style.height = '0';
                        cartItem.style.padding = '0';
                        cartItem.style.margin = '0';
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    }, 50);
                }, 300);
            } else {
                showToast(data.message || 'Gagal menghapus item', 'error');
                cartItem.style.opacity = '1';
                cartItem.style.transform = 'translateX(0)';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus item', 'error');
            cartItem.style.opacity = '1';
            cartItem.style.transform = 'translateX(0)';
        });
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white'
    }`;
    toast.innerHTML = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        toast.style.transition = 'opacity 0.5s, transform 0.5s';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 500);
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateOrderSummary();
});
</script>
<?php $this->load->view('templates/footer') ?>