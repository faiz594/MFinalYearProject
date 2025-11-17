<?php
include "Alumni-sidebar.php";
?>

<!-- Custom Styles -->
<style>
  .section-head {
    font-weight: bold;
    color: #198754;
    margin-bottom: 15px;
  }

  .card {
    border-radius: 20px;
    transition: all 0.4s ease;
    border: none;
  }

  .card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  }

  .card img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 50%;
    margin: 20px auto 10px;
    display: block;
  }

  .card-body {
    text-align: center;
  }

  .card-title {
    font-weight: 600;
    color: #0d6efd;
  }

  .card-text {
    color: #6c757d;
  }

  .section {
    margin-top: 10px;
  }

  .btn-view {
    background-color: #0d6efd;
    color: white;
    border-radius: 20px;
    padding: 5px 15px;
    font-size: 14px;
  }

  .btn-view:hover {
    background-color: #0b5ed7;
  }

  /* Pagination */
  .pagination {
    justify-content: center;
    margin-top: 20px;
  }

  .pagination .page-link {
    color: #198754;
  }

  .pagination .active .page-link {
    background-color: #198754;
    border-color: #198754;
    color: white;
  }
</style>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <h4 class="mt-3 text-center text-success fw-bold">Welcome back, Alumni!</h4>

  <!-- ==========================
        🎉 EVENTS SECTION
  =========================== -->
  <div class="container section" id="events">
    <h2 class="section-head"><i class="bi bi-calendar-event me-2"></i> Events</h2>

    <div class="row">
      <?php
      // Pagination setup for Events
      $limit = 3;
      $page = isset($_GET['event_page']) ? (int)$_GET['event_page'] : 1;
      $offset = ($page - 1) * $limit;

      $totalEventsQuery = "SELECT COUNT(*) AS total FROM events";
      $totalEventsResult = mysqli_query($conn, $totalEventsQuery);
      $totalEvents = mysqli_fetch_assoc($totalEventsResult)['total'];
      $totalPagesEvents = ceil($totalEvents / $limit);

      $eventQuery = "SELECT event_title, description, schedule, banner_image FROM events ORDER BY posted_on DESC LIMIT $limit OFFSET $offset";
      $eventResult = mysqli_query($conn, $eventQuery);

      if (mysqli_num_rows($eventResult) > 0) {
        while ($event = mysqli_fetch_assoc($eventResult)) {
          $event_title = htmlspecialchars($event['event_title']);
          $description = htmlspecialchars($event['description']);
          $schedule = htmlspecialchars($event['schedule']);
          $banner_image = !empty($event['banner_image'])
            ? "../Admin/img/events/" . basename($event['banner_image'])
            : "../img/default-profile.png";
      ?>
          <div class="col-md-4 col-sm-6 mb-4">
            <div class="card shadow-lg border-0 text-center p-3">
              <img src="<?= $banner_image ?>" alt="Event Image">
              <div class="card-body">
                <h5 class="card-title"><?= $event_title ?></h5>
                <p class="small text-muted"><i class="bi bi-calendar"></i> <?= $schedule ?></p>
                <p class="card-text small"><?= substr($description, 0, 100) . '...'; ?></p>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<div class='text-center text-muted'>No events available at the moment.</div>";
      }
      ?>
    </div>

    <!-- Pagination for Events -->
    <?php if ($totalPagesEvents > 1): ?>
      <nav>
        <ul class="pagination">
          <?php for ($i = 1; $i <= $totalPagesEvents; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
              <a class="page-link" href="?event_page=<?= $i ?>#events"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>

  <!-- ==========================
        💼 JOBS SECTION
  =========================== -->
  <div class="container section" id="jobs">
    <h2 class="section-head"><i class="bi bi-briefcase me-2"></i> Jobs</h2>

    <div class="row">
      <?php
      // Pagination setup for Jobs
      $jobLimit = 3;
      $jobPage = isset($_GET['job_page']) ? (int)$_GET['job_page'] : 1;
      $jobOffset = ($jobPage - 1) * $jobLimit;

      $totalJobsQuery = "SELECT COUNT(*) AS total FROM jobs";
      $totalJobsResult = mysqli_query($conn, $totalJobsQuery);
      $totalJobs = mysqli_fetch_assoc($totalJobsResult)['total'];
      $totalPagesJobs = ceil($totalJobs / $jobLimit);

      $jobQuery = "SELECT job_id, job_title, company, location, description, requirements, posted_on, closes_on FROM jobs ORDER BY posted_on DESC LIMIT $jobLimit OFFSET $jobOffset";
      $jobResult = mysqli_query($conn, $jobQuery);

      if (mysqli_num_rows($jobResult) > 0) {
        while ($job = mysqli_fetch_assoc($jobResult)) {
          $job_id = $job['job_id'];
          $job_title = htmlspecialchars($job['job_title']);
          $company = htmlspecialchars($job['company']);
          $location = htmlspecialchars($job['location']);
          $description = htmlspecialchars($job['description']);
          $requirements = htmlspecialchars($job['requirements']);
          $posted_on = date("M d, Y", strtotime($job['posted_on']));
          $closes_on = date("M d, Y", strtotime($job['closes_on']));
      ?>
          <div class="col-md-4 col-sm-6 mb-4">
            <div class="card job-card shadow-lg border-0 text-center p-3">
              <div class="card-body">
                <h5 class="card-title"><?= $job_title ?></h5>
                <p class="card-text small"><strong>Company:</strong> <?= $company ?></p>
                <p class="text-muted small mb-2"><i class="bi bi-calendar-check"></i> Posted on: <?= $posted_on ?></p>
                <button class="btn btn-view" data-bs-toggle="modal" data-bs-target="#jobModal<?= $job_id ?>">View Details</button>
              </div>
            </div>
          </div>

          <!-- Job Modal -->
          <div class="modal fade" id="jobModal<?= $job_id ?>" tabindex="-1" aria-labelledby="jobModalLabel<?= $job_id ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title"><?= $job_title ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                  <p><strong>Company:</strong> <?= $company ?></p>
                  <p><strong>Location:</strong> <?= $location ?></p>
                  <p><strong>Description:</strong> <?= nl2br($description) ?></p>
                  <p><strong>Requirements:</strong> <?= nl2br($requirements) ?></p>
                  <p><strong>Posted On:</strong> <?= $posted_on ?></p>
                  <p><strong>Closes On:</strong> <?= $closes_on ?></p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
                </div>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<div class='text-center text-muted'>No job postings available yet.</div>";
      }
      ?>
    </div>

    <!-- Pagination for Jobs -->
    <?php if ($totalPagesJobs > 1): ?>
      <nav>
        <ul class="pagination">
          <?php for ($i = 1; $i <= $totalPagesJobs; $i++): ?>
            <li class="page-item <?= ($i == $jobPage) ? 'active' : '' ?>">
              <a class="page-link" href="?job_page=<?= $i ?>#jobs"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>

  <!-- ==========================
        🖼️ GALLERY SECTION
  =========================== -->
  <div class="container section" id="gallery">
    <h2 class="section-head"><i class="bi bi-image me-2"></i> Gallery</h2>

    <div class="row">
      <?php
      // Pagination for Gallery
      $galleryLimit = 4;
      $galleryPage = isset($_GET['gallery_page']) ? (int)$_GET['gallery_page'] : 1;
      $galleryOffset = ($galleryPage - 1) * $galleryLimit;

      $totalGalleryQuery = "SELECT COUNT(*) AS total FROM gallery";
      $totalGalleryResult = mysqli_query($conn, $totalGalleryQuery);
      $totalGallery = mysqli_fetch_assoc($totalGalleryResult)['total'];
      $totalPagesGallery = ceil($totalGallery / $galleryLimit);

      $galleryQuery = "SELECT image, image_title, description FROM gallery ORDER BY uploaded_on DESC LIMIT $galleryLimit OFFSET $galleryOffset";
      $galleryResult = mysqli_query($conn, $galleryQuery);

      if (mysqli_num_rows($galleryResult) > 0) {
        while ($img = mysqli_fetch_assoc($galleryResult)) {
          $imagePath = !empty($img['image']) ? "../img/gallery/" . $img['image'] : "../img/default.jpg";
          $title = htmlspecialchars($img['image_title']);
          $description = htmlspecialchars($img['description']);
      ?>
          <div class="col-md-4 col-sm-6 mb-4">
            <div class="card shadow-lg border-0 text-center p-3">
              <img src="<?php echo $imagePath; ?>" alt="Gallery Image">
              <div class="card-body">
                <h6 class="card-title"><?php echo $title; ?></h6>
                <p class="card-text small"><?php echo $description; ?></p>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<div class='text-center text-muted'>No images found in the gallery.</div>";
      }
      ?>
    </div>

    <!-- Pagination for Gallery -->
    <?php if ($totalPagesGallery > 1): ?>
      <nav>
        <ul class="pagination">
          <?php for ($i = 1; $i <= $totalPagesGallery; $i++): ?>
            <li class="page-item <?= ($i == $galleryPage) ? 'active' : '' ?>">
              <a class="page-link" href="?gallery_page=<?= $i ?>#gallery"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</main>

<script src="../js/bootstrap.bundle.js"></script>
</body>
</html>
