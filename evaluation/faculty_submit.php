<?php include('../session.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Submit Evaluation</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-3">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card shadow-lg mb-5">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">📋 Faculty Self-Assessment Submission</h4>
        </div>
        <div class="card-body">
          <form action="evaluation_form_handler.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">📚 Subject</label>
              <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">🏛️ Department</label>
              <input type="text" name="department" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">📆 Semester</label>
              <input type="text" name="semester" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">🗒️ Self-Assessment Summary</label>
              <textarea name="self_assessment" class="form-control" rows="4" placeholder="Briefly describe your contributions this semester." required></textarea>
            </div>
            <hr>
            <h5 class="mb-3">📌 Self-Evaluation on Key Criteria</h5>
            <div class="mb-3">
              <label class="form-label">Teaching Effectiveness</label>
              <textarea name="teaching" class="form-control" rows="2" placeholder="Describe classroom delivery, feedback, and innovation..." required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Research/Publications</label>
              <textarea name="research" class="form-control" rows="2" placeholder="Mention papers published, research grants, conferences..." required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Service to Institution</label>
              <textarea name="service" class="form-control" rows="2" placeholder="Mentoring, committee work, event coordination..." required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">📎 Supporting File (PDF/DOC/DOCX)</label>
              <input type="file" name="supporting_file" accept=".pdf,.doc,.docx" class="form-control" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success">✅ Submit Evaluation</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
