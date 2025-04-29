<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();

$no_resep       = htmlspecialchars($_POST['no_resep']);
$bottle_qty_1   = (int) $_POST['bottle_qty_1'];
$bottle_qty_2   = (int) $_POST['bottle_qty_2'];
$temp_1         = htmlspecialchars($_POST['temp_1']); 
$temp_2         = htmlspecialchars($_POST['temp_2']);
$username       = $_SESSION['userLAB'];

// for ($i = 0; $i < $bottle_qty_1; $i++) {
//     mysqli_query($con, "INSERT INTO `tbl_preliminary_schedule` SET
//         `no_resep` = '$no_resep',
//         `code` = '$temp_1',
//         `username` = '$username'
//     ") or die(mysqli_error($con));
// }

// for ($i = 0; $i < $bottle_qty_2; $i++) {
//     mysqli_query($con, "INSERT INTO `tbl_preliminary_schedule` SET
//         `no_resep` = '$no_resep',
//         `code` = '$temp_2',
//         `username` = '$username'
//     ") or die(mysqli_error($con));
// }

// $response = array(
//     'session' => 'LIB_SUCCESS'
// );
// echo json_encode($response);

$success = false;  // Default success adalah false
$errorMessages = [];
$insertedCount = 0; // Menambahkan penghitung

$con->begin_transaction();

try {
    // Insert data untuk bottle_qty_1
    for ($i = 0; $i < $bottle_qty_1; $i++) {
        $query1 = $con->prepare("INSERT INTO tbl_preliminary_schedule (no_resep, code, username) VALUES (?, ?, ?)");
        if (!$query1) {
            $success = false;
            $errorMessages[] = "Prepare failed: " . $con->error;
            continue;
        }

        $query1->bind_param("sss", $no_resep, $temp_1, $username);
        if ($query1->execute()) {
            $insertedCount++;
        } else {
            $errorMessages[] = $query1->error;
        }
    }

    // Insert data untuk bottle_qty_2
    for ($i = 0; $i < $bottle_qty_2; $i++) {
        $query2 = $con->prepare("INSERT INTO tbl_preliminary_schedule (no_resep, code, username) VALUES (?, ?, ?)");
        if (!$query2) {
            $success = false;
            $errorMessages[] = "Prepare failed: " . $con->error;
            continue;
        }

        $query2->bind_param("sss", $no_resep, $temp_2, $username);
        if ($query2->execute()) {
            $insertedCount++;
        } else {
            $errorMessages[] = $query2->error;
        }
    }

    // Jika ada data yang berhasil diinsert
    if ($insertedCount > 0) {
        $con->commit();
        $success = true;
        echo json_encode([
            'success' => true,
            'message' => "Berhasil menyimpan $insertedCount data."
        ]);
    } else {
        $con->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Tidak ada data yang disimpan.',
            'errors' => $errorMessages
        ]);
    }
} catch (Exception $e) {
    $con->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server.',
        'error' => $e->getMessage()
    ]);
}