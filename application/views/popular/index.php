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
    <div class="flex items-center justify-between">
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
                        <?php $this->load->view('popular/filter_content'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Filters -->
        <div class="w-full md:w-64 hidden md:flex flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-24 max-h-[calc(100vh-120px)] overflow-y-auto">
                <h3 class="font-semibold text-lg text-green-800 mb-4 border-b pb-2 sticky top-0 bg-white z-10">Filter Produk</h3>
                <?php $this->load->view('popular/filter_content'); ?>
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
                    $this->load->view('popular/product_card', ['produk' => $produk]);
                endforeach; 
                ?>
            </div>
            
            <?php $this->load->view('popular/pagination', [
                'current_page' => $current_page,
                'total_pages' => $total_pages
            ]); ?>
            
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
// Common utility functions
const utils = {
    formatCurrency: (value) => 'Rp' + parseInt(value).toLocaleString('id-ID'),
    
    getFilterValues: () => ({
        searchTerm: document.getElementById('searchProduct').value.toLowerCase(),
        minPrice: parseInt(document.getElementById('minPriceSlider').value),
        maxPrice: parseInt(document.getElementById('maxPriceSlider').value),
        selectedCategories: Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.value),
        selectedRatings: Array.from(document.querySelectorAll('.rating-checkbox:checked')).map(cb => parseInt(cb.value)),
        sortBy: document.getElementById('sortBy').value
    }),
    
    buildFilterParams: (filters) => {
        const params = new URLSearchParams(window.location.search);
        params.set('page', '1');
        
        if (filters.searchTerm) params.set('search', filters.searchTerm);
        if (filters.minPrice > 0) params.set('min_price', filters.minPrice);
        if (filters.maxPrice < 1000000) params.set('max_price', filters.maxPrice);
        if (filters.selectedCategories.length) params.set('categories', filters.selectedCategories.join(','));
        if (filters.selectedRatings.length) params.set('ratings', filters.selectedRatings.join(','));
        if (filters.sortBy !== 'popular') params.set('sort', filters.sortBy);
        
        return params;
    },
    
    resetFilterInputs: () => {
        const elements = {
            searchInput: document.getElementById('searchProduct'),
            minPriceSlider: document.getElementById('minPriceSlider'),
            maxPriceSlider: document.getElementById('maxPriceSlider'),
            minPriceInput: document.getElementById('minPrice'),
            maxPriceInput: document.getElementById('maxPrice'),
            minPriceLabel: document.getElementById('minPriceLabel'),
            maxPriceLabel: document.getElementById('maxPriceLabel'),
            categoryCheckboxes: document.querySelectorAll('.category-checkbox'),
            ratingCheckboxes: document.querySelectorAll('.rating-checkbox'),
            sortBySelect: document.getElementById('sortBy')
        };
        
        elements.searchInput.value = '';
        elements.minPriceSlider.value = elements.minPriceInput.value = '0';
        elements.maxPriceSlider.value = elements.maxPriceInput.value = '1000000';
        elements.minPriceLabel.textContent = utils.formatCurrency(0);
        elements.maxPriceLabel.textContent = utils.formatCurrency(1000000);
        elements.categoryCheckboxes.forEach(cb => cb.checked = false);
        elements.ratingCheckboxes.forEach(cb => cb.checked = false);
        elements.sortBySelect.value = 'popular';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize price sliders
    const priceSliders = {
        min: document.getElementById('minPriceSlider'),
        max: document.getElementById('maxPriceSlider'),
        minInput: document.getElementById('minPrice'),
        maxInput: document.getElementById('maxPrice'),
        minLabel: document.getElementById('minPriceLabel'),
        maxLabel: document.getElementById('maxPriceLabel')
    };
    
    const priceGap = 10000;
    
    // Price slider event handlers
    const handlePriceSlider = (slider, isMin) => {
        const value = parseInt(slider.value);
        const otherSlider = isMin ? priceSliders.max : priceSliders.min;
        const otherValue = parseInt(otherSlider.value);
        
        if (isMin && otherValue - value < priceGap) {
            slider.value = otherValue - priceGap;
        } else if (!isMin && value - otherValue < priceGap) {
            slider.value = otherValue + priceGap;
        }
        
        const input = isMin ? priceSliders.minInput : priceSliders.maxInput;
        const label = isMin ? priceSliders.minLabel : priceSliders.maxLabel;
        input.value = slider.value;
        label.textContent = utils.formatCurrency(slider.value);
    };
    
    priceSliders.min.addEventListener('input', () => handlePriceSlider(priceSliders.min, true));
    priceSliders.max.addEventListener('input', () => handlePriceSlider(priceSliders.max, false));
    
    // Filter and search functionality
    const filterProducts = (event) => {
        const filters = utils.getFilterValues();
        
        if (event?.target?.id === 'applyFilters') {
            const params = utils.buildFilterParams(filters);
            window.location.href = window.location.pathname + '?' + params.toString();
            return;
        }
        
        const productCards = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const matches = {
                search: card.getAttribute('data-name').includes(filters.searchTerm),
                price: (() => {
                    const price = parseInt(card.getAttribute('data-price'));
                    return price >= filters.minPrice && price <= filters.maxPrice;
                })(),
                category: filters.selectedCategories.length === 0 || 
                         card.getAttribute('data-categories').split(',').some(cat => 
                            filters.selectedCategories.includes(cat)),
                rating: filters.selectedRatings.length === 0 || 
                       filters.selectedRatings.some(r => 
                            parseFloat(card.getAttribute('data-rating')) >= r)
            };
            
            const isVisible = Object.values(matches).every(Boolean);
            card.classList.toggle('hidden', !isVisible);
            if (isVisible) visibleCount++;
        });
        
        document.getElementById('productGrid').classList.toggle('hidden', visibleCount === 0);
        document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
        
        sortProducts(filters.sortBy);
    };
    
    // Event listeners
    document.getElementById('searchProduct').addEventListener('input', filterProducts);
    document.getElementById('applyFilters').addEventListener('click', filterProducts);
    document.getElementById('resetFilters').addEventListener('click', (event) => {
        utils.resetFilterInputs();
        if (event.target.id === 'resetFilters') {
            window.location.href = window.location.pathname;
        } else {
            filterProducts();
        }
    });
    document.getElementById('clearFilters').addEventListener('click', () => {
        window.location.href = window.location.pathname;
    });
    document.getElementById('sortBy').addEventListener('change', (e) => sortProducts(e.target.value));
    
    document.querySelectorAll('.category-checkbox, .rating-checkbox').forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });
    
    // Mobile filter functionality
    const mobileFilter = {
        btn: document.getElementById('mobileFilterBtn'),
        sidebar: document.getElementById('mobileFilterSidebar'),
        closeBtn: document.getElementById('closeMobileFilter')
    };
    
    mobileFilter.btn.addEventListener('click', () => mobileFilter.sidebar.classList.remove('hidden'));
    mobileFilter.closeBtn.addEventListener('click', () => mobileFilter.sidebar.classList.add('hidden'));
    mobileFilter.sidebar.addEventListener('click', (e) => {
        if (e.target === mobileFilter.sidebar) mobileFilter.sidebar.classList.add('hidden');
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
</style>

<?php $this->load->view('templates/footer') ?>