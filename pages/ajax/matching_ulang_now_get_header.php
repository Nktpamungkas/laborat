<?php
ini_set('display_errors', 0);
error_reporting(0);
include __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

// Header khusus untuk Matching Ulang NOW

$reqNo = isset($_POST['req_no']) ? strtoupper(trim($_POST['req_no'])) : '';

if ($reqNo === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Req No kosong.'
    ]);
    exit;
}

try {
    $sqlLangganan = "
        SELECT 
            TRIM(s.CODE) AS PROJECTCODE, 
            TRIM(ip.LANGGANAN) AS LANGGANAN, 
            TRIM(ip.BUYER) AS BUYER
        FROM SALESORDER s 
        LEFT JOIN ITXVIEW_PELANGGAN ip 
            ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE 
           AND ip.CODE = s.CODE 
        WHERE s.CODE LIKE ?
        FETCH FIRST 1 ROW ONLY
    ";

    $stmtLangganan = db2_prepare($conn1, $sqlLangganan);
    if (!$stmtLangganan || !db2_execute($stmtLangganan, ['%' . $reqNo . '%'])) {
        throw new Exception('Gagal mengambil data langganan (Matching Ulang NOW).');
    }

    $dt_langganan = db2_fetch_assoc($stmtLangganan);
    if (!$dt_langganan) {
        echo json_encode([
            'success' => false,
            'message' => 'Data Matching Ulang NOW tidak ditemukan untuk Req No tersebut.'
        ]);
        exit;
    }

    $projectCode   = trim($dt_langganan['PROJECTCODE']);
    $langgananText = trim($dt_langganan['LANGGANAN']) . '/' . trim($dt_langganan['BUYER']);

    // LIST NO. ITEM (mengikuti NowForm lama: ITXVIEWBONORDER)
    $sqlItems = "
        SELECT
            i.ORDERLINE AS DLVSALESORDERLINEORDERLINE,
            i.ITEMTYPEAFICODE,
            i.WARNA,
            TRIM(i.SUBCODE01) AS SUBCODE01,
            TRIM(i.SUBCODE02) AS SUBCODE02,
            TRIM(i.SUBCODE03) AS SUBCODE03,
            TRIM(i.SUBCODE04) AS SUBCODE04,
            TRIM(i.SUBCODE05) AS SUBCODE05, 
            SUM(i2.USERPRIMARYQUANTITY) AS BRUTO
        FROM ITXVIEWBONORDER i
        LEFT JOIN ITXVIEWKGBRUTOBONORDER2 i2 
            ON i2.ORIGDLVSALORDLINESALORDERCODE = i.SALESORDERCODE 
           AND i2.ORIGDLVSALORDERLINEORDERLINE = i.ORDERLINE 
        WHERE i.SALESORDERCODE = ?
        GROUP BY 
            i.ORDERLINE,
            i.ITEMTYPEAFICODE,
            i.WARNA,
            i.SUBCODE01,
            i.SUBCODE02,
            i.SUBCODE03,
            i.SUBCODE04,
            i.SUBCODE05,
            i2.USERPRIMARYQUANTITY
        ORDER BY i.ORDERLINE
    ";

    $stmtItems = db2_prepare($conn1, $sqlItems);
    $items     = [];

    if ($stmtItems && db2_execute($stmtItems, [$projectCode])) {
        while ($r = db2_fetch_assoc($stmtItems)) {
            $value = $r['DLVSALESORDERLINEORDERLINE'];
            $bruto = isset($r['BRUTO']) ? (float) $r['BRUTO'] : 0;

            $text  = trim($r['ITEMTYPEAFICODE']) . '-' . trim($r['SUBCODE02']) . '.' . trim($r['SUBCODE03']);
            $text .= ' | ' . trim($r['WARNA']);
            $text .= ' | ' . number_format($bruto, 2);

            $items[] = [
                'value' => $value,
                'text'  => $text,
            ];
        }
    }

    echo json_encode([
        'success'     => true,
        'projectcode' => $projectCode,
        'langganan'   => $langgananText,
        'items'       => $items,
    ], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}

