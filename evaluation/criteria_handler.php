<?php
include('../session.php');
include('../db.php');

if ($_SESSION['role'] !== 'admin') {
    exit("Access denied!");
}

// ✅ ADD Criterion with Total Weight Validation
if (isset($_POST['add'])) {
    $name = $_POST['criterion_name'];
    $weight = (int) $_POST['weight'];

    // Get current total weight
    $result = $conn->query("SELECT SUM(weight) as total FROM evaluation_criteria");
    $total = $result->fetch_assoc()['total'] ?? 0;

    if (($total + $weight) > 100) {
        echo "<script>alert('❌ Adding this would exceed the total weight limit of 100%. Current total: $total%.'); window.location.href='define_criteria.php';</script>";
        exit();
    }

    // Add the criterion
    $stmt = $conn->prepare("INSERT INTO evaluation_criteria (criterion_name, weight) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $weight);
    $stmt->execute();

    header("Location: define_criteria.php");
    exit();
}

// ✅ DELETE Criterion (Safe Deletion)
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    $check = $conn->query("SELECT COUNT(*) as total FROM evaluation_scores WHERE criterion_id = $id");
    $count = $check->fetch_assoc()['total'];

    if ($count > 0) {
        echo "<script>alert('❌ Cannot delete: This criterion is used in evaluations.'); window.location.href='define_criteria.php';</script>";
        exit();
    }

    $conn->query("DELETE FROM evaluation_criteria WHERE id = $id");
    header("Location: define_criteria.php");
    exit();
}
?>
