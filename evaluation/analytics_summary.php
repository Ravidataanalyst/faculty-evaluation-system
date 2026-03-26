<?php
// analytics_summary.php
// Put this file at: evaluation/analytics_summary.php

// --- keep session handling as it currently works in your project ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include DB and session (do not change your session.php)
require_once __DIR__ . "/../db.php";
$sessionPath = __DIR__ . "/../session.php";
if (file_exists($sessionPath)) {
    require_once $sessionPath;
} else {
    die("<h3 style='color:red; text-align:center; margin-top:20px;'>⚠️ session.php missing!</h3>");
}

// role check (page is for admin and dept_head)
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'dept_head'])) {
    die("<h3 style='color:red; text-align:center; margin-top:20px;'>Access Denied!</h3>");
}

$role = $_SESSION['role'];

// build safe department filter (only if dept_head AND department exists in session)
$whereBase = [];
if ($role === 'dept_head' && !empty($_SESSION['department'])) {
    $deptEsc = mysqli_real_escape_string($conn, $_SESSION['department']);
    $whereBase[] = "e.department = '$deptEsc'";
}

// helper to build WHERE clause from base + extra conditions
function build_where(array $base, array $extra = []) {
    $conds = array_merge($base, $extra);
    if (count($conds) === 0) return "";
    return " WHERE " . implode(" AND ", $conds);
}

/* -------------------------
   Overview stats
   ------------------------- */
$whereAll = build_where($whereBase);

// total submissions
$q = "SELECT COUNT(*) AS cnt FROM evaluations e $whereAll";
$res = $conn->query($q);
$totalSubmissions = ($res && $row = $res->fetch_assoc()) ? (int)$row['cnt'] : 0;

// pending
$wherePending = build_where($whereBase, ["e.status = 'pending'"]);
$q = "SELECT COUNT(*) AS cnt FROM evaluations e $wherePending";
$res = $conn->query($q);
$pendingEvaluations = ($res && $row = $res->fetch_assoc()) ? (int)$row['cnt'] : 0;

// reviewed
$whereReviewed = build_where($whereBase, ["e.status = 'reviewed'"]);
$q = "SELECT COUNT(*) AS cnt FROM evaluations e $whereReviewed";
$res = $conn->query($q);
$reviewedEvaluations = ($res && $row = $res->fetch_assoc()) ? (int)$row['cnt'] : 0;

/* -------------------------
   Criteria averages
   ------------------------- */
$criteriaLabels = [];
$criteriaScores = [];
$q = "
    SELECT c.criterion_name, ROUND(AVG(s.score),2) AS avg_score
    FROM evaluation_scores s
    INNER JOIN evaluation_criteria c ON s.criterion_id = c.id
    INNER JOIN evaluations e ON s.evaluation_id = e.id
    " . $whereAll . "
    GROUP BY c.id, c.criterion_name
    ORDER BY c.id
";
$res = $conn->query($q);
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $criteriaLabels[] = $r['criterion_name'];
        // ensure numeric
        $criteriaScores[] = is_null($r['avg_score']) ? 0 : (float)$r['avg_score'];
    }
}

/* -------------------------
   Top 5 faculty (overall average)
   ------------------------- */
$topFacultyLabels = [];
$topFacultyScores = [];
$q = "
    SELECT e.faculty_username, ROUND(AVG(s.score),2) AS avg_score
    FROM evaluation_scores s
    INNER JOIN evaluations e ON s.evaluation_id = e.id
    " . $whereAll . "
    GROUP BY e.faculty_username
    ORDER BY avg_score DESC
    LIMIT 5
";
$res = $conn->query($q);
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $topFacultyLabels[] = $r['faculty_username'];
        $topFacultyScores[] = is_null($r['avg_score']) ? 0 : (float)$r['avg_score'];
    }
}

/* -------------------------
   Semester trend (avg per semester)
   ------------------------- */
$semesterLabels = [];
$semesterScores = [];
$q = "
    SELECT e.semester, ROUND(AVG(s.score),2) AS avg_score
    FROM evaluation_scores s
    INNER JOIN evaluations e ON s.evaluation_id = e.id
    " . $whereAll . "
    GROUP BY e.semester
    ORDER BY e.semester
";
$res = $conn->query($q);
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $semesterLabels[] = $r['semester'];
        $semesterScores[] = is_null($r['avg_score']) ? 0 : (float)$r['avg_score'];
    }
}

/* -------------------------
   Department-wise averages (admin only)
   ------------------------- */
$deptLabels = [];
$deptScores = [];
if ($role === 'admin') {
    $q = "
        SELECT e.department, ROUND(AVG(s.score),2) AS avg_score
        FROM evaluation_scores s
        INNER JOIN evaluations e ON s.evaluation_id = e.id
        GROUP BY e.department
        ORDER BY avg_score DESC
    ";
    $res = $conn->query($q);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $deptLabels[] = $r['department'];
            $deptScores[] = is_null($r['avg_score']) ? 0 : (float)$r['avg_score'];
        }
    }
}

/* -------------------------
   Faculty performance table (top 10 overall)
   ------------------------- */
$facultyTable = [];
$q = "
    SELECT e.faculty_username, ROUND(AVG(s.score),2) AS avg_score, COUNT(DISTINCT e.id) AS submissions
    FROM evaluation_scores s
    INNER JOIN evaluations e ON s.evaluation_id = e.id
    " . $whereAll . "
    GROUP BY e.faculty_username
    ORDER BY avg_score DESC
    LIMIT 10
";
$res = $conn->query($q);
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $facultyTable[] = [
            'username' => $r['faculty_username'],
            'avg' => is_null($r['avg_score']) ? 0 : (float)$r['avg_score'],
            'submissions' => (int)$r['submissions']
        ];
    }
}

/* -------------------------
   Safe JSON encode for JS (ensure arrays exist)
   ------------------------- */
function js_json($arr) {
    return json_encode(array_values($arr), JSON_UNESCAPED_UNICODE);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Analytics Summary</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.min.js"></script>

<style>
    :root{
        --bg:#f6f8fb;
        --card:#ffffff;
        --muted:#6b7280;
        --heading:#0f172a; /* high contrast */
        --accent-1:#0d6efd;
        --accent-2:#20c997;
        --accent-3:#6610f2;
    }

    html,body { height:100%; }
    body {
        margin:0;
        font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        background: var(--bg);
        color: #0b1220;
    }

    .container-fluid { max-width:1400px; }

    /* Card look */
    .card {
        background: var(--card);
        border: 0;
        border-radius: 12px;
        box-shadow: 0 6px 22px rgba(2,6,23,0.06);
    }

    /* STAT CARDS */
    .stat-card {
        padding:20px;
        min-height:110px;
    }
    .stat-title { color:var(--muted); font-size:0.92rem; }
    .stat-value { font-size:1.9rem; font-weight:700; color:#071032; }

    /* Section titles: bold with high contrast */
    .section-title {
        color: var(--heading);
        font-weight:800;
        letter-spacing:0.2px;
        margin-bottom:12px;
        display:flex;
        align-items:center;
        gap:10px;
    }
    .section-sub { color:var(--muted); font-size:0.9rem; }

    /* Chart boxes: force consistent heights to align */
    .chart-box {
        height:320px;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:6px 6px;
    }
    .chart-box canvas { width:100% !important; height:100% !important; display:block; }

    /* Table headers high contrast */
    .table thead th {
        background:#0b1220;
        color:#fff;
        font-weight:800;
        border:0;
    }
    .table tbody tr td { vertical-align:middle; }

    /* Small muted */
    .small-muted { color:var(--muted); font-size:0.92rem; }

    /* Make sure cards in same row are same height */
    .align-stretch { align-items: stretch; }

    /* Responsive tweaks */
    @media (max-width:991px) {
        .chart-box { height:260px; }
    }
    @media (max-width:575px) {
        .chart-box { height:220px; }
    }

    /* little icons look */
    .stat-emoji { font-size:34px; opacity:0.18; }
</style>
</head>
<body>
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 section-title">
                <img src="../assets/icon-analytics.png" alt="" style="height:34px;">
                <span>Analytics Summary</span>
            </h2>
            <div class="section-sub">A compact, aligned view of evaluations and trends</div>
        </div>
        <div class="text-end small-muted">
            Role: <strong style="color:var(--heading)"><?php echo htmlspecialchars(ucfirst($role)); ?></strong><br>
            <span class="small-muted">Generated: <?php echo date('d M Y, H:i'); ?></span>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4 align-stretch">
        <div class="col-md-4">
            <div class="card stat-card h-100 d-flex">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <div class="stat-title">TOTAL SUBMISSIONS</div>
                        <div class="stat-value"><?php echo $totalSubmissions; ?></div>
                    </div>
                    <div class="stat-emoji">📄</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100 d-flex">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <div class="stat-title">PENDING EVALUATIONS</div>
                        <div class="stat-value"><?php echo $pendingEvaluations; ?></div>
                    </div>
                    <div class="stat-emoji">⏳</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100 d-flex">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <div class="stat-title">REVIEWED EVALUATIONS</div>
                        <div class="stat-value"><?php echo $reviewedEvaluations; ?></div>
                    </div>
                    <div class="stat-emoji">✅</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 align-stretch">

        <!-- Left: Criteria + Semester -->
        <div class="col-lg-7 d-flex flex-column gap-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0 section-title" style="font-size:1rem;">Average Scores by Criteria</h5>
                            <div class="section-sub">Shows average per defined criterion</div>
                        </div>
                    </div>
                    <div class="mt-3 chart-box flex-fill">
                        <canvas id="criteriaChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0 section-title" style="font-size:1rem;">Semester Trend (Average)</h5>
                            <div class="section-sub">Average score trend across semesters</div>
                        </div>
                    </div>
                    <div class="mt-3 chart-box flex-fill">
                        <canvas id="semesterChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Top Faculty + Dept (if admin) -->
        <div class="col-lg-5 d-flex flex-column gap-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div>
                        <h6 class="mb-1 section-title" style="font-size:0.95rem;">Top 5 Faculty</h6>
                        <div class="section-sub">Highest overall average scores</div>
                    </div>
                    <div class="mt-3 chart-box flex-fill">
                        <?php if (count($topFacultyLabels) === 0): ?>
                            <div class="small-muted">No data to show.</div>
                        <?php else: ?>
                            <canvas id="topFacultyChart"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($role === 'admin'): ?>
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div>
                        <h6 class="mb-1 section-title" style="font-size:0.95rem;">Department-wise Averages</h6>
                        <div class="section-sub">Compare departments by average score</div>
                    </div>
                    <div class="mt-3 chart-box flex-fill">
                        <?php if (count($deptLabels) === 0): ?>
                            <div class="small-muted">No department data yet.</div>
                        <?php else: ?>
                            <canvas id="deptChart"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4 mt-3 align-stretch">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div>
                        <h6 class="mb-2 section-title" style="font-size:0.98rem;">Faculty Performance (Top 10)</h6>
                        <div class="section-sub">Top performers and submission counts</div>
                    </div>

                    <div class="mt-3 flex-fill">
                        <?php if (empty($facultyTable)): ?>
                            <div class="small-muted">No faculty performance data available.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Faculty</th>
                                            <th>Average Score</th>
                                            <th>Submissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($facultyTable as $f): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($f['username']); ?></td>
                                                <td><strong><?php echo $f['avg']; ?></strong></td>
                                                <td><?php echo $f['submissions']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div>
                        <h6 class="mb-2 section-title" style="font-size:0.98rem;">Criteria Summary</h6>
                        <div class="section-sub">Quick averages per criterion</div>
                    </div>

                    <div class="mt-3 flex-fill">
                        <div class="table-responsive h-100">
                            <table class="table mb-0">
                                <thead>
                                    <tr><th>Criterion</th><th class="text-end">Average</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($criteriaLabels)): ?>
                                        <tr><td colspan="2" class="small-muted">No criteria scores yet.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($criteriaLabels as $i => $c): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($c); ?></td>
                                                <td class="text-end"><strong><?php echo $criteriaScores[$i] ?? 0; ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Charts scripts -->
<script>
const criteriaLabels = <?php echo js_json($criteriaLabels); ?>;
const criteriaScores = <?php echo js_json($criteriaScores); ?>;

const topFacultyLabels = <?php echo js_json($topFacultyLabels); ?>;
const topFacultyScores = <?php echo js_json($topFacultyScores); ?>;

const semesterLabels = <?php echo js_json($semesterLabels); ?>;
const semesterScores = <?php echo js_json($semesterScores); ?>;

const deptLabels = <?php echo js_json($deptLabels); ?>;
const deptScores = <?php echo js_json($deptScores); ?>;

/* Professional palette (distinct, high-contrast) */
const PALETTE = [
    '#0d6efd','#6610f2','#6f42c1','#20c997','#fd7e14',
    '#e83e8c','#198754','#0dcaf0','#adb5bd','#ff8c42'
];

function pickColors(n){
    if (n <= PALETTE.length) return PALETTE.slice(0,n);
    // repeat palette if more items than colors
    const out = [];
    while(out.length < n) out.push(...PALETTE);
    return out.slice(0,n);
}

/* Global Chart defaults for consistent look */
Chart.defaults.font.family = "'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial";
Chart.defaults.color = "#101828";
Chart.defaults.plugins.legend.labels.boxWidth = 12;
Chart.defaults.plugins.tooltip.cornerRadius = 6;
Chart.defaults.plugins.tooltip.titleFont = { weight: 700 };

/* Criteria bar chart */
(function(){
    const el = document.getElementById('criteriaChart');
    if (!el) return;
    const colors = pickColors(criteriaLabels.length || 5);
    new Chart(el, {
        type: 'bar',
        data: {
            labels: criteriaLabels,
            datasets: [{
                label: 'Average Score',
                data: criteriaScores,
                backgroundColor: colors.map(c => c + 'CC'), // add alpha
                borderColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision:0 } }
            },
            plugins: { legend: { display: false } }
        }
    });
})();

/* Top faculty horizontal bar */
(function(){
    const el = document.getElementById('topFacultyChart');
    if (!el) return;
    const colors = pickColors(topFacultyLabels.length || 5);
    new Chart(el, {
        type: 'bar',
        data: {
            labels: topFacultyLabels,
            datasets: [{
                label: 'Avg Score',
                data: topFacultyScores,
                backgroundColor: colors.map(c => c + 'CC'),
                borderColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { beginAtZero: true, ticks: { precision:0 } }
            },
            plugins: { legend: { display: false } }
        }
    });
})();

/* Semester trend line */
(function(){
    const el = document.getElementById('semesterChart');
    if (!el) return;
    const color = '#0d6efd';
    new Chart(el, {
        type: 'line',
        data: {
            labels: semesterLabels,
            datasets: [{
                label: 'Average Score',
                data: semesterScores,
                borderColor: color,
                backgroundColor: color + '33',
                tension: 0.32,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: color
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision:0 } }
            },
            plugins: { legend: { display: false } }
        }
    });
})();

/* Department pie (admin) */
(function(){
    const el = document.getElementById('deptChart');
    if (!el) return;
    const colors = pickColors(deptLabels.length || 6);
    new Chart(el, {
        type: 'doughnut',
        data: {
            labels: deptLabels,
            datasets: [{
                data: deptScores,
                backgroundColor: colors.map(c => c + 'CC'),
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right' } }
        }
    });
})();

</script>
</body>
</html>
