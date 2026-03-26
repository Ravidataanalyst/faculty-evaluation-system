<?php
$current_page = basename($_SERVER['PHP_SELF']);
$username = htmlspecialchars($_SESSION['username'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
      <h5 class="mb-0">Faculty Eval</h5>
    </div>
    <span class="badge-role"><i class="bi bi-shield-check me-1"></i>Admin Panel</span>
    <div class="d-flex align-items-center gap-2 mt-2">
      <span class="online-dot"></span>
      <small style="color:rgba(255,255,255,.55); font-size:.75rem;"><?= $username ?></small>
    </div>
  </div>

  <nav class="flex-grow-1 overflow-auto py-2">
    <div class="sidebar-section-label">Overview</div>
    <a href="../dashboard_admin.php" class="nav-link <?= $current_page === 'dashboard_admin.php' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-section-label">Evaluation</div>
    <a href="../evaluation/define_criteria.php" class="nav-link <?= $current_page === 'define_criteria.php' ? 'active' : '' ?>">
      <i class="bi bi-sliders"></i> Define Criteria
    </a>
    <a href="../evaluation/admin_evaluations.php" class="nav-link <?= $current_page === 'admin_evaluations.php' ? 'active' : '' ?>">
      <i class="bi bi-clipboard2-data"></i> All Evaluations
    </a>
    <a href="../evaluation/report_generator.php" class="nav-link <?= $current_page === 'report_generator.php' ? 'active' : '' ?>">
      <i class="bi bi-file-earmark-bar-graph"></i> Evaluation Reports
    </a>
    <a href="../evaluation/analytics_summary.php" class="nav-link <?= $current_page === 'analytics_summary.php' ? 'active' : '' ?>">
      <i class="bi bi-graph-up-arrow"></i> Faculty Analytics
    </a>

    <div class="sidebar-section-label">Student Feedback</div>
    <a href="../feedback/all_feedbacks.php" class="nav-link <?= $current_page === 'all_feedbacks.php' ? 'active' : '' ?>">
      <i class="bi bi-chat-square-text"></i> Feedback Table
    </a>
    <a href="../analytics/student_feedback_chart.php" class="nav-link <?= $current_page === 'student_feedback_chart.php' ? 'active' : '' ?>">
      <i class="bi bi-bar-chart-line"></i> Feedback Chart
    </a>

    <div class="sidebar-section-label">Prof. Development</div>
    <a href="../analytics/pd_admin_view.php" class="nav-link <?= $current_page === 'pd_admin_view.php' ? 'active' : '' ?>">
      <i class="bi bi-journal-bookmark"></i> PD Records &amp; Scores
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="../logout.php" class="nav-link">
      <i class="bi bi-box-arrow-left"></i> Logout
    </a>
  </div>
</aside>
