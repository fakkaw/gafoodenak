<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Gafood'; ?></title>
    <link rel="stylesheet" href="assets/style.css?v=1.1">
</head>
<body>
    <header class="top-nav">
        <div class="container">
            <a href="index.php" class="logo">Gafood</a>
            <button class="hamburger" id="hamburger-menu" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav id="main-nav">
                <a href="admin/index.php" class="login-link">Login</a>
                <a href="index.php">Beranda</a>
                <a href="menu.php">Menu</a>
                <a href="cart.php">Keranjang <span id="cart-item-count" class="cart-count">(<?php echo $cart_count; ?>)</span></a>
                <a href="track.php">Lacak Pesanan</a>
                <a href="contact.php">Kontak</a>
            </nav>
        </div>
    </header>
    <main class="container">
