<?php

// ADMIN METRICS
function totalEvaluations($conn) {
    $res = $conn->query("SELECT COUNT(*) as total FROM evaluations");
    return $res->fetch_assoc()['total'];
}

function pendingReviews($conn) {
    $res = $conn->query("SELECT COUNT(*) as total FROM evaluations WHERE status = 'pending'");
    return $res->fetch_assoc()['total'];
}

function averageScore($conn) {
    $res = $conn->query("SELECT AVG(s.score * c.weight / 100) as avg_score 
                         FROM evaluation_scores s 
                         JOIN evaluation_criteria c ON s.criterion_id = c.id");
    return round($res->fetch_assoc()['avg_score'], 2);
}

function totalDocuments($conn) {
    $res = $conn->query("SELECT COUNT(*) as total FROM evaluations WHERE supporting_file IS NOT NULL AND supporting_file != ''");
    return $res->fetch_assoc()['total'];
}

// FACULTY METRICS
function mySubmissionCount($conn, $username) {
    $res = $conn->query("SELECT COUNT(*) as total FROM evaluations WHERE faculty_username='$username'");
    return $res->fetch_assoc()['total'];
}

function lastSubmittedOn($conn, $username) {
    $res = $conn->query("SELECT submitted_on FROM evaluations WHERE faculty_username='$username' ORDER BY submitted_on DESC LIMIT 1");
    return $res->num_rows ? $res->fetch_assoc()['submitted_on'] : 'N/A';
}

// DEPT HEAD METRICS
function reviewsDone($conn, $dept_head) {
    $res = $conn->query("SELECT COUNT(*) as total FROM evaluations WHERE reviewed_by='$dept_head'");
    return $res->fetch_assoc()['total'];
}

function reviewsPending($conn) {
    $res = $conn->query("SELECT COUNT(*) as total FROM evaluations WHERE status='pending'");
    return $res->fetch_assoc()['total'];
}
?>
