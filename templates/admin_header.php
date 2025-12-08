<?php
session_start();
// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Gafood'; ?></title>
    <link rel="stylesheet" href="../assets/admin_style.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Gafood Admin</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="icon-dashboard"></i> Dashboard
                </a>
                <a href="menu_management.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'menu_management.php') ? 'active' : ''; ?>">
                    <i class="icon-menu"></i> Manajemen Menu
                </a>
                <a href="order_management.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'order_management.php') ? 'active' : ''; ?>">
                    <i class="icon-orders"></i> Manajemen Pesanan
                </a>
                <a href="contact_management.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'contact_management.php') ? 'active' : ''; ?>">
                    <i class="icon-contact"></i> Manajemen Kontak
                </a>
                <a href="logout.php" class="nav-item logout-btn">
                    <i class="icon-logout"></i> Logout
                </a>
            </nav>
        </aside>
        <main class="admin-content">
            <header class="admin-topbar">
                <h3><?php echo $page_title ?? 'Dashboard'; ?></h3>
                <div class="user-info">
                    <span>Halo, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </header>
            <div class="content-area">