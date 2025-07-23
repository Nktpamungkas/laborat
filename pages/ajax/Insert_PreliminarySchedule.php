<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
include "../../includes/log_helper.php";
session_start();

$no_resep       = trim(htmlspecialchars($_POST['no_resep']));
$bottle_qty_1   = (int) $_POST['bottle_qty_1'];
$bottle_qty_2   = (int) $_POST['bottle_qty_2'];
$temp_1         = trim(htmlspecialchars($_POST['temp_1'])); 
$temp_2         = trim(htmlspecialchars($_POST['temp_2']));
$username       = $_SESSION['userLAB'];

$checkReady = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_preliminary_schedule WHERE no_resep = '$no_resep' AND status = 'ready'");
$dataReady = mysqli_fetch_assoc($checkReady);

if ($dataReady['total'] > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Data sudah diinput!'
    ]);
    exit;
}

$checkEnd = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_preliminary_schedule WHERE no_resep = '$no_resep' AND status = 'end'");
$dataReady = mysqli_fetch_assoc($checkEnd);

if ($dataReady['total'] > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Maaf tidak bisa input no. resep ini!'
    ]);
    exit;
}

$success = false;
$errorMessages = [];
$insertedCount = 0; // Menambahkan penghitung

$con->begin_transaction();

try {
    $checkQuery = $con->prepare("SELECT COUNT(*) FROM tbl_preliminary_schedule WHERE no_resep = ? AND status = 'repeat'");
    $checkQuery->bind_param("s", $no_resep);
    $checkQuery->execute();
    $checkQuery->bind_result($countOldDataRepeat);
    $checkQuery->fetch();
    $checkQuery->close();

    if ($countOldDataRepeat > 0) {
        // $deleteQuery = $con->prepare("DELETE FROM tbl_preliminary_schedule WHERE no_resep = ?");
        // $deleteQuery->bind_param("s", $no_resep);
        // $deleteQuery->execute();

        // $deleteQuery->close();

        $updateQuery = $con->prepare("UPDATE tbl_preliminary_schedule SET is_old_cycle = 1 WHERE no_resep = ? AND status = 'repeat'");
        $updateQuery->bind_param("s", $no_resep);
        $updateQuery->execute();

        $updateQuery->close();
    }

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

        // logResepHistory($no_resep, 1, 'ready', 'Preliminary scheduled', $insertedCount);

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