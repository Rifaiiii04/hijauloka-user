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

<!-- Add these functions to the existing script section -->
<script>
function handleCartClick(event, productId) {
    event.preventDefault();
    
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    fetch('<?= base_url('cart/add') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `id_product=${productId}&jumlah=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cartNotification').classList.remove('hidden');
            setTimeout(() => {
                closeCartNotification();
            }, 2000);
        } else {
            alert(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menambahkan ke keranjang');
    });
}

function closeCartNotification() {
    document.getElementById('cartNotification').classList.add('hidden');
}

// Close notification when clicking outside
document.getElementById('cartNotification').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCartNotification();
    }
});
</script>

<div class="mb-12 mt-28 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
        Katalog Tanaman
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
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
    <div class="relative">
        <input type="text" 
               id="searchProduct" 
               placeholder="Cari tanaman..." 
               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

<!-- Replace the main content section with this new layout -->
<main class="container mx-auto px-4">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-24">
                <h3 class="font-semibold text-lg text-green-800 mb-4 border-b pb-2">Filter Produk</h3>
                
                <!-- Price Range Filter -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-700 mb-3">Rentang Harga</h4>
                    <div class="px-2">
                        <div class="flex justify-between mb-2">
                            <span id="minPriceLabel" class="text-sm text-gray-600">Rp0</span>
                            <span id="maxPriceLabel" class="text-sm text-gray-600">Rp1.000.000</span>
                        </div>
                        <div class="relative mb-4">
                            <div class="slider-track h-1 bg-gray-200 rounded-full absolute inset-0"></div>
                            <input type="range" id="minPriceSlider" min="0" max="1000000" value="0" step="10000"
                                   class="absolute w-full h-1 bg-transparent appearance-none pointer-events-auto">
                            <input type="range" id="maxPriceSlider" min="0" max="1000000" value="1000000" step="10000"
                                   class="absolute w-full h-1 bg-transparent appearance-none pointer-events-auto">
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" id="minPrice" placeholder="Min" value="0"
                                   class="w-full p-2 text-sm border rounded-md">
                            <span class="text-gray-400">-</span>
                            <input type="number" id="maxPrice" placeholder="Max" value="1000000"
                                   class="w-full p-2 text-sm border rounded-md">
                        </div>
                    </div>
                </div>
                
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
            <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($produk_populer as $produk) : ?>
                    <?php 
                    if (!empty($produk['gambar'])) {
                        $gambarArr = explode(',', $produk['gambar']);
                        $gambar = trim($gambarArr[0]);
                    } else {
                        $gambar = 'default.jpg';
                    }
                    
                    // Initialize product categories array if not set
                    $product_categories_data = [];
                    
                    // Check if product has an ID before querying categories
                    if (isset($produk['id_product'])) {
                        // Fetch categories for this specific product
                        $this->db->select('c.nama_kategori, c.id_kategori');
                        $this->db->from('product_category pc');
                        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
                        $this->db->where('pc.id_product', $produk['id_product']);
                        $product_categories_data = $this->db->get()->result_array();
                    }
                    
                    // Create a string of category IDs for data attribute
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
                                <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
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
                                    <button onclick="handleCartClick(event, <?= isset($produk['id_product']) ? $produk['id_product'] : '0' ?>)"
                                            class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
                                        <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
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
// Filter and Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Price slider styling
    const minPriceSlider = document.getElementById('minPriceSlider');
    const maxPriceSlider = document.getElementById('maxPriceSlider');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const minPriceLabel = document.getElementById('minPriceLabel');
    const maxPriceLabel = document.getElementById('maxPriceLabel');
    const searchInput = document.getElementById('searchProduct');
    const productGrid = document.getElementById('productGrid');
    const noResults = document.getElementById('noResults');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const sortBySelect = document.getElementById('sortBy');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const ratingCheckboxes = document.querySelectorAll('.rating-checkbox');
    
    const priceGap = 10000;
    
    function formatCurrency(value) {
        return 'Rp' + parseInt(value).toLocaleString('id-ID');
    }
    
    // Initialize price labels
    minPriceLabel.textContent = formatCurrency(minPriceSlider.value);
    maxPriceLabel.textContent = formatCurrency(maxPriceSlider.value);
    
    // Min price slider
    minPriceSlider.addEventListener('input', function() {
        let minVal = parseInt(minPriceSlider.value);
        let maxVal = parseInt(maxPriceSlider.value);
        
        if(maxVal - minVal < priceGap) {
            minVal = maxVal - priceGap;
            minPriceSlider.value = minVal;
        }
        
        minPriceInput.value = minVal;
        minPriceLabel.textContent = formatCurrency(minVal);
    });
    
    // Max price slider
    maxPriceSlider.addEventListener('input', function() {
        let minVal = parseInt(minPriceSlider.value);
        let maxVal = parseInt(maxPriceSlider.value);
        
        if(maxVal - minVal < priceGap) {
            maxVal = minVal + priceGap;
            maxPriceSlider.value = maxVal;
        }
        
        maxPriceInput.value = maxVal;
        maxPriceLabel.textContent = formatCurrency(maxVal);
    });
    
    // Min price input
    minPriceInput.addEventListener('input', function() {
        let minVal = parseInt(minPriceInput.value) || 0;
        let maxVal = parseInt(maxPriceInput.value) || 1000000;
        
        if(minVal < 0) minVal = 0;
        if(minVal > maxVal - priceGap) minVal = maxVal - priceGap;
        
        minPriceSlider.value = minVal;
        minPriceLabel.textContent = formatCurrency(minVal);
    });
    
    // Max price input
    maxPriceInput.addEventListener('input', function() {
        let minVal = parseInt(minPriceInput.value) || 0;
        let maxVal = parseInt(maxPriceInput.value) || 1000000;
        
        if(maxVal > 1000000) maxVal = 1000000;
        if(maxVal < minVal + priceGap) maxVal = minVal + priceGap;
        
        maxPriceSlider.value = maxVal;
        maxPriceLabel.textContent = formatCurrency(maxVal);
    });
    
    // Filter products function
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const minPrice = parseInt(minPriceSlider.value);
        const maxPrice = parseInt(maxPriceSlider.value);
        const selectedCategories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const selectedRatings = Array.from(ratingCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => parseInt(cb.value));
        const sortBy = sortBySelect.value;
        
        const productCards = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const productName = card.getAttribute('data-name');
            const productPrice = parseInt(card.getAttribute('data-price'));
            const productRating = parseFloat(card.getAttribute('data-rating'));
            const productCategories = card.getAttribute('data-categories').split(',');
            
            // Check if product matches all filters
            const matchesSearch = productName.includes(searchTerm);
            const matchesPrice = productPrice >= minPrice && productPrice <= maxPrice;
            const matchesCategory = selectedCategories.length === 0 || 
                                   productCategories.some(cat => selectedCategories.includes(cat));
            const matchesRating = selectedRatings.length === 0 || 
                                 selectedRatings.some(r => productRating >= r);
            
            if (matchesSearch && matchesPrice && matchesCategory && matchesRating) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            productGrid.classList.add('hidden');
            noResults.classList.remove('hidden');
        } else {
            productGrid.classList.remove('hidden');
            noResults.classList.add('hidden');
        }
        
        // Sort visible products
        sortProducts(sortBy);
    }
    
    // Sort products function
    function sortProducts(sortBy) {
        const productCards = Array.from(document.querySelectorAll('.product-card:not(.hidden)'));
        
        productCards.sort((a, b) => {
            const aPrice = parseInt(a.getAttribute('data-price'));
            const bPrice = parseInt(b.getAttribute('data-price'));
            const aRating = parseFloat(a.getAttribute('data-rating'));
            const bRating = parseFloat(b.getAttribute('data-rating'));
            const aId = parseInt(a.getAttribute('data-id'));
            const bId = parseInt(b.getAttribute('data-id'));
            
            switch(sortBy) {
                case 'price_low':
                    return aPrice - bPrice;
                case 'price_high':
                    return bPrice - aPrice;
                case 'rating':
                    return bRating - aRating;
                case 'newest':
                    return bId - aId; // Assuming newer products have higher IDs
                default: // popular
                    return 0; // Keep original order
            }
        });
        
        // Reorder elements in the DOM
        const parent = productGrid;
        productCards.forEach(card => {
            parent.appendChild(card);
        });
    }
    
    // Reset all filters
    function resetFilters() {
        searchInput.value = '';
        minPriceSlider.value = 0;
        maxPriceSlider.value = 1000000;
        minPriceInput.value = 0;
        maxPriceInput.value = 1000000;
        minPriceLabel.textContent = formatCurrency(0);
        maxPriceLabel.textContent = formatCurrency(1000000);
        
        categoryCheckboxes.forEach(cb => {
            cb.checked = false;
        });
        
        ratingCheckboxes.forEach(cb => {
            cb.checked = false;
        });
        
        sortBySelect.value = 'popular';
        
        filterProducts();
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterProducts);
    applyFiltersBtn.addEventListener('click', filterProducts);
    resetFiltersBtn.addEventListener('click', resetFilters);
    clearFiltersBtn.addEventListener('click', resetFilters);
    sortBySelect.addEventListener('change', () => sortProducts(sortBySelect.value));
    
    // Add event listeners to all checkboxes
    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });
    
    ratingCheckboxes.forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });
    
    // Initial filter
    filterProducts();
});
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
</style>

<?php $this->load->view('templates/footer') ?>