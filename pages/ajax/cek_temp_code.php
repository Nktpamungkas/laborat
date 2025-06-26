<?php
include "../../koneksi.php";
header('Content-Type: application/json');

$rcode = $_POST['rcode'] ?? '';
$response = ['needInput' => false, 'isDR' => false];

if (!$con || empty($rcode)) {
    echo json_encode($response);
    exit;
}

$isDR = substr($rcode, 0, 2) === 'DR';
$response['isDR'] = $isDR;

if ($isDR) {
    $sql = "SELECT temp_code, temp_code2 FROM tbl_matching WHERE no_resep = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $rcode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data && (empty($data['temp_code']) || empty($data['temp_code2']))) {
        $response['needInput'] = true;
    }

} else {
    $sql = "SELECT temp_code FROM tbl_matching WHERE no_resep = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $rcode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data && empty($data['temp_code'])) {
        $response['needInput'] = true;
    }
}

echo json_encode($response);
