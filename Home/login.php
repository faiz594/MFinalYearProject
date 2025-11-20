<?php
session_start();
include "configure.php"; // DB connection

$error = "";

// 🔹 Reusable Login Function
function loginUser($conn, $table, $identifier, $inputValue, $password, $redirectPage, $sessionPrefix, $idField)
{
  $sql = "SELECT * FROM $table WHERE $identifier = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $inputValue);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows == 1) {
    $row = $res->fetch_assoc();

    // ✅ Verify password
    if (password_verify($password, $row['password_hash'])) {

      // ✅ Store sessions for future use
      $_SESSION[$sessionPrefix . '_id']   = $row[$idField];      // user_id or std_id
      $_SESSION[$sessionPrefix . '_name'] = $row[$identifier];   // username or std_email
      $_SESSION['logged_in'] = true;

//       echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

      // ✅ Redirect to dashboard
      header("Location: $redirectPage");
      exit();

    } else {
      return "Invalid $sessionPrefix credentials.";
    }
  } else {
    return ucfirst($sessionPrefix) . " not found.";
  }
}

// 🔹 Alumni Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['alumni_login'])) {
  $error = loginUser(
    $conn,
    "alumni",
    "std_email",
    $_POST['email'],
    $_POST['password'],
    "../Alumni/Alumni-dashboard.php",
    "alumni",
    "std_id"
  );
}

// 🔹 Admin Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
  $error = loginUser(
    $conn,
    "admin",
    "username",
    $_POST['username'],
    $_POST['password'],
    "../Admin/Admin-dashboard.php",
    "admin",
    "user_id"
  );
}

include "header.php";
?>

<?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
  <div id="successPopup" class="alert alert-success alert-popup">
    ✅ Signup successful! Please Login Here!
  </div>
<?php endif; ?>

<style>
  .password-wrapper {
    display: flex;
    width: 100%;
  }
  .password-wrapper .form-control {
    border-radius: 5px 0 0 5px;
    border-right: none;
    background-color: #E8F0FE;
  }
  .password-toggle {
    cursor: pointer;
    background-color: #E8F0FE;
    border-left: none;
    padding: 5px 12px;
    border-radius: 0 5px 5px 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>

<div class="bg-light text-center">
  <h2 class="fw-bold">Login Admin/Alumni</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-center mb-0">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Login</li>
    </ol>
  </nav>
</div>

<div class="container mt-1 login-container">
  <div class="card d-flex justify-content-center align-items-center border border-3 border-dark min-vh-100">
    <img src="../img/home/cs-logo.png" class="rounded-circle mb-2 mt-1" alt="Logo" style="width:100px;height:100px;">
    <ul class="nav nav-tabs nav-fill" id="loginTabs">
      <li class="nav-item"><button class="nav-link active fw-bold" id="alumni-tab" data-bs-toggle="tab" data-bs-target="#alumni">Alumni</button></li>
      <li class="nav-item"><button class="nav-link fw-bold" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin">Admin</button></li>
    </ul>
    <div class="card-body tab-content">

      <!-- Alumni Login -->
      <div class="tab-pane fade show active" id="alumni">
        <h4 class="text-center mb-3">Alumni Login</h4>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
          <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
          <div class="mb-3 password-wrapper">
            <input type="password" id="alumniPassword" name="password" class="form-control" placeholder="Password" required>
            <span class="password-toggle" onclick="togglePassword('alumniPassword', this)">👁️</span>
          </div>
          <button type="submit" name="alumni_login" class="btn btn-custom w-100">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Register</a></p>
      </div>

      <!-- Admin Login -->
      <div class="tab-pane fade" id="admin">
        <h4 class="text-center mb-3">Admin Login</h4>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
          <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Admin Username" required></div>
          <div class="mb-3 password-wrapper">
            <input type="password" id="adminPassword" name="password" class="form-control" placeholder="Password" required>
            <span class="password-toggle" onclick="togglePassword('adminPassword', this)">👁️</span>
          </div>
          <button type="submit" name="admin_login" class="btn btn-custom w-100">Login</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  function togglePassword(inputId, icon) {
    const field = document.getElementById(inputId);
    if (field.type === "password") {
      field.type = "text";
      icon.textContent = "👁️‍🗨️";
    } else {
      field.type = "password";
      icon.textContent = "👁️";
    }
  }
</script>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger text-center mt-2"><?php echo $error; ?></div>
<?php endif; 
include_once "footer.php"
?>
</body>
</html>
