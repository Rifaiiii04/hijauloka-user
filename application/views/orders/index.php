<?php $this->load->view('templates/header2'); ?>
<?php
// Ambil status dari query string
$status = $_GET['status'] ?? 'all';
$status_map = [
    'all' => 'Semua',
    'pending' => 'Menunggu',
    'diproses' => 'Diproses',
    'dikirim' => 'Dikirim',
    'selesai' => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
];
// Filter orders sesuai status
$filtered_orders = ($status === 'all') ? $orders : array_filter($orders, function($o) use ($status) {
    return $o['stts_pemesanan'] === $status;
});
?>
<div class="container mx-auto max-w-2xl py-12 min-h-[60vh]">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-home"></i>
                <span class="font-medium">Beranda</span>
            </a>
            <h2 class="text-3xl font-extrabold text-green-800 tracking-tight">Pesanan Saya</h2>
        </div>
    </div>
    <!-- Top Bar Tab Filter -->
    <div class="flex justify-center mb-8 gap-2 flex-wrap">
        <?php foreach ($status_map as $key => $label): ?>
            <a href="?status=<?= $key ?>" class="px-4 py-2 rounded-full font-semibold text-sm transition-all hover:scale-105
                <?= $status === $key ? 'bg-green-600 text-white shadow animate-bounce-subtle' : 'bg-gray-100 text-green-800 hover:bg-green-200' ?>
                "><?= $label ?></a>
        <?php endforeach; ?>
    </div>
    <?php if (empty($filtered_orders)): ?>
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center animate-fade-in">
            <i class="fas fa-box-open text-5xl text-gray-300 mb-4 animate-float"></i>
            <p class="text-gray-600 text-lg mb-2">Tidak ada pesanan <?= $status_map[$status] ?? '' ?>.</p>
            <a href="<?= base_url('popular') ?>" class="inline-block mt-4 px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all font-semibold text-lg shadow-md hover:scale-105">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="space-y-7">
            <?php foreach ($filtered_orders as $order): ?>
                <?php
                $status_icon = [
                    'pending' => 'fa-hourglass-half text-yellow-500',
                    'diproses' => 'fa-cogs text-blue-500',
                    'dikirim' => 'fa-truck text-indigo-500',
                    'selesai' => 'fa-check-circle text-green-500',
                    'dibatalkan' => 'fa-times-circle text-red-500',
                ];
                $icon = $status_icon[$order['stts_pemesanan']] ?? 'fa-hourglass text-gray-400';
                $badge_color = [
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'diproses' => 'bg-blue-100 text-blue-700',
                    'dikirim' => 'bg-indigo-100 text-indigo-700',
                    'selesai' => 'bg-green-100 text-green-700',
                    'dibatalkan' => 'bg-red-100 text-red-700',
                ];
                $badge = $badge_color[$order['stts_pemesanan']] ?? 'bg-gray-100 text-gray-700';
                $pay_badge = $order['stts_pembayaran'] == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                ?>
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 hover:shadow-xl transition-all duration-300 animate-slide-in hover:scale-[1.02]">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <i class="fas <?= $icon ?> text-3xl animate-pulse"></i>
                        </div>
                        <div>
                            <div class="font-bold text-green-800 text-lg mb-1">#<?= $order['id_order'] ?> <span class="text-xs text-gray-400">| <?= date('d M Y, H:i', strtotime($order['tgl_pemesanan'])) ?></span></div>
                            <div class="flex flex-wrap gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $badge ?> capitalize animate-fade-in"><?= $order['stts_pemesanan'] ?></span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $pay_badge ?> capitalize animate-fade-in">Pembayaran: <?= $order['stts_pembayaran'] ?></span>
                            </div>
                            <div class="text-sm text-gray-700 mb-1">Total: <span class="font-bold text-green-700">Rp<?= number_format($order['total_harga'], 0, ',', '.') ?></span></div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 sm:gap-0 sm:flex-row sm:items-center">
                        <a href="<?= base_url('orders/detail/' . $order['id_order']) ?>" class="px-5 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-all shadow text-sm flex items-center gap-2 hover:scale-105">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <?php if ($order['stts_pemesanan'] === 'pending'): ?>
                            <button onclick="cancelOrder(<?= $order['id_order'] ?>)" class="px-5 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-all shadow text-sm flex items-center gap-2 hover:scale-105 ml-2">
                                <i class="fas fa-times"></i> Batalkan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-in {
    from { 
        opacity: 0;
        transform: translateX(-20px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

@keyframes bounce-subtle {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.animate-fade-in {
    animation: fade-in 0.6s cubic-bezier(.4,0,.2,1);
}

.animate-slide-in {
    animation: slide-in 0.6s cubic-bezier(.4,0,.2,1);
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-bounce-subtle {
    animation: bounce-subtle 1s infinite;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>
<script>
function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        fetch('<?= base_url('orders/cancel_order') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Gagal membatalkan pesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membatalkan pesanan');
        });
    }
}
</script>
<?php $this->load->view('templates/footer'); ?> 