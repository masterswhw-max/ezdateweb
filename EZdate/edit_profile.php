<?php
include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$user_id = $_SESSION['user_id'];

// Get current user data
$stmt = $conn->prepare("SELECT name, email, gender, age, bio FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $bio = trim($_POST['bio']);
    $new_password = $_POST['new_password'];
    
    if (strlen($name) < 2) {
        $message = '<div class="error">Name must be at least 2 characters</div>';
    } else {
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $message = '<div class="error">Password must be at least 6 characters</div>';
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name = ?, bio = ?, password = ? WHERE id = ?");
                $stmt->bind_param("sssi", $name, $bio, $hashed_password, $user_id);
            }
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, bio = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $bio, $user_id);
        }
        
        if (empty($message) && $stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $user['name'] = $name;
            $user['bio'] = $bio;
            $message = '<div class="success">Profile updated successfully!</div>';
        } elseif (empty($message)) {
            $message = '<div class="error">Update failed. Please try again.</div>';
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <h2>Edit Your Profile</h2>
        <?php echo $message; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email (cannot be changed):</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Gender: <?php echo htmlspecialchars($user['gender']); ?> (cannot be changed)</label>
            </div>
            
            <div class="form-group">
                <label>Age: <?php echo $user['age']; ?> (cannot be changed)</label>
            </div>
            
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password (leave blank to keep current):</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            
            <button type="submit" class="btn">Update Profile</button>
        </form>
        
        <div class="photo-section">
            <h3>Profile Photo</h3>
            <a href="upload_photo.php" class="btn">Upload New Photo</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>