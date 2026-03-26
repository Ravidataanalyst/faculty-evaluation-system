<?php
session_start();

// If OTP or timestamp isn't set, redirect to login
if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_time'])) {
    header("Location: index.php");
    exit();
}

$error = null;

// Handle OTP form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_input = $_POST['otp'] ?? '';
    $stored_otp = $_SESSION['otp'];
    $otp_time   = $_SESSION['otp_time'];

    if (time() - $otp_time > 120) {
        $error = "OTP has expired. Please login again.";
        session_destroy();
    } elseif ($user_input == $stored_otp) {
        session_regenerate_id(true);
        $role = $_SESSION['role'];
        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);

        if ($role === 'admin')      { header("Location: dashboard_admin.php"); }
        elseif ($role === 'faculty')   { header("Location: dashboard_faculty.php"); }
        elseif ($role === 'dept_head') { header("Location: dashboard_dept.php"); }
        elseif ($role === 'student')   {
            $_SESSION['student_username'] = $_SESSION['username'];
            header("Location: dashboard_student.php");
        }
        exit();
    } else {
        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
        $error = "Incorrect OTP. Please try again.";
    }
}

// Calculate remaining seconds for countdown
$otp_age     = time() - ($_SESSION['otp_time'] ?? time());
$remaining   = max(0, 120 - $otp_age);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verification – Faculty Eval</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Inter', sans-serif;
    }
    .otp-wrapper {
      width: 100%;
      max-width: 440px;
      padding: 16px;
    }
    .otp-card {
      background: rgba(255,255,255,.97);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0,0,0,.35);
      overflow: hidden;
      animation: slideUp .6s cubic-bezier(.22,.61,.36,1) both;
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .otp-header {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      padding: 32px 28px 24px;
      text-align: center;
      color: #fff;
    }
    .otp-header .shield-icon {
      width: 64px; height: 64px;
      background: rgba(255,255,255,.15);
      border-radius: 18px;
      display: flex; align-items: center; justify-content: center;
      font-size: 2rem;
      margin: 0 auto 16px;
      border: 2px solid rgba(255,255,255,.2);
    }
    .otp-header h4 { font-weight: 700; margin-bottom: 6px; }
    .otp-header p  { opacity: .85; font-size: .875rem; margin: 0; }
    .otp-body { padding: 28px; }

    /* OTP digit inputs */
    .otp-inputs { display: flex; gap: 10px; justify-content: center; margin-bottom: 20px; }
    .otp-inputs input {
      width: 48px; height: 56px;
      text-align: center;
      font-size: 1.4rem; font-weight: 700;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      outline: none;
      transition: border .2s, box-shadow .2s;
    }
    .otp-inputs input:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 3px rgba(13,110,253,.15);
    }

    /* Hidden single input for form submit (stores combined digits) */
    .otp-hidden { display: none; }

    .btn-verify {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
      color: #fff; border: none; border-radius: 12px;
      padding: 13px; font-weight: 700; font-size: 1rem;
      width: 100%; transition: transform .2s, opacity .2s;
    }
    .btn-verify:hover { opacity: .88; transform: translateY(-2px); }

    .countdown { font-size: .8rem; color: #64748b; text-align: center; margin-bottom: 16px; }
    .countdown span { font-weight: 700; color: #0d6efd; }

    .alert { border-radius: 12px; }
    .back-link { text-align: center; margin-top: 16px; font-size: .85rem; }
    .back-link a { color: #0d6efd; text-decoration: none; font-weight: 600; }
    .back-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>
<div class="otp-wrapper">
  <div class="otp-card">
    <div class="otp-header">
      <div class="shield-icon mx-auto"><i class="bi bi-shield-lock-fill"></i></div>
      <h4>Two-Factor Verification</h4>
      <p>Enter the 6-digit OTP sent to your registered email address.</p>
    </div>

    <div class="otp-body">
      <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
          <i class="bi bi-x-circle-fill"></i>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
        <a href="index.php" class="btn btn-gradient w-100" style="background:linear-gradient(90deg,#0d6efd,#6610f2);color:#fff;border:none;border-radius:12px;padding:12px;font-weight:700;">
          <i class="bi bi-arrow-left-circle me-1"></i> Back to Login
        </a>
      <?php else: ?>
        <div class="countdown">
          OTP expires in <span id="timer"><?= $remaining ?></span>s
        </div>

        <form method="POST" id="otpForm">
          <!-- Visible digit boxes -->
          <div class="otp-inputs" id="otpBoxes">
            <?php for ($i = 0; $i < 6; $i++): ?>
              <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit">
            <?php endfor; ?>
          </div>
          <!-- Hidden input to collect the full OTP -->
          <input type="hidden" name="otp" id="otpHidden">

          <button type="submit" class="btn-verify">
            <i class="bi bi-check2-circle me-2"></i> Verify &amp; Login
          </button>
        </form>

        <div class="back-link">
          <a href="index.php"><i class="bi bi-arrow-left me-1"></i>Use a different account</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
// Digit auto-advance
const digits = document.querySelectorAll('.otp-digit');
digits.forEach((el, idx) => {
  el.addEventListener('input', () => {
    if (el.value && idx < digits.length - 1) digits[idx + 1].focus();
  });
  el.addEventListener('keydown', e => {
    if (e.key === 'Backspace' && !el.value && idx > 0) digits[idx - 1].focus();
  });
});

// On form submit: combine digits
document.getElementById('otpForm')?.addEventListener('submit', e => {
  const val = Array.from(digits).map(d => d.value).join('');
  document.getElementById('otpHidden').value = val;
});

// Countdown timer
let secs = <?= $remaining ?>;
const timerEl = document.getElementById('timer');
if (timerEl) {
  const t = setInterval(() => {
    secs--;
    timerEl.textContent = secs;
    if (secs <= 0) {
      clearInterval(t);
      timerEl.closest('.countdown').innerHTML = '<span style="color:#dc3545;">OTP expired – please login again.</span>';
      document.querySelector('.btn-verify').disabled = true;
    }
  }, 1000);
}
</script>
</body>
</html>
