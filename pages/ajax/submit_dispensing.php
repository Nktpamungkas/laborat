<?php
session_start();
include '../../koneksi.php';
include '../../includes/insert_balance_transaction_helper.php';
include '../../includes/check_stock_balance_before_dispensing.php';
header('Content-Type: application/json');

$userScheduled = $_SESSION['userLAB'] ?? '';
if (!$userScheduled) {
    echo json_encode([
        'success' => false,
        'message' => 'Session telah habis, silahkan login ulang terlebih dahulu!'
    ]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$assignments = (isset($data['assignments']) && is_array($data['assignments'])) ? $data['assignments'] : [];
$all_ids_raw = (isset($data['all_ids']) && is_array($data['all_ids'])) ? $data['all_ids'] : [];

$all_ids = array_values(array_unique(array_filter(array_map('intval', $all_ids_raw))));
$submitted_ids = [];

if (empty($all_ids) && empty($assignments)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
    exit;
}

mysqli_query($con, "UPDATE tbl_is_scheduling SET is_scheduling = 0");

try {
    // ===== 1) Ambil mesin sibuk SEBELUM update apa pun =====
    $busyStatuses = ['scheduled', 'in_progress_dispensing', 'in_progress_dyeing', 'stop_dyeing'];
    $ph = implode(',', array_fill(0, count($busyStatuses), '?'));
    $types = str_repeat('s', count($busyStatuses));

    $sqlBusy = "
        SELECT DISTINCT no_machine
        FROM tbl_preliminary_schedule
        WHERE status IN ($ph)
          AND no_machine IS NOT NULL
          AND no_machine <> ''
    ";
    $stmtBusy = $con->prepare($sqlBusy);
    if (!$stmtBusy) throw new Exception("Prepare busy failed: " . $con->error);

    $stmtBusy->bind_param($types, ...$busyStatuses);
    $stmtBusy->execute();
    $resBusy = $stmtBusy->get_result();

    $mesin_sibuk_sebelumnya = [];
    while ($row = $resBusy->fetch_assoc()) {
        $mesin_sibuk_sebelumnya[] = $row['no_machine'];
    }
    $stmtBusy->close();

    // ===== 2) Stock check (hanya yang memang punya mesin valid) =====
    $assignmentsForStock = array_values(array_filter($assignments, function($it){
        $m = strtoupper(trim($it['machine'] ?? ''));
        return !empty($it['id_schedule']) && $m !== '' && $m !== 'BONRESEP';
    }));

    $check = checkStockAvailability($con, $assignmentsForStock);
    if (!$check['ok']) {
        echo json_encode([
            'success' => false,
            'message' => $check['message'],
            'detail'  => $check['failed']
        ]);
        exit;
    }

    // ===== 3) Base update: semua all_ids jadi scheduled (BON ikut) =====
    if (!empty($all_ids)) {
        $phIds = implode(',', array_fill(0, count($all_ids), '?'));
        $typesIds = str_repeat('i', count($all_ids));

        $sqlBase = "
            UPDATE tbl_preliminary_schedule
            SET status = 'scheduled',
                user_scheduled = ?,
                pass_dispensing = 0
            WHERE id IN ($phIds)
        ";
        $stmtBase = $con->prepare($sqlBase);
        if (!$stmtBase) throw new Exception("Prepare base update failed: " . $con->error);

        $stmtBase->bind_param('s' . $typesIds, $userScheduled, ...$all_ids);
        $stmtBase->execute();
        $stmtBase->close();
    }

    // ===== 4) Update assignment mesin (non-BON) + insertBalance + is_old_data =====
    foreach ($assignments as $item) {
        $id = intval($item['id_schedule'] ?? 0);
        $machine = strtoupper(trim($item['machine'] ?? ''));
        $group = trim($item['group'] ?? '');

        if (!$id) continue;

        // Skip BONRESEP / kosong => biarkan scheduled tapi mesin NULL
        if ($machine === '' || $machine === 'BONRESEP') {
            continue;
        }

        $stmt = $con->prepare("
            UPDATE tbl_preliminary_schedule
            SET no_machine = ?,
                id_group = ?,
                status = 'scheduled',
                user_scheduled = ?
            WHERE id = ?
        ");
        if (!$stmt) throw new Exception("Prepare update assignment failed: " . $con->error);

        $stmt->bind_param("sssi", $machine, $group, $userScheduled, $id);
        $stmt->execute();
        $stmt->close();

        $submitted_ids[] = $id;

        insertBalanceTransaction($con, $id);

        // tandai is_old_data kalau mesin sudah sibuk sebelumnya (exclude bon)
        if (in_array($machine, $mesin_sibuk_sebelumnya, true)) {
            $stmtOld = $con->prepare("UPDATE tbl_preliminary_schedule SET is_old_data = 1 WHERE id = ? AND is_bonresep = 0");
            $stmtOld->bind_param("i", $id);
            $stmtOld->execute();
            $stmtOld->close();
        }
    }

    // ===== 5) Tandai data yg tidak dipilih (non-bon) sebagai old =====
    if (!empty($all_ids)) {
        $submitted_ids = array_values(array_unique($submitted_ids));
        $not_selected_ids = array_values(array_diff($all_ids, $submitted_ids));

        if (!empty($not_selected_ids)) {
            $phNot = implode(',', array_fill(0, count($not_selected_ids), '?'));
            $typesNot = str_repeat('i', count($not_selected_ids));

            $sqlNot = "UPDATE tbl_preliminary_schedule SET is_old_data = 1 WHERE id IN ($phNot) AND is_bonresep = 0";
            $stmtNot = $con->prepare($sqlNot);
            $stmtNot->bind_param($typesNot, ...$not_selected_ids);
            $stmtNot->execute();
            $stmtNot->close();
        }
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}