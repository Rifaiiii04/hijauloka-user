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

    .scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Chrome, Safari and Opera */
}
</style>

<?php $this->load->view('templates/section')?>

<!-- Top 10 Product -->
<section class="px-4 py-8">
    <h2 class="text-2xl font-bold text-green-800 mb-4">Rekomendasi Terbaik</h2>
    <div class="relative">
        <div class="overflow-x-auto scrollbar-hide ">
            <div class="flex gap-4 pb-4">
                <?php foreach ($featured_products as $product): ?>
                    <?php 
                    // Process image first
                    $productImage = 'default.jpg';
                    if (!empty($product['gambar'])) {
                        $gambarArr = explode(',', $product['gambar']);
                        $productImage = trim($gambarArr[0]);
                    }
                    ?>
                    <div class="w-72 flex-shrink-0">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <a href="<?= base_url('product/detail/' . $product['id_product']) ?>">
                                <img src="http://localhost/hijauloka/uploads/<?= $productImage ?>" 
                                     alt="<?= $product['nama_product'] ?>"
                                     class="w-full h-48 object-cover hover:scale-110 transition-all duration-300">
                            </a>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2"><?= $product['nama_product'] ?></h3>
                                <!-- Rest of the card content stays the same -->
                                <div class="flex items-center mb-2 flex-shrink-0">
                                    <div class="flex text-yellow-400">
                                        <?php 
                                        $rating = floatval($product['rating'] ?? 0);
                                        for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="far fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(<?= number_format($rating, 1) ?>)</span>
                                </div>
                                <div class="mt-auto flex justify-between items-center flex-shrink-0">
                                    <span class="text-green-600 font-bold">Rp<?= number_format($product['harga'], 0, ',', '.') ?></span>
                                    <!-- In the featured products loop, update the wishlist button section -->
                                    <div class="flex gap-2">
                                        <?php 
                                        $is_wishlisted = $this->session->userdata('logged_in') ? 
                                            $this->wishlist_model->is_wishlisted($this->session->userdata('id_user'), $product['id_product']) : 
                                            false;
                                        ?>
                                        <button onclick="toggleWishlist(this, <?= $product['id_product'] ?>)" 
                                                class="wishlist-btn p-2 text-gray-600 bg-gray-100 rounded-md hover:text-red-500">
                                            <i class="fas fa-heart <?= $is_wishlisted ? 'text-red-500' : '' ?>"></i>
                                        </button>
                                        <button onclick="handleCartClick(event, <?= $product['id_product'] ?>)" 
                                                class="p-2 text-white bg-green-600 rounded-md hover:bg-green-700 active:bg-green-800">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Section - Improved UI -->
<section class="px-4 py-12 ">
    <div class="container mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-green-800 mb-2">Kategori Tanaman</h2>
                <p class="text-gray-600">Temukan berbagai jenis tanaman sesuai kebutuhan Anda</p>
            </div>
            <a href="<?= base_url('category') ?>" class="text-green-700 hover:text-green-900 font-medium flex items-center">
                Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 gap-6 max-w-5xl mx-auto">
            <!-- Plants Category -->
            <div class="relative h-[400px] rounded-xl overflow-hidden shadow-lg group">
                <img src="<?= base_url('assets/img/plantcategory.png') ?>" alt="Plants" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-green-900 to-transparent opacity-80"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 transform transition-transform duration-300 group-hover:translate-y-[-10px]">
                    <h3 class="text-2xl font-bold text-white mb-2">Plants</h3>
                    <p class="text-green-100 mb-4">Koleksi tanaman hias indoor & outdoor</p>
                    <a href="<?= base_url('category/plants') ?>" class="inline-block bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all">
                        <i class="fas fa-leaf mr-2"></i> Jelajahi
                    </a>
                </div>
            </div>

            <div class="grid grid-rows-2 gap-6">
                <!-- Seeds Category -->
                <div class="relative h-[190px] rounded-xl overflow-hidden shadow-lg group">
                    <img src="<?= base_url('assets/img/seedscategory.png') ?>" alt="Seeds" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900 to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 transform transition-transform duration-300 group-hover:translate-y-[-5px]">
                        <h3 class="text-xl font-bold text-white mb-1">Seeds</h3>
                        <a href="<?= base_url('category/seeds') ?>" class="inline-block text-green-100 hover:text-white transition-colors">
                            <i class="fas fa-seedling mr-1"></i> Lihat Koleksi
                        </a>
                    </div>
                </div>

                <!-- Pots Category -->
                <div class="relative h-[190px] rounded-xl overflow-hidden shadow-lg group">
                    <img src="<?= base_url('assets/img/category-pots.png') ?>" alt="Pots" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900 to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 transform transition-transform duration-300 group-hover:translate-y-[-5px]">
                        <h3 class="text-xl font-bold text-white mb-1">Pots</h3>
                        <a href="<?= base_url('category/pots') ?>" class="inline-block text-green-100 hover:text-white transition-colors">
                            <i class="fas fa-box mr-1"></i> Lihat Koleksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section - Improved UI -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
            <div class="max-w-xl">
                <h2 class="text-3xl font-bold text-green-800 mb-3">Postingan Blog Terbaru</h2>
                <p class="text-gray-600">Tips perawatan tanaman, inspirasi dekorasi, dan panduan berkebun untuk membantu Anda merawat tanaman dengan baik</p>
            </div>
            <a href="#" class="mt-4 md:mt-0 px-6 py-3 bg-green-800 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        <!-- Featured Blog Posts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Featured Post 1 -->
            <div class="rounded-xl overflow-hidden shadow-lg group h-[400px] relative">
                <img src="<?= base_url('assets/img/news1.png') ?>" alt="Featured blog post" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-green-900/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <div class="flex items-center text-green-100 mb-3">
                        <!-- <img src="<?= base_url('assets/img/avatar.jpg') ?>" alt="Author" class="w-8 h-8 rounded-full mr-3 border-2 border-white"> -->
                        <span>By Muhamad Rifai</span>
                        <span class="mx-3">•</span>
                        <span>20 Mei, 2023</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-green-200 transition-colors">Cara Merawat Tanaman Hias Indoor dengan Mudah</h3>
                    <p class="text-green-100 mb-4 line-clamp-2">Pelajari tips dan trik merawat tanaman hias indoor agar tetap segar dan indah sepanjang tahun.</p>
                    <a href="#" class="inline-flex items-center text-white hover:text-green-200 transition-colors">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-long-arrow-alt-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Featured Post 2 -->
            <div class="rounded-xl overflow-hidden shadow-lg group h-[400px] relative">
                <img src="<?= base_url('assets/img/news2.png') ?>" alt="Featured blog post" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-green-900/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <div class="flex items-center text-green-100 mb-3">
                        <!-- <img src="<?= base_url('assets/img/avatar.jpg') ?>" alt="Author" class="w-8 h-8 rounded-full mr-3 border-2 border-white"> -->
                        <span>By Muhamad Rifai</span>
                        <span class="mx-3">•</span>
                        <span>20 Mei, 2023</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-green-200 transition-colors">10 Tanaman yang Cocok untuk Pemula</h3>
                    <p class="text-green-100 mb-4 line-clamp-2">Baru memulai hobi berkebun? Ini dia 10 tanaman yang mudah dirawat dan cocok untuk pemula.</p>
                    <a href="#" class="inline-flex items-center text-white hover:text-green-200 transition-colors">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-long-arrow-alt-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Small Blog Posts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Small Post 1 -->
            <div class="flex gap-4 group">
                <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
                    <img src="<?= base_url('assets/img/news1.png')?>" alt="Blog thumbnail" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-green-700 transition-colors line-clamp-2 mb-2">Panduan Lengkap Menanam Sayuran di Rumah</h3>
                    <p class="text-sm text-gray-500 flex items-center">
                        <span>By Dea Amelia</span>
                        <span class="mx-2">•</span>
                        <span>18 April, 2023</span>
                    </p>
                </div>
            </div>
            
            <!-- Small Post 2 -->
            <div class="flex gap-4 group">
                <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
                    <img src="<?= base_url('assets/img/news1.png')?>" alt="Blog thumbnail" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-green-700 transition-colors line-clamp-2 mb-2">5 Tanaman Pembersih Udara untuk Kamar Tidur</h3>
                    <p class="text-sm text-gray-500 flex items-center">
                        <span>By Dea Amelia</span>
                        <span class="mx-2">•</span>
                        <span>12 April, 2023</span>
                    </p>
                </div>
            </div>
            
            <!-- Small Post 3 -->
            <div class="flex gap-4 group">
                <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
                    <img src="<?= base_url('assets/img/news1.png')?>" alt="Blog thumbnail" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-green-700 transition-colors line-clamp-2 mb-2">Cara Mengatasi Hama pada Tanaman Hias</h3>
                    <p class="text-sm text-gray-500 flex items-center">
                        <span>By Dea Amelia</span>
                        <span class="mx-2">•</span>
                        <span>5 April, 2023</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<!-- blog -->
<section class="mt-10">
<!-- After the blog section, add a "Why Choose Us" section -->
<section class="py-16 ">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-green-800 mb-12">Mengapa Memilih HijauLoka?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Feature 1 -->
            <div class="bg-white p-6 rounded-xl shadow-md text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-leaf text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-green-800">Tanaman Berkualitas</h3>
                <p class="text-gray-600">Semua tanaman kami dirawat dengan teliti dan dipilih dari sumber terbaik untuk memastikan kesehatan dan pertumbuhan optimal.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white p-6 rounded-xl shadow-md text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-green-800">Pengiriman Aman</h3>
                <p class="text-gray-600">Kami menggunakan metode pengiriman khusus untuk memastikan tanaman Anda tiba dalam kondisi segar dan sehat.</p>
            </div>
            
            <!-- Feature 3 - Changed from Dukungan Ahli -->
            <div class="bg-white p-6 rounded-xl shadow-md text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-green-800">Garansi Tanaman</h3>
                <p class="text-gray-600">Kami memberikan garansi penggantian untuk tanaman yang tidak tumbuh dengan baik dalam 14 hari setelah pembelian.</p>
            </div>
        </div>
    </div>
</section>

<!-- Add a call-to-action section -->
<section class="py-16 bg-green-800 text-white relative overflow-hidden">
    <!-- Decorative plant elements -->
    <div class="absolute -left-16 -bottom-10 opacity-10">
        <i class="fas fa-leaf text-9xl transform rotate-45"></i>
    </div>
    <div class="absolute right-16 top-10 opacity-10">
        <i class="fas fa-seedling text-8xl"></i>
    </div>
    
    <div class="container mx-auto px-4 text-center relative z-10">
        <h2 class="text-4xl font-bold mb-6">Hijaukan Rumah Anda Sekarang!</h2>
        <p class="text-lg mb-8 max-w-2xl mx-auto">Temukan koleksi tanaman pilihan kami untuk menciptakan ruangan yang lebih segar, sehat, dan indah untuk Anda dan keluarga.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= base_url('popular') ?>" class="inline-block bg-white text-green-800 font-bold px-8 py-4 rounded-lg hover:bg-green-100 transition-all transform hover:-translate-y-1 hover:shadow-lg">
                <i class="fas fa-shopping-cart mr-2"></i> Belanja Sekarang
            </a>
            <!-- <a href="<?= base_url('category') ?>" class="inline-block bg-transparent border-2 border-white text-white font-bold px-8 py-4 rounded-lg hover:bg-white/10 transition-all transform hover:-translate-y-1 hover:shadow-lg">
                <i class="fas fa-th-large mr-2"></i> Lihat Kategori
            </a> -->
        </div>
    </div>
</section>


 </section>
</main>



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
<!-- Add this function to your existing script section -->
<script>
function closeLoginPrompt() {
    const modal = document.getElementById('loginPrompt');
    modal.classList.add('hidden');
}

// Add click outside modal to close
document.getElementById('loginPrompt').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginPrompt();
    }
});
</script>

<!-- Add this notification modal after the login prompt modal -->
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

<!-- Add these styles -->
<style>
    @keyframes bounce-once {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-bounce-once {
        animation: bounce-once 0.5s ease-in-out;
    }
</style>

<!-- Update the handleCartClick function -->
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

