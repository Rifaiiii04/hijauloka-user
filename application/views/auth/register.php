<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register - HijauLoka</title>
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
    <a href="<?= base_url() ?>" class="fixed top-4 left-4 text-gray-700 hover:text-green-600 bg-white/80 backdrop-blur-sm py-2 px-4 rounded-full shadow-sm transition-all hover:shadow">
        <i class="fas fa-arrow-left mr-1"></i> Back to Home
    </a>

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 border border-gray-100">
        <div class="text-center mb-8">
            <img src="<?= base_url('assets/')?>img/HijauLoka.png" alt="HijauLoka" class="w-24 h-24 mx-auto rounded-full">
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Join HijauLoka</h2>
            <div class="w-16 h-1 bg-green-500 mx-auto mt-2 rounded-full"></div>
        </div>

        <form id="registerForm" action="<?= base_url('auth/register') ?>" method="post" class="space-y-5">
            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Full Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                      
                    </span>
                    <input type="text" name="nama" required
                        class="w-full pl-10 pr-4 py-3 p-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Enter your name">
                </div>
            </div>

            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    
                    </span>
                    <input type="email" name="email" required
                        class="w-full pl-10 pr-4 py-3 p-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Enter your email">
                </div>
            </div>

            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                       
                    </span>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-4 py-3 p-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Create a password">
                </div>
            </div>

            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Phone Number</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                     
                    </span>
                    <input type="text" name="no_tlp" required
                        class="w-full pl-10 pr-4 py-3 p-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Enter your phone number">
                </div>
            </div>

            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Address</label>
                <div class="relative">
                    <span class="absolute top-3 left-0 flex items-start pl-3 text-gray-400">
                     
                    </span>
                    <textarea name="alamat" required
                        class="w-full pl-10 pr-4 py-3 p-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50 h-24"
                        placeholder="Enter your complete address"></textarea>
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-300 shadow-md hover:shadow-lg font-medium mt-2">
                Create Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="<?= base_url('auth') ?>" class="text-green-600 font-medium hover:underline">Sign in</a>
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="registerLoader" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-xl flex items-center gap-3">
            <div class="animate-spin rounded-full h-7 w-7 border-4 border-gray-200 border-t-green-600"></div>
            <p class="text-gray-700 font-medium">Creating your account...</p>
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
            const registerForm = document.getElementById('registerForm');
            const loader = document.getElementById('registerLoader');

            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                loader.classList.remove('hidden');
                loader.classList.add('flex');
                
                setTimeout(() => {
                    this.submit();
                }, 1000);
            });
        });
    </script>
</body>
</html>