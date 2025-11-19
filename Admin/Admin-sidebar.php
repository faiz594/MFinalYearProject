<?php
include "configure.php"; // Database connection

// Assume admin is already logged in for testing/demo
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1; // example admin ID
}

// Fetch admin username from DB if not already in session
if (!isset($_SESSION['admin_username'])) {
    $admin_id = $_SESSION['admin_id'];
    $query = $conn->prepare("SELECT username FROM admin WHERE user_id = ?");
    $query->bind_param("i", $admin_id);
    $query->execute();
    $result = $query->get_result();
    $admin = $result->fetch_assoc();
    $_SESSION['admin_username'] = $admin['username'] ?? 'Admin';
}

// Handle admin profile update
if (isset($_POST['update_admin'])) {
    $admin_id = $_POST['user_id'];
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $new_password = $_POST['password'] ?? '';

    $sql_parts = [];
    $params = [];
    $types = '';

    $sql_parts[] = "username = ?";
    $params[] = $username;
    $types .= 's';

    $sql_parts[] = "full_name = ?";
    $params[] = $full_name;
    $types .= 's';

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_parts[] = "password_hash = ?";
        $params[] = $hashed_password;
        $types .= 's';
    }

    $sql = "UPDATE admin SET " . implode(', ', $sql_parts) . " WHERE user_id = ?";
    $params[] = $admin_id;
    $types .= 'i';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['admin_username'] = $username; // update navbar dynamically
        $_SESSION['flash_success'] = "Profile updated successfully!";
    } else {
        $_SESSION['flash_error'] = "Update failed: " . $stmt->error;
    }

    header("Location: Admin-dashboard.php");
    exit;
}

// Detect current page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="../css/dashboard.css">

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="../javascript/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* SIDEBAR */
#sidebar {
  background: rgb(0, 123, 255);
  height: 100vh;
  color: white;
  padding-top: 20px;
}
#sidebar .nav-link {
  color: white;
  padding: 12px 18px;
  border-radius: 8px;
  font-weight: 500;
}
#sidebar .nav-link:hover {
  background: rgba(255, 255, 255, 0.18);
}
.active-link {
  background: white !important;
  color: #0d6efd !important;
  font-weight: 600;
}
@media (max-width: 768px) {
  .navbar-brand {
    text-align: center !important;
    flex-grow: 1;
  }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary sticky-top p-2 shadow">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <button class="navbar-toggler d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <span class="navbar-brand mb-0 h6 flex-grow-1 text-center text-md-start">
      Alumni Management System
    </span>
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <?= $_SESSION['admin_username'] ?? 'Admin' ?>
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#adminProfileModal">Profile</a></li>
        <li><a class="dropdown-item" href="../Home/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    <nav id="sidebar" class="col-md-3 col-lg-2 collapse d-md-block">
      <h5 class="px-3 mb-3">Admin Panel</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= ($current_page == 'Admin-dashboard.php') ? 'active-link' : '' ?>" href="Admin-dashboard.php"><i class="bi bi-house me-2"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link <?= ($current_page == 'gallery.php') ? 'active-link' : '' ?>" href="gallery.php"><i class="bi bi-image me-2"></i> Gallery</a></li>
        <li class="nav-item"><a class="nav-link <?= ($current_page == 'job.php') ? 'active-link' : '' ?>" href="job.php"><i class="bi bi-briefcase me-2"></i> Jobs</a></li>
        <li class="nav-item"><a class="nav-link <?= ($current_page == 'Alumni-list.php') ? 'active-link' : '' ?>" href="Alumni-list.php"><i class="bi bi-people me-2"></i> Alumni List</a></li>
        <li class="nav-item"><a class="nav-link <?= ($current_page == 'manage-event.php') ? 'active-link' : '' ?>" href="manage-event.php"><i class="bi bi-calendar-event me-2"></i> Events</a></li>
        <li class="nav-item"><a class="nav-link <?= ($current_page == 'report.php') ? 'active-link' : '' ?>" href="report.php"><i class="bi bi-file-earmark-text me-2"></i> Generate Report</a></li>
        <li class="nav-item"><a class="nav-link <?= ($current_page == 'send-email.php') ? 'active-link' : '' ?>" href="send-email.php"><i class="bi bi-envelope me-2"></i> Send Email</a></li>
      </ul>
    </nav>

<!-- ADMIN PROFILE MODAL -->
<div class="modal fade" id="adminProfileModal" tabindex="-1" aria-labelledby="adminProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="adminProfileModalLabel"><i class="bi bi-person-circle me-2"></i>Admin Profile</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="POST">
        <div class="modal-body">
          <?php
          $admin_id = $_SESSION['admin_id'];
          $query = $conn->prepare("SELECT username, full_name FROM admin WHERE user_id = ?");
          $query->bind_param("i", $admin_id);
          $query->execute();
          $result = $query->get_result();
          $admin = $result->fetch_assoc();
          ?>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" id="full_name" value="<?= htmlspecialchars($admin['full_name']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter new password">
          </div>
          <input type="hidden" name="user_id" value="<?= $admin_id ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="update_admin">Update Profile</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- SweetAlert flash messages -->
<?php if (isset($_SESSION['flash_success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?= $_SESSION['flash_success'] ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['flash_success']); endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: '<?= $_SESSION['flash_error'] ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['flash_error']); endif; ?>

</body>
</html>
