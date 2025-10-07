<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$chat_user_id = (int)$_POST['user_id'];
$message = trim($_POST['message']);

if (empty($message)) {
    echo json_encode(['error' => 'Message cannot be empty']);
    exit();
}

// Verify they are matched
$stmt = $conn->prepare("SELECT id FROM matches WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
$user1 = min($user_id, $chat_user_id);
$user2 = max($user_id, $chat_user_id);
$stmt->bind_param("iiii", $user1, $user2, $user1, $user2);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['error' => 'Not matched']);
    exit();
}

// Insert message
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $chat_user_id, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to send message']);
}
?>