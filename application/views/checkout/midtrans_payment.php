<?php $this->load->view('templates/header2'); ?>

<div class="container mx-auto max-w-4xl py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-green-800">Pembayaran</h1>
        <p class="text-gray-600">Selesaikan pembayaran untuk pesanan Anda</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-credit-card text-green-600 text-3xl"></i>
            </div>
            <h2 class="text-xl font-semibold mt-4">Pembayaran untuk Order #<?= $order_id ?></h2>
            <p class="text-gray-600 mt-2">Total: Rp <?= number_format($amount, 0, ',', '.') ?></p>
        </div>
        
        <!-- Direct Payment Options -->
        <div class="mb-6 border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Bank Transfer -->
                <div class="border rounded-lg p-4 hover:border-green-500 cursor-pointer" onclick="showPaymentDetails('bank')">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-university text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-medium">Transfer Bank</h4>
                            <p class="text-sm text-gray-500">BCA, BNI, BRI, Mandiri, Permata</p>
                        </div>
                    </div>
                </div>
                
                <!-- E-Wallet -->
                <div class="border rounded-lg p-4 hover:border-green-500 cursor-pointer" onclick="showPaymentDetails('ewallet')">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-wallet text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-medium">E-Wallet</h4>
                            <p class="text-sm text-gray-500">GoPay, OVO, DANA, LinkAja</p>
                        </div>
                    </div>
                </div>
                
                <!-- QRIS -->
                <div class="border rounded-lg p-4 hover:border-green-500 cursor-pointer" onclick="showPaymentDetails('qris')">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-qrcode text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-medium">QRIS</h4>
                            <p class="text-sm text-gray-500">Scan untuk membayar</p>
                        </div>
                    </div>
                </div>
                
                <!-- Credit Card -->
                <div class="border rounded-lg p-4 hover:border-green-500 cursor-pointer" onclick="showPaymentDetails('cc')">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-medium">Kartu Kredit</h4>
                            <p class="text-sm text-gray-500">Visa, Mastercard, JCB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Details Section (initially hidden) -->
        <div id="payment-details" class="hidden border-t pt-6">
            <h3 class="text-lg font-semibold mb-4" id="payment-method-title">Detail Pembayaran</h3>
            <div id="payment-method-content" class="space-y-4">
                <!-- Content will be filled by JavaScript -->
            </div>
        </div>
        
        <!-- Midtrans Button (as fallback) -->
        <div id="payment-button" class="mt-6 text-center">
            <button id="pay-button" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                Bayar dengan Midtrans
            </button>
            <p class="text-gray-500 text-sm mt-2">Anda akan diarahkan ke halaman pembayaran Midtrans</p>
        </div>
        
        <!-- Check Status Button -->
        <div class="mt-4 text-center">
            <a href="<?= base_url('midtrans/check_status/' . $id_order) ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium inline-block">
                Cek Status Pembayaran
            </a>
        </div>
    </div>
</div>

<!-- Midtrans JS SDK -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-nJRhbIURiIRk4n5S"></script>

<script>
    // Function to show payment details based on selected method
    function showPaymentDetails(method) {
        const detailsSection = document.getElementById('payment-details');
        const methodTitle = document.getElementById('payment-method-title');
        const methodContent = document.getElementById('payment-method-content');
        
        detailsSection.classList.remove('hidden');
        
        // Set title and content based on selected method
        switch(method) {
            case 'bank':
                methodTitle.textContent = 'Transfer Bank';
                methodContent.innerHTML = `
                    <p class="text-gray-600">Silakan pilih bank untuk melakukan pembayaran:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="snapPay('bank_transfer', 'bca')">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-university text-blue-600"></i>
                                </div>
                                <span class="ml-3">Bank BCA</span>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="snapPay('bank_transfer', 'bni')">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-university text-green-600"></i>
                                </div>
                                <span class="ml-3">Bank BNI</span>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="snapPay('bank_transfer', 'bri')">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-university text-red-600"></i>
                                </div>
                                <span class="ml-3">Bank BRI</span>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="snapPay('bank_transfer', 'mandiri')">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-university text-yellow-600"></i>
                                </div>
                                <span class="ml-3">Bank Mandiri</span>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="snapPay('bank_transfer', 'permata')">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-university text-purple-600"></i>
                                </div>
                                <span class="ml-3">Bank Permata</span>
                            </div>
                        </div>
                    </div>
                `;
                break;
                
            case 'ewallet':
                methodTitle.textContent = 'E-Wallet';
                methodContent.innerHTML = `
                    <p class="text-gray-600">Silakan pilih e-wallet untuk melakukan pembayaran:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="snapPay('gopay')">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-wallet text-green-600"></i>
                                </div>
                                <span class="ml-3">GoPay</span>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="snapPay('shopeepay')">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-wallet text-orange-600"></i>
                                </div>
                                <span class="ml-3">ShopeePay</span>
                            </div>
                        </div>
                    </div>
                `;
                break;
                
            case 'qris':
                methodTitle.textContent = 'QRIS';
                methodContent.innerHTML = `
                    <p class="text-gray-600">Bayar dengan QRIS dari aplikasi e-wallet favorit Anda:</p>
                    <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer mt-4" onclick="snapPay('qris')">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-qrcode text-blue-600"></i>
                            </div>
                            <span class="ml-3">QRIS (OVO, DANA, LinkAja, dll)</span>
                        </div>
                    </div>
                `;
                break;
                
            case 'cc':
                methodTitle.textContent = 'Kartu Kredit';
                methodContent.innerHTML = `
                    <p class="text-gray-600">Bayar dengan kartu kredit:</p>
                    <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer mt-4" onclick="snapPay('credit_card')">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-credit-card text-red-600"></i>
                            </div>
                            <span class="ml-3">Kartu Kredit (Visa, Mastercard, JCB)</span>
                        </div>
                    </div>
                `;
                break;
        }
    }
    
    // Function to trigger Snap payment with specific method
    function snapPay(paymentType, bankType = null) {
        const options = {
            onSuccess: function(result) {
                window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id) ?>&transaction_status=' + result.transaction_status;
            },
            onPending: function(result) {
                window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id) ?>&transaction_status=' + result.transaction_status;
            },
            onError: function(result) {
                window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id) ?>&transaction_status=error';
            },
            onClose: function() {
                alert('Anda menutup popup tanpa menyelesaikan pembayaran. Silakan coba lagi atau pilih metode pembayaran lain.');
            }
        };
        
        // Add payment type to options
        if (paymentType) {
            options.enabledPayments = [paymentType];
            
            // For bank transfer, specify the bank
            if (paymentType === 'bank_transfer' && bankType) {
                options.bankTransfer = {
                    bank: bankType
                };
            }
        }
        
        // Call Snap with token and options
        snap.pay('<?= $snap_token ?>', options);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Set up the main Midtrans button
        document.getElementById('pay-button').onclick = function() {
            snap.pay('<?= $snap_token ?>', {
                onSuccess: function(result) {
                    window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id) ?>&transaction_status=' + result.transaction_status;
                },
                onPending: function(result) {
                    window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id) ?>&transaction_status=' + result.transaction_status;
                },
                onError: function(result) {
                    window.location.href = '<?= base_url('midtrans/finish?order_id=' . $order_id) ?>&transaction_status=error';
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran. Silakan coba lagi atau pilih metode pembayaran lain.');
                }
            });
        };
    });
</script>

