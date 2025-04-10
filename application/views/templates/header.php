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
      <nav class="bg-green-800 flex items-center p-4 fixed top-0 w-screen z-50 shadow-lg">
        <!-- Left Section -->
        <div class="flex items-center">
          <button id="toggleSidebar" class="w-12 h-12 md:hidden flex flex-col items-center space-y-1.5 justify-center">
            <div id="line1" class="bg-white rounded-full w-8 h-0.5 transition-all duration-300"></div>
            <div id="line2" class="bg-white rounded-full w-6 h-0.5 transition-all duration-300"></div>
            <div id="line3" class="bg-white rounded-full w-8 h-0.5 transition-all duration-300"></div>
          </button>
          
          <div class="logo hidden md:flex items-center ml-4">
            <a href="<?= base_url() ?>" class="flex items-center gap-3">
              <img src="<?= base_url('assets/img/logo1.png') ?>" alt="Logo" class="w-16 transition-all duration-300">
              <span class="text-white font-semibold text-xl">HijauLoka</span>
            </a>
          </div>
        </div>

        <!-- Center Section -->
        <div class="hidden md:flex flex-1 justify-center">
          <ul class="flex gap-12 text-base text-white font-medium">
            <li>
              <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                <i class="fas fa-home"></i>
                <span>Home</span>
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                <i class="fas fa-leaf"></i>
                <span>Collection</span>
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                <i class="fas fa-star"></i>
                <span>Popular</span>
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                <i class="fas fa-heart"></i>
                <span>Wishlist</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Right Section -->
        <div class="flex items-center">
          <ul class="hidden md:flex items-center gap-6 text-white mr-4">
            <!-- Search -->
            <li class="relative">
              <div class="flex items-center bg-green-700/50 rounded-lg px-3 py-2">
                <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-white placeholder-gray-300 w-40">
                <i class="fas fa-search ml-2"></i>
              </div>
            </li>
            
            <!-- Cart -->
            <li>
              <a href="#" class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                <i class="fas fa-shopping-cart text-xl"></i>
                <span class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 flex items-center justify-center rounded-full">0</span>
              </a>
            </li>

            <!-- Notifications -->
            <li>
              <a href="#" class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 flex items-center justify-center rounded-full">3</span>
              </a>
            </li>

            <!-- User Section -->
            <li class="relative">
              <?php if($this->session->userdata('logged_in')): ?>
                <div class="flex items-center gap-3 bg-green-700/50 px-4 py-2 rounded-lg">
                  <span class="text-sm font-medium"><?= $this->session->userdata('nama') ?></span>
                  <a href="#" onclick="handleLogout(event)" class="text-white hover:text-red-300 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                  </a>
                </div>
              <?php else: ?>
                <a href="<?= base_url('auth') ?>" class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                  <span>Login</span>
                </a>
              <?php endif; ?>
            </li>
          </ul>

          <!-- Mobile Icons -->
          <ul class="flex md:hidden gap-4 items-center mr-2">
            <li>
              <a href="#" class="text-white">
                <i class="fas fa-search text-xl"></i>
              </a>
            </li>
            <li>
              <a href="#" class="text-white">
                <i class="fas fa-shopping-cart text-xl"></i>
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

    <!-- Logout Modal -->
    <div id="logoutModal" class="fixed inset-0 bg-black/30 backdrop-blur-[2px] hidden items-center justify-center z-[100]">
        <div class="bg-white/95 rounded-2xl p-8 flex flex-col items-center gap-5 shadow-lg transform scale-95 opacity-0 transition-all duration-300 max-w-xs w-11/12">
            <div class="relative w-24 h-24">
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-leaf text-5xl text-green-600 animate-pulse"></i>
                </div>
                <div class="absolute inset-0 border-4 border-dashed border-green-200 rounded-full animate-spin" style="animation-duration: 3s"></div>
            </div>
            <div class="text-center space-y-1">
                <h2 class="text-xl font-medium text-green-800">See you soon!</h2>
                <p class="text-green-600/80 text-sm">Growing memories with HijauLoka</p>
            </div>
            <div class="flex items-center gap-1">
                <i class="fas fa-seedling text-green-500 text-xs animate-bounce"></i>
                <i class="fas fa-seedling text-green-600 text-sm animate-bounce" style="animation-delay: 0.2s"></i>
                <i class="fas fa-seedling text-green-700 text-xs animate-bounce" style="animation-delay: 0.4s"></i>
            </div>
        </div>
    </div>

    <script>
    function handleLogout(e) {
        e.preventDefault();
        const modal = document.getElementById('logoutModal');
        const modalContent = modal.querySelector('div[class*="bg-white"]');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 50);

        setTimeout(() => {
            window.location.href = '<?= base_url('auth/logout') ?>';
        }, 1500);
    }
    </script>

    <!-- Existing Scripts -->
    <script src="<?= base_url('assets/') ;?>js/index.js"></script>
    <script src="<?= base_url('assets/') ;?>js/slide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Memuat ulang skrip JS jika diperlukan -->
    <script src="<?= base_url('assets/') ;?>js/index.js"></script>
    <script src="<?= base_url('assets/') ;?>js/slide.js"></script>
  </body>
</html>
