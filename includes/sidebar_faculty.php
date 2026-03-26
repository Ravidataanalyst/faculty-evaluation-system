<?php
$current_page = basename($_SERVER['PHP_SELF']);
$username = htmlspecialchars($_SESSION['username'] ?? 'Faculty', ENT_QUOTES, 'UTF-8');
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
      <h5 class="mb-0">Faculty Eval</h5>
    </div>
    <span class="badge-role"><i class="bi bi-person-video3 me-1"></i>Faculty Panel</span>
    <div class="d-flex align-items-center gap-2 mt-2">
      <span class="online-dot"></span>
      <small style="color:rgba(255,255,255,.55); font-size:.75rem;"><?= $username ?></small>
    </div>
  </div>

  <nav class="flex-grow-1 overflow-auto py-2">
    <div class="sidebar-section-label">Overview</div>
    <a href="../dashboard_faculty.php" class="nav-link <?= $current_page === 'dashboard_faculty.php' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-section-label">My Work</div>
    <a href="../evaluation/faculty_submit.php" class="nav-link <?= $current_page === 'faculty_submit.php' ? 'active' : '' ?>">
      <i class="bi bi-send-check"></i> Submit Self-Evaluation
    </a>
    <a href="../evaluation/faculty_submissions.php" class="nav-link <?= $current_page === 'faculty_submissions.php' ? 'active' : '' ?>">
      <i class="bi bi-folder2-open"></i> My Submissions
    </a>
    <a href="../evaluation/faculty_pd_submit.php" class="nav-link <?= $current_page === 'faculty_pd_submit.php' ? 'active' : '' ?>">
      <i class="bi bi-journal-plus"></i> Add PD Activity
    </a>

    <div class="sidebar-section-label">Notifications</div>
    <a href="../notifications/all.php" class="nav-link <?= $current_page === 'all.php' ? 'active' : '' ?>">
      <i class="bi bi-bell"></i> All Notifications
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="../logout.php" class="nav-link">
      <i class="bi bi-box-arrow-left"></i> Logout
    </a>
  </div>
</aside>
