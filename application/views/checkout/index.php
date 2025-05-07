<!-- Shipping Method Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Metode Pengiriman</h3>
    
    <div class="space-y-4">
        <!-- HijauLoka Kurir -->
        <div class="flex items-center p-4 border rounded-lg hover:border-green-500 cursor-pointer">
            <input type="radio" name="kurir" value="hijauloka" id="kurir-hijauloka" class="w-4 h-4 text-green-600" checked>
            <label for="kurir-hijauloka" class="ml-3 flex-grow">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-medium text-gray-900">HijauLoka Kurir</span>
                        <p class="text-sm text-gray-500">Pengiriman dalam 1-2 hari kerja</p>
                    </div>
                    <span class="font-semibold text-green-600">Rp 15.000</span>
                </div>
            </label>
        </div>

        <!-- JNE (Coming Soon) -->
        <div class="flex items-center p-4 border rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
            <input type="radio" name="kurir" value="jne" id="kurir-jne" class="w-4 h-4 text-gray-400" disabled>
            <label for="kurir-jne" class="ml-3 flex-grow">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-medium text-gray-900">JNE</span>
                        <p class="text-sm text-gray-500">Coming Soon</p>
                    </div>
                    <span class="font-semibold text-gray-400">-</span>
                </div>
            </label>
        </div>

        <!-- JNT (Coming Soon) -->
        <div class="flex items-center p-4 border rounded-lg bg-gray-50 cursor-not-allowed opacity-60">
            <input type="radio" name="kurir" value="jnt" id="kurir-jnt" class="w-4 h-4 text-gray-400" disabled>
            <label for="kurir-jnt" class="ml-3 flex-grow">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-medium text-gray-900">JNT</span>
                        <p class="text-sm text-gray-500">Coming Soon</p>
                    </div>
                    <span class="font-semibold text-gray-400">-</span>
                </div>
            </label>
        </div>
    </div>
</div>

<!-- Order Summary Section -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h3>
    
    <div class="space-y-3">
        <div class="flex justify-between text-gray-600">
            <span>Subtotal</span>
            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between text-gray-600">
            <span>Ongkos Kirim</span>
            <span id="shipping-cost">Rp 15.000</span>
        </div>
        <div class="border-t pt-3 flex justify-between font-semibold text-gray-900">
            <span>Total</span>
            <span id="total-amount">Rp <?= number_format($total + 15000, 0, ',', '.') ?></span>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="kurir"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingCost = this.value === 'hijauloka' ? 15000 : 0;
        document.getElementById('shipping-cost').textContent = `Rp ${shippingCost.toLocaleString('id-ID')}`;
        document.getElementById('total-amount').textContent = `Rp ${(<?= $total ?> + shippingCost).toLocaleString('id-ID')}`;
    });
});
</script> 