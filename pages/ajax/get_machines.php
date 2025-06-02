<?php
include '../../koneksi.php';
header('Content-Type: application/json');

$maxLimit = 24;

$sql = "SELECT no_machine FROM tbl_preliminary_schedule 
        WHERE no_machine IS NOT NULL AND no_machine != '' AND status = 'scheduled'";

$result = mysqli_query($con, $sql);

$machineCounts = [];

// Hitung jumlah data per mesin
while ($row = mysqli_fetch_assoc($result)) {
    $machine = $row['no_machine'];
    if (!isset($machineCounts[$machine])) {
        $machineCounts[$machine] = 0;
    }
    $machineCounts[$machine]++;
}

$validMachines = [];

// Filter mesin yang punya < 24 data
foreach ($machineCounts as $machine => $count) {
    if ($count < $maxLimit) {
        $validMachines[] = $machine;
    }
}

// Urutkan seperti A1, A2, ..., B1, B2
function natural_machine_sort($a, $b) {
    return strnatcasecmp($a, $b);
}
usort($validMachines, 'natural_machine_sort');

echo json_encode([
    'machines' => $validMachines,
    'machine_counts' => $machineCounts
]);

