<?php
require_once '../api/db_connect.php';

// Ambil beberapa data untuk ringkasan
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_income = $conn->query("SELECT SUM(total_amount) as sum FROM orders WHERE status = 'Tiba di Tujuan'")->fetch_assoc()['sum'];
$pending_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status != 'Tiba di Tujuan'")->fetch_assoc()['count'];

$page_title = "Dashboard";
include '../templates/admin_header.php';
?>

<section class="dashboard">
    <h2>Ringkasan</h2>
    <div class="summary-cards">
        <div class="card">
            <h3>Total Pesanan</h3>
            <p><?php echo $total_orders; ?></p>
        </div>
        <div class="card">
            <h3>Total Pemasukan</h3>
            <p>Rp <?php echo number_format($total_income ?? 0, 2, ',', '.'); ?></p>
        </div>
        <div class="card">
            <h3>Pesanan Aktif</h3>
            <p><?php echo $pending_orders; ?></p>
        </div>
    </div>
</section>

<?php include '../templates/admin_footer.php'; ?>