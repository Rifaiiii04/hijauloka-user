<div class="container mx-auto px-4 py-8">
    <!-- Add animation styles -->
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
        
        @keyframes bounce-once {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-bounce-once {
            animation: bounce-once 0.5s ease-in-out;
        }
    </style>
    
    <!-- Login Prompt Modal -->
    <div id="loginPrompt" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <i class="fas fa-lock text-4xl text-green-600 mb-4"></i>
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
    
    <!-- Cart Notification -->
    <div id="cartNotification" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl transform transition-all animate-bounce-once">
            <div class="text-center mb-4">
                <i class="fas fa-check-circle text-5xl text-green-500 mb-3"></i>
                <h3 class="text-xl font-semibold text-gray-900">Berhasil!</h3>
                <p class="text-gray-600 mt-2">Produk telah ditambahkan ke keranjang</p>
            </div>
            <button onclick="closeCartNotification()" 
                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors mt-4">
                Lanjut Belanja
            </button>
        </div>
    </div>
    
    <!-- Category Header -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 mt-22">
        <div class="flex items-center justify-between">
            <div>
                <a href="<?= base_url('category/plants') ?>" class="inline-flex items-center text-green-600 hover:text-green-700 mb-3 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-800"><?= $category_name ?></h1>
                <p class="text-gray-600 mt-2"><?= $category_description ?></p>
            </div>
            <div class="hidden md:block">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas <?= $category_icon ?? 'fa-leaf' ?> text-green-600 text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Sort Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center">
                <span class="text-gray-700 font-medium mr-3">Urutkan:</span>
                <select id="sortProducts" class="bg-gray-50 border border-gray-200 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="newest">Terbaru</option>
                    <option value="price_low">Harga: Rendah ke Tinggi</option>
                    <option value="price_high">Harga: Tinggi ke Rendah</option>
                    <option value="name_asc">Nama: A-Z</option>
                    <option value="name_desc">Nama: Z-A</option>
                </select>
            </div>
            
            <div class="flex items-center">
                <span class="text-gray-700 font-medium mr-3">Tampilkan:</span>
                <select id="limitProducts" class="bg-gray-50 border border-gray-200 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
                    <option value="48">48</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <?php 
                // Get the first image from comma-separated list or use default
                $gambar = 'default.jpg';
                if (!empty($product->gambar)) {
                    $gambarArr = explode(',', $product->gambar);
                    $gambar = trim($gambarArr[0]);
                }
                ?>
                <div class="product-card bg-white rounded-lg overflow-hidden shadow-lg h-full flex flex-col transform hover:scale-105 transition-all duration-300"
                     data-id="<?= $product->id_product ?>"
                     data-name="<?= strtolower($product->nama_product) ?>"
                     data-price="<?= $product->harga ?>"
                     data-rating="<?= floatval($product->rating ?? 0) ?>">
                    <a href="<?= base_url('product/detail/' . $product->id_product) ?>" class="block flex-grow">
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="http://localhost/hijauloka/uploads/<?= $gambar ?>" 
                                 alt="<?= htmlspecialchars($product->nama_product) ?>" 
                                 class="w-full h-36 sm:h-48 object-cover transform hover:scale-110 transition-all duration-300">
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-base sm:text-xl font-semibold mb-1 sm:mb-2 line-clamp-1"><?= htmlspecialchars($product->nama_product) ?></h3>
                            <div class="flex flex-wrap gap-1 sm:gap-2 mb-2 sm:mb-3">
                                <?php if (isset($product->nama_kategori)): ?>
                                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-800 text-[10px] sm:text-xs rounded-full"><?= $product->nama_kategori ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>

                    <div class="p-3 sm:p-4">
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <?php 
                                $rating = floatval($product->rating ?? 0);
                                for ($i = 1; $i <= 5; $i++) : ?>
                                    <?php if ($i <= $rating) : ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif ($i - 0.5 <= $rating) : ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else : ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(<?= number_format($rating, 1) ?>)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <?php if (isset($product->diskon) && $product->diskon > 0): ?>
                                <?php 
                                $discounted_price = $product->harga - ($product->harga * $product->diskon / 100);
                                ?>
                                <span class="text-sm sm:text-lg font-bold">Rp<?= number_format($discounted_price, 0, ',', '.') ?></span>
                                <span class="text-gray-400 text-xs line-through">Rp<?= number_format($product->harga, 0, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="text-sm sm:text-lg font-bold">Rp<?= number_format($product->harga, 0, ',', '.') ?></span>
                            <?php endif; ?>
                            <div class="flex gap-2">
                                <button class="wishlist-btn bg-gray-100 text-gray-600 p-2 sm:p-2.5 rounded-md hover:bg-gray-200 transition-colors"
                                        data-product-id="<?= $product->id_product ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="add-to-cart-btn bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors"
                                        data-product-id="<?= $product->id_product ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full py-12 text-center">
                <div class="bg-gray-50 rounded-lg p-8 inline-block">
                    <i class="fas <?= $category_icon ?? 'fa-leaf' ?> text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada produk dalam kategori ini.</p>
                    <a href="<?= base_url() ?>" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors duration-300">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?= $pagination ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort products functionality
    document.getElementById('sortProducts').addEventListener('change', function() {
        const sortValue = this.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', sortValue);
        window.location.href = currentUrl.toString();
    });
    
    // Limit products functionality
    document.getElementById('limitProducts').addEventListener('change', function() {
        const limitValue = this.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('limit', limitValue);
        window.location.href = currentUrl.toString();
    });
    
    // Set selected values based on URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('sort')) {
        document.getElementById('sortProducts').value = urlParams.get('sort');
    }
    if (urlParams.has('limit')) {
        document.getElementById('limitProducts').value = urlParams.get('limit');
    }
    
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            
            // Check if user is logged in
            <?php if ($this->session->userdata('user_id')): ?>
                // Add to cart AJAX request
                fetch('<?= base_url('cart/add') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId + '&quantity=1'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('Produk berhasil ditambahkan ke keranjang');
                        // Update cart count if needed
                        if (document.getElementById('cartCount')) {
                            document.getElementById('cartCount').textContent = data.cart_count;
                        }
                    } else {
                        alert(data.message || 'Gagal menambahkan produk ke keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan produk ke keranjang');
                });
            <?php else: ?>
                // Show login prompt
                document.getElementById('loginPrompt').classList.remove('hidden');
            <?php endif; ?>
        });
    });
    
    // Wishlist functionality
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            
            // Check if user is logged in
            <?php if ($this->session->userdata('user_id')): ?>
                // Toggle wishlist AJAX request
                fetch('<?= base_url('wishlist/toggle') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update wishlist icon
                        const icon = this.querySelector('i');
                        if (data.action === 'added') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            icon.classList.add('text-red-500');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.remove('text-red-500');
                            icon.classList.add('far');
                        }
                        
                        // Show message
                        alert(data.action === 'added' ? 'Produk ditambahkan ke wishlist' : 'Produk dihapus dari wishlist');
                    } else {
                        alert(data.message || 'Gagal memperbarui wishlist');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui wishlist');
                });
            <?php else: ?>
                // Show login prompt
                document.getElementById('loginPrompt').classList.remove('hidden');
            <?php endif; ?>
        });
    });
});
</script>