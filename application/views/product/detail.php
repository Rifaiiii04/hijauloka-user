<?php $this->load->view('templates/header'); ?>

<main class="container mx-auto px-1 py-1 mt-4 md:mt-20 max-w-5xl">
    <!-- Breadcrumb -->
    <div class="mb-1">
        <nav class="text-[10px] text-gray-600">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="<?= base_url() ?>" class="hover:text-green-600">Beranda</a>
                    <svg class="w-3 h-3 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-green-600"><?= $product['nama_product'] ?></li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded shadow py-2 overflow-hidden">
        <div class="md:flex md:space-x-4">
            <!-- Product Images -->
            <div class="md:w-2/5 p-1">
                <div class="relative h-[250px]">
                    <?php 
                    $mainImage = $product['gambar'];
                    if (strpos($mainImage, ',') !== false) {
                        $images = array_map('trim', explode(',', $mainImage));
                        $mainImage = $images[0];
                    }
                    ?>
                    <img src="http://localhost/hijauloka/uploads/<?= $mainImage ?>" 
                         alt="<?= $product['nama_product'] ?>" 
                         class="w-full h-full object-contain rounded-lg">
                </div>
                
                <?php if (isset($images) && count($images) > 1): ?>
                <div class="grid grid-cols-4 gap-0.5 mt-0.5">
                    <?php foreach($images as $image): ?>
                    <div class="relative h-14 left-1.5 gap-5">
                        <img src="http://localhost/hijauloka/uploads/<?= $image ?>" 
                             alt="Product thumbnail" 
                             class="w-full h-full object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="md:w-3/5 p-2 space-y-1.5">
                <div class="flex flex-wrap gap-0.5">
                    <?php foreach($categories as $category): ?>
                        <span class="px-1.5 py-0.5 bg-green-100 text-green-800 text-[10px] rounded-full">
                            <?= $category['nama_kategori'] ?>
                        </span>
                    <?php endforeach; ?>
                </div>

                <h1 class="text-lg font-bold text-gray-900"><?= $product['nama_product'] ?></h1>
                
                <div class="flex items-center">
                    <div class="flex text-yellow-400 text-xs">
                        <?php 
                        $rating = floatval($product['rating'] ?? 0);
                        for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $rating): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif ($i - 0.5 <= $rating): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="text-gray-500 text-[10px] ml-1">(<?= number_format($rating, 1) ?>)</span>
                </div>

                <p class="text-lg font-bold text-green-600">
                    Rp<?= number_format($product['harga'], 0, ',', '.') ?>
                </p>

                <div>
                    <h2 class="text-sm font-semibold">Deskripsi Tanaman</h2>
                    <p class="text-[11px] text-gray-600"><?= nl2br($product['desk_product']) ?></p>
                </div>

                <!-- Plant Care Instructions -->
                <div>
                    <h2 class="text-sm font-semibold">Cara Merawat Tanaman</h2>
                    <div class="bg-gray-50 rounded p-2 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-seedling text-lg"></i>
                        </div>
                        <p class="text-[11px] text-gray-600 font-medium">Coming Soon!</p>
                        <p class="text-[10px] text-gray-500">Panduan perawatan tanaman dengan ilustrasi akan tersedia segera.</p>
                    </div>
                </div>

                <!-- Quantity and Actions -->
                <div class="flex items-center gap-1.5 pt-1">
                    <div class="flex items-center border rounded">
                        <button class="px-2 py-0.5 text-gray-600 text-xs" onclick="updateQuantity(-1)">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?= $product['stok'] ?>"
                               class="w-8 text-center border-x py-0.5 text-xs">
                        <button class="px-2 py-0.5 text-gray-600 text-xs" onclick="updateQuantity(1)">+</button>
                    </div>
                    <span class="text-[10px] text-gray-500">Stok: <?= $product['stok'] ?></span>
                </div>

                <div class="flex gap-1.5 pt-1">
                    <button onclick="addToCart(<?= $product['id_product'] ?>)" 
                            class="flex-1 bg-green-600 text-white py-1.5 px-3 rounded text-xs hover:bg-green-700">
                        <i class="fas fa-shopping-cart text-[10px] mr-1"></i>
                        Tambah ke Keranjang
                    </button>
                    <button onclick="toggleWishlist(this, <?= $product['id_product'] ?>)" 
                            class="p-1.5 border rounded">
                        <i class="fas fa-heart text-xs <?= $is_wishlisted ? 'text-red-500' : 'text-gray-400' ?>"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section (similarly updated) -->
    <section class="px-2 py-4">
        <h2 class="text-lg font-bold text-gray-900 mb-3">Ulasan Pembeli</h2>
        
        <!-- Review Statistics -->
        <div class="bg-white rounded-md shadow p-4 mb-4">
            <div class="flex items-center gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-900 mb-2"><?= number_format($rating, 1) ?></div>
                    <div class="flex text-yellow-400 justify-center mb-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="text-gray-500 text-sm">Dari 0 ulasan</div>
                </div>
                <div class="flex-1">
                    <div class="space-y-2">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="flex items-center gap-4">
                            <div class="flex text-yellow-400">
                                <?php for ($j = 1; $j <= $i; $j++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="flex-1">
                                <div class="h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-yellow-400 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="text-gray-500 w-12 text-right">0</div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments List -->
        <div class="space-y-4">
            <div class="bg-white rounded-md shadow p-4 text-center">
                <div class="text-gray-400 mb-1">
                    <i class="far fa-comment-dots text-2xl"></i>
                </div>
                <p class="text-sm text-gray-600 font-medium">Belum ada ulasan</p>
                <p class="text-xs text-gray-500 mt-1">Jadilah yang pertama memberikan ulasan untuk produk ini</p>
            </div>
        </div>
    </section>
</main>

<!-- Add this after header -->
<!-- Login Prompt Modal -->
<div id="loginPrompt" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl">
        <div class="text-center mb-6">
            <i class="fas fa-lock text-4xl text-green-600 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-900">Login Required</h3>
            <p class="text-gray-600 mt-2">Please login or create an account to add items to your wishlist</p>
        </div>
        <div class="space-y-3">
            <a href="<?= base_url('auth') ?>" class="flex items-center justify-center gap-2 w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-all">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login to Your Account</span>
            </a>
            <a href="<?= base_url('auth/register') ?>" class="flex items-center justify-center gap-2 w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-all">
                <i class="fas fa-user-plus"></i>
                <span>Create New Account</span>
            </a>
            <button onclick="closeLoginPrompt()" class="w-full text-gray-500 hover:text-gray-700 py-2">Maybe Later</button>
        </div>
    </div>
</div>

<!-- Add these styles -->
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

<!-- Update the wishlist toggle function -->
<script>
function toggleWishlist(button, productId) {
    <?php if (!$this->session->userdata('logged_in')): ?>
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    <?php endif; ?>

    const icon = button.querySelector('i');
    
    if (icon.classList.contains('text-red-500')) {
        icon.classList.remove('text-red-500');
        icon.classList.add('animate-heartbeat-out');
    } else {
        icon.classList.add('text-red-500');
        icon.classList.add('animate-heartbeat');
    }
    
    setTimeout(() => {
        icon.classList.remove('animate-heartbeat', 'animate-heartbeat-out');
    }, 500);

    fetch('<?= base_url('wishlist/toggle/') ?>' + productId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .catch(error => {
        console.error('Error:', error);
        if (icon.classList.contains('text-red-500')) {
            icon.classList.remove('text-red-500');
        } else {
            icon.classList.add('text-red-500');
        }
    });
}

function closeLoginPrompt() {
    document.getElementById('loginPrompt').classList.add('hidden');
}

document.getElementById('loginPrompt').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginPrompt();
    }
});
</script>

<!-- In your Product Controller, add this to the detail method -->
<?php
// Add this to get wishlist status
$is_wishlisted = $this->session->userdata('logged_in') ? 
    $this->wishlist_model->is_wishlisted($this->session->userdata('id_user'), $product['id_product']) : 
    false;
$data['is_wishlisted'] = $is_wishlisted;
?>

function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const newValue = parseInt(input.value) + change;
    if (newValue >= 1 && newValue <= <?= $product['stok'] ?>) {
        input.value = newValue;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    // Add your cart logic here
}
</script>

<?php $this->load->view('templates/footer'); ?>