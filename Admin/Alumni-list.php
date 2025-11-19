<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "configure.php"; // Database connection
include "Admin-sidebar.php"; 


// =================== VERIFY ALUMNI ===================
if (isset($_GET['verify_id'])) {
    $verify_id = intval($_GET['verify_id']);
    $update_query = "UPDATE alumni SET is_verified = 1 WHERE std_id = $verify_id";
    
    if (mysqli_query($conn, $update_query)) {

        // ==== SweetAlert Popup (Email Removed) ====
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Verified!',
                text: 'Alumni has been verified successfully!',
                position: 'top',
                showConfirmButton: false,
                timer: 2500
            });
            setTimeout(() => { window.location.href = '".$_SERVER['PHP_SELF']."'; }, 2600);
        });
        </script>";
    }
}

// =================== DELETE ALUMNI ===================
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM alumni WHERE std_id = $delete_id";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Alumni record deleted successfully.',
                position: 'top',
                showConfirmButton: false,
                timer: 2000
            });
            setTimeout(() => { window.location.href = '".$_SERVER['PHP_SELF']."'; }, 2100);
        });
        </script>";
    }
}
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.avatar {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 50%;
}
.pagination-container { margin-top: 1rem; }
.table-responsive::-webkit-scrollbar { height: 8px; }
.table-responsive::-webkit-scrollbar-thumb { background: #0d6efd; border-radius: 10px; }
.table-responsive::-webkit-scrollbar-track { background: #f1f1f1; }
.status-cell .btn { min-width: 90px; }

/* Stylish Search Bar */
.search-wrapper {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}
.search-form {
    display: flex;
    background: #ffffff;
    border-radius: 50px;
    padding: 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.search-input {
    border: none;
    outline: none;
    width: 250px;
    padding: 10px 15px;
    border-radius: 50px 0 0 50px;
    font-size: 14px;
}
.search-btn {
    background: #0d6efd;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 50px;
    cursor: pointer;
}
.search-btn:hover {
    background: #084298;
}
</style>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-success text-white">List of Alumni</div>
    <div class="card-body">

      <!-- ================= SEARCH BAR ================= -->
      <div class="search-wrapper">
        <form method="GET" class="search-form">
            <input type="text" name="search" class="search-input"
                placeholder="Search by name, email or batch..."
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>
      </div>

      <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Picture</th>
              <th>Name</th>
              <th>Course Graduated</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>

<?php
// =================== SEARCH LOGIC ===================
$search = $_GET['search'] ?? '';
$where = "";

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $where = "WHERE std_name LIKE '%$search%' 
              OR std_email LIKE '%$search%' 
              OR batch LIKE '%$search%'";
}

// Pagination setup
$limit = 3;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$count_query = "SELECT COUNT(*) AS total FROM alumni $where";
$total_records = mysqli_fetch_assoc(mysqli_query($conn, $count_query))['total'];
$total_pages = ceil($total_records / $limit);

$query = "SELECT std_id, std_name, std_email, batch, picture, course_studied, is_verified 
          FROM alumni 
          $where
          ORDER BY std_id DESC 
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    
    $id = $row['std_id'];
    $name = htmlspecialchars($row['std_name']);
    $email = htmlspecialchars($row['std_email']);
    $batch = htmlspecialchars($row['batch']);
    $course = htmlspecialchars($row['course_studied']);
    $picture = !empty($row['picture']) ? "/fyp/home/uploads/" . basename($row['picture']) : "../img/default-avatar.png";
    $status = $row['is_verified'];

    if ($status == 0) {
      $statusBtn = "<a href='?verify_id={$id}' class='btn btn-warning btn-sm text-dark'>Verify</a>";
    } else {
      $statusBtn = "<button class='btn btn-primary btn-sm' disabled>Verified</button>";
    }

    echo "
    <tr>
      <td>{$id}</td>
      <td><img src='{$picture}' class='avatar'></td>
      <td><strong>{$name}</strong></td>
      <td>{$course}</td>
      <td class='status-cell text-center'>{$statusBtn}</td>
      <td class='text-center'>
        <div class='d-flex justify-content-center gap-2'>
          <button class='btn btn-sm btn-info text-white' data-bs-toggle='modal' data-bs-target='#viewModal{$id}'><i class='bi bi-eye'></i></button>
          <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal{$id}'><i class='bi bi-trash'></i></button>
        </div>
      </td>
    </tr>

    <!-- View Modal -->
    <div class='modal fade' id='viewModal{$id}' tabindex='-1'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header bg-info text-white'>
            <h5 class='modal-title'>Alumni Details</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
          </div>
          <div class='modal-body'>
            <div class='text-center mb-3'>
              <img src='{$picture}' class='avatar' style='width:80px;height:80px;'>
            </div>
            <p><strong>ID:</strong> {$id}</p>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Batch:</strong> {$batch}</p>
            <p><strong>Course:</strong> {$course}</p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>OK</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <div class='modal fade' id='deleteModal{$id}' tabindex='-1'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header bg-danger text-white'>
            <h5 class='modal-title'>Confirm Delete</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
          </div>
          <div class='modal-body'>
            Are you sure you want to delete <strong>{$name}</strong>?
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
            <a href='?delete_id={$id}' class='btn btn-danger'>Delete</a>
          </div>
        </div>
      </div>
    </div>
    ";
  }
} else {
  echo "<tr><td colspan='6' class='text-center text-muted'>No alumni found</td></tr>";
}
?>

          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center flex-wrap pagination-container">
        <div>
          <?php 
          $start = $offset + 1;
          $end = min($offset + $limit, $total_records);
          echo "Showing {$start} to {$end} of {$total_records} entries";
          ?>
        </div>
        <nav>
          <ul class="pagination pagination-sm mb-0">

            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
              <a class="page-link" href="<?php if ($page > 1) echo '?page='.($page-1).'&search='.$search; ?>">Previous</a>
            </li>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>

            <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
              <a class="page-link" href="<?php if ($page < $total_pages) echo '?page='.($page+1).'&search='.$search; ?>">Next</a>
            </li>

          </ul>
        </nav>
      </div>

    </div>
  </div>
</main>
</body>
</html>
