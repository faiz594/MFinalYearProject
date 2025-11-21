<?php
include "configure.php";
include "header.php";
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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, visibility 0.6s ease;
  }

  .alert-popup.show {
    opacity: 1;
    visibility: visible;
  }

  #carousel {
    margin-top: 80px;
    /* Space for navbar */
  }

  #carousel .carousel-item {
    height: 70vh;
    min-height: 400px;
    background: no-repeat center center;
    background-size: cover;
    position: relative;
    overflow: hidden;
  }

  /* Overlay Gradient */
  #carousel .carousel-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
  }

  /* Caption at Bottom */
  #carousel .carousel-caption {
    bottom: 10px;
    /* Position at bottom */
    left: 50%;
    transform: translateX(-50%);
    z-index: 5;
    text-align: center;
  }

  #carousel .carousel-caption h5 {
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
  }

  #carousel .carousel-caption p {
    font-size: 1.1rem;
    color: #f0f0f0;
  }

  /* Carousel Controls - Modern Look */
  .carousel-control-prev,
  .carousel-control-next {
    width: 50px;
    height: 50px;
    top: 50%;
    opacity: 0.8;
  }

  .carousel-control-prev:hover,
  .carousel-control-next:hover {
    opacity: 1;
    background-color: rgba(0, 123, 255, 0.7);
    border-radius: 50%;
  }

  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    background-size: 50%, 50%;
    background-image: none;
    /* remove default icon */
    display: inline-block;
    width: 100%;
    height: 100%;
    position: relative;
  }

  /* Custom Arrows */
  .carousel-control-prev-icon::after,
  .carousel-control-next-icon::after {
    content: '';
    display: block;
    width: 15px;
    height: 15px;
    border-top: 3px solid white;
    border-right: 3px solid white;
    position: absolute;
    top: 50%;
    left: 50%;
  }

  /* View All Buttons */
  .view-all-btn {
    font-size: 1.1rem;
    font-weight: 600;
    padding: 10px 22px;
    display: inline-block;
    margin: 0 auto;
  }

  .equal-card {
    height: 100% !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .view-all-btn {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    padding: 10px 22px !important;
    display: inline-block;
    margin: 0 auto;
  }
</style>


<!-- Modern Carousel -->
<div id="carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <!-- Indicators -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#carousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#carousel" data-bs-slide-to="2"></button>
  </div>

  <!-- Slides -->
  <div class="carousel-inner">
    <div class="carousel-item active" style="background-image: url('../img/home/1.jpg');">
      <div class="carousel-caption d-none d-md-block">
        <h5>Friends Gathering</h5>
        <p>Party with friends and visits Joghor gol and other places.</p>
      </div>
    </div>
    <div class="carousel-item" style="background-image: url('../img/home/2.jpg');">
      <div class="carousel-caption d-none d-md-block">
        <h5>Convocation</h5>
        <p>This is the time when we got separated from our friends and seniors...</p>
      </div>
    </div>
    <div class="carousel-item" style="background-image: url('../img/faculty/faculty.jpg');">
      <div class="carousel-caption d-none d-md-block">
        <h5>Faculty</h5>
        <p>This is faculty of our Department</p>
      </div>
    </div>
  </div>

  <!-- Modern Controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- ================== HOD Section================== -->
<div class="container-fluid px-0 mt-2 pt-3 py-3" style="background:#e9e9e9;">
  <div class="container">

    <div class="row align-items-center">

      <!-- IMAGE LEFT -->
      <div class="col-md-3 text-center mb-4 mb-md-0">
        <img src="../img/faculty/Sir-Shakeel.JPG"
          class="rounded-circle img-fluid shadow"
          style="width: 200px; height: 200px; object-fit: cover;">
        <h5 class="fw-bold mt-3 mb-0">Shakeel Ahmad Khan</h5>
        <p class="text-muted">Head of Computer Science Department</p>
      </div>

      <!-- TEXT RIGHT -->
      <div class="col-md-9">
        <h3 class="fw-bold mb-3">Head of the Department Message</h3>
        <p>
          Welcome to the Department of Computer Science at Govt. Degree College Chitral.
          Our goal is to provide quality education and prepare students with the skills needed to excel in today’s fast-growing digital world.
        </p>
        <p>
          We take pride in our dedicated faculty, motivated students, and growing alumni who are contributing to education, software development, IT services, and entrepreneurship. By combining strong theoretical foundations with practical learning,
          we ensure our students are ready to face modern technological challenges.
        </p>
        <p>
          I warmly welcome you to be part of the Department of Computer Science at Govt. Degree College
          Chitral, and I
          look forward to seeing our students achieve excellence, contribute to the IT sector, and play their
          role in
          shaping a better future.
        </p>
        <pre class="mt-1 text-center">
                   Sincerely,
                  Shakeel Ahmad Khan, HOD computer Science
                        Govt. Degree College Chitral
                </pre>
      </div>

    </div>

  </div>
</div>



<!-- ========================= Alumni Section ========================= -->
<div class="container section mt-5">
  <div class="row">
    <div class="col-md-12 mb-3">
      <h2 class="section-head">Alumni</h2>
    </div>

    <!-- Dynamic Alumni -->
    <?php
    $alumni_query = "SELECT picture, std_name, phone FROM alumni LIMIT 3";
    $alumni_result = mysqli_query($conn, $alumni_query);

    if ($alumni_result && mysqli_num_rows($alumni_result) > 0):
      while ($alumnus = mysqli_fetch_assoc($alumni_result)):
        $alumniPhoto = "/fyp/home/uploads/" . basename($alumnus['picture']);
    ?>
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card equal-card shadow-lg border-0 rounded-3 text-center p-3">
            <img src="<?php echo $alumniPhoto; ?>"
              class="rounded-circle mx-auto d-block img-fluid shadow mb-3"
              style="width: 180px; height: 180px; object-fit: cover;">

            <h5 class="fw-bold"><?php echo htmlspecialchars($alumnus['std_name']); ?></h5>
            <p class="text-muted"><?php echo htmlspecialchars($alumnus['phone']); ?></p>
          </div>
        </div>
    <?php endwhile;
    endif; ?>

    <!-- View All Card -->
    <div class="col-md-3 col-sm-6 mb-4">
      <div class="card equal-card shadow-lg border-0 rounded-3 text-center p-4 d-flex flex-column justify-content-center">
        <h4 class="fw-bold mb-2">More Alumni</h4>
        <p class="text-muted">Click below to view all alumni.</p>

        <a href="Alumni.php" class="btn btn-primary view-all-btn mt-3">
          View All →
        </a>
      </div>
    </div>

  </div>
</div>



<!-- ========================= Events Section ========================= -->
<div class="container section mt-5">
  <div class="row">
    <div class="col-md-12 mb-3">
      <h2 class="section-head">Events</h2>
    </div>

    <?php
    $event_query = "SELECT banner_image, event_title, posted_on, description
                    FROM events ORDER BY posted_on DESC LIMIT 3";
    $event_result = mysqli_query($conn, $event_query);

    if ($event_result && mysqli_num_rows($event_result) > 0):
      while ($event = mysqli_fetch_assoc($event_result)):
        $eventImage = "../Admin/img/events/" . htmlspecialchars($event['banner_image']);
        $date = date("F d, Y", strtotime($event['posted_on']));
    ?>
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card equal-card shadow-lg border-0 rounded-3">

            <img src="<?php echo $eventImage; ?>"
              class="card-img-top img-fluid"
              style="height: 180px; object-fit: cover;">

            <div class="card-body text-center d-flex flex-column">
              <h5><?php echo htmlspecialchars($event['event_title']); ?></h5>
              <p class="text-muted small mb-1"><?php echo $date; ?></p>
              <p class="card-text small"><?php echo htmlspecialchars($event['description']); ?></p>
            </div>

          </div>
        </div>
    <?php endwhile;
    endif; ?>

    <!-- View All Card -->
    <div class="col-md-3 col-sm-6 mb-4">
      <div class="card equal-card shadow-lg border-0 rounded-3 text-center p-4 d-flex flex-column justify-content-center">
        <h4 class="fw-bold mb-2">More Events</h4>
        <p class="text-muted">Click below to view all events.</p>

        <a href="events.php" class="btn btn-primary view-all-btn mt-3">
          View All →
        </a>
      </div>
    </div>
  </div>
</div>
<div class="container section py-5">
  <!-- Section Title -->
  <div class="row mb-2">
    <div class="col-12 text-center">
      <h2 class="section-head display-5 fw-bold">Who We Are?</h2>
    </div>
  </div>

  <div class="row align-items-center g-5">

    <!-- Left Side - Text Content -->
    <div class="col-lg-7">
      <div class="pe-lg-7">
        <h5 class="text-primary fw-bold mb-3">Department of Computer Science</h5>
    <Pre>The Department of Computer Science at Govt. Degree College Chitral offers
    quality education in a remote yet culturally rich region. Emphasizing 
    practical skills and hands-on learning, it prepares graduatesfor careers 
    in education, software development, IT services,and entrepreneurship, 
    locally and internationally.
       <h4 class="fw-bold text-primary fs-3">Vision & Mission </h4>
❖ To empower students with skills for the modern digital world
❖ Updated curriculum and practical training
❖ University and industry linkages
❖ Research addressing community challenges
❖ Seminars, workshops, and career guidance
❖ Preparing graduates for higher education, global IT workforce.
        </Pre>
      </div>
    </div>

    <!-- Right Side - Full-width Image -->
    <div class="col-lg-5">
      <img src="../img/home/cs-bg.png" class="img-fluid rounded shadow w-100" alt="CS Department Chitral">
    </div>

  </div>
</div>

<?php include "footer.php"; ?>