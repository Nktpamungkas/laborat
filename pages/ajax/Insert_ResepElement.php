<?php
header('Content-Type: application/json');

// koneksi ke DB
include "../../koneksi.php"; 

// Ambil POST data
$no_resep   = $_POST['no_resep'] ?? '';
$element_code = $_POST['element_code'] ?? '';

// Validasi basic
if ($no_resep === '' || $element_code === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'no_resep dan element_id wajib diisi'
    ]);
    exit;
}

$sqlGetId = "SELECT NUMBERID FROM balance WHERE ELEMENTSCODE = ?";
$stmt = mysqli_prepare($con, $sqlGetId);
mysqli_stmt_bind_param($stmt, "s", $element_code);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$row) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Element Code tidak ditemukan di tabel balance.'
    ]);
    exit;
}

$element_id = $row['NUMBERID'];


// Cek balance: pastikan record balance ada dan qty (BASEPRIMARYQUANTITYUNIT) > 0
$checkBalanceQuery = "SELECT BASEPRIMARYQUANTITYUNIT FROM balance WHERE NUMBERID = ? LIMIT 1";

$stmt = mysqli_prepare($con, $checkBalanceQuery);
mysqli_stmt_bind_param($stmt, "s", $element_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$balanceRow = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$balanceRow) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Element tidak ditemukan di tabel balance.'
    ]);
    exit;
}

$qty = floatval($balanceRow['BASEPRIMARYQUANTITYUNIT']);
if ($qty <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Balance untuk element ini tidak mencukupi (qty <= 0).'
    ]);
    exit;
}

// 1. Cek apakah data sudah ada
$checkQuery = "SELECT COUNT(*) AS total FROM tbl_resep_element WHERE no_resep = ?";
$stmt = mysqli_prepare($con, $checkQuery);
mysqli_stmt_bind_param($stmt, "s", $no_resep);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($row['total'] > 0) {
    // 2. UPDATE jika sudah ada
    $updateQuery = "UPDATE tbl_resep_element 
        SET element_id = ?, element_code = ?
        WHERE no_resep = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sss", $element_id, $element_code, $no_resep);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'status' => 'success',
            'mode' => 'update',
            'message' => 'Data berhasil diupdate.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_stmt_error($stmt)
        ]);
    }

    mysqli_stmt_close($stmt);

} else {
    // 3. INSERT jika belum ada
    $insertQuery = "INSERT INTO tbl_resep_element (no_resep, element_id, element_code) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($stmt, "sss", $no_resep, $element_id, $element_code);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'status' => 'success',
            'mode' => 'insert',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_stmt_error($stmt)
        ]);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($con);
