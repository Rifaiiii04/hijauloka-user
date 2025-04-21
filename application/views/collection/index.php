<main
    class="px-3 py-20 pt-20 pb-24 bg-gradient-to-b from-green-50 to-green-100 font-sans"
    x-data="{ open: '' }"
>
    <div class="mb-12 mt-10 text-center">
        <h1 class="font-bold text-4xl text-green-800 relative inline-block pb-4">
            Koleksi
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
        </h1>
    </div>
    <div class="space-y-5 max-w-md mx-auto">
        <!-- Plants -->
        <div>
            <button
                @click="open === 'plants' ? open = '' : open = 'plants'"
                class="w-full flex items-center justify-between bg-gradient-to-tr from-green-800 to-green-400 rounded-3xl px-7 py-8 shadow-xl transition-all duration-200 hover:scale-[1.02] active:scale-95"
            >
                <span class="flex items-center gap-5">
                    <img
                        src="<?= base_url('assets/img/plants.png') ?>"
                        alt="Plants"
                        class="w-20 h-20 object-contain rounded-2xl p-2 shadow"
                    />
                    <span class="text-white text-2xl font-bold tracking-wide drop-shadow">Plants</span>
                </span>
                <i
                    :class="open === 'plants' ? 'fa-chevron-up' : 'fa-chevron-down'"
                    class="fas text-white text-3xl"
                ></i>
            </button>
            <template x-if="open === 'plants'">
                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="bg-green-700 rounded-2xl shadow-2xl mt-4 mb-2 border border-green-200 overflow-hidden"
                >
                    <?php if (!empty($plants_subcategories)): ?>
                        <?php foreach ($plants_subcategories as $subcategory): ?>
                            <a
                                href="<?= base_url('plants/' . strtolower(str_replace(' ', '_', $subcategory['nama_kategori']))) ?>"
                                class="block px-7 py-4 text-white hover:bg-green-800 active:bg-green-900 border-b last:border-b-0 transition-all duration-150 text-lg font-medium"
                            >
                                <?= $subcategory['nama_kategori'] ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="px-7 py-4 text-white">Tidak ada kategori yang tersedia.</p>
                    <?php endif; ?>
                </div>
            </template>
        </div>

        <!-- Seeds -->
        <div>
            <button
                @click="open === 'seeds' ? open = '' : open = 'seeds'"
                class="w-full flex items-center justify-between bg-gradient-to-tr from-green-800 to-green-400 rounded-3xl px-7 py-8 shadow-xl transition-all duration-200 hover:scale-[1.02] active:scale-95"
            >
                <span class="flex items-center gap-5">
                    <img
                        src="<?= base_url('assets/img/seeds.png') ?>"
                        alt="Seeds"
                        class="w-20 h-20 object-contain rounded-2xl p-2 shadow"
                    />
                    <span class="text-white text-2xl font-bold tracking-wide drop-shadow">Seeds</span>
                </span>
                <i
                    :class="open === 'seeds' ? 'fa-chevron-up' : 'fa-chevron-down'"
                    class="fas text-white text-3xl"
                ></i>
            </button>
            <template x-if="open === 'seeds'">
                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="bg-green-700 rounded-2xl shadow-2xl mt-4 mb-2 border border-green-200 overflow-hidden"
                >
                    <?php if (!empty($seeds_subcategories)): ?>
                        <?php foreach ($seeds_subcategories as $subcategory): ?>
                            <a
                                href="<?= base_url('collection/' . strtolower(str_replace(' ', '_', $subcategory['nama_kategori'])) . '.php') ?>"
                                class="block px-7 py-4 text-white active:bg-green-800 border-b last:border-b-0 transition-all duration-150 text-lg font-medium"
                            >
                                <?= $subcategory['nama_kategori'] ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="px-7 py-4 text-white">Tidak ada kategori yang tersedia.</p>
                    <?php endif; ?>
                </div>
            </template>
        </div>

        <!-- Pots -->
        <div>
            <button
                @click="open === 'pots' ? open = '' : open = 'pots'"
                class="w-full flex items-center justify-between bg-gradient-to-tr from-green-800 to-green-400 rounded-3xl px-7 py-8 shadow-xl transition-all duration-200 hover:scale-[1.02] active:scale-95"
            >
                <span class="flex items-center gap-5">
                    <img
                        src="<?= base_url('assets/img/pots.png') ?>"
                        alt="Pots"
                        class="w-20 h-20 object-contain rounded-2xl p-2 shadow"
                    />
                    <span class="text-white text-2xl font-bold tracking-wide drop-shadow">Pots</span>
                </span>
                <i
                    :class="open === 'pots' ? 'fa-chevron-up' : 'fa-chevron-down'"
                    class="fas text-white text-3xl"
                ></i>
            </button>
            <template x-if="open === 'pots'">
                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="bg-green-700 rounded-2xl shadow-2xl mt-4 mb-2 border border-green-200 overflow-hidden"
                >
                    <?php if (!empty($pots_subcategories)): ?>
                        <?php foreach ($pots_subcategories as $subcategory): ?>
                            <a
                                href="<?= base_url('collection/' . strtolower(str_replace(' ', '_', $subcategory['nama_kategori'])) . '.php') ?>"
                                class="block px-7 py-4 text-white active:bg-green-800 border-b last:border-b-0 transition-all duration-150 text-lg font-medium"
                            >
                                <?= $subcategory['nama_kategori'] ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="px-7 py-4 text-white">Tidak ada kategori yang tersedia.</p>
                    <?php endif; ?>
                </div>
            </template>
        </div>
    </div>
</main>