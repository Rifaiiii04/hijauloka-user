<?php $this->load->view('templates/header'); ?>

<div class="container mx-auto px-4 py-8 mt-22">
    <!-- Category Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-green-800 mb-2">Tanaman</h1>
        <p class="text-gray-600">
            Temukan berbagai jenis tanaman berkualitas untuk koleksi Anda
        </p>
    </div>

    <!-- Filter Section -->
    <div class="mb-8 bg-white p-4 rounded-lg shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center flex-grow">
                <span class="text-gray-700 font-medium mr-3">Cari:</span>
                <input type="text" id="quickSearch" placeholder="Cari tanaman..." class="bg-gray-50 border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
            </div>
          
            <div class="flex items-center">
                <span class="text-gray-700 font-medium mr-3">Urutkan:</span>
                <select id="sortOrder" class="bg-gray-50 border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="newest">Terbaru</option>
                    <option value="price_low">Harga Terendah</option>
                    <option value="price_high">Harga Tertinggi</option>
                    <option value="rating">Rating Tertinggi</option>
                </select>
            </div>
            <button id="applyFilter" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                Terapkan Filter
            </button>
        </div>
    </div>

    <!-- Products Grid -->
    <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        // Use the products data passed from the controller
        if (!empty($products)) {
            foreach ($products as $product) :
                // Get image
                if (!empty($product['gambar'])) {
                    $gambarArr = explode(',', $product['gambar']);
                    $gambar = trim($gambarArr[0]);
                } else {
                    $gambar = 'default.jpg';
                }
                
                // Get category name from the product data
                $kategori = isset($product['nama_kategori']) ? $product['nama_kategori'] : 'Tanaman';
        ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 product-card" data-name="<?= strtolower($product['nama_product']) ?>" data-category="<?= strtolower($kategori) ?>">
            <a href="<?= base_url('product/detail/' . $product['id_product']) ?>" class="block">
                <div class="relative h-48">
                    <img src="http://localhost/hijauloka/uploads/<?= $gambar ?>" 
                         alt="<?= $product['nama_product'] ?>" 
                         class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= $product['nama_product'] ?></h3>
                    
                    <!-- Tags -->
                    <div class="flex flex-wrap gap-1 mb-2">
                        <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full"><?= $kategori ?></span>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex items-center mb-3">
                        <?php 
                        $rating = isset($product['rating']) ? floatval($product['rating']) : 0;
                        for ($i = 1; $i <= 5; $i++): 
                        ?>
                            <?php if ($i <= $rating): ?>
                                <i class="fas fa-star text-yellow-400"></i>
                            <?php elseif ($i - 0.5 <= $rating): ?>
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            <?php else: ?>
                                <i class="far fa-star text-yellow-400"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="text-xs text-gray-500 ml-1">(<?= number_format($rating, 1) ?>)</span>
                    </div>
                    
                    <!-- Price and Actions -->
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Rp<?= number_format($product['harga'], 0, ',', '.') ?></span>
                        <div class="flex gap-2">
                            <button onclick="event.preventDefault(); toggleWishlist(this, <?= $product['id_product'] ?>)" 
                                    class="bg-gray-100 text-gray-600 p-2 rounded-md hover:bg-gray-200 transition-colors">
                                <i class="<?= $is_wishlisted ? 'fas text-red-500' : 'far' ?> fa-heart"></i>
                            </button>
                            <button onclick="event.preventDefault(); addToCart(<?= $product['id_product'] ?>)" 
                                    class="bg-green-600 text-white p-2 rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php 
            endforeach;
        } else {
            echo '<div class="col-span-full text-center py-8">
                    <p class="text-gray-500 text-lg">Tidak ada produk yang tersedia saat ini.</p>
                  </div>';
        }
        ?>
    </div>

    <!-- Pagination -->
    <?php if(isset($pagination) && !empty($pagination)): ?>
    <div class="mt-8">
        <?= $pagination ?>
    </div>
    <?php endif; ?>
</div>

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

<script>
function toggleWishlist(button, productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    const icon = button.querySelector('i');
    
    // Toggle heart icon
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas', 'text-red-500');
    } else {
        icon.classList.remove('fas', 'text-red-500');
        icon.classList.add('far');
    }
    
    // Send AJAX request to server
    fetch('<?= base_url('wishlist/toggle') ?>/' + productId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Wishlist updated:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function addToCart(productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>
    
    // Send AJAX request to server
    fetch('<?= base_url('cart/add') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'id_product=' + productId + '&jumlah=1'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cartNotification').classList.remove('hidden');
            setTimeout(() => {
                closeCartNotification();
            }, 2000);
        } else {
            alert(data.message || 'Gagal menambahkan produk ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan produk ke keranjang');
    });
}

// Filter functionality
document.getElementById('applyFilter').addEventListener('click', function() {
    const sortBy = document.getElementById('sortOrder').value;
    
    // Send AJAX request to filter products
    fetch('<?= base_url('category/filter') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'sort_by=' + sortBy
    })
    .then(response => response.json())
    .then(data => {
        // Update products grid with filtered results
        updateProductsGrid(data.products);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Quick search functionality
document.getElementById('quickSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        const productName = card.getAttribute('data-name');
        const productCategory = card.getAttribute('data-category');
        
        if (productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show message if no products match
    const visibleProducts = document.querySelectorAll('.product-card[style=""]').length;
    const noResultsMessage = document.querySelector('.no-results-message');
    
    if (visibleProducts === 0 && searchTerm !== '') {
        if (!noResultsMessage) {
            const message = document.createElement('div');
            message.className = 'col-span-full text-center py-8 no-results-message';
            message.innerHTML = '<p class="text-gray-500 text-lg">Tidak ada produk yang sesuai dengan pencarian Anda.</p>';
            document.getElementById('productsGrid').appendChild(message);
        }
    } else if (noResultsMessage) {
        noResultsMessage.remove();
    }
});

function updateProductsGrid(products) {
    const grid = document.getElementById('productsGrid');
    grid.innerHTML = '';
    
    if (products.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-500 text-lg">Tidak ada produk yang tersedia saat ini.</p></div>';
        return;
    }
    
    products.forEach(product => {
        // Get image
        let gambar = 'default.jpg';
        if (product.gambar) {
            const gambarArr = product.gambar.split(',');
            gambar = gambarArr[0].trim();
        }
        
        // Get category
        const kategori = product.nama_kategori || 'Tanaman';
        
        // Create product card
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 product-card';
        card.setAttribute('data-name', product.nama_product.toLowerCase());
        card.setAttribute('data-category', kategori.toLowerCase());
        
        card.innerHTML = `
            <a href="<?= base_url('product/detail/') ?>${product.id_product}" class="block">
                <div class="relative h-48">
                    <img src="http://localhost/hijauloka/uploads/${gambar}" 
                         alt="${product.nama_product}" 
                         class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">${product.nama_product}</h3>
                    
                    <!-- Rating -->
                    <div class="flex items-center mb-3">
                        ${getRatingStars(product.rating || 0)}
                        <span class="text-xs text-gray-500 ml-1">(${parseFloat(product.rating || 0).toFixed(1)})</span>
                    </div>
                    
                    <!-- Price and Actions -->
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Rp${numberFormat(product.harga)}</span>
                        <div class="flex gap-2">
                            <button onclick="event.preventDefault(); toggleWishlist(this, ${product.id_product})" 
                                    class="bg-gray-100 text-gray-600 p-2 rounded-md hover:bg-gray-200 transition-colors">
                                <i class="far fa-heart"></i>
                            </button>
                            <button onclick="event.preventDefault(); addToCart(${product.id_product})" 
                                    class="bg-green-600 text-white p-2 rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </a>
        `;
        
        grid.appendChild(card);
    });
}

function getRatingStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star text-yellow-400"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
        } else {
            stars += '<i class="far fa-star text-yellow-400"></i>';
        }
    }
    return stars;
}

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function closeLoginPrompt() {
    document.getElementById('loginPrompt').classList.add('hidden');
}

function closeCartNotification() {
    document.getElementById('cartNotification').classList.add('hidden');
}

document.getElementById('loginPrompt').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginPrompt();
    }
});

document.getElementById('cartNotification').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCartNotification();
    }
});
</script>

<style>
@keyframes bounce-once {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
.animate-bounce-once {
    animation: bounce-once 0.5s ease-in-out;
}
</style>