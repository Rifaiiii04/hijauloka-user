<div class="container mx-auto px-4 py-20">
    <div class="text-center relative mb-12">
        <h1 class="text-4xl font-bold text-green-800 mt-10 mb-6">My Wishlist</h1>
        <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
    </div>
    
    <?php if (empty($wishlist)): ?>
        <div class="text-center py-8">
            <p class="text-gray-500">Your wishlist is empty</p>
            <a href="<?= base_url() ?>" class="inline-block mt-4 text-green-600 hover:text-green-700">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($wishlist as $item): ?>
                <?php 
                if (!empty($item['gambar'])) {
                    $gambarArr = explode(',', $item['gambar']);
                    $gambar = trim($gambarArr[0]);
                } else {
                    $gambar = 'default.jpg';
                }
                ?>
                <div class="bg-white rounded-lg overflow-hidden shadow h-full flex flex-col">
                    <a href="<?= base_url('product/detail/' . $item['id_product']) ?>" class="block flex-grow">
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                                 alt="<?= $item['nama_product']; ?>" 
                                 class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-300">
                        </div>
                        <div class="p-4">
                            <h3 class="text-base sm:text-xl font-semibold mb-2 line-clamp-1"><?= $item['nama_product']; ?></h3>
                            <div class="flex flex-wrap gap-1 sm:gap-2 mb-3">
                                <?php
                                $this->db->select('c.nama_kategori');
                                $this->db->from('product_category pc');
                                $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
                                $this->db->where('pc.id_product', $item['id_product']);
                                $product_categories = $this->db->get()->result_array();
                                
                                foreach ($product_categories as $cat) : ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full"><?= $cat['nama_kategori'] ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </a>

                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <?php 
                                $rating = floatval($item['rating'] ?? 0);
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
                            <span class="text-sm sm:text-lg font-bold">Rp<?= number_format($item['harga'], 0, ',', '.'); ?></span>
                            <div class="flex gap-2">
                                <button onclick="removeFromWishlist(<?= $item['id_product'] ?>, this)" 
                                        class="bg-red-100 text-red-600 p-2 sm:p-2.5 rounded-md hover:bg-red-200 transition-colors">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button onclick="handleCartClick(event, <?= $item['id_product'] ?>)"
                                        class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
                                    <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>