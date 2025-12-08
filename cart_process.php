<?php
session_start();
require_once 'api/db_connect.php';

// Set header untuk respons JSON
header('Content-Type: application/json');

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = ['success' => false, 'message' => '', 'cart_total_items' => 0];

// Fungsi untuk menghitung total item di keranjang
function count_cart_items() {
    $total = 0;
    foreach (($_SESSION['cart'] ?? []) as $item) {
        $total += $item['quantity'];
    }
    return $total;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $action = $_POST['action'] ?? 'add';
    $quantity_to_add = (int)($_POST['quantity'] ?? 1); // Default 1 jika tidak dispesifikasikan

    // Pastikan kuantitas tidak negatif
    if ($quantity_to_add < 0) $quantity_to_add = 0;

    switch ($action) {
        case 'add':
            // Ambil detail produk dari database jika belum ada di keranjang
            if (!isset($_SESSION['cart'][$product_id])) {
                $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    $_SESSION['cart'][$product_id] = [
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => 0 // Akan ditambahkan di bawah
                    ];
                } else {
                    $response['message'] = 'Produk tidak ditemukan.';
                    echo json_encode($response);
                    exit();
                }
            }
            // Tambahkan kuantitas
            $_SESSION['cart'][$product_id]['quantity'] += $quantity_to_add;
            $response['success'] = true;
            $response['message'] = 'Produk berhasil ditambahkan ke keranjang.';
            break;

        case 'remove_one': // Mengurangi 1 item dari keranjang (dari halaman keranjang)
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']--;
                if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$product_id]);
                }
                $response['success'] = true;
                $response['message'] = 'Kuantitas produk berhasil dikurangi.';
            }
            break;

        case 'delete': // Menghapus semua item dari keranjang (dari halaman keranjang)
            if (isset($_SESSION['cart'][$product_id])) {
                unset($_SESSION['cart'][$product_id]);
                $response['success'] = true;
                $response['message'] = 'Produk berhasil dihapus dari keranjang.';
            }
            break;

        default:
            $response['message'] = 'Aksi tidak valid.';
            break;
    }
}

$response['cart_total_items'] = count_cart_items();
echo json_encode($response);
exit();
?>