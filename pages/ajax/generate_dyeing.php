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
    // 'scheduled',
    'in_progress_dispensing',
    'in_progress_dyeing',
    // 'in_progress_darkroom',
    // 'ok'
];

$statusList = "'" . implode("','", $statuses) . "'";

$sql = "SELECT tps.no_resep, tps.no_machine, tps.status, tps.dyeing_start, ms.`group`, ms.product_name, ms.waktu
        FROM tbl_preliminary_schedule tps
        LEFT JOIN master_suhu ms ON tps.code = ms.code
        WHERE tps.status IN ($statusList)
        ORDER BY tps.no_machine ASC, tps.id ASC";

$result = mysqli_query($con, $sql);

$data = [];
$maxPerMachine = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $machine = $row['no_machine'] ?: 'UNASSIGNED';
    $group = $row['group'];

    $data[$machine][] = [
        'no_resep' => $row['no_resep'],
        'status' => $row['status'],
        'group' => $group,
        'product_name' => $row['product_name'],
        'dyeing_start' => $row['dyeing_start'],
        'waktu' => $row['waktu']
    ];

    if (count($data[$machine]) > $maxPerMachine) {
        $maxPerMachine = count($data[$machine]);
    }
}

$tempListMap = [];

foreach ($data as $machine => $entries) {
    $groupSet = [];

    foreach ($entries as $entry) {
        if (!empty($entry['group'])) {
            $groupSet[$entry['group']] = true;
        }
    }

    $groupNames = array_keys($groupSet);
    $firstGroup = $groupNames[0] ?? null;

    if ($firstGroup) {
        $groupName = $firstGroup;

        // Dapatkan nilai dyeing
        $stmt = $con->prepare("SELECT dyeing FROM master_suhu WHERE `group` = ? LIMIT 1");
        $stmt->bind_param("s", $groupName);
        $stmt->execute();
        $stmt->bind_result($dyeingValue);
        $stmt->fetch();
        $stmt->close();

        $keterangan = '';
        if ($dyeingValue == "1") {
            $keterangan = 'POLY';
        } elseif ($dyeingValue == "2") {
            $keterangan = 'COTTON';
        }

        // Ambil informasi suhu
        $stmtTemp = $con->prepare("SELECT program, suhu, product_name FROM master_suhu WHERE `group` = ? LIMIT 1");
        $stmtTemp->bind_param("s", $groupName);
        $stmtTemp->execute();
        $result = $stmtTemp->get_result();
        $row = $result->fetch_assoc();
        $stmtTemp->close();

        if ($row) {
            $desc = '';
            // if ($keterangan) {
            //     $desc .= "[$keterangan] ";
            // }

            if ($row['program'] == 1) {
                $desc .= 'Constant ' . $row['suhu'];
            } elseif ($row['program'] == 2) {
                $desc .= 'Raising ' . $row['product_name'];
            } else {
                $desc .= 'Unknown';
            }

            $tempListMap[$machine] = [$desc]; // masih array agar JS tetap kompatibel
        }
    }
}

$response = [
    'data' => $data,
    'tempListMap' => $tempListMap,
    'maxPerMachine' => $maxPerMachine
];

header('Content-Type: application/json');
echo json_encode($response);