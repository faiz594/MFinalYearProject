<?php
// Start session & connect database
session_start();
include "configure.php";

// Get alumni ID from session
$alumni_id = $_SESSION['alumni_id'] ?? null;

// Default values
$alumni_name = "Alumni";
$alumni_picture = "../uploads/default.png"; // fallback

// Fetch alumni info from DB
if ($alumni_id) {
    $query = "SELECT std_name, picture FROM alumni WHERE std_id = '$alumni_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $alumni_name = htmlspecialchars($row['std_name']);
        $alumni_picture = !empty($row['picture'])
            ? "../uploads/" . htmlspecialchars($row['picture'])
            : "../uploads/default.png";
    }
}

// Detect the current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Alumni Dashboard</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/dashboard.css">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- JS -->
    <script src="../javascript/bootstrap.bundle.min.js"></script>

    <style>
        /* Default link style */
        #sidebar .nav-link {
            color: #5c0af5ff;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(4px);
        }

        .active-link {
            background-color: #ffffff !important;
            color: #0d6efd !important;
            font-weight: 600 !important;
            border-left: 5px solid #0d6efd;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        }

        .active-link i {
            color: #0d6efd !important;
        }

        .active-link:hover {
            background-color: #e9f1ff !important;
        }

        /* Navbar image style */
        .navbar-brand-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                top: 56px;
                left: 0;
                height: calc(100% - 56px);
                z-index: 1050;
                overflow-y: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary sticky-top p-2 shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-nowrap">

            <!-- Left side: Hamburger + Alumni Image -->
            <div class="d-flex align-items-center flex-shrink-0">
                <button class="navbar-toggler d-md-none me-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- 🔹 Replace text with alumni image -->
                <a href="Alumni-dashboard.php" class="d-flex align-items-center text-white text-decoration-none">
                    <img src="<?= $alumni_picture ?>" alt="Profile Picture" class="navbar-brand-img">
                    <span class="fw-bold d-none d-sm-inline"><?= $alumni_name ?></span>
                </a>
            </div>

            <!-- Right side: Alumni Dropdown -->
            <div class="dropdown ms-auto flex-shrink-0">
                <button class="btn btn-primary dropdown-toggle" type="button" id="alumniMenu" data-bs-toggle="dropdown"
                    aria-expanded="false">
                  <?= htmlspecialchars($alumni_name) ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alumniMenu">
                    <li><a class="dropdown-item" href="update-profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-primary text-white sidebar collapse sticky-top"
                style="top: 56px; height: calc(100vh - 56px);">
                <div class="position-sticky pt-3">
                    <h5 class="px-3">Alumni</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a
                                class="nav-link text-white <?= ($current_page == 'Alumni-dashboard.php') ? 'active-link' : '' ?>"
                                href="Alumni-dashboard.php"><i class="bi bi-house mr-2"></i> Home</a></li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= ($current_page == 'update-profile.php') ? 'active-link' : '' ?>"
                                href="update-profile.php"><i class="bi bi-person-circle mr-2"></i> Update Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= ($current_page == 'job-history.php') ? 'active-link' : '' ?>"
                                href="job-history.php"><i class="bi bi-briefcase mr-2"></i> Job History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= ($current_page == 'password-change.php') ? 'active-link' : '' ?>"
                                href="password-change.php"><i class="bi bi-key mr-2"></i> Change Password</a>
                        </li>
                    </ul>
                </div>
            </nav>
