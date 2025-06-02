<?php
session_start();
include '../../koneksi.php';

$statuses = [
    'scheduled',
    // 'in_progress_dispensing',
    // 'in_progress_dyeing',
    // 'in_progress_darkroom',
    // 'ok'
];

$statusList = "'" . implode("','", $statuses) . "'";

$sql = "SELECT tps.no_resep, tps.no_machine, tps.status, ms.`group`, ms.product_name
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
        'product_name' => $row['product_name']
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