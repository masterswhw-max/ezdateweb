<?php
include 'header.php';
include 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="error">Please enter a valid email</div>';
    } elseif (empty($password)) {
        $message = '<div class="error">Password is required</div>';
    } else {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $email;
                header('Location: index.php');
                exit();
            } else {
                $message = '<div class="error">Invalid email or password</div>';
            }
        } else {
            $message = '<div class="error">Invalid email or password</div>';
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <h2>Login to Your Account</h2>
        <?php echo $message; ?>
        
        <form id="loginForm" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>