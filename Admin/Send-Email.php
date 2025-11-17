<?php
session_start();
include "configure.php";

// ====================== LOAD ALUMNI BY BATCH ======================
$selectedBatch = $_POST['batch'] ?? '';
$alumniEmails = [];
if ($selectedBatch) {
  $stmt = $conn->prepare("SELECT std_email FROM alumni WHERE batch = ?");
  $stmt->bind_param("i", $selectedBatch);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $alumniEmails[] = $row['std_email'];
  }
  $stmt->close();
}

// ====================== SEND EMAILS ======================
if (isset($_POST['send_emails'])) {
  $recipients = $_POST['selected_emails'] ?? [];
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if (!empty($recipients) && $subject && $message) {
    $headers = "From: admin@yourdomain.com\r\n";
    $headers .= "Reply-To: admin@yourdomain.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $successCount = 0;
    foreach ($recipients as $email) {
      $cleanEmail = trim($email);
      if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL) && mail($cleanEmail, $subject, $message, $headers)) {
        $successCount++;
      }
    }

    $_SESSION['message'] = "✅ Sent $successCount out of " . count($recipients) . " emails successfully!";
    if ($successCount < count($recipients)) {
      $_SESSION['message'] .= " ⚠ Some failed — check server logs.";
    }

    $selectedBatch = '';
    $alumniEmails = [];
  } else {
    $_SESSION['error'] = "⚠ Please fill all fields and select at least one recipient.";
  }
}

// ====================== FETCH BATCHES ======================
$batchesResult = $conn->query("SELECT DISTINCT batch FROM alumni ORDER BY batch DESC");
$batches = [];
while ($row = $batchesResult->fetch_assoc()) {
  $batches[] = $row['batch'];
}
$conn->close();

// ====================== FLASH MESSAGES ======================
$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);
?>

<?php include "Admin-sidebar.php"; ?>

<!-- ✅ Main Content (aligned beside sidebar) -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="compose-box mt-3" id="email-box">
    <div class="compose-header">
      <i class="bi bi-envelope-plus me-2"></i> New Message
    </div>

    <!-- Flash Messages -->
    <?php if ($message): ?>
      <div class="alert alert-success mt-3"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (empty($alumniEmails)): ?>
      <!-- ====================== SELECT BATCH FORM ====================== -->
      <form method="POST" class="mt-3">
        <div class="mb-3">
          <label for="batch" class="form-label">Select Batch</label>
          <select name="batch" id="batch" class="form-select" required>
            <option value="">-- Select Batch --</option>
            <?php foreach ($batches as $batch): ?>
              <option value="<?php echo $batch; ?>" <?php echo ($selectedBatch == $batch) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($batch); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Load Alumni</button>
      </form>

    <?php else: ?>
      <!-- ====================== EMAIL FORM ====================== -->
      <form method="POST" class="mt-3">
        <div class="mb-3">
          <label class="form-label">
            Alumni from Batch <?php echo htmlspecialchars($selectedBatch); ?> (<?php echo count($alumniEmails); ?> total)
          </label>
          <div id="alumniList" class="border p-2 rounded bg-light" style="max-height: 150px; overflow-y: auto;">
            <?php foreach ($alumniEmails as $email): ?>
              <div class="form-check">
                <input class="form-check-input alumni-check" type="checkbox" name="selected_emails[]"
                  value="<?php echo htmlspecialchars($email); ?>"
                  id="email_<?php echo md5($email); ?>" checked>
                <label class="form-check-label" for="email_<?php echo md5($email); ?>">
                  <?php echo htmlspecialchars($email); ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Recipients Preview -->
        <div class="mb-2">
          <label class="form-label">Recipients Preview</label>
          <input type="text" id="recipients" class="form-control"
            value="<?php echo htmlspecialchars(implode(', ', $alumniEmails)); ?>" readonly>
        </div>

        <!-- Subject -->
        <div class="mb-2">
          <input type="text" name="subject" class="form-control" placeholder="Subject" required>
        </div>

        <!-- Message Body -->
        <div class="compose-body mb-3">
          <textarea name="message" id="message" class="form-control" rows="10" placeholder="Write your message..." required></textarea>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-danger mr-2" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>'">
            <i class="bi bi-x-circle"></i> Discard
          </button>
          <button type="submit" name="send_emails" class="btn btn-primary">
            <i class="bi bi-send"></i> Send
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</main>

<!-- ====================== JS ====================== -->
<script>
  // Update Recipients Preview dynamically
  document.querySelectorAll('.alumni-check').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const selected = Array.from(document.querySelectorAll('.alumni-check:checked')).map(cb => cb.value);
      document.getElementById('recipients').value = selected.join(', ');
    });
  });
</script>

</body>
</html>
