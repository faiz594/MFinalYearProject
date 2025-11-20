<?php
// Detect the current page
$current_page = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle ?? "Alumni Portal"; ?></title>
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
  <style>
    .active-link {
      background-color: #ccb4ceff !important;
      color: #0d6efd !important;
      font-weight: 600 !important;
      border-radius: 8px 8px 8px 8px;
      box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
    }

    .active-link i {
      color: #0d6efd !important;
    }

    .active-link:hover {
      background-color: #ccb4ceff !important;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <header id="header">
    <nav class="navbar navbar-expand-md navbar-light">
      <div class="container justify-content-between">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
          <img src="../CSLOGO.png" alt="Logo" width="150" height="150">
        </a>

        <!-- Hamburger menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse sticky-top" id="mynavbar">
          <ul class="navbar-nav mb-2 mb-lg-0 me-3">
            <li class="nav-item"><a class="nav-link <?= ($current_page == 'index.php') ? 'active-link' : '' ?>" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link <?= ($current_page == 'alumni.php') ? 'active-link' : '' ?>"  href="alumni.php">Alumni</a></li>
            <li class="nav-item"><a class="nav-link <?= ($current_page == 'events.php') ? 'active-link' : '' ?>" href="events.php">Events</a></li>
            <li class="nav-item"><a class="nav-link <?= ($current_page == 'signup.php') ? 'active-link' : '' ?>" href="signup.php">Sign Up</a></li>
            <li class="nav-item"><a class="nav-link <?= ($current_page == 'login.php') ? 'active-link' : '' ?>" href="login.php">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>