<?php 
include "configure.php";
include "header.php"; 

// Removed pagination setup — all events will load without pagination
?>

<style>
.nav-link.active {
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 6px;
}
.alert-popup {
  position: fixed;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1050;
  min-width: 350px;
  padding: 15px 20px;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 500;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.6s ease, visibility 0.6s ease;
}
.alert-popup.show {
  opacity: 1;
  visibility: visible;
}
</style>

<!-- ================ Home Banner Area =================  -->
<div id="carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../img/home/1.jpg" class="d-block mx-auto w-100" style="object-fit:cover;" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Friends Gathering</h5>
        <p>Party with friends and visits Joghor gol and other places.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="../img/home/2.jpg" class="d-block mx-auto w-100" style="object-fit:cover;" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Convocation</h5>
        <p>This is the time when we got separated from our friends and seniors...</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="../img/faculty/faculty.jpg" class="d-block mx-auto w-100" style="object-fit:cover;" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Faculty</h5>
        <p>This is faculty of our Department</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Department + HOD Section -->
<div class="container py-5">
  <div class="row justify-content-center g-1">
    <div class="col-md-5">
      <div class="card mb-4">
        <img src="../img/home/cs-bg.png" alt="CS Department Chitral">
        <div class="card-body">
          <h5 class="title-highlight text-primary">Department of Computer Science</h5>
          <p>
            The Department of Computer Science at <strong>Govt. Degree College Chitral</strong> is a
            growing hub of knowledge and innovation in the field of Computer Science...
          </p>
          <a href="intro.html" class="btn btn-outline-primary btn-sm btn-read">Read More</a>
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <div class="card">
        <img src="../img/faculty/Sir-Shakeel.JPG" alt="HOD Shakeel Ahmad Khan">
        <div class="card-body">
          <h5 class="title-highlight text-primary">Message by the HoD</h5>
          <p>
            Welcome to the Department of Computer Science at <strong>Govt. Degree College Chitral</strong>...
          </p>
          <a href="Hod-message.html" class="btn btn-outline-primary btn-sm btn-read">Read More</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Alumni Section -->
<div class="container section">
  <div class="row">
    <div class="col-md-12">
      <h2 class="section-head"><a href="Alumni.html">Alumni</a></h2>
    </div>

    <?php
    // Fetch alumni dynamically
    $alumni_query = "SELECT picture, std_name, phone FROM alumni LIMIT 3";
    $alumni_result = mysqli_query($conn, $alumni_query);

    if ($alumni_result && mysqli_num_rows($alumni_result) > 0) {
        while ($alumnus = mysqli_fetch_assoc($alumni_result)) {
            $alumniPhoto = "/fyp/home/uploads/" . basename($alumnus['picture']);
            ?>
            <div class="col-md-4 col-sm-6 mb-4">
              <div class="alumni card shadow-lg border-0 rounded-3 text-center p-3">
                <img src="<?php echo $alumniPhoto; ?>" class="rounded-circle mx-auto d-block img-fluid shadow mb-3"
                  alt="<?php echo htmlspecialchars($alumnus['std_name']); ?>" style="width: 250px; height: 250px; object-fit: cover;">
                <h5 class="fw-bold"><?php echo htmlspecialchars($alumnus['std_name']); ?></h5>
                <p class="text-muted mb-1"><?php echo htmlspecialchars($alumnus['phone']); ?></p>
              </div>
            </div>
            <?php
        }
    } else {
        echo '<p class="text-muted">No alumni found.</p>';
    }
    ?>
  </div>
</div>

<!-- Events Section -->
<div class="container section">
  <div class="row">
    <div class="col-md-12">
      <h2 class="section-head"><a href="events.html">Events</a></h2>
    </div>

    <?php
    // Fetch events
    $event_query = "SELECT banner_image, event_title, posted_on, description FROM events ORDER BY posted_on DESC LIMIT 3";
    $event_result = mysqli_query($conn, $event_query);

    if ($event_result && mysqli_num_rows($event_result) > 0) {
        while ($event = mysqli_fetch_assoc($event_result)) {
            $date = date("F d, Y", strtotime($event['posted_on']));
            $eventImage = "../Admin/img/events/" . htmlspecialchars($event['banner_image']);
            ?>
            <div class="col-md-4 col-sm-6 mb-4">
              <div class="news-post card shadow-sm border-0 rounded-3">
                <img src="<?php echo $eventImage; ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($event['event_title']); ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                  <h5 class="card-title text-center"><a href="#"><?php echo htmlspecialchars($event['event_title']); ?></a></h5>
                  <div class="post-date text-muted mb-2 text-center"><?php echo $date; ?></div>
                  <p class="card-text text-center"><?php echo htmlspecialchars($event['description']); ?></p>
                </div>
              </div>
            </div>
            <?php
        }
    } else {
        echo '<p class="text-muted">No events found.</p>';
    }
    ?>
  </div>
</div>

<?php include "footer.php"; ?>
