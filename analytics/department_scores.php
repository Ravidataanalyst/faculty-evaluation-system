<?php
include('../session.php');
include('../db.php');

$query = "
SELECT department, ROUND(AVG(s.score * c.weight / 100), 2) AS avg_score
FROM evaluations e
JOIN evaluation_scores s ON e.id = s.evaluation_id
JOIN evaluation_criteria c ON s.criterion_id = c.id
GROUP BY department
";

$result = $conn->query($query);
$departments = [];
$scores = [];

while ($row = $result->fetch_assoc()) {
    $departments[] = $row['department'];
    $scores[] = $row['avg_score'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Department-wise Scores</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 15px;
    }
    h2 {
      font-weight: bold;
    }
    #chart-container {
      position: relative;
      height: 400px;   /* Fixed proportional height */
      width: 100%;
      margin-top: 20px;
    }
    @media (max-width: 768px) {
      #chart-container {
        height: 300px; /* Reduce height on smaller screens */
      }
    }
  </style>
</head>
<body class="bg-light p-3">

  <div class="container">
    <h2 class="text-center text-primary mb-4">
      📊 Department-wise Average Evaluation Scores
    </h2>

    <div class="card shadow p-4">
      <div class="card-body">
        <!-- Table -->
        <div class="table-responsive mb-4">
          <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-primary">
              <tr>
                <th>Department</th>
                <th>Average Score (%)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($departments as $i => $dept): ?>
              <tr>
                <td><?= htmlspecialchars($dept) ?></td>
                <td>
                  <span class="badge bg-success fs-6">
                    <?= $scores[$i] ?>%
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Chart -->
        <div id="chart-container">
          <canvas id="deptChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('deptChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($departments) ?>,
        datasets: [{
          label: 'Average Score (%)',
          data: <?= json_encode($scores) ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          borderRadius: 8,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
          },
          tooltip: {
            backgroundColor: '#0d6efd',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: '#0d6efd',
            borderWidth: 1
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: function(value) { return value + "%" }
            }
          }
        }
      }
    });
  </script>
</body>
</html>
