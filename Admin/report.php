<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "configure.php";
include "Admin-sidebar.php"; // Sidebar + Navbar
?>

<style>
.export-btns button {
    margin-right: 10px;
}
</style>

<!-- MAIN CONTENT AREA -->
<div class="col-md-9 col-lg-10 ms-sm-auto px-4 mt-4">

    <h2 class="mb-4">Alumni Report (By Batch)</h2>

    <!-- SELECT BATCH -->
    <form method="POST">
        <label><b>Select Batch</b></label>
        <select name="batch" class="form-control" required>
            <option value="">Choose Batch</option>
            <?php
            $result = $conn->query("SELECT DISTINCT Batch FROM alumni ORDER BY Batch ASC");
            while ($row = $result->fetch_assoc()):
            ?>
                <option value="<?= $row['Batch'] ?>"
                    <?= (isset($_POST['batch']) && $_POST['batch'] == $row['Batch']) ? 'selected' : '' ?>>
                    <?= $row['Batch'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button class="btn btn-primary mt-3" name="show">
            <i class="bi bi-file-earmark-text me-1"></i> Generate Report
        </button>
    </form>

    <hr>

    <?php if (isset($_POST['show'])): ?>
        <?php
        $batch = $_POST['batch'];

        // Query with job history
        $stmt = $conn->prepare("
            SELECT
                a.std_id,
                a.std_name,
                a.std_email,
                a.Batch,
                (
                    SELECT CONCAT(
                        IFNULL(j.occupation, ''),
                        ' - ',
                        IFNULL(j.designation, ''),
                        ' at ',
                        IFNULL(j.workplace_name, '')
                    )
                    FROM job_history j
                    WHERE j.std_id = a.std_id
                    ORDER BY j.job_id DESC
                    LIMIT 1
                ) AS current_job
            FROM alumni a
            WHERE a.Batch = ?
        ");
        $stmt->bind_param("s", $batch);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $total = count($rows);
        ?>

        <h3>Batch: <?= $batch ?> Report</h3>
        <h4>Total Alumni Found: <?= $total ?></h4>

        <!-- EXPORT BUTTON -->
        <div class="export-btns mb-3">
            <button class="btn btn-success" onclick="printReport()">
                <i class="bi bi-printer me-1"></i> Print / Save PDF
            </button>
        </div>

        <!-- REPORT TABLE -->
        <div id="report-area" class="table-responsive">
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Job</th>
                    <th>Batch</th>
                </tr>

                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['std_name']) ?></td>
                        <td><?= htmlspecialchars($row['std_email']) ?></td>
                        <td><?= (empty($row['current_job']) || $row['current_job'] == " -  at ") ? "No Job Added" : htmlspecialchars($row['current_job']) ?></td>
                        <td><?= htmlspecialchars($row['Batch']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    <?php endif; ?>

</div> <!-- END MAIN CONTENT -->

<script>
function printReport() {
    let printContents = document.getElementById('report-area').innerHTML;
    let win = window.open('', '', 'height=700,width=900');
    win.document.write('<html><head><title>Alumni Report</title>');
    win.document.write('<style>table{width:100%;border-collapse:collapse;}th,td{padding:10px;border:1px solid #000;}</style>');
    win.document.write('</head><body>');
    win.document.write(printContents);
    win.document.write('</body></html>');
    win.print();
}
</script>
