<?php
require_once __DIR__ . '/includes/csrf.php';
// ✅ Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Faculty Evaluation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* === Background Styling === */
    body {
      background: linear-gradient(135deg, #eef5ff, #dbeafe);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    /* === Glassmorphism Card === */
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 420px;
      padding: 25px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 35px rgba(0, 0, 0, 0.2);
    }

    /* === Header Styling === */
    .login-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .login-header h4 {
      font-weight: 700;
      color: #0d6efd;
    }
    .login-header p {
      font-size: 14px;
      color: #6c757d;
    }

    /* === Input Groups === */
    .input-group-text {
      background-color: #f8f9fa;
      border-right: none;
      font-size: 1.1rem;
    }
    .form-control {
      border-left: none;
      padding-left: 0;
    }
    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    /* === Button Styling === */
    .btn-primary {
      background: #0d6efd;
      border: none;
      border-radius: 8px;
      padding: 10px;
      font-weight: 600;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-primary:hover {
      background: #0b5ed7;
      transform: scale(1.03);
    }

    /* === Footer Text === */
    .footer-text {
      text-align: center;
      font-size: 13px;
      color: #6c757d;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-header">
      <h4><i class="bi bi-shield-lock"></i> Faculty Evaluation Login</h4>
      <p>Secure login with OTP verification</p>
    </div>

    <form action="send_otp.php" method="POST" novalidate>
      <?php echo csrf_field(); ?>
      <!-- Username -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Username</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
      </div>

      <!-- Role Selection -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Select Role</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-people"></i></span>
          <select name="role" class="form-select" required>
            <option value="">-- Choose your role --</option>
            <option value="admin">Admin</option>
            <option value="faculty">Faculty</option>
            <option value="dept_head">Department Head</option>
            <option value="student">Student</option>
          </select>
        </div>
      </div>

      <!-- Login Button -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-box-arrow-in-right"></i> Login & Send OTP
        </button>
      </div>
    </form>

    <div class="footer-text">
      © <?php echo date("Y"); ?> Faculty Evaluation System. All Rights Reserved.
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
