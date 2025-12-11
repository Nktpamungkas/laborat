<?php
ini_set('display_errors', 0);
error_reporting(0);
include __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

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
        throw new Exception('Gagal mengambil detail item.');
    }

    $r_item = db2_fetch_assoc($stmtItem);
    if (!$r_item) {
        echo json_encode([
            'success' => false,
            'message' => 'Detail item tidak ditemukan.'
        ]);
        exit;
    }

    $no_item1 = trim($r_item['SUBCODE02']) . trim($r_item['SUBCODE03']);

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

    $pogreige  = '';
    $pogreige2 = '';

    if ($r_pogreigenew) {
        // Ikuti format lama: selalu tampil "NO KO : <LOTCODE>/ DEMAND KGF :<DEMAND>"
        // dengan fallback '-' jika datanya kosong/null.
        $lotcode = isset($r_pogreigenew['LOTCODE']) && $r_pogreigenew['LOTCODE'] !== '' ? $r_pogreigenew['LOTCODE'] : '-';
        $dkgf    = isset($r_pogreigenew['DEMAND_KGF']) && $r_pogreigenew['DEMAND_KGF'] !== '' ? $r_pogreigenew['DEMAND_KGF'] : '-';
        $pogreige = 'NO KO : ' . $lotcode . '/ DEMAND KGF :' . $dkgf;
    } else {
        // Tidak ada record POGREIGE -> pakai default yang lama
        $pogreige = 'NO KO : -/ DEMAND KGF :-';
    }
    if ($r_pogreigenew4 && $r_pogreigenew4['INTERNALREFERENCE']) {
        $pogreige2 = $r_pogreigenew4['INTERNALREFERENCE'];
    } else {
        // Ikuti logika lama di form: jika tidak ada INTERNALREFERENCE, ambil VALUESTRING (bisa kosong)
        $pogreige2 = $r_pogreigenew5['VALUESTRING'] ?? '';
    }

    // Selalu tampilkan ", PROJECT : ..." seperti di implementasi lama
    $no_po = rtrim($pogreige) . ', PROJECT : ' . $pogreige2;

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

    // ========= DATA PRODUK (JENIS KAIN) =========
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

    // ========= COLOR CODE & WARNA (sesuai kode lama Matching Ulang NOW) =========
    $assoc_colorcode = null;
    $sqlColorCode = "
        SELECT 
            p.DLVSALESORDERLINEORDERLINE,
            TRIM(p.CODE) AS DEMANDCODE,
            trim(i2.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
            trim(i2.SUBCODE01) AS SUBCODE01, 
            trim(i2.SUBCODE02) AS SUBCODE02,
            trim(i2.SUBCODE03) AS SUBCODE03, 
            trim(i2.SUBCODE04) AS SUBCODE04, 
            trim(i2.SUBCODE05) AS SUBCODE05,
            trim(i2.SUBCODE06) AS SUBCODE06,
            trim(i2.SUBCODE07) AS SUBCODE07,
            trim(i2.SUBCODE08) AS SUBCODE08,
            trim(i2.SUBCODE09) AS SUBCODE09,
            trim(i2.SUBCODE10) AS SUBCODE10,
            i.WARNA AS WARNA
        FROM PRODUCTIONDEMAND p 
        LEFT JOIN ITXVIEWBONORDER i2 ON i2.SALESORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND i2.ORDERLINE = p.ORIGDLVSALORDERLINEORDERLINE 
        LEFT JOIN ITXVIEWCOLOR i ON i.ITEMTYPECODE = i2.ITEMTYPEAFICODE 
                                AND i.SUBCODE01 = i2.SUBCODE01 
                                AND i.SUBCODE02 = i2.SUBCODE02 
                                AND i.SUBCODE03 = i2.SUBCODE03 
                                AND i.SUBCODE04 = i2.SUBCODE04 
                                AND i.SUBCODE05 = i2.SUBCODE05 
                                AND i.SUBCODE06 = i2.SUBCODE06 
                                AND i.SUBCODE07 = i2.SUBCODE07 
                                AND i.SUBCODE08 = i2.SUBCODE08 
                                AND i.SUBCODE09 = i2.SUBCODE09 
                                AND i.SUBCODE10 = i2.SUBCODE10
        LEFT JOIN USERGENERICGROUP USERGENERICGROUP ON p.SUBCODE05 = USERGENERICGROUP.CODE 
        WHERE p.ORIGDLVSALORDLINESALORDERCODE = ? AND p.DLVSALESORDERLINEORDERLINE = ?
        GROUP BY 
            p.DLVSALESORDERLINEORDERLINE,i2.SUBCODE01,i2.SUBCODE02,i2.SUBCODE03,i2.SUBCODE04,i2.SUBCODE05,i2.SUBCODE06,i2.SUBCODE07,i2.SUBCODE08,i2.SUBCODE09,i2.SUBCODE10,i2.ITEMTYPEAFICODE,i.WARNA,p.CODE
    ";
    $stmtColor = db2_prepare($conn1, $sqlColorCode);
    if ($stmtColor && db2_execute($stmtColor, [$projectCode, $orderLine])) {
        $assoc_colorcode = db2_fetch_assoc($stmtColor);
    }

    // $color_code   = '';
    $warna_short  = trim($r_item['WARNA']);
    if ($assoc_colorcode) {
        if (!empty($assoc_colorcode['SUBCODE05'])) {
            $color_code = $assoc_colorcode['SUBCODE05'];
        }
        if (!empty($assoc_colorcode['WARNA'])) {
            $warna_short = $assoc_colorcode['WARNA'];
        }
    }

    // Selaraskan logika warna dengan L/D NOW:
    // selalu ambil nama warna pendek dari ITXVIEWBONORDER.WARNA
    // berdasarkan SALESORDERCODE + ORDERLINE.
    $sqlWarnaBon = "
        SELECT WARNA 
        FROM ITXVIEWBONORDER
        WHERE SALESORDERCODE = ?
          AND ORDERLINE      = ?
        FETCH FIRST 1 ROW ONLY
    ";
    $stmtWarnaBon = db2_prepare($conn1, $sqlWarnaBon);
    if ($stmtWarnaBon && db2_execute($stmtWarnaBon, [$projectCode, $orderLine])) {
        $rowWarnaBon = db2_fetch_assoc($stmtWarnaBon);
        if ($rowWarnaBon && !empty($rowWarnaBon['WARNA'])) {
            $warna_short = trim($rowWarnaBon['WARNA']);
        }
    }

    // ========= BENANG (mengikuti logika NowForm lama) =========
    $benang = '';

    // Ambil data BON ORDER terlebih dahulu (seperti $q_itxviewkk di form lama)
    $sqlItxviewkk = "
        SELECT *
        FROM ITXVIEWBONORDER
        WHERE SALESORDERCODE = ?
          AND ORDERLINE      = ?
    ";
    $stmtItxviewkk = db2_prepare($conn1, $sqlItxviewkk);
    $d_itxviewkk   = null;
    if ($stmtItxviewkk && db2_execute($stmtItxviewkk, [$projectCode, $orderLine])) {
        $d_itxviewkk = db2_fetch_assoc($stmtItxviewkk);
    }

    if ($d_itxviewkk) {
        // Tentukan SUBCODE04 yang dipakai (ada pengecualian untuk KFF)
        $subcode04 = $d_itxviewkk['SUBCODE04'];
        if (trim($d_itxviewkk['ITEMTYPEAFICODE']) === 'KFF' && !empty($d_itxviewkk['RESERVATION_SUBCODE04'])) {
            $subcode04 = $d_itxviewkk['RESERVATION_SUBCODE04'];
        }

        $linesBenang = [];

        // 1) Data rajut (ITXVIEW_RAJUT)
        $sqlRajut = "
            SELECT SUMMARIZEDDESCRIPTION
            FROM ITXVIEW_RAJUT
            WHERE SUBCODE01 = ?
              AND SUBCODE02 = ?
              AND SUBCODE03 = ?
              AND SUBCODE04 = ?
              AND ORIGDLVSALORDLINESALORDERCODE = ?
              AND (ITEMTYPEAFICODE = 'KGF' OR ITEMTYPEAFICODE = 'FKG')
        ";
        $stmtRajut = db2_prepare($conn1, $sqlRajut);
        if ($stmtRajut && db2_execute($stmtRajut, [
            $d_itxviewkk['SUBCODE01'],
            $d_itxviewkk['SUBCODE02'],
            $d_itxviewkk['SUBCODE03'],
            $subcode04,
            $projectCode,
        ])) {
            $rowRajut = db2_fetch_assoc($stmtRajut);
            if ($rowRajut && !empty($rowRajut['SUMMARIZEDDESCRIPTION'])) {
                $linesBenang[] = trim($rowRajut['SUMMARIZEDDESCRIPTION']);
            }
        }

        // Helper untuk ambil satu baris dari ITXVIEW_BOOKING_BLM_READY
        $sqlBooking = "
            SELECT SUMMARIZEDDESCRIPTION, ORIGDLVSALORDLINESALORDERCODE
            FROM ITXVIEW_BOOKING_BLM_READY
            WHERE SUBCODE01 = ?
              AND SUBCODE02 = ?
              AND SUBCODE03 = ?
              AND SUBCODE04 = ?
              AND ORIGDLVSALORDLINESALORDERCODE = ?
              AND (ITEMTYPEAFICODE = 'KGF' OR ITEMTYPEAFICODE = 'FKG')
        ";

        $bookingFields = ['ADDITIONALDATA', 'ADDITIONALDATA2', 'ADDITIONALDATA3', 'ADDITIONALDATA4', 'ADDITIONALDATA4'];
        foreach ($bookingFields as $fieldName) {
            $origCode = isset($d_itxviewkk[$fieldName]) ? trim($d_itxviewkk[$fieldName]) : '';
            if ($origCode === '') {
                continue;
            }
            $stmtBooking = db2_prepare($conn1, $sqlBooking);
            if ($stmtBooking && db2_execute($stmtBooking, [
                $d_itxviewkk['SUBCODE01'],
                $d_itxviewkk['SUBCODE02'],
                $d_itxviewkk['SUBCODE03'],
                $subcode04,
                $origCode,
            ])) {
                $rowBooking = db2_fetch_assoc($stmtBooking);
                if ($rowBooking && !empty($rowBooking['SUMMARIZEDDESCRIPTION'])) {
                    $linesBenang[] = trim($rowBooking['SUMMARIZEDDESCRIPTION']) . ' - ' . trim($rowBooking['ORIGDLVSALORDLINESALORDERCODE']);
                }
            }
        }

        // 2) Booking baru (ITXVIEW_BOOKING_NEW)
        $sqlBookingNew = "
            SELECT SUMMARIZEDDESCRIPTION
            FROM ITXVIEW_BOOKING_NEW
            WHERE SALESORDERCODE = ?
              AND ORDERLINE      = ?
        ";
        $stmtBookingNew = db2_prepare($conn1, $sqlBookingNew);
        if ($stmtBookingNew && db2_execute($stmtBookingNew, [$projectCode, $orderLine])) {
            $rowBookingNew = db2_fetch_assoc($stmtBookingNew);
            if ($rowBookingNew && !empty($rowBookingNew['SUMMARIZEDDESCRIPTION'])) {
                $linesBenang[] = trim($rowBookingNew['SUMMARIZEDDESCRIPTION']);
            }
        }

        if ($linesBenang) {
            // Gabungkan dengan newline \r\n supaya tampilan di textarea sama seperti server lama
            $benang = implode("\r\n", $linesBenang);
        }
    }

    // ========= LEBAR (WIDTH) & GRAMASI (GSM) =========
    $lebar   = '';
    $gramasi = '';

    // Lebar
    $sqlLebar = "
        SELECT
            CASE
                WHEN TRIM(ADSTORAGE.NAMENAME) = 'Width' AND TRIM(PRODUCT.ITEMTYPECODE) = 'KFF' THEN ADSTORAGE.VALUEDECIMAL
                WHEN TRIM(ADSTORAGE.NAMENAME) = 'Width' AND TRIM(PRODUCT.ITEMTYPECODE) = 'FKF'
                    THEN SUBSTRING(PRODUCT.SUBCODE04, 1, LOCATE('-', PRODUCT.SUBCODE04) - 1)
                ELSE NULL
            END AS LEBAR
        FROM ADSTORAGE
        RIGHT JOIN PRODUCT ON ADSTORAGE.UNIQUEID = PRODUCT.ABSUNIQUEID
        WHERE TRIM(PRODUCT.SUBCODE01) = ?
          AND TRIM(PRODUCT.SUBCODE02) = ?
          AND TRIM(PRODUCT.SUBCODE03) = ?
          AND TRIM(PRODUCT.SUBCODE04) = ?
          AND TRIM(PRODUCT.SUBCODE05) = ?
          AND TRIM(PRODUCT.SUBCODE06) = ?
          AND TRIM(PRODUCT.SUBCODE07) = ?
          AND TRIM(PRODUCT.SUBCODE08) = ?
          AND TRIM(PRODUCT.SUBCODE09) = ?
          AND TRIM(PRODUCT.SUBCODE10) = ?
          AND TRIM(PRODUCT.ITEMTYPECODE) = ?
          AND TRIM(ADSTORAGE.NAMENAME) = 'Width'
    ";
    $stmtLebar = db2_prepare($conn1, $sqlLebar);
    if ($stmtLebar && db2_execute($stmtLebar, [$s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $s9, $s10, $itemtype])) {
        $rowLebar = db2_fetch_assoc($stmtLebar);
        if ($rowLebar && $rowLebar['LEBAR'] !== null) {
            $lebar = (string) $rowLebar['LEBAR'];
        }
    }

    // Gramasi
    $sqlGramasi = "
        SELECT
            TRIM(ADSTORAGE.VALUEDECIMAL) AS VALUEDECIMAL,
            PRODUCT.SUBCODE04
        FROM ADSTORAGE
        RIGHT JOIN PRODUCT ON ADSTORAGE.UNIQUEID = PRODUCT.ABSUNIQUEID
        WHERE TRIM(PRODUCT.SUBCODE01) = ?
          AND TRIM(PRODUCT.SUBCODE02) = ?
          AND TRIM(PRODUCT.SUBCODE03) = ?
          AND TRIM(PRODUCT.SUBCODE04) = ?
          AND TRIM(PRODUCT.SUBCODE05) = ?
          AND TRIM(PRODUCT.SUBCODE06) = ?
          AND TRIM(PRODUCT.SUBCODE07) = ?
          AND TRIM(PRODUCT.SUBCODE08) = ?
          AND TRIM(PRODUCT.SUBCODE09) = ?
          AND TRIM(PRODUCT.SUBCODE10) = ?
          AND TRIM(ADSTORAGE.NAMENAME) = 'GSM'
    ";
    $stmtGramasi = db2_prepare($conn1, $sqlGramasi);
    if ($stmtGramasi && db2_execute($stmtGramasi, [$s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $s9, $s10])) {
        $rowGramasi = db2_fetch_assoc($stmtGramasi);
        if ($rowGramasi) {
            if ($itemtype === 'FKF' && !empty($rowGramasi['SUBCODE04'])) {
                $parts = explode('-', $rowGramasi['SUBCODE04']);
                if (isset($parts[1])) {
                    $gramasi = $parts[1];
                }
            } elseif (!empty($rowGramasi['VALUEDECIMAL'])) {
                $gramasi = $rowGramasi['VALUEDECIMAL'];
            }
        }
    }

    // LAB DIP NO & Cocok Warna (STDCCKWARNA) – pakai view lama
    $no_warna    = '';
    $cocok_warna = '';

    // 1) STDCCK (cocok warna) – view standar
    $sqlCckStd = "
        SELECT * 
        FROM ITXVIEW_STD_CCK_WARNA 
        WHERE SALESORDERCODE = ? 
          AND ORDERLINE = ?
    ";
    $stmtCckStd = db2_prepare($conn1, $sqlCckStd);
    $r_cck_std  = null;
    if ($stmtCckStd && db2_execute($stmtCckStd, [$projectCode, $orderLine])) {
        $r_cck_std = db2_fetch_assoc($stmtCckStd);
        if ($r_cck_std) {
            $cocok_warna = trim($r_cck_std['STDCCKWARNA'] ?? 'aaa');
        }
    }

    // 2) LAB DIP NO lebih lengkap (seperti $stdcckwarna_lapdip)
    $sqlCckLabdip = "
        SELECT
            ITXVIEW_STD_CCK_WARNA.LABDIPNO,
            ITXVIEW_COLORREMARKS.VALUESTRING
        FROM SALESORDERLINE
        LEFT JOIN ITXVIEW_COLORSTANDARD 
            ON SALESORDERLINE.ABSUNIQUEID = ITXVIEW_COLORSTANDARD.UNIQUEID
        LEFT JOIN ITXVIEW_COLORREMARKS 
            ON ITXVIEW_COLORSTANDARD.UNIQUEID = ITXVIEW_COLORREMARKS.UNIQUEID
        LEFT JOIN ITXVIEW_STD_CCK_WARNA
            ON ITXVIEW_STD_CCK_WARNA.SALESORDERCODE = SALESORDERLINE.PROJECTCODE
           AND ITXVIEW_STD_CCK_WARNA.ORDERLINE     = SALESORDERLINE.ORDERLINE
        WHERE TRIM(SALESORDERLINE.PROJECTCODE) = ?
          AND TRIM(SALESORDERLINE.ORDERLINE)   = ?
    ";
    $stmtCckLab = db2_prepare($conn1, $sqlCckLabdip);
    if ($stmtCckLab && db2_execute($stmtCckLab, [$projectCode, $orderLine])) {
        $r_cck_lab = db2_fetch_assoc($stmtCckLab);
        if ($r_cck_lab && !empty($r_cck_lab['LABDIPNO'])) {
            $no_warna = $r_cck_lab['LABDIPNO'];
        }
    }

    // Fallback / normalisasi WARNA:
    // Jika masih mengandung '-' (contoh: "CAMBRIDGE BLUE-..."),
    // ambil teks sebelum tanda '-' untuk mendapatkan nama warna pendek.
    if (strpos($warna_short, '-') !== false) {
        $warna_short = trim(strtok($warna_short, '-'));
    }

    // - COLOR CODE & LAB DIP NO dari STDCCKWARNA, contoh:
    //   "Previous Order - 6400/181448M-C ( ... )"
    //   "Labdip - 12367/121274D-C ( ... )"
    //   "First Lot - 21120/230538D-B, LANJUT PROCESS"
    if ($cocok_warna) {
        // if (!$color_code && preg_match('/\\d+\\/([A-Za-z0-9]+)-/', $cocok_warna, $m)) {
        //     $color_code = $m[1];
        // }
        if (!$no_warna && preg_match('/Previous Order\\s*-\\s*(.+)$/i', $cocok_warna, $m2)) {
            $no_warna = trim($m2[1]);
        }
        // Pola khusus "Labdip - 12367/121274D-C ( ... )"
        if (!$no_warna && preg_match('/Labdip\\s*-\\s*([^()]+)/i', $cocok_warna, $m3)) {
            $no_warna = trim($m3[1]);
        }
        // Pola "First Lot - 21120/230538D-B, LANJUT PROCESS"
        if (!$no_warna && preg_match('/First Lot\\s*-\\s*([^()]+)/i', $cocok_warna, $m4)) {
            $no_warna = trim($m4[1]);
        }
        // Pola "Body - 21120/230538D-B, LANJUT PROCESS"
        if (!$no_warna && preg_match('/Body\\s*-\\s*([^()]+)/i', $cocok_warna, $m4)) {
            $no_warna = trim($m4[1]);
        }
    }

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

    // Qty Order (QTY_BRUTO) ? hanya dipakai untuk Matching Ulang NOW (NowForm)
    $qty = '';
    $sqlQty = "
        SELECT SUM(USERPRIMARYQUANTITY) AS QTY_BRUTO
        FROM ITXVIEW_KGBRUTO 
        WHERE PROJECTCODE = ?
          AND ORIGDLVSALORDERLINEORDERLINE = ?
    ";
    $stmtQty = db2_prepare($conn1, $sqlQty);
    if ($stmtQty && db2_execute($stmtQty, [$projectCode, $orderLine])) {
        $rowQty = db2_fetch_assoc($stmtQty);
        if ($rowQty && $rowQty['QTY_BRUTO'] !== null) {
            $qty = (string) $rowQty['QTY_BRUTO'];
        }
    }

    // Cari history Recipe Code di MySQL (tbl_matching) untuk order & item yang sama,
    // supaya tampil seperti server lama (multi-line).
    $recipeHistory = '';
    if (!empty($no_item1) && !empty($projectCode)) {
        $orderEsc  = mysqli_real_escape_string($con, $projectCode);
        $itemEsc   = mysqli_real_escape_string($con, $no_item1);
        $sqlRecipe = "
            SELECT DISTINCT recipe_code 
            FROM tbl_matching 
            WHERE no_order = '{$orderEsc}'
              AND no_item = '{$itemEsc}'
              AND recipe_code IS NOT NULL
              AND recipe_code <> ''
            ORDER BY id DESC
            LIMIT 50
        ";
        if ($resRecipe = mysqli_query($con, $sqlRecipe)) {
            $codes = [];
            while ($rowRc = mysqli_fetch_assoc($resRecipe)) {
                $codes[] = $rowRc['recipe_code'];
            }
            if ($codes) {
                $recipeHistory = implode("\n", $codes);
            }
        }
    }

    echo json_encode([
        'success'      => true,
        'no_item1'     => $no_item1,
        'color_code'   => $color_code,
        // 'recipe_code'  => $recipeHistory,
        'no_po'        => $no_po,
        'kain'         => $kain,
        'warna'        => $warna_short,
        'no_warna'     => $no_warna,
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
