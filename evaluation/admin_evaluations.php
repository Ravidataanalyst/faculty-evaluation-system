<?php
include('../session.php');
include('../db.php');

if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied!'); window.location.href='../index.php';</script>";
    exit();
}

$result = $conn->query("SELECT * FROM evaluations ORDER BY submitted_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - All Evaluations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
  <h3 class="mb-4 text-primary">📝 All Faculty Evaluations</h3>

  <?php if ($result->num_rows === 0): ?>
    <div class="alert alert-warning">No evaluation records available.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th><th>Faculty</th><th>Subject</th><th>Department</th>
            <th>Semester</th><th>Status</th><th>Reviewer</th><th>Submitted On</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
          <tr class="text-center">
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['faculty_username']) ?></td>
            <td><?= htmlspecialchars($row['subject']) ?></td>
            <td><?= $row['department'] ?></td>
            <td><?= $row['semester'] ?></td>
            <td>
              <span class="badge bg-<?= $row['status'] == 'completed' ? 'success' : ($row['status'] == 'pending' ? 'warning text-dark' : 'info') ?>">
                <?= ucfirst($row['status']) ?>
              </span>
            </td>
            <td><?= $row['reviewed_by'] ?: '—' ?></td>
            <td><?= date('d M Y, H:i', strtotime($row['submitted_on'])) ?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
