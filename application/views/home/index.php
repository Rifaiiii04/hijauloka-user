<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/section')?>

<!-- Produk Terlaris section -->
<div class="mb-12 mt-28">
    <div class="text-center">
        <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
            Produk Terlaris
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
        </h1>
        <p id="produk_section" class="text-gray-600 mt-3">Tanaman hias pilihan terbaik yang paling diminati</p>
    </div>

    <div class="relative mt-6">
        <div class="overflow-x-auto pb-4 hide-scrollbar">
            <div class="flex gap-4 sm:gap-6 px-2 sm:px-4 min-w-full">
                <?php if (!empty($produk_terlaris)) : ?>
                    <?php foreach ($produk_terlaris as $produk) : ?>
                        <?php 
                        if (!empty($produk['gambar'])) {
                            $gambarArr = explode(',', $produk['gambar']);
                            $gambar = trim($gambarArr[0]);
                        } else {
                            $gambar = 'default.jpg';
                        }
                        ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow flex-shrink-0 w-[220px] sm:w-[280px] transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                            <div class="aspect-w-1 aspect-h-1 overflow-hidden">
                                <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                                     alt="<?= $produk['nama_product']; ?>" 
                                     class="w-full h-32 sm:h-48 object-cover transition-transform duration-300 hover:scale-110">
                            </div>
                            <div class="p-2 sm:p-4 flex flex-col flex-1 group">
                                <div>
                                    <h3 class="text-base sm:text-xl font-semibold mb-1 sm:mb-2 line-clamp-1 transition-colors duration-300 group-hover:text-green-600"><?= $produk['nama_product']; ?></h3>
                                    <!-- Rest of your card content -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-500 w-full">Tidak ada produk terlaris untuk saat ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Untuk Anda section -->
<div class="mt-16">
    <div class="text-center mb-12">
        <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
            Untuk Anda
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
        </h1>
        <p class="text-gray-600 mt-3">Temukan koleksi tanaman hias terbaru untuk Anda</p>
    </div>
    
    <div class="h-full p-2 sm:p-3 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($produk_terbaru as $produk) : ?>
      <?php 
      if (!empty($produk['gambar'])) {
          $gambarArr = explode(',', $produk['gambar']);
          $gambar = trim($gambarArr[0]);
      } else {
          $gambar = 'default.jpg'; 
      }
      ?>
      <!-- Product Card for Untuk Anda -->
      <div class="bg-white rounded-lg overflow-hidden shadow h-full flex flex-col transform hover:scale-105 transition-all duration-300">
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
                        <!-- Add this right after header, before any content -->
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
                        
                        <!-- In your product loop, update the wishlist button -->
                        <button onclick="toggleWishlist(this, <?= $produk['id_product'] ?>)" 
                                class="wishlist-btn bg-gray-100 text-gray-600 p-2 sm:p-2.5 rounded-md hover:bg-gray-200 transition-colors <?= $is_wishlisted ? 'active' : '' ?>">
                            <i class="fas fa-heart <?= $is_wishlisted ? 'text-red-500' : '' ?>"></i>
                        </button>
                        
                        <!-- Add this before closing body tag -->
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
                        
                        // Close modal when clicking outside
                        document.getElementById('loginPrompt').addEventListener('click', function(e) {
                            if (e.target === this) {
                                closeLoginPrompt();
                            }
                        });
                        </script>
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
