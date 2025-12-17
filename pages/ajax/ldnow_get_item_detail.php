<?php
ini_set('display_errors', 0);
error_reporting(0);
include __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

// DETAIL khusus L/D NOW
// Mengikuti pola query di kode lama:
// - Basis dari SALESORDERLINE + PRODUCTIONDEMAND
// - PO Greige dari ITXVIEWPOGREIGENEW(1/2/3) + PRODUCTIONDEMAND/ADSTORAGE
// - Jenis kain dari PRODUCT
// - LAB DIP NO & Cocok Warna dari ITXVIEW_STD_CCK_WARNA
// - Tgl Delivery dari SALESORDERDELIVERY
// Field lain (lebar/gramasi/benang/qty/recipe_code) dikosongkan karena
// di form L/D NOW memang hidden / manual.

$projectCode = isset($_POST['projectcode']) ? trim($_POST['projectcode']) : '';
$orderLine   = isset($_POST['orderline']) ? trim($_POST['orderline']) : '';

if ($projectCode === '' || $orderLine === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Project code atau orderline kosong.'
    ]);
    exit;
}

try {
    // ============= BASE ITEM (SALESORDERLINE + PRODUCTIONDEMAND) ============
    $sqlItem = "
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
            TRIM(p.SUBCODE06) AS SUBCODE06, 
            TRIM(p.SUBCODE07) AS SUBCODE07, 
            TRIM(p.SUBCODE08) AS SUBCODE08,
            TRIM(p.SUBCODE09) AS SUBCODE09,
            TRIM(p.SUBCODE10) AS SUBCODE10
        FROM SALESORDERLINE p
        LEFT JOIN PRODUCTIONDEMAND p2 
            ON p2.ORIGDLVSALORDLINESALORDERCODE = p.SALESORDERCODE 
           AND p2.ORIGDLVSALORDERLINEORDERLINE = p.ORDERLINE
        WHERE p.SALESORDERCODE = ? 
          AND p.ORDERLINE = ?
        GROUP BY 
            p2.CODE, 
            p.ORDERLINE,
            p.SUBCODE01,
            p.SUBCODE02,
            p.SUBCODE03,
            p.SUBCODE04,
            p.SUBCODE05,
            p.SUBCODE06,
            p.SUBCODE07,
            p.SUBCODE08,
            p.SUBCODE09,
            p.SUBCODE10,
            p.ITEMTYPEAFICODE,
            p.ITEMDESCRIPTION
    ";

    $stmtItem = db2_prepare($conn1, $sqlItem);
    if (!$stmtItem || !db2_execute($stmtItem, [$projectCode, $orderLine])) {
        throw new Exception('Gagal mengambil detail item L/D NOW.');
    }

    $r_item = db2_fetch_assoc($stmtItem);
    if (!$r_item) {
        echo json_encode([
            'success' => false,
            'message' => 'Detail item L/D NOW tidak ditemukan.'
        ]);
        exit;
    }

    $no_item1 = trim($r_item['SUBCODE02']) . trim($r_item['SUBCODE03']);

    // ============= PO GREIGE (ITXVIEWPOGREIGENEW*) + PROJECT / INTERNALREF ============
    $sqlPoGreige = "
        SELECT 
            CASE
                WHEN LOTCODE IS NOT NULL THEN LOTCODE
                ELSE '-'
            END AS LOTCODE,
            CASE
                WHEN DEMAND_KGF IS NOT NULL THEN DEMAND_KGF
                ELSE '-'
            END AS DEMAND_KGF
        FROM 
        (
            SELECT 
                i.LOTCODE AS LOTCODE,
                i.DEMAND_KGF AS DEMAND_KGF
            FROM ITXVIEWPOGREIGENEW i 
            WHERE i.SALESORDERCODE = ? AND i.ORDERLINE = ?
            UNION ALL
            SELECT 
                i2.LOTCODE AS LOTCODE,
                i2.DEMAND_KGF AS DEMAND_KGF
            FROM ITXVIEWPOGREIGENEW2 i2 
            WHERE i2.SALESORDERCODE = ? AND i2.ORDERLINE = ?
            UNION ALL
            SELECT
                i3.LOTCODE AS LOTCODE,
                i3.DEMAND_KGF AS DEMAND_KGF
            FROM ITXVIEWPOGREIGENEW3 i3 
            WHERE i3.SALESORDERCODE = ? AND i3.ORDERLINE = ?
        )
        GROUP BY LOTCODE, DEMAND_KGF
    ";

    $stmtPo        = db2_prepare($conn1, $sqlPoGreige);
    $r_pogreigenew = null;
    if ($stmtPo && db2_execute($stmtPo, [$projectCode, $orderLine, $projectCode, $orderLine, $projectCode, $orderLine])) {
        $r_pogreigenew = db2_fetch_assoc($stmtPo);
    }

    $sqlPoIntRef = "
        SELECT INTERNALREFERENCE 
        FROM PRODUCTIONDEMAND 
        WHERE ORIGDLVSALORDLINESALORDERCODE = ? 
          AND ORIGDLVSALORDERLINEORDERLINE = ?
    ";
    $stmtPo4        = db2_prepare($conn1, $sqlPoIntRef);
    $r_pogreigenew4 = null;
    if ($stmtPo4 && db2_execute($stmtPo4, [$projectCode, $orderLine])) {
        $r_pogreigenew4 = db2_fetch_assoc($stmtPo4);
    }

    $sqlPoAd = "
        SELECT 
            a.ORIGDLVSALORDLINESALORDERCODE,
            a.ORIGDLVSALORDERLINEORDERLINE,
            a.INTERNALREFERENCE,
            b.NAMENAME,
            b.VALUESTRING 
        FROM PRODUCTIONDEMAND a
        LEFT JOIN ADSTORAGE b ON b.UNIQUEID = a.ABSUNIQUEID 
        WHERE 
            ORIGDLVSALORDLINESALORDERCODE = ? 
            AND ORIGDLVSALORDERLINEORDERLINE = ?
            AND (b.NAMENAME = 'ProAllow' OR b.NAMENAME = 'ProAllow2' OR b.NAMENAME = 'ProAllo3' OR b.NAMENAME = 'ProAllow4' OR b.NAMENAME = 'ProAllow5')
    ";
    $stmtPo5        = db2_prepare($conn1, $sqlPoAd);
    $r_pogreigenew5 = null;
    if ($stmtPo5 && db2_execute($stmtPo5, [$projectCode, $orderLine])) {
        $r_pogreigenew5 = db2_fetch_assoc($stmtPo5);
    }

    $pogreige  = 'NO KO : -/ DEMAND KGF :-';
    $pogreige2 = '';

    if ($r_pogreigenew) {
        $lotcode = isset($r_pogreigenew['LOTCODE']) && $r_pogreigenew['LOTCODE'] !== '' ? $r_pogreigenew['LOTCODE'] : '-';
        $dkgf    = isset($r_pogreigenew['DEMAND_KGF']) && $r_pogreigenew['DEMAND_KGF'] !== '' ? $r_pogreigenew['DEMAND_KGF'] : '-';
        $pogreige = 'NO KO : ' . $lotcode . '/ DEMAND KGF :' . $dkgf;
    }
    if ($r_pogreigenew4 && $r_pogreigenew4['INTERNALREFERENCE']) {
        $pogreige2 = $r_pogreigenew4['INTERNALREFERENCE'];
    } else {
        $pogreige2 = $r_pogreigenew5['VALUESTRING'] ?? '';
    }

    $no_po = rtrim($pogreige) . ', PROJECT : ' . $pogreige2;

    // ============= PRODUK (JENIS KAIN) ============
    $itemtype = $r_item['ITEMTYPEAFICODE'];
    $s1       = $r_item['SUBCODE01'];
    $s2       = $r_item['SUBCODE02'];
    $s3       = $r_item['SUBCODE03'];
    $s4       = $r_item['SUBCODE04'];
    $s5       = $r_item['SUBCODE05'];
    $s6       = $r_item['SUBCODE06'];
    $s7       = $r_item['SUBCODE07'];
    $s8       = $r_item['SUBCODE08'];
    $s9       = $r_item['SUBCODE09'];
    $s10      = $r_item['SUBCODE10'];

    $sqlJk = "
        SELECT * 
        FROM PRODUCT 
        WHERE TRIM(SUBCODE01) = ? 
          AND TRIM(SUBCODE02) = ? 
          AND TRIM(SUBCODE03) = ? 
          AND TRIM(SUBCODE04) = ? 
          AND TRIM(SUBCODE05) = ? 
          AND TRIM(SUBCODE06) = ? 
          AND TRIM(SUBCODE07) = ? 
          AND TRIM(SUBCODE08) = ?
          AND TRIM(SUBCODE09) = ?
          AND TRIM(SUBCODE10) = ?
          AND TRIM(ITEMTYPECODE) = ?
    ";

    $stmtJk = db2_prepare($conn1, $sqlJk);
    $r_jk   = null;
    if ($stmtJk && db2_execute($stmtJk, [$s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $s9, $s10, $itemtype])) {
        $r_jk = db2_fetch_assoc($stmtJk);
    }

    $kain = $r_jk ? str_replace('"', ' ', $r_jk['LONGDESCRIPTION']) : '';

    // ============= WARNA & COLOR CODE untuk L/D NOW ============
    // Di L/D NOW, color code diisi manual, jadi dikosongkan.
    // Warna cukup dari ITEMDESCRIPTION (p.ITEMDESCRIPTION).
    $color_code = '';
    $warna      = trim($r_item['WARNA']);

    // ============= LAB DIP NO & COCOK WARNA (ITXVIEW_STD_CCK_WARNA) ============
    $no_warna    = '';
    $cocok_warna = '';

    $sqlCckStd = "
        SELECT * 
        FROM ITXVIEW_STD_CCK_WARNA 
        WHERE SALESORDERCODE = ? 
          AND ORDERLINE = ?
    ";
    $stmtCckStd = db2_prepare($conn1, $sqlCckStd);
    if ($stmtCckStd && db2_execute($stmtCckStd, [$projectCode, $orderLine])) {
        $r_cck_std = db2_fetch_assoc($stmtCckStd);
        if ($r_cck_std) {
            $cocok_warna = trim($r_cck_std['STDCCKWARNA'] ?? '');
            $no_warna    = $r_cck_std['LABDIPNO'];
        }
    }

    // ============= TGL DELIVERY ============
    $sqlDelivery = "
        SELECT * 
        FROM SALESORDERDELIVERY 
        WHERE SALESORDERLINESALESORDERCODE = ? 
          AND SALESORDERLINEORDERLINE = ?
    ";
    $stmtDelivery = db2_prepare($conn1, $sqlDelivery);
    $tgl_delivery = '';
    if ($stmtDelivery && db2_execute($stmtDelivery, [$projectCode, $orderLine])) {
        $r_delivery = db2_fetch_assoc($stmtDelivery);
        if ($r_delivery && !empty($r_delivery['DELIVERYDATE'])) {
            $date_deliv = date_create($r_delivery['DELIVERYDATE']);
            if ($date_deliv) {
                $tgl_delivery = date_format($date_deliv, "Y-m-d");
            }
        }
    }

    // Field lain dikosongkan untuk L/D NOW
    $lebar   = '';
    $gramasi = '';
    $benang  = '';
    $qty     = '';

    echo json_encode([
        'success'      => true,
        'no_item1'     => $no_item1,
        'color_code'   => $color_code,
        'recipe_code'  => '',
        'no_po'        => $no_po,
        'kain'         => $kain,
        'warna'        => $warna,
        // 'no_warna'     => $no_warna,
        'cocok_warna'  => $cocok_warna,
        'tgl_delivery' => $tgl_delivery,
        'lebar'        => $lebar,
        'gramasi'      => $gramasi,
        'benang'       => $benang,
        'qty'          => $qty,
    ], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}

