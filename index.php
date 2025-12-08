<?php
session_start();
require_once 'api/db_connect.php';

// Cek jika pelanggan sudah pernah isi data
if (!isset($_COOKIE['customer_name'])) {
    // Jika belum, tampilkan form data diri (tidak ada perubahan di sini)
    $page_title = "Selamat Datang di Gafood";
    include 'templates/header.php';
    ?>
    <div class="form-container">
        <h2>Isi Data Diri</h2>
        <p>Silakan isi data diri Anda untuk melanjutkan pemesanan.</p>
        <form action="save_customer.php" method="POST">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Simpan & Lanjut Memesan</button>
        </form>
    </div>
    <?php
    include 'templates/footer.php';
    exit();
}

// --- HALAMAN UTAMA BARU YANG LEBIH MENARIK ---
$page_title = "Selamat Datang di Gafood";
include 'templates/header.php';
?>

<!-- 1. Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Lapar? Jangan Ditahan!</h1>
        <p>Pesan makanan favoritmu sekarang dan nikmati promo spesial dari Gafood. Cepat, mudah, dan lezat!</p>
        <a href="menu.php" class="btn-primary btn-large">Lihat Semua Menu</a>
    </div>
</section>

<!-- 2. Bagian Cara Kerja -->
<section class="how-it-works">
    <h2 class="section-title">3 Langkah Mudah</h2>
    <div class="steps-grid">
        <div class="step">
            <div class="step-icon">1</div>
            <h3>Pilih Menu</h3>
            <p>Jelajahi ratusan menu dan pilih yang kamu suka.</p>
        </div>
        <div class="step">
            <div class="step-icon">2</div>
            <h3>Bayar & Lacak</h3>
            <p>Pilih metode pembayaran dan lacak pesananmu.</p>
        </div>
        <div class="step">
            <div class="step-icon">3</div>
            <h3>Nikmati</h3>
            <p>Kurir tiba, saatnya menikmati hidangan lezat!</p>
        </div>
    </div>
</section>

<!-- 3. Bagian Menu Terlaris -->
<section class="featured-products">
    <h2 class="section-title">Menu Terlaris Minggu Ini</h2>
    <div class="product-grid">
        <?php 
        $featured_products = $conn->query("SELECT * FROM products WHERE is_available = 1 ORDER BY RAND() LIMIT 3");
        while($product = $featured_products->fetch_assoc()): 
        ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'assets/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
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