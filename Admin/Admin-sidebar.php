<?php
// Detect the current page file name
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

  <!-- custom css -->
  <link rel="stylesheet" href="../css/dashboard.css">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap JS -->
  <script src="../javascript/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Sidebar link style */
    #sidebar .nav-link {
      color: white;
      font-weight: 500;
      padding: 12px 18px;
      border-radius: 8px;
      transition: 0.25s ease;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.95rem;
    }

    /* Hover effect */
    #sidebar .nav-link:hover {
      background: rgba(255, 255, 255, 0.18);
      transform: translateX(6px);
    }

    /* Active link */
    .active-link {
      background: #ffffff !important;
      color: #0d6efd !important;
      font-weight: 600;
      border-left: 5px solid #0d6efd;
      border-radius: 0 12px 12px 0;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
    }

    .active-link i {
      color: #0d6efd !important;
    }

    /* Sidebar fixed on left */
    #sidebar {
      position: fixed;
      top: 56px;
      left: 0;
      height: calc(100% - 56px);
      background: #0d6efd;
      color: white;
      overflow-y: auto;
      padding-top: 20px;
    }

    /* Main content adjustment */
    main, .container-fluid > .row > :not(#sidebar) {
      margin-left: 240px;
    }

    /* Mobile collapse sidebar */
    @media (max-width: 768px) {
      main {
        margin-left: 0 !important;
      }
      #sidebar {
        position: fixed;
        width: 70%;
        z-index: 1050;
      }
    }
  </style>
</head>

<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-dark bg-primary sticky-top p-2 shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">

      <button class="navbar-toggler d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <span class="navbar-brand mb-0 h6">Alumni Management System</span>

      <div class="dropdown ms-auto">
        <button class="btn btn-primary dropdown-toggle" type="button" id="adminMenu" data-bs-toggle="dropdown">
          Admin
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminMenu">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="#">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar -->
      <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
        <div class="pt-3">
          <h5 class="px-3 mb-3">Admin Panel</h5>

          <ul class="nav flex-column">

            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'Admin-dashboard.php') ? 'active-link' : '' ?>" href="Admin-dashboard.php">
                <i class="bi bi-house mr-2"></i> Home
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'gallery.php') ? 'active-link' : '' ?>" href="gallery.php">
                <i class="bi bi-image"></i> Gallery
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'job.php') ? 'active-link' : '' ?>" href="job.php">
                <i class="bi bi-briefcase mr-2"></i> Jobs
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'Alumni-list.php') ? 'active-link' : '' ?>" href="Alumni-list.php">
                <i class="bi bi-people"></i> Alumni List
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'manage-event.php') ? 'active-link' : '' ?>" href="manage-event.php">
                <i class="bi bi-calendar-event mr-2"></i> Events
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?= ($current_page == 'send-email.php') ? 'active-link' : '' ?>" href="send-email.php">
                <i class="bi bi-envelope mr-2"></i> Send Email
              </a>
            </li>

          </ul>
        </div>
      </nav>
