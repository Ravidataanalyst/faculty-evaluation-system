<?php
require_once __DIR__ . '/includes/alert.php';
session_start();

// Check if OTP session variables exist
if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_time'])) {
    swal_redirect('Error', 'Invalid session or OTP expired.', 'error', 'index.php');
}

$time_diff = time() - $_SESSION['otp_time'];

// Check if OTP is older than 120 seconds (2 minutes)
if ($time_diff > 120) {
    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);
    swal_redirect('OTP Expired', 'Your OTP has expired. Please log in again.', 'warning', 'index.php');
}

// Verify OTP
if ($_POST['otp_input'] == $_SESSION['otp']) {
    // Regenerate session ID for security against session fixation
    session_regenerate_id(true);

    // Unset the OTP so it cannot be reused
    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);

    switch ($_SESSION['role']) {
        case 'admin': header("Location: dashboard_admin.php"); break;
        case 'faculty': header("Location: dashboard_faculty.php"); break;
        case 'dept_head': header("Location: dashboard_dept.php"); break;
        case 'student': header("Location: dashboard_student.php"); break;
        default: header("Location: index.php"); break;
    }
    exit();
} else {
    // Unset OTP on failed attempt to prevent brute force
    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);
    swal_redirect('Login Failed', 'Incorrect OTP. Please login again.', 'error', 'index.php');
}
?>
