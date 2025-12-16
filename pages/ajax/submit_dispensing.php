<?php
session_start();
include '../../koneksi.php';
include '../../includes/insert_balance_transaction_helper.php';
include '../../includes/check_stock_balance_before_dispensing.php';
header('Content-Type: application/json');

$response = ['success' => false];

// Set is_scheduling = 0
$isScheduling = "UPDATE tbl_is_scheduling SET is_scheduling = 0";
mysqli_query($con, $isScheduling);

// Ambil data dari request
$data = json_decode(file_get_contents('php://input'), true);
$userScheduled = $_SESSION['userLAB'] ?? '';

if (!$userScheduled) {
    echo json_encode([
        'success' => false,
        'message' => 'Session telah habis, silahkan login ulang terlebih dahulu!'
    ]);
    exit;
}

if (isset($data['assignments']) && is_array($data['assignments'])) {
    $submitted_ids = [];

    $check = checkStockAvailability($con, $data['assignments']);

    if (!$check['ok']) {
        
        $response = [
            'success' => false,
            'message' => $check['message'],
            'detail' => $check['failed']
        ];
        echo json_encode($response);
        return;
    }
    
    foreach ($data['assignments'] as $item) {
        $id = intval($item['id_schedule']);
        $machine = trim($item['machine']);
        $group = trim($item['group']);

        if ($id && $machine) {
            $stmt = $con->prepare("UPDATE tbl_preliminary_schedule 
                                   SET no_machine = ?, id_group = ?, status = 'scheduled' 
                                   WHERE id = ?");
            $stmt->bind_param("ssi", $machine, $group, $id);
            $stmt->execute();
            $stmt->close();

            $submitted_ids[] = $id;
        }
    }

    // 1️⃣ Ambil mesin yang sibuk SEBELUM pemrosesan input
    $busyStatuses = ['scheduled', 'in_progress_dispensing', 'in_progress_dyeing', 'stop_dyeing'];
    $placeholders = implode(',', array_fill(0, count($busyStatuses), '?'));
    $types = str_repeat('s', count($busyStatuses));

    $sqlBusy = "SELECT DISTINCT no_machine FROM tbl_preliminary_schedule WHERE status IN ($placeholders)";
    $stmtBusy = $con->prepare($sqlBusy);
    $stmtBusy->bind_param($types, ...$busyStatuses);
    $stmtBusy->execute();
    $resultBusy = $stmtBusy->get_result();

    $mesin_sibuk_sebelumnya = [];
    while ($row = $resultBusy->fetch_assoc()) {
        $mesin_sibuk_sebelumnya[] = $row['no_machine'];
    }
    $stmtBusy->close();


    // 2️⃣ Loop inputan baru
    foreach ($data['assignments'] as $item) {
        $id = intval($item['id_schedule']);
        $machine = trim($item['machine']);
        $group = trim($item['group']);

        if ($id && $machine) {
            // Update status jadi scheduled
            $stmt = $con->prepare("UPDATE tbl_preliminary_schedule 
                                   SET no_machine = ?, id_group = ?, status = 'scheduled', user_scheduled = ?
                                   WHERE id = ?");
            $stmt->bind_param("sssi", $machine, $group, $userScheduled, $id);
            $stmt->execute();
            $stmt->close();
            $submitted_ids[] = $id;

            // PANGGIL function insert balance
            insertBalanceTransaction($con, $id);

            // ❗ Cek apakah mesin sudah sibuk SEBELUMNYA
            if (in_array($machine, $mesin_sibuk_sebelumnya)) {
                $stmt = $con->prepare("UPDATE tbl_preliminary_schedule SET is_old_data = 1 WHERE id = ? AND is_bonresep = 0");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // // 🔁 Tandai data yang tidak dipilih sebagai is_old_data = 1
    if (isset($data['all_ids']) && is_array($data['all_ids'])) {
        $all_ids = array_map('intval', $data['all_ids']);
        $not_selected_ids = array_diff($all_ids, $submitted_ids);

        if (!empty($not_selected_ids)) {
            $placeholders = implode(',', array_fill(0, count($not_selected_ids), '?'));
            $types = str_repeat('i', count($not_selected_ids));

            $sql = "UPDATE tbl_preliminary_schedule SET is_old_data = 1 WHERE id IN ($placeholders) AND is_bonresep = 0";
            $stmt = $con->prepare($sql);
            $stmt->bind_param($types, ...array_values($not_selected_ids));
            $stmt->execute();
            $stmt->close();
        }
    }

    $response['success'] = true;
} else {
    $response['message'] = 'Data tidak valid.';
}

echo json_encode($response);