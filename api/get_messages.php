<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required.']);
    exit;
}

$stmt = $conn->prepare("SELECT sender, message, timestamp FROM chat_messages WHERE order_id = ? ORDER BY timestamp ASC");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'messages' => $messages]);
?>