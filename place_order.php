<?php
session_start();
require_once 'api/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_SESSION['cart']) || !isset($_COOKIE['customer_name'])) {
    header("Location: index.php");
    exit();
}

$conn->begin_transaction();

try {
    // 1. Kumpulkan data
    $customer_name = $_COOKIE['customer_name'];
    $customer_phone = $_COOKIE['customer_phone'];
    $customer_address = $_COOKIE['customer_address'];
    $payment_method = $_POST['payment_method'];
    $cart = $_SESSION['cart'];
    $total_amount = 0;

    foreach ($cart as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // 2. Masukkan ke tabel `orders`
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, total_amount, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $customer_name, $customer_phone, $customer_address, $total_amount, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // 3. Masukkan setiap item ke tabel `order_items`
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $product_id => $item) {
        $stmt_items->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
        $stmt_items->execute();
    }

    // 4. Jika semua berhasil, commit transaksi
    $conn->commit();

    // 5. Kosongkan keranjang dan arahkan ke halaman sukses
    unset($_SESSION['cart']);
    header("Location: track.php?order_id=" . $order_id);
    exit();

} catch (Exception $e) {
    // Jika ada error, rollback semua perubahan database
    $conn->rollback();
    // Bisa ditambahkan logging error di sini
    die("Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.");
}

?>