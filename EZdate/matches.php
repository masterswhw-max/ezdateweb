<?php
include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's matches
$sql = "SELECT u.id, u.name, u.age, u.profile_picture, m.created_at
        FROM matches m
        JOIN users u ON (u.id = m.user1_id OR u.id = m.user2_id)
        WHERE (m.user1_id = ? OR m.user2_id = ?) AND u.id != ?
        ORDER BY m.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <div class="matches-container">
        <h2>💖 Your Love Connections 💖</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="matches-grid">
                <?php while ($match = $result->fetch_assoc()): ?>
                    <div class="match-card">
                        <img src="uploads/<?php echo htmlspecialchars($match['profile_picture']); ?>" 
                             alt="Profile Picture" onerror="this.src='uploads/default-avatar.jpg'">
                        <h4><?php echo htmlspecialchars($match['name']); ?>, <?php echo $match['age']; ?></h4>
                        <p>Matched on <?php echo date('M j', strtotime($match['created_at'])); ?></p>
                        <a href="profile.php?id=<?php echo $match['id']; ?>" class="btn-secondary">View Profile</a>
                        <a href="chat.php?user_id=<?php echo $match['id']; ?>" class="btn">Message</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-matches">
                <h3>No matches yet!</h3>
                <p>Start swiping to find your perfect match.</p>
                <a href="swipe.php" class="btn">Start Swiping</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>