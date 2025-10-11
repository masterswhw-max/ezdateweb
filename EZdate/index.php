<?php include 'header.php'; ?>

<div class="hero">
    <div class="container">
        <h1>💕 Welcome to EZDate 💕</h1>
        <p>✨ Where hearts connect and love stories begin ✨</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn">💘 Start Your Love Journey 💘</a>
        <?php else: ?>
            <a href="swipe.php" class="btn">💕 Find Your Love 💕</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="form-container">
        <h2>About EZDate</h2>
        <p>EZDate is your premier destination for finding meaningful connections. Our platform makes it easy to discover compatible matches based on your preferences.</p>
        
        <h3>💖 Why Choose EZDate? 💖</h3>
        
        <ul>
            <li>💕 Create your perfect love profile</li>
            <li>🔍 Find your soulmate with smart matching</li>
            <li>💬 Connect hearts through secure messaging</li>
            <li>✨ Express yourself with beautiful profiles</li>
            <li>💘 Real connections, real relationships</li>
        </ul>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
