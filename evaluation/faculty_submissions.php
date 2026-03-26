<?php
include('../session.php');
include('../db.php');

$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM evaluations WHERE faculty_username='$username' ORDER BY submitted_on DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Submissions</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <div class="card shadow mb-5">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">📁 Your Evaluation Submissions</h4>
      </div>
      <div class="card-body">
        <?php if ($result->num_rows === 0): ?>
          <div class="alert alert-info">You haven't submitted any evaluations yet.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>📚 Subject</th>
                  <th>🏛️ Department</th>
                  <th>📌 Status</th>
                  <th>📆 Submitted On</th>
                  <th>📎 File</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?= $row['subject'] ?></td>
                    <td><?= $row['department'] ?></td>
                    <td>
                      <?php
                        $status = $row['status'];
                        $badge = match($status) {
                          'pending' => 'secondary',
                          'under review' => 'warning',
                          'completed' => 'success',
                          default => 'dark'
                        };
                      ?>
                      <span class="badge bg-<?= $badge ?>"><?= ucfirst($status) ?></span>
                    </td>
                    <td><?= $row['submitted_on'] ?></td>
                    <td>
                      <?php if ($row['supporting_file']) { ?>
                        <a href="../uploads/<?= $row['supporting_file'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                      <?php } else { echo "No file"; } ?>
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
