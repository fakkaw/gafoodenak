<?php
session_start();
$page_title = "Keranjang Belanja";
include 'templates/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<section class="cart-page">
    <h2>Keranjang Belanja Anda</h2>
    <?php if (empty($cart)): ?>
        <p>Keranjang Anda kosong. Yuk, mulai pesan!</p>
        <a href="index.php" class="btn-primary">Lihat Menu</a>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>Rp <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>Rp <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                    <td class="actions">
                        <form action="cart_process.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-summary">
            <h3>Total Belanja: Rp <?php echo number_format($total, 2, ',', '.'); ?></h3>
            <a href="checkout.php" class="btn-primary">Lanjut ke Checkout</a>
        </div>
    <?php endif; ?>
</section>

<?php include 'templates/footer.php'; ?>