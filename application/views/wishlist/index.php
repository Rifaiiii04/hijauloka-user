<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-green-800">My Wishlist</h1>
    
    <?php if (empty($wishlist)): ?>
        <div class="text-center py-8">
            <p class="text-gray-500">Your wishlist is empty</p>
            <a href="<?= base_url() ?>" class="inline-block mt-4 text-green-600 hover:text-green-700">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($wishlist as $item): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow h-full flex flex-col">
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="https://source.unsplash.com/800x600/?plant,<?= urlencode($item['name']) ?>" 
                             alt="<?= $item['name'] ?>" 
                             class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div>
                            <h3 class="text-base sm:text-xl font-semibold mb-2 line-clamp-1"><?= $item['name'] ?></h3>
                            <?php if (!empty($item['categories'])): ?>
                                <div class="flex flex-wrap gap-1 sm:gap-2 mb-3">
                                    <?php foreach ($item['categories'] as $category): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full"><?= $category ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-auto">
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <?php 
                                    $rating = floatval($item['rating'] ?? 0);
                                    for ($i = 1; $i <= 5; $i++) : ?>
                                        <?php if ($i <= $rating) : ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif ($i - 0.5 <= $rating) : ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else : ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-gray-500 text-xs ml-1">(<?= number_format($rating, 1) ?>)</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm sm:text-lg font-bold">Rp<?= number_format(floatval($item['price'] ?? 0), 0, ',', '.') ?></span>
                                <div class="flex gap-2">
                                    <button onclick="removeFromWishlist(<?= $item['id'] ?? $item['id_produk'] ?? 0 ?>)" 
                                            class="bg-red-100 text-red-600 p-2 sm:p-2.5 rounded-md hover:bg-red-200 transition-colors">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button class="bg-green-600 text-white p-2 sm:p-2.5 rounded-md hover:bg-green-700 transition-colors">
                                        <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function removeFromWishlist(productId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        window.location.href = '<?= base_url('wishlist/remove/') ?>' + productId;
    }
}
</script>