<?php
ini_set('display_errors', 0);
error_reporting(0);
include __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

function ldnow_log($msg)
{
    // Logging dimatikan sesuai permintaan, agar ldnow_debug.log
    // tidak dibuat lagi. Fungsi ini dibiarkan kosong.
    return;
}

ldnow_log('HEADER start, raw req_no=' . ($_POST['req_no'] ?? 'NULL'));

$reqNo = isset($_POST['req_no']) ? strtoupper(trim($_POST['req_no'])) : '';
$mode  = isset($_POST['mode']) ? strtoupper(trim($_POST['mode'])) : 'LD'; // LD (L/D NOW) atau NOW (Matching Ulang NOW)
ldnow_log('HEADER after trim, reqNo=' . $reqNo . ' mode=' . $mode);

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
    ldnow_log('HEADER before db2_prepare');
    $stmtLangganan = db2_prepare($conn1, $sqlLangganan);
    ldnow_log('HEADER after db2_prepare, stmt=' . ($stmtLangganan ? 'OK' : 'FALSE'));
    if (!$stmtLangganan || !db2_execute($stmtLangganan, ['%' . $reqNo . '%'])) {
        ldnow_log('HEADER db2_execute gagal: ' . db2_stmt_errormsg());
        throw new Exception('Gagal mengambil data langganan.');
    }

    $dt_langganan = db2_fetch_assoc($stmtLangganan);
    if (!$dt_langganan) {
        echo json_encode([
            'success' => false,
            'message' => 'Data L/D NOW tidak ditemukan untuk Req No tersebut.'
        ]);
        exit;
    }

    $projectCode   = trim($dt_langganan['PROJECTCODE']);
    $langgananText = trim($dt_langganan['LANGGANAN']) . '/' . trim($dt_langganan['BUYER']);

    // ============================
    // 2. LIST NO. ITEM (beda query LD vs Matching Ulang NOW)
    // ============================
    if ($mode === 'NOW') {
        // Matching Ulang NOW / Perbaikan NOW → pakai ITXVIEWBONORDER seperti kode lama
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
    } else {
        // LD NOW → pakai SALESORDERLINE seperti sebelumnya
        $sqlItems = "
            SELECT 
                TRIM(p2.CODE) AS CODE,
                p.ORDERLINE AS DLVSALESORDERLINEORDERLINE,
                p.ITEMTYPEAFICODE AS ITEMTYPEAFICODE,
                p.ITEMDESCRIPTION AS WARNA,
                TRIM(p.SUBCODE01) AS SUBCODE01, 
                TRIM(p.SUBCODE02) AS SUBCODE02, 
                TRIM(p.SUBCODE03) AS SUBCODE03, 
                TRIM(p.SUBCODE04) AS SUBCODE04, 
                TRIM(p.SUBCODE05) AS SUBCODE05,
                p.ORDERLINE
            FROM SALESORDERLINE p
            LEFT JOIN PRODUCTIONDEMAND p2 
                ON p2.ORIGDLVSALORDLINESALORDERCODE = p.SALESORDERCODE 
               AND p2.ORIGDLVSALORDERLINEORDERLINE = p.ORDERLINE
            WHERE p.SALESORDERCODE = ? 
              AND p.ORDERLINE IS NOT NULL
            GROUP BY 
                p2.CODE, 
                p.ORDERLINE,
                p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
                p.SUBCODE04,
                p.SUBCODE05,
                p.SUBCODE08,
                p.SUBCODE07,
                p.ITEMTYPEAFICODE,
                p.ITEMDESCRIPTION
            ORDER BY p.ORDERLINE
        ";
    }

    $stmtItems = db2_prepare($conn1, $sqlItems);
    $items     = [];

    if ($stmtItems && db2_execute($stmtItems, [$projectCode])) {
        while ($r = db2_fetch_assoc($stmtItems)) {
            $value = $r['DLVSALESORDERLINEORDERLINE'];

            if ($mode === 'NOW') {
                // Format lama Matching Ulang NOW: ITEMTYPE-SUBCODE02.SUBCODE03 | WARNA | BRUTO
                $bruto = isset($r['BRUTO']) ? (float) $r['BRUTO'] : 0;
                $text  = trim($r['ITEMTYPEAFICODE']) . '-' . trim($r['SUBCODE02']) . '.' . trim($r['SUBCODE03']);
                $text .= ' | ' . trim($r['WARNA']);
                $text .= ' | ' . number_format($bruto, 2);
            } else {
                // Format LD NOW (seperti sebelumnya)
                $text  = trim($r['ITEMTYPEAFICODE']) . '-' . trim($r['SUBCODE02']) . '.' . trim($r['SUBCODE03']);
                $text .= ' | ' . trim($r['WARNA']);
                $text .= ' | ' . trim($r['ORDERLINE']);
                $text .= ' | ' . trim($r['CODE']);
            }

            $items[] = [
                'value' => $value,
                'text'  => $text,
            ];
        }
    }

    $payload = [
        'success'     => true,
        'projectcode' => $projectCode,
        'langganan'   => $langgananText,
        'items'       => $items,
    ];
    $json = json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    ldnow_log('HEADER sukses, json_error=' . json_last_error() . ' msg=' . json_last_error_msg());
    ldnow_log('HEADER sukses, json length=' . strlen((string) $json) . ' json=' . $json);
    echo $json;
} catch (Exception $e) {
    ldnow_log('HEADER exception: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
