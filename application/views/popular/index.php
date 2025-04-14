<?php $this->load->view('templates/header'); ?>

<div class="mb-12 mt-28 text-center">
    <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
        Produk Populer
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
    </h1>
    <p class="text-gray-600 mt-3">Tanaman hias yang paling banyak diminati</p>
</div>

<main class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($produk_populer as $produk) : ?>
            <?php 
            if (!empty($produk['gambar'])) {
                $gambarArr = explode(',', $produk['gambar']);
                $gambar = trim($gambarArr[0]);
            } else {
                $gambar = 'default.jpg';
            }
            ?>
            <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="aspect-w-1 aspect-h-1">
                    <img src="http://localhost/hijauloka/uploads/<?= $gambar; ?>" 
                         alt="<?= $produk['nama_product']; ?>" 
                         class="w-full h-48 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2"><?= $produk['nama_product']; ?></h3>
                    <p class="text-gray-600 text-sm mb-3">
                        <?php
                        $desc = $produk['desk_product'] ?? 'Deskripsi tidak tersedia';
                        echo (strlen($desc) > 100) ? substr($desc, 0, 100) . '...' : $desc;
                        ?>
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Rp<?= number_format($produk['harga'], 0, ',', '.'); ?></span>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Order Now
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php $this->load->view('templates/footer'); ?>