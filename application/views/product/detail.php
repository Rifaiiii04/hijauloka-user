<?php $this->load->view('templates/header'); ?>

<main class="container mx-auto px-1 py-1 mt-4 md:mt-20 mt-5 max-w-5xl">
    <!-- Breadcrumb -->
    <div class="mb-1">
        <nav class="text-[10px] text-gray-600">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="<?= base_url() ?>" class="hover:text-green-600">Beranda</a>
                    <svg class="w-3 h-3 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-green-600"><?= $product['nama_product'] ?></li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded shadow py-2 overflow-hidden">
        <div class="md:flex md:space-x-4">
            <!-- Product Images -->
            <div class="md:w-2/5 p-1">
                <div class="relative h-[250px]">
                    <?php 
                    $mainImage = $product['gambar'];
                    $images = [];
                    
                    // Check if gambar is already an array
                    if (is_array($mainImage)) {
                        $images = $mainImage;
                        $mainImage = !empty($images) ? $images[0] : '';
                    } 
                    // If it's a string, check if it contains multiple images
                    else if (is_string($mainImage) && strpos($mainImage, ',') !== false) {
                        $images = array_map('trim', explode(',', $mainImage));
                        $mainImage = $images[0];
                    }
                    ?>
                    <img src="https://admin.hijauloka.my.id/uploads/<?= $mainImage ?>" 
                         alt="<?= $product['nama_product'] ?>" 
                         class="w-full h-full object-contain rounded-lg">
                </div>
                
                <?php if (!empty($images) && count($images) > 1): ?>
                <div class="grid grid-cols-4 gap-0.5 mt-0.5">
                    <?php foreach($images as $image): ?>
                    <div class="relative h-14 left-1.5 gap-5">
                        <img src="https://admin.hijauloka.my.id/uploads/<?= $image ?>" 
                             alt="Product thumbnail" 
                             class="w-full h-full object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="md:w-3/5 p-2 space-y-1.5">
                <div class="flex flex-wrap gap-0.5">
                    <?php foreach($categories as $category): ?>
                        <span class="px-1.5 py-0.5 bg-green-100 text-green-800 text-[10px] rounded-full">
                            <?= $category['nama_kategori'] ?>
                        </span>
                    <?php endforeach; ?>
                </div>

                <h1 class="text-lg font-bold text-gray-900"><?= $product['nama_product'] ?></h1>
                
                <div class="flex items-center">
                    <div class="flex text-yellow-400 text-xs">
                        <?php 
                        $rating = floatval($product['rating'] ?? 0);
                        for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $rating): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif ($i - 0.5 <= $rating): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="text-gray-500 text-[10px] ml-1">(<?= number_format($rating, 1) ?>)</span>
                </div>

                <p class="text-lg font-bold text-green-600">
                    Rp<?= number_format($product['harga'], 0, ',', '.') ?>
                </p>

                <div>
                    <h2 class="text-sm font-semibold">Deskripsi Tanaman</h2>
                    <p class="text-[11px] text-gray-600"><?= nl2br($product['desk_product']) ?></p>
                </div>

              <div>
    <h2 class="text-sm font-semibold">Cara Merawat Tanaman</h2>
    
    <?php if (!empty($product['cara_rawat_video'])): ?>
        <!-- Video Section -->
        <div class="mt-2">
            <video controls class="w-full rounded-lg" style="max-height: 300px">
                <source src="https://admin.hijauloka.my.id/uploads/videos/<?= $product['cara_rawat_video'] ?>" type="video/mp4">
                Browser tidak mendukung pemutar video
            </video>
        </div>
    <?php else: ?>
        <!-- Placeholder jika tidak ada video -->
        <div class="bg-gray-50 rounded p-2 text-center">
            <div class="text-gray-400">
                <i class="fas fa-seedling text-lg"></i>
            </div>
            <p class="text-[11px] text-gray-600 font-medium">Coming Soon!</p>
            <p class="text-[10px] text-gray-500">Panduan perawatan tanaman dengan ilustrasi akan tersedia segera.</p>
        </div>
    <?php endif; ?>
</div>
                <!-- Quantity and Actions -->
                <div class="flex items-center gap-1.5 pt-1">
                    <div class="flex items-center border rounded">
                        <button class="px-2 py-0.5 text-gray-600 text-xs" onclick="updateQuantity(-1)">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?= $product['stok'] ?>"
                               class="w-8 text-center border-x py-0.5 text-xs">
                        <button class="px-2 py-0.5 text-gray-600 text-xs" onclick="updateQuantity(1)">+</button>
                    </div>
                    <span class="text-[10px] text-gray-500">Stok: <?= $product['stok'] ?></span>
                </div>

                <div class="flex gap-1.5 pt-1">
                    <button onclick="addToCart(<?= $product['id_product'] ?>)" 
                            class="flex-1 bg-green-600 text-white py-1.5 px-3 rounded text-xs hover:bg-green-700">
                        <i class="fas fa-shopping-cart text-[10px] mr-1"></i>
                        Tambah ke Keranjang
                    </button>
                    <button onclick="buyNow(<?= $product['id_product'] ?>)"
                            class="flex-1 bg-blue-600 text-white py-1.5 px-3 rounded text-xs hover:bg-blue-700 transition-all">
                        <i class="fas fa-bolt text-[10px] mr-1"></i>
                        Beli Sekarang
                    </button>
                    <button onclick="toggleWishlist(this, <?= $product['id_product'] ?>)" 
                            class="p-1.5 border rounded">
                        <i class="fas fa-heart text-xs <?= $is_wishlisted ? 'text-red-500' : 'text-gray-400' ?>"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section - Updated with actual reviews and review form -->
    <section class="px-2 py-4">
        <h2 class="text-lg font-bold text-gray-900 mb-3">Ulasan Pembeli</h2>
        
        <!-- Review Statistics -->
        <div class="bg-white rounded-md shadow p-4 mb-4">
            <div class="flex items-center gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-900 mb-2"><?= number_format($rating, 1) ?></div>
                    <div class="flex text-yellow-400 justify-center mb-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $rating): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif ($i - 0.5 <= $rating): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="text-gray-500 text-sm">Dari <?= count($reviews) ?> ulasan</div>
                </div>
                <div class="flex-1">
                    <div class="space-y-2">
                        <?php 
                        $rating_counts = [0, 0, 0, 0, 0];
                        foreach ($reviews as $review) {
                            if ($review['rating'] >= 1 && $review['rating'] <= 5) {
                                $rating_counts[$review['rating']-1]++;
                            }
                        }
                        $total_reviews = count($reviews);
                        ?>
                        
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="flex items-center gap-4">
                            <div class="flex text-yellow-400">
                                <?php for ($j = 1; $j <= $i; $j++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="flex-1">
                                <div class="h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-yellow-400 rounded-full" 
                                         style="width: <?= $total_reviews > 0 ? ($rating_counts[$i-1] / $total_reviews * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                            <div class="text-gray-500 w-12 text-right"><?= $rating_counts[$i-1] ?></div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check if user can review this product -->
        <?php if ($can_review): ?>
        <div class="bg-white rounded-md shadow p-4 mb-4">
            <h3 class="text-md font-semibold text-gray-800 mb-3">Berikan Ulasan Anda</h3>
            <form id="reviewForm" action="<?= base_url('product/submit_review') ?>" method="post">
                <input type="hidden" name="id_product" value="<?= $product['id_product'] ?>">
                
                <!-- Star Rating -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <div class="flex gap-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors" 
                                data-rating="<?= $i ?>">
                            <i class="fas fa-star"></i>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0">
                </div>
                
                <!-- Review Text -->
                <div class="mb-3">
                    <label for="review" class="block text-sm font-medium text-gray-700 mb-1">Ulasan</label>
                    <textarea id="review" name="ulasan" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    Kirim Ulasan
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Comments List -->
        <div class="space-y-4">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                <div class="bg-white rounded-md shadow p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                    <?php if (!empty($review['profile_image'])): ?>
                                        <img src="<?= base_url('uploads/profile/' . $review['profile_image']) ?>" alt="Profile" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <i class="fas fa-user text-gray-400"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800"><?= $review['nama'] ?></h4>
                                    <div class="flex text-yellow-400 text-xs">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas <?= $i <= $review['rating'] ? 'fa-star' : 'fa-star text-gray-300' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500"><?= date('d M Y', strtotime($review['tgl_review'])) ?></span>
                    </div>
                    <p class="text-gray-600 text-sm"><?= nl2br(htmlspecialchars($review['ulasan'])) ?></p>
                    
                    <!-- Review Photo - Add this section -->
                    <?php if (!empty($review['foto_review'])): ?>
                    <div class="mt-3">
                        <a href="<?= base_url('uploads/reviews/' . $review['foto_review']) ?>" target="_blank" class="block">
                            <img src="<?= base_url('uploads/reviews/' . $review['foto_review']) ?>" 
                                 alt="Review Photo" 
                                 class="h-24 object-cover rounded-md border border-gray-200 hover:opacity-90 transition-opacity">
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bg-white rounded-md shadow p-4 text-center">
                    <div class="text-gray-400 mb-1">
                        <i class="far fa-comment-dots text-2xl"></i>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Belum ada ulasan</p>
                    <p class="text-xs text-gray-500 mt-1">Jadilah yang pertama memberikan ulasan untuk produk ini</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Add this JavaScript for the rating functionality -->
<script>
// Rating functionality
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('ratingInput');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            
            // Update star colors
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });
    
    // Form validation
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            const rating = parseInt(ratingInput.value);
            const review = document.getElementById('review').value.trim();
            
            if (rating === 0) {
                e.preventDefault();
                showNotification('error', 'Error', 'Silakan berikan rating (1-5 bintang)');
                return false;
            }
            
            if (review.length < 10) {
                e.preventDefault();
                showNotification('error', 'Error', 'Ulasan harus minimal 10 karakter');
                return false;
            }
            
            return true;
        });
    }
});
</script>

<!-- Add this after header -->
<!-- Login Prompt Modal -->
<div id="loginPrompt" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl">
        <div class="text-center mb-6">
            <i class="fas fa-lock text-4xl text-green-600 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-900">Login Required</h3>
            <p class="text-gray-600 mt-2">Please login or create an account to add items to your wishlist</p>
        </div>
        <div class="space-y-3">
            <a href="<?= base_url('auth') ?>" class="flex items-center justify-center gap-2 w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-all">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login to Your Account</span>
            </a>
            <a href="<?= base_url('auth/register') ?>" class="flex items-center justify-center gap-2 w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-all">
                <i class="fas fa-user-plus"></i>
                <span>Create New Account</span>
            </a>
            <button onclick="closeLoginPrompt()" class="w-full text-gray-500 hover:text-gray-700 py-2">Maybe Later</button>
        </div>
    </div>
</div>

<!-- Add these styles -->
<style>
    @keyframes heartbeat {
        0% { transform: scale(1); }
        25% { transform: scale(1.3); }
        50% { transform: scale(1); }
        75% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
    
    @keyframes heartbeat-out {
        0% { transform: scale(1); }
        50% { transform: scale(0.7); }
        100% { transform: scale(1); }
    }
    
    .animate-heartbeat {
        animation: heartbeat 0.5s ease-in-out;
    }
    
    .animate-heartbeat-out {
        animation: heartbeat-out 0.5s ease-in-out;
    }
    
    .fa-heart {
        transition: color 0.2s ease-in-out;
    }
</style>


</style>

<!-- Add custom notification styles -->
<style>
    .custom-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        max-width: 300px;
        background-color: white;
        border-left: 4px solid #10b981;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        border-radius: 4px;
        padding: 16px;
        transform: translateX(400px);
        transition: transform 0.3s ease-out;
        z-index: 9999;
    }
    
    .custom-notification.show {
        transform: translateX(0);
    }
    
    .custom-notification.error {
        border-left-color: #ef4444;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
    }
    
    .notification-icon {
        margin-right: 12px;
        font-size: 20px;
    }
    
    .notification-icon.success {
        color: #10b981;
    }
    
    .notification-icon.error {
        color: #ef4444;
    }
    
    .notification-text h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .notification-text p {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }
</style>

<!-- Update the wishlist toggle function -->
<script>
function toggleWishlist(button, productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    const icon = button.querySelector('i');
    
    if (icon.classList.contains('text-red-500')) {
        icon.classList.remove('text-red-500');
        icon.classList.add('animate-heartbeat-out');
    } else {
        icon.classList.add('text-red-500');
        icon.classList.add('animate-heartbeat');
    }
    
    setTimeout(() => {
        icon.classList.remove('animate-heartbeat', 'animate-heartbeat-out');
    }, 500);

    fetch('<?= base_url('wishlist/toggle/') ?>' + productId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .catch(error => {
        console.error('Error:', error);
        if (icon.classList.contains('text-red-500')) {
            icon.classList.remove('text-red-500');
        } else {
            icon.classList.add('text-red-500');
        }
    });
}

function closeLoginPrompt() {
    document.getElementById('loginPrompt').classList.add('hidden');
}

document.getElementById('loginPrompt').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginPrompt();
    }
});
</script>

<!-- In your Product Controller, add this to the detail method -->
<?php
// Add this to get wishlist status
$is_wishlisted = $this->session->userdata('logged_in') ? 
    $this->wishlist_model->is_wishlisted($this->session->userdata('id_user'), $product['id_product']) : 
    false;
$data['is_wishlisted'] = $is_wishlisted;
?>
<script>
function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const newValue = parseInt(input.value) + change;
    if (newValue >= 1 && newValue <= <?= $product['stok'] ?>) {
        input.value = newValue;
    }
}

function addToCart(productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    const quantity = document.getElementById('quantity').value;
    console.log('Adding to cart:', productId, 'Quantity:', quantity);
    
    // Show loading state
    const button = document.querySelector('button[onclick="addToCart(' + productId + ')"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-[10px] mr-1"></i> Menambahkan...';
    button.disabled = true;

    // Create form data
    const formData = new FormData();
    formData.append('id_product', productId);
    formData.append('quantity', quantity);

    // Add to cart via AJAX
    fetch('<?= base_url('cart/add') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            // Show custom notification
            showNotification('success', 'Berhasil!', 'Produk telah ditambahkan ke keranjang');
            
            // Update cart counter if it exists
            const cartCounter = document.querySelector('.cart-counter');
            if (cartCounter && data.cartCount) {
                cartCounter.textContent = data.cartCount;
                cartCounter.classList.remove('hidden');
            }
        } else {
            showNotification('error', 'Gagal', data.message || 'Terjadi kesalahan saat menambahkan produk ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        
        showNotification('error', 'Oops...', 'Terjadi kesalahan saat menghubungi server');
    });
}

// Add this function to show custom notifications
function showNotification(type, title, message) {
    // Remove any existing notifications
    const existingNotification = document.querySelector('.custom-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'custom-notification' + (type === 'error' ? ' error' : '');
    
    // Create notification content
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon ${type}">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            </div>
            <div class="notification-text">
                <h4>${title}</h4>
                <p>${message}</p>
            </div>
        </div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show notification with a slight delay
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function buyNow(productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    const quantity = document.getElementById('quantity').value;
    
    // Show loading state
    const button = document.querySelector('button[onclick="buyNow(' + productId + ')"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-[10px] mr-1"></i> Memproses...';
    button.disabled = true;

    // Create form data
    const formData = new FormData();
    formData.append('id_product', productId);
    formData.append('quantity', quantity);

    // Add to cart and proceed to checkout
    fetch('<?= base_url('cart/add_and_checkout') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to checkout - fixed URL to match your application's route
            window.location.href = '<?= base_url('checkout/metode') ?>';
        } else {
            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
            showNotification('error', 'Gagal', data.message || 'Terjadi kesalahan saat memproses pembelian');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        showNotification('error', 'Oops...', 'Terjadi kesalahan saat menghubungi server');
    });
}
</script>

<?php $this->load->view('templates/footer'); ?>