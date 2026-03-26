<?php
include('../session.php');
include('../db.php');
include('notify_after_review.php');

if ($_SESSION['role'] !== 'dept_head') {
    echo "Access denied!";
    exit();
}

$result = $conn->query("SELECT * FROM evaluations WHERE status='pending' OR status='under review'");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $comments = $_POST['review_comments'];
    $reviewed_by = $_SESSION['username'];

    $stmt = $conn->prepare("UPDATE evaluations SET status=?, review_comments=?, reviewed_by=? WHERE id=?");
    $stmt->bind_param("sssi", $status, $comments, $reviewed_by, $id);
    $stmt->execute();

    $evalInfo = $conn->query("SELECT faculty_username FROM evaluations WHERE id=$id")->fetch_assoc();
    $faculty = $evalInfo['faculty_username'];
    $userInfo = $conn->query("SELECT email FROM users WHERE username='$faculty'")->fetch_assoc();

    notifyFacultyAfterReview($userInfo['email'], $faculty, $status, $comments);

    echo "<script>alert('Review submitted and notification sent.'); window.location.href='head_review.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Review Evaluations</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-3">

<div class="container">
  <h2 class="mb-4 text-center text-primary">📝 Department Head - Faculty Review Panel</h2>

  <?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info text-center">No evaluations to review right now.</div>
  <?php else: ?>
    <div class="row row-cols-1 g-4">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="col">
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
              Faculty: <?= $row['faculty_username'] ?> | Subject: <?= $row['subject'] ?>
            </div>
            <div class="card-body">
              <h5 class="card-title mb-3">📋 Self-Evaluation Details</h5>
              <ul class="list-group mb-3">
                <li class="list-group-item"><strong>📝 Summary:</strong> <?= nl2br($row['self_assessment']) ?></li>
                <li class="list-group-item"><strong>👩‍🏫 Teaching Effectiveness:</strong> <?= nl2br($row['teaching_effectiveness']) ?></li>
                <li class="list-group-item"><strong>📚 Research/Publications:</strong> <?= nl2br($row['research_contribution']) ?></li>
                <li class="list-group-item"><strong>🏫 Service to Institution:</strong> <?= nl2br($row['service_to_institution']) ?></li>
              </ul>

              <div class="mb-3">
                <strong>📎 Supporting File:</strong>
                <?php if ($row['supporting_file']) { ?>
                  <a href="../uploads/<?= $row['supporting_file'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">📄 View File</a>
                <?php } else { echo "No file uploaded."; } ?>
              </div>

              <hr>
              <h5 class="mb-2">🔍 Submit Your Review</h5>
              <form method="POST" class="row g-3">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <div class="col-12">
                  <textarea name="review_comments" class="form-control" rows="3" placeholder="Enter your review comments here..." required></textarea>
                </div>

                <div class="col-sm-6">
                  <select name="status" class="form-select" required>
                    <option value="under review">Under Review</option>
                    <option value="completed">Completed</option>
                  </select>
                </div>

                <div class="col-sm-6 text-sm-end">
                  <button type="submit" name="review" class="btn btn-success w-100">✅ Submit Review</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
