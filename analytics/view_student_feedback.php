<?php
include("../session.php");
include("../db.php");

if ($_SESSION['role'] !== 'dept_head') {
    echo "Access Denied!";
    exit();
}

$facultyList = $conn->query("SELECT DISTINCT faculty_username FROM student_feedback");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Feedback Records</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-3">

<div class="container-fluid">
  <h3 class="text-primary mb-4 text-center">🧑‍🎓 Student Feedback per Faculty</h3>

  <?php while ($fac = $facultyList->fetch_assoc()): ?>
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-dark text-white">
        Feedback for Faculty: <strong><?= $fac['faculty_username'] ?></strong>
      </div>
      <div class="card-body">
        <?php
        $faculty = $fac['faculty_username'];
        $feedbacks = $conn->query("SELECT * FROM student_feedback WHERE faculty_username = '$faculty' ORDER BY submitted_on DESC");
        if ($feedbacks->num_rows === 0): ?>
          <p class="text-muted">No feedback submitted yet.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead class="table-secondary text-center">
                <tr>
                  <th>Student</th>
                  <th>Communication</th>
                  <th>Punctuality</th>
                  <th>Knowledge</th>
                  <th>Feedback</th>
                  <th>Submitted On</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <?php while ($row = $feedbacks->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['student_username'] ?></td>
                  <td><?= $row['communication'] ?></td>
                  <td><?= $row['punctuality'] ?></td>
                  <td><?= $row['knowledge'] ?></td>
                  <td><?= htmlspecialchars($row['feedback_text']) ?></td>
                  <td><?= $row['submitted_on'] ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>
</body>
</html>
