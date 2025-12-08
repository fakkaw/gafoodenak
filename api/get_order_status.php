<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required.']);
    exit;
}

$stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo json_encode(['success' => true, 'status' => $order['status']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found.']);
}

$stmt->close();
$conn->close();
?>