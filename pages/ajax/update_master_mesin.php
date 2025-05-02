<?php
include "../../koneksi.php";
header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $id = $_POST['id'];
//     $no_machine = $_POST['no_machine'];
//     $suhu = $_POST['suhu'] !== '' ? $_POST['suhu'] . '°C' : null;
//     $program = $_POST['program'];
//     $keterangan = $_POST['keterangan'];

//     $query = "UPDATE master_mesin SET no_machine=?, suhu=?, program=?, keterangan=? WHERE id=?";
//     $stmt = $con->prepare($query);
//     $stmt->bind_param("ssssi", $no_machine, $suhu, $program, $keterangan, $id);

//     if ($stmt->execute()) {
//         echo json_encode(["success" => true]);
//     } else {
//         echo json_encode(["error" => "Gagal update data"]);
//     }

//     $stmt->close();
//     $con->close();
// } else {
//     echo json_encode(["error" => "Terjadi kesalahan."]);
// }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $no_machine = trim($_POST['no_machine']);
    $suhu = $_POST['suhu'] !== '' ? $_POST['suhu'] . '°C' : null;
    $program = $_POST['program'];
    $keterangan = $_POST['keterangan'];

    // Cek apakah no_machine sudah digunakan oleh mesin lain
    $checkQuery = "SELECT id FROM master_mesin WHERE no_machine = ? AND id != ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bind_param("si", $no_machine, $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(["error" => "No Machine sudah digunakan."]);
    } else {
        // Lanjutkan update
        $query = "UPDATE master_mesin SET no_machine=?, suhu=?, program=?, keterangan=? WHERE id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssi", $no_machine, $suhu, $program, $keterangan, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Gagal update data"]);
        }

        $stmt->close();
    }

    $checkStmt->close();
    $con->close();
} else {
    echo json_encode(["error" => "Terjadi kesalahan."]);
}