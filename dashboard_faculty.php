<?php
include("session.php");
if ($_SESSION['role'] !== 'faculty') exit("Access Denied!");

include("templates/header.php");
include("includes/sidebar_faculty.php");
include("db.php");
include("functions/dashboard_metrics.php");
include("functions/notifications.php");

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
?>
<style>
  /* Layout polish */
  .main-content { min-height: 100vh; }
  .card { border: none; border-radius: 18px; box-shadow: 0 6px 20px rgba(0,0,0,.06); }
  .card-header { border-top-left-radius: 18px !important; border-top-right-radius: 18px !important; }
  .equal-h-320 { height: 320px; }
  /* Toasts container */
  .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 1090; }
</style>

<div class="main-content p-4 bg-light">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="fw-bold mb-1">🎓 Welcome, <?= $username ?>!</h2>
      <span class="badge bg-primary px-3 py-2">Faculty Dashboard</span>
    </div>
    <button id="themeToggle" class="btn btn-outline-dark">🌙 Dark Mode</button>
  </div>

  <!-- (Kept for backward compatibility) Hidden fallback alert box -->
  <div id="notification-box" class="d-none"></div>

  <!-- Metrics -->
  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
      <div class="card h-100 bg-primary text-white">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📤 Evaluations Submitted</h5>
            <span class="fs-3 fw-semibold"><?= mySubmissionCount($conn, $username) ?></span>
          </div>
          <div class="progress mt-3" style="height:8px;">
            <div class="progress-bar bg-light" style="width: <?= min(mySubmissionCount($conn, $username)*10,100) ?>%;"></div>
          </div>
          <a href="evaluation/faculty_submit.php" class="btn btn-light btn-sm mt-3">+ Submit New</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="card h-100 bg-success text-white">
        <div class="card-body">
          <h5 class="mb-2">📅 Last Submitted</h5>
          <p class="fs-5 mb-3"><?= lastSubmittedOn($conn, $username) ?: 'No submissions yet' ?></p>
          <a href="evaluation/faculty_submissions.php" class="btn btn-light btn-sm">📁 View All</a>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="card h-100 bg-warning text-dark">
        <div class="card-body">
          <h5 class="mb-2">🧑‍💼 Professional Development</h5>
          <p class="mb-3">Record FDPs, Certifications & Workshops</p>
          <a href="evaluation/faculty_pd_submit.php" class="btn btn-dark btn-sm">+ Add PD</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Analytics & Announcements -->
  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card h-100">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">📊 Performance Insights</h5>
        </div>
        <div class="card-body equal-h-320">
          <canvas id="performanceChart" style="height:100%;"></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0">📢 Announcements</h5>
        </div>
        <div class="card-body" style="max-height:320px; overflow-y:auto;">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">🎯 Submit all evaluations before <strong>15th July</strong></li>
            <li class="list-group-item">📚 SWAYAM course enrollments open</li>
            <li class="list-group-item">🗓️ FDP on AI Tools – <strong>20th July</strong></li>
            <li class="list-group-item">🖊️ Faculty dev report generation starts from August</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Achievements & Deadlines -->
  <div class="row g-4 mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0">🎖️ Recent Achievements</h5>
        </div>
        <div class="card-body" style="max-height:250px; overflow-y:auto;">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">🏅 "Best Paper" award at TechConf 2025</li>
            <li class="list-group-item">👨‍🏫 FDP on Python completed</li>
            <li class="list-group-item">🏆 AI-based Teaching Innovation award</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header bg-warning text-dark">
          <h5 class="mb-0">📅 Upcoming Deadlines</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">📝 Next Evaluation Due: <strong>31st July</strong></li>
            <li class="list-group-item">📑 Report Submission: <strong>10th Aug</strong></li>
            <li class="list-group-item">🎤 FDP Registration closes: <strong>15th Aug</strong></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Links -->
  <div class="card mb-4">
    <div class="card-header bg-dark text-white"><h5 class="mb-0">🔗 Quick Links</h5></div>
    <div class="card-body">
      <a href="evaluation/faculty_submit.php" class="btn btn-primary btn-sm m-1">+ Submit Evaluation</a>
      <a href="evaluation/faculty_submissions.php" class="btn btn-success btn-sm m-1">📁 View Submissions</a>
      <a href="evaluation/faculty_pd_submit.php" class="btn btn-warning btn-sm m-1">+ Add PD</a>
      <a href="notifications/all.php" class="btn btn-info btn-sm m-1">🔔 Notifications</a>
    </div>
  </div>
</div>

<!-- Floating Toasts container (auto-filled by JS) -->
<div class="toast-container" id="toast-container" aria-live="polite" aria-atomic="true"></div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* -------- Chart -------- */
(() => {
  const el = document.getElementById('performanceChart');
  if (!el) return;
  new Chart(el, {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May'],
      datasets: [{
        label: 'Evaluation Score',
        data: [70,75,80,78,85],
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13,110,253,.15)',
        fill: true, tension: .35, pointRadius: 3
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      scales: { y: { min: 0, max: 100, ticks: { stepSize: 10 } } },
      plugins: { legend: { display: false } }
    }
  });
})();

/* -------- Dark / Light Toggle -------- */
(() => {
  const btn = document.getElementById('themeToggle');
  if (!btn) return;
  btn.addEventListener('click', () => {
    document.body.classList.toggle('bg-dark');
    document.body.classList.toggle('text-white');
    btn.textContent = document.body.classList.contains('bg-dark') ? '☀️ Light Mode' : '🌙 Dark Mode';
  });
})();

/* -------- Toast Notifications --------
   Works with either:
   1) JSON from notifications/fetch_notifications.php?role=faculty&format=json
      -> [{title:"...", message:"...", type:"info|success|warning|danger", created_at:"..."}]
   2) Legacy HTML (we'll fallback to showing it inside #notification-box)
-------------------------------------------------- */
const TOAST_LIFETIME = 4500; // ms

function showToast({ title = 'Notification', message = '', type = 'info' }) {
  const colors = {
    info:   'text-bg-info',
    success:'text-bg-success',
    warning:'text-bg-warning',
    danger: 'text-bg-danger'
  };
  const cls = colors[type] || colors.info;
  const container = document.getElementById('toast-container');

  const wrapper = document.createElement('div');
  wrapper.className = `toast align-items-center ${cls} border-0 mb-2`;
  wrapper.setAttribute('role', 'alert');
  wrapper.setAttribute('aria-live', 'assertive');
  wrapper.setAttribute('aria-atomic', 'true');
  wrapper.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">
        <strong>${title}</strong><br>${message}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  `;
  container.appendChild(wrapper);
  const bsToast = new bootstrap.Toast(wrapper, { delay: TOAST_LIFETIME });
  bsToast.show();
}

async function fetchAndRenderToasts() {
  try {
    const res = await fetch('notifications/fetch_notifications.php?role=faculty&format=json', { cache: 'no-store' });
    const text = await res.text().then(t => t.trim());

    // If JSON, parse and toast
    if (text.startsWith('[') || text.startsWith('{')) {
      let items = [];
      try { items = JSON.parse(text); } catch(e) { items = []; }
      if (Array.isArray(items) && items.length) {
        items.slice(0, 5).forEach(n => showToast({
          title: n.title || 'Notification',
          message: n.message || '',
          type: (n.type || 'info').toLowerCase()
        }));
        return;
      }
    }

    // Fallback: treat as HTML and show in a visible alert box (legacy)
    const box = document.getElementById('notification-box');
    box.classList.remove('d-none');
    box.classList.add('alert','alert-info','mb-4');
    box.innerHTML = text || 'No notifications.';
  } catch (err) {
    console.error('Notifications error:', err);
  }
}

// Initial + periodic fetch (15s)
fetchAndRenderToasts();
setInterval(fetchAndRenderToasts, 15000);
</script>

<?php include("templates/footer.php"); ?>
