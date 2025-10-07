<?php
include 'header.php';
include 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $msg = trim($_POST['message']);
    
    if (strlen($name) < 2) {
        $message = '<div class="error">Name is required</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="error">Please enter a valid email</div>';
    } elseif (strlen($subject) < 5) {
        $message = '<div class="error">Subject must be at least 5 characters</div>';
    } elseif (strlen($msg) < 10) {
        $message = '<div class="error">Message must be at least 10 characters</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $msg);
        
        if ($stmt->execute()) {
            $message = '<div class="success">💕 Thank you for your message! We will get back to you soon. 💕</div>';
            
            // Optional: Send email notification (uncomment if needed)
            // mail('admin@ezdate.com', 'New Contact Message: ' . $subject, $msg, 'From: ' . $email);
            
            // Clear form data after successful submission
            $name = $email = $subject = $msg = '';
        } else {
            $message = '<div class="error">Failed to send message. Please try again.</div>';
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <h2>💕 Contact Us 💕</h2>
        <p>✨ Have questions or feedback? We'd love to hear from you! ✨</p>
        
        <?php echo $message; ?>
        
        <form id="contactForm" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="6" required><?php echo isset($msg) ? htmlspecialchars($msg) : ''; ?></textarea>
            </div>
            
            <button type="submit" class="btn">💌 Send Message 💌</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>