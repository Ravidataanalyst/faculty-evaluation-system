<?php
// Database Configuration
define('DB_SERVER', '127.0.0.1:3307'); // Default local server port setup
define('DB_USERNAME', 'root'); // Your DB username
define('DB_PASSWORD', ''); // Your DB password
define('DB_NAME', 'faculty_eval'); // The database name

// SMTP Email Configuration
define('SMTP_HOST', 'smtp.gmail.com'); // e.g. smtp.gmail.com
define('SMTP_USERNAME', 'your_email@gmail.com'); // Insert SMTP email here
define('SMTP_PASSWORD', 'your_app_password'); // App Password (e.g. from Google Security)
define('SMTP_PORT', 587); // Typically 587 for TLS
define('SMTP_FROM_EMAIL', 'your_email@gmail.com');
define('SMTP_FROM_NAME', 'Faculty Evaluation System');
?>
