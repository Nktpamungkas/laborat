<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

session_start();

$no_resep       = trim(htmlspecialchars($_POST['no_resep']));
$bottle_qty_1   = (int) $_POST['bottle_qty_1'];
$bottle_qty_2   = (int) $_POST['bottle_qty_2'];
$bottle_qty_test= (int) $_POST['bottle_qty_test'];
$temp_1         = trim(htmlspecialchars($_POST['temp_1'])); 
$temp_2         = trim(htmlspecialchars($_POST['temp_2']));
$username       = $_SESSION['userLAB'] ?? null;
$is_bonresep    = isset($_POST['is_bonresep']) ? (int)$_POST['is_bonresep'] : 0;


// Element balance
$element_id     = trim(htmlspecialchars($_POST['element'])) ?? '';
$kain_qty       = (int) $_POST['kain_qty'];
$kain_qty_test  = (int) $_POST['kain_qty_test'];

if (!$username) {
    echo json_encode([
        'success' => false,
        'message' => 'Session telah habis, silahkan login ulang terlebih dahulu!'
    ]);
    exit;
}

if ($bottle_qty_1 > 10 || $bottle_qty_2 > 10 || $bottle_qty_test > 10) {
    echo json_encode([
        'success' => false,
        'message' => 'Qty tidak boleh lebih dari 10!'
    ]);
    exit;
}

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
$dataEnd = mysqli_fetch_assoc($checkEnd);

if ($dataEnd['total'] > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ERROR - data suffix di END!'
    ]);
    exit;
}

$checkHold = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_preliminary_schedule WHERE no_resep = '$no_resep' AND status = 'hold'");
$dataHold = mysqli_fetch_assoc($checkHold);

if ($dataHold['total'] > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ERROR - data suffix di HOLD!'
    ]);
    exit;
}

$checkInProgress = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_preliminary_schedule WHERE no_resep = '$no_resep' AND status IN ('scheduled', 'in_progress_dispensing', 'in_progress_dyeing', 'stop_dyeing', 'in_progress_darkroom')");
$dataInProgress = mysqli_fetch_assoc($checkInProgress);

if ($dataInProgress['total'] > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'No. resep ini sedang dalam proses!'
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
        $updateQuery = $con->prepare("UPDATE tbl_preliminary_schedule SET is_old_cycle = 1 WHERE no_resep = ? AND status = 'repeat'");
        $updateQuery->bind_param("s", $no_resep);
        $updateQuery->execute();

        $updateQuery->close();
    }

    // Prepare statement untuk insert parent
    $stmtInsertParent = $con->prepare("
        INSERT INTO tbl_preliminary_schedule (no_resep, code, username, is_test, is_bonresep)
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmtInsertParent) {
        throw new Exception("Prepare insert parent failed: " . $con->error);
    }

    // Prepare statement untuk insert child
    $stmtInsertChild = $con->prepare("
        INSERT INTO tbl_preliminary_schedule_element (tbl_preliminary_schedule_id, element_id, qty)
        VALUES (?, ?, ?)
    ");

    if (!$stmtInsertChild) {
        throw new Exception("Prepare insert child failed: " . $con->error);
    }

    $insertedCount = 0;
    $no_resep_esc = $con->real_escape_string($no_resep);
    $temp_1_esc = $con->real_escape_string($temp_1);
    $username_esc = $con->real_escape_string($username);

    // Insert test bottles
    for ($i = 0; $i < $bottle_qty_test; $i++) {
        // is_test = 1
        $stmtInsertParent->bind_param("sssii", $no_resep_esc, $temp_1_esc, $username_esc, $is_test, $is_bonresep);
        $is_test = 1;

        if (!$stmtInsertParent->execute()) {
            throw new Exception("Insert schedule (test) failed: " . $stmtInsertParent->error);
        }

        $lastId = $stmtInsertParent->insert_id;
        $insertedCount++;

        // insert element
        $stmtInsertChild->bind_param("isi", $lastId, $element_id, $kain_qty_test);
        if (!$stmtInsertChild->execute()) {
            throw new Exception("Insert element (test) failed: " . $stmtInsertChild->error);
        }
    }

    // Insert normal bottles
    for ($i = 0; $i < $bottle_qty_1; $i++) {
        // is_test = 0
        $stmtInsertParent->bind_param("sssii", $no_resep_esc, $temp_1_esc, $username_esc, $is_test, $is_bonresep);
        $is_test = 0;

        if (!$stmtInsertParent->execute()) {
            throw new Exception("Insert schedule failed: " . $stmtInsertParent->error);
        }

        $lastId = $stmtInsertParent->insert_id;
        $insertedCount++;

        // insert element
        $stmtInsertChild->bind_param("isi", $lastId, $element_id, $kain_qty);
        if (!$stmtInsertChild->execute()) {
            throw new Exception("Insert element failed: " . $stmtInsertChild->error);
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