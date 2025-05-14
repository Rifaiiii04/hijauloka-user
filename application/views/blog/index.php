<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Blog Header with improved visual appeal -->
        <div class="text-center flex flex-col items-center justify-center max-w-3xl mx-auto  mt-22 p-4">
            <h1 class="text-4xl font-bold text-green-800 relative inline-block">
                Blog HijauLoka
            </h1>
            <!-- <p class="text-gray-600  w-96 text-center">Temukan tips perawatan tanaman, inspirasi dekorasi, dan panduan berkebun untuk membantu Anda merawat tanaman dengan baik</p> -->
        </div>
        
        <!-- Search Bar - Adding search functionality -->
        <div class="max-w-xl mx-auto">
            <form action="<?= base_url('blog/search') ?>" method="get" class="flex">
                <input type="text" name="q" placeholder="Cari artikel..." class="w-full px-4 py-3 rounded-l-lg border-2 border-green-100 focus:border-green-500 focus:outline-none transition-colors">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 rounded-r-lg transition-colors flex items-center justify-center">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <!-- Categories with improved scrolling for mobile -->
        <div class="mb-10 overflow-x-auto mt-10 pb-2 hide-scrollbar">
            <div class="flex gap-3 min-w-max justify-center">
                <a href="<?= base_url('blog') ?>" class="px-5 py-2.5 bg-green-700 text-white rounded-full text-sm font-medium hover:bg-green-800 transition-colors shadow-sm flex items-center">
                    <i class="fas fa-th-large mr-2"></i> Semua
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="<?= base_url('blog/category/' . $category['slug']) ?>" class="px-5 py-2.5 bg-white text-green-700 rounded-full text-sm font-medium hover:bg-green-50 transition-colors shadow-sm flex items-center">
                        <i class="fas fa-folder mr-2"></i> <?= $category['name'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if (!empty($posts)): ?>
            <!-- Blog Grid with improved card design -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="block relative h-52 overflow-hidden group">
                            <img src="<?= !empty($post['featured_image']) ? 'http://localhost/hijauloka/uploads/blog/' . $post['featured_image'] : base_url('assets/img/news1.png') ?>" 
                                 alt="<?= $post['title'] ?>" 
                                 onerror="this.onerror=null; this.src='<?= base_url('assets/img/news1.png') ?>';"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <!-- Modern overlay with gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <!-- Category badge overlay with improved styling -->
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 bg-green-600/90 text-white text-xs font-medium rounded-full backdrop-blur-sm shadow-sm">
                                    <?= $post['category_name'] ?>
                                </span>
                            </div>
                            <!-- Read more overlay that appears on hover -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium border border-white/30">
                                    Baca Artikel
                                </span>
                            </div>
                        </a>
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <span class="flex items-center">
                                    <i class="fas fa-user-circle mr-1"></i>
                                    <?= $post['author_name'] ?? 'Admin' ?>
                                </span>
                                <span class="mx-2">•</span>
                                <span class="flex items-center">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?= date('d M Y', strtotime($post['created_at'])) ?>
                                </span>
                            </div>
                            <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="block">
                                <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-green-700 transition-colors line-clamp-2"><?= $post['title'] ?></h3>
                            </a>
                            <p class="text-gray-600 mb-5 line-clamp-3">
                                <?= $post['excerpt'] ?? substr(strip_tags($post['content']), 0, 150) . '...' ?>
                            </p>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <i class="far fa-eye mr-1"></i>
                                    <span><?= $post['views'] ?> views</span>
                                </div>
                                <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center gap-1 transition-colors">
                                    Baca Selengkapnya <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Enhanced Pagination -->
            <div class="mt-12">
                <?= $pagination ?>
            </div>
            
        <?php else: ?>
            <!-- Improved Empty State -->
            <div class="bg-white p-3 rounded-xl  text-center max-w-2xl mx-auto shadow-md">
                <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-4xl text-green-300"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-700 mb-3">Belum Ada Postingan Blog</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Kami sedang menyiapkan konten menarik untuk Anda. Kunjungi kembali halaman ini dalam waktu dekat.</p>
                <a href="<?= base_url() ?>" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md">
                    <i class="fas fa-home mr-2"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add this CSS to hide scrollbars but keep functionality -->
<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>