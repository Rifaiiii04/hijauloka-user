<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HomePage</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <!-- Swiper CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <style>
      .wishlist {
        color: red !important;
      }
    </style>
  </head>
  <body class="bg-slate-100 overflow-x-hidden font-poppins">
    <!-- Header & Navbar -->
    <header>
      <nav class="bg-green-800 flex items-center p-3 fixed top-0 w-screen z-100">
        <!-- Bagian Kiri -->
        <div class="flex items-center">
          <!-- Tombol Mobile -->
          <button
            id="toggleSidebar"
            class="w-12 h-12 mt-2 md:hidden flex flex-col items-center space-y-2 justify-center cursor-pointer"
          >
            <div
              id="line1"
              class="bg-white rounded-lg w-10 h-1 transition-all duration-300"
            ></div>
            <div
              id="line2"
              class="bg-white rounded-lg w-8 h-1 transition-all duration-300"
            ></div>
            <div
              id="line3"
              class="bg-white rounded-lg w-10 h-1 transition-all duration-300"
            ></div>
          </button>
          <!-- Logo versi Desktop -->
          <div class="logo hidden md:flex items-center ml-4">
            <a href="#" class="flex items-center">
              <img
                src="<?= base_url('assets/img/logo1.png') ;?>"
                alt="Logo"
                class="w-20 transition-all duration-300"
                id="logoo-img"
              />
    
            </a>
          </div>
        </div>

        <!-- Bagian Tengah (Menu) -->
        <div class="hidden md:flex flex-1 justify-center">
          <ul class="flex gap-10 text-lg text-white">
            <li>
              <a
                href="#"
                class="relative inline-block after:content-[''] after:block after:w-0 after:h-0.5 after:bg-white after:absolute after:bottom-0 after:left-0 after:transition-all after:duration-300 hover:after:w-full"
              >
                Home
              </a>
            </li>
            <li>
              <a
                href="#"
                class="relative inline-block after:content-[''] after:block after:w-0 after:h-0.5 after:bg-white after:absolute after:bottom-0 after:left-0 after:transition-all after:duration-300 hover:after:w-full"
              >
                Collection
              </a>
            </li>
            <li>
              <a
                href="#"
                class="relative inline-block after:content-[''] after:block after:w-0 after:h-0.5 after:bg-white after:absolute after:bottom-0 after:left-0 after:transition-all after:duration-300 hover:after:w-full"
              >
                Popular
              </a>
            </li>
            <li>
              <a
                href="#"
                class="relative inline-block after:content-[''] after:block after:w-0 after:h-0.5 after:bg-white after:absolute after:bottom-0 after:left-0 after:transition-all after:duration-300 hover:after:w-full"
              >
                Wishlist
              </a>
            </li>
          </ul>
        </div>

        <!-- Bagian Kanan -->
        <div class="flex items-center">
          <!-- Search dan ikon lainnya untuk tampilan desktop -->
          <ul class="hidden md:flex gap-10 text-lg items-center text-white mr-10">
            <li class="relative flex items-center">
              <a href="#" id="search-icon" class="flex items-center">
                <i
                  class="fa-solid fa-magnifying-glass text-white text-lg hover:text-green-300 transition-all duration-300"
                ></i>
              </a>
              <input
                type="text"
                id="search-input"
                class="ml-2 bg-transparent border-b-2 border-white outline-none text-white placeholder-gray-400 w-0 opacity-0 transition-all duration-300"
                placeholder="Search..."
              />
            </li>
            <li class="relative">
              <a href="#" class="relative inline-block after:content-[''] after:block after:w-0 after:h-0.5 after:bg-white after:absolute after:bottom-0 after:left-0 after:transition-all after:duration-300 hover:after:w-full">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </li>
            <li>
              <?php if($this->session->userdata('logged_in')): ?>
                <div class="flex items-center gap-3">
                  <span class="text-white"><?= $this->session->userdata('nama') ?></span>
                  <a href="<?= base_url('auth/logout') ?>" class="text-red-300 hover:text-red-400">
                    <i class="fa-solid fa-sign-out-alt"></i>
                  </a>
                </div>
              <?php else: ?>
                <a href="<?= base_url('auth') ?>" class="relative inline-block after:content-[''] after:block after:w-0 after:h-0.5 after:bg-white after:absolute after:bottom-0 after:left-0 after:transition-all after:duration-300 hover:after:w-full">
                  <i class="fa-solid fa-user"></i>
                </a>
              <?php endif; ?>
            </li>
          </ul>

          <!-- Ikon untuk tampilan mobile -->
          <ul class="flex md:hidden gap-5 text-2xl items-center mr-5 mt-4">
            <li>
              <a href="#">
                <i class="fa-solid fa-bell text-white"></i>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Sidebar (untuk mobile) -->
      <aside
        id="sidebar"
        class="fixed top-0 left-0 w-64 h-full bg-green-800 transform -translate-x-full transition-transform duration-300 z-20"
      >
        <!-- Also update the mobile sidebar user name section -->
        <div class="flex flex-col shadow-lg h-36 justify-center items-center gap-2 mt-5 font-semibold text-white">
          <img
            src="<?= base_url('assets/img/logo1.png') ;?>"
            alt="Logo"
            class="w-24 h-24 rounded-full"
          />
          <?php if($this->session->userdata('logged_in')): ?>
            <h2><?= $this->session->userdata('nama') ?></h2>
          <?php else: ?>
            <h2>Guest</h2>
          <?php endif; ?>
        </div>
        <div>
          <ul class="flex flex-col gap-6 ml-3 text-md mt-4 font-semibold text-white mr-10">
            <li class="w-56 p-1 border-2 rounded-md text-center hover:bg-green-500 transition-all duration-300 active:bg-green-600 h-10">
              <a href="#">Home</a>
            </li>
            <li class="w-56 p-1 border-2 rounded-md text-center hover:bg-green-500 transition-all duration-300 active:bg-green-600 h-10">
              <a href="#">Collection</a>
            </li>
            <li class="w-56 p-1 border-2 rounded-md text-center hover:bg-green-500 transition-all duration-300 active:bg-green-600 h-10">
              <a href="#">Popular</a>
            </li>
          </ul>
        </div>
      </aside>
      <!-- Overlay untuk sidebar mobile -->
      <div
        id="overlayy"
        class="fixed inset-0 bg-transparent z-10 hidden transition-opacity duration-300"
      ></div>

      <!-- Section header mobile -->
      <section
        class="bg-green-800 md:hidden flex h-48 relative top-20 items-center justify-center"
      >
        <div class="p-3 w-72">
          <div class="swiper mySwiper h-full">
            <div class="swiper-wrapper">
              <div
                class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden"
              >
                <img
                  src="<?= base_url('assets/plant/plant1.png') ;?>"
                  class="w-full h-full object-cover"
                />
                <div
                  class="absolute inset-0 flex flex-col justify-center items-center bg-black/30 text-white text-center"
                >
                  <h1 class="text-2xl font-bold">Welcome to Our Website</h1>
                  <p class="text-sm mt-2">
                    Explore the beauty of nature with us
                  </p>
                </div>
              </div>
              <div
                class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden"
              >
                <img
                  src="<?= base_url('assets/plant/plant2.png') ;?>"
                  class="w-full h-full object-cover"
                />
                <div
                  class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 text-white text-center"
                >
                  <h1 class="text-2xl font-bold">Innovative Technology</h1>
                  <p class="text-sm mt-2">Discover the latest trends in tech</p>
                </div>
              </div>
              <div
                class="swiper-slide relative aspect-[16/9] max-h-56 rounded-lg overflow-hidden"
              >
                <img
                  src="<?= base_url('assets/plant/plant3.png') ;?>"
                  class="w-full h-full object-cover"
                />
                <div
                  class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 text-white text-center"
                >
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
      <section
        class="hidden md:flex justify-between bg-green-800 relative top-20 flex-col md:flex-row p-10 items-center text-center md:text-left text-white"
      >
        <div class="md:w-[450px]">
          <h1 class="text-4xl font-bold">Selamat Datang di HijauLoka!</h1>
          <p class="mt-4 text-lg leading-relaxed">
            Temukan berbagai tanaman hias sesuai selera Anda di sini. Buat
            lingkungan sekitar Anda semakin indah dengan tanaman hias.
          </p>
          <a
            href="#main"
            class="text-xl relative top-8 bg-white rounded-lg text-green-800 p-3 font-bold shadow-lg"
            >Lihat Produk</a
          >
        </div>
        <div class="md:w-1/2 grid grid-cols-3 gap-2 items-center">
          <div class="grid grid-rows-2 gap-2">
            <img
              src="<?= base_url('assets/plant/plant2.png') ;?>"
              class="w-full h-full object-cover rounded-lg"
              alt="Plant Image"
            />
            <img
              src="<?= base_url('assets/plant/plant1.png') ;?>"
              class="w-full h-full object-cover rounded-lg"
              alt="Plant Image"
            />
          </div>
          <div class="col-span-2 relative">
            <img
              src="<?= base_url('assets/plant/plant3.png') ;?>"
              class="w-full h-full object-cover rounded-lg"
              alt="Plant Image"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent p-6 flex flex-col justify-end rounded-lg"
            >
              <h3 class="text-2xl font-bold">Anthurium Flower</h3>
              <p class="text-sm">
                The flower of human being. It has meaningful of fact that the
                plant always grow whatever season and weather...
              </p>
              <a
                href="#seller"
                class="mt-4 px-4 py-2 bg-white text-green-900 font-bold rounded-lg text-center hover:bg-green-800 hover:text-white transition duration-500"
                >READ MORE</a
              >
            </div>
          </div>
        </div>
      </section>
    </header>

    <!-- Skrip JavaScript -->
    <script src="<?= base_url('assets/') ;?>js/index.js"></script>
    <script src="<?= base_url('assets/') ;?>js/slide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Memuat ulang skrip JS jika diperlukan -->
    <script src="<?= base_url('assets/') ;?>js/index.js"></script>
    <script src="<?= base_url('assets/') ;?>js/slide.js"></script>
  </body>
</html>
