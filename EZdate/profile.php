<?php
include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$profile_id = (int)$_GET['id'];
$current_user_id = $_SESSION['user_id'];

// Get profile user info
$stmt = $conn->prepare("SELECT name, gender, age, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: index.php');
    exit();
}

$profile_user = $result->fetch_assoc();

// Check if already liked
$stmt = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND liked_user_id = ?");
$stmt->bind_param("ii", $current_user_id, $profile_id);
$stmt->execute();
$already_liked = $stmt->get_result()->num_rows > 0;

// Check if matched
$stmt = $conn->prepare("SELECT id FROM matches WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
$user1 = min($current_user_id, $profile_id);
$user2 = max($current_user_id, $profile_id);
$stmt->bind_param("iiii", $user1, $user2, $user1, $user2);
$stmt->execute();
$is_matched = $stmt->get_result()->num_rows > 0;
?>

<div class="container">
    <div class="profile-view">
        <div class="profile-header">
            <img src="uploads/<?php echo htmlspecialchars($profile_user['profile_picture']); ?>" 
                 alt="Profile Picture" onerror="this.src='uploads/default-avatar.jpg'">
            <h2><?php echo htmlspecialchars($profile_user['name']); ?>, <?php echo $profile_user['age']; ?></h2>
            <p class="gender"><?php echo htmlspecialchars($profile_user['gender']); ?></p>
        </div>
        
        <div class="profile-bio">
            <h3>About</h3>
            <p><?php echo htmlspecialchars($profile_user['bio']); ?></p>
        </div>
        
        <div class="profile-actions">
            <?php if ($is_matched): ?>
                <a href="chat.php?user_id=<?php echo $profile_id; ?>" class="btn">💬 Message</a>
            <?php elseif ($already_liked): ?>
                <p class="liked-status">❤️ You liked this profile</p>
            <?php else: ?>
                <button onclick="likeProfile(<?php echo $profile_id; ?>)" class="btn">❤️ Like</button>
            <?php endif; ?>
            
            <a href="javascript:history.back()" class="btn-secondary">← Back</a>
        </div>
    </div>
</div>

<script>
function likeProfile(userId) {
    fetch('swipe_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=like&user_id=' + userId
    })
    .then(response => response.json())
    .then(data => {
        if (data.match) {
            alert('🎉 It\'s a match! You can now message each other.');
        } else {
            alert('❤️ Profile liked!');
        }
        location.reload();
    });
}
</script>

<?php include 'footer.php'; ?>