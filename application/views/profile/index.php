<?php $this->load->view('templates/header'); ?>

<div class="container mx-auto px-4 mt-28">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-4 sm:p-6 md:p-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-green-800 mb-4 sm:mb-0">Profile</h1>
            <button class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                Edit Profile
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
            <!-- Profile Info -->
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                    <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Name</label>
                            <p class="text-gray-800 break-words"><?= $user['nama'] ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-800 break-words"><?= $user['email'] ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-800"><?= $user['no_telp'] ?? 'Not set' ?></p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Address</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-800 break-words"><?= $user['alamat'] ?? 'No address set' ?></p>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Recent Orders</h2>
                <div class="bg-gray-50 p-4 rounded-lg min-h-[200px]">
                    <p class="text-gray-600">No recent orders</p>
                </div>
            </div>
        </div>
    </div>
</div>