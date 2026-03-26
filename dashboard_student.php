<?php include("session_student.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- AOS Animation CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #eef5ff, #dbeafe);
      min-height: 100vh;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      align-items: center;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      border: none;
      overflow: hidden;
    }
    .glass-header {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
      color: white;
      padding: 20px;
      text-align: center;
      border-bottom: none;
    }
    .btn-custom {
      border-radius: 12px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .btn-feedback {
      background: linear-gradient(90deg, #198754, #20c997);
      color: white;
      border: none;
    }
    .btn-feedback:hover {
      background: linear-gradient(90deg, #157347, #1ba87e);
      color: white;
    }
    .btn-logout {
      background: #f8f9fa;
      color: #dc3545;
      border: 1px solid #dc3545;
    }
    .btn-logout:hover {
      background: #dc3545;
      color: white;
    }
    .welcome-text {
      font-size: 1.25rem;
      color: #495057;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-5">
        <div class="card glass-card" data-aos="fade-up" data-aos-duration="800">
          <div class="glass-header">
            <h3 class="mb-0"><i class="bi bi-mortarboard-fill me-2"></i> Student Portal</h3>
          </div>
          <div class="card-body text-center p-5">
            <div class="mb-4">
              <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
            </div>
            <h4 class="mb-4 welcome-text">Welcome back,<br><strong class="text-dark fs-3"><?= htmlspecialchars($_SESSION['student_username']) ?></strong></h4>
            
            <a href="submit_feedback.php" class="btn btn-custom btn-feedback w-100 mb-3">
              <i class="bi bi-clipboard2-check me-2"></i> Submit Faculty Feedback
            </a>
            
            <a href="logout.php" class="btn btn-custom btn-logout w-100">
              <i class="bi bi-box-arrow-right me-2"></i> Secure Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstap JS + AOS Script -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
