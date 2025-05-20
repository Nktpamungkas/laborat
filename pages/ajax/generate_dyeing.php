<?php
session_start();
include '../../koneksi.php';

// header('Content-Type: application/json');

// if (!isset($_POST['schedules'])) {
//     echo json_encode(['status' => 'error', 'message' => 'Schedules not provided']);
//     exit;
// }

// $schedules = json_decode($_POST['schedules'], true);
// $maxRows = 24;
// $scheduleChunks = [];

// foreach ($schedules as $groupName => $noReseps) {
//     $scheduleChunks[$groupName] = array_chunk($noReseps, $maxRows);
// }

// $dataTable = [];

// foreach ($scheduleChunks as $groupName => $chunks) {
//     $stmtInfo = $con->prepare("SELECT dyeing, product_name FROM master_suhu WHERE `group` = ?");
//     $stmtInfo->bind_param("s", $groupName);
//     $stmtInfo->execute();
//     $resultInfo = $stmtInfo->get_result();

//     $dyeingType = '';
//     $productNames = [];

//     while ($row = $resultInfo->fetch_assoc()) {
//         $productNames[] = $row['product_name'];
//         if ($row['dyeing'] == '1') $dyeingType = 'POLY';
//         if ($row['dyeing'] == '2') $dyeingType = 'COTTON';
//     }
//     $stmtInfo->close();

//     foreach ($chunks as $chunkIndex => $chunk) {
//         foreach ($chunk as $i => $no_resep) {
//             $stmt = $con->prepare("SELECT id, no_machine, status FROM tbl_preliminary_schedule 
//                                    WHERE no_resep = ?
//                                    AND status IN ('scheduled', 'in_progress_dispensing', 'in_progress_dyeing', 'in_progress_darkroom', 'ok')
//                                    ORDER BY id ASC LIMIT 1");
//             $stmt->bind_param("s", $no_resep);
//             $stmt->execute();
//             $stmt->bind_result($id, $no_machine, $status);
//             $stmt->fetch();
//             $stmt->close();

//             $dataTable[$i][$groupName][$chunkIndex] = [
//                 'no_resep' => $no_resep,
//                 'id' => $id,
//                 'no_machine' => $no_machine ?: '-',
//                 'status' => $status ?: '-',
//                 'product_names' => implode('; ', $productNames),
//                 'dyeing' => $dyeingType
//             ];
//         }
//     }
// }

// echo json_encode(['status' => 'success', 'data' => $dataTable, 'meta' => [
//     'groups' => $schedules,
//     'maxRows' => $maxRows
// ]]);

$statuses = [
    'scheduled',
    'in_progress_dispensing',
    'in_progress_dyeing',
    'in_progress_darkroom',
    'ok'
];

$statusList = "'" . implode("','", $statuses) . "'";

$sql = "SELECT tps.no_resep, tps.no_machine, tps.status, ms.`group`, ms.product_name
        FROM tbl_preliminary_schedule tps
        LEFT JOIN master_suhu ms ON tps.code = ms.code
        WHERE tps.status IN ($statusList)
        ORDER BY tps.no_machine, tps.id ASC";

$result = mysqli_query($con, $sql);

$data = [];
$maxPerMachine = 0;

// Kumpulan tempList untuk tiap mesin
$tempListMap = [];

while ($row = mysqli_fetch_assoc($result)) {
    $machine = $row['no_machine'] ?: 'UNASSIGNED';
    $data[$machine][] = [
        'no_resep' => $row['no_resep'],
        'status' => $row['status'],
        'group' => $row['group'],
        'product_name' => $row['product_name']
    ];
    if (count($data[$machine]) > $maxPerMachine) {
        $maxPerMachine = count($data[$machine]);
    }

    // Kumpulkan product_name berdasarkan mesin
    if (!isset($tempListMap[$machine])) {
        $tempListMap[$machine] = [];
    }
    if ($row['product_name'] && !in_array($row['product_name'], $tempListMap[$machine])) {
        $tempListMap[$machine][] = $row['product_name'];
    }
}

$response = [
    'data' => $data,
    'tempListMap' => $tempListMap,
    'maxPerMachine' => $maxPerMachine
];

header('Content-Type: application/json');
echo json_encode($response);

