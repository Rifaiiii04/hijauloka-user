<?php $this->load->view('templates/header'); ?>

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

<!-- Add this style section near the top of the file -->
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

<!-- Replace the toggleWishlist function with this improved version -->
<script>
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

function closeLoginPrompt() {
    const modal = document.getElementById('loginPrompt');
    modal.classList.add('hidden');
}

document.getElementById('loginPrompt').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginPrompt();
    }
});
</script>

<!-- Add this after the login prompt modal -->
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

<!-- Add these styles to the existing style section -->
<style>
    @keyframes bounce-once {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-bounce-once {
        animation: bounce-once 0.5s ease-in-out;
    }
</style>

<!-- Replace the handleCartClick function with this improved version
function handleCartClick(event, productId) {
    event.preventDefault();
    
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    // Show loading state
    const button = event.currentTarget;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    fetch('<?= base_url('cart/add') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `id_product=${productId}&jumlah=1`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('cartNotification').classList.remove('hidden');
            setTimeout(() => {
                closeCartNotification();
            }, 2000);
        } else {
            // Show error message
            const errorMessage = data.message || 'Gagal menambahkan ke keranjang';
            alert(errorMessage);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan ke keranjang. Silakan coba lagi.');
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}

// Add this function to handle cart notification
function closeCartNotification() {
    document.getElementById('cartNotification').classList.add('hidden');
}

// Close notification when clicking outside
document.getElementById('cartNotification').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCartNotification();
    }
}); -->

<div class="mb-12 mt-28 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
        Katalog Tanaman
    </h1>
    <p class="text-gray-600 mt-3">Temukan berbagai koleksi tanaman hias pilihan untuk rumah Anda</p>
</div>

<!-- Category Filter -->
<!-- <div class="container mx-auto px-4 mb-8">
    <div class="flex gap-4 overflow-x-auto pb-4">
        <a href="<?= base_url('popular') ?>" 
           class="px-4 py-2 rounded-full whitespace-nowrap <?= empty($selected_category) ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
            Semua
        </a>
        <?php foreach ($categories as $category): ?>
            <a href="<?= base_url('popular?kategori=' . $category['id_kategori']) ?>" 
               class="px-4 py-2 rounded-full whitespace-nowrap <?= ($selected_category == $category['id_kategori']) ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                <?= $category['nama_kategori'] ?>
            </a>
        <?php endforeach; ?>
    </div>
</div> -->

<!-- Add this after the category filter and before the main content -->
<div class="container mx-auto px-4 mb-6">
    <div class="flex items-center mx-auto justify-between w-92">
        <div class="relative flex-grow">
            <input type="text" 
                   id="searchProduct" 
                   placeholder="Cari tanaman..." 
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <!-- Mobile Filter Button -->
        <button id="mobileFilterBtn" class="md:hidden ml-4 p-2 bg-green-600 text-white rounded-lg">
            <i class="fas fa-filter"></i>
        </button>
    </div>
</div>

<!-- Replace the main content section with this new layout -->
<main class="container mx-auto px-4">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Mobile Filter Sidebar -->
        <div id="mobileFilterSidebar" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden md:hidden">
            <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-xl transform transition-transform duration-300 ease-in-out">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg text-green-800">Filter Produk</h3>
                        <button id="closeMobileFilter" class="p-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="overflow-y-auto h-[calc(100vh-8rem)]">
                        <!-- Category Filter -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Kategori</h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                <?php foreach ($categories as $category): ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="category" value="<?= $category['id_kategori'] ?>" 
                                           class="category-checkbox w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                           <?= ($selected_category == $category['id_kategori']) ? 'checked' : '' ?>>
                                    <span class="text-gray-700"><?= $category['nama_kategori'] ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Rating Filter -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Rating</h4>
                            <div class="space-y-2">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="rating" value="<?= $i ?>" 
                                           class="rating-checkbox w-4 h-4 text-green-600 rounded focus:ring-green-500">
                                    <div class="flex text-yellow-400">
                                        <?php for($j = 1; $j <= 5; $j++): ?>
                                            <?php if($j <= $i): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if($i == 5): ?>
                                        <span class="text-sm text-gray-600">& Up</span>
                                    <?php endif; ?>
                                </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <!-- Sort By -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Urutkan</h4>
                            <select id="sortBy" class="w-full p-2 border rounded-md text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="popular">Popularitas</option>
                                <option value="price_low">Harga: Rendah ke Tinggi</option>
                                <option value="price_high">Harga: Tinggi ke Rendah</option>
                                <option value="rating">Rating Tertinggi</option>
                                <option value="newest">Terbaru</option>
                            </select>
                        </div>
                        
                        <!-- Apply/Reset Buttons -->
                        <div class="flex gap-2">
                            <button id="resetFilters" class="w-1/2 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-colors">
                                Reset
                            </button>
                            <button id="applyFilters" class="w-1/2 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Filters -->
        <div class="w-full md:w-64 hidden md:flex flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-24 max-h-[calc(100vh-120px)] overflow-y-auto">
                <h3 class="font-semibold text-lg text-green-800 mb-4 border-b pb-2 sticky top-0 bg-white z-10">Filter Produk</h3>
                
                <!-- Category Filter -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-700 mb-3">Kategori</h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        <?php foreach ($categories as $category): ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="category" value="<?= $category['id_kategori'] ?>" 
                                   class="category-checkbox w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                   <?= ($selected_category == $category['id_kategori']) ? 'checked' : '' ?>>
                            <span class="text-gray-700"><?= $category['nama_kategori'] ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Rating Filter -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-700 mb-3">Rating</h4>
                    <div class="space-y-2">
                        <?php for($i = 5; $i >= 1; $i--): ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="rating" value="<?= $i ?>" 
                                   class="rating-checkbox w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <div class="flex text-yellow-400">
                                <?php for($j = 1; $j <= 5; $j++): ?>
                                    <?php if($j <= $i): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <?php if($i == 5): ?>
                                <span class="text-sm text-gray-600">& Up</span>
                            <?php endif; ?>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <!-- Sort By -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-700 mb-3">Urutkan</h4>
                    <select id="sortBy" class="w-full p-2 border rounded-md text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="popular">Popularitas</option>
                        <option value="price_low">Harga: Rendah ke Tinggi</option>
                        <option value="price_high">Harga: Tinggi ke Rendah</option>
                        <option value="rating">Rating Tertinggi</option>
                        <option value="newest">Terbaru</option>
                    </select>
                </div>
                
                <!-- Apply/Reset Buttons -->
                <div class="flex gap-2">
                    <button id="resetFilters" class="w-1/2 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-colors">
                        Reset
                    </button>
                    <button id="applyFilters" class="w-1/2 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Product Grid -->
        <div class="flex-grow">
            <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <?php 
                // Get first 12 products (4 rows x 3 columns)
                $initial_products = array_slice($produk_populer, 0, 12);
                foreach ($initial_products as $produk) : 
                    if (!empty($produk['gambar'])) {
                        $gambarArr = explode(',', $produk['gambar']);
                        $gambar = trim($gambarArr[0]);
                    } else {
                        $gambar = 'default.jpg';
                    }
                    
                    // Get product categories
                    $product_categories_data = [];
                    if (isset($produk['id_product'])) {
                        $this->db->select('c.nama_kategori, c.id_kategori');
                        $this->db->from('product_category pc');
                        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
                        $this->db->where('pc.id_product', $produk['id_product']);
                        $product_categories_data = $this->db->get()->result_array();
                    }
                    
                    $category_ids = [];
                    if (!empty($product_categories_data)) {
                        foreach ($product_categories_data as $cat) {
                            if (isset($cat['id_kategori'])) {
                                $category_ids[] = $cat['id_kategori'];
                            }
                        }
                    }
                    $category_ids_str = implode(',', $category_ids);
                ?>
                    <div class="product-card bg-white rounded-lg overflow-hidden shadow-lg h-full flex flex-col transform hover:scale-105 transition-all duration-300"
                         data-id="<?= isset($produk['id_product']) ? $produk['id_product'] : '0' ?>"
                         data-name="<?= strtolower(isset($produk['nama_product']) ? $produk['nama_product'] : '') ?>"
                         data-price="<?= isset($produk['harga']) ? $produk['harga'] : '0' ?>"
                         data-rating="<?= floatval(isset($produk['rating']) ? $produk['rating'] : 0) ?>"
                         data-categories="<?= $category_ids_str ?>">
                        <a href="<?= base_url('product/detail/' . (isset($produk['id_product']) ? $produk['id_product'] : '0')) ?>" class="block flex-grow">
                            <div class="aspect-w-1 aspect-h-1">
                                <img src="https://admin.hijauloka.my.id/uploads/<?= $gambar; ?>" 
                                     alt="<?= isset($produk['nama_product']) ? $produk['nama_product'] : 'Product'; ?>" 
                                     class="w-full h-36 sm:h-48 object-cover transform hover:scale-110 transition-all duration-300">
                            </div>
                            <div class="p-3 sm:p-4">
                                <h3 class="text-base sm:text-xl font-semibold mb-1 sm:mb-2 line-clamp-1"><?= isset($produk['nama_product']) ? $produk['nama_product'] : 'Product'; ?></h3>
                                <div class="flex flex-wrap gap-1 sm:gap-2 mb-2 sm:mb-3">
                                    <?php if (!empty($product_categories_data)) : ?>
                                        <?php foreach ($product_categories_data as $cat) : ?>
                                            <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-800 text-[10px] sm:text-xs rounded-full"><?= $cat['nama_kategori'] ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>

                        <div class="p-3 sm:p-4">
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <?php 
                                    $rating = floatval(isset($produk['rating']) ? $produk['rating'] : 0);
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
                                <span class="text-sm sm:text-lg font-bold">Rp<?= number_format(isset($produk['harga']) ? $produk['harga'] : 0, 0, ',', '.'); ?></span>
                                <div class="flex gap-2">
                                    <?php 
                                    $is_wishlisted = false;
                                    if ($this->session->userdata('logged_in') && isset($produk['id_product'])) {
                                        $is_wishlisted = $this->wishlist_model->is_wishlisted($this->session->userdata('id_user'), $produk['id_product']);
                                    }
                                    ?>
                                    <button onclick="toggleWishlist(this, <?= isset($produk['id_product']) ? $produk['id_product'] : '0' ?>)"
                                            class="wishlist-btn bg-gray-100 text-gray-600 p-2 sm:p-2.5 rounded-md hover:bg-gray-200 transition-colors <?= $is_wishlisted ? 'active' : '' ?>">
                                        <i class="fas fa-heart <?= $is_wishlisted ? 'text-red-500' : '' ?>"></i>
                                    </button>
                                    <button onclick="addToCartCard(<?= isset($produk['id_product']) ? $produk['id_product'] : '0' ?>, this)"
                                            class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
                                        <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Load More Button -->
            <?php if (count($produk_populer) > 12): ?>
            <div class="text-center mt-8">
                <button id="loadMoreBtn" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 mx-auto">
                    <span>Load More</span>
                    <i class="fas fa-spinner fa-spin hidden"></i>
                </button>
            </div>
            <?php endif; ?>
            
            <!-- No Results Message -->
            <div id="noResults" class="hidden py-12 text-center">
                <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada produk yang ditemukan</h3>
                <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
                <button id="clearFilters" class="mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Hapus Semua Filter
                </button>
            </div>
        </div>
    </div>
</main>

<!-- Add this to your existing script section -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchProduct');
    const productGrid = document.getElementById('productGrid');
    const noResults = document.getElementById('noResults');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const sortBySelect = document.getElementById('sortBy');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const ratingCheckboxes = document.querySelectorAll('.rating-checkbox');
    
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    let currentPage = 1;
    const productsPerPage = 12; // 4 rows x 3 columns
    let allProducts = <?= json_encode($produk_populer) ?>;
    let filteredProducts = [...allProducts];
    let remainingProducts = allProducts.slice(productsPerPage); // Products after initial 12
    
    // Function to create product card HTML
    function createProductCard(produk) {
        const gambar = produk.gambar ? produk.gambar.split(',')[0].trim() : 'default.jpg';
        const category_ids = produk.categories ? produk.categories.map(cat => cat.id_kategori).join(',') : '';
        const is_wishlisted = <?= $this->session->userdata('logged_in') ? 'true' : 'false' ?>;
        
        return `
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-lg h-full flex flex-col transform hover:scale-105 transition-all duration-300"
                 data-id="${produk.id_product || '0'}"
                 data-name="${(produk.nama_product || '').toLowerCase()}"
                 data-price="${produk.harga || '0'}"
                 data-rating="${parseFloat(produk.rating || 0)}"
                 data-categories="${category_ids}">
                <a href="${baseUrl}product/detail/${produk.id_product || '0'}" class="block flex-grow">
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="https://admin.hijauloka.my.id/uploads/${gambar}" 
                             alt="${produk.nama_product || 'Product'}" 
                             class="w-full h-36 sm:h-48 object-cover transform hover:scale-110 transition-all duration-300">
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-xl font-semibold mb-1 sm:mb-2 line-clamp-1">${produk.nama_product || 'Product'}</h3>
                        <div class="flex flex-wrap gap-1 sm:gap-2 mb-2 sm:mb-3">
                            ${produk.categories ? produk.categories.map(cat => 
                                `<span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-800 text-[10px] sm:text-xs rounded-full">${cat.nama_kategori}</span>`
                            ).join('') : ''}
                        </div>
                    </div>
                </a>
                <div class="p-3 sm:p-4">
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            ${generateStarRating(produk.rating || 0)}
                        </div>
                        <span class="text-gray-500 text-xs ml-1">(${parseFloat(produk.rating || 0).toFixed(1)})</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm sm:text-lg font-bold">Rp${formatNumber(produk.harga || 0)}</span>
                        <div class="flex gap-2">
                            <button onclick="toggleWishlist(this, ${produk.id_product || '0'})"
                                    class="wishlist-btn bg-gray-100 text-gray-600 p-2 sm:p-2.5 rounded-md hover:bg-gray-200 transition-colors ${is_wishlisted ? 'active' : ''}">
                                <i class="fas fa-heart ${is_wishlisted ? 'text-red-500' : ''}"></i>
                            </button>
                            <button onclick="addToCartCard(${produk.id_product || '0'}, this)"
                                    class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Helper function to generate star rating HTML
    function generateStarRating(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star"></i>';
            } else if (i - 0.5 <= rating) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            } else {
                stars += '<i class="far fa-star"></i>';
            }
        }
        return stars;
    }
    
    // Helper function to format numbers
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Function to load more products
    function loadMoreProducts() {
        const spinner = loadMoreBtn.querySelector('.fa-spinner');
        const buttonText = loadMoreBtn.querySelector('span');
        
        spinner.classList.remove('hidden');
        buttonText.textContent = 'Loading...';
        loadMoreBtn.disabled = true;
        
        setTimeout(() => {
            const start = (currentPage - 1) * productsPerPage;
            const end = start + productsPerPage;
            const productsToAdd = filteredProducts.slice(start, end);
            
            if (productsToAdd.length > 0) {
                const productGrid = document.getElementById('productGrid');
                productsToAdd.forEach(produk => {
                    const card = createProductCard(produk);
                    productGrid.insertAdjacentHTML('beforeend', card);
                });
                
                currentPage++;
                
                // Hide load more button if no more products
                if (end >= filteredProducts.length) {
                    loadMoreBtn.style.display = 'none';
                }
            }
            
            spinner.classList.add('hidden');
            buttonText.textContent = 'Load More';
            loadMoreBtn.disabled = false;
        }, 500);
    }
    
    // Update filterProducts function
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const selectedRatings = Array.from(ratingCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => parseInt(cb.value));
        const sortBy = sortBySelect.value;
        
        // Filter products
        filteredProducts = allProducts.filter(produk => {
            const productName = produk.nama_product.toLowerCase();
            const productRating = parseFloat(produk.rating || 0);
            const productCategories = produk.categories ? 
                produk.categories.map(cat => cat.id_kategori.toString()) : [];
            
            const matchesSearch = productName.includes(searchTerm);
            const matchesCategory = selectedCategories.length === 0 || 
                                   productCategories.some(cat => selectedCategories.includes(cat));
            const matchesRating = selectedRatings.length === 0 || 
                                 selectedRatings.some(r => productRating >= r);
            
            return matchesSearch && matchesCategory && matchesRating;
        });
        
        // Sort filtered products
        filteredProducts.sort((a, b) => {
            const aPrice = parseInt(a.harga || 0);
            const bPrice = parseInt(b.harga || 0);
            const aRating = parseFloat(a.rating || 0);
            const bRating = parseFloat(b.rating || 0);
            const aId = parseInt(a.id_product || 0);
            const bId = parseInt(b.id_product || 0);
            
            switch(sortBy) {
                case 'price_low':
                    return aPrice - bPrice;
                case 'price_high':
                    return bPrice - aPrice;
                case 'rating':
                    return bRating - aRating;
                case 'newest':
                    return bId - aId;
                default:
                    return 0;
            }
        });
        
        // Reset pagination
        currentPage = 1;
        
        // Update product grid
        const productGrid = document.getElementById('productGrid');
        productGrid.innerHTML = '';
        
        if (filteredProducts.length === 0) {
            productGrid.classList.add('hidden');
            noResults.classList.remove('hidden');
            loadMoreBtn.style.display = 'none';
        } else {
            productGrid.classList.remove('hidden');
            noResults.classList.add('hidden');
            
            // Show initial 12 products (4 rows)
            const initialProducts = filteredProducts.slice(0, productsPerPage);
            initialProducts.forEach(produk => {
                const card = createProductCard(produk);
                productGrid.insertAdjacentHTML('beforeend', card);
            });
            
            // Show/hide load more button
            if (filteredProducts.length > productsPerPage) {
                loadMoreBtn.style.display = 'flex';
            } else {
                loadMoreBtn.style.display = 'none';
            }
        }
    }
    
    // Add load more button event listener
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMoreProducts);
    }
    
    // ... rest of your existing event listeners ...
    
    // Initial filter
    filterProducts();
});

// Mobile filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterBtn = document.getElementById('mobileFilterBtn');
    const mobileFilterSidebar = document.getElementById('mobileFilterSidebar');
    const closeMobileFilter = document.getElementById('closeMobileFilter');
    
    mobileFilterBtn.addEventListener('click', () => {
        mobileFilterSidebar.classList.remove('hidden');
    });
    
    closeMobileFilter.addEventListener('click', () => {
        mobileFilterSidebar.classList.add('hidden');
    });
    
    // Close mobile filter when clicking outside
    mobileFilterSidebar.addEventListener('click', (e) => {
        if (e.target === mobileFilterSidebar) {
            mobileFilterSidebar.classList.add('hidden');
        }
    });
    
    // ... rest of your existing script ...
});

// 1. Tambahkan fungsi showNotification jika belum ada
function showNotification(type, title, message) {
    // Remove any existing notifications
    const existingNotification = document.querySelector('.custom-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'custom-notification' + (type === 'error' ? ' error' : '');
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
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// 2. Tambahkan fungsi addToCartCard
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

// 3. Ganti tombol keranjang pada card produk
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-card .fa-shopping-cart').forEach(function(icon) {
        const button = icon.closest('button');
        if (button) {
            button.onclick = function() {
                addToCartCard(button.closest('.product-card').getAttribute('data-id'), button);
            };
        }
    });
});

// 4. Tambahkan style notifikasi jika belum ada
</script>

<style>
/* Custom styles for range sliders */
input[type="range"] {
    -webkit-appearance: none;
    height: 5px;
    background: #ddd;
    border-radius: 5px;
    background-image: linear-gradient(#22c55e, #22c55e);
    background-repeat: no-repeat;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #22c55e;
    cursor: pointer;
    box-shadow: 0 0 2px 0 #555;
}

input[type="range"]::-moz-range-thumb {
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #22c55e;
    cursor: pointer;
    box-shadow: 0 0 2px 0 #555;
}

/* Checkbox styling */
.category-checkbox, .rating-checkbox {
    accent-color: #22c55e;
}

/* Add responsive styles */
@media (max-width: 768px) {
    .product-card {
        height: auto;
    }
    
    .product-card img {
        height: 120px;
        object-fit: cover;
    }
    
    .product-card h3 {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    
    .product-card .price {
        font-size: 0.875rem;
    }
    
    .product-card .rating {
        font-size: 0.75rem;
    }
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

<?php $this->load->view('templates/footer') ?>