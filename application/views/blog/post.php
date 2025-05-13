<div class="bg-gray-50 min-h-screen pt-24 pb-16 mt-32">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumbs -->
            <div class="flex items-center text-sm text-gray-500 mb-6">
                <a href="<?= base_url() ?>" class="hover:text-green-700 transition-colors">Beranda</a>
                <span class="mx-2">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
                <a href="<?= base_url('blog') ?>" class="hover:text-green-700 transition-colors">Blog</a>
                <span class="mx-2">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
                <a href="<?= base_url('blog/category/' . $post['category_slug']) ?>" class="hover:text-green-700 transition-colors"><?= $post['category_name'] ?></a>
                <span class="mx-2">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
                <span class="text-gray-700 font-medium truncate"><?= $post['title'] ?></span>
            </div>
            
            <!-- Post Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4"><?= $post['title'] ?></h1>
                
                <div class="flex flex-wrap items-center text-sm text-gray-500 gap-4 mb-6">
                    <span class="flex items-center">
                        <i class="fas fa-user-circle mr-1"></i>
                        <?= $post['author_name'] ?? 'Admin' ?>
                    </span>
                    <span class="flex items-center">
                        <i class="far fa-calendar-alt mr-1"></i>
                        <?= date('d M Y', strtotime($post['created_at'])) ?>
                    </span>
                    <span class="flex items-center">
                        <i class="far fa-eye mr-1"></i>
                        <?= $post['views'] ?> views
                    </span>
                    <a href="<?= base_url('blog/category/' . $post['category_slug']) ?>" class="flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-full hover:bg-green-100 transition-colors">
                        <i class="fas fa-folder-open mr-1"></i>
                        <?= $post['category_name'] ?>
                    </a>
                </div>
            </div>
            
            <!-- Featured Image - Improved with modern styling -->
            <?php if (!empty($post['featured_image'])): ?>
                <div class="mb-8 rounded-xl overflow-hidden shadow-lg relative">
                    <img src="<?= base_url('uploads/blog/' . $post['featured_image']) ?>" 
                         alt="<?= $post['title'] ?>" 
                         onerror="this.onerror=null; this.src='<?= base_url('assets/img/news1.png') ?>';"
                         class="w-full h-auto object-cover">
                    <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/70 to-transparent">
                        <div class="flex items-center gap-3">
                            <span class="bg-green-600 text-white text-xs px-3 py-1 rounded-full"><?= $post['category_name'] ?></span>
                            <span class="text-white/90 text-sm flex items-center">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <?= date('d M Y', strtotime($post['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Post Content -->
            <div class="bg-white rounded-xl p-6 md:p-10 shadow-md mb-10">
                <div class="prose prose-green max-w-none">
                    <?= $post['content'] ?>
                </div>
            </div>
            
            <!-- Share Buttons -->
            <div class="bg-white rounded-xl p-6 shadow-md mb-10">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Bagikan Artikel</h3>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url() ?>" target="_blank" class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= current_url() ?>&text=<?= $post['title'] ?>" target="_blank" class="flex items-center justify-center w-10 h-10 bg-blue-400 text-white rounded-full hover:bg-blue-500 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text=<?= $post['title'] ?>%20<?= current_url() ?>" target="_blank" class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="mailto:?subject=<?= $post['title'] ?>&body=<?= current_url() ?>" class="flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
            
            <!-- Related Posts - Improved with modern card design -->
            <?php if (!empty($related_posts)): ?>
                <div class="mb-10">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Artikel Terkait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($related_posts as $related): ?>
                            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group">
                                <a href="<?= base_url('blog/post/' . $related['slug']) ?>" class="block relative h-40 overflow-hidden">
                                    <img src="<?= !empty($related['featured_image']) ? base_url('uploads/blog/' . $related['featured_image']) : base_url('assets/img/news1.png') ?>" 
                                         alt="<?= $related['title'] ?>" 
                                         onerror="this.onerror=null; this.src='<?= base_url('assets/img/news1.png') ?>';"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <!-- Hover overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-medium border border-white/30 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                            Baca Artikel
                                        </span>
                                    </div>
                                </a>
                                <div class="p-4">
                                    <a href="<?= base_url('blog/post/' . $related['slug']) ?>" class="block">
                                        <h4 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-green-700 transition-colors line-clamp-2"><?= $related['title'] ?></h4>
                                    </a>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500"><?= date('d M Y', strtotime($related['created_at'])) ?></span>
                                        <a href="<?= base_url('blog/category/' . $related['category_slug']) ?>" class="text-green-700 hover:underline">
                                            <?= $related['category_name'] ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Back to Blog -->
            <div class="text-center">
                <a href="<?= base_url('blog') ?>" class="inline-flex items-center px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali ke Blog</span>
                </a>
            </div>
        </div>
    </div>
</div>