<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

// Pastikan hanya menerima POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$order_id = isset($data['order_id']) ? $data['order_id'] : null;
$status = isset($data['status']) ? $data['status'] : null;

if (!$order_id || !$status) {
    echo json_encode(['success' => false, 'message' => 'Order ID and status are required.']);
    exit;
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update order status: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>