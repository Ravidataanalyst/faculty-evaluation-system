<?php
include('../session.php');
include('../db.php');

// ✅ Access Control
if (!in_array($_SESSION['role'], ['admin', 'dept_head'])) {
  echo "Access Denied!";
  exit;
}

// ✅ Fetch Evaluation Score Trends
$sql = "
  SELECT semester, faculty_username, 
         ROUND(SUM(s.score * c.weight / 100), 2) AS total_score
  FROM evaluations e
  JOIN evaluation_scores s ON s.evaluation_id = e.id
  JOIN evaluation_criteria c ON c.id = s.criterion_id
  GROUP BY semester, faculty_username
  ORDER BY semester
";

$result = $conn->query($sql);

// ✅ Process into $data[faculty][semester] = score
$data = [];
while ($row = $result->fetch_assoc()) {
  $data[$row['faculty_username']][$row['semester']] = $row['total_score'];
}

// ✅ Extract all semesters across faculty
$semesters = [];
foreach ($data as $scores) {
  foreach (array_keys($scores) as $sem) {
    $semesters[] = $sem;
  }
}
$semesters = array_unique($semesters);
sort($semesters);
?>

<!DOCTYPE html>
<html>
<head>
  <title>📈 Faculty Score Trends</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">
<div class="container">
  <h3 class="mb-4 text-primary">📅 Faculty Score Trends (Semester-wise)</h3>

  <?php if (count($data) === 0): ?>
    <div class="alert alert-warning">⚠️ No evaluation data found. Submit faculty evaluations to generate trends.</div>
  <?php else: ?>
    <canvas id="trendChart" height="400"></canvas>
  <?php endif; ?>
</div>

<?php if (count($data) > 0): ?>
<script>
const labels = <?= json_encode(array_values($semesters)) ?>;
const datasets = [];

<?php foreach ($data as $faculty => $scores): ?>
datasets.push({
  label: <?= json_encode($faculty) ?>,
  data: <?= json_encode(array_values(array_replace(array_fill_keys($semesters, null), $scores))) ?>,
  fill: false,
  borderColor: 'hsl(' + Math.floor(Math.random() * 360) + ', 70%, 50%)',
  tension: 0.3
});
<?php endforeach; ?>

new Chart(document.getElementById('trendChart'), {
  type: 'line',
  data: { labels, datasets },
  options: {
    responsive: true,
    plugins: {
      title: {
        display: true,
        text: 'Faculty Performance Trends Across Semesters',
        font: { size: 18 }
      },
      legend: {
        position: 'top'
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Total Score (%)'
        }
      },
      x: {
        title: {
          display: true,
          text: 'Semester'
        }
      }
    }
  }
});
</script>
<?php endif; ?>
</body>
</html>
