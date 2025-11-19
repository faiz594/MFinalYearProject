<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "configure.php"; // DB connection
// ======================
// CREATE UPLOAD FOLDER IF NOT EXISTS
// ======================
$upload_dir = "../img/gallery/";
if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0777, true);
}

// ======================
// ADD IMAGE TO GALLERY
// ======================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_gallery"])) {
  $title = mysqli_real_escape_string($conn, $_POST["image_title"]);
  $desc = mysqli_real_escape_string($conn, $_POST["desc"]);
  $image = $_FILES["formFile"]["name"];
  $tmp_name = $_FILES["formFile"]["tmp_name"];
  $uploaded_by= $_SESSION['admin_id'];

  if (!empty($image)) {
    $target = $upload_dir . basename($image);
    if (move_uploaded_file($tmp_name, $target)) {
      $query = "INSERT INTO gallery (image_title, image, description) VALUES ('$title', '$image', '$desc')";
      mysqli_query($conn, $query);
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            title: 'Uploaded!',
            text: 'Image uploaded successfully.',
            position: 'top',
            showConfirmButton: false,
            timer: 2000
          });
          setTimeout(() => { window.location.href = '".$_SERVER['PHP_SELF']."'; }, 2100);
        });
      </script>";
    } else {
      echo "<script>alert('Failed to upload image.');</script>";
    }
  }
}

// ======================
// DELETE GALLERY IMAGE
// ======================
if (isset($_POST['delete_gallery'])) {
  $id = intval($_POST['delete_id']);
  $get_image = mysqli_query($conn, "SELECT image FROM gallery WHERE id=$id");
  if ($get_image && mysqli_num_rows($get_image) > 0) {
    $img = mysqli_fetch_assoc($get_image)['image'];
    $img_path = $upload_dir . $img;
    if (mysqli_query($conn, "DELETE FROM gallery WHERE id=$id")) {
      if (file_exists($img_path)) unlink($img_path);
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Image deleted successfully.',
            position: 'top',
            showConfirmButton: false,
            timer: 2000
          });
          setTimeout(() => { window.location.href = '".$_SERVER['PHP_SELF']."'; }, 2100);
        });
      </script>";
    }
  }
}

// ======================
// EDIT GALLERY IMAGE
// ======================
if (isset($_POST['edit_gallery'])) {
  $id = intval($_POST['edit_id']);
  $title = mysqli_real_escape_string($conn, $_POST['edit_title']);
  $desc = mysqli_real_escape_string($conn, $_POST['edit_desc']);
  $new_image = $_FILES['edit_image']['name'];
  $tmp_name = $_FILES['edit_image']['tmp_name'];

  if (!empty($new_image)) {
    // Upload new image
    $target = $upload_dir . basename($new_image);
    move_uploaded_file($tmp_name, $target);

    // Delete old image
    $get_old = mysqli_query($conn, "SELECT image FROM gallery WHERE id=$id");
    if ($get_old && mysqli_num_rows($get_old) > 0) {
      $old = mysqli_fetch_assoc($get_old)['image'];
      $old_path = $upload_dir . $old;
      if (file_exists($old_path)) unlink($old_path);
    }

    $query = "UPDATE gallery SET image_title='$title', image='$new_image', description='$desc' WHERE id=$id";
  } else {
    $query = "UPDATE gallery SET image_title='$title', description='$desc' WHERE id=$id";
  }

  if (mysqli_query($conn, $query)) {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Updated!',
          text: 'Image updated successfully.',
          position: 'top',
          showConfirmButton: false,
          timer: 2000
        });
        setTimeout(() => { window.location.href = '".$_SERVER['PHP_SELF']."'; }, 2100);
      });
    </script>";
  }
}

// ======================
// FETCH ALL GALLERY RECORDS
// ======================
$result = mysqli_query($conn, "SELECT * FROM gallery ORDER BY gallery_id DESC");

include "Admin-sidebar.php";
?>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 6px;
  }

  img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
  }

  @media (max-width: 576px) {
    table td img {
      width: 60px;
      height: 45px;
    }
  }
</style>

<!-- Main Content -->
<main class="col-md-9 ms-sm-auto col-lg-10 content mt-5">
  <h4 class="text-center mb-3">Gallery Management</h4>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <span>Gallery List</span>
      <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-upload mr-2"></i> Add Gellery
      </button>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
          <thead class="table-primary">
            <tr>
              <th>S.No</th>
              <th>Title</th>
              <th>Gallery</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
              $count = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                $img_path = "../img/gallery/" . htmlspecialchars($row['image']);
                $id = $row['gallery_id'];
                echo "
                  <tr>
                    <td>{$count}</td>
                    <td>" . htmlspecialchars($row['image_title']) . "</td>
                    <td><img src='{$img_path}' alt='gallery'></td>
                    <td>" . htmlspecialchars($row['description']) . "</td>
                    <td>
                      <div class='d-flex justify-content-center gap-2'>
                        <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editModal{$id}'>
                          <i class='bi bi-pencil'></i>
                        </button>
                        <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal{$id}'>
                          <i class='bi bi-trash'></i>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Delete Modal -->
                  <div class='modal fade' id='deleteModal{$id}' tabindex='-1'>
                    <div class='modal-dialog modal-dialog-centered'>
                      <div class='modal-content'>
                        <div class='modal-header bg-danger text-white'>
                          <h5 class='modal-title'>Delete Image</h5>
                          <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                        </div>
                        <div class='modal-body text-center'>
                          <p>Are you sure you want to delete <strong>" . htmlspecialchars($row['image_title']) . "</strong>?</p>
                          <img src='{$img_path}' class='rounded shadow-sm' width='120'>
                        </div>
                        <div class='modal-footer'>
                          <form method='POST'>
                            <input type='hidden' name='delete_id' value='{$id}'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                            <button type='submit' name='delete_gallery' class='btn btn-danger'>Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Edit Modal -->
                  <div class='modal fade' id='editModal{$id}' tabindex='-1'>
                    <div class='modal-dialog modal-dialog-centered'>
                      <div class='modal-content'>
                        <div class='modal-header bg-warning text-dark'>
                          <h5 class='modal-title'>Edit Image</h5>
                          <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                        </div>
                        <form method='POST' enctype='multipart/form-data'>
                          <div class='modal-body'>
                            <input type='hidden' name='edit_id' value='{$id}'>
                            <div class='mb-3'>
                              <label class='form-label'>Title</label>
                              <input type='text' name='edit_title' class='form-control' value='" . htmlspecialchars($row['image_title']) . "' required>
                            </div>
                            <div class='mb-3'>
                              <label class='form-label'>Change Image (optional)</label>
                              <input type='file' class='form-control' name='edit_image'>
                            </div>
                            <img src='{$img_path}' class='rounded shadow-sm mb-2' width='100'>
                            <div class='mb-3'>
                              <label class='form-label'>Description</label>
                              <textarea name='edit_desc' class='form-control' rows='2' required>" . htmlspecialchars($row['description']) . "</textarea>
                            </div>
                          </div>
                          <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                            <button type='submit' name='edit_gallery' class='btn btn-warning text-white'>Save Changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  ";
                $count++;
              }
            } else {
              echo "<tr><td colspan='5'>No images found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Upload New Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label for="image_title" class="form-label">Title</label>
            <input class="form-control" type="text" id="image_title" name="image_title" required>
          </div>
          <div class="mb-3">
            <label for="formFile" class="form-label">Image</label>
            <input class="form-control" type="file" id="formFile" name="formFile" required>
          </div>
          <div class="mb-3">
            <label for="desc" class="form-label">Description</label>
            <textarea class="form-control" id="desc" name="desc" rows="2" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add_gallery" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
