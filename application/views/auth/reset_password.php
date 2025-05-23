<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password - HijauLoka</title>
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
    <a href="<?= base_url('auth') ?>" class="fixed top-4 left-4 text-gray-700 hover:text-green-600 bg-white/80 backdrop-blur-sm py-2 px-4 rounded-full shadow-sm transition-all hover:shadow">
        <i class="fas fa-arrow-left mr-1"></i> Back to Login
    </a>

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 border border-gray-100">
        <div class="text-center mb-8">
            <img src="<?= base_url('assets/')?>img/HijauLoka.png" alt="HijauLoka" class="w-24 h-24 mx-auto rounded-full">
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Reset Password</h2>
            <div class="w-16 h-1 bg-green-500 mx-auto mt-2 rounded-full"></div>
            <p class="text-gray-600 mt-3">Create a new password for your account</p>
        </div>

        <form id="resetPasswordForm" action="<?= base_url('auth/update_password') ?>" method="post" class="space-y-5">
            <input type="hidden" name="user_id" value="<?= isset($user_id) ? $user_id : '' ?>">
            <input type="hidden" name="email" value="<?= isset($email) ? $email : '' ?>">
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-blue-500">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Resetting password for: <strong><?= isset($email) ? $email : 'Unknown' ?></strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="new_password" id="new_password" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Create a new password">
                </div>
            </div>

            <div class="relative">
                <label class="block text-gray-700 text-sm font-medium mb-1.5">Confirm New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="confirm_password" id="confirm_password" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                        placeholder="Confirm your new password">
                </div>
                <p id="password_match_error" class="text-red-500 text-xs mt-1 hidden">Passwords do not match</p>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-300 shadow-md hover:shadow-lg font-medium mt-2">
                Reset Password
            </button>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div id="loader" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-xl flex items-center gap-3">
            <div class="animate-spin rounded-full h-7 w-7 border-4 border-gray-200 border-t-green-600"></div>
            <p class="text-gray-700 font-medium">Updating your password...</p>
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

            // Password matching validation
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            const passwordMatchError = document.getElementById('password_match_error');
            const submitBtn = document.getElementById('submitBtn');
            
            function validatePasswords() {
                if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
                    passwordMatchError.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                } else {
                    passwordMatchError.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            }
            
            newPassword.addEventListener('input', validatePasswords);
            confirmPassword.addEventListener('input', validatePasswords);

            // Form submission and loader
            const resetPasswordForm = document.getElementById('resetPasswordForm');
            const loader = document.getElementById('loader');

            resetPasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Final validation before submission
                if (newPassword.value !== confirmPassword.value) {
                    passwordMatchError.classList.remove('hidden');
                    return;
                }
                
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