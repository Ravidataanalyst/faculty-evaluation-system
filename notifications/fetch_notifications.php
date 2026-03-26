<?php
session_start();
include("../db.php");

header("Content-Type: application/json");

// Ensure the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Ensure DB connection is active
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Prepare SQL: fetch last 5 notifications for role OR public (role = 'all'), and optionally specific username
$query = "
    SELECT message, created_at 
    FROM notifications 
    WHERE (role = ? OR role = 'all') 
      AND (username IS NULL OR username = ?)
    ORDER BY created_at DESC 
    LIMIT 5
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $role, $username);
$stmt->execute();

$result = $stmt->get_result();
$notifications = [];

while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'message' => htmlspecialchars($row['message']),
        'created_at' => date('d M, H:i', strtotime($row['created_at']))
    ];
}

echo json_encode($notifications);
?>
