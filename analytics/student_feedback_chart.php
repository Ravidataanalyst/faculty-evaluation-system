<?php
include("../session.php");
include("../db.php");

if ($_SESSION['role'] !== 'admin') {
    echo "Access Denied!";
    exit();
}

$sql = "
SELECT faculty_username, 
       ROUND(AVG((communication + punctuality + knowledge) / 3), 2) AS avg_rating
FROM student_feedback
GROUP BY faculty_username
ORDER BY avg_rating DESC
";

$result = $conn->query($sql);

$faculty = [];
$ratings = [];

while ($row = $result->fetch_assoc()) {
    $faculty[] = $row['faculty_username'];
    $ratings[] = $row['avg_rating'];
}

// Calculate summary stats
$totalFaculty = count($faculty);
$highestRating = !empty($ratings) ? max($ratings) : 0;
$averageRating = !empty($ratings) ? round(array_sum($ratings) / count($ratings), 2) : 0;

// Prepare top 3 faculty
$topFaculty = [];
for ($i = 0; $i < min(3, count($faculty)); $i++) {
    $topFaculty[] = ["name" => $faculty[$i], "rating" => $ratings[$i]];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Feedback Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.min.js"></script>

  <style>
    body {
      background: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .page-header {
      text-align: center;
      margin-bottom: 25px;
    }
    .page-header h2 {
      color: #0d6efd;
      font-weight: 700;
    }
    .stat-card {
      border: none;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      background: #fff;
      text-align: center;
      transition: transform 0.2s ease-in-out;
    }
    .stat-card:hover {
      transform: translateY(-5px);
    }
    .stat-card h4 {
      margin-bottom: 8px;
      color: #333;
      font-weight: 600;
    }
    .stat-card span {
      font-size: 24px;
      color: #0d6efd;
      font-weight: bold;
    }
    .chart-card {
      border-radius: 15px;
      background: #fff;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .table-container {
      margin-top: 30px;
    }
    .table {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    .table thead {
      background-color: #0d6efd;
      color: #fff;
    }
    .no-data {
      text-align: center;
      background: #fff3cd;
      color: #856404;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      font-weight: 500;
    }
  </style>
</head>
<body>

<div class="container py-4">
  <div class="page-header">
    <h2>📊 Student Feedback Dashboard</h2>
    <p class="text-muted">A comprehensive view of faculty ratings based on student feedback</p>
  </div>

  <?php if (empty($faculty)): ?>
    <div class="no-data">
      ⚠️ No feedback data available yet.
    </div>
  <?php else: ?>

    <!-- Top Summary Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="stat-card">
          <h4>Average Rating</h4>
          <span>⭐ <?= $averageRating ?> / 5</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <h4>Highest Rating</h4>
          <span>🏆 <?= $highestRating ?> / 5</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <h4>Total Faculty</h4>
          <span>👨‍🏫 <?= $totalFaculty ?></span>
        </div>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-card mb-4">
      <h5 class="text-center mb-3">Faculty Ratings Comparison</h5>
      <canvas id="feedbackChart" height="350"></canvas>
    </div>

    <!-- Top 3 Faculty Table -->
    <div class="table-container">
      <h5 class="mb-3">🏅 Top 3 Best-Rated Faculty</h5>
      <div class="table-responsive">
        <table class="table table-hover table-bordered text-center">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Faculty Name</th>
              <th>Average Rating</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($topFaculty as $i => $facultyData): ?>
              <tr>
                <td><strong>#<?= $i + 1 ?></strong></td>
                <td><?= htmlspecialchars($facultyData['name']) ?></td>
                <td><strong><?= $facultyData['rating'] ?> / 5</strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <script>
      const ctx = document.getElementById('feedbackChart').getContext('2d');
      const gradient = ctx.createLinearGradient(0, 0, 0, 400);
      gradient.addColorStop(0, 'rgba(54, 162, 235, 0.9)');
      gradient.addColorStop(1, 'rgba(75, 192, 192, 0.7)');

      new Chart(ctx, {
        type: 'radar',
        data: {
          labels: <?= json_encode($faculty) ?>,
          datasets: [{
            label: 'Average Rating',
            data: <?= json_encode($ratings) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            pointBackgroundColor: '#0d6efd',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          plugins: {
            tooltip: {
              backgroundColor: '#0d6efd',
              titleColor: '#fff',
              bodyColor: '#fff',
              padding: 10,
              cornerRadius: 8,
              callbacks: {
                label: (tooltipItem) => `⭐ ${tooltipItem.raw} / 5`
              }
            },
            legend: { display: false }
          },
          scales: {
            r: {
              beginAtZero: true,
              max: 5,
              ticks: { stepSize: 1, color: '#555' },
              grid: { color: '#e0e0e0' },
              angleLines: { color: '#ccc' },
              pointLabels: {
                color: '#333',
                font: { size: 12, weight: '600' }
              }
            }
          }
        }
      });
    </script>
  <?php endif; ?>
</div>

</body>
</html>
