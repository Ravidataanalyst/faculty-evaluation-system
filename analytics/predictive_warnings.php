<?php
include('../session.php');
include('../db.php');
if ($_SESSION['role'] !== 'admin') exit("Access Denied");

$sql = "
SELECT e.faculty_username, e.semester,
  ROUND(SUM(s.score * c.weight / 100), 2) AS score
FROM evaluations e
JOIN evaluation_scores s ON e.id = s.evaluation_id
JOIN evaluation_criteria c ON c.id = s.criterion_id
GROUP BY e.faculty_username, e.semester
ORDER BY e.faculty_username, e.semester
";

$history = [];
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $faculty = $row['faculty_username'];
    $history[$faculty][] = [
        'semester' => $row['semester'],
        'score' => $row['score']
    ];
}

$at_risk = [];
foreach ($history as $faculty => $semesters) {
    $count = count($semesters);
    $last_score = $semesters[$count-1]['score'];
    $prev_score = $semesters[$count-2]['score'] ?? $last_score;

    if ($last_score < 60 || ($prev_score > $last_score && $last_score < 70)) {
        $at_risk[$faculty] = [$prev_score, $last_score];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📉 Faculty At-Risk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h3 class="text-danger mb-4">📉 Predictive Alerts: Faculty Needing Support</h3>

    <?php if (count($at_risk) === 0): ?>
      <div class="alert alert-success text-center">✅ No faculty flagged for risk based on recent evaluations.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>Faculty</th>
              <th>Previous Score</th>
              <th>Latest Score</th>
              <th>Suggested Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($at_risk as $faculty => $scores): ?>
              <tr>
                <td><?= htmlspecialchars($faculty) ?></td>
                <td><?= $scores[0] ?>%</td>
                <td><?= $scores[1] ?>%</td>
                <td>
                  <?php
                    if ($scores[1] < 60) echo "📘 Assign mentor / Additional training";
                    else echo "💡 Encourage improvement in weak areas";
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
