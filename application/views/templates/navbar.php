<nav class="text-black w-[1000px] h-12 hidden md:flex items-center justify-between backdrop-blur-2xl rounded-full border-2 border-green-800 p-3 fixed top-3 left-1/2 -translate-x-1/2 z-50 shadow-lg mx-auto">
    <div class="flex items-center">
        <div class="logo flex md:flex items-center">
            <a href="<?= base_url() ?>" class="flex items-center gap-2 md:gap-3">
                <img src="<?= base_url('assets/img/logoicon.png') ?>" alt="" class="w-16 h-16">
                <!-- <span class="text-green-800 font-semibold text-base md:text-lg ml-8">HijauLoka</span> -->
            </a>
        </div>
    </div>

    <div class="hidden md:flex flex-1 justify-center">
        <ul class="flex gap-8 text-sm text-green-800 font-semibold">
            <!-- For desktop menu, replace the Collection list item with: -->
            <ul class="flex gap-12  text-green-800 text-xs font-semibold">
                <li>
                    <a href="<?= base_url('home') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/30 transition-all duration-300">
                        <i class="fas fa-home"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="<?= base_url('plants/index') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/30 transition-all duration-300">
                        <i class="fas fa-leaf"></i>
                        <span>Koleksi</span>
                    </a>
                </li> -->
                <li>
                    <a href="<?= base_url('popular') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/30 transition-all duration-300">
                        <i class="fas fa-shop"></i>
                        <span>Belanja</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->session->userdata('logged_in') ? base_url('wishlist') : '#' ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                        <i class="fas fa-heart"></i>
                        <span>Favorit</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Right Section -->
        <div class="flex items-center">
            <!-- Desktop Icons -->
            <ul class="hidden md:flex items-center gap-3 text-white mr-4">
                <!-- Cart -->
                <!-- For desktop cart icon -->
                <li>
                    <a href="<?= base_url('cart') ?>" class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                        <i class="fas fa-shopping-cart text-sm text-green-800"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                            <?= $this->cart_model->get_cart_count($this->session->userdata('id_user')) ?? '0' ?>
                        </span>
                    </a>
                </li>
                
                <!-- For mobile cart icon -->
    
                <!-- Notifications -->
                <li>
                    <a href="#" class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                        <i class="fas fa-bell text-sm text-green-800"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">3</span>
                    </a>
                </li>

                <!-- Mobile version -->
                
                <!-- User Section -->
                <li class="relative">
                    <?php if ($this->session->userdata('logged_in')): ?>
                        <div class="flex items-center gap-3">
                            <a href="<?= base_url('profile') ?>" class="flex items-center gap-2 px-4 py-2 rounded-lg text-green-800  hover:bg-green-700/50 transition-colors">
                                <i class="fas fa-user text-sm"></i>
                            </a>
                            <a href="#" onclick="handleLogout(event)" class="flex items-center gap-2 bg-red-500/50 px-4 py-2 rounded-lg text-green-800 hover:text-red-700 transition-colors">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?= base_url('auth') ?>" class="flex items-center gap-2 bg-white/10 px-4 py-2 text-green-800 rounded-lg hover:bg-green-700/50 transition-all duration-300">
                            <span>Masuk</span>
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
    
            <!-- Mobile Icons -->
            <ul class="flex md:hidden items-center gap-3 text-white">
                <li>
                    <a href="#" class="text-white relative p-2">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">0</span>
                    </a>
                </li>
                <?php if (!$this->session->userdata('logged_in')): ?>
                    <li>
                        <a href="<?= base_url('auth') ?>" class="text-black bg-green-700/50 px-3 py-1.5 rounded-lg text-sm">
                            Masuk
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <!-- Mobile Navigation Header -->
    <nav class="md:hidden bg-white w-full flex items-center bottom-5 rounded-b-full justify-between px-4 py-3 fixed  z-50">
        <div class="flex items-center gap-3">
        <img src="<?= base_url('assets/img/logoicon.png') ?>" alt="" class="w-10 h-10">
        </div>
    
        <ul class="flex items-center gap-4">
            <?php if ($this->session->userdata('logged_in')): ?>
                <li>
                    <a href="#" class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/30 transition-all duration-300">
                        <i class="fas fa-shopping-cart text-xl text-green-800"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 text-white h-5 flex items-center justify-center rounded-full">0</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/30 transition-all duration-300">
                        <i class="fas fa-bell text-xl text-green-800"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 flex text-white items-center justify-center rounded-full">3</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wishlist/index') ?>" class="relative flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-700/30 transition-all duration-300">
                        <i class="fas fa-heart text-xl text-green-800"></i>
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?= base_url('auth') ?>" class="flex items-center gap-2 bg-white/10 px-4 py-2 text-green-800 rounded-lg hover:bg-green-700/30 transition-all duration-300">
                        <span>Masuk</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 mb-10 left-0 right-0 text-center mx-auto bg-white/40 backdrop-blur-2xl border-2 border-green-800 rounded-full h-14  shadow-lg z-50 w-80">
        <div class="max-w-md mx-auto">
            <ul class="grid grid-cols-3 gap-1 p-2 text-center">
                <li>
                    <a href="<?= base_url('home') ?>" class="flex flex-col items-center justify-center py-2 text-green-800 hover:bg-green-700/30 rounded-lg transition-all duration-300">
                        <i class="fas fa-home text-lg"></i>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('popular') ?>" class="flex flex-col items-center justify-center py-2 text-green-800 hover:bg-green-700/30 rounded-lg transition-all duration-300">
                        <i class="fas fa-shop text-lg"></i>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('profile/index') ?>" class="flex flex-col items-center justify-center py-2 text-green-800 hover:bg-green-700/30 rounded-lg transition-all duration-300">
                        <i class="fas fa-user text-lg"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>