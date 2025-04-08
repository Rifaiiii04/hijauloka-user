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
    <!-- Back to Home Button -->
    <a href="<?= base_url('home') ?>" class="fixed top-4 left-4 flex items-center gap-2 text-green-800 hover:text-green-600 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Home</span>
    </a>

    <div class="bg-white shadow-lg rounded-lg flex p-8 w-full max-w-4xl">
        <!-- Left Section: Plant Image and Title -->
        <div class="w-1/2 flex flex-col items-center justify-center">
            <img src="img/hijauloka.jpg" alt="Plant Image" class="rounded-full w-80 h-80 object-cover">
        </div>

        <!-- Right Section: Login Form -->
        <div class="w-1/2 px-8">
            <h2 class="text-3xl font-bold text-center mb-6">Welcome to PlantNet!</h2>

            <form action="<?= base_url('auth/login') ?>" method="post">
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
           <div class="flex justify-center space-x-4">
            <button class="flex items-center space-x-2 border px-4 py-2 rounded-lg hover:bg-gray-100">
                <img src="img/google.png" class="w-6 h-6" alt="Google">
                <span class="text-sm">Google</span>
            </button>

            <button class="flex items-center space-x-2 border px-4 py-2 rounded-lg hover:bg-gray-100">
                <img src="img/fb.png" class="w-6 h-6" alt="Facebook">
                <span class="text-sm">Facebook</span>
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
