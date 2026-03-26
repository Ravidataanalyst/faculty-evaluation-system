<?php
include('../session.php');
include('../db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied!";
    exit();
}

$result = $conn->query("SELECT * FROM pd_records ORDER BY submitted_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Faculty PD Records - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
  <h3 class="mb-4 text-primary">🧑‍💼 Faculty Professional Development Records</h3>

  <div class="card shadow">
    <div class="card-body">
      <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info text-center">No PD submissions yet.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-primary">
              <tr>
                <th>Faculty</th>
                <th>Course Title</th>
                <th>Type</th>
                <th>Duration (hrs)</th>
                <th>PD Score</th>
                <th>Submitted On</th>
                <th>Proof</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?= htmlspecialchars($row['faculty_username']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= ucfirst($row['type']) ?></td>
                <td><?= $row['duration'] ?></td>
                <td><strong><?= $row['pd_score'] ?></strong></td>
                <td><?= $row['submitted_on'] ?></td>
                <td>
                  <?php if (!empty($row['proof_file'])): ?>
                    <a class="btn btn-sm btn-outline-primary" href="../uploads/pd_proofs/<?= $row['proof_file'] ?>" target="_blank">View</a>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
