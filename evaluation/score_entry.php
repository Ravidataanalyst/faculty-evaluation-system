<?php
require_once __DIR__ . '/../includes/csrf.php';
verify_csrf();
include('../session.php');
include('../db.php');
include('notify_after_score.php');

if ($_SESSION['role'] !== 'dept_head') {
    echo "Access denied!";
    exit();
}

if (!isset($_GET['evaluation_id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
<!DOCTYPE html>
<html>
<head>
  <title>Enter Evaluation ID</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">🔢 Score Faculty Evaluation</h4>
        <form method="get">
          <div class="mb-3">
            <label for="evaluation_id" class="form-label">Enter Evaluation ID:</label>
            <input type="number" name="evaluation_id" id="evaluation_id" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Proceed</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
<?php
exit();
}

$evaluation_id = isset($_GET['evaluation_id']) ? intval($_GET['evaluation_id']) : 0;
if (!$evaluation_id) {
    require_once __DIR__ . '/../includes/alert.php';
    swal_redirect('Error', 'Invalid evaluation ID.', 'error', 'score_entry.php');
}

$criteria = $conn->query("SELECT * FROM evaluation_criteria");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $totalWeightedScore = 0;
    $totalWeight = 0;

    foreach ($_POST['score'] as $criterion_id => $score) {
        $score = intval($score);
        $criterion_id = intval($criterion_id); // Prevent SQL Injection
        if ($score < 0 || $score > 100) continue;

        $q = $conn->query("SELECT weight FROM evaluation_criteria WHERE id = $criterion_id");
        $weight = $q->fetch_assoc()['weight'];

        $totalWeightedScore += ($score * $weight);
        $totalWeight += $weight;

        $stmt = $conn->prepare("INSERT INTO evaluation_scores (evaluation_id, criterion_id, score)
                                VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score=?");
        $stmt->bind_param("iiii", $evaluation_id, $criterion_id, $score, $score);
        $stmt->execute();
    }

    $totalScore = ($totalWeight > 0) ? round($totalWeightedScore / $totalWeight, 2) : 0;
    $review_comments = trim($_POST['review_comments']);

    $stmt = $conn->prepare("UPDATE evaluations SET status='completed', review_comments=? WHERE id=?");
    $stmt->bind_param("si", $review_comments, $evaluation_id);
    $stmt->execute();

    $eval = $conn->query("SELECT faculty_username FROM evaluations WHERE id=$evaluation_id")->fetch_assoc();
    $faculty = $eval['faculty_username'];
    $user = $conn->query("SELECT email FROM users WHERE username='$faculty'")->fetch_assoc();

    notifyFacultyAfterScore($user['email'], $faculty, $totalScore, $review_comments);

    require_once __DIR__ . '/../includes/alert.php';
    swal_redirect('Success', 'Scores submitted and email sent.', 'success', 'head_review.php');
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Score Evaluation</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h4 class="mb-4">📝 Scoring for Evaluation ID: <?= $evaluation_id ?></h4>
    <form method="POST">
      <?php echo csrf_field(); ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light text-center">
            <tr>
              <th>Criterion</th>
              <th>Weight (%)</th>
              <th>Score (0–100)</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $criteria->fetch_assoc()) { ?>
              <tr class="text-center">
                <td><?= $row['criterion_name'] ?></td>
                <td><?= $row['weight'] ?>%</td>
                <td>
                  <input type="number" name="score[<?= $row['id'] ?>]" min="0" max="100" class="form-control" required>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <div class="mb-3">
        <label for="review_comments" class="form-label">💬 Review Comments</label>
        <textarea name="review_comments" class="form-control" rows="3" required></textarea>
      </div>

      <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-success">✅ Submit Scores</button>
        <a href="score_entry.php" class="btn btn-outline-secondary">🔁 Start Over</a>
      </div>
    </form>
  </div>
</body>
</html>
