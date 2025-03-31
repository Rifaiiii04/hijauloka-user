<?php $this->load->view('templates/header'); ?>

<!-- target scroll -->
<div id="main"></div>

<!-- main -->
<main class="h-full mt-28">
  <!-- For You Section -->
  <h1 class="font-bold ml-3 text-md text-green-800 sm:text-lg">For You</h1>
  <div class="h-full p-2 sm:p-3 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 md:gap-y-5 gap-y-10 place-items-center sm:place-items-start justify-between">
    <?php foreach ($produk_terbaru as $produk) : ?>
      <?php 
      if (!empty($produk['gambar'])) {
          $gambarArr = explode(',', $produk['gambar']);
          $gambar = trim($gambarArr[0]);
      } else {
          $gambar = 'default.jpg'; 
      }
      ?>
      <!-- Card -->
      <div class="bg-white shadow-lg w-40 sm:w-52 h-72 sm:h-96 p-2 rounded-lg transform hover:scale-105 transition-all duration-300">
        <div class="absolute cursor-pointer">
          <i class="fa-solid fa-heart wishlist-icon text-2xl sm:text-3xl text-slate-300"></i>
        </div>
        <a href="<?= base_url('hijauloka/produk/detail/' . $produk['id_product']); ?>">
          <div class="flex flex-col h-full">
            <div class="h-40 sm:h-52 overflow-hidden rounded-md shadow-lg">
              <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                   alt="<?= $produk['nama_product']; ?>" 
                   class="w-full h-full object-cover">
            </div>
            <div class="flex-1 flex flex-col justify-between pt-4">
              <h3 class="font-bold text-slate-800 text-md sm:text-xl line-clamp-2">
                <?= $produk['nama_product']; ?>
              </h3>
              <div class="flex justify-between items-end mt-3">
                <h3 class="font-bold text-green-800 text-xs sm:text-lg">
                  Rp<?= number_format($produk['harga'], 0, ',', '.'); ?>
                </h3>
                <i class="fa-solid fa-cart-shopping text-xl sm:text-2xl text-green-800 mb-1"></i>
              </div>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="mt-8"></div>

  <!-- Best Seller Section -->
  <div class="flex justify-between items-center">
    <h1 class="font-bold ml-3 text-md text-green-800 sm:text-lg">Best Seller</h1>
    <a href="#" class="font-bold mr-5 text-md text-green-800 sm:text-lg decoration-1">
      <u>Lihat lainnya..</u>
    </a>
  </div>
  <div class="h-full p-2 sm:p-3 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 md:gap-y-5 gap-y-10 place-items-center sm:place-items-start justify-between">
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
        <!-- Card -->
        <div class="bg-white shadow-lg w-40 sm:w-52 h-72 sm:h-96 p-2 rounded-lg transform hover:scale-105 transition-all duration-300">
          <div class="absolute cursor-pointer">
            <i class="fa-solid fa-heart wishlist-icon text-2xl sm:text-3xl text-slate-300"></i>
          </div>
          <a href="<?= base_url('hijauloka/produk/detail/' . $produk->id_product); ?>">
            <div class="flex flex-col h-full">
              <div class="h-40 sm:h-52 overflow-hidden rounded-md shadow-lg">
                <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                     alt="<?= $produk->nama_produk; ?>" 
                     class="w-full h-full object-cover">
              </div>
              <div class="flex-1 flex flex-col justify-between pt-4">
                <h3 class="font-bold text-slate-800 text-md sm:text-xl line-clamp-2">
                  <?= $produk->nama_produk; ?>
                </h3>
                <div class="flex justify-between items-end mt-3">
                  <h3 class="font-bold text-green-800 text-xs sm:text-lg">
                    Rp<?= number_format($produk->harga, 0, ',', '.'); ?>
                  </h3>
                  <i class="fa-solid fa-cart-shopping text-xl sm:text-2xl text-green-800 mb-1"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-gray-500 w-full">Tidak ada produk terlaris untuk saat ini.</p>
    <?php endif; ?>
  </div>
</main>


<?php $this->load->view('templates/footer'); ?>
