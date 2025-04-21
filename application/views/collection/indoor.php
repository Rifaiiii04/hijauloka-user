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

<script>
function toggleWishlist(button, productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    const icon = button.querySelector('i');
    
    fetch('<?= base_url('wishlist/toggle') ?>/' + productId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.action === 'added') {
            icon.classList.add('text-red-500');
            button.classList.add('active');
        } else if (data.action === 'removed') {
            icon.classList.remove('text-red-500');
            button.classList.remove('active');
        }
    })
    .catch(error => console.error('Error:', error));
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

<div class="w-32 h-12 flex text-center justify-center items-center mb-4 mt-2">
        <a href="<?= base_url('plants/index') ?>" class="text-green-800 text-xl font-bold underline ">Kembali</a>
    </div>
<div class="container mx-auto px-4 py-0 sm:py-8">
<div class="mb-5 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
       Koleksi Indoor
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
    </h1><br>
    <div class="flex items-center mt-5 justify-center gap-4">
        <input type="search" name="" id="searchInput" class="w-96 h-10 px-4 border border-gray-600 bg-white rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-800" placeholder="Search Plants....">
        
        <!-- Filter Button -->
        <div class="relative">
            <button onclick="toggleFilter()" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            
            <!-- Filter Dropdown -->
            <div id="filterMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-50 p-4">
                <!-- Price Range -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Price Range</h3>
                    <select id="priceRange" class="w-full p-2 border rounded-lg">
                        <option value="">All Prices</option>
                        <option value="0-50000">Under Rp50.000</option>
                        <option value="50000-100000">Rp50.000 - Rp100.000</option>
                        <option value="100000-200000">Rp100.000 - Rp200.000</option>
                        <option value="200000+">Above Rp200.000</option>
                    </select>
                </div>
                
                <!-- Rating Filter -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Minimum Rating</h3>
                    <select id="ratingFilter" class="w-full p-2 border rounded-lg">
                        <option value="">All Ratings</option>
                        <option value="4">4+ Stars</option>
                        <option value="3">3+ Stars</option>
                        <option value="2">2+ Stars</option>
                    </select>
                </div>
                
                <!-- Sort By -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Sort By</h3>
                    <select id="sortBy" class="w-full p-2 border rounded-lg">
                        <option value="">Default</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="rating-desc">Highest Rating</option>
                        <option value="name-asc">Name: A to Z</option>
                    </select>
                </div>
                
                <!-- Apply/Reset Buttons -->
                <div class="flex gap-2">
                    <button onclick="applyFilters()" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-all">
                        Apply
                    </button>
                    <button onclick="resetFilters()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition-all">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleFilter() {
        const filterMenu = document.getElementById('filterMenu');
        filterMenu.classList.toggle('hidden');
    }

    function applyFilters() {
        const priceRange = document.getElementById('priceRange').value;
        const rating = document.getElementById('ratingFilter').value;
        const sortBy = document.getElementById('sortBy').value;
        const productCards = document.querySelectorAll('.grid > div');

        productCards.forEach(card => {
            let show = true;
            
            // Price Filter
            if (priceRange) {
                const price = parseInt(card.querySelector('.font-bold').textContent.replace(/[^0-9]/g, ''));
                const [min, max] = priceRange.split('-').map(Number);
                
                if (max) {
                    show = price >= min && price <= max;
                } else if (priceRange === '200000+') {
                    show = price >= 200000;
                }
            }

            // Rating Filter
            if (show && rating) {
                const productRating = parseFloat(card.querySelector('.text-gray-500').textContent.replace(/[()]/g, ''));
                show = productRating >= parseFloat(rating);
            }

            card.style.display = show ? '' : 'none';
        });

        // Sorting
        if (sortBy) {
            const container = document.querySelector('.grid');
            const cards = Array.from(container.children);
            
            cards.sort((a, b) => {
                if (sortBy === 'price-asc' || sortBy === 'price-desc') {
                    const priceA = parseInt(a.querySelector('.font-bold').textContent.replace(/[^0-9]/g, ''));
                    const priceB = parseInt(b.querySelector('.font-bold').textContent.replace(/[^0-9]/g, ''));
                    return sortBy === 'price-asc' ? priceA - priceB : priceB - priceA;
                } else if (sortBy === 'rating-desc') {
                    const ratingA = parseFloat(a.querySelector('.text-gray-500').textContent.replace(/[()]/g, ''));
                    const ratingB = parseFloat(b.querySelector('.text-gray-500').textContent.replace(/[()]/g, ''));
                    return ratingB - ratingA;
                } else if (sortBy === 'name-asc') {
                    const nameA = a.querySelector('h3').textContent.toLowerCase();
                    const nameB = b.querySelector('h3').textContent.toLowerCase();
                    return nameA.localeCompare(nameB);
                }
            });

            cards.forEach(card => container.appendChild(card));
        }

        toggleFilter();
    }

    function resetFilters() {
        document.getElementById('priceRange').value = '';
        document.getElementById('ratingFilter').value = '';
        document.getElementById('sortBy').value = '';
        
        const productCards = document.querySelectorAll('.grid > div');
        productCards.forEach(card => {
            card.style.display = '';
        });
        
        toggleFilter();
    }

    // Close filter menu when clicking outside
    document.addEventListener('click', function(e) {
        const filterMenu = document.getElementById('filterMenu');
        const filterButton = document.querySelector('button[onclick="toggleFilter()"]');
        
        if (!filterMenu.contains(e.target) && !filterButton.contains(e.target)) {
            filterMenu.classList.add('hidden');
        }
    });

    // Add search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const productCards = document.querySelectorAll('.grid > div');
        let hasResults = false;

        productCards.forEach(card => {
            if (!card.querySelector('h3')) return; // Skip if not a product card
            
            const productName = card.querySelector('h3').textContent.toLowerCase();
            const categories = Array.from(card.querySelectorAll('.bg-green-100'))
                .map(cat => cat.textContent.toLowerCase());
            
            const matchName = productName.includes(searchTerm);
            const matchCategory = categories.some(cat => cat.includes(searchTerm));

            if (matchName || matchCategory) {
                card.style.display = '';
                hasResults = true;
            } else {
                card.style.display = 'none';
            }
        });

        // Handle no results message
        let noResultsMsg = document.querySelector('.no-results-message');
        if (!hasResults) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('p');
                noResultsMsg.className = 'no-results-message col-span-full text-center text-gray-500 py-8';
                noResultsMsg.textContent = 'No plants found matching your search.';
                document.querySelector('.grid').appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    });
</script>
</div>
    <div class="h-full p-2 sm:p-3 grid grid-cols-2 md:grid-cols- mt-5 lg:grid-cols-4 gap-6">
        <?php if (!empty($plants)) : ?>
            <?php foreach ($plants as $produk) : ?>
                <?php 
                if (!empty($produk['gambar'])) {
                    $gambarArr = explode(',', $produk['gambar']);
                    $gambar = trim($gambarArr[0]);
                } else {
                    $gambar = 'default.jpg';
                }
                ?>
                <div class="bg-white rounded-lg overflow-hidden shadow h-full flex flex-col transform hover:scale-105 transition-all duration-300">
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                             alt="<?= $produk['nama_product']; ?>" 
                             class="w-full h-36 sm:h-48 object-cover transform hover:scale-110 transition-all duration-300">
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
                                    <!-- Update the wishlist button -->
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
        <?php else: ?>
            <p class="col-span-full text-center text-gray-500">No indoor plants available.</p>
        <?php endif; ?>
    </div>
</div>