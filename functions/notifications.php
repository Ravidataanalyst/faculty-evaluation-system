<?php
function displayNotifications($conn, $role, $username) {
    $sql = "SELECT message, created_at 
            FROM notifications 
            WHERE role = ? OR username = ? 
            ORDER BY created_at DESC 
            LIMIT 5";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $role, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return '<div class="alert alert-info mb-3">🔕 No new notifications.</div>';
    }

    $html = '<div class="alert alert-secondary mb-3"><strong>🔔 Notifications:</strong><ul class="mb-0 mt-2 ps-3">';
    while ($row = $result->fetch_assoc()) {
        $msg = htmlspecialchars($row['message']);
        $time = date('d M Y, H:i', strtotime($row['created_at']));
        $html .= "<li class='mb-1'>$msg <span class='text-muted small'>($time)</span></li>";
    }
    $html .= '</ul></div>';

    return $html;
}
?>
