<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/section')?>

<!-- Produk Terlaris section -->
 
<div class="mb-12 mt-28 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
        Produk Terlaris
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
    </h1>
    <p class="text-gray-600 mt-3">Tanaman hias pilihan terbaik yang paling diminati</p>
</div>

<main class="h-full" id="produk_section">
  <div class="h-full p-2 sm:p-3 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php if (!empty($produk_terlaris)) : ?>
        <?php foreach ($produk_terlaris as $produk) : ?>
            <?php 
            if (!empty($produk->gambar)) {
                $gambarArr = explode(',', $produk->gambar);
                $gambar = trim($gambarArr[0]);
            } else {
                $gambar = 'default.jpg';
            }
            ?>
            <!-- Product Card for Produk Terlaris -->
            <div class="bg-white rounded-lg overflow-hidden shadow">
                <div class="aspect-w-1 aspect-h-1">
                    <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                         alt="<?= $produk->nama_produk; ?>" 
                         class="w-full h-48 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2"><?= $produk->nama_produk; ?></h3>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php
                        $this->db->select('c.nama_kategori');
                        $this->db->from('product_category pc');
                        $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
                        $this->db->where('pc.id_product', $produk->id_product);
                        $product_categories = $this->db->get()->result_array();
                        
                        foreach ($product_categories as $cat) : ?>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full"><?= $cat['nama_kategori'] ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-0 sm:justify-between sm:items-center">
                        <span class="text-lg font-bold">Rp<?= number_format($produk->harga, 0, ',', '.'); ?></span>
                        <button class="w-full sm:w-auto bg-green-600 text-white px-3 sm:px-4 py-2 rounded-md hover:bg-green-700 text-sm sm:text-base transition-colors">
                            Order Now
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center text-gray-500 col-span-full">Tidak ada produk terlaris untuk saat ini.</p>
    <?php endif; ?>
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
      <div class="bg-white rounded-lg overflow-hidden shadow">
        <div class="aspect-w-1 aspect-h-1">
          <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
               alt="<?= $produk['nama_product']; ?>" 
               class="w-full h-48 object-cover">
        </div>
        <div class="p-4">
          <h3 class="text-xl font-semibold mb-2"><?= $produk['nama_product']; ?></h3>
          <div class="flex flex-wrap gap-2 mb-3">
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
          <div class="flex flex-col sm:flex-row gap-2 sm:gap-0 sm:justify-between sm:items-center">
                        <span class="text-lg font-bold">Rp<?= number_format($produk['harga'], 0, ',', '.'); ?></span>
                        <button class="w-full sm:w-auto bg-green-600 text-white px-3 sm:px-4 py-2 rounded-md hover:bg-green-700 text-sm sm:text-base transition-colors">
                            Order Now
                        </button>
                    </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<?php $this->load->view('templates/footer'); ?>
