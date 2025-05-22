<?php $this->load->view('templates/header'); ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            <i class="fas fa-credit-card text-4xl text-green-600 mb-4"></i>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
            <p class="text-gray-600">Silakan selesaikan pembayaran Anda</p>
        </div>
        
        <div class="mb-6">
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Order ID:</span>
                <span class="font-semibold"><?= $order_id ?></span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Total Pembayaran:</span>
                <span class="font-semibold">Rp<?= number_format($amount, 0, ',', '.') ?></span>
            </div>
        </div>
        
        <div class="text-center">
            <button id="pay-button" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors">
                Bayar Sekarang
            </button>
        </div>
    </div>
</div>

<!-- Load Midtrans JS library -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $client_key ?>"></script>
<script>
    document.getElementById('pay-button').onclick = function() {
        // Trigger snap popup
        snap.pay('<?= $snap_token ?>', {
            onSuccess: function(result) {
                window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id . '&status_code=200') ?>';
            },
            onPending: function(result) {
                window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id . '&status_code=201') ?>';
            },
            onError: function(result) {
                window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id . '&status_code=400') ?>';
            },
            onClose: function() {
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
        });
    };
    
    // Auto-trigger payment popup
    window.onload = function() {
        setTimeout(function() {
            document.getElementById('pay-button').click();
        }, 1000);
    };
</script>

<?php $this->load->view('templates/footer'); ?>