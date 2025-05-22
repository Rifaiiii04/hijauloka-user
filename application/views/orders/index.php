<?php $this->load->view('templates/header2'); ?>
<?php
// Ambil status dari query string
$status = $_GET['status'] ?? 'all';
$status_map = [
    'all' => 'Semua',
    'pending' => 'Menunggu',
    'diproses' => 'Diproses',
    'dikirim' => 'Dikirim',
    'selesai' => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
];
// Filter orders sesuai status
$filtered_orders = ($status === 'all') ? $orders : array_filter($orders, function($o) use ($status) {
    return $o['stts_pemesanan'] === $status;
});
?>
<div class="container mx-auto max-w-2xl py-12 p-3 min-h-[60vh]">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-home"></i>
                <span class="font-medium">Beranda</span>
            </a>
            <h2 class="md:text-3xl text-lg font-extrabold text-green-800 tracking-tight">Pesanan Saya</h2>
        </div>
    </div>
    <!-- Top Bar Tab Filter -->
    <div class="flex justify-center mb-8 gap-2 flex-wrap">
        <?php foreach ($status_map as $key => $label): ?>
            <a href="?status=<?= $key ?>" class="px-4 py-2 rounded-full font-semibold text-sm transition-all hover:scale-105
                <?= $status === $key ? 'bg-green-600 text-white shadow animate-bounce-subtle' : 'bg-gray-100 text-green-800 hover:bg-green-200' ?>
                "><?= $label ?></a>
        <?php endforeach; ?>
    </div>
    <?php if (empty($filtered_orders)): ?>
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center animate-fade-in">
            <i class="fas fa-box-open text-5xl text-gray-300 mb-4 animate-float"></i>
            <p class="text-gray-600 text-lg mb-2">Tidak ada pesanan <?= $status_map[$status] ?? '' ?>.</p>
            <a href="<?= base_url('popular') ?>" class="inline-block mt-4 px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all font-semibold text-lg shadow-md hover:scale-105">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php foreach ($filtered_orders as $order): ?>
                <?php
                $status_icon = [
                    'pending' => 'fa-hourglass-half text-yellow-500',
                    'diproses' => 'fa-cogs text-blue-500',
                    'dikirim' => 'fa-truck text-indigo-500',
                    'selesai' => 'fa-check-circle text-green-500',
                    'dibatalkan' => 'fa-times-circle text-red-500',
                ];
                $icon = $status_icon[$order['stts_pemesanan']] ?? 'fa-hourglass text-gray-400';
                $badge_color = [
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'diproses' => 'bg-blue-100 text-blue-700',
                    'dikirim' => 'bg-indigo-100 text-indigo-700',
                    'selesai' => 'bg-green-100 text-green-700',
                    'dibatalkan' => 'bg-red-100 text-red-700',
                ];
                $badge = $badge_color[$order['stts_pemesanan']] ?? 'bg-gray-100 text-gray-700';
                $pay_badge = $order['stts_pembayaran'] == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 animate-slide-in hover:scale-[1.02] flex flex-col">
                    <!-- Order Header -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas <?= $icon ?> text-2xl animate-pulse"></i>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="font-bold text-green-800 text-base truncate">#<?= $order['id_order'] ?></div>
                            <div class="text-xs text-gray-400"><?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></div>
                        </div>
                    </div>

                    <!-- Status Badges -->
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $badge ?> capitalize animate-fade-in"><?= $order['stts_pemesanan'] ?></span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $pay_badge ?> capitalize animate-fade-in"><?= $order['stts_pembayaran'] ?></span>
                    </div>

                    <!-- Total -->
                    <div class="text-sm text-gray-700 mb-4">
                        Total: <span class="font-bold text-green-700">Rp<?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-auto space-y-2">
                        <a href="<?= base_url('orders/detail/' . $order['id_order']) ?>" 
                           class="w-full px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-all shadow text-sm flex items-center justify-center gap-2 hover:scale-105">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        
                        <?php if ($order['stts_pemesanan'] === 'pending'): ?>
                            <button onclick="cancelOrder(<?= $order['id_order'] ?>)" 
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-all shadow text-sm flex items-center justify-center gap-2 hover:scale-105">
                                <i class="fas fa-times"></i> Batalkan
                            </button>
                        <?php endif; ?>
                        
                        <?php if ($order['stts_pemesanan'] === 'dikirim'): ?>
                            <button onclick="completeOrder(<?= $order['id_order'] ?>)" 
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-all shadow text-sm flex items-center justify-center gap-2 hover:scale-105">
                                <i class="fas fa-check"></i> Selesai
                            </button>
                        <?php endif; ?>
                        
                        <?php if ($order['stts_pemesanan'] === 'selesai'): ?>
                            <button onclick="showReviewModal(<?= $order['id_order'] ?>)" 
                                    class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition-all shadow text-sm flex items-center justify-center gap-2 hover:scale-105">
                                <i class="fas fa-star"></i> Beri Ulasan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Beri Ulasan Produk</h3>
            <button onclick="closeReviewModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="orderProducts" class="mb-4">
            <!-- Products will be loaded here -->
            <div class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500 mx-auto"></div>
                <p class="mt-2 text-sm text-gray-600">Memuat produk...</p>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-in {
    from { 
        opacity: 0;
        transform: translateX(-20px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

@keyframes bounce-subtle {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.animate-fade-in {
    animation: fade-in 0.6s cubic-bezier(.4,0,.2,1);
}

.animate-slide-in {
    animation: slide-in 0.6s cubic-bezier(.4,0,.2,1);
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-bounce-subtle {
    animation: bounce-subtle 1s infinite;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>

<script>
let currentOrderId = null;

function showReviewModal(orderId) {
    currentOrderId = orderId;
    document.getElementById('reviewModal').classList.remove('hidden');
    
    // Load products for this order
    fetch(`<?= base_url('orders/get_order_products/') ?>${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const productsHtml = data.products.map(product => `
                    <div class="border-b pb-4 mb-4">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="http://localhost/hijauloka/uploads/${product.gambar.split(',')[0]}" 
                                 alt="${product.nama_product}" 
                                 class="w-16 h-16 object-cover rounded-md">
                            <div>
                                <h4 class="font-medium text-gray-800">${product.nama_product}</h4>
                                <p class="text-sm text-gray-500">Rp${new Intl.NumberFormat('id-ID').format(product.harga_satuan)}</p>
                            </div>
                        </div>
                        
                        <form action="<?= base_url('product/submit_review') ?>" method="post" class="review-form">
                            <input type="hidden" name="id_product" value="${product.id_product}">
                            <input type="hidden" name="id_order" value="${orderId}">
                            
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <div class="flex gap-1">
                                    ${[1,2,3,4,5].map(star => `
                                        <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors" 
                                                data-rating="${star}" onclick="setRating(this, ${star})">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    `).join('')}
                                </div>
                                <input type="hidden" name="rating" class="rating-input" value="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ulasan</label>
                                <textarea name="ulasan" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                          placeholder="Bagikan pengalaman Anda dengan produk ini"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition-all">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                `).join('');
                
                document.getElementById('orderProducts').innerHTML = productsHtml;
                
                // Add form submission handlers
                document.querySelectorAll('.review-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const ratingInput = this.querySelector('.rating-input');
                        const ulasan = this.querySelector('textarea[name="ulasan"]').value.trim();
                        
                        if (ratingInput.value === '0') {
                            alert('Silakan berikan rating (1-5 bintang)');
                            return false;
                        }
                        
                        if (ulasan.length < 10) {
                            alert('Ulasan harus minimal 10 karakter');
                            return false;
                        }
                        
                        this.submit();
                    });
                });
            } else {
                document.getElementById('orderProducts').innerHTML = `
                    <div class="text-center py-4">
                        <div class="text-red-500 mb-2">
                            <i class="fas fa-exclamation-circle text-2xl"></i>
                        </div>
                        <p class="text-gray-700">Gagal memuat produk. Silakan coba lagi.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('orderProducts').innerHTML = `
                <div class="text-center py-4">
                    <div class="text-red-500 mb-2">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                    </div>
                    <p class="text-gray-700">Terjadi kesalahan. Silakan coba lagi.</p>
                </div>
            `;
        });
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    currentOrderId = null;
}

function setRating(button, rating) {
    const starsContainer = button.parentElement;
    const ratingInput = starsContainer.parentElement.querySelector('.rating-input');
    ratingInput.value = rating;
    
    // Update star colors
    const stars = starsContainer.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        // Show loading indicator
        const loadingToast = showToast('Membatalkan pesanan...', 'loading');
        
        // Send cancel request to server
        fetch(`<?= base_url('orders/cancel/') ?>${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading indicator
            hideToast(loadingToast);
            
            if (data.success) {
                showToast('Pesanan berhasil dibatalkan', 'success');
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast(data.message || 'Gagal membatalkan pesanan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideToast(loadingToast);
            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        });
    }
}

function completeOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini?')) {
        // Show loading indicator
        const loadingToast = showToast('Menyelesaikan pesanan...', 'loading');
        
        // Send complete request to server
        fetch(`<?= base_url('order/complete/') ?>${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading indicator
            hideToast(loadingToast);
            
            if (data.success) {
                showToast('Pesanan berhasil diselesaikan', 'success');
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast(data.message || 'Gagal menyelesaikan pesanan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideToast(loadingToast);
            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        });
    }
}

// Toast notification functions
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white flex items-center z-50 animate-fade-in';
    
    // Set background color based on type
    if (type === 'success') {
        toast.classList.add('bg-green-600');
    } else if (type === 'error') {
        toast.classList.add('bg-red-600');
    } else if (type === 'loading') {
        toast.classList.add('bg-blue-600');
    } else {
        toast.classList.add('bg-gray-800');
    }
    
    // Add icon based on type
    let icon = '';
    if (type === 'success') {
        icon = '<i class="fas fa-check-circle mr-2"></i>';
    } else if (type === 'error') {
        icon = '<i class="fas fa-exclamation-circle mr-2"></i>';
    } else if (type === 'loading') {
        icon = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>';
    } else {
        icon = '<i class="fas fa-info-circle mr-2"></i>';
    }
    
    toast.innerHTML = `${icon}<span>${message}</span>`;
    document.body.appendChild(toast);
    
    // Auto remove toast after 5 seconds unless it's a loading toast
    if (type !== 'loading') {
        setTimeout(() => {
            hideToast(toast);
        }, 5000);
    }
    
    return toast;
}

function hideToast(toast) {
    toast.classList.add('opacity-0');
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 300);
}

document.getElementById('reviewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewModal();
    }
});
</script>