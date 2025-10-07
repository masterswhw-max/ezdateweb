$(document).ready(function() {
    // Form validation
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Registration form validation
    $('#registerForm').on('submit', function(e) {
        let isValid = true;
        $('.error').remove();

        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();

        if (name.length < 2) {
            $('#name').after('<div class="error">Name must be at least 2 characters</div>');
            isValid = false;
        }

        if (!validateEmail(email)) {
            $('#email').after('<div class="error">Please enter a valid email</div>');
            isValid = false;
        }

        if (password.length < 6) {
            $('#password').after('<div class="error">Password must be at least 6 characters</div>');
            isValid = false;
        }

        if (password !== confirmPassword) {
            $('#confirm_password').after('<div class="error">Passwords do not match</div>');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Login form validation
    $('#loginForm').on('submit', function(e) {
        let isValid = true;
        $('.error').remove();

        const email = $('#email').val().trim();
        const password = $('#password').val();

        if (!validateEmail(email)) {
            $('#email').after('<div class="error">Please enter a valid email</div>');
            isValid = false;
        }

        if (password.length === 0) {
            $('#password').after('<div class="error">Password is required</div>');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Contact form validation
    $('#contactForm').on('submit', function(e) {
        let isValid = true;
        $('.error').remove();

        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const subject = $('#subject').val().trim();
        const message = $('#message').val().trim();

        if (name.length < 2) {
            $('#name').after('<div class="error">Name is required</div>');
            isValid = false;
        }

        if (!validateEmail(email)) {
            $('#email').after('<div class="error">Please enter a valid email</div>');
            isValid = false;
        }

        if (subject.length < 5) {
            $('#subject').after('<div class="error">Subject must be at least 5 characters</div>');
            isValid = false;
        }

        if (message.length < 10) {
            $('#message').after('<div class="error">Message must be at least 10 characters</div>');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // AJAX search functionality
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: 'search_results.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#search-results').html(response);
            },
            error: function() {
                $('#search-results').html('<p class="error">Error loading results. Please try again.</p>');
            }
        });
    });

    // Smooth animations
    $('.btn').hover(
        function() { $(this).css('transform', 'translateY(-2px)'); },
        function() { $(this).css('transform', 'translateY(0)'); }
    );
});