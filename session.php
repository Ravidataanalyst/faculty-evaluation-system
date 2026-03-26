<?php
// ✅ Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit();
}
?>
