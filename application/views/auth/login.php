<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Add Snackbar Notifications -->
    <?php if($this->session->flashdata('success')): ?>
    <div id="snackbar" class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-0 opacity-100 transition-all duration-500 flex items-center gap-2 z-50">
        <i class="fas fa-check-circle text-xl"></i>
        <div>
            <h4 class="font-semibold">Success!</h4>
            <p class="text-sm"><?= $this->session->flashdata('success') ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
    <div id="snackbar" class="fixed bottom-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-0 opacity-100 transition-all duration-500 flex items-center gap-2 z-50">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <div>
            <h4 class="font-semibold">Error!</h4>
            <p class="text-sm"><?= $this->session->flashdata('error') ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Back to Home Button -->
    <a href="<?= base_url('home') ?>" class="fixed top-4 left-4 flex items-center gap-2 text-green-800 hover:text-green-600 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Home</span>
    </a>

    <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row p-4 md:p-8 w-full max-w-4xl mx-4">
        <!-- Left Section: Plant Image and Title -->
        <div class="hidden md:w-1/2 md:flex md:flex-col md:items-center md:justify-center">
            <img src="<?= base_url('assets/')?>img/hijauloka.jpg" alt="Plant Image" class="rounded-full w-80 h-80 object-cover">
        </div>

        <!-- Right Section: Login Form -->
        <div class="w-full md:w-1/2 px-4 md:px-8">
            <!-- Mobile Logo -->
            <div class="flex md:hidden justify-center mb-6">
                <img src="<?= base_url('assets/')?>img/hijauloka.jpg" alt="Plant Image" class="rounded-full w-32 h-32 object-cover">
            </div>
            
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-6">Welcome to PlantNet!</h2>

            <!-- Add loader HTML after the snackbar notifications -->
            <div id="loader" class="fixed inset-0 bg-black/30 backdrop-blur-[2px] hidden items-center justify-center z-50">
                <div class="bg-white/95 rounded-2xl p-8 flex flex-col items-center gap-4 shadow-lg transform scale-95 opacity-0 transition-all duration-300 max-w-xs w-11/12">
                    <div class="flex items-center gap-2 mb-2">
                        <img src="<?= base_url('assets/img/logo1.png') ?>" alt="Logo" class="w-8 h-8">
                    </div>
                    <div class="relative w-20 h-20">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-leaf text-4xl text-green-600 animate-pulse"></i>
                        </div>
                        <div class="absolute inset-0 border-4 border-dashed border-green-200 rounded-full animate-spin" style="animation-duration: 3s"></div>
                    </div>
                    <div class="text-center">
                        <h2 class="text-lg font-medium text-green-800">Selamat Datang!</h2>
                        <p class="text-green-600/80 text-sm">Sedang masuk ke akun Anda...</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-seedling text-green-500 text-xs animate-bounce"></i>
                        <i class="fas fa-seedling text-green-600 text-sm animate-bounce" style="animation-delay: 0.2s"></i>
                        <i class="fas fa-seedling text-green-700 text-xs animate-bounce" style="animation-delay: 0.4s"></i>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const loginForm = document.getElementById('loginForm');
                    const loader = document.getElementById('loader');

                    loginForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        loader.classList.remove('hidden');
                        loader.classList.add('flex');
                        
                        const modalContent = loader.querySelector('div[class*="bg-white"]');
                        setTimeout(() => {
                            modalContent.style.opacity = '1';
                            modalContent.style.transform = 'scale(1)';
                        }, 50);

                        setTimeout(() => {
                            this.submit();
                        }, 2000);
                    });
                });
            </script>

            <!-- Update the form with an ID -->
            <form action="<?= base_url('auth/login') ?>" method="post" id="loginForm">
                <!-- Email Input -->
                <div class="mb-4">
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="Email" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        required
                    >
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Password" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                            required
                        >
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" class="mr-2">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-green-600 hover:underline">Forgot password?</a>
                </div>

                <!-- Sign In Button -->
                <button 
                    type="submit" 
                    class="w-full  text-white py-2 rounded-lg hover:bg-green-700 bg-green-800 transition duration-200">
                    Sign In
                </button>
            </form>

            <!-- Sign Up Link -->
            <p class="text-center mt-6 text-sm">
                Don’t have an account? 
                <a href="<?php echo base_url('auth/register') ?>" class="text-green-600 font-semibold hover:underline">Sign up</a>
            </p>

            <!-- OR Divider -->
            <div class="flex items-center my-6">
                <hr class="flex-grow border-gray-300">
                <span class="mx-2 text-sm text-gray-500">or continue with</span>
                <hr class="flex-grow border-gray-300">
            </div>

           <!-- Social Login Buttons -->
           <div class="flex justify-center space-x-2 md:space-x-4">
            <button class="flex items-center space-x-1 md:space-x-2 border px-3 md:px-4 py-2 rounded-lg hover:bg-gray-100">
                <img src="<?= base_url('assets/')?>img/google.png" class="w-5 md:w-6 h-5 md:h-6" alt="Google">
                <span class="text-xs md:text-sm">Google</span>
            </button>

            <button class="flex items-center space-x-1 md:space-x-2 border px-3 md:px-4 py-2 rounded-lg hover:bg-gray-100">
                <img src="<?= base_url('assets/')?>img/fb.png" class="w-5 md:w-6 h-5 md:h-6" alt="Facebook">
                <span class="text-xs md:text-sm">Facebook</span>
            </button>
        </div>
        </div>
    </div>

</body>
</html>

<!-- Add this right before the form -->
<?php if($this->session->flashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

    <!-- Add this script before closing body tag -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const snackbar = document.getElementById('snackbar');
            if (snackbar) {
                setTimeout(() => {
                    snackbar.style.opacity = '0';
                    snackbar.style.transform = 'translateY(100%)';
                    setTimeout(() => {
                        snackbar.remove();
                    }, 500);
                }, 3000);
            }

            // Add login form handling
            const loginForm = document.getElementById('loginForm');
            const loader = document.getElementById('loader');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                loader.classList.remove('hidden');
                loader.classList.add('flex');
                
                setTimeout(() => {
                    this.submit();
                }, 1500); // 1.5 seconds delay
            });
        });
</script>
