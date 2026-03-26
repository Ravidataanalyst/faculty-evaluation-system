<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to generate the hidden CSRF input field
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

// Function to verify CSRF token
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }
    }
}
?>
