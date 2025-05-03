<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Add Snackbar -->
    <!-- Update the snackbar section -->
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
            <p class="text-sm whitespace-pre-line"><?= $this->session->flashdata('error') ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Back to Home Button -->
    <a href="<?= base_url() ?>" class="fixed top-4 left-4 flex items-center gap-2 text-green-800 hover:text-green-600 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Home</span>
    </a>

    <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row p-4 md:p-6 w-full max-w-4xl mx-4">
        <!-- Left Section -->
        <div class="hidden md:flex md:w-1/2 md:flex-col md:items-center md:justify-center">
            <img src="<?= base_url('assets/')?>img/hijauloka.pbg" alt="Plant Image" class="rounded-full w-52 h-52 object-cover">
        </div>

        <!-- Right Section -->
        <div class="w-full md:w-1/2 px-4 md:px-6">
            <!-- Mobile Logo -->
            <div class="flex md:hidden justify-center mb-8">
                <img src="<?= base_url('assets/')?>img/hijauloka.png" alt="Plant Image" class="rounded-full w-28 h-28 object-cover">
            </div>
            
            <h2 class="text-xl md:text-2xl font-bold text-center mb-6">Create Your Account</h2>

            <!-- Add loader HTML -->
            <div id="registerLoader" class="fixed inset-0 bg-white/95 backdrop-blur-sm hidden items-center justify-center z-50">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative w-32 h-32">
                        <div class="loader">
                            <div class="absolute top-0 left-1/2 w-5 h-5 bg-green-800 rounded-full"></div>
                            <div class="text-4xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">🌱</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-green-800">Creating Your Account</h3>
                        <p class="text-green-600/80">Please wait a moment...</p>
                    </div>
                </div>
            </div>

            <style>
                .loader {
                    position: relative;
                    width: 120px;
                    height: 120px;
                }

                .loader div:first-child {
                    animation: grow 1s ease-in-out infinite;
                }

                @keyframes grow {
                    0% {
                        transform: translateX(-50%) scale(0);
                        opacity: 0;
                    }
                    50% {
                        transform: translateX(-50%) scale(1);
                        opacity: 1;
                    }
                    100% {
                        transform: translateX(-50%) scale(0);
                        opacity: 0;
                    }
                }
            </style>

            <!-- Update the form to include an ID and add the loader script -->
            <form id="registerForm" action="<?= base_url('auth/register') ?>" method="post" class="space-y-3">

            <!-- Add this script before closing body tag -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const registerForm = document.getElementById('registerForm');
                    const loader = document.getElementById('registerLoader');

                    registerForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        loader.classList.remove('hidden');
                        loader.classList.add('flex');
                        
                        setTimeout(() => {
                            this.submit();
                        }, 2000);
                    });
                });
            </script>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Name</label>
                    <input type="text" name="nama" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="Enter your name">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="Enter your email">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="Create a password">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm mb-1">Address</label>
                    <textarea name="alamat" required
                        class="w-full px-4 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 h-16"
                        placeholder="Enter your address"></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm mb-1">Phone Number</label>
                    <input type="text" name="no_tlp" required
                        class="w-full px-4 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="Enter your phone number">
                </div>

                <button type="submit" 
                    class="w-full bg-green-800 text-white py-2.5 rounded-lg hover:bg-green-700 transition duration-200 font-semibold mt-6">
                    Create Account
                </button>
            </form>

            <p class="text-center mt-6 mb-6 text-sm">
                Already have an account? 
                <a href="<?= base_url('auth') ?>" class="text-green-600 font-semibold hover:underline">Sign in</a>
            </p>

            <div class="flex items-center mb-6">
                <hr class="flex-grow border-gray-300">
                <span class="mx-4 text-sm text-gray-500">or continue with</span>
                <hr class="flex-grow border-gray-300">
            </div>

            <div class="flex justify-center space-x-3 md:space-x-4">
                <button class="flex items-center space-x-2 border px-4 md:px-6 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                    <img src="<?= base_url('assets/img/google.png') ?>" class="w-5 h-5" alt="Google">
                    <span class="text-sm">Google</span>
                </button>

                <button class="flex items-center space-x-2 border px-4 md:px-6 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                    <img src="<?= base_url('assets/img/fb.png') ?>" class="w-5 h-5" alt="Facebook">
                    <span class="text-sm">Facebook</span>
                </button>
            </div>
        </div>
    </div>
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
        });
    </script>
</body>
</html>