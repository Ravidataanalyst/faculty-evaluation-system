<?php
include("session.php");
if ($_SESSION['role'] !== 'admin') exit("Access Denied!");

include("templates/header.php");
include("includes/sidebar_admin.php");
include("db.php");
include("functions/dashboard_metrics.php");
include("functions/notifications.php");

$admin_username = $_SESSION['username'];
?>

<div class="main-content">

  <!-- ===== Top Header ===== -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
      <h2 class="page-title mb-1">🎓 Welcome, <?= htmlspecialchars($admin_username) ?>!</h2>
      <span class="text-muted" style="font-size:.85rem;">Faculty Evaluation System · Admin</span>
    </div>
    <button id="themeToggle" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-moon-fill me-1"></i> Dark Mode
    </button>
  </div>

  <!-- ===== Floating Notifications ===== -->
  <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

  <!-- ===== Metrics Overview ===== -->
  <div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
      <div class="metric-card bg-g-blue">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="metric-label">Total Evaluations</div>
            <div class="metric-value"><?= totalEvaluations($conn) ?></div>
          </div>
          <i class="bi bi-graph-up metric-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="80">
      <div class="metric-card bg-g-orange">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="metric-label">Pending Reviews</div>
            <div class="metric-value"><?= pendingReviews($conn) ?></div>
          </div>
          <i class="bi bi-hourglass-split metric-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="160">
      <div class="metric-card bg-g-teal">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="metric-label">Average Score</div>
            <div class="metric-value"><?= averageScore($conn) ?>%</div>
          </div>
          <i class="bi bi-bar-chart-line metric-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="240">
      <div class="metric-card bg-g-cyan">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="metric-label">Documents Uploaded</div>
            <div class="metric-value"><?= totalDocuments($conn) ?></div>
          </div>
          <i class="bi bi-folder-check metric-icon"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== Analytics Section ===== -->
  <h5 class="fw-bold mb-3 page-title" data-aos="fade-right">📊 Analytics &amp; Reports</h5>
  <div class="row g-4">
    <?php
    $cards = [
      ["icon" => "bi-bar-chart-steps",    "title" => "Department-wise Averages",   "desc" => "Compare faculty performance across departments visually.",          "link" => "analytics/department_scores.php"],
      ["icon" => "bi-activity",            "title" => "Time-series Trends",          "desc" => "Visualize faculty score patterns semester-wise.",                    "link" => "analytics/score_trends.php"],
      ["icon" => "bi-radar",               "title" => "Faculty Radar Chart",          "desc" => "Compare a faculty's scores vs overall average.",                     "link" => "analytics/faculty_radar.php"],
      ["icon" => "bi-trophy",              "title" => "Top Performing Faculty",       "desc" => "View the top 5 scoring faculty based on evaluation metrics.",        "link" => "analytics/top_faculty.php"],
      ["icon" => "bi-exclamation-triangle","title" => "At-Risk Faculty (Predictive)","desc" => "Detect faculty with low or declining scores and suggest actions.",   "link" => "analytics/predictive_warnings.php"],
    ];
    foreach ($cards as $i => $c) { ?>
      <div class="col-lg-6 col-md-12" data-aos="zoom-in" data-aos-delay="<?= $i * 60 ?>">
        <div class="card h-100">
          <div class="card-body d-flex gap-3 align-items-center">
            <div style="flex-shrink:0;width:52px;height:52px;border-radius:14px;"
                 class="bg-g-blue d-flex align-items-center justify-content-center">
              <i class="bi <?= $c['icon'] ?> text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
              <h6 class="fw-bold mb-1"><?= $c['title'] ?></h6>
              <p class="text-muted mb-2" style="font-size:.83rem;"><?= $c['desc'] ?></p>
              <a href="<?= $c['link'] ?>" class="btn btn-gradient btn-sm">View Details →</a>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<script>
function showToast(message) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = "toast align-items-center text-bg-primary border-0 mb-2 show";
    toast.setAttribute("role", "alert");
    toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 6000);
}
function fetchNotifications() {
    fetch('notifications/fetch_notifications.php?role=admin')
        .then(res => res.json())
        .then(data => { if (Array.isArray(data) && data.length > 0) data.forEach(msg => showToast(msg.message)); })
        .catch(err => console.error("Error:", err));
}
fetchNotifications();
setInterval(fetchNotifications, 15000);
</script>

<?php include("templates/footer.php"); ?>
