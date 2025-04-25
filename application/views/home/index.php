<?php $this->load->view('templates/header'); ?>

<!-- Login Prompt Modal - Place this right after header -->
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

<!-- Place this script after the modal but before the content -->
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
</style>

<?php $this->load->view('templates/section')?>

<!-- Untuk Anda section -->
<div class="mt-28">
    <div class="flex justify-between items-start mb-12 px-4">
        <div class="text-center flex-1">
            <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
                Untuk Anda
            </h1>
        </div>
        
        <!-- Filter and sort controls would go here -->
    </div>
    
    <!-- Product container -->
    <div id="untukAndaProductsContainer" class="h-full p-2 sm:p-3 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($produk_terbaru as $produk) : ?>
            <?php 
            if (!empty($produk['gambar'])) {
                $gambarArr = explode(',', $produk['gambar']);
                $gambar = trim($gambarArr[0]);
            } else {
                $gambar = 'default.jpg'; 
            }
            ?>
            
            <!-- Product Card -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow h-full flex flex-col transform hover:scale-105 transition-all duration-300"
                 data-price="<?= $produk['harga'] ?>"
                 data-rating="<?= floatval($produk['rating'] ?? 0) ?>"
                 data-name="<?= htmlspecialchars($produk['nama_product']) ?>">
                <div class="aspect-w-1 aspect-h-1">
                    <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                         alt="<?= $produk['nama_product']; ?>" 
                         class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-300">
                </div>
                <div class="p-3 sm:p-4 flex flex-col flex-1">
                    <div>
                        <h3 class="text-base sm:text-xl font-semibold mb-1 sm:mb-2 line-clamp-1"><?= $produk['nama_product']; ?></h3>
                        <div class="flex flex-wrap gap-1 sm:gap-2 mb-2 sm:mb-3">
                            <?php
                            $this->db->select('c.nama_kategori');
                            $this->db->from('product_category pc');
                            $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
                            $this->db->where('pc.id_product', $produk['id_product']);
                            $product_categories = $this->db->get()->result_array();
                            
                            foreach ($product_categories as $cat) : ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full"><?= $cat['nama_kategori'] ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <?php 
                                $rating = floatval($produk['rating'] ?? 0);
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
                            <span class="text-sm sm:text-lg font-bold">Rp<?= number_format($produk['harga'], 0, ',', '.'); ?></span>
                            <div class="flex gap-2">
                                <?php 
                                $is_wishlisted = false;
                                if ($this->session->userdata('logged_in') && isset($this->wishlist_model)) {
                                    $is_wishlisted = $this->wishlist_model->is_wishlisted(
                                        $this->session->userdata('id_user'), 
                                        $produk['id_product']
                                    );
                                }
                                ?>
                                <button onclick="toggleWishlist(this, <?= $produk['id_product'] ?>)" 
                                        class="wishlist-btn bg-gray-100 text-gray-600 p-2 sm:p-2.5 rounded-md hover:bg-gray-200 transition-colors <?= $is_wishlisted ? 'active' : '' ?>">
                                    <i class="fas fa-heart <?= $is_wishlisted ? 'text-red-500' : '' ?>"></i>
                                </button>
                                <button class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
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
</main>

<?php $this->load->view('templates/footer'); ?>

<script>
function toggleUntukAndaFilter() {
    const filterMenu = document.getElementById('untukAndaFilterMenu');
    filterMenu.classList.toggle('hidden');
}

// --- Price Slider and Input Synchronization ---
const minPriceInput = document.getElementById('minPrice');
const maxPriceInput = document.getElementById('maxPrice');
const minPriceSlider = document.getElementById('minPriceSlider');
const maxPriceSlider = document.getElementById('maxPriceSlider');
const minPriceLabel = document.getElementById('minPriceLabel');
const maxPriceLabel = document.getElementById('maxPriceLabel');
const priceGap = 10000; // Minimum gap between min and max

function formatCurrency(value) {
    return 'Rp' + parseInt(value).toLocaleString('id-ID');
}

function setupPriceSync() {
    // Initial label setup
    minPriceLabel.textContent = formatCurrency(minPriceSlider.value);
    maxPriceLabel.textContent = formatCurrency(maxPriceSlider.value);

    minPriceSlider.addEventListener('input', () => {
        let minVal = parseInt(minPriceSlider.value);
        let maxVal = parseInt(maxPriceSlider.value);

        if (maxVal - minVal < priceGap) {
            minVal = maxVal - priceGap;
            if (minVal < parseInt(minPriceSlider.min)) {
                minVal = parseInt(minPriceSlider.min);
            }
            minPriceSlider.value = minVal;
        }
        minPriceInput.value = minVal;
        minPriceLabel.textContent = formatCurrency(minVal);
        // applyCombinedFilters(); // Optional: Apply filters immediately on slider change
    });

    maxPriceSlider.addEventListener('input', () => {
        let minVal = parseInt(minPriceSlider.value);
        let maxVal = parseInt(maxPriceSlider.value);

        if (maxVal - minVal < priceGap) {
            maxVal = minVal + priceGap;
             if (maxVal > parseInt(maxPriceSlider.max)) {
                maxVal = parseInt(maxPriceSlider.max);
            }
            maxPriceSlider.value = maxVal;
        }
        maxPriceInput.value = maxVal;
        maxPriceLabel.textContent = formatCurrency(maxVal);
        // applyCombinedFilters(); // Optional: Apply filters immediately on slider change
    });

    minPriceInput.addEventListener('input', () => {
        let minVal = parseInt(minPriceInput.value) || 0;
        let maxVal = parseInt(maxPriceInput.value);

        if (minVal < parseInt(minPriceSlider.min)) minVal = parseInt(minPriceSlider.min);
        if (maxVal - minVal < priceGap) {
            minVal = maxVal - priceGap;
            if (minVal < parseInt(minPriceSlider.min)) minVal = parseInt(minPriceSlider.min);
        }
        if (minVal > parseInt(minPriceSlider.max)) minVal = parseInt(minPriceSlider.max) - priceGap;

        minPriceSlider.value = minVal;
        minPriceInput.value = minVal; // Correct the input if needed
        minPriceLabel.textContent = formatCurrency(minVal);
        // applyCombinedFilters(); // Optional: Apply filters immediately on input change
    });

    maxPriceInput.addEventListener('input', () => {
        let minVal = parseInt(minPriceInput.value);
        let maxVal = parseInt(maxPriceInput.value) || 1000000;

        if (maxVal > parseInt(maxPriceSlider.max)) maxVal = parseInt(maxPriceSlider.max);
        if (maxVal - minVal < priceGap) {
            maxVal = minVal + priceGap;
             if (maxVal > parseInt(maxPriceSlider.max)) maxVal = parseInt(maxPriceSlider.max);
        }
         if (maxVal < parseInt(maxPriceSlider.min)) maxVal = parseInt(maxPriceSlider.min) + priceGap;

        maxPriceSlider.value = maxVal;
        maxPriceInput.value = maxVal; // Correct the input if needed
        maxPriceLabel.textContent = formatCurrency(maxVal);
        // applyCombinedFilters(); // Optional: Apply filters immediately on input change
    });
}

// --- Combined Filter and Search Function ---
function applyCombinedFilters() {
    const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
    const minPrice = parseInt(minPriceInput.value) || 0;
    const maxPrice = parseInt(maxPriceInput.value) || 1000000;
    const minRating = parseFloat(document.getElementById('untukAndaRatingFilter').value) || 0;
    const sortBy = document.getElementById('untukAndaSortBy').value;
    const container = document.getElementById('untukAndaProductsContainer');
    const cards = Array.from(container.querySelectorAll('.product-card')); // Select by class

    let visibleCards = cards.filter(card => {
        const price = parseInt(card.dataset.price);
        const rating = parseFloat(card.dataset.rating);
        const name = card.dataset.name.toLowerCase();
        const categories = Array.from(card.querySelectorAll('.text-green-800')).map(cat => cat.textContent.toLowerCase());

        // Check filters
        const priceMatch = price >= minPrice && price <= maxPrice;
        const ratingMatch = rating >= minRating;
        const searchMatch = searchTerm === '' || name.includes(searchTerm) || categories.some(cat => cat.includes(searchTerm));

        const show = priceMatch && ratingMatch && searchMatch;
        card.style.display = show ? '' : 'none'; // Show/hide immediately
        return show; // Return if the card should be considered for sorting
    });

    // Sorting
    if (sortBy) {
        visibleCards.sort((a, b) => {
            const priceA = parseInt(a.dataset.price);
            const priceB = parseInt(b.dataset.price);
            const ratingA = parseFloat(a.dataset.rating);
            const ratingB = parseFloat(b.dataset.rating);
            const nameA = a.dataset.name.toLowerCase();
            const nameB = b.dataset.name.toLowerCase();

            switch (sortBy) {
                case 'price-asc': return priceA - priceB;
                case 'price-desc': return priceB - priceA;
                case 'rating-desc': return ratingB - ratingA;
                case 'name-asc': return nameA.localeCompare(nameB);
                default: return 0;
            }
        });

        // Reorder DOM elements
        visibleCards.forEach(card => container.appendChild(card));
    }

    // Hide filter menu after applying (optional, keep if you want immediate feedback)
    // document.getElementById('untukAndaFilterMenu').classList.add('hidden');
}

// --- Functions to trigger combined filter ---
function applyUntukAndaFilters() {
    applyCombinedFilters();
    document.getElementById('untukAndaFilterMenu').classList.add('hidden'); // Close menu on Apply
}

function resetUntukAndaFilters() {
    // Reset inputs and sliders
    minPriceInput.value = 0;
    maxPriceInput.value = 1000000;
    minPriceSlider.value = 0;
    maxPriceSlider.value = 1000000;
    minPriceLabel.textContent = formatCurrency(0);
    maxPriceLabel.textContent = formatCurrency(1000000);
    document.getElementById('untukAndaRatingFilter').value = '';
    document.getElementById('untukAndaSortBy').value = '';
    document.getElementById('searchProduct').value = ''; // Reset search

    // Re-apply filters to show all cards
    applyCombinedFilters();
    document.getElementById('untukAndaFilterMenu').classList.add('hidden'); // Close menu on Reset
}

// --- Event Listeners ---
document.addEventListener('DOMContentLoaded', () => {
    setupPriceSync();
    // Initial filter application if needed (e.g., if filters can be pre-set)
    // applyCombinedFilters();
});

// Close filter dropdown when clicking outside
document.addEventListener('click', function(event) {
    const filterMenu = document.getElementById('untukAndaFilterMenu');
    const filterButton = document.querySelector('button[onclick="toggleUntukAndaFilter()"]');

    if (filterMenu && !filterMenu.classList.contains('hidden') && !filterMenu.contains(event.target) && event.target !== filterButton && !filterButton.contains(event.target)) {
        filterMenu.classList.add('hidden');
    }
});

</script>
