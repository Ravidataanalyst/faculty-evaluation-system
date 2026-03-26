<?php
include('../session.php');
include('../db.php');

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'dept_head')) {
    echo "Access denied!";
    exit();
}

$sql = "SELECT e.id, e.faculty_username, e.subject, e.department, e.semester, e.submitted_on, 
               SUM(s.score * c.weight / 100) AS total_score
        FROM evaluations e
        JOIN evaluation_scores s ON e.id = s.evaluation_id
        JOIN evaluation_criteria c ON s.criterion_id = c.id
        GROUP BY e.id
        ORDER BY e.submitted_on DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Faculty Evaluation Report</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print { display: none !important; }
    }
  </style>
</head>
<body class="bg-light p-4">

<div class="container">
  <div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">📄 Faculty Evaluation Report Summary</h4>
      <button onclick="window.print()" class="btn btn-light btn-sm no-print">🖨️ Print / Save as PDF</button>
    </div>

    <div class="card-body">
      <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-warning">No evaluation reports available yet.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Faculty</th>
                <th>Subject</th>
                <th>Dept</th>
                <th>Semester</th>
                <th>Submitted On</th>
                <th>Total Score</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; while($row = $result->fetch_assoc()) { ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= $row['faculty_username'] ?></td>
                  <td><?= $row['subject'] ?></td>
                  <td><?= $row['department'] ?></td>
                  <td><?= $row['semester'] ?></td>
                  <td><?= $row['submitted_on'] ?></td>
                  <td><strong><?= round($row['total_score'], 2) ?>%</strong></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <div class="card-footer text-end no-print">
      <a href="export_report.php?type=excel" class="btn btn-outline-success">⬇️ Download as Excel</a>
    </div>
  </div>
</div>

</body>
</html>
