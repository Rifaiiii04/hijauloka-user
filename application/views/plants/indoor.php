<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-green-800">Indoor Plants</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($plants as $item): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
                <div class="relative">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="w-full h-48 object-cover">
                    <button class="absolute top-3 right-3 text-red-500 hover:text-red-600 focus:outline-none bg-white bg-opacity-80 rounded-full p-2">
                        <i class="fas fa-heart text-lg"></i>
                    </button>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <h2 class="text-base font-semibold mb-2 text-gray-800"><?= $item['name'] ?></h2>
                    <p class="text-green-700 font-bold mb-4"><?= $item['price'] ?></p>
                    <button class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded flex items-center gap-2 justify-center mt-auto">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>