<?php
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

  <!-- Dashboard CSS -->
  <link rel="stylesheet" href="../css/dashboard.css">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap JS -->
  <script src="../javascript/bootstrap.bundle.min.js"></script>

  <style>
    /* Sidebar */
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

    /* CENTER TITLE ON MOBILE */
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

    <!-- Hamburger (mobile only) -->
    <button class="navbar-toggler d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Title centered on mobile -->
    <span class="navbar-brand mb-0 h6 flex-grow-1 text-center text-md-start">
      Alumni Management System
    </span>

    <!-- Admin Dropdown -->
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Admin
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
      </ul>
    </div>

  </div>
</nav>


<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR (Responsive) -->
    <nav id="sidebar" class="col-md-3 col-lg-2 collapse d-md-block">
      <h5 class="px-3 mb-3">Admin Panel</h5>

      <ul class="nav flex-column">

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'Admin-dashboard.php') ? 'active-link' : '' ?>" href="Admin-dashboard.php">
            <i class="bi bi-house mr-2"></i> Home
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'gallery.php') ? 'active-link' : '' ?>" href="gallery.php">
            <i class="bi bi-image mr-2"></i> Gallery
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'job.php') ? 'active-link' : '' ?>" href="job.php">
            <i class="bi bi-briefcase mr-2"></i> Jobs
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'Alumni-list.php') ? 'active-link' : '' ?>" href="Alumni-list.php">
            <i class="bi bi-people mr-2"></i> Alumni List
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'manage-event.php') ? 'active-link' : '' ?>" href="manage-event.php">
            <i class="bi bi-calendar-event mr-2"></i> Events
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'report.php') ? 'active-link' : '' ?>" href="report.php">
            <i class="bi bi-file-earmark-text mr-2"></i> Generate Report
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'send-email.php') ? 'active-link' : '' ?>" href="send-email.php">
            <i class="bi bi-envelope mr-2"></i> Send Email
          </a>
        </li>

      </ul>
    </nav>
