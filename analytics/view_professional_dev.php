<?php
include("../session.php");
if ($_SESSION['role'] !== 'dept_head') exit("Access Denied!");

include("../db.php");
include("../templates/header.php");
?>

<div class="container py-4">
  <h3 class="mb-4 text-info">📚 Faculty Professional Development Records</h3>

  <form method="GET" class="mb-3">
    <label for="faculty_username" class="form-label">Select Faculty:</label>
    <select name="faculty_username" id="faculty_username" class="form-select" onchange="this.form.submit()">
      <option value="">-- Choose Faculty --</option>
      <?php
      $facultyResult = $conn->query("SELECT username FROM users WHERE role='faculty'");
      while ($fac = $facultyResult->fetch_assoc()) {
        $selected = (isset($_GET['faculty_username']) && $_GET['faculty_username'] == $fac['username']) ? 'selected' : '';
        echo "<option value='{$fac['username']}' $selected>{$fac['username']}</option>";
      }
      ?>
    </select>
  </form>

  <?php
  if (isset($_GET['faculty_username']) && $_GET['faculty_username'] !== '') {
    $faculty = $_GET['faculty_username'];

    $query = "SELECT title, type, duration, pd_score, submitted_on, proof_file 
              FROM pd_records 
              WHERE faculty_username = '$faculty' 
              ORDER BY submitted_on DESC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      echo "<table class='table table-bordered table-striped'>
              <thead class='table-primary'>
                <tr>
                  <th>Title</th>
                  <th>Type</th>
                  <th>Duration (hrs)</th>
                  <th>Score</th>
                  <th>Submitted On</th>
                  <th>Proof</th>
                </tr>
              </thead>
              <tbody>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['type']}</td>
                <td>{$row['duration']}</td>
                <td><strong>{$row['pd_score']}</strong></td>
                <td>{$row['submitted_on']}</td>
                <td>";
        if ($row['proof_file']) {
          echo "<a class='btn btn-sm btn-outline-primary' href='../uploads/pd_proofs/{$row['proof_file']}' target='_blank'>View</a>";
        } else {
          echo "—";
        }
        echo "</td></tr>";
      }
      echo "</tbody></table>";
    } else {
      echo "<p class='text-muted'>No professional development records found for this faculty.</p>";
    }
  }
  ?>
</div>

<?php include("../templates/footer.php"); ?>
