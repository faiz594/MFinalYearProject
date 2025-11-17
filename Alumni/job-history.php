<?php
include "configure.php"; // Database connection
include "Alumni-sidebar.php";

// 🟩 Alumni is already logged in (session already active in your system)
$alumni_id = $_SESSION['alumni_id']; // use directly, no check

// Initialize SweetAlert message variables
$alert_type = '';
$alert_message = '';
$redirect = false;

// -------------------- Handle Add Job Form --------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_job'])) {
    $occupation        = trim($_POST['occupation']);
    $designation       = trim($_POST['designation']);
    $description       = trim($_POST['description']);
    $workplace_name    = trim($_POST['workplace_name']);
    $workplace_address = trim($_POST['workplace_address']);
    $start_date        = trim($_POST['start_date']);
    $end_date          = trim($_POST['end_date']);

    // ✅ Validation
    if (!empty($occupation) && !empty($designation) && !empty($start_date)) {
        // If end_date is empty, store NULL
        if (empty($end_date)) {
            $query = "INSERT INTO job_history 
                      (std_id, occupation, designation, description, workplace_name, workplace_address, start_date, end_date)
                      VALUES 
                      ('$alumni_id', '$occupation', '$designation', '$description', '$workplace_name', '$workplace_address', '$start_date', NULL)";
        } else {
            $query = "INSERT INTO job_history 
                      (std_id, occupation, designation, description, workplace_name, workplace_address, start_date, end_date)
                      VALUES 
                      ('$alumni_id', '$occupation', '$designation', '$description', '$workplace_name', '$workplace_address', '$start_date', '$end_date')";
        }

        if (mysqli_query($conn, $query)) {
            $alert_type = 'success';
            $alert_message = 'Job added successfully!';
            $redirect = true;
        } else {
            $alert_type = 'error';
            $alert_message = 'Database Error: ' . mysqli_error($conn);
        }
    } else {
        $alert_type = 'warning';
        $alert_message = 'Please fill in all required fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job History</title>

  <!-- ✅ Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- ✅ SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <!-- ✅ Main Content -->
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h4 class="mb-4"><i class="bi bi-briefcase me-2"></i> Job History</h4>

    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span>Alumni Job History</span>
        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#jobModal">
          <i class="bi bi-plus-circle me-1"></i> Add New Job
        </button>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
          <thead class="table-light">
            <tr>
              <th>Job ID</th>
              <th>Occupation</th>
              <th>Designation</th>
              <th>Workplace Name</th>
              <th>Start Date</th>
              <th>End Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT * FROM job_history WHERE std_id='$alumni_id' ORDER BY start_date DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $endDateDisplay = (!empty($row['end_date']) && $row['end_date'] != '0000-00-00')
                        ? htmlspecialchars($row['end_date'])
                        : '<span class="text-success fw-bold">Present</span>';

                    echo "<tr>
                            <td>" . htmlspecialchars($row['job_id']) . "</td>
                            <td>" . htmlspecialchars($row['occupation']) . "</td>
                            <td>" . htmlspecialchars($row['designation']) . "</td>
                            <td>" . htmlspecialchars($row['workplace_name']) . "</td>
                            <td>" . htmlspecialchars($row['start_date']) . "</td>
                            <td>$endDateDisplay</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-muted'>No job records found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- ✅ Add Job Modal -->
  <div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="jobModalLabel"><i class="bi bi-briefcase me-2"></i> Add Job</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- ✅ Add Job Form -->
        <form method="POST" action="job-history.php">
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Alumni ID</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($alumni_id); ?>" readonly>
              </div>

              <div class="col-md-6">
                <label class="form-label">Occupation <span class="text-danger">*</span></label>
                <input type="text" name="occupation" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Designation <span class="text-danger">*</span></label>
                <input type="text" name="designation" class="form-control" required>
              </div>

              <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Describe your job..."></textarea>
              </div>

              <div class="col-md-6">
                <label class="form-label">Workplace Name</label>
                <input type="text" name="workplace_name" class="form-control">
              </div>

              <div class="col-md-6">
                <label class="form-label">Workplace Address</label>
                <input type="text" name="workplace_address" class="form-control">
              </div>

              <div class="col-md-6">
                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">End Date (Leave blank if Present)</label>
                <input type="date" name="end_date" class="form-control">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" name="save_job" class="btn btn-success">
              <i class="bi bi-check-circle me-1"></i> Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php if (!empty($alert_type)) : ?>
  <script>
    Swal.fire({
      icon: '<?php echo $alert_type; ?>',
      title: '<?php echo ucfirst($alert_type); ?>',
      text: '<?php echo $alert_message; ?>',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    }).then(() => {
      <?php if ($redirect) echo "window.location.href='job-history.php';"; ?>
    });
  </script>
  <?php endif; ?>
</body>
</html>
