<div class="container mx-auto px-4 py-20">
    <div class="text-center relative mb-12">
        <h1 class="text-4xl font-bold text-green-800 mt-10 mb-6">My Wishlist</h1>
        <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
    </div>
    
    <?php if (empty($wishlist)): ?>
        <div class="text-center py-8">
            <p class="text-gray-500">Your wishlist is empty</p>
            <a href="<?= base_url() ?>" class="inline-block mt-4 text-green-600 hover:text-green-700">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($wishlist as $item): ?>
                <?php 
                if (!empty($item['gambar'])) {
                    $gambarArr = explode(',', $item['gambar']);
                    $gambar = trim($gambarArr[0]);
                } else {
                    $gambar = 'default.jpg';
                }
                ?>
                <div class="bg-white rounded-lg overflow-hidden shadow h-full flex flex-col">
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                             alt="<?= $item['nama_product']; ?>" 
                             class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div>
                            <h3 class="text-base sm:text-xl font-semibold mb-2 line-clamp-1"><?= $item['nama_product']; ?></h3>
                            <div class="flex flex-wrap gap-1 sm:gap-2 mb-3">
                                <?php
                                $this->db->select('c.nama_kategori');
                                $this->db->from('product_category pc');
                                $this->db->join('category c', 'c.id_kategori = pc.id_kategori');
                                $this->db->where('pc.id_product', $item['id_product']);
                                $product_categories = $this->db->get()->result_array();
                                
                                foreach ($product_categories as $cat) : ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full"><?= $cat['nama_kategori'] ?></span>
                                <?php endforeach; ?>
                            </div>
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
                                <span class="text-sm sm:text-lg font-bold">Rp<?= number_format($item['harga'], 0, ',', '.'); ?></span>
                                <div class="flex gap-2">
                                    <!-- Add this style section at the top of the file -->
                                    <style>
                                        @keyframes heartbeat-out {
                                            0% { transform: scale(1); }
                                            50% { transform: scale(0.7); }
                                            100% { transform: scale(1); }
                                        }
                                        
                                        .animate-heartbeat-out {
                                            animation: heartbeat-out 0.5s ease-in-out;
                                        }
                                        
                                        .card-fade-out {
                                            animation: fadeOut 0.5s ease-in-out forwards;
                                        }
                                        
                                        @keyframes fadeOut {
                                            0% { opacity: 1; transform: scale(1); }
                                            100% { opacity: 0; transform: scale(0.8); }
                                        }
                                    </style>
                                    
                                    <!-- Replace the existing script at the bottom with this improved version -->
                                    <script>
                                    function removeFromWishlist(productId, cardElement) {
                                        // Get the parent card element
                                        const card = cardElement.closest('.bg-white');
                                        
                                        // Add animation to the heart icon
                                        const icon = cardElement.querySelector('i');
                                        icon.classList.add('animate-heartbeat-out');
                                        
                                        // After a short delay, fade out the entire card
                                        setTimeout(() => {
                                            card.classList.add('card-fade-out');
                                            
                                            // After animation completes, send request to server
                                            setTimeout(() => {
                                                fetch('<?= base_url('wishlist/toggle/') ?>' + productId, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    },
                                                    credentials: 'same-origin'
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    // Remove the card from DOM
                                                    card.remove();
                                                    
                                                    // If this was the last item, show empty wishlist message
                                                    const remainingCards = document.querySelectorAll('.grid > .bg-white');
                                                    if (remainingCards.length === 0) {
                                                        const grid = document.querySelector('.grid');
                                                        grid.innerHTML = `
                                                            <div class="text-center py-8 col-span-full">
                                                                <p class="text-gray-500">Your wishlist is empty</p>
                                                                <a href="<?= base_url() ?>" class="inline-block mt-4 text-green-600 hover:text-green-700">Continue Shopping</a>
                                                            </div>
                                                        `;
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    // If there's an error, reload the page
                                                    window.location.reload();
                                                });
                                            }, 500);
                                        }, 200);
                                    }
                                    </script>
                                    
                                    <!-- Update the button in the card to use the new function -->
                                    <button onclick="removeFromWishlist(<?= $item['id_product'] ?>, this)" 
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