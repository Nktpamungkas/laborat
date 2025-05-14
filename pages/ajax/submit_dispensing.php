<?php
session_start();
include '../../koneksi.php';

$isScheduling = "UPDATE tbl_is_scheduling SET is_scheduling = 0";
mysqli_query($con, $isScheduling);

$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

if (isset($data['assignments']) && is_array($data['assignments'])) {
    // foreach ($data['assignments'] as $item) {
    //     $id = intval($item['id_schedule']);
    //     $machine = trim($item['machine']);

    //     if ($id && $machine) {
    //         $stmt = $con->prepare("UPDATE tbl_preliminary_schedule 
    //                                SET no_machine = ?, status = 'in_progress_dispense' 
    //                                WHERE id = ?");
    //         $stmt->bind_param("si", $machine, $id);
    //         $stmt->execute();
    //         $stmt->close();
    //     }
    // }

    foreach ($data['assignments'] as $item) {
        $id = intval($item['id_schedule']);
        $machine = trim($item['machine']);

        if ($id && $machine) {
            $stmt = $con->prepare("UPDATE tbl_preliminary_schedule 
                                SET no_machine = ?, status = 'scheduled' 
                                WHERE id = ?");
            $stmt->bind_param("si", $machine, $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    $response['success'] = true;
} else {
    $response['message'] = 'Data tidak valid.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
