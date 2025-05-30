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

<div class="mb-2 mt-28 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block">
        Katalog Tanaman
    </h1>
    <p class="text-gray-600 mt-3">Temukan berbagai koleksi tanaman hias pilihan untuk rumah Anda</p>
</div>

<!-- Search Section -->
<div class="container mx-auto px-4 mb-8">
    <div class="max-w-2xl mx-auto">
        <div class="relative group">
            <input type="text" 
                   id="searchProduct" 
                   placeholder="🔎 Cari tanaman hias..." 
                   class="w-full p-3 text-base rounded-xl border-2 border-gray-200 
                          focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:ring-opacity-50 
                          transition-all duration-200 ease-in-out
                          placeholder-gray-400 text-gray-700
                          shadow-sm hover:border-green-300"
                   autocomplete="off">
            <!-- <button type="button" 
                    id="searchButton"
                    class="absolute right-10 top-1/2
                           w-10 h-10 flex items-center justify-center
                           bg-green-600 text-white rounded-lg
                           hover:bg-green-700 active:bg-green-800
                           transition-colors duration-200
                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                
            </button> -->
            <!-- Search Suggestions -->
            <div id="searchSuggestions" 
                 class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200 
                        max-h-60 overflow-y-auto hidden group-focus-within:block">
            </div>
        </div>
        <!-- Search Tags -->
        <div id="searchTags" class="flex flex-wrap gap-2 mt-3">
            <button class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors"
                    onclick="setSearchTerm('Tanaman Hias Daun')">
                Tanaman Hias Daun
            </button>
            <button class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors"
                    onclick="setSearchTerm('Tanaman Hias Bunga')">
                Tanaman Hias Bunga
            </button>
            <button class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors"
                    onclick="setSearchTerm('Tanaman Hias Gantung')">
                Tanaman Hias Gantung
            </button>
        </div>
    </div>
</div>

<!-- Mobile Filter Button -->
<div class="container mx-auto px-4 mb-6">
    <div class="flex justify-end">
        <button id="mobileFilterBtn" class="md:hidden p-2 bg-green-600 text-white rounded-lg">
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
                $products_per_page = 15;
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $total_products = count($produk_populer);
                $total_pages = ceil($total_products / $products_per_page);
                $offset = ($current_page - 1) * $products_per_page;
                $paginated_products = array_slice($produk_populer, $offset, $products_per_page);
                
                foreach ($paginated_products as $produk) : 
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
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="flex justify-center items-center space-x-2 mt-8">
                <!-- Previous Button -->
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?= $current_page - 1 ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                
                <!-- Page Numbers -->
                <?php
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                if ($start_page > 1) {
                    echo '<a href="?page=1" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">1</a>';
                    if ($start_page > 2) {
                        echo '<span class="px-2 text-gray-500">...</span>';
                    }
                }
                
                for ($i = $start_page; $i <= $end_page; $i++) {
                    $active_class = $i === $current_page ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-white text-gray-700 hover:bg-gray-50';
                    echo '<a href="?page=' . $i . '" class="px-4 py-2 border border-gray-300 rounded-md ' . $active_class . ' transition-colors">' . $i . '</a>';
                }
                
                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<span class="px-2 text-gray-500">...</span>';
                    }
                    echo '<a href="?page=' . $total_pages . '" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">' . $total_pages . '</a>';
                }
                ?>
                
                <!-- Next Button -->
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?= $current_page + 1 ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
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
    const searchButton = document.getElementById('searchButton');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const productGrid = document.getElementById('productGrid');
    const noResults = document.getElementById('noResults');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const ratingCheckboxes = document.querySelectorAll('.rating-checkbox');
    let searchTimeout;

    // Function to get all categories
    const categories = <?= json_encode(array_map(function($cat) {
        return [
            'id' => $cat['id_kategori'],
            'name' => $cat['nama_kategori']
        ];
    }, $categories)) ?>;

    // Add search history functionality
    const MAX_HISTORY_ITEMS = 5;
    let searchHistory = JSON.parse(localStorage.getItem('searchHistory') || '[]');

    // Function to save search history
    function saveToHistory(term) {
        if (!term.trim()) return;
        
        // Remove if exists and add to front
        searchHistory = searchHistory.filter(item => item !== term);
        searchHistory.unshift(term);
        
        // Keep only last 5 items
        searchHistory = searchHistory.slice(0, MAX_HISTORY_ITEMS);
        localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
    }

    // Function to get search suggestions with fuzzy matching
    function getFuzzyMatches(term, items) {
        const searchTerm = term.toLowerCase();
        return items.filter(item => {
            const itemName = item.toLowerCase();
            // Exact match
            if (itemName.includes(searchTerm)) return true;
            
            // Fuzzy match (allow for typos)
            const words = itemName.split(' ');
            return words.some(word => {
                // Check if word starts with search term
                if (word.startsWith(searchTerm)) return true;
                
                // Check for similar words (Levenshtein distance <= 2)
                if (levenshteinDistance(word, searchTerm) <= 2) return true;
                
                return false;
            });
        });
    }

    // Levenshtein distance function for fuzzy matching
    function levenshteinDistance(a, b) {
        if (a.length === 0) return b.length;
        if (b.length === 0) return a.length;

        const matrix = [];
        for (let i = 0; i <= b.length; i++) {
            matrix[i] = [i];
        }
        for (let j = 0; j <= a.length; j++) {
            matrix[0][j] = j;
        }

        for (let i = 1; i <= b.length; i++) {
            for (let j = 1; j <= a.length; j++) {
                if (b.charAt(i - 1) === a.charAt(j - 1)) {
                    matrix[i][j] = matrix[i - 1][j - 1];
                } else {
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j - 1] + 1,
                        matrix[i][j - 1] + 1,
                        matrix[i - 1][j] + 1
                    );
                }
            }
        }
        return matrix[b.length][a.length];
    }

    // Function to show suggestions with enhanced features
    function showSuggestions(term) {
        if (!term.trim()) {
            // Show search history when input is empty
            if (searchHistory.length > 0) {
                searchSuggestions.innerHTML = `
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Riwayat Pencarian</div>
                    ${searchHistory.map(item => `
                        <button class="w-full px-4 py-2 text-left hover:bg-green-50 text-gray-700 
                                     transition-colors duration-150 flex items-center justify-between group"
                                onclick="setSearchTerm('${item}')">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-history text-gray-400 text-sm"></i>
                                <span>${item}</span>
                            </div>
                            <button onclick="event.stopPropagation(); removeFromHistory('${item}')" 
                                    class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-opacity">
                                <i class="fas fa-times"></i>
                            </button>
                        </button>
                    `).join('')}
                `;
                searchSuggestions.classList.remove('hidden');
            } else {
                searchSuggestions.classList.add('hidden');
            }
            return;
        }

        const searchTerm = term.toLowerCase();
        let suggestions = [];

        // Get product suggestions with fuzzy matching
        const products = Array.from(document.querySelectorAll('.product-card'))
            .map(card => ({
                name: card.getAttribute('data-name'),
                categories: card.getAttribute('data-categories').split(','),
                rating: parseFloat(card.getAttribute('data-rating')),
                price: parseFloat(card.getAttribute('data-price'))
            }))
            .filter(product => {
                const nameMatch = getFuzzyMatches(searchTerm, [product.name]).length > 0;
                const categoryMatch = product.categories.some(catId => {
                    const category = categories.find(c => c.id === catId);
                    return category && getFuzzyMatches(searchTerm, [category.name]).length > 0;
                });
                return nameMatch || categoryMatch;
            })
            .sort((a, b) => {
                // Sort by relevance (exact matches first, then fuzzy matches)
                const aExactMatch = a.name.toLowerCase().includes(searchTerm);
                const bExactMatch = b.name.toLowerCase().includes(searchTerm);
                if (aExactMatch !== bExactMatch) return bExactMatch - aExactMatch;
                
                // Then sort by rating
                return b.rating - a.rating;
            })
            .slice(0, 5);

        // Get category suggestions with fuzzy matching
        const categoryMatches = categories
            .filter(cat => getFuzzyMatches(searchTerm, [cat.name]).length > 0)
            .slice(0, 3);

        // Format suggestions
        if (products.length > 0) {
            suggestions.push('<div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Produk</div>');
            suggestions.push(...products.map(product => {
                const categoryNames = product.categories
                    .map(catId => categories.find(c => c.id === catId)?.name)
                    .filter(Boolean)
                    .join(', ');
                
                const price = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(product.price);
                
                return `
                    <button class="w-full px-4 py-2 text-left hover:bg-green-50 text-gray-700 
                                 transition-colors duration-150 flex items-start gap-3"
                            onclick="setSearchTerm('${product.name}')">
                        <div class="flex-1">
                            <div class="font-medium">${product.name}</div>
                            <div class="text-sm text-gray-500">${categoryNames}</div>
                            <div class="text-sm font-medium text-green-600 mt-1">${price}</div>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <span class="ml-1 text-gray-600">${product.rating.toFixed(1)}</span>
                            </div>
                        </div>
                    </button>
                `;
            }));
        }

        if (categoryMatches.length > 0) {
            if (suggestions.length > 0) {
                suggestions.push('<div class="border-t border-gray-100"></div>');
            }
            suggestions.push('<div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Kategori</div>');
            suggestions.push(...categoryMatches.map(category => `
                <button class="w-full px-4 py-2 text-left hover:bg-green-50 text-gray-700 
                             transition-colors duration-150 flex items-center gap-2"
                        onclick="setSearchTerm('${category.name}')">
                    <i class="fas fa-tag text-green-600 text-sm"></i>
                    <span>${category.name}</span>
                </button>
            `));
        }

        if (suggestions.length > 0) {
            searchSuggestions.innerHTML = suggestions.join('');
            searchSuggestions.classList.remove('hidden');
        } else {
            // Show "no results" message
            searchSuggestions.innerHTML = `
                <div class="px-4 py-3 text-center text-gray-500">
                    <i class="fas fa-search text-2xl mb-2"></i>
                    <p>Tidak ada hasil yang ditemukan</p>
                    <p class="text-sm mt-1">Coba kata kunci lain atau periksa ejaan</p>
                </div>
            `;
            searchSuggestions.classList.remove('hidden');
        }
    }

    // Function to remove item from search history
    window.removeFromHistory = function(term) {
        searchHistory = searchHistory.filter(item => item !== term);
        localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
        showSuggestions(searchInput.value);
    };

    // Update setSearchTerm function
    window.setSearchTerm = function(term) {
        searchInput.value = term;
        saveToHistory(term);
        filterProducts();
        searchSuggestions.classList.add('hidden');
    };

    // Handle search input with debouncing
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const term = e.target.value;
        
        showSuggestions(term);
        
        searchTimeout = setTimeout(() => {
            filterProducts();
        }, 300);
    });

    // Handle enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const term = e.target.value.trim();
            if (term) {
                saveToHistory(term);
            }
            filterProducts();
            searchSuggestions.classList.add('hidden');
        }
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.classList.add('hidden');
        }
    });

    // Handle suggestion clicks
    searchSuggestions.addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            searchSuggestions.classList.add('hidden');
        }
    });

    // Update filterProducts function
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCategories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const selectedRatings = Array.from(ratingCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => parseInt(cb.value));
        
        // Only redirect if apply button is clicked
        if (event?.target?.id === 'applyFilters') {
            const params = new URLSearchParams(window.location.search);
            params.set('page', '1');
            
            if (searchTerm) params.set('search', searchTerm);
            if (selectedCategories.length > 0) params.set('categories', selectedCategories.join(','));
            if (selectedRatings.length > 0) params.set('ratings', selectedRatings.join(','));
            
            window.location.href = window.location.pathname + '?' + params.toString();
            return;
        }
        
        // Client-side filtering
        const productCards = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const productName = card.getAttribute('data-name');
            const productRating = parseFloat(card.getAttribute('data-rating'));
            const productCategories = card.getAttribute('data-categories').split(',');
            
            // Check if product matches search term (either by name or category)
            const matchesSearch = searchTerm === '' || 
                productName.toLowerCase().includes(searchTerm) ||
                productCategories.some(catId => {
                    const category = categories.find(c => c.id === catId);
                    return category && category.name.toLowerCase().includes(searchTerm);
                });
            
            const matchesCategory = selectedCategories.length === 0 || 
                                  productCategories.some(cat => selectedCategories.includes(cat));
            const matchesRating = selectedRatings.length === 0 || 
                                selectedRatings.some(r => productRating >= r);
            
            if (matchesSearch && matchesCategory && matchesRating) {
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
    }

    // Update resetFilters function
    function resetFilters() {
        searchInput.value = '';
        categoryCheckboxes.forEach(cb => cb.checked = false);
        ratingCheckboxes.forEach(cb => cb.checked = false);
        
        if (event?.target?.id === 'resetFilters') {
            window.location.href = window.location.pathname;
        } else {
            filterProducts();
        }
    }

    // Event listeners for filters
    applyFiltersBtn.addEventListener('click', filterProducts);
    resetFiltersBtn.addEventListener('click', resetFilters);
    clearFiltersBtn.addEventListener('click', () => window.location.href = window.location.pathname);
    
    // Add event listeners to all checkboxes
    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });
    
    ratingCheckboxes.forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });
    
    // Initial filter
    filterProducts();

    // Update search tags to include categories
    const searchTagsContainer = document.getElementById('searchTags');
    if (searchTagsContainer) {
        const popularCategories = categories.slice(0, 3); // Get first 3 categories
        searchTagsContainer.innerHTML = popularCategories.map(category => `
            <button class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors"
                    onclick="setSearchTerm('${category.name}')">
                ${category.name}
            </button>
        `).join('');
    }
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

/* Add styles for pagination */
.pagination-link {
    min-width: 2.5rem;
    text-align: center;
}

.pagination-link.active {
    background-color: #059669;
    color: white;
    border-color: #059669;
}

.pagination-link:hover:not(.active) {
    background-color: #f3f4f6;
}

/* Add these styles to your existing style section */
#searchSuggestions {
    scrollbar-width: thin;
    scrollbar-color: #22c55e #f3f4f6;
}

#searchSuggestions::-webkit-scrollbar {
    width: 6px;
}

#searchSuggestions::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

#searchSuggestions::-webkit-scrollbar-thumb {
    background-color: #22c55e;
    border-radius: 3px;
}

#searchSuggestions button:not(:last-child) {
    border-bottom: 1px solid #f3f4f6;
}

#searchSuggestions button:hover {
    background-color: #f0fdf4;
}

#searchTags button {
    transition: all 0.2s ease-in-out;
}

#searchTags button:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

#searchTags button:active {
    transform: translateY(0);
}

/* Add these styles to your existing style section */
#searchSuggestions button {
    border-bottom: 1px solid #f3f4f6;
}

#searchSuggestions button:last-child {
    border-bottom: none;
}

#searchSuggestions .uppercase {
    letter-spacing: 0.05em;
}

#searchSuggestions .border-t {
    margin: 0.5rem 0;
}

/* Add animation for suggestions */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#searchSuggestions:not(.hidden) {
    animation: slideDown 0.2s ease-out;
}

/* Style for search history items */
#searchSuggestions button .fa-history {
    transition: color 0.2s ease;
}

#searchSuggestions button:hover .fa-history {
    color: #059669;
}

/* Style for no results message */
#searchSuggestions .fa-search {
    color: #9ca3af;
}

/* Add responsive styles */
@media (max-width: 640px) {
    #searchSuggestions {
        max-height: 60vh;
    }
    
    #searchSuggestions button {
        padding: 0.75rem 1rem;
    }
}
</style>

<?php $this->load->view('templates/footer') ?>