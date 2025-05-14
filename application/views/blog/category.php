<div class="bg-gray-50 min-h-screen pt-24 pb-16 mt-18">
    <div class="container mx-auto px-4">
        <!-- Category Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-4xl font-bold text-green-800 mb-4">Kategori: <?= $category['name'] ?></h1>
            <p class="text-gray-600">Artikel dan tips seputar <?= $category['name'] ?> untuk membantu Anda merawat tanaman dengan baik</p>
        </div>
        
        <!-- Categories -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <a href="<?= base_url('blog') ?>" class="px-4 py-2 bg-white text-green-700 rounded-full text-sm font-medium hover:bg-green-50 transition-colors">
                Semua
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= base_url('blog/category/' . $cat['slug']) ?>" 
                   class="px-4 py-2 <?= ($cat['id'] == $category['id']) ? 'bg-green-700 text-white' : 'bg-white text-green-700' ?> rounded-full text-sm font-medium hover:<?= ($cat['id'] == $category['id']) ? 'bg-green-800' : 'bg-green-50' ?> transition-colors">
                    <?= $cat['name'] ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (!empty($posts)): ?>
            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
                        <a href="<?= base_url('blog/post/' . $post['slug']) ?>" class="block relative h-48 overflow-hidden">
                            <img src="<?= !empty($post['featured_image']) ? base_url('uploads/blog/' . $post['featured_image']) : base_url('assets/img/news1.png') ?>" 
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
                                <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-green-700 transition-colors line-clamp-2"><?= $post['title'] ?></h3>
                            </a>
                            <p class="text-gray-600 mb-4 line-clamp-3">
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
            
            <!-- Pagination -->
            <?= $pagination ?>
            
        <?php else: ?>
            <!-- No Posts Message -->
            <div class="bg-white rounded-xl p-12 text-center max-w-2xl mx-auto shadow-md">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-700 mb-3">Belum Ada Postingan di Kategori Ini</h3>
                <p class="text-gray-500 mb-6">Kami sedang menyiapkan konten menarik untuk kategori <?= $category['name'] ?>. Kunjungi kembali halaman ini dalam waktu dekat.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?= base_url('blog') ?>" class="inline-flex items-center px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-newspaper mr-2"></i>
                        <span>Lihat Semua Blog</span>
                    </a>
                    <a href="<?= base_url() ?>" class="inline-flex items-center px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>