<?php
$current_page = basename($_SERVER['PHP_SELF']);
$username = htmlspecialchars($_SESSION['username'] ?? 'Dept Head', ENT_QUOTES, 'UTF-8');
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
      <h5 class="mb-0">Faculty Eval</h5>
    </div>
    <span class="badge-role"><i class="bi bi-building me-1"></i>Dept Head Panel</span>
    <div class="d-flex align-items-center gap-2 mt-2">
      <span class="online-dot"></span>
      <small style="color:rgba(255,255,255,.55); font-size:.75rem;"><?= $username ?></small>
    </div>
  </div>

  <nav class="flex-grow-1 overflow-auto py-2">
    <div class="sidebar-section-label">Overview</div>
    <a href="../dashboard_dept.php" class="nav-link <?= $current_page === 'dashboard_dept.php' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-section-label">Evaluation</div>
    <a href="../evaluation/head_review.php" class="nav-link <?= $current_page === 'head_review.php' ? 'active' : '' ?>">
      <i class="bi bi-clipboard2-check"></i> Review Evaluations
    </a>
    <a href="../evaluation/score_entry.php" class="nav-link <?= $current_page === 'score_entry.php' ? 'active' : '' ?>">
      <i class="bi bi-pencil-square"></i> Score Faculty
    </a>
    <a href="../evaluation/report_generator.php" class="nav-link <?= $current_page === 'report_generator.php' ? 'active' : '' ?>">
      <i class="bi bi-file-earmark-bar-graph"></i> Evaluation Report
    </a>
    <a href="../evaluation/analytics_summary.php" class="nav-link <?= $current_page === 'analytics_summary.php' ? 'active' : '' ?>">
      <i class="bi bi-graph-up-arrow"></i> Analytics Dashboard
    </a>

    <div class="sidebar-section-label">Insights</div>
    <a href="../analytics/view_student_feedback.php" class="nav-link <?= $current_page === 'view_student_feedback.php' ? 'active' : '' ?>">
      <i class="bi bi-chat-left-quote"></i> Student Feedback
    </a>
    <a href="../analytics/view_professional_dev.php" class="nav-link <?= $current_page === 'view_professional_dev.php' ? 'active' : '' ?>">
      <i class="bi bi-journal-bookmark"></i> Professional Dev
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="../logout.php" class="nav-link">
      <i class="bi bi-box-arrow-left"></i> Logout
    </a>
  </div>
</aside>
