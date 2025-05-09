<div class="container mx-auto px-4 py-8">
    <!-- Category Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-green-800 mb-2">
            <?php 
            $category_names = [
                'plants' => 'Tanaman Indoor',
                'seeds' => 'Tanaman Outdoor',
                'pots' => 'Florikultura'
            ];
            echo $category_names[$category] ?? ucfirst($category); 
            ?>
        </h1>
        <p class="text-gray-600">
            Temukan berbagai <?= $category_names[$category] ?? $category ?> berkualitas untuk koleksi Anda
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
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productsGrid">
        <?php foreach ($products as $product): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <a href="<?= base_url('product/detail/' . $product['id_product']) ?>" class="block">
                    <div class="relative pb-[100%]">
                        <?php 
                        $gambar = !empty($product['gambar']) ? 
                            (strpos($product['gambar'], ',') !== false ? 
                                explode(',', $product['gambar'])[0] : 
                                $product['gambar']) : 
                            'default.jpg';
                        ?>
                        <img src="<?= base_url('uploads/' . $gambar) ?>" 
                             alt="<?= $product['nama_product'] ?>" 
                             class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2"><?= $product['nama_product'] ?></h3>
                        <p class="text-green-600 font-bold mb-2">Rp<?= number_format($product['harga'], 0, ',', '.') ?></p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Stok: <?= $product['stok'] ?></span>
                            <button onclick="addToCart(<?= $product['id_product'] ?>)" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-cart-plus"></i> Beli
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <?php if (empty($products)): ?>
        <div class="text-center py-12">
            <i class="fas fa-leaf text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada produk</h3>
            <p class="text-gray-500">Produk akan segera hadir</p>
            <p class="text-gray-500 mt-2">Category: <?= $category ?></p>
        </div>
    <?php endif; ?>
</div>

<script>
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
        const priceA = parseInt(a.querySelector('p').textContent.replace(/[^0-9]/g, ''));
        const priceB = parseInt(b.querySelector('p').textContent.replace(/[^0-9]/g, ''));
        
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

// Add to cart functionality
function addToCart(productId) {
    fetch('<?= base_url('cart/add') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produk berhasil ditambahkan ke keranjang');
            // Update cart count if needed
        } else {
            alert(data.message || 'Gagal menambahkan produk ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan produk ke keranjang');
    });
}
</script>