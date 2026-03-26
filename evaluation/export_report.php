<?php
include("../session.php");
include("../db.php");

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'dept_head') {
    echo "Access Denied!";
    exit;
}

// Set headers for download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=faculty_evaluation_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch evaluation data
$sql = "SELECT e.id, e.faculty_username, e.subject, e.department, e.semester, e.submitted_on, 
               ROUND(SUM(s.score * c.weight / 100), 2) AS total_score
        FROM evaluations e
        JOIN evaluation_scores s ON e.id = s.evaluation_id
        JOIN evaluation_criteria c ON s.criterion_id = c.id
        GROUP BY e.id
        ORDER BY e.submitted_on DESC";

$result = $conn->query($sql);

// Output table structure
echo "<table border='1'>";
echo "<tr>
        <th>#</th>
        <th>Faculty Username</th>
        <th>Subject</th>
        <th>Department</th>
        <th>Semester</th>
        <th>Submitted On</th>
        <th>Total Score (%)</th>
      </tr>";

$i = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$i}</td>";
    echo "<td>{$row['faculty_username']}</td>";
    echo "<td>{$row['subject']}</td>";
    echo "<td>{$row['department']}</td>";
    echo "<td>{$row['semester']}</td>";
    echo "<td>{$row['submitted_on']}</td>";
    echo "<td>{$row['total_score']}%</td>";
    echo "</tr>";
    $i++;
}

echo "</table>";
?>
