<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navbar with Logo</title>
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    /* Custom CSS for Logo and Hover Effect */

    .navbar-brand-logo {
      /* Kept your 100px height for the logo */
      height: 100px;
      margin-right: 15px;
    }

    /* --- Navbar Link Styling --- */
    .navbar-nav .nav-link {
      font-weight: 500;
      padding: 0.5rem 1rem;
      color: #333;
      position: relative;
      transition: color 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      color: #0d6efd;
    }

    /* Good Effect: Underline on Hover */
    .navbar-nav .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      display: block;
      margin-top: 5px;
      right: 0;
      background: #0d6efd;
      transition: width 0.3s ease;
    }

    .navbar-nav .nav-link:hover::after {
      width: 100%;
      left: 0;
      right: auto;
    }

    /* Optional: Change navbar background color */
    .custom-navbar {
      background-color: #f8f9fa;
      box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
      padding-top: 10px;
      padding-bottom: 10px;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg custom-navbar fixed-top py-0">
    <div class="container-fluid px-5 py-0 mb-2">

      <a class="navbar-brand d-flex align-items-center py-0" href="#">
        <img src="../img/home/brand.jpg" alt="CS Dept Logo" class="navbar-brand-logo">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav mr-5">
          <li class="nav-item mx-3">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link" href="Alumni.php">Alumni</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link" href="events.php">Events</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link" href="About.php">About Us</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link" href="Contact.php">Contact Us</a>
          </li>
        </ul>

        <div class="mx-auto d-flex align-items-center">
          <a class="btn btn-outline-primary mx-2" href="signup.php">Sign Up</a>
          <a class="btn btn-outline-primary" href="login.php">Sign In</a>
        </div>
      </div>
    </div>
  </nav>
