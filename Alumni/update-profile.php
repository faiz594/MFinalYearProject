<?php
include "configure.php"; // DB connection
include "Alumni-sidebar.php";

// ✅ Alumni is already logged in
$alumni_id = $_SESSION['alumni_id'];

// ========== FETCH ALUMNI DATA ==========
$alumni_query = "SELECT * FROM alumni WHERE std_id = ?";
$stmt = $conn->prepare($alumni_query);
$stmt->bind_param("i", $alumni_id);
$stmt->execute();
$alumni_result = $stmt->get_result();
$alumni = $alumni_result->fetch_assoc();

// ========== FETCH JOB HISTORY (by std_id, not job_id) ==========
$job_query = "SELECT * FROM job_history WHERE std_id = ?";
$job_stmt = $conn->prepare($job_query);
$job_stmt->bind_param("i", $alumni_id);
$job_stmt->execute();
$job_result = $job_stmt->get_result();
$job = $job_result->fetch_assoc();

// ========== UPDATE PROFILE ==========
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_changes'])) {

  // Personal info
  $std_name = trim($_POST['std_name']);
  $std_email = trim($_POST['std_email']);
  $phone = trim($_POST['phone']);
  $Batch = trim($_POST['Batch']);
  $course_studied = trim($_POST['course_studied']);
  $university = trim($_POST['university']);

  // Job info
  $occupation = trim($_POST['occupation']);
  $designation = trim($_POST['designation']);
  $description = trim($_POST['description']);
  $workplace_name = trim($_POST['workplace_name']);
  $workplace_address = trim($_POST['workplace_address']);
  $start_date = $_POST['start_date'] ?: NULL;
  $end_date = $_POST['end_date'] ?: NULL;

  // ✅ Update alumni table
  $update_alumni = "UPDATE alumni 
                    SET std_name=?, std_email=?, phone=?, Batch=?, course_studied=?, university=? 
                    WHERE std_id=?";
  $stmt = $conn->prepare($update_alumni);
  $stmt->bind_param("ssssssi", $std_name, $std_email, $phone, $Batch, $course_studied, $university, $alumni_id);
  $stmt->execute();

  // ✅ Update or insert job history properly
  if ($job) {
    // Update existing job record
    $update_job = "UPDATE job_history 
                   SET occupation=?, designation=?, description=?, workplace_name=?, workplace_address=?, start_date=?, end_date=?, updated_at=NOW() 
                   WHERE std_id=?";
    $job_stmt = $conn->prepare($update_job);
    $job_stmt->bind_param(
      "sssssssi",
      $occupation,
      $designation,
      $description,
      $workplace_name,
      $workplace_address,
      $start_date,
      $end_date,
      $alumni_id
    );
  } else {
    // Insert new job record
    $insert_job = "INSERT INTO job_history 
                   (std_id, occupation, designation, description, workplace_name, workplace_address, start_date, end_date, created_at) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $job_stmt = $conn->prepare($insert_job);
    $job_stmt->bind_param(
      "isssssss",
      $alumni_id,
      $occupation,
      $designation,
      $description,
      $workplace_name,
      $workplace_address,
      $start_date,
      $end_date
    );
  }
  $job_stmt->execute();

  // ✅ SweetAlert message for 2 seconds, then redirect to dashboard
  echo '
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        icon: "success",
        title: "Profile Updated Successfully!",
        text: "Redirecting to Dashboard...",
        showConfirmButton: false,
        timer: 2000, // 2 seconds
        timerProgressBar: true
      }).then(() => {
        window.location.href = "Alumni-dashboard.php";
      });
    });
  </script>
  ';
  exit();
}
?>

<!-- ================= FRONTEND ================= -->
<style>
  .form-container {
    max-width: 750px;
    margin: auto;
    border-radius: 15px;
    background: #007BFF;
    padding: 2rem;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
  }

  .form-control {
    border-radius: 10px;
  }

  .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
  }

  .btn-success,
  .btn-outline-success {
    border-radius: 10px;
    padding: 8px 20px;
  }

  .btn-success:hover {
    background-color: #198754;
  }

  label {
    color: #ffff;
    font-weight: bold;
  }
</style>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <h4 class="mt-4 text-center fw-bold">Update Your Profile</h4>

  <form class="alumni-form mt-4 mb-5" method="post">
    <div class="form-container">
      <div class="text-center mb-4">
               <h2 class="mt-3">Alumni Portal</h2>
        <h5>Please update your personal and career details below.</h5>
      </div>

      <div class="row g-3">
        <!-- Personal Info -->
        <div class="col-md-6">
          <label>Full Name*</label>
          <input type="text" name="std_name" class="form-control" value="<?= htmlspecialchars($alumni['std_name'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label>Email*</label>
          <input type="email" name="std_email" class="form-control" value="<?= htmlspecialchars($alumni['std_email'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label>Phone Number*</label>
          <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($alumni['phone'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label>Batch*</label>
          <input type="text" name="Batch" class="form-control" value="<?= htmlspecialchars($alumni['Batch'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label>Course of Study*</label>
          <input type="text" name="course_studied" class="form-control" value="<?= htmlspecialchars($alumni['course_studied'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label>University Name*</label>
          <input type="text" name="university" class="form-control" value="<?= htmlspecialchars($alumni['university'] ?? '') ?>" required>
        </div>

        <!-- Job History -->
        <div class="col-12 mt-4">
          <h5 class="fw-bold text-center">Job Information</h5>
          <hr>
        </div>

        <div class="col-md-6">
          <label>Occupation</label>
          <input type="text" name="occupation" class="form-control" value="<?= htmlspecialchars($job['occupation'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label>Designation</label>
          <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($job['designation'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label>Job Description</label>
          <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($job['description'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label>Workplace Name</label>
          <input type="text" name="workplace_name" class="form-control" value="<?= htmlspecialchars($job['workplace_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label>Workplace Address</label>
          <input type="text" name="workplace_address" class="form-control" value="<?= htmlspecialchars($job['workplace_address'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label>Start Date</label>
          <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($job['start_date'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label>End Date</label>
          <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($job['end_date'] ?? '') ?>">
        </div>
      </div>

      <div class="d-flex justify-content-center align-items-center mt-4">
        <button type="reset" class="btn btn-danger me-2">Discard Changes</button>
        <button type="submit" name="save_changes" class="btn btn-success">Save Changes</button>
      </div>
    </div>
  </form>
</main>
