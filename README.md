# ezdateweb

# 💕 EZDate - Romantic Dating Website

A complete dating platform with real-time messaging, profile matching, and beautiful romantic UI design.



# ✨ Features

- 💘 like Swiping - Like/Pass interface for discovering matches
- 💬 Real-time Messaging - Chat with matched users instantly
- 📸 Profile Pictures - Upload and manage profile photos
- 🔍 Smart Matching - Gender-based filtering (opposite gender only)
- 💖 Romantic UI - Glass-morphism design with interactive gradients
- 🔐 Secure Authentication - Password hashing and session management
- 📱 Responsive Design - Works perfectly on all devices

# 🛠️ Technologies Used

# Frontend
- HTML5 - Semantic markup and structure
- CSS3 - Glass-morphism effects, gradients, animations
- Bootstrap 5.3.0 - Responsive framework and components
- JavaScript - Interactive features and AJAX
- jQuery - DOM manipulation and event handling

# Backend
- PHP 8.0+ - Server-side logic and processing
- MySQL - Database management and storage
- AJAX - Real-time updates without page reload

# Security
- **Password Hashing** - bcrypt encryption
- **Prepared Statements** - SQL injection prevention
- **File Validation** - Secure image uploads
- **Session Management** - User authentication

# 📋 Requirements

- XAMPP/WAMP/LAMP - Local development environment
- PHP 8.0+ - Server-side scripting
- MySQL 5.7+ - Database server
- Apache - Web server
- Modern Browser - Chrome, Firefox, Safari, Edge

 🚀 Quick Setup

# Option 1: One-Click Setup (Recommended)
1. Clone this repository to your XAMPP `htdocs` folder
2. Start XAMPP (Apache + MySQL)
3. Visit: `http://localhost/EZdate/complete_setup.php`
4. Delete `complete_setup.php` after setup
5. Launch: `http://localhost/EZdate`

# Option 2: Manual Setup
1. Clone Repository
   ```bash
   git clone https://github.com/yourusername/ezdateweb.git
   cd ezdate
   ```

2. Database Setup
   - Import `setup/setup_database.sql` in phpMyAdmin
   - Or run `setup/setup.php`

3. Configuration
   - Rename `db_connect_example.php` to `db_connect.php`
   - Update database credentials if needed

4. Permissions
   ```bash
   chmod 777 uploads/
   ```

5. Sample Data (Optional)
   - Run `setup/add_sample_data.php`


# 📁 Project Structure

```
EZdate/
├── index.php              # Home page
├── register.php           # User registration
├── login.php             # User authentication
├── swipe.php             # Tinder-like discovery
├── matches.php           # View matches
├── chat.php              # Real-time messaging
├── profile.php           # User profiles
├── edit_profile.php      # Profile editing
├── upload_photo.php      # Photo upload
├── contact.php           # Contact form
├── header.php            # Navigation component
├── footer.php            # Footer component
├── db_connect.php        # Database connection
├── style.css             # Romantic styling
├── script.js             # JavaScript functions
├── uploads/              # Profile pictures
├── setup/                # Setup utilities
└── complete_setup.php    # One-click setup
```

# 🎨 Design Features

# Glass-morphism UI
- Semi-transparent containers with backdrop blur
- Soft shadows and romantic color schemes
- Interactive gradient backgrounds

# Responsive Design
- Mobile-first approach
- Flexbox and CSS Grid layouts
- Cross-browser compatibility

# Interactive Elements
- Mouse-following gradient backgrounds
- Smooth hover animations
- Real-time form validation

# 🔧 Core Functionality

# User Management
- Secure registration with validation
- Password hashing (bcrypt)
- Session-based authentication
- Profile customization

# Matching System
- Gender-based filtering
- Like/Pass swipe interface
- Mutual like detection
- Match notifications

# Messaging System
- Real-time chat updates
- AJAX message sending
- Match verification
- Message history

# File Upload
- Secure image validation
- File type and size checking
- Automatic resizing
- Default avatar system

# 🗄️ Database Schema

# Tables
- users - User profiles and authentication
- likes - Swipe actions and preferences
- matches - Mutual likes and connections
- messages - Chat conversations
- contact_messages - Support inquiries

# Relationships
- Foreign key constraints
- Unique constraints for data integrity
- Indexed fields for performance

# 🔒 Security Features

- **SQL Injection Prevention** - Prepared statements
- **Password Security** - bcrypt hashing
- **File Upload Security** - Type and size validation
- **Session Management** - Secure user sessions
- **Input Validation** - Server-side validation
- **XSS Protection** - Output escaping

# 🌐 Browser Support

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers

# 📱 Mobile Responsive

- Touch-friendly interface
- Optimized for mobile swiping
- Responsive navigation
- Mobile-first CSS

# 🚀 Performance

- Optimized CSS and JavaScript
- Efficient database queries
- Image optimization
- Minimal HTTP requests

# 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

# 🙏 Acknowledgments

- Inspired by modern dating applications
- Built with love for connecting hearts
- Thanks to the open-source community


**Made with 💕 for bringing people together**

*EZDate - Where hearts connect and love stories begin* ✨
