<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include 'header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    
    // Debug info removed for clean interface
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            chmod($target_dir, 0777); // Ensure proper permissions
        } else {
            $message = '<div class="error">Failed to create uploads directory</div>';
        }
    }
    
    // Check if directory is writable
    if (!is_writable($target_dir)) {
        $message = '<div class="error">Uploads directory is not writable. Please check permissions.</div>';
    }
    
    $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check for upload errors first
    if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        switch ($_FILES['profile_picture']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $message = '<div class="error">File too large (server limit)</div>';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = '<div class="error">File too large (form limit)</div>';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = '<div class="error">File upload incomplete</div>';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = '<div class="error">No file selected</div>';
                break;
            default:
                $message = '<div class="error">Upload error: ' . $_FILES['profile_picture']['error'] . '</div>';
        }
    } else {
        // Check if image file is valid
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if($check !== false) {
        // Check file size (5MB max)
        if ($_FILES["profile_picture"]["size"] <= 5000000) {
            // Allow certain file formats
            if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "jpeg" || $file_extension == "gif") {
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    chmod($target_file, 0644); // Set proper file permissions
                    // Update database
                    $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    $stmt->bind_param("si", $new_filename, $user_id);
                    
                    if ($stmt->execute()) {
                        $message = '<div class="success">Profile picture updated successfully!</div>';
                    } else {
                        $message = '<div class="error">Database update failed.</div>';
                    }
                } else {
                    $message = '<div class="error">Sorry, there was an error uploading your file.</div>';
                }
            } else {
                $message = '<div class="error">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
            }
        } else {
            $message = '<div class="error">Sorry, your file is too large. Max 5MB allowed.</div>';
        }
        } else {
            $message = '<div class="error">File is not an image.</div>';
        }
    }
}

// Get current profile picture
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<div class="container">
    <div class="form-container">
        <h2>Upload Profile Picture</h2>
        <?php echo $message; ?>
        
        <div class="current-photo">
            <h3>Current Photo:</h3>
            <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                 alt="Current Profile Picture" class="profile-preview" 
                 onerror="this.src='uploads/default-avatar.jpg'">
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_picture">Choose new profile picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                <small>Max file size: 5MB. Formats: JPG, PNG, GIF</small>
            </div>
            
            <button type="submit" class="btn">Upload Photo</button>
        </form>
        
        <p><a href="edit_profile.php">← Back to Edit Profile</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>