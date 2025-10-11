<?php include 'header.php'; ?>

<div class="container">
    
    <div class="glass-container">
        <h1 class="text-center mb-4">❓ Frequently Asked Questions</h1>
        
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        💕 How does the matching system work?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        EZDate shows you profiles of the opposite gender. Swipe right to like, left to pass. When both users like each other, it's a match! You can then start chatting.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        🔐 Is my data secure?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes! We use password hashing, prepared statements to prevent SQL injection, and secure file uploads. Your privacy and security are our top priorities.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        📸 How do I upload a profile picture?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Go to your profile page and click "Upload Photo". We accept JPG, PNG, and GIF files up to 5MB. Your photo will be automatically resized for optimal display.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        💬 Can I message anyone?
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can only message users you've matched with. This ensures a safe and comfortable environment for everyone.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                        🔄 Can I undo a swipe?
                    </button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Currently, swipes are final. Take your time to review each profile before making your decision!
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                        📱 Is EZDate mobile-friendly?
                    </button>
                </h2>
                <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Absolutely! EZDate is fully responsive and works perfectly on phones, tablets, and desktops. The swiping interface is optimized for touch screens.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                        ⚙️ How do I edit my profile?
                    </button>
                </h2>
                <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Click on your profile in the navigation menu, then select "Edit Profile". You can update your bio, age, and other information anytime.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                        🚫 How do I report inappropriate behavior?
                    </button>
                </h2>
                <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Use our contact form to report any inappropriate behavior. We take all reports seriously and will investigate promptly.
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <p>Still have questions? <a href="contact.php" class="text-decoration-none" style="color: #ff6b6b;">Contact us</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
