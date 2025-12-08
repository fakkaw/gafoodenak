<?php
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}
$order_id = htmlspecialchars($_GET['order_id']);
$page_title = "Pesanan Berhasil";
include 'templates/header.php';
?>

<section class="success-page">
    <h2>Terima Kasih!</h2>
    <p>Pesanan Anda telah berhasil kami terima.</p>
    <p>Nomor pesanan Anda adalah: <strong><?php echo $order_id; ?></strong></p>
    <p>Silakan simpan nomor ini untuk melacak status pesanan Anda.</p>
    <a href="track.php?order_id=<?php echo $order_id; ?>" class="btn-primary">Lacak Pesanan Saya</a>
    <a href="menu.php" class="btn-secondary">Kembali ke Menu</a>
</section>

<?php include 'templates/footer.php'; ?>