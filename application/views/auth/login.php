<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - HijauLoka</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body class="bg-gradient-to-br from-green-50 to-gray-100 min-h-screen flex items-center justify-center py-8 px-4">
    <!-- Notifications -->
    <?php if($this->session->flashdata('success')): ?>
    <div id="notification" class="fixed top-4 right-4 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg z-50 flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <p><?= $this->session->flashdata('success') ?></p>
    </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
    <div id="notification" class="fixed top-4 right-4 bg-red-600 text-white px-5 py-3 rounded-lg shadow-lg z-50 flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <p><?= $this->session->flashdata('error') ?></p>
    </div>
    <?php endif; ?>

    <!-- Back Button -->
    <a href="<?= base_url('home') ?>" class="fixed top-4 left-4 text-gray-700 hover:text-green-600 bg-white/80 backdrop-blur-sm py-2 px-4 rounded-full shadow-sm transition-all hover:shadow">
        <i class="fas fa-arrow-left mr-1"></i> Back to Home
    </a>

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 border border-gray-100">
        <div class="text-center mb-8">
            <img src="<?= base_url('assets/')?>img/hijauloka.png" alt="HijauLoka" class="w-24 h-24 mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Welcome Back</h2>
            <div class="w-16 h-1 bg-green-500 mx-auto mt-2 rounded-full"></div>
        </div>

        <form id="loginForm" action="<?= base_url('auth/login') ?>" method="post" class="space-y-5">
            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Enter your email">
                </div>
            </div>

            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Enter your password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-green-600 hover:underline">Forgot password?</a>
            </div>

            <button type="submit" 
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-300 shadow-md hover:shadow-lg font-medium mt-2">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="<?= base_url('auth/register') ?>" class="text-green-600 font-medium hover:underline">Sign up</a>
            </p>
        </div>

        <!-- OR Divider -->
        <div class="flex items-center my-6">
            <hr class="flex-grow border-gray-200">
            <span class="mx-4 text-sm text-gray-500">or continue with</span>
            <hr class="flex-grow border-gray-200">
        </div>

        <!-- Social Login Buttons -->
        <div class="flex justify-center space-x-4">
            <button class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                <img src="<?= base_url('assets/img/google.png') ?>" class="w-5 h-5" alt="Google">
                <span class="text-sm font-medium">Google</span>
            </button>

            <button class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                <img src="<?= base_url('assets/img/fb.png') ?>" class="w-5 h-5" alt="Facebook">
                <span class="text-sm font-medium">Facebook</span>
            </button>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loader" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-xl flex items-center gap-3">
            <div class="animate-spin rounded-full h-7 w-7 border-4 border-gray-200 border-t-green-600"></div>
            <p class="text-gray-700 font-medium">Signing you in...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notification handling
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    notification.style.transition = 'opacity 0.5s, transform 0.5s';
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000);
            }

            // Form submission and loader
            const loginForm = document.getElementById('loginForm');
            const loader = document.getElementById('loader');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                loader.classList.remove('hidden');
                loader.classList.add('flex');
                
                setTimeout(() => {
                    this.submit();
                }, 1500);
            });
        });
    </script>
</body>
</html>
