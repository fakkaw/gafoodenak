<?php
session_start();
if (empty($_SESSION['cart']) || !isset($_COOKIE['customer_name'])) {
    header("Location: index.php");
    exit();
}

$page_title = "Checkout";
include 'templates/header.php';

$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<section class="checkout-page">
    <h2>Konfirmasi Pesanan Anda</h2>
    
    <div class="order-summary">
        <h3>Ringkasan Pesanan</h3>
        <ul>
            <?php foreach ($cart as $item): ?>
                <li><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></li>
            <?php endforeach; ?>
        </ul>
        <h4>Total: Rp <?php echo number_format($total, 2, ',', '.'); ?></h4>
    </div>

    <div class="customer-details">
        <h3>Detail Pengiriman</h3>
        <p><strong>Nama:</strong> <?php echo htmlspecialchars($_COOKIE['customer_name']); ?></p>
        <p><strong>Telepon:</strong> <?php echo htmlspecialchars($_COOKIE['customer_phone']); ?></p>
        <p><strong>Alamat:</strong> <?php echo htmlspecialchars($_COOKIE['customer_address']); ?></p>
    </div>

    <form action="place_order.php" method="POST" class="payment-form">
        <h3>Pilih Metode Pembayaran</h3>
        <div class="form-group">
            <select name="payment_method" id="payment_method" required onchange="showPaymentOptions()">
                <option value="">Pilih Metode Pembayaran</option>
                <option value="COD (Cash on Delivery)">COD (Cash on Delivery)</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        <div id="bank-transfer-options" style="display: none;" class="form-group mt-3">
            <label>Pilih Bank:</label><br>
            <div class="payment-options-grid">
                <label class="payment-option-item" style="background-color: #f2f2f2; padding: 10px; border-radius: 5px;">
                    <input type="radio" name="bank_name" value="BRI">
                    <img src="assets/bri_logo.png" alt="BRI" style="width: 80px; height: auto;"><br>
                </label>
                <label class="payment-option-item" style="background-color: #f2f2f2; padding: 10px; border-radius: 5px;">
                    <input type="radio" name="bank_name" value="MANDIRI">
                    <img src="assets/mandiri_logo.png" alt="MANDIRI" style="width: 80px; height: auto;"><br>
                </label>
                <label class="payment-option-item" style="background-color: #f2f2f2; padding: 10px; border-radius: 5px;">
                    <input type="radio" name="bank_name" value="BCA">
                    <img src="assets/bca_logo.png" alt="BCA" style="width: 80px; height: auto;"><br>
                </label>
                <label class="payment-option-item" style="background-color: #f2f2f2; padding: 10px; border-radius: 5px;">
                    <input type="radio" name="bank_name" value="BNI">
                    <img src="assets/bni_logo.png" alt="BNI" style="width: 80px; height: auto;"><br>
                </label>
            </div>
        </div>

        <div id="e-wallet-options" style="display: none;" class="form-group mt-3">
            <label>Pilih E-Wallet:</label><br>
            <div class="payment-options-grid">
                <label class="payment-option-item" style="background-color: #f2f2f2; padding: 10px; border-radius: 5px;">
                    <input type="radio" name="e_wallet_type" value="OVO">
                    <img src="assets/ovo_logo.png" alt="OVO" style="width: 80px; height: auto;"><br>
                </label>
                <label class="payment-option-item" style="background-color: #f2f2f2; padding: 10px; border-radius: 5px;">
                    <input type="radio" name="e_wallet_type" value="GOPAY">
                    <img src="assets/gopay_logo.png" alt="GOPAY" style="width: 80px; height: auto;"><br>
                </label>
            </div>
        </div>

        <div id="qris-details" style="display: none;" class="form-group mt-3">
            <h4>Pembayaran via QRIS</h4>
            <p>Silakan scan QR Code di bawah ini untuk menyelesaikan pembayaran.</p>
            <img src="assets/qris_logo.png" alt="QRIS Code" style="max-width: 200px; border: 1px solid #ccc; padding: 10px;">
            <p>Jumlah yang harus dibayar: <strong>Rp <?php echo number_format($total, 2, ',', '.'); ?></strong></p>
            <p>Setelah pembayaran, mohon konfirmasi melalui WhatsApp atau email kami.</p>
        </div>
        <button type="submit" class="btn-primary">Buat Pesanan</button>
    </form>
</section>

<?php include 'templates/footer.php'; ?>

<script>
    function showPaymentOptions() {
        var paymentMethod = document.getElementById('payment_method').value;
        document.getElementById('bank-transfer-options').style.display = 'none';
        document.getElementById('e-wallet-options').style.display = 'none';
        document.getElementById('qris-details').style.display = 'none';

        if (paymentMethod === 'Transfer Bank') {
            document.getElementById('bank-transfer-options').style.display = 'block';
        } else if (paymentMethod === 'E-Wallet') {
            document.getElementById('e-wallet-options').style.display = 'block';
        } else if (paymentMethod === 'QRIS') {
            document.getElementById('qris-details').style.display = 'block';
        }
    }

    // Call the function on page load to set initial state if a method is pre-selected
    document.addEventListener('DOMContentLoaded', showPaymentOptions);
</script>