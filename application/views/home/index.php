<?php $this->load->view('templates/header'); ?>

<!-- Login Prompt Modal - Place this right after header -->
<div id="loginPrompt" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl transform transition-all animate-fade-in">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-3xl text-green-600"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900">Login Required</h3>
            <p class="text-gray-600 mt-2">Please login or create an account to add items to your wishlist</p>
        </div>
        <div class="space-y-3">
            <a href="<?= base_url('auth') ?>" 
               class="flex items-center justify-center gap-2 w-full bg-green-600 text-white py-3 rounded-lg text-center hover:bg-green-700 transition-all duration-300">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login to Your Account</span>
            </a>
            <a href="<?= base_url('auth/register') ?>" 
               class="flex items-center justify-center gap-2 w-full bg-gray-100 text-gray-700 py-3 rounded-lg text-center hover:bg-gray-200 transition-all duration-300">
                <i class="fas fa-user-plus"></i>
                <span>Create New Account</span>
            </a>
            <button onclick="closeLoginPrompt()" 
                    class="w-full text-gray-500 hover:text-gray-700 py-2 transition-colors duration-300">
                Maybe Later
            </button>
        </div>
    </div>
</div>

<!-- Add this animation to your existing style section -->
<style>
    @keyframes fade-in {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
</style>

<!-- Place this script after the modal but before the content -->
<script>
// Add this function if it doesn't exist
function closeLoginPrompt() {
    document.getElementById('loginPrompt').classList.add('hidden');
}

function toggleWishlist(button, productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    const icon = button.querySelector('i');
    
    // Toggle heart color immediately with animation
    if (icon.classList.contains('text-red-500')) {
        icon.classList.remove('text-red-500');
        icon.classList.add('animate-heartbeat-out');
    } else {
        icon.classList.add('text-red-500');
        icon.classList.add('animate-heartbeat');
    }
    
    // Remove animation class after it completes
    setTimeout(() => {
        icon.classList.remove('animate-heartbeat', 'animate-heartbeat-out');
    }, 500);

    // Send AJAX request to server
    fetch('<?= base_url('wishlist/toggle') ?>/' + productId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Wishlist updated:', data);
        // No need to update UI here as we've already done it
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert the UI change if there was an error
        if (icon.classList.contains('text-red-500')) {
            icon.classList.remove('text-red-500');
        } else {
            icon.classList.add('text-red-500');
        }
    });
}

function addToCartCard(productId, button) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    // Show loading state
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    const formData = new FormData();
    formData.append('id_product', productId);
    formData.append('quantity', 1);

    fetch('<?= base_url('cart/add') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalHTML;
        button.disabled = false;
        if (data.success) {
            showNotification('success', 'Berhasil!', 'Produk telah ditambahkan ke keranjang');
        } else {
            showNotification('error', 'Gagal', data.message || 'Terjadi kesalahan saat menambahkan produk ke keranjang');
        }
    })
    .catch(error => {
        button.innerHTML = originalHTML;
        button.disabled = false;
        showNotification('error', 'Oops...', 'Terjadi kesalahan saat menghubungi server');
    });
}

// Tambahkan fungsi showNotification jika belum ada
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
</script>

<!-- Make sure these animation styles are in your page -->
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
    
    /* Add this to make the heart transition smoother */
    .fa-heart {
        transition: color 0.2s ease-in-out;
    }

    .scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Chrome, Safari and Opera */
}

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

<?php $this->load->view('templates/section')?>

<!-- Top 10 Product -->
<section class="px-4 py-8">
    <h2 class="text-2xl font-bold text-green-800 mb-4">Rekomendasi Terbaik</h2>
    <div class="relative">
        <div class="overflow-x-auto scrollbar-hide ">
            <div class="flex gap-4 pb-4">
                <?php foreach ($featured_products as $product): ?>
                    <?php 
                    // Process image first
                    $productImage = 'default.jpg';
                    if (!empty($product['gambar'])) {
                        $gambarArr = explode(',', $product['gambar']);
                        $productImage = trim($gambarArr[0]);
                    }
                    ?>
                    <div class="w-72 flex-shrink-0">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <a href="<?= base_url('product/detail/' . $product['id_product']) ?>">
                                <img src="https://admin.hijauloka.my.id/uploads/<?= $productImage ?>" 
                                     alt="<?= $product['nama_product'] ?>"
                                     class="w-full h-48 object-cover hover:scale-110 transition-all duration-300">
                            </a>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2"><?= $product['nama_product'] ?></h3>
                                <!-- Rest of the card content stays the same -->
                                <div class="flex items-center mb-2 flex-shrink-0">
                                    <div class="flex text-yellow-400">
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
                                    <span class="text-gray-500 text-sm ml-2">(<?= number_format($rating, 1) ?>)</span>
                                </div>
                                <div class="mt-auto flex justify-between items-center flex-shrink-0">
                                    <span class="text-green-600 font-bold">Rp<?= number_format($product['harga'], 0, ',', '.') ?></span>
                                    <!-- In the featured products loop, update the wishlist button section -->
                                    <div class="flex gap-2">
                                        <?php 
                                        $is_wishlisted = $this->session->userdata('logged_in') ? 
                                            $this->wishlist_model->is_wishlisted($this->session->userdata('id_user'), $product['id_product']) : 
                                            false;
                                        ?>
                                        <button onclick="toggleWishlist(this, <?= $product['id_product'] ?>)" 
                                                class="wishlist-btn p-2 text-gray-600 bg-gray-100 rounded-md hover:text-red-500">
                                            <i class="fas fa-heart <?= $is_wishlisted ? 'text-red-500' : '' ?>"></i>
                                        </button>
                                        <button onclick="addToCartCard(<?= isset($product['id_product']) ? $product['id_product'] : '0' ?>, this)" 
                                                class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
                                            <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Section - Enhanced UX -->
<section class="px-4 py-12">
    <div class="container mx-auto">
        <!-- Desktop Header -->
        <div class="hidden md:flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-green-800 mb-2">Kategori Tanaman</h2>
                <p class="text-gray-600">Temukan berbagai jenis tanaman sesuai kebutuhan Anda</p>
            </div>
            <!-- <a href="<?= base_url('category') ?>" class="text-green-700 hover:text-green-900 font-medium flex items-center group transition-all duration-300">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a> -->
        </div>
        
        <!-- Mobile Header -->
        <div class="flex md:hidden items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-green-800">Kategori Tanaman</h2>
            <!--<a href="<?= base_url('category') ?>" class="text-green-700 text-sm hover:text-green-900 font-medium flex items-center">-->
            <!--    Lihat <i class="fas fa-chevron-right ml-1 text-xs"></i>-->
            <!--</a>-->
        </div>
        
        <!-- Desktop Layout -->
        <div class="hidden md:grid grid-cols-2 gap-6 max-w-5xl mx-auto">
            <!-- Plants Category -->
            <div class="relative h-[400px] rounded-xl overflow-hidden shadow-lg group hover:shadow-xl transition-all duration-300">
                <img src="<?= base_url('assets/img/plantcategory.png') ?>" alt="Plants" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-green-900 to-transparent opacity-80"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 transform transition-transform duration-300 group-hover:translate-y-[-10px]">
                    <h3 class="text-2xl font-bold text-white mb-2">Plants</h3>
                    <p class="text-green-100 mb-4">Koleksi tanaman hias indoor & outdoor</p>
                    <a href="<?= base_url('category/plants') ?>" class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all">
                        <i class="fas fa-leaf mr-2"></i> 
                        <span>Jelajahi</span>
                        <i class="fas fa-chevron-right ml-2 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-rows-2 gap-6">
                <!-- Seeds Category -->
                <div class="relative h-[190px] rounded-xl overflow-hidden shadow-lg group hover:shadow-xl transition-all duration-300">
                    <img src="<?= base_url('assets/img/seedscategory.png') ?>" alt="Seeds" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900 to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 transform transition-transform duration-300 group-hover:translate-y-[-5px]">
                        <h3 class="text-xl font-bold text-white mb-1">Seeds</h3>
                        <a href="<?= base_url('category/coming_soon') ?>" class="inline-flex items-center text-green-100 hover:text-white transition-colors">
                            <i class="fas fa-seedling mr-1"></i> 
                            <span>Lihat Koleksi</span>
                            <i class="fas fa-chevron-right ml-1 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all"></i>
                        </a>
                    </div>
                </div>

                <!-- Pots Category -->
                <div class="relative h-[190px] rounded-xl overflow-hidden shadow-lg group hover:shadow-xl transition-all duration-300">
                    <img src="<?= base_url('assets/img/category-pots.png') ?>" alt="Pots" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900 to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 transform transition-transform duration-300 group-hover:translate-y-[-5px]">
                        <h3 class="text-xl font-bold text-white mb-1">Pots</h3>
                        <a href="<?= base_url('category/coming_soon') ?>" class="inline-flex items-center text-green-100 hover:text-white transition-colors">
                            <i class="fas fa-box mr-1"></i> 
                            <span>Lihat Koleksi</span>
                            <i class="fas fa-chevron-right ml-1 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Layout -->
        <div class="grid md:hidden grid-cols-1 gap-4">
            <!-- Plants Category -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                <a href="<?= base_url('category/plants') ?>" class="block">
                    <div class="relative h-[180px]">
                        <img src="<?= base_url('assets/img/plantcategory.png') ?>" alt="Plants" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-xl font-bold text-white">Plants</h3>
                            <p class="text-white/80 text-sm">Koleksi tanaman hias indoor & outdoor</p>
                        </div>
                    </div>
                    <div class="p-3">
                        <button class="flex items-center text-green-700 text-sm font-medium">
                            <i class="fas fa-leaf mr-2"></i> Jelajahi
                        </button>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Seeds Category -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                    <a href="<?= base_url('category/coming_soon') ?>" class="block">
                        <div class="relative h-[120px]">
                            <img src="<?= base_url('assets/img/seedscategory.png') ?>" alt="Seeds" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <h3 class="text-lg font-bold text-white">Seeds</h3>
                            </div>
                        </div>
                        <div class="p-2">
                            <button class="flex items-center text-green-700 text-sm font-medium">
                                <span>Lihat</span>
                            </button>
                        </div>
                    </a>
                </div>

                <!-- Pots Category -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                    <a href="<?= base_url('category/coming_soon') ?>" class="block">
                        <div class="relative h-[120px]">
                            <img src="<?= base_url('assets/img/category-pots.png') ?>" alt="Pots" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <h3 class="text-lg font-bold text-white">Pots</h3>
                            </div>
                        </div>
                        <div class="p-2">
                            <button class="flex items-center text-green-700 text-sm font-medium">
                                <span>Lihat</span>
                            </button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section - Enhanced UX -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
            <div class="max-w-xl">
                <h2 class="text-3xl font-bold text-green-800 mb-3">Postingan Blog Terbaru</h2>
                <p class="text-gray-600">Tips perawatan tanaman, inspirasi dekorasi, dan panduan berkebun untuk membantu Anda merawat tanaman dengan baik</p>
            </div>
            <a href="<?= base_url('blog') ?>" class="mt-4 md:mt-0 px-6 py-3 bg-green-800 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center group">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <?php if (!empty($featured_blog_posts)): ?>
            <!-- Featured Blog Posts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <?php foreach ($featured_blog_posts as $post): ?>
                    <!-- Featured Post - Modern Design -->
                    <div class="rounded-xl overflow-hidden shadow-lg group h-[400px] relative hover:shadow-xl transition-all duration-300">
                        <img src="<?= !empty($post['featured_image']) ? 'http://localhost/hijauloka/uploads/blog/' . $post['featured_image'] : base_url('assets/img/news1.png') ?>" 
                             alt="<?= $post['title'] ?>"
                             onerror="this.onerror=null; this.src='<?= base_url('assets/img/news1.png') ?>';" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <!-- Modern glass morphism overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-70"></div>
                        <!-- Modern content container with glass effect -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 backdrop-blur-md bg-gradient-to-t from-black/100 to-black-10  transform transition-all duration-300 ">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full"><?= $post['author_name'] ?? 'Admin' ?></span>
                                <span class="text-white/80 text-xs"><?= date('d M, Y', strtotime($post['created_at'])) ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 group-hover:text-green-300 transition-colors line-clamp-2"><?= $post['title'] ?></h3>
                            <!-- Modern excerpt styling -->
                            <p class="text-white/80 text-sm mb-4 line-clamp-2"><?= $post['excerpt'] ?? substr(strip_tags($post['content']), 0, 120) . '...' ?></p>
                            <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white text-sm px-4 py-2 rounded-full transition-all group">
                                <span>Baca Selengkapnya</span>
                                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Small Blog Posts -->
            <?php if (!empty($small_blog_posts)): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($small_blog_posts as $post): ?>
                        <!-- Small Post -->
                        <div class="flex gap-4 group bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
                                <img src="<?= !empty($post['featured_image']) ? 'http://localhost/hijauloka/uploads/blog/' . $post['featured_image'] : base_url('assets/img/news1.png') ?>" 
                                     alt="<?= $post['title'] ?>"
                                     onerror="this.onerror=null; this.src='<?= base_url('assets/img/news1.png') ?>';" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 group-hover:text-green-700 transition-colors line-clamp-2 mb-2"><?= $post['title'] ?></h3>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <span>By <?= $post['author_name'] ?? 'Admin' ?></span>
                                    <span class="mx-2">•</span>
                                    <span><?= date('d M, Y', strtotime($post['created_at'])) ?></span>
                                </p>
                                <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="text-green-600 text-sm mt-2 inline-flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span>Baca</span>
                                    <i class="fas fa-arrow-right ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- No Blog Posts Message -->
            <div class="bg-gray-50 rounded-xl p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-newspaper text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Postingan Blog</h3>
                <p class="text-gray-500 max-w-md mx-auto">Kami sedang menyiapkan konten menarik untuk Anda. Kunjungi kembali halaman ini dalam waktu dekat.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Why Choose Us Section - Enhanced UX -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-green-800 mb-4">Mengapa Memilih HijauLoka?</h2>
        <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12">Kami berkomitmen memberikan pengalaman berbelanja tanaman terbaik dengan kualitas produk dan layanan yang terpercaya</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Feature 1 -->
            <div class="bg-white p-6 rounded-xl shadow-md text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-full -mr-12 -mt-12 transition-all duration-300 group-hover:scale-150"></div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 relative z-10 group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-leaf text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-green-800 relative z-10">Tanaman Berkualitas</h3>
                <p class="text-gray-600 relative z-10">Semua tanaman kami dirawat dengan teliti dan dipilih dari sumber terbaik untuk memastikan kesehatan dan pertumbuhan optimal.</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity relative z-10">
                    <a href="#" class="text-green-600 inline-flex items-center">
                        <span>Pelajari Lebih Lanjut</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white p-6 rounded-xl shadow-md text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-full -mr-12 -mt-12 transition-all duration-300 group-hover:scale-150"></div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 relative z-10 group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-truck text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-green-800 relative z-10">Pengiriman Aman</h3>
                <p class="text-gray-600 relative z-10">Kami menggunakan metode pengiriman khusus untuk memastikan tanaman Anda tiba dalam kondisi segar dan sehat.</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity relative z-10">
                    <a href="#" class="text-green-600 inline-flex items-center">
                        <span>Pelajari Lebih Lanjut</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-white p-6 rounded-xl shadow-md text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-full -mr-12 -mt-12 transition-all duration-300 group-hover:scale-150"></div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 relative z-10 group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-green-800 relative z-10">Garansi Tanaman</h3>
                <p class="text-gray-600 relative z-10">Kami memberikan garansi penggantian untuk tanaman yang tidak tumbuh dengan baik dalam 14 hari setelah pembelian.</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity relative z-10">
                    <a href="#" class="text-green-600 inline-flex items-center">
                        <span>Pelajari Lebih Lanjut</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call-to-action Section - Enhanced UX -->
<section class="py-16 bg-gradient-to-br from-green-800 to-green-900 text-white relative overflow-hidden">
    <!-- Decorative plant elements with animation -->
    <div class="absolute -left-16 -bottom-10 opacity-10 animate-float-slow">
        <i class="fas fa-leaf text-9xl transform rotate-45"></i>
    </div>
    <div class="absolute right-16 top-10 opacity-10 animate-float">
        <i class="fas fa-seedling text-8xl"></i>
    </div>
    <div class="absolute left-1/4 top-1/3 opacity-5 animate-float-reverse">
        <i class="fas fa-spa text-6xl"></i>
    </div>
    
    <div class="container mx-auto px-4 text-center relative z-10">
        <span class="inline-block px-4 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium mb-4">Mulai Perjalanan Hijau Anda</span>
        <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Hijaukan Rumah Anda <span class="text-green-300">Sekarang!</span></h2>
        <p class="text-lg mb-8 max-w-2xl mx-auto text-green-50">Temukan koleksi tanaman pilihan kami untuk menciptakan ruangan yang lebih segar, sehat, dan indah untuk Anda dan keluarga.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= base_url('popular') ?>" class="inline-block bg-white text-green-800 font-bold px-8 py-4 rounded-lg hover:bg-green-100 transition-all transform hover:-translate-y-1 hover:shadow-lg group">
                <div class="flex items-center justify-center">
                    <i class="fas fa-shopping-cart mr-2 group-hover:animate-bounce-once"></i>
                    <span>Belanja Sekarang</span>
                </div>
            </a>
            <!-- <a href="<?= base_url('category') ?>" class="inline-block bg-transparent border-2 border-white text-white font-bold px-8 py-4 rounded-lg hover:bg-white/10 transition-all transform hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-center">
                    <i class="fas fa-th-large mr-2"></i>
                    <span>Lihat Kategori</span>
                </div>
            </a> -->
        </div>
        
        <!-- Testimonial preview -->
        <div class="mt-12 max-w-4xl mx-auto bg-white/10 backdrop-blur-sm p-6 rounded-xl">
            <div class="flex items-center justify-center mb-4">
                <!-- <div class="flex -space-x-2">
                    <img src="<?= base_url('assets/img/avatar1.jpg') ?>" alt="Customer" class="w-10 h-10 rounded-full border-2 border-green-500">
                    <img src="<?= base_url('assets/img/avatar2.jpg') ?>" alt="Customer" class="w-10 h-10 rounded-full border-2 border-green-500">
                    <img src="<?= base_url('assets/img/avatar3.jpg') ?>" alt="Customer" class="w-10 h-10 rounded-full border-2 border-green-500">
                </div> -->
                <!-- <div class="ml-4 text-left">
                    <p class="text-sm font-medium">Bergabung dengan <span class="font-bold">1000+</span> pelanggan puas</p>
                    <div class="flex text-yellow-300 mt-1">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span class="ml-1 text-white text-xs">(4.9/5)</span>
                    </div>
                </div> -->
            </div>
            <p class="italic">"Tanaman dari HijauLoka selalu dalam kondisi prima. Pengiriman cepat dan aman. Sangat merekomendasikan untuk pecinta tanaman!"</p>
        </div>
    </div>
</section>

<!-- Add these animations to your existing style section -->
<style>
    /* Add to your existing animations */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    
    @keyframes float-slow {
        0%, 100% { transform: translateY(0) rotate(45deg); }
        50% { transform: translateY(-20px) rotate(45deg); }
    }
    
    @keyframes float-reverse {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(15px); }
    }
    
    @keyframes bounce-once {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-float-slow {
        animation: float-slow 8s ease-in-out infinite;
    }
    
    .animate-float-reverse {
        animation: float-reverse 7s ease-in-out infinite;
    }
    
    .group-hover\:animate-bounce-once:hover {
        animation: bounce-once 0.5s ease-in-out;
    }
</style>

<?php $this->load->view('templates/footer'); ?>
