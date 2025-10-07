<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZDate - Find Your Perfect Match</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <script>
    document.addEventListener('mousemove', function(e) {
        const x = e.clientX / window.innerWidth * 100;
        const y = e.clientY / window.innerHeight * 100;
        
        const overlay = document.querySelector('body::before') || document.body;
        document.body.style.setProperty('--mouse-x', x + '%');
        document.body.style.setProperty('--mouse-y', y + '%');
        
        document.body.style.background = `
            radial-gradient(circle at ${x}% ${y}%, 
                rgba(255,107,107,0.08) 0%, 
                rgba(238,90,111,0.04) 30%, 
                rgba(214,51,132,0.02) 60%, 
                transparent 100%
            ), #f8f9fa`;
    });
    </script>
    <nav class="navbar">
        <div class="nav-container">
            <h1 class="logo">EZDate</h1>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="swipe.php">Discover</a></li>
                    <li><a href="matches.php">Matches</a></li>
                    <li><a href="edit_profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>