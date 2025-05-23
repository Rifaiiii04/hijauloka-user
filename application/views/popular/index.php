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
        animation: heartbeat 0.8s ease-in-out;
    }
    
    .animate-heartbeat-out {
        animation: heartbeat-out 0.5s ease-in-out;
    }
    
    /* Custom notification styles */
    .custom-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 16px;
        z-index: 1000;
        max-width: 350px;
        transform: translateX(400px);
        transition: transform 0.3s ease-out;
    }
    
    .custom-notification.show {
        transform: translateX(0);
    }
    
    .custom-notification.error {
        border-left: 4px solid #ef4444;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
    }
    
    .notification-icon {
        margin-right: 12px;
        font-size: 24px;
    }
    
    .notification-icon.success {
        color: #10b981;
    }
    
    .notification-icon.error {
        color: #ef4444;
    }
    
    .notification-text h4 {
        margin: 0 0 4px 0;
        font-weight: 600;
        font-size: 16px;
    }
    
    .notification-text p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }
    
    /* Range slider styles */
    input[type="range"] {
        -webkit-appearance: none;
        height: 5px;
        border-radius: 5px;
        background: #d1d5db;
        outline: none;
    }
    
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #10b981;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    input[type="range"]::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #10b981;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Aspect ratio container for product images */
    .aspect-w-1 {
        position: relative;
        padding-bottom: 100%;
        height: 0;
        overflow: hidden;
    }
    
    .aspect-w-1 img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Line clamp for product titles */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Improved scrollbar for filter sidebar */
    .sticky::-webkit-scrollbar {
        width: 6px;
    }
    
    .sticky::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .sticky::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .sticky::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Smooth transitions */
    #searchSuggestions {
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Improved search input */
    #searchProduct:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
</style>

<div class="mb-12 mt-28 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
        Katalog Tanaman
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
    </h1>
    <p class="text-gray-600 mt-3">Temukan berbagai koleksi tanaman hias pilihan untuk rumah Anda</p>
</div>

<!-- Add this after the category filter and before the main content -->
<div class="container mx-auto px-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="relative flex-grow">
            <input type="text" 
                   id="searchProduct" 
                   placeholder="Cari tanaman..." 
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <div id="searchSuggestions" class="absolute z-10 w-full bg-white mt-1 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
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
            <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-xl transform transition-transform duration-300 ease-in-out overflow-y-auto">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg text-green-800">Filter Produk</h3>
                        <button id="closeMobileFilter" class="p-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="overflow-y-auto h-[calc(100vh-8rem)]">
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
            </div>
        </div>

        <!-- Desktop Filters - Make it sticky -->
        <div class="w-full md:w-64 hidden md:block flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-24 max-h-[calc(100vh-120px)] overflow-y-auto">
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
            <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
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
                            <div class="aspect-w-1">
                                <img src="https://admin.hijauloka.my.id/uploads/<?= $gambar; ?>" 
                                     alt="<?= isset($produk['nama_product']) ? $produk['nama_product'] : 'Product'; ?>" 
                                     class="w-full h-full object-cover transform hover:scale-110 transition-all duration-300">
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
            
            <!-- No Results Message -->
            <div id="noResults" class="hidden py-12 text-center">
                <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada produk yang ditemukan</h3>
                <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
                <button id="clearFilters" class="mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Hapus Semua Filter
                </button>
            </div>
            
            <!-- Pagination Controls -->
            <div id="paginationControls" class="mt-8 flex justify-center items-center">
                <button id="prevPage" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-l-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-1"></i> Prev
                </button>
                <div id="pageNumbers" class="flex mx-2"></div>
                <button id="nextPage" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-r-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
</main>

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

<script>
// Pass PHP variables to JavaScript
var isUserLoggedIn = <?= $this->session->userdata('logged_in') ? 'true' : 'false' ?>;
var baseUrl = '<?= base_url() ?>';
</script>

<!-- Include the external JavaScript file -->
<script src="<?= base_url('assets/js/popular.js') ?>"></script>


<?php $this->load->view('templates/footer'); ?>