<?php $this->load->view('templates/header'); ?>

<div class="container mx-auto px-4 mt-28">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-green-800">Profile</h1>
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                Edit Profile
            </button>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Profile Info -->
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Name</label>
                            <p class="text-gray-800"><?= $user['nama'] ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-800"><?= $user['email'] ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-800"><?= $user['no_telp'] ?? 'Not set' ?></p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Address</h2>
                    <p class="text-gray-800"><?= $user['alamat'] ?? 'No address set' ?></p>
                </div>
            </div>

            <!-- Order History -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Orders</h2>
                <div class="space-y-4">
                    <p class="text-gray-600">No recent orders</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>