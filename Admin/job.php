<?php
// job.php - Final Clean Version
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection
include "configure.php"; // must define $conn (mysqli)

// ------------------ Handle POST actions ------------------ //
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ---------- ADD JOB ----------
  if (isset($_POST['add_job'])) {
    $job_title     = trim($_POST['job_title'] ?? '');
    $company       = trim($_POST['company'] ?? '');
    $location      = trim($_POST['location'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $requirements  = trim($_POST['requirements'] ?? '');
    $posted_by     = $_SESSION['admin_id'] ?? 'Admin'; // 🟩 Store admin name who posted

    if ($job_title === '' || $company === '' || $location === '' || $description === '' || $requirements === '') {
      $_SESSION['error'] = "❌ All fields are required.";
    } else {
      $posted_on = date("Y-m-d");
      $closes_on = date("Y-m-d", strtotime("+30 days"));

      $stmt = $conn->prepare("INSERT INTO jobs (job_title, company, location, description, requirements, posted_by, posted_on, closes_on) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      if ($stmt) {
        $stmt->bind_param("ssssssss", $job_title, $company, $location, $description, $requirements, $posted_by, $posted_on, $closes_on);
        if ($stmt->execute()) {
          $_SESSION['success'] = "✅ Job added successfully!";
        } else {
          $_SESSION['error'] = "❌ Error adding job: " . $stmt->error;
        }
        $stmt->close();
      } else {
        $_SESSION['error'] = "❌ Prepare error: " . $conn->error;
      }
    }
    header("Location: job.php");
    exit();
  }

  // ---------- UPDATE JOB ----------
  if (isset($_POST['update_job'])) {
    $job_id        = intval($_POST['job_id'] ?? 0);
    $job_title     = trim($_POST['job_title'] ?? '');
    $company       = trim($_POST['company'] ?? '');
    $location      = trim($_POST['location'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $requirements  = trim($_POST['requirements'] ?? '');

    if ($job_id <= 0 || $job_title === '' || $company === '' || $location === '' || $description === '' || $requirements === '') {
      $_SESSION['error'] = "❌ All fields and a valid ID are required.";
    } else {
      $stmt = $conn->prepare("UPDATE jobs 
                              SET job_title=?, company=?, location=?, description=?, requirements=? 
                              WHERE job_id=?");
      if ($stmt) {
        $stmt->bind_param("sssssi", $job_title, $company, $location, $description, $requirements, $job_id);
        if ($stmt->execute()) {
          $_SESSION['success'] = "✏️ Job updated successfully.";
        } else {
          $_SESSION['error'] = "❌ Failed to update job: " . $stmt->error;
        }
        $stmt->close();
      } else {
        $_SESSION['error'] = "❌ Prepare error: " . $conn->error;
      }
    }
    header("Location: job.php");
    exit();
  }

  // ---------- DELETE JOB ----------
  if (isset($_POST['delete_job'])) {
    $job_id = intval($_POST['job_id'] ?? 0);
    if ($job_id <= 0) {
      $_SESSION['error'] = "❌ Invalid job ID.";
    } else {
      $stmt = $conn->prepare("DELETE FROM jobs WHERE job_id = ?");
      if ($stmt) {
        $stmt->bind_param("i", $job_id);
        if ($stmt->execute()) {
          $_SESSION['success'] = "🗑️ Job deleted successfully.";
        } else {
          $_SESSION['error'] = "❌ Failed to delete job: " . $stmt->error;
        }
        $stmt->close();
      } else {
        $_SESSION['error'] = "❌ Prepare error: " . $conn->error;
      }
    }
    header("Location: job.php");
    exit();
  }
}

// ------------------ Fetch jobs ------------------ //
$jobs_array = [];
$result = mysqli_query($conn, "SELECT * FROM jobs ORDER BY job_id DESC");
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $jobs_array[] = $row;
  }
  mysqli_free_result($result);
} else {
  $_SESSION['error'] = "❌ Query error: " . mysqli_error($conn);
}
?>

<?php include "Admin-sidebar.php"; ?>

<style>
  body {
    background-color: #f8f9fa;
  }

  .table-responsive::-webkit-scrollbar {
    height: 8px;
  }

  .table-responsive::-webkit-scrollbar-thumb {
    background: #0d6efd;
    border-radius: 10px;
  }

  .table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .message-box {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    min-width: 300px;
    display: none;
    text-align: center;
    font-weight: 500;
  }

  .modal-body label {
    display: block;
    text-align: left;
    font-weight: 700;
    margin-bottom: 5px;
    margin-left: 10px;
  }
</style>

<body>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-briefcase mr-2"></i>Jobs List</span>
        <button class="btn btn-light btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#addModal">
          <i class="bi bi-plus-circle mr-2"></i>Add New Job
        </button>
      </div>

      <!-- Add Job Modal -->
      <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <form method="POST">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Job</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3"><label>Job Title:</label><input type="text" name="job_title" class="form-control" required></div>
                <div class="mb-3"><label>Company:</label><input type="text" name="company" class="form-control" required></div>
                <div class="mb-3"><label>Location:</label><input type="text" name="location" class="form-control" required></div>
                <div class="mb-3"><label>Description:</label><textarea name="description" class="form-control" rows="4" required></textarea></div>
                <div class="mb-3"><label>Requirements:</label><textarea name="requirements" class="form-control" rows="3" required></textarea></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="add_job" class="btn btn-success">Save Job</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="card-body">
        <!-- Message Box -->
        <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
          <div id="messageBox" class="alert message-box <?= isset($_SESSION['success']) ? 'alert-success' : 'alert-danger'; ?>">
            <?= $_SESSION['success'] ?? $_SESSION['error']; ?>
          </div>
          <script>
            (function() {
              const msg = document.getElementById('messageBox');
              if (msg) {
                msg.style.display = 'block';
                setTimeout(() => {
                  msg.style.opacity = '0';
                  msg.style.transition = 'opacity 0.5s';
                  setTimeout(() => msg.remove(), 500);
                }, 2000);
              }
            })();
          </script>
          <?php unset($_SESSION['success'], $_SESSION['error']); ?>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
              <tr>
                <th>ID</th>
                <th>Job Title</th>
                <th>Company</th>
                <th>Location</th>
                <th>Description</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($jobs_array)): ?>
                <?php foreach ($jobs_array as $row): ?>
                  <tr>
                    <td><?= $row['job_id']; ?></td>
                    <td><?= $row['job_title']; ?></td>
                    <td><?= $row['company']; ?></td>
                    <td><?= $row['location']; ?></td>
                    <td><?= substr($row['description'], 0, 60); ?>...</td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-warning mb-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['job_id']; ?>"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['job_id']; ?>"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="7" class="text-center text-muted">No jobs available</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <!-- Edit & Delete Modals -->
  <?php foreach ($jobs_array as $row): ?>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal<?= $row['job_id']; ?>" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <form method="POST">
            <div class="modal-header bg-primary text-dark">
              <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Job</h5>
              <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="job_id" value="<?= $row['job_id']; ?>">
              <div class="mb-3"><label>Job Title:</label><input type="text" name="job_title" class="form-control" value="<?= $row['job_title']; ?>" required></div>
              <div class="mb-3"><label>Company:</label><input type="text" name="company" class="form-control" value="<?= $row['company']; ?>" required></div>
              <div class="mb-3"><label>Location:</label><input type="text" name="location" class="form-control" value="<?= $row['location']; ?>" required></div>
              <div class="mb-3"><label>Description:</label><textarea name="description" class="form-control" rows="4" required><?= $row['description']; ?></textarea></div>
              <div class="mb-3"><label>Requirements:</label><textarea name="requirements" class="form-control" rows="3" required><?= $row['requirements']; ?></textarea></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" name="update_job" class="btn btn-success">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal<?= $row['job_id']; ?>" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form method="POST">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Delete Job</h5>
              <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete <strong><?= $row['job_title']; ?></strong>?
              <input type="hidden" name="job_id" value="<?= $row['job_id']; ?>">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" name="delete_job" class="btn btn-danger">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</body>
</html>
