<?php
include "configure.php";
include "Alumni-sidebar.php";

// 🟩 Get alumni ID from session
$alumni_id = $_SESSION['alumni_id'] ?? null;

// 🟨 Variables for SweetAlert messages
$alertType = '';
$alertTitle = '';
$alertMessage = '';
$redirect = false;

// 🟦 Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword     = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $alertType = 'error';
        $alertTitle = 'Missing Fields!';
        $alertMessage = 'Please fill in all required fields.';
    } elseif ($newPassword !== $confirmPassword) {
        $alertType = 'warning';
        $alertTitle = 'Password Mismatch!';
        $alertMessage = 'New password and confirm password do not match.';
    } else {
        // ✅ Fetch current hashed password from DB
        $query = "SELECT password_hash FROM alumni WHERE std_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $alumni_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $dbPassword = $row['password_hash'];

            // ✅ Verify current password (hashed)
            if (password_verify($currentPassword, $dbPassword)) {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = "UPDATE alumni SET password_hash = ? WHERE std_id = ?";
                $updateStmt = $conn->prepare($update);
                $updateStmt->bind_param("si", $hashedNewPassword, $alumni_id);

                if ($updateStmt->execute()) {
                    $alertType = 'success';
                    $alertTitle = 'Password Updated!';
                    $alertMessage = 'Your password has been changed successfully.';
                    $redirect = true;
                } else {
                    $alertType = 'error';
                    $alertTitle = 'Database Error!';
                    $alertMessage = 'Failed to update password. Please try again.';
                }
            } else {
                $alertType = 'error';
                $alertTitle = 'Incorrect Password!';
                $alertMessage = 'The current password you entered is incorrect.';
            }
        } else {
            $alertType = 'error';
            $alertTitle = 'User Not Found!';
            $alertMessage = 'Could not find your account.';
        }
    }
}
?>

<!-- ✅ HTML Content -->
<div class="content col-md-9">
    <div class="container mt-4">
        <div class="card shadow rounded-3 justify-content-center" style="max-width: 800px; width: 100%;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-key mr-2"></i> Modify Password</h5>
            </div>
            <div class="card-body justify-content-center">

                <!-- ✅ Password Change Form -->
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mx-auto">

                            <!-- Current Password -->
                            <div class="mb-3 position-relative">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" name="currentPassword" class="form-control" id="currentPassword"
                                        placeholder="Enter current password" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="currentPassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-3 position-relative">
                                <label for="newPassword" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="newPassword" class="form-control" id="newPassword"
                                        placeholder="Enter new password" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="newPassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3 position-relative">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="confirmPassword" class="form-control" id="confirmPassword"
                                        placeholder="Re-enter new password" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="confirmPassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-outline-success mb-2">
                                    <i class="bi bi-check2-circle "></i> Update Password
                                </button>
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- ✅ Bootstrap + SweetAlert JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/bootstrap.bundle.js"></script>

<!-- ✅ Password Show/Hide Script -->
<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    });
});
</script>

<?php if (!empty($alertType)): ?>
<script>
Swal.fire({
    icon: '<?php echo $alertType; ?>',
    title: '<?php echo $alertTitle; ?>',
    text: '<?php echo $alertMessage; ?>',
    showConfirmButton: false,
    timer: <?php echo $redirect ? 2000 : 2500; ?>,
    timerProgressBar: true
}).then(() => {
    <?php if ($redirect): ?>
    window.location.href = 'Alumni-dashboard.php';
    <?php endif; ?>
});
</script>
<?php endif; ?>
</body>
</html>
