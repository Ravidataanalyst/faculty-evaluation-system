<?php
require_once __DIR__ . '/includes/csrf.php';
verify_csrf();
session_start();
include("db.php");
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

// Get inputs from login form
$username = $_POST['username'];
$password = $_POST['password'];
$role     = $_POST['role']; // ⬅️ Role selection from login page

// Check if user with this role exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role=?");
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];
    $_SESSION['email']    = $user['email'];

    // ✅ Generate OTP and save timestamp
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();  // for 2-minute validity

    // ✅ Send OTP via email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($user['email']);
        $mail->Subject = "Your OTP for Login";
        $mail->Body    = "Dear " . $user['username'] . ",\n\nYour OTP for login is: $otp\n⏳ This OTP is valid for 2 minutes only.\n\nRegards,\nFaculty Eval Team";

        $mail->send();
        header("Location: otp_verify.php");
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    require_once __DIR__ . '/includes/alert.php';
    swal_redirect('Login Failed', 'Invalid credentials or role mismatch.', 'error', 'index.php');
}
?>  