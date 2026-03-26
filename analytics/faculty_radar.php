<?php
include('../session.php');
include('../db.php');

if ($_SESSION['role'] !== 'admin') {
    exit("Access Denied");
}

// Dropdown if no faculty is selected
if (!isset($_GET['faculty'])) {
    $faculties = $conn->query("SELECT username FROM users WHERE role='faculty'");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <title>Select Faculty</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light p-4">
      <div class="container">
        <div class="card shadow p-4">
          <h3 class="text-primary">📊 Select Faculty for Radar Analysis</h3>
          <form method="GET" class="mt-3">
            <label for="faculty" class="form-label">Choose Faculty Username:</label>
            <select name="faculty" class="form-select mb-3" required>
              <option value="">-- Select Faculty --</option>
              <?php while ($f = $faculties->fetch_assoc()) { ?>
                <option value="<?= $f['username'] ?>"><?= $f['username'] ?></option>
              <?php } ?>
            </select>
            <button class="btn btn-primary">Generate Chart</button>
          </form>
        </div>
      </div>
    </body>
    </html>
    <?php exit();
}

// Radar chart display
$faculty = $_GET['faculty'];
$criteria = [];
$userScores = [];
$avgScores = [];

$res = $conn->query("SELECT id, criterion_name FROM evaluation_criteria");
while ($row = $res->fetch_assoc()) {
    $criteria[$row['id']] = $row['criterion_name'];
}

foreach ($criteria as $id => $name) {
    $user = $conn->query("
        SELECT ROUND(AVG(score), 2) AS avg
        FROM evaluation_scores s
        JOIN evaluations e ON e.id = s.evaluation_id
        WHERE e.faculty_username='$faculty' AND s.criterion_id=$id
    ")->fetch_assoc()['avg'] ?? 0;

    $avg = $conn->query("
        SELECT ROUND(AVG(score), 2) AS avg
        FROM evaluation_scores
        WHERE criterion_id=$id
    ")->fetch_assoc()['avg'] ?? 0;

    $userScores[] = floatval($user);
    $avgScores[] = floatval($avg);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title><?= $faculty ?> vs Average – Radar Chart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <div class="card shadow p-4">
      <h4 class="text-center mb-4">📊 Radar Comparison: <strong><?= htmlspecialchars($faculty) ?></strong> vs Overall</h4>
      <div class="text-center mb-3">
        <a href="faculty_radar.php" class="btn btn-outline-secondary">🔁 Choose Another Faculty</a>
      </div>
      <div class="chart-container" style="position: relative; height: 400px;">
        <canvas id="radarChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('radarChart');
    new Chart(ctx, {
      type: 'radar',
      data: {
        labels: <?= json_encode(array_values($criteria)) ?>,
        datasets: [
          {
            label: '<?= $faculty ?>',
            data: <?= json_encode($userScores) ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.3)',
            borderColor: 'rgba(255, 99, 132, 1)',
            pointBackgroundColor: 'red'
          },
          {
            label: 'Overall Average',
            data: <?= json_encode($avgScores) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.3)',
            borderColor: 'rgba(54, 162, 235, 1)',
            pointBackgroundColor: 'blue'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          r: {
            beginAtZero: true,
            max: 100
          }
        }
      }
    });
  </script>
</body>
</html>
