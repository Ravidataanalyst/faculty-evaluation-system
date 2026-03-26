<?php
include('../session.php');
if ($_SESSION['role'] !== 'faculty') {
    echo "Access Denied!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Professional Development Submission</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-3">

<div class="container">
  <div class="card shadow-lg mx-auto" style="max-width: 700px;">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">🧑‍💼 Submit Professional Development Activity</h4>
    </div>
    <div class="card-body">
      <form action="pd_handler.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">📚 Course / Workshop / Certification Title</label>
          <input type="text" name="course_title" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">🎓 Type of Activity</label>
          <select name="type" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="NPTEL">NPTEL / SWAYAM</option>
            <option value="Workshop">Workshop</option>
            <option value="Certification">Certification</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">⏱️ Duration (in hours)</label>
          <input type="number" name="duration_hours" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
          <label class="form-label">📎 Upload Proof (PDF/Image)</label>
          <input type="file" name="proof_file" class="form-control" accept=".pdf,.jpg,.png,.jpeg" required>
        </div>

        <button class="btn btn-success w-100" type="submit">✅ Submit Record</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
