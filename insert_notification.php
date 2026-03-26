<?php
include("db.php");

$recipient_role = $_POST['role'];
$recipient_username = $_POST['username'] ?? null;
$message = $_POST['message'];
$link = $_POST['link'] ?? null;

$stmt = $conn->prepare("INSERT INTO notifications (recipient_role, recipient_username, message, link) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $recipient_role, $recipient_username, $message, $link);
if ($stmt->execute()) {
    echo "Notification inserted!";
} else {
    echo "Error: " . $stmt->error;
}
?>
