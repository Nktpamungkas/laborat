<?php
include "koneksi.php";

$sql = "
    SELECT DISTINCT no_machine 
    FROM tbl_preliminary_schedule 
    WHERE status IN ('scheduled', 'in_progress_dispensing', 'in_progress_dyeing')
      AND no_machine IS NOT NULL
";

$result = mysqli_query($con, $sql);

$machines = [];
while ($row = mysqli_fetch_assoc($result)) {
    $machines[] = $row['no_machine'];
}

header('Content-Type: application/json');
echo json_encode($machines);
?>
