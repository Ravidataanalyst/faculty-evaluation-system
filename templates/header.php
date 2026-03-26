<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Faculty Evaluation System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- AOS Animations -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    :root {
      --sidebar-width: 260px;
      --accent-blue:   #0d6efd;
      --accent-purple: #6610f2;
      --accent-teal:   #20c997;
      --surface:       #f4f7fe;
      --card-shadow:   0 4px 24px rgba(0,0,0,.07);
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--surface);
      color: #212529;
      margin: 0;
    }

    /* ── Sidebar ── */
    .sidebar {
      width: var(--sidebar-width);
      min-height: 100vh;
      background: linear-gradient(160deg, #0f172a 0%, #1e293b 60%, #0f2027 100%);
      position: fixed;
      top: 0; left: 0;
      display: flex;
      flex-direction: column;
      padding: 0;
      z-index: 1000;
      transition: transform .3s ease;
    }
    .sidebar-brand {
      padding: 22px 20px 18px;
      border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .sidebar-brand h5 {
      font-weight: 700;
      color: #fff;
      margin-bottom: 2px;
      font-size: 1rem;
    }
    .sidebar-brand .badge-role {
      font-size: .68rem;
      background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
      padding: 3px 10px;
      border-radius: 20px;
      color: #fff;
    }
    .sidebar-section-label {
      font-size: .65rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(255,255,255,.35);
      padding: 14px 20px 6px;
      font-weight: 600;
    }
    .sidebar .nav-link {
      color: rgba(255,255,255,.72);
      padding: 9px 20px;
      border-radius: 10px;
      margin: 2px 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: .875rem;
      font-weight: 500;
      transition: all .2s ease;
    }
    .sidebar .nav-link:hover {
      background: rgba(255,255,255,.09);
      color: #fff;
      transform: translateX(3px);
    }
    .sidebar .nav-link.active {
      background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
      color: #fff;
      box-shadow: 0 4px 14px rgba(13,110,253,.35);
    }
    .sidebar-footer {
      margin-top: auto;
      padding: 14px 10px;
      border-top: 1px solid rgba(255,255,255,.06);
    }
    .sidebar-footer .nav-link {
      color: #ef4444;
      background: rgba(239,68,68,.1);
    }
    .sidebar-footer .nav-link:hover {
      background: #ef4444;
      color: #fff;
    }
    .online-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: #22c55e;
      display: inline-block;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%,100% { box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
      50%      { box-shadow: 0 0 0 6px rgba(34,197,94,0); }
    }

    /* ── Main Content ── */
    .main-content {
      margin-left: var(--sidebar-width);
      padding: 28px;
      min-height: 100vh;
    }

    /* ── Cards ── */
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      transition: transform .2s, box-shadow .2s;
    }
    .card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,.11); }

    /* ── Metric Cards ── */
    .metric-card {
      border-radius: 18px;
      padding: 24px;
      color: #fff;
      border: none;
      box-shadow: 0 6px 24px rgba(0,0,0,.12);
      transition: transform .25s, box-shadow .25s;
    }
    .metric-card:hover { transform: translateY(-5px); box-shadow: 0 12px 36px rgba(0,0,0,.18); }
    .metric-card .metric-icon { font-size: 2.5rem; opacity: .8; }
    .metric-card .metric-value { font-size: 2rem; font-weight: 700; }
    .metric-card .metric-label { font-size: .8rem; opacity: .85; text-transform: uppercase; letter-spacing: .08em; }

    /* ── Gradient backgrounds ── */
    .bg-g-blue    { background: linear-gradient(135deg, #0d6efd, #6610f2); }
    .bg-g-teal    { background: linear-gradient(135deg, #20c997, #198754); }
    .bg-g-orange  { background: linear-gradient(135deg, #fd7e14, #ffc107); }
    .bg-g-pink    { background: linear-gradient(135deg, #e91e75, #6610f2); }
    .bg-g-cyan    { background: linear-gradient(135deg, #17a2b8, #0dcaf0); }

    /* ── Page Header ── */
    .page-title {
      font-weight: 700;
      background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* ── Tables ── */
    .table { border-radius: 12px; overflow: hidden; }
    .table thead th { background: #f8faff; font-weight: 600; border-bottom: 2px solid #e8edf5; }

    /* ── Buttons ── */
    .btn-gradient {
      background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
      color: #fff; border: none; border-radius: 10px;
      font-weight: 600; transition: all .3s;
    }
    .btn-gradient:hover { opacity: .88; transform: translateY(-1px); color: #fff; }

    /* ── Toast container ── */
    #toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 1090; }

    /* ── Dark mode toggle ── */
    body.dark-mode {
      background: #0f172a;
      color: #e2e8f0;
    }
    body.dark-mode .card { background: #1e293b; }
    body.dark-mode .table thead th { background: #1e293b; color: #c9d6e3; }
    body.dark-mode .main-content { background: #0f172a; }

    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .main-content { margin-left: 0; padding: 16px; }
    }
  </style>
</head>
<body>
<div class="d-flex">
