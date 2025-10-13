<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['no_resep'])) {
    $no_resep = mysqli_real_escape_string($con, $_POST['no_resep']);
    $ip_num = $_SERVER['REMOTE_ADDR'];

    // === PANGGIL API PRINT ===
    $url = "http://10.0.0.121:8080/api/v1/document/create";
    $payload = json_encode([
        "doc_number" => $no_resep,
        "ip_address" => '10.0.6.233'
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    // === LOG HASIL PRINTING ===
    if ($error) {
        $logMessage = "CURL Error: " . addslashes($error);
        $logSuccess = 0;
    } else {
        $result = json_decode($response, true);
        $logMessage = addslashes($result['message'] ?? 'Unknown response');
        $logSuccess = isset($result['success']) && $result['success'] ? 1 : 0;
    }

    mysqli_query($con, "INSERT INTO log_printing SET
        no_resep = '$no_resep',
        ip_address = '$ip_num',
        success = '$logSuccess',
        message = '$logMessage',
        response_raw = '" . addslashes($response) . "',
        created_at = NOW(),
        created_by = '$_SESSION[userLAB]'");

    // === FEEDBACK KE USER ===
    if ($logSuccess) {
        echo "<script>alert('Data tersimpan & print berhasil dikirim!');window.location.href='?p=form-matching-detail&noresep=$no_resep';</script>";
    } else {
        echo "<script>alert('Data tersimpan, tapi print gagal: " . addslashes($logMessage) . "');window.location.href='?p=form-matching-detail&noresep=$no_resep';</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print RFID</title>
</head>
<body>
    <h2>Print RFID</h2>
    <form method="post">
        <label>No Resep:</label>
        <input type="text" name="no_resep" required autofocus>
        <button type="submit">Print</button>
    </form>
</body>
</html>