<?php
include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get current user's gender
$stmt = $conn->prepare("SELECT gender FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();
$opposite_gender = ($current_user['gender'] == 'Male') ? 'Female' : 'Male';

// Get next potential match (opposite gender, not already liked/passed)
$sql = "SELECT u.id, u.name, u.age, u.bio, u.profile_picture 
        FROM users u 
        WHERE u.id != ? 
        AND u.gender = ?
        AND u.id NOT IN (SELECT liked_user_id FROM likes WHERE user_id = ?)
        ORDER BY RAND() 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $user_id, $opposite_gender, $user_id);

// Statement already prepared above
$stmt->execute();
$result = $stmt->get_result();
$potential_match = $result->fetch_assoc();
?>

<div class="container">
    <div class="swipe-container">
        <h2>💕 Discover Your Perfect Match 💕</h2>
        
        <?php if ($potential_match): ?>
            <div class="profile-card-swipe" data-user-id="<?php echo $potential_match['id']; ?>">
                <div class="profile-image">
                    <img src="uploads/<?php echo htmlspecialchars($potential_match['profile_picture']); ?>" 
                         alt="Profile Picture" onerror="this.src='uploads/default-avatar.jpg'">
                </div>
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($potential_match['name']); ?>, <?php echo $potential_match['age']; ?></h3>
                    <p><?php echo htmlspecialchars($potential_match['bio']); ?></p>
                    <a href="profile.php?id=<?php echo $potential_match['id']; ?>" class="btn-view-profile">View Full Profile</a>
                </div>
            </div>
            
            <div class="swipe-buttons">
                <button class="btn-pass" onclick="swipeAction('pass', <?php echo $potential_match['id']; ?>)">❌ Pass</button>
                <button class="btn-like" onclick="swipeAction('like', <?php echo $potential_match['id']; ?>)">❤️ Like</button>
            </div>
        <?php else: ?>
            <div class="no-more-profiles">
                <h3>No more profiles to show!</h3>
                <p>Check back later for new members.</p>
                <a href="matches.php" class="btn">View Your Matches</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function swipeAction(action, userId) {
    fetch('swipe_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=' + action + '&user_id=' + userId
    })
    .then(response => response.json())
    .then(data => {
        if (data.match) {
            alert('🎉 It\'s a match! You can now message each other.');
        }
        location.reload();
    });
}
</script>

<?php include 'footer.php'; ?>