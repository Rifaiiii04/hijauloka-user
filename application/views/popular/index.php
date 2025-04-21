<?php $this->load->view('templates/header'); ?>

<div class="mb-12 mt-28 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
        Produk Populer
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
    </h1>
    <p class="text-gray-600 mt-3">Tanaman hias yang paling banyak diminati</p>
</div>

<!-- Category Filter -->
<div class="container mx-auto px-4 mb-8">
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
</div>

<main class="container mx-auto px-4 ">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($produk_populer as $produk) : ?>
            <?php 
            if (!empty($produk['gambar'])) {
                $gambarArr = explode(',', $produk['gambar']);
                $gambar = trim($gambarArr[0]);
            } else {
                $gambar = 'default.jpg';
            }
            ?>
            <div class="bg-white rounded-lg overflow-hidden shadow-lg h-full flex flex-col transform hover:scale-105 transition-all duration-300">
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
                                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-800 text-[10px] sm:text-xs rounded-full"><?= $cat['nama_kategori'] ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(4.5)</span>
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
                                <button onclick="toggleWishlist(<?= $produk['id_product'] ?>)" 
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
</main>

<?php $this->load->view('templates/footer'); ?>