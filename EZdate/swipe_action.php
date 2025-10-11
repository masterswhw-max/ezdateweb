<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'];
$target_user_id = (int)$_POST['user_id'];
$is_match = false;

if ($action == 'like') {
    // Record the like
    $stmt = $conn->prepare("INSERT IGNORE INTO likes (user_id, liked_user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $target_user_id);
    $stmt->execute();
    
    // Check if it's a mutual like (match)
    $stmt = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND liked_user_id = ?");
    $stmt->bind_param("ii", $target_user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // It's a match! Create a match record
        $stmt = $conn->prepare("INSERT IGNORE INTO matches (user1_id, user2_id) VALUES (?, ?)");
        $user1 = min($user_id, $target_user_id);
        $user2 = max($user_id, $target_user_id);
        $stmt->bind_param("ii", $user1, $user2);
        $stmt->execute();
        $is_match = true;
    }
}

echo json_encode(['success' => true, 'match' => $is_match]);
?>
