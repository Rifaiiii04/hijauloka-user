<?php $this->load->view('templates/header2'); ?>

<div class="container mx-auto max-w-4xl py-8 px-4 sm:px-6 lg:px-8 min-h-[60vh]">
    <!-- Header Navigation -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <a href="<?= base_url('orders') ?>" class="inline-flex items-center gap-2 text-green-700 hover:text-green-800 hover:underline transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
                <span>Kembali ke Daftar Pesanan</span>
            </a>
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 text-green-700 hover:text-green-800 hover:underline transition-colors">
                <i class="fas fa-home text-sm"></i>
                <span>Beranda</span>
            </a>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            Detail Pesanan #<?= $order['id_order'] ?>
        </h1>
    </div>

    <!-- Order Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6">
            <!-- Order Status Badges -->
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?= $order['stts_pemesanan'] == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?> capitalize">
                        <?= $order['stts_pemesanan'] ?>
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Pembayaran:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?= $order['stts_pembayaran'] == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?> capitalize">
                        <?= $order['stts_pembayaran'] ?>
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Tanggal:</span>
                    <span class="text-sm text-gray-700"><?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></span>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-green-600"></i>
                    Alamat Pengiriman
                </h3>
                <?php if ($shipping_address): ?>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2">
                            <div class="flex-grow">
                                <p class="text-sm font-medium text-gray-800">
                                    <?= htmlspecialchars($shipping_address['recipient_name']) ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <?= htmlspecialchars($shipping_address['phone']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <?= htmlspecialchars($shipping_address['address']) ?>, 
                            RT <?= $shipping_address['rt'] ?>/RW <?= $shipping_address['rw'] ?>, 
                            No. <?= $shipping_address['house_number'] ?>, 
                            <?= $shipping_address['postal_code'] ?>
                        </div>
                        <?php if (!empty($shipping_address['detail_address'])): ?>
                            <div class="text-sm text-gray-500 italic">
                                Catatan: <?= htmlspecialchars($shipping_address['detail_address']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-gray-500">Alamat tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Products Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-box text-green-600"></i>
                Daftar Produk
            </h2>
            <div class="divide-y divide-gray-100">
                <?php $total = 0; ?>
                <?php foreach ($order_items as $item): ?>
                    <?php 
                    $total += $item['subtotal'];
                    $gambar = 'default.jpg';
                    if (!empty($item['gambar'])) {
                        $images = explode(',', $item['gambar']);
                        $gambar = trim($images[0]);
                    }
                    ?>
                    <div class="py-4">
                        <div class="flex gap-4">
                            <!-- Product Image -->
                            <div class="w-20 h-20 flex-shrink-0">
                                <img src="https://admin.hijauloka.my.id/uploads/<?= $gambar ?>" 
                                     alt="<?= htmlspecialchars($item['nama_product']) ?>" 
                                     class="w-full h-full object-cover rounded-lg shadow-sm">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow min-w-0">
                                <h3 class="text-base font-medium text-gray-800 mb-1 truncate">
                                    <?= htmlspecialchars($item['nama_product']) ?>
                                </h3>
                                <div class="flex flex-wrap justify-between items-center gap-2">
                                    <div class="text-sm text-gray-500">
                                        Qty: <?= $item['quantity'] ?>
                                    </div>
                                    <div class="font-semibold text-green-700">
                                        Rp<?= number_format($item['subtotal'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Total Section -->
            <div class="flex justify-between items-center border-t border-gray-100 pt-4 mt-4">
                <span class="text-lg font-semibold text-gray-800">Total</span>
                <span class="text-xl font-bold text-green-700">
                    Rp<?= number_format($total, 0, ',', '.') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Tracking Timeline Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-truck text-green-600"></i>
                Status Pengiriman
            </h2>
            <?php
            $status_timeline = [
                'pending' => [
                    'icon' => 'fa-hourglass-half',
                    'color' => 'text-yellow-500',
                    'bg_color' => 'bg-yellow-50',
                    'title' => 'Pesanan Diterima',
                    'description' => 'Pesanan Anda telah diterima dan sedang diproses',
                    'time' => date('d M Y, H:i', strtotime($order['tgl_pemesanan']))
                ],
                'diproses' => [
                    'icon' => 'fa-cogs',
                    'color' => 'text-blue-500',
                    'bg_color' => 'bg-blue-50',
                    'title' => 'Pesanan Diproses',
                    'description' => 'Pesanan Anda sedang dipersiapkan untuk pengiriman',
                    'time' => $order['stts_pemesanan'] == 'diproses' || $order['stts_pemesanan'] == 'dikirim' || $order['stts_pemesanan'] == 'selesai' 
                        ? date('d M Y, H:i', strtotime($order['tgl_pemesanan'] . ' +1 day'))
                        : null
                ],
                'dikirim' => [
                    'icon' => 'fa-truck',
                    'color' => 'text-indigo-500',
                    'bg_color' => 'bg-indigo-50',
                    'title' => 'Pesanan Dikirim',
                    'description' => 'Pesanan Anda sedang dalam perjalanan',
                    'time' => $order['stts_pemesanan'] == 'dikirim' || $order['stts_pemesanan'] == 'selesai'
                        ? date('d M Y, H:i', strtotime($order['tgl_pemesanan'] . ' +2 days'))
                        : null
                ],
                'selesai' => [
                    'icon' => 'fa-check-circle',
                    'color' => 'text-green-500',
                    'bg_color' => 'bg-green-50',
                    'title' => 'Pesanan Selesai',
                    'description' => 'Pesanan telah diterima',
                    'time' => $order['stts_pemesanan'] == 'selesai'
                        ? date('d M Y, H:i', strtotime($order['tgl_pemesanan'] . ' +3 days'))
                        : null
                ]
            ];

            $current_status = $order['stts_pemesanan'];
            $status_order = ['pending', 'diproses', 'dikirim', 'selesai'];
            $current_index = array_search($current_status, $status_order);
            ?>

            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                <?php foreach ($status_timeline as $status => $info): ?>
                    <?php 
                    $status_index = array_search($status, $status_order);
                    $is_completed = $status_index <= $current_index;
                    $is_current = $status === $current_status;
                    ?>
                    <div class="relative pl-10 pb-8 last:pb-0">
                        <!-- Status Icon -->
                        <div class="absolute left-0 w-8 h-8 rounded-full flex items-center justify-center <?= $is_completed ? $info['color'] : 'bg-gray-100 text-gray-400' ?> shadow-sm">
                            <i class="fas <?= $info['icon'] ?>"></i>
                        </div>

                        <!-- Status Content -->
                        <div class="<?= $info['bg_color'] ?> rounded-lg p-4 <?= $is_current ? 'ring-2 ring-green-500' : '' ?>">
                            <div class="flex flex-wrap justify-between items-start gap-2">
                                <div>
                                    <h4 class="font-semibold text-gray-800"><?= $info['title'] ?></h4>
                                    <p class="text-sm text-gray-600 mt-1"><?= $info['description'] ?></p>
                                </div>
                                <?php if ($info['time']): ?>
                                    <span class="text-xs text-gray-500 whitespace-nowrap"><?= $info['time'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Estimated Delivery -->
            <?php if ($current_status != 'selesai' && $current_status != 'dibatalkan'): ?>
                <?php
                $estimated_days = 0;
                switch($current_status) {
                    case 'pending':
                        $estimated_days = 3;
                        break;
                    case 'diproses':
                        $estimated_days = 2;
                        break;
                    case 'dikirim':
                        $estimated_days = 1;
                        break;
                }
                $estimated_date = date('d M Y', strtotime($order['tgl_pemesanan'] . " +{$estimated_days} days"));
                ?>
                <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-green-800">Estimasi Pengiriman</h4>
                            <p class="text-sm text-green-700 mt-1">Pesanan diperkirakan akan sampai pada <?= $estimated_date ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-end">
        <a href="<?= base_url('orders') ?>" 
           class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors shadow-sm">
            <i class="fas fa-list"></i>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>