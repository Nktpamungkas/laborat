<?php
session_start();
include '../../koneksi.php';

$allMachines = [];
$sqlMachines = "SELECT no_machine FROM master_mesin";
$resMachines = mysqli_query($con, $sqlMachines);
while ($row = mysqli_fetch_assoc($resMachines)) {
    $allMachines[] = $row['no_machine'];
}

// Ambil data utama (tidak termasuk old_data)
$statuses = [
    'scheduled',
    'in_progress_dispensing',
    'in_progress_dyeing',
    // 'stop_dyeing'
];

$statusList = "'" . implode("','", $statuses) . "'";

$sql = "SELECT tps.no_resep, tps.no_machine, tps.status, tps.dyeing_start,tps.is_test, ms.`group`, ms.product_name, ms.waktu
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
        'waktu' => $row['waktu'],
        'is_test' => $row['is_test']
    ];

    if (count($data[$machine]) > $maxPerMachine) {
        $maxPerMachine = count($data[$machine]);
    }
}

// Buat array urut dengan maksimal 24
$maxRow = 24;
foreach ($data as $machine => $entries) {
    // Tambahkan data kosong jika kurang dari 24
    while (count($entries) < $maxRow) {
        $entries[] = null;
    }
    $data[$machine] = $entries;
}

// Ambil old data (is_old_data = 1)
$oldDataList = [];
$oldQuery = "SELECT tps.no_resep, tps.no_machine, tps.status, tps.dyeing_start, tps.is_test, ms.`group`, ms.product_name, ms.waktu
             FROM tbl_preliminary_schedule tps
             LEFT JOIN master_suhu ms ON tps.code = ms.code
             WHERE tps.is_old_data = 1 AND tps.status IN ($statusList) AND is_old_cycle = 0
             ORDER BY tps.no_resep";
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

        // Tambahkan old data pada mesin yang kosong
        while (count($data[$machine]) < $maxRow) {
            $data[$machine][] = null;
        }

        $data[$machine][] = [
            'no_resep' => $old['no_resep'],
            'status' => $old['status'],
            'group' => $old['group'],
            'product_name' => $old['product_name'],
            'dyeing_start' => $old['dyeing_start'],
            'waktu' => $old['waktu'],
            'justMoved' => true,
            'is_test' => $old['is_test']
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

$oldMachineMap = [];

foreach ($oldDataList as $old) {
    $machine = $old['no_machine'] ?: 'UNASSIGNED';

    if (!isset($oldMachineMap[$machine])) {
        $oldMachineMap[$machine] = [];
    }

    $oldMachineMap[$machine][] = $old;
}

// Buat tempListMapNext untuk old data (Next Cycle)
$tempListMapNext = [];

foreach ($oldMachineMap as $machine => $oldEntries) {
    $groupSet = [];

    foreach ($oldEntries as $entry) {
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

            if (!isset($tempListMapNext[$machine]) || !in_array($desc, $tempListMapNext[$machine])) {
                $tempListMapNext[$machine][] = $desc;
            }
        }
    }
}

$response = [
    'data' => $data,
    'tempListMap' => $tempListMap,
    'tempListMapNext' => $tempListMapNext,
    'maxPerMachine' => $maxPerMachine,
    'oldDataList' => $remainingOldData,
    'allMachines'   => $allMachines
];

header('Content-Type: application/json');
echo json_encode($response);