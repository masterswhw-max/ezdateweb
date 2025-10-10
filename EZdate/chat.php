<?php
include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    header('Location: matches.php');
    exit();
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
    header('Location: matches.php');
    exit();
}

// Get chat partner info
$stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $chat_user_id);
$stmt->execute();
$result = $stmt->get_result();
$chat_user = $result->fetch_assoc();

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $chat_user_id, $message);
    $stmt->execute();
}
// Get messages
$stmt = $conn->prepare("SELECT m.*, u.name FROM messages m 
                       JOIN users u ON u.id = m.sender_id 
                       WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
                       ORDER BY m.created_at ASC");
$stmt->bind_param("iiii", $user_id, $chat_user_id, $chat_user_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<div class="container">
    <div class="chat-container">
        <div class="chat-header">
            <img src="uploads/<?php echo htmlspecialchars($chat_user['profile_picture']); ?>" 
                 alt="Profile Picture" onerror="this.src='uploads/default-avatar.jpg'">
            <h3><?php echo htmlspecialchars($chat_user['name']); ?></h3>
            <a href="matches.php" class="btn-back">← Back to Matches</a>
        </div>
        
        <div class="messages-container" id="messages">
            <!-- Messages will be loaded here -->
        </div>
        
        <form class="message-form" id="messageForm">
            <input type="text" id="messageInput" placeholder="Type a message..." required>
            <button type="submit" class="btn">Send</button>
        </form>
    </div>
</div>

<script>
const chatUserId = <?php echo $chat_user_id; ?>;
const currentUserId = <?php echo $user_id; ?>;

// Load messages with error handling
let lastMessageCount = 0;

function loadMessages() {
    fetch(`load_messages.php?user_id=${chatUserId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.text();
        })
        .then(data => {
            const messagesDiv = document.getElementById('messages');
            // Only update if content changed
            if (messagesDiv.innerHTML !== data) {
                messagesDiv.innerHTML = data;
                scrollToBottom();
            }
        })
        .catch(error => {
            console.log('Message load failed:', error);
        });
}

// Send message
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (message) {
        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${chatUserId}&message=${encodeURIComponent(message)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages(); // Only refresh after sending
            }
        });
    }
});

// Auto-scroll to bottom
function scrollToBottom() {
    const messages = document.getElementById('messages');
    messages.scrollTop = messages.scrollHeight;
}

// Load messages and enable auto-refresh
loadMessages();
setInterval(loadMessages, 3000);
</script>

<?php include 'footer.php'; ?>
