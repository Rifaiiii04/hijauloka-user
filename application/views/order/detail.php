<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - HijauLoka</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php $this->load->view('templates/navbar'); ?>

    <div class="container mx-auto px-4 pt-20 pb-16 md:pt-28">
        <div class="text-center relative mb-12">
            <h1 class="text-3xl font-bold text-green-800 mt-10 mb-6">Detail Pesanan</h1>
            <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-green-600 to-green-800 rounded-full"></div>
        </div>

        <div class="max-w-4xl mx-auto">
            <!-- Order Summary Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Pesanan #<?= $order['id_order'] ?></h2>
                        
                        <?php
                        $statusClass = 'bg-blue-100 text-blue-800';
                        $statusIcon = 'fa-clock';
                        
                        switch($order['stts_pemesanan']) {
                            case 'pending':
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusIcon = 'fa-clock';
                                break;
                            case 'diproses':
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusIcon = 'fa-box-open';
                                break;
                            case 'dikirim':
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusIcon = 'fa-truck';
                                break;
                            case 'selesai':
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusIcon = 'fa-check-circle';
                                break;
                            case 'dibatalkan':
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusIcon = 'fa-times-circle';
                                break;
                        }
                        ?>
                        
                        <span class="<?= $statusClass ?> px-3 py-1 rounded-full text-sm font-medium flex items-center">
                            <i class="fas <?= $statusIcon ?> mr-1"></i>
                            <?= ucfirst($order['stts_pemesanan']) ?>
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pemesanan</h3>
                            <p class="text-gray-800"><?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status Pembayaran</h3>
                            <p class="<?= $order['stts_pembayaran'] == 'lunas' ? 'text-green-600' : 'text-red-600' ?> font-medium">
                                <?= $order['stts_pembayaran'] == 'lunas' ? 'Lunas' : 'Belum Dibayar' ?>
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</h3>
                            <p class="text-gray-800">
                                <?php
                                switch($order['metode_pembayaran']) {
                                    case 'cod':
                                        echo 'Cash on Delivery (COD)';
                                        break;
                                    case 'midtrans':
                                        echo 'Midtrans';
                                        break;
                                    case 'transfer':
                                        echo 'Transfer Bank';
                                        break;
                                    default:
                                        echo ucfirst($order['metode_pembayaran']);
                                }
                                ?>
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kurir</h3>
                            <p class="text-gray-800"><?= strtoupper($order['kurir']) ?></p>
                        </div>
                    </div>
                    
                    <!-- Order Timeline -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Status Pesanan</h3>
                        
                        <div class="relative">
                            <!-- Timeline Line -->
                            <div class="absolute left-3.5 top-0 h-full w-0.5 bg-gray-200"></div>
                            
                            <!-- Timeline Items -->
                            <div class="space-y-6">
                                <div class="relative flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-green-500 h-7 w-7 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Pesanan Dibuat</h4>
                                        <p class="text-xs text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></p>
                                    </div>
                                </div>
                                
                                <?php if($order['stts_pemesanan'] != 'pending'): ?>
                                <div class="relative flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-green-500 h-7 w-7 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Pesanan Diproses</h4>
                                        <p class="text-xs text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($order['stts_pemesanan'] == 'dikirim' || $order['stts_pemesanan'] == 'selesai'): ?>
                                <div class="relative flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-green-500 h-7 w-7 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Pesanan Dikirim</h4>
                                        <p class="text-xs text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($order['tgl_dikirim'])) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($order['stts_pemesanan'] == 'selesai'): ?>
                                <div class="relative flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-green-500 h-7 w-7 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Pesanan Selesai</h4>
                                        <p class="text-xs text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($order['tgl_selesai'])) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($order['stts_pemesanan'] == 'dibatalkan'): ?>
                                <div class="relative flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-red-500 h-7 w-7 rounded-full flex items-center justify-center">
                                            <i class="fas fa-times text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Pesanan Dibatalkan</h4>
                                        <p class="text-xs text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($order['tgl_batal'])) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Produk yang Dibeli</h2>
                    
                    <div class="divide-y divide-gray-200">
                        <?php foreach($items as $item): ?>
                        <div class="py-4 flex">
                            <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-md overflow-hidden">
                                <img src="https://admin.hijauloka.my.id/uploads/<?= $item['gambar'] ?>" alt="<?= $item['nama_produk'] ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900"><?= $item['nama_produk'] ?></h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <?= isset($item['qty']) ? $item['qty'] : 1 ?> x 
                                            Rp <?= number_format(isset($item['harga']) ? $item['harga'] : 0, 0, ',', '.') ?>
                                        </p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Rp <?= number_format((isset($item['harga']) ? $item['harga'] : 0) * (isset($item['qty']) ? $item['qty'] : 1), 0, ',', '.') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <p class="text-sm text-gray-600">Subtotal Produk</p>
                            <p class="text-sm text-gray-900">Rp <?= number_format($order['total_harga'] - $order['ongkir'], 0, ',', '.') ?></p>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-sm text-gray-600">Ongkos Kirim</p>
                            <p class="text-sm text-gray-900">Rp <?= number_format($order['ongkir'], 0, ',', '.') ?></p>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between">
                                <p class="text-base font-medium text-gray-900">Total</p>
                                <p class="text-base font-medium text-gray-900">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="<?= base_url('notification') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat Pesanan
                </a>
                
                <?php if($order['stts_pemesanan'] == 'dikirim'): ?>
                <button id="completeOrderBtn" data-order-id="<?= $order['id_order'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check-circle mr-2"></i> Pesanan Diterima
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $this->load->view('templates/footer'); ?>
    
    <?php if($order['stts_pemesanan'] == 'dikirim'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const completeOrderBtn = document.getElementById('completeOrderBtn');
            
            completeOrderBtn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                
                if (confirm('Apakah Anda yakin telah menerima pesanan ini?')) {
                    fetch('<?= base_url('order/complete') ?>/' + orderId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Terima kasih! Pesanan Anda telah dikonfirmasi selesai.');
                            window.location.reload();
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi nanti.');
                        }
                    })
                    .catch(error => {
                        console.error('Error completing order:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi nanti.');
                    });
                }
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>