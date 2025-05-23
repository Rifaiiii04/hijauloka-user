<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - HijauLoka</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php $this->load->view('templates/navbar'); ?>

    <div class="container mx-auto px-4 pt-20 pb-16 ">
        <div class="text-center relative mb-12">
            <h1 class="text-3xl font-bold text-green-800 mt-6 mb-6">Notifikasi</h1>
        </div>

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <?php if(empty($notifications)): ?>
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="bg-gray-100 p-6 rounded-full mb-4">
                        <i class="fas fa-bell text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">Belum ada notifikasi</h3>
                    <p class="text-gray-500">Kami akan memberi tahu Anda ketika ada pembaruan pada pesanan Anda</p>
                    <a href="<?= base_url('popular') ?>" class="mt-6 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Lanjutkan Belanja
                    </a>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach($notifications as $notification): ?>
                        <?php 
                        // Determine notification message and date based on order status
                        $message = '';
                        $date = null;
                        
                        // In the switch statement where you set the date variable
                        switch ($notification['stts_pemesanan']) {
                            case 'pending':
                                $message = 'Pesanan Anda sedang menunggu konfirmasi.';
                                $date = $notification['tgl_pemesanan']; // This should always have a value
                                break;
                            case 'diproses':
                                $message = 'Pesanan Anda sedang diproses oleh tim kami.';
                                $date = $notification['tgl_pemesanan']; // This should always have a value
                                break;
                            case 'dikirim':
                                $message = 'Pesanan Anda telah dikirim dan sedang dalam perjalanan.';
                                $date = $notification['tgl_dikirim'] ?? $notification['tgl_pemesanan']; // Fallback to tgl_pemesanan if tgl_dikirim is null
                                break;
                            case 'selesai':
                                $message = 'Pesanan Anda telah selesai. Terima kasih telah berbelanja di HijauLoka!';
                                $date = $notification['tgl_selesai'] ?? $notification['tgl_pemesanan']; // Fallback to tgl_pemesanan if tgl_selesai is null
                                break;
                            case 'dibatalkan':
                                $message = 'Pesanan Anda telah dibatalkan.';
                                $date = $notification['tgl_batal'] ?? $notification['tgl_pemesanan']; // Fallback to tgl_pemesanan if tgl_batal is null
                                break;
                            default:
                                $message = 'Status pesanan Anda telah diperbarui.';
                                $date = $notification['tgl_pemesanan']; // This should always have a value
                        }
                        
                        // Set icon based on order status
                        $iconClass = 'fa-box';
                        $bgColor = 'bg-blue-100';
                        $textColor = 'text-blue-500';
                        
                        switch ($notification['stts_pemesanan']) {
                            case 'diproses':
                                $iconClass = 'fa-box-open';
                                $bgColor = 'bg-blue-100';
                                $textColor = 'text-blue-500';
                                break;
                            case 'dikirim':
                                $iconClass = 'fa-truck';
                                $bgColor = 'bg-green-100';
                                $textColor = 'text-green-500';
                                break;
                            case 'selesai':
                                $iconClass = 'fa-check-circle';
                                $bgColor = 'bg-green-100';
                                $textColor = 'text-green-500';
                                break;
                            case 'dibatalkan':
                                $iconClass = 'fa-times-circle';
                                $bgColor = 'bg-red-100';
                                $textColor = 'text-red-500';
                                break;
                            default:
                                $iconClass = 'fa-box';
                                $bgColor = 'bg-blue-100';
                                $textColor = 'text-blue-500';
                        }
                        ?>
                        
                        <div class="p-5 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start">
                                <div class="<?= $bgColor ?> <?= $textColor ?> p-3 rounded-full mr-4">
                                    <i class="fas <?= $iconClass ?>"></i>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-medium text-gray-900">
                                            Pesanan #<?= $notification['id_order'] ?>
                                        </h3>
                                        <span class="text-xs text-gray-500">
                                            <?= $date ? date('d M Y, H:i', strtotime($date)) : 'N/A' ?>
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mt-1"><?= $message ?></p>
                                    
                                    <div class="mt-3">
                                        <a href="<?= base_url('order/detail/' . $notification['id_order']) ?>" 
                                           class="text-sm text-green-600 hover:text-green-700 font-medium">
                                            Lihat Detail Pesanan <i class="fas fa-chevron-right text-xs ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if(count($notifications) > 10): ?>
                    <div class="p-4 text-center border-t">
                        <button id="loadMoreBtn" class="text-green-600 hover:text-green-700 font-medium">
                            Muat Lebih Banyak
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark notifications as read when viewed
            fetch('<?= base_url('notification/mark_as_read') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update notification counter in navbar if needed
                    const notificationCounters = document.querySelectorAll('.notification-counter');
                    notificationCounters.forEach(counter => {
                        counter.textContent = '0';
                        counter.classList.add('hidden');
                    });
                }
            })
            .catch(error => console.error('Error marking notifications as read:', error));

            // Load more functionality
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            if (loadMoreBtn) {
                let page = 1;
                loadMoreBtn.addEventListener('click', function() {
                    page++;
                    fetch(`<?= base_url('notification/load_more') ?>?page=${page}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.notifications && data.notifications.length > 0) {
                            const container = document.querySelector('.divide-y');
                            
                            data.notifications.forEach(notification => {
                                // Determine notification message and date based on order status
                                let message = '';
                                let date = null;
                                
                                switch (notification.stts_pemesanan) {
                                    case 'pending':
                                        message = 'Pesanan Anda sedang menunggu konfirmasi.';
                                        date = notification.tgl_pemesanan;
                                        break;
                                    case 'diproses':
                                        message = 'Pesanan Anda sedang diproses oleh tim kami.';
                                        date = notification.tgl_pemesanan;
                                        break;
                                    case 'dikirim':
                                        message = 'Pesanan Anda telah dikirim dan sedang dalam perjalanan.';
                                        date = notification.tgl_dikirim;
                                        break;
                                    case 'selesai':
                                        message = 'Pesanan Anda telah selesai. Terima kasih telah berbelanja di HijauLoka!';
                                        date = notification.tgl_selesai;
                                        break;
                                    case 'dibatalkan':
                                        message = 'Pesanan Anda telah dibatalkan.';
                                        date = notification.tgl_batal;
                                        break;
                                    default:
                                        message = 'Status pesanan Anda telah diperbarui.';
                                        date = notification.tgl_pemesanan;
                                }
                                
                                let iconClass = 'fa-box';
                                let bgColor = 'bg-blue-100';
                                let textColor = 'text-blue-500';
                                
                                switch (notification.stts_pemesanan) {
                                    case 'diproses':
                                        iconClass = 'fa-box-open';
                                        bgColor = 'bg-blue-100';
                                        textColor = 'text-blue-500';
                                        break;
                                    case 'dikirim':
                                        iconClass = 'fa-truck';
                                        bgColor = 'bg-green-100';
                                        textColor = 'text-green-500';
                                        break;
                                    case 'selesai':
                                        iconClass = 'fa-check-circle';
                                        bgColor = 'bg-green-100';
                                        textColor = 'text-green-500';
                                        break;
                                    case 'dibatalkan':
                                        iconClass = 'fa-times-circle';
                                        bgColor = 'bg-red-100';
                                        textColor = 'text-red-500';
                                        break;
                                }
                                
                                const formattedDate = new Date(date).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                
                                const notificationHtml = `
                                    <div class="p-5 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start">
                                            <div class="${bgColor} ${textColor} p-3 rounded-full mr-4">
                                                <i class="fas ${iconClass}"></i>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <h3 class="font-medium text-gray-900">
                                                        Pesanan #${notification.id_order}
                                                    </h3>
                                                    <span class="text-xs text-gray-500">
                                                        ${formattedDate}
                                                    </span>
                                                </div>
                                                <p class="text-gray-600 mt-1">${message}</p>
                                                
                                                <div class="mt-3">
                                                    <a href="${'<?= base_url('order/detail/') ?>' + notification.id_order}" 
                                                       class="text-sm text-green-600 hover:text-green-700 font-medium">
                                                        Lihat Detail Pesanan <i class="fas fa-chevron-right text-xs ml-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                                container.insertAdjacentHTML('beforeend', notificationHtml);
                            });
                            
                            if (data.notifications.length < 10) {
                                loadMoreBtn.parentElement.remove();
                            }
                        } else {
                            loadMoreBtn.textContent = 'Tidak ada notifikasi lagi';
                            loadMoreBtn.disabled = true;
                        }
                    })
                    .catch(error => console.error('Error loading more notifications:', error));
                });
            }
        });
    </script>
</body>
</html>