<?php $this->load->view('templates/header'); ?>

<div class="container mx-auto px-4 py-8 mt-12">
    <!-- Breadcrumb and Back Link -->
    <div class="mb-4">
        <a href="<?= base_url('category/plants') ?>" class="text-green-600 hover:text-green-800 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Kategori Tanaman
        </a>
    </div>

    <!-- Category Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-green-800 mb-2"><?= $category->nama_kategori ?></h1>
        <p class="text-gray-600">
            Temukan berbagai produk <?= $category->nama_kategori ?> berkualitas untuk koleksi Anda
        </p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-8">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari produk..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex gap-4">
                <select id="sortSelect" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="newest">Terbaru</option>
                    <option value="price_asc">Harga: Rendah ke Tinggi</option>
                    <option value="price_desc">Harga: Tinggi ke Rendah</option>
                    <option value="name_asc">Nama: A-Z</option>
                    <option value="name_desc">Nama: Z-A</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6" id="productsGrid">
        <?php foreach ($products as $product): ?>
            <?php 
            if (!empty($product['gambar'])) {
                $gambarArr = explode(',', $product['gambar']);
                $gambar = trim($gambarArr[0]);
            } else {
                $gambar = 'default.jpg';
            }
            
            // Get categories for this product
            $this->db->select('c.nama_kategori, c.id_kategori');
            $this->db->from('product_category pc');
            $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
            $this->db->where('pc.id_product', $product['id_product']);
            $product_categories = $this->db->get()->result_array();
            
            // Create a string of category IDs for data attribute
            $category_ids = [];
            foreach ($product_categories as $cat) {
                $category_ids[] = $cat['id_kategori'];
            }
            $category_ids_str = implode(',', $category_ids);
            
            // Check if product is in wishlist
            $is_wishlisted = false;
            if ($this->session->userdata('logged_in')) {
                $is_wishlisted = $this->wishlist_model->is_wishlisted($this->session->userdata('id_user'), $product['id_product']);
            }
            ?>
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-lg h-full flex flex-col transform hover:scale-105 transition-all duration-300"
                 data-id="<?= $product['id_product'] ?>"
                 data-name="<?= strtolower($product['nama_product']) ?>"
                 data-price="<?= $product['harga'] ?>"
                 data-rating="<?= floatval($product['rating'] ?? 0) ?>"
                 data-categories="<?= $category_ids_str ?>">
                <a href="<?= base_url('product/detail/' . $product['id_product']) ?>" class="block flex-grow">
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="https://admin.hijauloka.my.id/uploads/<?= $gambar ?>" 
                             alt="<?= $product['nama_product'] ?>" 
                             class="w-full h-36 sm:h-48 object-cover transform hover:scale-110 transition-all duration-300">
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-xl font-semibold mb-1 sm:mb-2 line-clamp-1"><?= $product['nama_product'] ?></h3>
                        <div class="flex flex-wrap gap-1 sm:gap-2 mb-2 sm:mb-3">
                            <?php foreach ($product_categories as $cat): ?>
                                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-800 text-[10px] sm:text-xs rounded-full"><?= $cat['nama_kategori'] ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </a>

                <div class="p-3 sm:p-4">
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <?php 
                            $rating = floatval($product['rating'] ?? 0);
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
                        <span class="text-sm sm:text-lg font-bold">Rp<?= number_format($product['harga'], 0, ',', '.') ?></span>
                        <div class="flex gap-2">
                            <button onclick="toggleWishlist(this, <?= $product['id_product'] ?>)"
                                    class="wishlist-btn bg-gray-100 text-gray-600 p-2 sm:p-2.5 rounded-md hover:bg-gray-200 transition-colors <?= $is_wishlisted ? 'active' : '' ?>">
                                <i class="fas fa-heart <?= $is_wishlisted ? 'text-red-500' : '' ?>"></i>
                            </button>
                            <button onclick="addToCart(<?= $product['id_product'] ?>, this)"
                                    class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <?php if (empty($products)): ?>
        <div class="text-center py-12">
            <i class="fas fa-leaf text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada produk</h3>
            <p class="text-gray-500">Produk akan segera hadir untuk kategori ini</p>
        </div>
    <?php endif; ?>
</div>

<!-- Add these styles before the existing script section -->
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

<!-- Add this cart notification modal before the script section -->
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

<!-- Replace the existing script with this updated version -->
<script>
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

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const products = document.querySelectorAll('#productsGrid > div');
    
    products.forEach(product => {
        const title = product.querySelector('h3').textContent.toLowerCase();
        if (title.includes(searchTerm)) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
});

// Sort functionality
document.getElementById('sortSelect').addEventListener('change', function(e) {
    const sortBy = e.target.value;
    const productsGrid = document.getElementById('productsGrid');
    const products = Array.from(productsGrid.children);
    
    products.sort((a, b) => {
        const titleA = a.querySelector('h3').textContent;
        const titleB = b.querySelector('h3').textContent;
        const priceA = parseInt(a.getAttribute('data-price'));
        const priceB = parseInt(b.getAttribute('data-price'));
        
        switch(sortBy) {
            case 'price_asc':
                return priceA - priceB;
            case 'price_desc':
                return priceB - priceA;
            case 'name_asc':
                return titleA.localeCompare(titleB);
            case 'name_desc':
                return titleB.localeCompare(titleA);
            default:
                return 0;
        }
    });
    
    products.forEach(product => productsGrid.appendChild(product));
});

// Wishlist functionality
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

// Add to cart functionality
function addToCart(productId, button) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    // Show loading state
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

function closeCartNotification() {
    document.getElementById('cartNotification').classList.add('hidden');
}

// Close notification when clicking outside
document.getElementById('cartNotification').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCartNotification();
    }
});

function closeLoginPrompt() {
    const modal = document.getElementById('loginPrompt');
    modal.classList.add('hidden');
}

// Use event delegation to avoid multiple event listeners
document.addEventListener('DOMContentLoaded', function() {
    const loginPrompt = document.getElementById('loginPrompt');
    if (loginPrompt) {
        loginPrompt.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginPrompt();
            }
        });
    }
});
</script>