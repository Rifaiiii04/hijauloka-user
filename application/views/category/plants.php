<?php $this->load->view('templates/header'); ?>

<div class="container mx-auto px-4 py-8 mt-22">
    <!-- Category Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-green-800 mb-2">Kategori Tanaman</h1>
        <p class="text-gray-600">
            Temukan berbagai jenis kategori tanaman untuk kebutuhan Anda
        </p>
    </div>

    <!-- Categories Grid -->
    <div id="categoriesGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <?php
        // Get all categories from database
        $this->db->select('id_kategori, nama_kategori');
        $this->db->from('category');
        $this->db->order_by('nama_kategori', 'ASC');
        $query = $this->db->get();
        $categories = $query->result();
        
        // Display each category from database
        foreach ($categories as $category) {
            // Generate a consistent color based on category name
            $colorHash = substr(md5($category->nama_kategori), 0, 6);
            
            // Count products in this category - fixed query to properly count products
            $this->db->select('COUNT(*) as count');
            $this->db->from('product');
            $this->db->where('id_kategori', $category->id_kategori);
            $count_query = $this->db->get();
            $product_count = $count_query->row()->count;
        ?>
        <a href="<?= base_url('product/category/' . $category->id_kategori) ?>" class="category-card group">
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 text-center h-full border border-gray-100">
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-green-500 to-green-700" style="background-color: #<?= $colorHash ?>;">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-leaf text-white text-4xl opacity-50"></i>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-green-600 transition-colors duration-300"><?= htmlspecialchars($category->nama_kategori) ?></h3>
                    <p class="text-sm text-gray-500"><?= $product_count ?> produk</p>
                </div>
            </div>
        </a>
        <?php } ?>
        
        <?php if (empty($categories)): ?>
        <div class="col-span-full text-center py-12">
            <div class="bg-gray-50 rounded-lg p-8 inline-block">
                <i class="fas fa-leaf text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada kategori yang tersedia saat ini.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.category-card:hover h3 {
    color: #16a34a; /* green-600 */
    transition: color 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.category-card {
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--animation-order) * 0.1s);
    opacity: 0;
}

#categoriesGrid {
    counter-reset: card-counter;
}

.category-card {
    counter-increment: card-counter;
    --animation-order: counter(card-counter);
}

.no-results-message {
    animation: fadeIn 0.3s ease forwards;
}
</style>

<!-- Login Prompt Modal -->
<div id="loginPrompt" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl transform transition-all">
        <div class="text-center mb-6">
            <i class="fas fa-lock text-4xl text-green-600 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-900">Login Required</h3>
            <p class="text-gray-600 mt-2">Please login or create an account to add items to your wishlist</p>
        </div>
        <div class="space-y-3">
            <a href="<?= base_url('auth') ?>" 
               class="flex items-center justify-center gap-2 w-full bg-green-600 text-white py-3 rounded-lg text-center hover:bg-green-700 transition-all duration-300">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login to Your Account</span>
            </a>
            <a href="<?= base_url('auth/register') ?>" 
               class="flex items-center justify-center gap-2 w-full bg-gray-100 text-gray-700 py-3 rounded-lg text-center hover:bg-gray-200 transition-all duration-300">
                <i class="fas fa-user-plus"></i>
                <span>Create New Account</span>
            </a>
            <button onclick="closeLoginPrompt()" 
                    class="w-full text-gray-500 hover:text-gray-700 py-2 transition-colors duration-300">
                Maybe Later
            </button>
        </div>
    </div>
</div>

<script>
// Prevent multiple event listeners by removing any existing ones
const quickSearchInput = document.getElementById('quickSearch');
if (quickSearchInput) {
    const oldQuickSearch = quickSearchInput.cloneNode(true);
    quickSearchInput.parentNode.replaceChild(oldQuickSearch, quickSearchInput);

    // Add the event listener once
    document.getElementById('quickSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const categoryCards = document.querySelectorAll('.category-card');
        let visibleCount = 0;
        
        categoryCards.forEach((card, index) => {
            const categoryName = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p') ? card.querySelector('p').textContent.toLowerCase() : '';
            
            if (categoryName.includes(searchTerm) || description.includes(searchTerm)) {
                card.style.display = '';
                card.style.setProperty('--animation-order', visibleCount);
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show message if no categories match
        const noResultsMessage = document.querySelector('.no-results-message');
        
        if (visibleCount === 0 && searchTerm !== '') {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.className = 'col-span-full text-center py-8 no-results-message';
                message.innerHTML = '<div class="bg-gray-50 rounded-lg p-8 inline-block"><i class="fas fa-search text-gray-300 text-4xl mb-4"></i><p class="text-gray-500 text-lg">Tidak ada kategori yang sesuai dengan pencarian Anda.</p></div>';
                document.getElementById('categoriesGrid').appendChild(message);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    });
}

function closeLoginPrompt() {
    const modal = document.getElementById('loginPrompt');
    modal.classList.add('hidden');
}

// Use event delegation to avoid multiple event listeners
document.addEventListener('DOMContentLoaded', function() {
    const loginPrompt = document.getElementById('loginPrompt');
    if (loginPrompt) {
        loginPrompt.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginPrompt();
            }
        });
    }
});
</script>