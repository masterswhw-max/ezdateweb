<?php
include 'db_connect.php';

// Sample users data
$users = [
    ['Sarah Johnson', 'sarah@example.com', 'Female', 25, 'Love hiking and coffee dates!'],
    ['Mike Chen', 'mike@example.com', 'Male', 28, 'Photographer and travel enthusiast'],
    ['Emma Davis', 'emma@example.com', 'Female', 24, 'Yoga instructor and book lover'],
    ['Alex Rodriguez', 'alex@example.com', 'Male', 30, 'Chef who loves cooking for others'],
    ['Lisa Wang', 'lisa@example.com', 'Female', 27, 'Software developer and gamer'],
    ['David Brown', 'david@example.com', 'Male', 26, 'Musician and dog lover']
];

$password = password_hash('password123', PASSWORD_DEFAULT);

echo "<h2>Adding Sample Users...</h2>";

foreach ($users as $user) {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, gender, age, bio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $user[0], $user[1], $password, $user[2], $user[3], $user[4]);
    
    if ($stmt->execute()) {
        echo "✓ Added: " . $user[0] . "<br>";
    } else {
        echo "✗ Failed to add: " . $user[0] . "<br>";
    }
}

// Add some sample likes and matches
echo "<h2>Adding Sample Likes and Matches...</h2>";

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
        echo "✓ Match created between user " . $match[0] . " and " . $match[1] . "<br>";
    }
}

// Add sample messages
echo "<h2>Adding Sample Messages...</h2>";

$messages = [
    [1, 2, "Hey! Nice to match with you! 😊"],
    [2, 1, "Thanks! I love your profile. How's your day going?"],
    [1, 2, "Going great! Want to grab coffee sometime?"],
    [1, 4, "Hi Alex! Your cooking photos look amazing!"],
    [4, 1, "Thank you! I'd love to cook for you sometime 👨‍🍳"]
];

foreach ($messages as $msg) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $msg[0], $msg[1], $msg[2]);
    
    if ($stmt->execute()) {
        echo "✓ Message added<br>";
    }
}

echo "<h2>Sample Data Added Successfully!</h2>";
echo "<p>You can now:</p>";
echo "<ul>";
echo "<li>Login with any email above using password: <strong>password123</strong></li>";
echo "<li>Test the search functionality</li>";
echo "<li>View matches in profiles</li>";
echo "</ul>";
echo "<a href='index.php'>Go to EZDate</a>";

$conn->close();
?>