<nav class="bg-green-800 hidden md:flex items-center justify-between p-4 fixed top-0 w-screen z-50 shadow-lg">
  <!-- Left Section with toggle and logo -->
  <div class="flex items-center">
    <div class="logo flex md:flex items-center">
      <a href="<?= base_url() ?>" class="flex items-center gap-2 md:gap-3">
        <img src="<?= base_url('assets/img/logo1.png') ?>" alt="Logo" class="w-10 md:w-16 transition-all duration-300">
        <span class="text-white font-semibold text-lg md:text-xl">HijauLoka</span>
      </a>
    </div>
  </div>

  <!-- Keep existing desktop menu and right section unchanged -->
  <div class="hidden md:flex flex-1 justify-center">
    <!-- For desktop menu, replace the Collection list item with: -->
    <ul class="flex gap-12 text-base text-white font-medium">
      <li>
        <a href="<?= base_url('home') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </a>
      </li>
      <li>
        <a href="<?= base_url('plants/index') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
          <i class="fas fa-leaf"></i>
          <span>Collection</span>
        </a>
      </li>
      <li>
        <a href="<?= base_url('popular') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
          <i class="fas fa-star"></i>
          <span>Popular</span>
        </a>
      </li>
      <!-- Desktop menu wishlist link -->
      <li>
          <a href="<?= $this->session->userdata('logged_in') ? base_url('wishlist') : '#' ?>" 
             onclick="<?= !$this->session->userdata('logged_in') ? 'document.getElementById(\'loginPrompt\').classList.remove(\'hidden\'); return false;' : '' ?>" 
             class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
              <i class="fas fa-heart"></i>
              <span>Wishlist</span>
          </a>
      </li>
    </ul>
  </div>

  <!-- Right Section -->
  <div class="flex items-center">
    <!-- Desktop Icons -->
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
            <div class="flex items-center gap-2 cursor-pointer">
              <span class="text-sm font-medium"><?= $this->session->userdata('nama') ?></span>
              <a href="<?= base_url('profile') ?>" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-user"></i>
              </a>
              <a href="#" onclick="handleLogout(event)" class="text-white hover:text-red-300 transition-colors">
                <i class="fas fa-sign-out-alt"></i>
              </a>
            </div>
          </div>
        <?php else: ?>
          <a href="<?= base_url('auth') ?>" class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
            <span>Login</span>
          </a>
        <?php endif; ?>
      </li>
    </ul>

    <!-- Mobile Icons -->
    <ul class="flex md:hidden items-center gap-3">
      <li>
        <a href="#" class="text-white p-2">
          <i class="fas fa-search text-lg"></i>
        </a>
      </li>
      <li>
        <a href="#" class="text-white relative p-2">
          <i class="fas fa-shopping-cart text-lg"></i>
          <span class="absolute -top-1 -right-1 bg-red-500 text-xs w-4 h-4 flex items-center justify-center rounded-full">0</span>
        </a>
      </li>
      <!-- Removed Profile Icon -->
      <?php if (!$this->session->userdata('logged_in')): ?>
        <li>
          <a href="<?= base_url('auth') ?>" class="text-white bg-green-700/50 px-3 py-1.5 rounded-lg text-sm">
            Login
          </a>
        </li>
      <?php endif; ?>
    </ul>
</nav>

<!-- Mobile Navigation Header -->
<nav class="md:hidden bg-green-800 flex items-center justify-between px-3 py-2 fixed top-0 w-screen z-50 shadow-lg">
  <div class="flex items-center gap-2">
    <img src="<?= base_url('assets/img/logo1.png') ?>" alt="Logo" class="w-7 h-7">
    <div class="flex items-center bg-green-700/50 rounded-lg px-2.5 py-1.5">
      <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-white placeholder-gray-300 w-28 text-sm">
      <i class="fas fa-search text-white ml-1.5 text-sm"></i>
    </div>
  </div>

  <ul class="flex items-center gap-3">
    <?php if ($this->session->userdata('logged_in')): ?>
      <li class="relative">
        <a href="#" class="text-white relative p-1.5 hover:bg-green-700/50 rounded-lg transition-colors">
          <i class="fas fa-shopping-cart text-base"></i>
          <span class="absolute -top-1 -right-1 bg-red-500 text-[10px] w-4 h-4 flex items-center justify-center rounded-full">2</span>
        </a>
      </li>
      <li class="relative">
        <a href="#" class="text-white relative p-1.5 hover:bg-green-700/50 rounded-lg transition-colors">
          <i class="fas fa-bell text-base"></i>
          <span class="absolute -top-1 -right-1 bg-red-500 text-[10px] w-4 h-4 flex items-center justify-center rounded-full">7</span>
        </a>
      </li>
      <li class="relative">
        <a href="<?= base_url('wishlist/index') ?>" class="text-white relative p-1.5 hover:bg-green-700/50 rounded-lg transition-colors">
          <i class="fas fa-heart text-base"></i>
        </a>
      </li>
    <?php else: ?>
      <li>
        <a href="<?= base_url('auth') ?>" class="text-white bg-green-700/50 px-3 py-1.5 rounded-lg text-sm">
          Login
        </a>
      </li>
    <?php endif; ?>
  </ul>
</nav>

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-green-800 shadow-lg z-50">
  <ul class="grid grid-cols-4 gap-1 p-1">
    <!-- Removed Wishlist from Bottom Navigation -->
    <li>
      <a href="<?= base_url('home') ?>" class="flex flex-col items-center justify-center py-2 text-white opacity-90 hover:opacity-100">
        <i class="fas fa-home text-lg"></i>
        <span class="text-[10px] mt-0.5">Home</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('plants/index') ?>" class="flex flex-col items-center justify-center py-2 text-white opacity-90 hover:opacity-100">
        <i class="fas fa-leaf text-lg"></i>
        <span class="text-[10px] mt-0.5">Collection</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('popular') ?>" class="flex flex-col items-center justify-center py-2 text-white opacity-90 hover:opacity-100">
        <i class="fas fa-star text-lg"></i>
        <span class="text-[10px] mt-0.5">Popular</span>
      </a>
    </li>
    <li>
        <a href="<?= base_url('profile/index') ?>" class="flex flex-col items-center justify-center py-2 text-white opacity-90 hover:opacity-100">
            <i class="fas fa-user text-lg"></i>
            <span class="text-[10px] mt-0.5">Profile</span>
        </a>
    </li>
  </ul>
</nav>