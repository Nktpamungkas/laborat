<?php
session_start();
include '../../koneksi.php';

// Ambil data utama (tidak termasuk old_data)
$statuses = [
    'scheduled',
    'in_progress_dispensing',
    'in_progress_dyeing'
];

$statusList = "'" . implode("','", $statuses) . "'";

$sql = "SELECT tps.no_resep, tps.no_machine, tps.status, tps.dyeing_start, ms.`group`, ms.product_name, ms.waktu
        FROM tbl_preliminary_schedule tps
        LEFT JOIN master_suhu ms ON tps.code = ms.code
        LEFT JOIN tbl_matching ON 
            CASE WHEN LEFT(tps.no_resep, 2) = 'DR' 
                THEN LEFT(tps.no_resep, LENGTH(tps.no_resep) - 2)
                ELSE tps.no_resep
            END = tbl_matching.no_resep
        WHERE tps.status IN ($statusList) AND tps.is_old_data = 0 AND is_old_cycle = 0
        ORDER BY
            tps.no_resep,
            CASE 
                WHEN tbl_matching.jenis_matching IN ('LD', 'LD NOW') THEN 1
                WHEN tbl_matching.jenis_matching IN ('Matching Ulang', 'Matching Ulang NOW', 'Matching Development', 'Perbaikan' , 'Perbaikan NOW') THEN 2
                ELSE 3
            END,
            CASE 
                WHEN tps.order_index > 0 THEN 0 
                ELSE 1 
            END, 
            tps.order_index ASC,
            ms.suhu DESC, 
            ms.waktu DESC, 
            tps.no_resep ASC";

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

// Ambil old data (is_old_data = 1)
$oldDataList = [];
$oldQuery = "SELECT tps.no_resep, tps.no_machine, tps.status, tps.dyeing_start, ms.`group`, ms.product_name, ms.waktu
             FROM tbl_preliminary_schedule tps
             LEFT JOIN master_suhu ms ON tps.code = ms.code
             WHERE tps.is_old_data = 1 AND tps.status IN ($statusList) AND is_old_cycle = 0";
$oldResult = mysqli_query($con, $oldQuery);

while ($row = mysqli_fetch_assoc($oldResult)) {
    $oldDataList[] = $row;
}

// Mesin yang punya data utama
$machinesWithMainData = array_keys($data);

// Pindahkan semua old data ke mesin yang benar-benar kosong (tidak ada data utama)
$remainingOldData = [];

foreach ($oldDataList as $old) {
    $machine = $old['no_machine'] ?: 'UNASSIGNED';

    if (!in_array($machine, $machinesWithMainData) || count($data[$machine]) === 0) {
        if (!isset($data[$machine])) $data[$machine] = [];

        $data[$machine][] = [
            'no_resep' => $old['no_resep'],
            'status' => $old['status'],
            'group' => $old['group'],
            'product_name' => $old['product_name'],
            'dyeing_start' => $old['dyeing_start'],
            'waktu' => $old['waktu'],
            'justMoved' => true
        ];
    } else {
        $remainingOldData[] = $old;
    }
}

// Hitung ulang maxPerMachine setelah penambahan old data
foreach ($data as $rows) {
    if (count($rows) > $maxPerMachine) {
        $maxPerMachine = count($rows);
    }
}

// Buat tempListMap dari data utama
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

        // Ambil info dyeing
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
            if ($row['program'] == 1) {
                $desc .= 'Constant ' . $row['suhu'];
            } elseif ($row['program'] == 2) {
                $desc .= 'Raising ' . $row['product_name'];
            } else {
                $desc .= 'Unknown';
            }

            $tempListMap[$machine] = [$desc];
        }
    }
}

$response = [
    'data' => $data,
    'tempListMap' => $tempListMap,
    'maxPerMachine' => $maxPerMachine,
    'oldDataList' => $remainingOldData
];

header('Content-Type: application/json');
echo json_encode($response);