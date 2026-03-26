<?php
include('../session.php');
include('../db.php');

if (!isset($_GET['evaluation_id'])) {
    echo "Missing evaluation ID";
    exit();
}

$evaluation_id = $_GET['evaluation_id'];
$sql = "SELECT ec.weight, es.score
        FROM evaluation_scores es
        JOIN evaluation_criteria ec ON es.criterion_id = ec.id
        WHERE es.evaluation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $evaluation_id);
$stmt->execute();
$result = $stmt->get_result();

$total_score = 0;
while ($row = $result->fetch_assoc()) {
    $total_score += ($row['score'] * $row['weight']) / 100;
}
echo "<h2>Total Performance Score: " . round($total_score, 2) . "%</h2>";
?>
