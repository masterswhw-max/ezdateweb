<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$chat_user_id = (int)$_GET['user_id'];

// Verify they are matched
$stmt = $conn->prepare("SELECT id FROM matches WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
$user1 = min($user_id, $chat_user_id);
$user2 = max($user_id, $chat_user_id);
$stmt->bind_param("iiii", $user1, $user2, $user1, $user2);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    exit('Not matched');
}

// Get messages
$stmt = $conn->prepare("SELECT m.*, u.name FROM messages m 
                       JOIN users u ON u.id = m.sender_id 
                       WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
                       ORDER BY m.created_at ASC");
$stmt->bind_param("iiii", $user_id, $chat_user_id, $chat_user_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();

if ($messages->num_rows == 0) {
    echo '<div class="no-messages">No messages yet. Start the conversation!</div>';
} else {
    while ($message = $messages->fetch_assoc()) {
        $messageClass = $message['sender_id'] == $user_id ? 'sent' : 'received';
        echo '<div class="message ' . $messageClass . '">';
        echo '<p>' . htmlspecialchars($message['message']) . '</p>';
        echo '<span class="time">' . date('M j, g:i A', strtotime($message['created_at'])) . '</span>';
        echo '</div>';
    }
}
?>