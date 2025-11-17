<?php
session_start();
include "configure.php";
include "Admin-sidebar.php";
?>
<style>
.export-btns button {
    margin-right: 10px;
}
</style>
<div class="container mt-5">
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
        <button class="btn btn-primary mt-3" name="show">Generate Report</button>
    </form>
    <hr>
    <?php if (isset($_POST['show'])): ?>
        <?php
        $batch = $_POST['batch'];
        // ⭐ Updated query with occupation, designation, workplace_name
        // Fixed: Changed j.alumni_id to j.std_id to match your job_history table schema
        // Also assuming 'job_id' is the primary key for ordering; adjust if it's 'id'
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
        $total = $result->num_rows;
        
        // Store results in an array to reuse for CSV without re-executing
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        ?>
        <h3>Batch: <?= $batch ?> Report</h3>
        <h4>Total Alumni Found: <?= $total ?></h4>
        <!-- EXPORT BUTTONS -->
        <div class="export-btns mb-3">
            <button class="btn btn-success" onclick="printReport()">Print / Save PDF</button>
            <button class="btn btn-info" onclick="exportCSV()">Export CSV</button>
        </div>
        <!-- REPORT TABLE -->
        <div id="report-area">
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Job</th>
                    <th>Batch</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['std_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['std_email'] ?? '') ?></td>
                        <td><?= (empty($row['current_job']) || $row['current_job'] == " - at ") ? 'No Job Added' : htmlspecialchars($row['current_job'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['Batch'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <!-- CSV DATA -->
        <textarea id="csvData" style="display:none;">
Name,Email,Current Job,Batch
<?php
foreach ($rows as $row) {
    $name = $row['std_name'] ?? '';
    $email = $row['std_email'] ?? '';
    $batch_val = $row['Batch'] ?? '';
    $job = (empty($row['current_job']) || $row['current_job'] == " - at ") ? 'No Job Added' : ($row['current_job'] ?? '');
    echo '"' . str_replace('"', '""', $name) . '","' . str_replace('"', '""', $email) . '","' . str_replace('"', '""', $job) . '","' . str_replace('"', '""', $batch_val) . '"' . "\n";
}
?>
        </textarea>
    <?php endif; ?>
</div>
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
function exportCSV() {
    let csv = document.getElementById("csvData").value;
    let blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    let link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "alumni_report.csv";
    link.click();
}
</script>