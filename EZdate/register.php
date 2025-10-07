<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'header.php';
include 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $age = (int)$_POST['age'];
    $bio = trim($_POST['bio']);
    
    // Server-side validation
    if (strlen($name) < 2) {
        $message = '<div class="error">Name must be at least 2 characters</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="error">Please enter a valid email</div>';
    } elseif (strlen($password) < 6) {
        $message = '<div class="error">Password must be at least 6 characters</div>';
    } elseif ($age < 18 || $age > 100) {
        $message = '<div class="error">Age must be between 18 and 100</div>';
    } else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if ($result->num_rows > 0) {
            $message = '<div class="error">Email already registered</div>';
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, gender, age, bio) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssis", $name, $email, $hashed_password, $gender, $age, $bio);
            
            if ($stmt->execute()) {
                $message = '<div class="success">Registration successful! <a href="login.php">Login here</a></div>';
            } else {
                $message = '<div class="error">Registration failed. Please try again.</div>';
            }
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <h2>Create Your Account</h2>
        <?php echo $message; ?>
        
        <form id="registerForm" method="POST">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label>Gender:</label>
                <div class="radio-group">
                    <input type="radio" id="male" name="gender" value="Male" required>
                    <label for="male">Male</label>
                    <input type="radio" id="female" name="gender" value="Female" required>
                    <label for="female">Female</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" min="18" max="100" required>
            </div>
            
            <div class="form-group">
                <label for="bio">Short Bio:</label>
                <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."></textarea>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>