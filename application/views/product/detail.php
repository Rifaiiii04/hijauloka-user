<?php $this->load->view('templates/header'); ?>

<main class="container mx-auto px-4 py-8 mt-28">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="text-gray-600">
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

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Product Images -->
            <div class="md:w-1/2 p-4">
                <div class="relative aspect-w-1 aspect-h-1">
                    <?php 
                    $images = explode(',', $product['gambar']);
                    $mainImage = trim($images[0]);
                    ?>
                    <img src="http://localhost/hijauloka/uploads/<?= $mainImage ?>" 
                         alt="<?= $product['nama_product'] ?>" 
                         class="w-full h-96 object-cover rounded-lg">
                </div>
                
                <?php if (count($images) > 1): ?>
                <div class="grid grid-cols-4 gap-2 mt-4">
                    <?php foreach($images as $image): ?>
                    <img src="http://localhost/hijauloka/uploads/<?= trim($image) ?>" 
                         alt="Product thumbnail" 
                         class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="md:w-1/2 p-6">
                <div class="flex flex-wrap gap-2 mb-4">
                    <?php foreach($categories as $category): ?>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                            <?= $category['nama_kategori'] ?>
                        </span>
                    <?php endforeach; ?>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= $product['nama_product'] ?></h1>
                
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
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
                    <span class="text-gray-500 ml-2">(<?= number_format($rating, 1) ?>)</span>
                </div>

                <p class="text-3xl font-bold text-green-600 mb-6">
                    Rp<?= number_format($product['harga'], 0, ',', '.') ?>
                </p>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Deskripsi Tanaman</h2>
                    <p class="text-gray-600"><?= nl2br($product['desk_product']) ?></p>
                </div>

                <?php if ($product['cara_rawat_video']): ?>
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Cara Merawat</h2>
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe src="<?= $product['cara_rawat_video'] ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                class="rounded-lg"></iframe>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex items-center gap-4">
                    <div class="flex items-center border rounded-lg">
                        <button class="px-4 py-2 text-gray-600 hover:text-gray-800" onclick="updateQuantity(-1)">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?= $product['stok'] ?>"
                               class="w-16 text-center border-x py-2">
                        <button class="px-4 py-2 text-gray-600 hover:text-gray-800" onclick="updateQuantity(1)">+</button>
                    </div>
                    <span class="text-gray-500">Stok: <?= $product['stok'] ?></span>
                </div>

                <div class="flex gap-4 mt-6">
                    <button onclick="addToCart(<?= $product['id_product'] ?>)" 
                            class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Tambah ke Keranjang
                    </button>
                    <button onclick="toggleWishlist(this, <?= $product['id_product'] ?>)" 
                            class="p-3 border rounded-lg hover:bg-gray-50 transition-colors <?= $is_wishlisted ? 'active' : '' ?>">
                        <i class="fas fa-heart <?= $is_wishlisted ? 'text-red-500' : 'text-gray-400' ?>"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
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