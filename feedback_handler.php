<?php
require_once __DIR__ . '/includes/csrf.php';
verify_csrf();
include('session.php');
include('db.php');

if ($_SESSION['role'] !== 'student') {
    echo "Access Denied!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = $_SESSION['username'];
    $faculty = trim($_POST['faculty_username'] ?? '');
    $communication = (int) ($_POST['communication'] ?? 0);
    $punctuality   = (int) ($_POST['punctuality'] ?? 0);
    $knowledge     = (int) ($_POST['knowledge'] ?? 0);
    $feedback_text = trim($_POST['feedback_text'] ?? '');

    // ✅ Validation
    if (!$faculty || $communication < 1 || $punctuality < 1 || $knowledge < 1 || empty($feedback_text)) {
        require_once __DIR__ . '/includes/alert.php';
        swal_redirect('Validation Error', 'Invalid form input. Please fill all fields properly.', 'warning', 'submit_feedback.php');
    }

    // ✅ Insert into DB
    $stmt = $conn->prepare("INSERT INTO student_feedback 
        (student_username, faculty_username, communication, punctuality, knowledge, feedback_text) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiis", $student, $faculty, $communication, $punctuality, $knowledge, $feedback_text);
    $stmt->execute();

    require_once __DIR__ . '/includes/alert.php';
    swal_redirect('Success!', 'Feedback submitted successfully.', 'success', 'submit_feedback.php');
}
?>
