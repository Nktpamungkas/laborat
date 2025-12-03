<?php
ini_set('display_errors', 0);
error_reporting(0);
include __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

$order = isset($_POST['order']) ? strtoupper(trim($_POST['order'])) : '';

if ($order === '') {
    echo json_encode([
        'success' => false,
        'message' => 'No Order kosong.',
    ]);
    exit;
}

try {
    // Ambil daftar NO_DEMAND untuk No Order tersebut (Development)
    $sqlDemand = "
        SELECT DISTINCT TRIM(NO_DEMAND) AS NO_DEMAND
        FROM ITXVIEW_KK_TAS
        WHERE PROJECTCODE LIKE ?
        ORDER BY NO_DEMAND
    ";

    $stmtDemand = db2_prepare($conn1, $sqlDemand);
    if (!$stmtDemand || !db2_execute($stmtDemand, ['%' . $order . '%'])) {
        throw new Exception('Gagal mengambil daftar Production Demand.');
    }

    $demands = [];
    while ($row = db2_fetch_assoc($stmtDemand)) {
        $code = trim($row['NO_DEMAND']);
        if ($code !== '') {
            $demands[] = [
                'value' => $code,
                'text'  => $code,
            ];
        }
    }

    // Ambil 1 baris sebagai referensi buyer/langganan (seperti $dt_kk_tas lama tanpa filter demand)
    $buyer = '';
    $sqlBuyer = "
        SELECT TRIM(BUYER) AS BUYER
        FROM ITXVIEW_KK_TAS
        WHERE PROJECTCODE LIKE ?
        FETCH FIRST 1 ROW ONLY
    ";
    $stmtBuyer = db2_prepare($conn1, $sqlBuyer);
    if ($stmtBuyer && db2_execute($stmtBuyer, ['%' . $order . '%'])) {
        $rowBuyer = db2_fetch_assoc($stmtBuyer);
        if ($rowBuyer && !empty($rowBuyer['BUYER'])) {
            $buyer = trim($rowBuyer['BUYER']);
        }
    }

    echo json_encode([
        'success' => true,
        'order'   => $order,
        'buyer'   => $buyer,
        'demands' => $demands,
    ], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}

