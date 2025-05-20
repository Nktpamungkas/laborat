<?php
session_start();
include '../../koneksi.php';

if (isset($_POST['schedules'])) {
    $schedules = json_decode($_POST['schedules'], true);
    $maxRows = 24;
    $scheduleChunks = [];

    foreach ($schedules as $groupName => $noReseps) {
        $scheduleChunks[$groupName] = array_chunk($noReseps, $maxRows);
    }

    $scheduleData = [];
    $columns = [];

    foreach ($scheduleChunks as $groupName => $chunks) {
 
        foreach ($chunks as $chunkIndex => $chunk) {
            // Ambil mesin dari salah satu resep (anggap semua di chunk sama)
            $machineName = '-';
            foreach ($chunk as $no_resep) {
                $stmt = $con->prepare("SELECT no_machine FROM tbl_preliminary_schedule 
                                       WHERE no_resep = ?
                                       AND status IN ('scheduled', 'in_progress_dispensing', 'in_progress_dyeing', 'in_progress_darkroom', 'ok')
                                       ORDER BY id ASC LIMIT 1");
                $stmt->bind_param("s", $no_resep);
                $stmt->execute();
                $stmt->bind_result($no_machine);
                if ($stmt->fetch()) {
                    $machineName = $no_machine ?: '-';
                }
                $stmt->close();
                break;
            }

            $columns[] = [
                'group' => $groupName,
                'chunk_index' => $chunkIndex,
                'machine' => $machineName
            ];

            foreach ($chunk as $rowIndex => $no_resep) {
                $stmt = $con->prepare("SELECT id, no_machine, status FROM tbl_preliminary_schedule 
                                       WHERE no_resep = ?
                                       AND status IN ('scheduled', 'in_progress_dispensing', 'in_progress_dyeing', 'in_progress_darkroom', 'ok')
                                       ORDER BY id ASC LIMIT 1");
                $stmt->bind_param("s", $no_resep);
                $stmt->execute();
                $stmt->bind_result($id, $no_machine, $status);
                if ($stmt->fetch()) {
                    if (!isset($scheduleData[$rowIndex])) $scheduleData[$rowIndex] = [];
                    if (!isset($scheduleData[$rowIndex][$groupName])) $scheduleData[$rowIndex][$groupName] = [];

                    $scheduleData[$rowIndex][$groupName][$chunkIndex] = [
                        'no_resep' => $no_resep,
                        'id' => $id,
                        'no_machine' => $no_machine ?: '-',
                        'status' => $status ?: '-'
                    ];
                }
                $stmt->close();
            }
        }
    }

    // Ambil info produk per group
    $groupInfo = [];
    foreach (array_keys($schedules) as $groupName) {
        $stmt = $con->prepare("SELECT GROUP_CONCAT(DISTINCT product_name SEPARATOR ' ; ') AS products 
                               FROM master_suhu WHERE `group` = ?");
        $stmt->bind_param("s", $groupName);
        $stmt->execute();
        $stmt->bind_result($products);
        $stmt->fetch();
        $groupInfo[$groupName] = $products;
        $stmt->close();
    }

    header('Content-Type: application/json');
    echo json_encode([
        'columns' => $columns,
        'groupInfo' => $groupInfo,
        'scheduleData' => $scheduleData,
        'maxRows' => $maxRows
    ]);
} else {
    echo json_encode([
        'columns' => [],
        'groupInfo' => [],
        'scheduleData' => [],
        'maxRows' => 24
    ]);
}
?>
