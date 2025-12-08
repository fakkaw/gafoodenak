<?php
session_start();
require_once 'api/db_connect.php';

$page_title = "Menu Makanan";
include 'templates/header.php';
?>

<section class="menu-promo-banner">
    <div class="container">
        <h2>Temukan Kelezatan Favoritmu!</h2>
        <p>Dari hidangan klasik hingga kreasi modern, setiap gigitan adalah petualangan rasa. Pesan sekarang dan rasakan bedanya!</p>
        
    </div>
</section>

<section id="full-menu-list" class="full-menu">
    <h2 class="section-title">Semua Menu Kami</h2>
    <p class="section-subtitle">Pilih hidangan lezat yang akan menemanimu hari ini.</p>
    <div class="product-grid">
        <?php 
        $all_products = $conn->query("SELECT * FROM products WHERE is_available = 1 ORDER BY id DESC");
        while($product = $all_products->fetch_assoc()): 
        ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'assets/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
            <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
            <form action="cart_process.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="action" value="add">
                <div class="quantity-control">
                    <button type="button" class="quantity-btn minus-btn" data-product-id="<?php echo $product['id']; ?>">-</button>
                    <input type="text" name="quantity" value="1" min="1" class="quantity-input" data-product-id="<?php echo $product['id']; ?>" readonly>
                    <button type="button" class="quantity-btn plus-btn" data-product-id="<?php echo $product['id']; ?>">+</button>
                </div>
                <button type="button" class="btn-primary add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">Tambah ke Keranjang</button>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<?php include 'templates/footer.php'; ?>