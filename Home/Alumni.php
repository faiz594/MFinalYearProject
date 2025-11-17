<?php 
include "header.php"; 
include "configure.php"; // DB connection
?>

<style>
.alumni img {
  width: 150px;
  height: 150px;
  object-fit: cover;
}
</style>

<main>
  <div class="bg-light py-4 text-center">
    <h2 class="fw-bold">Alumni</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Alumni</li>
      </ol>
      <!-- Alumni Search Bar -->
      <div class="mb-3 d-flex justify-content-center">
        <div class="input-group" style="width: 300px;">
          <span class="input-group-text bg-success text-white">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" id="alumniSearch" class="form-control" placeholder="Search Alumni...">
        </div>
      </div>
    </nav>
  </div>

  <div class="container my-5">
    <div class="row justify-content-center">
      <?php
      // Fetch alumni name and picture
      $query = "SELECT std_name, picture FROM alumni ORDER BY std_id DESC";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

          // Make sure picture column has a value
          if (!empty($row['picture'])) {
            // Build absolute URL for localhost
            $imagePath = "/fyp/home/uploads/" . basename($row['picture']);
          } else {
            // Fallback image
            $imagePath = "img/default-profile.png";
          }
      ?>

      <div class="col-md-4 col-sm-6 mb-4">
        <div class="alumni card shadow-lg border-0 rounded-3 text-center p-3">
          <!-- Alumni Image -->
          <img src="<?php echo $imagePath; ?>" 
               class="rounded-circle mx-auto d-block img-fluid shadow mb-3" 
               alt="<?php echo htmlspecialchars($row['std_name']); ?>">

          <!-- Alumni Name -->
          <h5 class="fw-bold"><?php echo htmlspecialchars($row['std_name']); ?></h5>
          <p class="text-muted mb-1">Alumni</p>
        </div>
      </div>
      <?php
        }
      } else {
        echo "<p class='text-center text-muted'>No alumni found.</p>";
      }
      ?>
    </div>
  </div>
</main>

<script src="../javascript/javascript.js"></script>
</body>
</html>
