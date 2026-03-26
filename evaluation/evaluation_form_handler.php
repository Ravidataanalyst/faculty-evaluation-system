<?php
session_start();
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate session
    if (!isset($_SESSION['username'])) {
        die("Unauthorized access.");
    }

    $faculty = $_SESSION['username'];
    $subject = $_POST['subject'] ?? '';
    $department = $_POST['department'] ?? '';
    $semester = $_POST['semester'] ?? '';
    $assessment = $_POST['self_assessment'] ?? '';
    $teaching = $_POST['teaching'] ?? '';
    $research = $_POST['research'] ?? '';
    $service = $_POST['service'] ?? '';

    // Handle file upload
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file = $_FILES['supporting_file'] ?? null;
    $saved_filename = '';

    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $filename = basename($file['name']);
        $saved_filename = time() . "_" . preg_replace("/[^a-zA-Z0-9_.-]/", "", $filename);  // sanitize filename
        $target_path = $upload_dir . $saved_filename;

        if (!move_uploaded_file($file['tmp_name'], $target_path)) {
            die("❌ File upload failed.");
        }
    } else {
        die("❌ No file uploaded or file error.");
    }

    // Insert into evaluations table
    $stmt = $conn->prepare("INSERT INTO evaluations 
        (faculty_username, subject, department, semester, self_assessment, teaching_effectiveness, research_contribution, service_to_institution, supporting_file) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssss",
        $faculty,
        $subject,
        $department,
        $semester,
        $assessment,
        $teaching,
        $research,
        $service,
        $saved_filename
    );

    if ($stmt->execute()) {
        header("Location: faculty_submissions.php");
        exit();
    } else {
        die("❌ Database insert failed: " . $stmt->error);
    }
}
?>
