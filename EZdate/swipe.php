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
    <div class="filter-section" style="background: rgba(255, 107, 107, 0.1); border: 2px solid rgba(255, 107, 107, 0.3); border-radius: 20px; padding: 25px; margin-bottom: 30px; box-shadow: 0 8px 32px rgba(255, 107, 107, 0.2);">
        <form id="filterForm" class="filter-form" style="display: flex; align-items: center; justify-content: center; gap: 25px; flex-wrap: wrap;">
            <div class="filter-group" style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <label for="min_age" style="color: #ff6b6b; font-weight: 700; font-size: 16px;">Min Age:</label>
                <input type="number" id="min_age" name="min_age" value="18" min="18" max="100" style="width: 90px; padding: 12px; border: 2px solid rgba(255, 107, 107, 0.4); border-radius: 15px; background: white; text-align: center; font-weight: 600; font-size: 16px;">
            </div>
            <div class="filter-group" style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <label for="max_age" style="color: #ff6b6b; font-weight: 700; font-size: 16px;">Max Age:</label>
                <input type="number" id="max_age" name="max_age" value="50" min="18" max="100" style="width: 90px; padding: 12px; border: 2px solid rgba(255, 107, 107, 0.4); border-radius: 15px; background: white; text-align: center; font-weight: 600; font-size: 16px;">
            </div>
            <button type="submit" class="btn-filter" style="background: linear-gradient(135deg, #ff6b6b, #ee5a6f); color: white; border: none; padding: 12px 25px; border-radius: 25px; font-weight: 700; cursor: pointer; font-size: 16px; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);">🔍 Filter</button>
        </form>
    </div>
    
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
        // Load next profile with current filters instead of page reload
        loadFilteredProfiles();
    });
}

function loadFilteredProfiles() {
    const minAge = $('#min_age').val() || 18;
    const maxAge = $('#max_age').val() || 50;
    
    $.ajax({
        url: 'filter_profiles.php',
        type: 'POST',
        data: {
            min_age: minAge,
            max_age: maxAge
        },
        success: function(response) {
            $('.swipe-container').html(response);
        },
        error: function() {
            $('.swipe-container').html('<div class="error">Error loading profiles. Please try again.</div>');
        }
    });
}
</script>

<?php include 'footer.php'; ?>
