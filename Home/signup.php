<?php
include "configure.php";

// Initialize variables
$errors  = [];
$success = false;

// ================== File Upload Function ==================
function handleUpload($file, $allowed = ["jpg", "png"])
{
    if (!isset($file) || $file["error"] !== 0) return ""; // No file uploaded
    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    // Check allowed types
    if (!in_array($ext, $allowed)) return false;

    $newName = uniqid("alumni_") . "." . $ext;
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    $target_file = $target_dir . $newName;

    return move_uploaded_file($file["tmp_name"], $target_file) ? $target_file : false;
}

// ================== Handle Form Submission ==================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect input values safely
    $full_name        = trim($_POST['full_name'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone            = trim($_POST['phone'] ?? '');
    $batch            = trim($_POST['batch'] ?? '');
    $course           = trim($_POST['course'] ?? '');
    $university       = trim($_POST['university'] ?? '');

    // Required fields validation
    $required = [
        "full_name" => "Full name is required.",
        "batch"     => "Batch is required.",
        "course"    => "Course is required.",
        "university" => "University is required."
    ];
    foreach ($required as $field => $message) {
        if (empty($_POST[$field])) {
            $errors[] = $message;
        }
    }

    // Special validations
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (strlen($password) < 4) $errors[] = "Password must be at least 4 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";
    if (!preg_match("/^\d{6,15}$/", $phone)) $errors[] = "Phone must be 16–15 digits.";

    // Check if email is already registered
    if (empty($errors)) {
        $sql  = "SELECT std_id FROM alumni WHERE std_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email is already registered.";
        }
        $stmt->close();
    }

    // Handle file upload (only JPG/PNG allowed for profile picture)
    $picture = handleUpload($_FILES["picture"]);
    if ($picture === false) $errors[] = "Only JPG or PNG files are allowed.";

    // If no errors → insert into DB
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $is_verified   = 0;

        $sql  = "INSERT INTO alumni (std_name, std_email, password_hash, phone, Batch, course_studied, university, picture, is_verified)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssi",
            $full_name,
            $email,
            $password_hash,
            $phone,
            $batch,
            $course,
            $university,
            $picture,
            $is_verified
        );

        if ($stmt->execute()) {
            // Redirect on success
            header("Location: login.php?signup=success");
            exit();
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();

// Page header
$pageTitle = "Signup";
include "header.php";
?>

<style>
    .input-group .btn {
        border-color: #ced4da;
        /* match input border */
    }

    /* Remove border overlap between input and toggle button */
    .input-group .form-control {
        border-right: none;
        /* remove right border of input */
    }

    .input-group .btn {
        border-left: none;
        /* remove left border of button */
    }

    /* Optional: keep rounded edges only on the outer sides */
    .input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    #form {
        border: 1px solid #B6B8B7;
        border-radius: 10px;
        border-width: 10px;
        padding: 30px;
        margin-top: 10px;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<div id="container" class="container mt-5 mb-3">

    <!-- Show errors if any -->
    <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($errors)): ?>
        <div class="alert alert-danger">
            ❌ <?php echo implode("<br>", $errors); ?>
        </div>
    <?php endif; ?>

    <!-- Signup Form -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-8">
            <form id="form" method="post" enctype="multipart/form-data" class="p-4 shadow rounded bg-light">
                <h3 class="text-center mb-4">Alumni Signup</h3>

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name*</label>
                    <input type="text" name="full_name" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email*</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password*</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password*</label>
                    <div class="input-group">
                        <input type="password" id="confirmPassword" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <!-- Phone -->
                <div class="mb-3">
                    <label class="form-label">Phone*</label>
                    <input type="tel" name="phone" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                        pattern="^\d{10,15}$" required>
                </div>

                <!-- Batch (JS-driven) -->
                <div class="mb-3">
                    <label class="form-label">Batch*</label>
                    <select id="batch" name="batch" class="form-control" required>
                        <option value="">Select batch</option>
                        <!-- JS will inject options here -->
                    </select>
                </div>

                <!-- Course -->
                <div class="mb-3">
                    <label class="form-label">Course of Study*</label>
                    <input type="text" name="course" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['course'] ?? ''); ?>" required>
                </div>

                <!-- University -->
                <div class="mb-3">
                    <label class="form-label">University*</label>
                    <input type="text" name="university" class="form-control"
                        value="<?php echo htmlspecialchars($_POST['university'] ?? ''); ?>" required>
                </div>

                <!-- File Upload -->
                <div class="mb-3">
                    <label class="form-label">Upload Profile Picture (JPG/PNG)</label>
                    <input type="file" name="picture" class="form-control" accept=".jpg,.png">
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-center">
                    <a href="login.php" class="text-decoration-none mr-3">Already have an account?</a>
                    <button type="submit" class="btn btn-outline-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Password toggle
    function togglePassword(fieldId, icon) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "👁️‍🗨️";
        } else {
            input.type = "password";
            icon.textContent = "👁️";
        }
    }
</script>

<?php include "footer.php"; ?>