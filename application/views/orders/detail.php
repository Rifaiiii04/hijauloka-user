<?php $this->load->view('templates/header2'); ?>

<div class="container mx-auto max-w-xl py-10 min-h-[60vh] p-3">
    <!-- Header Navigation -->
    <div class="mb-6 flex items-center gap-3">
        <a href="<?= base_url('orders') ?>" class="text-green-700 hover:underline flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="<?= base_url() ?>" class="text-green-700 hover:underline flex items-center gap-1">
            <i class="fas fa-home"></i> Beranda
        </a>
        <h2 class="md:text-2xl text-lg font-bold text-green-800 ml-2">Detail Pesanan #<?= $order['id_order'] ?></h2>
    </div>

    <!-- Order Info Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-wrap gap-3 items-center mb-2">
            <span class="font-semibold text-green-700">Tanggal:</span>
            <span><?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></span>
            
            <span class="font-semibold text-green-700">Status:</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $order['stts_pemesanan'] == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?> capitalize">
                <?= $order['stts_pemesanan'] ?>
            </span>
            
            <span class="font-semibold text-green-700">Pembayaran:</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $order['stts_pembayaran'] == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?> capitalize">
                <?= $order['stts_pembayaran'] ?>
            </span>
        </div>

        <!-- Order Tracking Timeline -->
        <div class="mt-6">
            <div class="font-semibold text-green-700 mb-4">Status Pengiriman</div>
            <?php
            $status_timeline = [
                'pending' => [
                    'icon' => 'fa-hourglass-half',
                    'color' => 'text-yellow-500',
                    'title' => 'Pesanan Diterima',
                    'description' => 'Pesanan Anda telah diterima dan sedang diproses',
                    'time' => date('d M Y, H:i', strtotime($order['tgl_pemesanan']))
                ],
                'diproses' => [
                    'icon' => 'fa-cogs',
                    'color' => 'text-blue-500',
                    'title' => 'Pesanan Diproses',
                    'description' => 'Pesanan Anda sedang dipersiapkan untuk pengiriman',
                    'time' => $order['stts_pemesanan'] == 'diproses' || $order['stts_pemesanan'] == 'dikirim' || $order['stts_pemesanan'] == 'selesai' 
                        ? date('d M Y, H:i', strtotime($order['tgl_pemesanan'] . ' +1 day'))
                        : null
                ],
                'dikirim' => [
                    'icon' => 'fa-truck',
                    'color' => 'text-indigo-500',
                    'title' => 'Pesanan Dikirim',
                    'description' => 'Pesanan Anda sedang dalam perjalanan',
                    'time' => $order['stts_pemesanan'] == 'dikirim' || $order['stts_pemesanan'] == 'selesai'
                        ? date('d M Y, H:i', strtotime($order['tgl_pemesanan'] . ' +2 days'))
                        : null
                ],
                'selesai' => [
                    'icon' => 'fa-check-circle',
                    'color' => 'text-green-500',
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
                        <div class="absolute left-0 w-8 h-8 rounded-full flex items-center justify-center <?= $is_completed ? $info['color'] : 'bg-gray-200 text-gray-400' ?>">
                            <i class="fas <?= $info['icon'] ?>"></i>
                        </div>

                        <!-- Status Content -->
                        <div class="bg-gray-50 rounded-lg p-4 <?= $is_current ? 'ring-2 ring-green-500' : '' ?>">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-800"><?= $info['title'] ?></h4>
                                    <p class="text-sm text-gray-600 mt-1"><?= $info['description'] ?></p>
                                </div>
                                <?php if ($info['time']): ?>
                                    <span class="text-xs text-gray-500"><?= $info['time'] ?></span>
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
                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-green-600"></i>
                        <div>
                            <h4 class="font-semibold text-green-800">Estimasi Pengiriman</h4>
                            <p class="text-sm text-green-700">Pesanan diperkirakan akan sampai pada <?= $estimated_date ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Shipping Address -->
        <div class="mt-2">
            <div class="font-semibold text-green-700 mb-1">Alamat Pengiriman</div>
            <?php if ($shipping_address): ?>
                <div class="text-sm text-gray-700 mb-1">
                    <?= htmlspecialchars($shipping_address['recipient_name']) ?> 
                    (<?= htmlspecialchars($shipping_address['phone']) ?>)
                </div>
                <div class="text-xs text-gray-600 mb-1">
                    <?= htmlspecialchars($shipping_address['address']) ?>, 
                    RT <?= $shipping_address['rt'] ?>/RW <?= $shipping_address['rw'] ?>, 
                    No. <?= $shipping_address['house_number'] ?>, 
                    <?= $shipping_address['postal_code'] ?>
                </div>
                <?php if (!empty($shipping_address['detail_address'])): ?>
                    <div class="text-xs text-gray-500 mb-1">
                        Catatan: <?= htmlspecialchars($shipping_address['detail_address']) ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-xs text-gray-500">Alamat tidak ditemukan.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Items Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="font-semibold text-green-700 mb-3">Daftar Produk</div>
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
                                 class="w-full h-full object-cover rounded-lg">
                        </div>

                        <!-- Product Details -->
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                <?= htmlspecialchars($item['nama_product']) ?>
                            </h3>
                            <div class="flex justify-between items-center">
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
        <div class="flex justify-between items-center border-t pt-4 mt-4 font-bold text-lg">
            <span>Total</span>
            <span class="text-green-700">
                Rp<?= number_format($total, 0, ',', '.') ?>
            </span>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-end">
        <a href="<?= base_url('orders') ?>" 
           class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-all flex items-center gap-2">
            <i class="fas fa-list"></i> Daftar Pesanan
        </a>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>