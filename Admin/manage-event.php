<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include "configure.php"; // Database connection file

// ====================== ADD EVENT ====================== //
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_event'])) {
    $event_title = trim($_POST['event_title']);
    $schedule    = trim($_POST['schedule']);
    $description = trim($_POST['description']);
    $banner = "";
    $posted_by = $_SESSION['admin_id']; // ✅ Admin ID from session

    if (empty($event_title) || empty($schedule)) {
        $_SESSION['error'] = "Event title and schedule are required.";
        header("Location: manage-event.php");
        exit();
    }

    // Ensure upload directory
    $upload_dir = "img/events/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    // Upload image
    if (!empty($_FILES["bannerImage"]["name"])) {
        $ext = strtolower(pathinfo($_FILES["bannerImage"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed) && $_FILES["bannerImage"]["error"] === UPLOAD_ERR_OK) {
            $banner = uniqid("event_", true) . "." . $ext;
            move_uploaded_file($_FILES["bannerImage"]["tmp_name"], $upload_dir . $banner);
        }
    }

    // ✅ Insert query includes posted_by
    $stmt = $conn->prepare("INSERT INTO events (event_title, schedule, description, banner_image, posted_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $event_title, $schedule, $description, $banner, $posted_by);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Event added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add event.";
    }
    $stmt->close();
    header("Location: manage-event.php");
    exit();
}

// ====================== UPDATE EVENT ====================== //
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $event_title = trim($_POST['event_title']);
    $schedule = trim($_POST['schedule']);
    $description = trim($_POST['description']);
    $banner = $_POST['old_banner']; // keep old banner

    // Upload new image if provided
    if (!empty($_FILES["edit_banner"]["name"])) {
        $ext = strtolower(pathinfo($_FILES["edit_banner"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed) && $_FILES["edit_banner"]["error"] === UPLOAD_ERR_OK) {
            $banner = uniqid("event_", true) . "." . $ext;
            move_uploaded_file($_FILES["edit_banner"]["tmp_name"], "img/events/" . $banner);
        }
    }

    $stmt = $conn->prepare("UPDATE events SET event_title=?, schedule=?, description=?, banner_image=? WHERE event_id=?");
    $stmt->bind_param("ssssi", $event_title, $schedule, $description, $banner, $event_id);
    if ($stmt->execute()) $_SESSION['success'] = "Event updated successfully.";
    else $_SESSION['error'] = "Failed to update event.";
    $stmt->close();
    header("Location: manage-event.php");
    exit();
}

// ====================== DELETE EVENT ====================== //
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE event_id=?");
    $stmt->bind_param("i", $event_id);
    if ($stmt->execute()) $_SESSION['success'] = "Event deleted successfully.";
    else $_SESSION['error'] = "Failed to delete event.";
    $stmt->close();
    header("Location: manage-event.php");
    exit();
}

// ====================== FETCH EVENTS ====================== //
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_id DESC");

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<?php include "Admin-sidebar.php"; ?>

<!-- ====================== PAGE STYLES ====================== -->
<style>
    body {
        background: #f7f9fc;
    }

    .card,
    .table {
        border-radius: 10px;
        background: #ffffffd9;
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
    }

    .table img {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
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
    <!-- ====================== MAIN CONTENT ====================== -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex text-center pt-3 pb-2 mb-3 border-bottom">
           
        </div>

        <!-- ➕ Add Event Modal -->
        <div class="modal fade" id="addEventModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Create New Event</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3"><label>Event Name</label><input type="text" class="form-control" name="event_title" required></div>
                            <div class="mb-3"><label>Schedule</label><input type="datetime-local" class="form-control" name="schedule" required></div>
                            <div class="mb-3"><label>Description</label><textarea class="form-control" name="description" rows="4"></textarea></div>
                            <div class="mb-3"><label>Banner Image</label><input type="file" class="form-control" name="bannerImage" accept="image/*"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="save_event" class="btn btn-success">Save Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ====================== EVENT LIST ====================== -->
        <div class="card p-3">
            <div class="d-flex d-block justify-content-between">
                <h5 class="mb-3">List of Events</h5>
                <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="bi bi-calendar-plus mr-2 me-1"></i> Add New Event
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>S.No</th>
                            <th>Event Name</th>
                            <th>Schedule</th>
                            <th>Description</th>
                            <th>Banner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (mysqli_num_rows($events) > 0):
                            while ($row = mysqli_fetch_assoc($events)): ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= htmlspecialchars($row['event_title']); ?></td>
                                    <td><?= htmlspecialchars($row['schedule']); ?></td>
                                    <td><?= htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <?php if ($row['banner_image']): ?>
                                            <img src="img/events/<?= htmlspecialchars($row['banner_image']); ?>" alt="Banner">
                                        <?php else: ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['event_id']; ?>"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['event_id']; ?>"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- ✏️ Edit Modal -->
                                <div class="modal fade" id="editModal<?= $row['event_id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5>Edit Event</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="event_id" value="<?= $row['event_id']; ?>">
                                                    <input type="hidden" name="old_banner" value="<?= htmlspecialchars($row['banner_image']); ?>">
                                                    <div class="mb-3"><label>Event Name</label><input type="text" class="form-control" name="event_title" value="<?= htmlspecialchars($row['event_title']); ?>" required></div>
                                                    <div class="mb-3"><label>Schedule</label><input type="datetime-local" class="form-control" name="schedule" value="<?= date('Y-m-d\TH:i', strtotime($row['schedule'])); ?>" required></div>
                                                    <div class="mb-3"><label>Description</label><textarea class="form-control" name="description"><?= htmlspecialchars($row['description']); ?></textarea></div>
                                                    <div class="mb-3"><label>Change Banner</label><input type="file" class="form-control" name="edit_banner" accept="image/*"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_event" class="btn btn-success">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🗑️ Delete Modal -->
                                <div class="modal fade" id="deleteModal<?= $row['event_id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Delete Event</h5>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete <strong><?= htmlspecialchars($row['event_title']); ?></strong>?
                                                    <input type="hidden" name="event_id" value="<?= $row['event_id']; ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="delete_event" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No events found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- ✅ SweetAlert Messages -->
    <?php if ($error): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= htmlspecialchars($error); ?>',
            background: '#fff0f0',
            color: '#c0392b',
            confirmButtonColor: '#d33',
            showClass: { popup: 'animate__animated animate__shakeX' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp' }
        });
    </script>
    <?php endif; ?>

    <?php if ($success): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= htmlspecialchars($success); ?>',
            background: '#f0fff4',
            color: '#155724',
            confirmButtonColor: '#28a745',
            showClass: { popup: 'animate__animated animate__zoomIn' },
            hideClass: { popup: 'animate__animated animate__fadeOutDown' },
            timer: 2000,
            timerProgressBar: true
        });
    </script>
    <?php endif; ?>
</body>
</html>
