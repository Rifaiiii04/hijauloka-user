<!-- Bagian Header Mobile -->
<section class="bg-green-800 md:hidden flex h-48 relative top-20 items-center justify-center">
  <div class="p-3 w-72">
    <div class="swiper mySwiper h-full">
      <div class="swiper-wrapper">
        <div class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden">
          <img src="<?= base_url('assets/plant/plant1.png') ;?>" class="w-full h-full object-cover" />
          <div class="absolute inset-0 flex flex-col justify-center items-center bg-black/30 text-white text-center">
            <h1 class="text-2xl font-bold">Selamat Datang di Website Kami</h1>
            <p class="text-sm mt-2">Jelajahi keindahan alam bersama kami</p>
          </div>
        </div>
        <div class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden">
          <img src="<?= base_url('assets/plant/plant2.png') ;?>" class="w-full h-full object-cover" />
          <div class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 text-white text-center">
            <h1 class="text-2xl font-bold">Teknologi Inovatif</h1>
            <p class="text-sm mt-2">Temukan tren teknologi terbaru</p>
          </div>
        </div>
        <div class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden">
          <img src="<?= base_url('assets/plant/plant3.png') ;?>" class="w-full h-full object-cover" />
          <div class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 text-white text-center">
            <h1 class="text-2xl font-bold">Bangun Masa Depan Anda</h1>
            <p class="text-sm mt-2">Kembangkan bisnis Anda bersama kami</p>
          </div>
        </div>
      </div>
      <!-- Pagination -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- Bagian Header Desktop -->
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
      <img src="<?= base_url('assets/plant/plant2.png') ;?>" class="w-full h-full object-cover rounded-lg" alt="Gambar Tanaman" />
      <img src="<?= base_url('assets/plant/plant1.png') ;?>" class="w-full h-full object-cover rounded-lg" alt="Gambar Tanaman" />
    </div>
    <div class="col-span-2 relative">
      <img src="<?= base_url('assets/plant/plant3.png') ;?>" class="w-full h-full object-cover rounded-lg" alt="Gambar Tanaman" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent p-6 flex flex-col justify-end rounded-lg">
        <h3 class="text-2xl font-bold">Bunga Anthurium</h3>
        <p class="text-sm">
          Bunga yang melambangkan manusia. Bunga ini memiliki makna bahwa
          tanaman selalu tumbuh di segala musim dan cuaca...
        </p>
        <a href="#seller" class="mt-4 px-4 py-2 bg-white text-green-900 font-bold rounded-lg text-center hover:bg-green-800 hover:text-white transition duration-500">BACA SELENGKAPNYA</a>
      </div>
    </div>
  </div>
</section>