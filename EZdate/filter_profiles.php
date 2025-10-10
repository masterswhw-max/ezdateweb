<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$min_age = intval($_POST['min_age'] ?? 18);
$max_age = intval($_POST['max_age'] ?? 50);

// Get current user's gender
$stmt = $conn->prepare("SELECT gender FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();
$opposite_gender = ($current_user['gender'] == 'Male') ? 'Female' : 'Male';

// Get filtered potential matches
$sql = "SELECT u.id, u.name, u.age, u.bio, u.profile_picture 
        FROM users u 
        WHERE u.id != ? 
        AND u.gender = ?
        AND u.age BETWEEN ? AND ?
        AND u.id NOT IN (SELECT liked_user_id FROM likes WHERE user_id = ?)
        ORDER BY RAND() 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isiii", $user_id, $opposite_gender, $min_age, $max_age, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$potential_match = $result->fetch_assoc();

if ($potential_match): ?>
    <h2>💕 Discover Your Perfect Match 💕</h2>
    
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
        <h3>No profiles match your filters!</h3>
        <p>Try adjusting your age range or check back later.</p>
        <a href="matches.php" class="btn">View Your Matches</a>
    </div>
<?php endif; ?>
