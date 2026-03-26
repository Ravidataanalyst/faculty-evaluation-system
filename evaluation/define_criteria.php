<?php
include('../session.php');
include('../db.php');
include('../includes/csrf.php');

if ($_SESSION['role'] !== 'admin') {
  echo "Access denied!";
  exit();
}

$result = $conn->query("SELECT * FROM evaluation_criteria");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Define Evaluation Criteria</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #eef5ff, #dbeafe);
      min-height: 100vh;
      padding: 32px 0;
    }

    .criteria-card {
      border-radius: 20px;
      border: none;
      box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
      background: rgba(255, 255, 255, .97);
    }

    .criteria-header {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
      border-radius: 20px 20px 0 0;
      padding: 20px 24px;
      color: white;
    }

    .form-control,
    .form-select {
      border-radius: 10px;
      border: 1.5px solid #e2e8f0;
      transition: border .2s, box-shadow .2s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 3px rgba(13, 110, 253, .12);
    }

    .btn-add {
      background: linear-gradient(90deg, #198754, #20c997);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      padding: 10px 22px;
      transition: all .25s;
    }

    .btn-add:hover {
      opacity: .88;
      transform: translateY(-1px);
      color: #fff;
    }

    .table {
      border-radius: 14px;
      overflow: hidden;
    }

    .table thead th {
      background: #f8faff;
      font-weight: 600;
      font-size: .84rem;
      border-bottom: 2px solid #e8edf5;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .table tbody tr:hover {
      background: #f0f7ff;
    }

    .badge-weight {
      background: linear-gradient(90deg, #0d6efd, #6610f2);
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .8rem;
    }

    .btn-delete {
      background: linear-gradient(90deg, #ef4444, #dc2626);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 5px 14px;
      font-size: .82rem;
      font-weight: 600;
      transition: all .2s;
    }

    .btn-delete:hover {
      opacity: .85;
      transform: translateY(-1px);
    }

    .back-btn {
      color: #0d6efd;
      font-weight: 600;
      text-decoration: none;
    }

    .back-btn:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container" style="max-width:840px;">
    <div class="mb-3" data-aos="fade-right">
      <a href="../dashboard_admin.php" class="back-btn"><i class="bi bi-arrow-left me-1"></i>Back to Dashboard</a>
    </div>

    <!-- Add Criteria Card -->
    <div class="criteria-card mb-4" data-aos="fade-up">
      <div class="criteria-header">
        <h5 class="mb-0 fw-bold"><i class="bi bi-sliders2 me-2"></i> Define Evaluation Criteria</h5>
        <small class="opacity-75">Hello <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> — Add criteria
          used for faculty scoring. Combined weight must not exceed 100%.</small>
      </div>
      <div class="card-body p-4">
        <form action="criteria_handler.php" method="POST" class="row g-3 align-items-end">
          <?php echo csrf_field(); ?>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Criterion Name</label>
            <input type="text" name="criterion_name" class="form-control" placeholder="e.g., Teaching Effectiveness"
              required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Weight (%)</label>
            <input type="number" name="weight" class="form-control" min="1" max="100" placeholder="e.g., 25" required>
          </div>
          <div class="col-md-2">
            <button type="submit" name="add" class="btn-add w-100">
              <i class="bi bi-plus-circle me-1"></i> Add
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Existing Criteria -->
    <div class="criteria-card" data-aos="fade-up" data-aos-delay="80">
      <div class="card-body p-0">
        <div class="p-4 border-bottom">
          <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Existing Evaluation Criteria</h6>
        </div>
        <?php if ($result->num_rows === 0): ?>
          <div class="p-4 text-center text-muted">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
            No criteria defined yet. Start by adding a new one above.
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th class="ps-4">#</th>
                  <th>Criterion Name</th>
                  <th class="text-center">Weight</th>
                  <th class="text-center pe-4">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1;
                while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td class="ps-4 text-muted"><?= $i++ ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($row['criterion_name']) ?></td>
                    <td class="text-center"><span class="badge-weight"><?= $row['weight'] ?>%</span></td>
                    <td class="text-center pe-4">
                      <form action="criteria_handler.php" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete" class="btn-delete"
                          onclick="return confirm('Delete this criterion?')">
                          <i class="bi bi-trash3 me-1"></i>Delete
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>AOS.init({ duration: 600, once: true });</script>
</body>

</html>