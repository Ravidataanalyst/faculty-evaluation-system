<?php
include("../session.php");
include("../db.php");

if ($_SESSION['role'] !== 'admin') {
    echo "Access Denied!";
    exit();
}

$result = $conn->query("SELECT * FROM student_feedback ORDER BY submitted_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Student Feedback</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h2 class="mb-4 text-primary">📋 All Student Feedback</h2>

  <?php if ($result->num_rows === 0): ?>
    <div class="alert alert-warning">No feedback records found.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-primary text-center">
          <tr>
            <th>Student</th>
            <th>Faculty</th>
            <th>Communication</th>
            <th>Punctuality</th>
            <th>Knowledge</th>
            <th>Comments</th>
            <th>Submitted On</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr class="text-center">
              <td><?= htmlspecialchars($row['student_username']) ?></td>
              <td><?= htmlspecialchars($row['faculty_username']) ?></td>
              <td><?= $row['communication'] ?></td>
              <td><?= $row['punctuality'] ?></td>
              <td><?= $row['knowledge'] ?></td>
              <td class="text-start"><?= htmlspecialchars($row['feedback_text']) ?></td>
              <td><?= date("d M Y, H:i", strtotime($row['submitted_on'])) ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
