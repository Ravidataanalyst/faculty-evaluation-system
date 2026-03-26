<?php
include('../session.php');
include('../db.php');

if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied!'); window.location.href='../index.php';</script>";
    exit();
}

$total = $conn->query("SELECT COUNT(*) AS count FROM evaluations")->fetch_assoc()['count'];
$pending = $conn->query("SELECT COUNT(*) AS count FROM evaluations WHERE status='pending'")->fetch_assoc()['count'];
$under_review = $conn->query("SELECT COUNT(*) AS count FROM evaluations WHERE status='under review'")->fetch_assoc()['count'];
$completed = $conn->query("SELECT COUNT(*) AS count FROM evaluations WHERE status='completed'")->fetch_assoc()['count'];

$by_dept = $conn->query("SELECT department, COUNT(*) AS count FROM evaluations GROUP BY department");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Evaluation Summary Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h3 class="mb-4 text-primary">📋 Admin Dashboard – Evaluation Summary</h3>

    <div class="row g-3 mb-4">
      <div class="col-md-3 col-sm-6">
        <div class="card border-primary">
          <div class="card-body text-center">
            <h6>Total Evaluations</h6>
            <h4 class="text-primary"><?= $total ?></h4>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border-warning">
          <div class="card-body text-center">
            <h6>Pending</h6>
            <h4 class="text-warning"><?= $pending ?></h4>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border-info">
          <div class="card-body text-center">
            <h6>Under Review</h6>
            <h4 class="text-info"><?= $under_review ?></h4>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card border-success">
          <div class="card-body text-center">
            <h6>Completed</h6>
            <h4 class="text-success"><?= $completed ?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">📊 Evaluations by Department</h5>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-light">
            <tr>
              <th>Department</th>
              <th>Total Submissions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $by_dept->fetch_assoc()) { ?>
              <tr>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= $row['count'] ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
