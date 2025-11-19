<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "configure.php"; // ✅ Database connection

// ------------------ Fetch Counts from Database ------------------ //
$alumni_count = 0;
$job_count = 0;
$event_count = 0;
$gallery_count = 0;

// Alumni count
$alumni_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM alumni");
if ($alumni_result) {
  $row = mysqli_fetch_assoc($alumni_result);
  $alumni_count = $row['total'];
}

// Job count
$job_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM job_history");
if ($job_result) {
  $row = mysqli_fetch_assoc($job_result);
  $job_count = $row['total'];
}

// Events count
$event_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events");
if ($event_result) {
  $row = mysqli_fetch_assoc($event_result);
  $event_count = $row['total'];
}

// Gallery count
$gallery_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM gallery");
if ($gallery_result) {
  $row = mysqli_fetch_assoc($gallery_result);
  $gallery_count = $row['total'];
}
include "Admin-sidebar.php";
?>

<!-- Main Content -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <h5 class="mt-3 text-center">Welcome back Admin!</h5>

  <div class="row mt-4 g-3">
    <!-- Alumni -->
    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-12">
      <div class="dashboard-card mb-2 bg-blue">
        <div>
          <h4><?php echo $alumni_count; ?></h4>
          <p>Alumni</p>
        </div>
        <i class="fa fa-users"></i>
      </div>
    </div>

    <!-- Jobs -->
    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-12">
      <div class="dashboard-card mb-2 bg-yellow">
        <div>
          <h4><?php echo $job_count; ?></h4>
          <p>Jobs</p>
        </div>
        <i class="fa fa-briefcase"></i>
      </div>
    </div>

    <!-- Events -->
    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-12">
      <div class="dashboard-card mb-2 bg-blue">
        <div>
          <h4><?php echo $event_count; ?></h4>
          <p>Upcoming Events</p>
        </div>
        <i class="fa fa-calendar"></i>
      </div>
    </div>

    <!-- Gallery -->
    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-12">
      <div class="dashboard-card mb bg-yellow">
        <div>
          <h4><?php echo $gallery_count; ?></h4>
          <p>Gallery Items</p>
        </div>
        <i class="fa fa-image"></i>
      </div>
    </div>
  </div>
</main>

</div>
</div>
</body>
</html>
