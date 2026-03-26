<?php
include('../session.php');
include('../db.php');

$sql = "
SELECT faculty_username, ROUND(SUM(s.score * c.weight / 100), 2) AS total_score
FROM evaluations e
JOIN evaluation_scores s ON e.id = s.evaluation_id
JOIN evaluation_criteria c ON s.criterion_id = c.id
GROUP BY faculty_username
ORDER BY total_score DESC
LIMIT 5
";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Top Faculty</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
  <div class="container">
    <div class="card shadow">
      <div class="card-header bg-success text-white">
        <h4 class="mb-0">🏆 Top Performing Faculty</h4>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>Rank</th>
              <th>Faculty</th>
              <th>Total Score (%)</th>
            </tr>
          </thead>
          <tbody>
            <?php $rank = 1; while ($row = $res->fetch_assoc()) { ?>
              <tr>
                <td><?= $rank++ ?></td>
                <td><?= htmlspecialchars($row['faculty_username']) ?></td>
                <td><strong><?= $row['total_score'] ?></strong></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
