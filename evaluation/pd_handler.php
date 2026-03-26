<?php
session_start();
include('../db.php');  // ✅ Corrected path

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    echo "Access Denied!";
    exit();
}

$faculty = $_SESSION['username'];
$title = $_POST['course_title'] ?? '';
$type = $_POST['type'] ?? '';
$duration = intval($_POST['duration_hours'] ?? 0);

// ✅ Input validation
if (empty($title) || empty($type) || $duration <= 0) {
    echo "<script>alert('❌ Invalid input.'); window.location.href='faculty_pd_submit.php';</script>";
    exit();
}

// ✅ PD Score calculation (capped at 30)
$pd_score = ($duration > 30) ? 30 : $duration;

// ✅ Handle file upload
$upload_dir = '../uploads/pd_proofs/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file = $_FILES['proof_file'];
$filename = time() . "_" . basename($file['name']);
$target_path = $upload_dir . $filename;

if (!move_uploaded_file($file['tmp_name'], $target_path)) {
    die("❌ File upload failed.");
}

// ✅ Insert into DB
$stmt = $conn->prepare("INSERT INTO pd_records 
    (faculty_username, title, type, duration, proof_file, pd_score) 
    VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssisi", $faculty, $title, $type, $duration, $filename, $pd_score);
$stmt->execute();

echo "<script>alert('✅ Record submitted successfully!'); window.location.href='faculty_pd_submit.php';</script>";
exit();
?>
