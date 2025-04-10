<!-- Section header mobile -->
<section class="bg-green-800 md:hidden flex h-48 relative top-20 items-center justify-center">
  <div class="p-3 w-72">
    <div class="swiper mySwiper h-full">
      <div class="swiper-wrapper">
        <div class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden">
          <img src="<?= base_url('assets/plant/plant1.png') ;?>" class="w-full h-full object-cover" />
          <div class="absolute inset-0 flex flex-col justify-center items-center bg-black/30 text-white text-center">
            <h1 class="text-2xl font-bold">Welcome to Our Website</h1>
            <p class="text-sm mt-2">Explore the beauty of nature with us</p>
          </div>
        </div>
        <div class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden">
          <img src="<?= base_url('assets/plant/plant2.png') ;?>" class="w-full h-full object-cover" />
          <div class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 text-white text-center">
            <h1 class="text-2xl font-bold">Innovative Technology</h1>
            <p class="text-sm mt-2">Discover the latest trends in tech</p>
          </div>
        </div>
        <div class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden">
          <img src="<?= base_url('assets/plant/plant3.png') ;?>" class="w-full h-full object-cover" />
          <div class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 text-white text-center">
            <h1 class="text-2xl font-bold">Build Your Future</h1>
            <p class="text-sm mt-2">Grow your business with us</p>
          </div>
        </div>
      </div>
      <!-- Pagination -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- Section header desktop -->
<section class="hidden md:flex justify-between bg-green-800 relative top-20 flex-col md:flex-row p-10 items-center text-center md:text-left text-white">
  <div class="md:w-[450px]">
    <h1 class="text-4xl font-bold">Selamat Datang di HijauLoka!</h1>
    <p class="mt-4 text-lg leading-relaxed">
      Temukan berbagai tanaman hias sesuai selera Anda di sini. Buat
      lingkungan sekitar Anda semakin indah dengan tanaman hias.
    </p>
    <a href="#main" class="text-xl relative top-8 bg-white rounded-lg text-green-800 p-3 font-bold shadow-lg">Lihat Produk</a>
  </div>
  <div class="md:w-1/2 grid grid-cols-3 gap-2 items-center">
    <div class="grid grid-rows-2 gap-2">
      <img src="<?= base_url('assets/plant/plant2.png') ;?>" class="w-full h-full object-cover rounded-lg" alt="Plant Image" />
      <img src="<?= base_url('assets/plant/plant1.png') ;?>" class="w-full h-full object-cover rounded-lg" alt="Plant Image" />
    </div>
    <div class="col-span-2 relative">
      <img src="<?= base_url('assets/plant/plant3.png') ;?>" class="w-full h-full object-cover rounded-lg" alt="Plant Image" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent p-6 flex flex-col justify-end rounded-lg">
        <h3 class="text-2xl font-bold">Anthurium Flower</h3>
        <p class="text-sm">
          The flower of human being. It has meaningful of fact that the
          plant always grow whatever season and weather...
        </p>
        <a href="#seller" class="mt-4 px-4 py-2 bg-white text-green-900 font-bold rounded-lg text-center hover:bg-green-800 hover:text-white transition duration-500">READ MORE</a>
      </div>
    </div>
  </div>
</section>