<?php
include("session.php");
if ($_SESSION['role'] !== 'dept_head') exit("Access Denied!");

include("templates/header.php");
include("includes/sidebar_dept.php");
include("db.php");
include("functions/dashboard_metrics.php");
include("functions/notifications.php");

$dept_head = htmlspecialchars($_SESSION['username']);
?>

<div class="main-content">

  <!-- Top Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
      <h2 class="page-title mb-1">👨‍🏫 Welcome, <?= $dept_head ?>!</h2>
      <span class="text-muted" style="font-size:.85rem;">Faculty Evaluation System · Department Head</span>
    </div>
    <button id="themeToggle" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-moon-fill me-1"></i> Dark Mode
    </button>
  </div>

  <!-- Metrics Row -->
  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
      <div class="metric-card bg-g-orange">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="metric-label">Pending Reviews</div>
            <div class="metric-value"><?= reviewsPending($conn) ?></div>
            <div class="mt-2"><a href="evaluation/head_review.php" class="btn btn-light btn-sm">Review Now →</a></div>
          </div>
          <i class="bi bi-hourglass-split metric-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="80">
      <div class="metric-card bg-g-blue">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="metric-label">Scored by You</div>
            <div class="metric-value"><?= reviewsDone($conn, $dept_head) ?></div>
            <div class="mt-2"><a href="evaluation/score_entry.php" class="btn btn-light btn-sm">Score Faculty →</a></div>
          </div>
          <i class="bi bi-star-half metric-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-12" data-aos="fade-up" data-aos-delay="160">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-trophy text-warning me-1"></i> Top 3 Faculty</h6>
          <?php
          $q = "SELECT e.faculty_username, ROUND(SUM(s.score * c.weight / 100), 2) AS total_score
                FROM evaluations e
                JOIN evaluation_scores s ON e.id = s.evaluation_id
                JOIN evaluation_criteria c ON s.criterion_id = c.id
                GROUP BY e.faculty_username ORDER BY total_score DESC LIMIT 3";
          $res = mysqli_query($conn, $q);
          $rank = 1;
          while ($row = mysqli_fetch_assoc($res)) {
              $medalColors = ['#FFD700', '#C0C0C0', '#CD7F32'];
              $color = $medalColors[$rank - 1] ?? '#6c757d';
              echo "<div class='d-flex justify-content-between align-items-center mb-2 p-2 rounded' style='background:#f8faff;'>
                      <span><i class='bi bi-circle-fill me-2' style='color:{$color};font-size:.6rem;'></i>{$row['faculty_username']}</span>
                      <span class='badge bg-primary'>{$row['total_score']}%</span>
                    </div>";
              $rank++;
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row g-4 mb-4">
    <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="0">
      <div class="card">
        <div class="card-body">
          <h6 class="fw-semibold mb-3"><i class="bi bi-pie-chart me-2 text-primary"></i>Performance Overview</h6>
          <canvas id="performanceChart" height="220"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="80">
      <div class="card">
        <div class="card-body">
          <h6 class="fw-semibold mb-3"><i class="bi bi-graph-up me-2 text-purple" style="color:#6610f2;"></i>Scoring Trend</h6>
          <canvas id="trendChart" height="220"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="row g-4">
    <div class="col-lg-6" data-aos="fade-up">
      <div class="card">
        <div class="card-body d-flex gap-3 align-items-center">
          <div style="width:52px;height:52px;border-radius:14px;" class="bg-g-teal d-flex align-items-center justify-content-center">
            <i class="bi bi-journal-bookmark-fill text-white fs-4"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">Professional Development</h6>
            <p class="text-muted mb-2" style="font-size:.83rem;">Track certifications, workshops &amp; training records.</p>
            <a href="analytics/view_professional_dev.php" class="btn btn-gradient btn-sm">View Records →</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="80">
      <div class="card">
        <div class="card-body d-flex gap-3 align-items-center">
          <div style="width:52px;height:52px;border-radius:14px;" class="bg-g-pink d-flex align-items-center justify-content-center">
            <i class="bi bi-file-earmark-bar-graph-fill text-white fs-4"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">Evaluation Report</h6>
            <p class="text-muted mb-2" style="font-size:.83rem;">Generate or download overall evaluation summary.</p>
            <a href="evaluation/report_generator.php" class="btn btn-gradient btn-sm">Open Report →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const ctx1 = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx1, {
  type: 'doughnut',
  data: {
    labels: ['Excellent', 'Good', 'Average', 'Poor'],
    datasets: [{ data: [45, 30, 15, 10], backgroundColor: ['#0d6efd', '#20c997', '#ffc107', '#dc3545'], borderWidth: 0 }]
  },
  options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
const ctx2 = document.getElementById('trendChart').getContext('2d');
new Chart(ctx2, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{ label: 'Avg Faculty Score', data: [78, 82, 85, 90, 87, 92],
      borderColor: '#6610f2', backgroundColor: 'rgba(102,16,242,0.08)', tension: 0.4, fill: true, pointBackgroundColor: '#6610f2' }]
  },
  options: { responsive: true, scales: { y: { min: 60, max: 100 } } }
});
</script>

<?php include("templates/footer.php"); ?>
