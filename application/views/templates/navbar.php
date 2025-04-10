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
        <?php if ($this->session->userdata('logged_in')): ?>
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