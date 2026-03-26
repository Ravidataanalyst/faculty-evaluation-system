<?php
require_once __DIR__ . '/includes/csrf.php';
include("session.php");
include("db.php");

if ($_SESSION['role'] !== 'student') {
    echo "Access Denied!";
    exit();
}

$facultyList = $conn->query("SELECT username FROM users WHERE role='faculty'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Submit Feedback</title>
  <!-- Bootstrap 5 -->
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
      padding-top: 40px;
      padding-bottom: 40px;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      border: none;
      overflow: hidden;
    }
    .glass-header {
      background: linear-gradient(90deg, #198754, #20c997);
      color: white;
      padding: 18px;
      text-align: center;
      border-bottom: none;
    }
    .btn-feedback {
      background: linear-gradient(90deg, #198754, #20c997);
      color: white;
      border: none;
      padding: 12px;
      font-weight: 600;
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    .btn-feedback:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 15px rgba(32, 201, 151, 0.3);
      color: white;
    }
    .form-label {
      font-weight: 600;
      color: #495057;
    }
    .form-control, .form-select {
      border-radius: 10px;
      border: 1px solid #ced4da;
    }
    .form-control:focus, .form-select:focus {
      border-color: #20c997;
      box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25);
    }
    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 100;
    }
  </style>
</head>
<body>
<a href="dashboard_student.php" class="btn btn-light shadow-sm back-btn"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
<div class="container">
  <div class="card glass-card mx-auto" style="max-width: 650px;" data-aos="zoom-in" data-aos-duration="600">
    <div class="glass-header">
      <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Faculty Feedback Form</h4>
    </div>
    <div class="card-body p-4 p-md-5">
      <form action="feedback_handler.php" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
          <label>Select Faculty</label>
          <select name="faculty_username" class="form-control" required>
            <option value="">-- Select Faculty --</option>
            <?php while($fac = $facultyList->fetch_assoc()) { ?>
              <option value="<?= $fac['username'] ?>"><?= $fac['username'] ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3">
          <label>Communication Skills (1–5)</label>
          <input type="number" name="communication" class="form-control" min="1" max="5" required>
        </div>

        <div class="mb-3">
          <label>Punctuality (1–5)</label>
          <input type="number" name="punctuality" class="form-control" min="1" max="5" required>
        </div>

        <div class="mb-3">
          <label>Subject Knowledge (1–5)</label>
          <input type="number" name="knowledge" class="form-control" min="1" max="5" required>
        </div>

        <div class="mb-3">
          <label>Feedback Comments</label>
          <textarea name="feedback_text" rows="4" class="form-control" placeholder="Write your honest feedback..." required></textarea>
        </div>

        <button type="submit" class="btn btn-feedback w-100"><i class="bi bi-send-fill me-2"></i> Submit Feedback</button>
      </form>
    </div>
  </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
