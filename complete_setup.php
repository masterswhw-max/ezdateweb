<?php
echo "<h1>🚀 EZDate Complete Setup</h1>";
echo "<p>Setting up your dating website...</p>";

$servername = "localhost";
$username = "root";
$password = "";

// Step 1: Create database connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Step 2: Create database
$sql = "CREATE DATABASE IF NOT EXISTS ezdate_db";
if ($conn->query($sql) === TRUE) {
    echo "✅ Database 'ezdate_db' created successfully<br>";
} else {
    echo "❌ Error creating database: " . $conn->error . "<br>";
}

// Step 3: Select database
$conn->select_db("ezdate_db");

// Step 4: Create all tables
echo "<h2>📊 Creating Tables...</h2>";

// Users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    age INT NOT NULL,
    bio TEXT,
    profile_picture VARCHAR(255) DEFAULT 'default-avatar.jpg'
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ Users table created<br>";
} else {
    echo "❌ Error creating users table: " . $conn->error . "<br>";
}

// Likes table
$sql = "CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    liked_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (liked_user_id) REFERENCES users(id),
    UNIQUE KEY unique_like (user_id, liked_user_id)
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ Likes table created<br>";
} else {
    echo "❌ Error creating likes table: " . $conn->error . "<br>";
}

// Matches table
$sql = "CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES users(id),
    FOREIGN KEY (user2_id) REFERENCES users(id),
    UNIQUE KEY unique_match (user1_id, user2_id)
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ Matches table created<br>";
} else {
    echo "❌ Error creating matches table: " . $conn->error . "<br>";
}

// Messages table
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ Messages table created<br>";
} else {
    echo "❌ Error creating messages table: " . $conn->error . "<br>";
}

// Contact messages table
$sql = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ Contact messages table created<br>";
} else {
    echo "❌ Error creating contact messages table: " . $conn->error . "<br>";
}

// Step 5: Create uploads directory
echo "<h2>📁 Creating Directories...</h2>";
if (!file_exists('uploads')) {
    if (mkdir('uploads', 0777, true)) {
        chmod('uploads', 0777);
        echo "✅ Uploads directory created<br>";
    } else {
        echo "❌ Failed to create uploads directory<br>";
    }
} else {
    echo "✅ Uploads directory already exists<br>";
}

// Step 6: Create default avatar
echo "<h2>🖼️ Creating Default Avatar...</h2>";
$width = 200;
$height = 200;
$image = imagecreate($width, $height);
$bg_color = imagecolorallocate($image, 200, 200, 200);
$text_color = imagecolorallocate($image, 100, 100, 100);
imagefill($image, 0, 0, $bg_color);
$text = "No Photo";
$font_size = 3;
$text_width = imagefontwidth($font_size) * strlen($text);
$text_height = imagefontheight($font_size);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;
imagestring($image, $font_size, $x, $y, $text, $text_color);
imagejpeg($image, 'uploads/default-avatar.jpg', 90);
imagedestroy($image);
echo "✅ Default avatar created<br>";

// Step 7: Add sample users
echo "<h2>👥 Adding Sample Users...</h2>";
$users = [
    ['Sarah Johnson', 'sarah@example.com', 'Female', 25, 'Love hiking and coffee dates! ☕🥾'],
    ['Mike Chen', 'mike@example.com', 'Male', 28, 'Photographer and travel enthusiast 📸✈️'],
    ['Emma Davis', 'emma@example.com', 'Female', 24, 'Yoga instructor and book lover 🧘📚'],
    ['Alex Rodriguez', 'alex@example.com', 'Male', 30, 'Chef who loves cooking for others 👨‍🍳❤️'],
    ['Lisa Wang', 'lisa@example.com', 'Female', 27, 'Software developer and gamer 💻🎮'],
    ['David Brown', 'david@example.com', 'Male', 26, 'Musician and dog lover 🎵🐕']
];

$password = password_hash('password123', PASSWORD_DEFAULT);

foreach ($users as $user) {
    $stmt = $conn->prepare("INSERT IGNORE INTO users (name, email, password, gender, age, bio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $user[0], $user[1], $password, $user[2], $user[3], $user[4]);
    
    if ($stmt->execute()) {
        echo "✅ Added: " . $user[0] . "<br>";
    } else {
        echo "⚠️ User " . $user[0] . " may already exist<br>";
    }
}

// Step 8: Add sample likes and matches
echo "<h2>💕 Creating Sample Likes and Matches...</h2>";
$likes = [
    [1, 2], [2, 1], // Mutual like = match
    [1, 4], [4, 1], // Mutual like = match  
    [2, 3], [3, 2], // Mutual like = match
    [3, 6], [4, 5], [5, 6] // One-way likes
];

foreach ($likes as $like) {
    $stmt = $conn->prepare("INSERT IGNORE INTO likes (user_id, liked_user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $like[0], $like[1]);
    $stmt->execute();
}

// Create matches from mutual likes
$matches = [
    [1, 2], [1, 4], [2, 3]
];

foreach ($matches as $match) {
    $user1 = min($match[0], $match[1]);
    $user2 = max($match[0], $match[1]);
    $stmt = $conn->prepare("INSERT IGNORE INTO matches (user1_id, user2_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user1, $user2);
    
    if ($stmt->execute()) {
        echo "✅ Match created between user " . $match[0] . " and " . $match[1] . "<br>";
    }
}

// Step 9: Add sample messages
echo "<h2>💬 Adding Sample Messages...</h2>";
$messages = [
    [1, 2, "Hey! Nice to match with you! 😊"],
    [2, 1, "Thanks! I love your profile. How's your day going?"],
    [1, 2, "Going great! Want to grab coffee sometime? ☕"],
    [1, 4, "Hi Alex! Your cooking photos look amazing! 🍳"],
    [4, 1, "Thank you! I'd love to cook for you sometime 👨‍🍳"],
    [2, 3, "Your yoga posts are so inspiring! 🧘‍♀️"],
    [3, 2, "Thank you! Maybe we can do a session together? 💕"]
];

foreach ($messages as $msg) {
    $stmt = $conn->prepare("INSERT IGNORE INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $msg[0], $msg[1], $msg[2]);
    
    if ($stmt->execute()) {
        echo "✅ Message added<br>";
    }
}

$conn->close();

echo "<h2>🎉 Setup Complete!</h2>";
echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>✅ Everything is ready!</h3>";
echo "<p><strong>Test Accounts (Password: password123):</strong></p>";
echo "<ul>";
echo "<li>📧 sarah@example.com - Sarah, 25, Female</li>";
echo "<li>📧 mike@example.com - Mike, 28, Male</li>";
echo "<li>📧 emma@example.com - Emma, 24, Female</li>";
echo "<li>📧 alex@example.com - Alex, 30, Male</li>";
echo "<li>📧 lisa@example.com - Lisa, 27, Female</li>";
echo "<li>📧 david@example.com - David, 26, Male</li>";
echo "</ul>";
echo "<p><strong>Features Ready:</strong></p>";
echo "<ul>";
echo "<li>💕 User registration and login</li>";
echo "<li>🔄 Swipe and match system</li>";
echo "<li>💬 Real-time messaging</li>";
echo "<li>📸 Profile photo uploads</li>";
echo "<li>💖 Romantic UI with glass-morphism</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>🚀 <a href='index.php' style='color: #ff6b6b; text-decoration: none; font-size: 18px;'>Launch EZDate Now!</a></strong></p>";
echo "<p><em>Delete this file after setup for security.</em></p>";
?>