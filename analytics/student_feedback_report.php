<?php
include("../session.php");
if ($_SESSION['role'] !== 'admin') exit("Access Denied!");
include("../db.php");

$result = $conn->query("
  SELECT faculty_username, AVG(clarity) AS clarity, AVG(engagement) AS engagement,
         AVG(feedback_timeliness) AS feedback_timeliness, AVG(resources) AS resources,
         AVG(overall) AS overall, COUNT(*) AS responses
  FROM student_feedback
  GROUP BY faculty_username
  ORDER BY overall DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Feedback Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">

<div class="container">
  <h3 class="text-success mb-4">📊 Faculty Feedback Averages (From Students)</h3>

  <div class="card shadow">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>Faculty</th>
            <th>Clarity</th>
            <th>Engagement</th>
            <th>Timeliness</th>
            <th>Resources</th>
            <th>Overall</th>
            <th>Responses</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= htmlspecialchars($row['faculty_username']) ?></td>
            <td><?= round($row['clarity'], 2) ?></td>
            <td><?= round($row['engagement'], 2) ?></td>
            <td><?= round($row['feedback_timeliness'], 2) ?></td>
            <td><?= round($row['resources'], 2) ?></td>
            <td><strong><?= round($row['overall'], 2) ?></strong></td>
            <td><?= $row['responses'] ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
