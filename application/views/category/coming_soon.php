<div class="container mx-auto px-4 py-16 mt-12">
    <div class="max-w-2xl mx-auto text-center">
        <div class="mb-8">
            <i class="fas fa-seedling text-8xl text-green-500 mb-6 animate-bounce"></i>
            <h1 class="text-4xl font-bold text-green-800 mb-4">Coming Soon!</h1>
            <p class="text-xl text-gray-600 mb-8">
                <?php if (isset($category) && $category === 'seeds'): ?>
                    Kategori benih tanaman akan segera hadir. Kami sedang menyiapkan koleksi benih berkualitas untuk Anda.
                <?php else: ?>
                    Kategori pot tanaman akan segera hadir. Kami sedang menyiapkan koleksi pot cantik untuk tanaman Anda.
                <?php endif; ?>
            </p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-green-700 mb-4">Dapatkan Notifikasi</h2>
            <p class="text-gray-600 mb-6">Beritahu kami email Anda untuk mendapatkan pemberitahuan ketika kategori ini sudah tersedia.</p>
            <form class="flex gap-4 max-w-md mx-auto">
                <input type="email" placeholder="Masukkan email Anda" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Notifikasi
                </button>
            </form>
        </div>

        <div class="flex justify-center gap-6">
            <a href="<?= base_url('category/plants') ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-leaf"></i>
                <span>Lihat Tanaman Hias</span>
            </a>
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-home"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</div>
<style>
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
}
</style>
