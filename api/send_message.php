<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

$order_id = isset($data['order_id']) ? $data['order_id'] : null;
$sender = isset($data['sender']) ? $data['sender'] : null;
$message = isset($data['message']) ? $data['message'] : null;

if (!$order_id || !$sender || !$message) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO chat_messages (order_id, sender, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $order_id, $sender, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
}

$stmt->close();
$conn->close();
?>