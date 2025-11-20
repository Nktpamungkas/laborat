<?php
include "../../koneksi.php";

$code = $_POST['code'] ?? '';

// =====================
// 1. MAIN QUERY (PREPARED)
// =====================
$mainSql = "SELECT DISTINCT 
                SALESORDERCODE,
                ORDERLINE,
                LEGALNAME1,
                AKJ,
                JENIS_KAIN,
                LISTAGG(DISTINCT ITEMCODE, ', ') AS ITEMCODE,
                LISTAGG(DISTINCT NOTETAS, ', ') AS NOTETAS,
                NO_PO,
                GRAMASI,
                LEBAR,
                COLOR_STANDARD,
                WARNA,
                KODE_WARNA,
                COLORREMARKS,
                SUBCODE01,
                SUBCODE02,
                SUBCODE03,
                SUBCODE04,
                LISTAGG(DISTINCT TRIM(SUBCODE04_FIXED), ', ') AS SUBCODE04_FIXED,
                SUBCODE05, SUBCODE06, SUBCODE07, SUBCODE08, SUBCODE09, SUBCODE10
            FROM (
                SELECT
                    i.SALESORDERCODE,
                    i.ORDERLINE,
                    CASE 
                        WHEN i.ITEMTYPEAFICODE = 'KFF' THEN i.RESERVATION_SUBCODE04
                        ELSE i.SUBCODE04
                    END AS SUBCODE04_FIXED,
                    i.LEGALNAME1,
                    i.AKJ,
                    p.LONGDESCRIPTION AS JENIS_KAIN,
                    i.NOTETAS_KGF || '/' || TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) || '-' || TRIM(i.SUBCODE04) AS ITEMCODE,
                    i.NOTETAS,
                    i.EXTERNALREFERENCE AS NO_PO,
                    CASE 
                        WHEN REGEXP_LIKE(i2.GRAMASI_KFF, '^\d+(\.\d+)?$') THEN CAST(i2.GRAMASI_KFF AS DECFLOAT)
                        WHEN REGEXP_LIKE(i2.GRAMASI_FKF, '^\d+(\.\d+)?$') THEN CAST(i2.GRAMASI_FKF AS DECFLOAT)
                        ELSE NULL
                    END AS GRAMASI,
                    i3.LEBAR,
                    CASE a.VALUESTRING
                        WHEN '1' THEN 'L/D'
                        WHEN '2' THEN 'First Lot'
                        WHEN '3' THEN 'Original'
                        WHEN '4' THEN 'Previous Order'
                        WHEN '5' THEN 'Master Color'
                        WHEN '6' THEN 'Lampiran Buyer'
                        WHEN '7' THEN 'Body'
                        ELSE ''
                    END AS COLOR_STANDARD,
                    i.WARNA,
                    TRIM(i.SUBCODE05) || ' (' || TRIM(i.COLORGROUP) || ')' AS KODE_WARNA,
                    a2.VALUESTRING AS COLORREMARKS,
                    TRIM(i.SUBCODE01) AS SUBCODE01,
                    TRIM(i.SUBCODE02) AS SUBCODE02,
                    TRIM(i.SUBCODE03) AS SUBCODE03,
                    TRIM(i.SUBCODE04) AS SUBCODE04,
                    TRIM(i.SUBCODE05) AS SUBCODE05,
                    TRIM(i.SUBCODE06) AS SUBCODE06,
                    TRIM(i.SUBCODE07) AS SUBCODE07,
                    TRIM(i.SUBCODE08) AS SUBCODE08,
                    TRIM(i.SUBCODE09) AS SUBCODE09,
                    TRIM(i.SUBCODE10) AS SUBCODE10
                FROM 
                    ITXVIEWBONORDER i
                LEFT JOIN PRODUCT p 
                    ON p.ITEMTYPECODE = i.ITEMTYPEAFICODE 
                    AND p.SUBCODE01 = i.SUBCODE01 
                    AND p.SUBCODE02 = i.SUBCODE02 
                    AND p.SUBCODE03 = i.SUBCODE03 
                    AND p.SUBCODE04 = i.SUBCODE04 
                    AND p.SUBCODE05 = i.SUBCODE05 
                    AND p.SUBCODE06 = i.SUBCODE06 
                    AND p.SUBCODE07 = i.SUBCODE07 
                    AND p.SUBCODE08 = i.SUBCODE08 
                    AND p.SUBCODE09 = i.SUBCODE09 
                    AND p.SUBCODE10 = i.SUBCODE10
                LEFT JOIN ITXVIEWGRAMASI i2 ON i2.SALESORDERCODE = i.SALESORDERCODE AND i2.ORDERLINE = i.ORDERLINE 
                LEFT JOIN ITXVIEWLEBAR i3 ON i3.SALESORDERCODE = i.SALESORDERCODE AND i3.ORDERLINE = i.ORDERLINE 
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a.FIELDNAME = 'ColorStandard'
                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a2.FIELDNAME = 'ColorRemarks'
                WHERE i.SALESORDERCODE = ?
            )
            GROUP BY
                SALESORDERCODE,
                ORDERLINE,
                LEGALNAME1,
                AKJ,
                JENIS_KAIN,
                NO_PO,
                GRAMASI,
                LEBAR,
                COLOR_STANDARD,
                WARNA,
                KODE_WARNA,
                COLORREMARKS,
                SUBCODE01,
                SUBCODE02,
                SUBCODE03,
                SUBCODE04,
                SUBCODE05,
                SUBCODE06,
                SUBCODE07,
                SUBCODE08,
                SUBCODE09,
                SUBCODE10
            ORDER BY ORDERLINE ASC";

$mainStmt = db2_prepare($conn1, $mainSql);
if (!$mainStmt) {
    echo json_encode(['success' => false, 'error' => 'Prepare main query failed']);
    exit;
}

if (!db2_execute($mainStmt, [$code])) {
    echo json_encode(['success' => false, 'error' => 'Execute main query failed']);
    exit;
}

// =====================
// 2. PREPARED STATEMENTS LAIN (CACHED)
// =====================

// 2.1 Detail ITXVIEWBONORDER
$itxSql = "SELECT * FROM ITXVIEWBONORDER WHERE SALESORDERCODE = ? AND ORDERLINE = ?";
$itxStmt = db2_prepare($conn1, $itxSql);

// 2.2 RAJUT
$rajutSql = "
    SELECT
        SUMMARIZEDDESCRIPTION AS BENANG,
        CODE AS PO_GREIGE
    FROM ITXVIEW_RAJUT
    WHERE
        SUBCODE01 = ?
        AND SUBCODE02 = ?
        AND SUBCODE03 = ?
        AND SUBCODE04 = ?
        AND ORIGDLVSALORDLINESALORDERCODE = ?
        AND ITEMTYPEAFICODE IN ('KGF','FKG')
";
$rajutStmt = db2_prepare($conn1, $rajutSql);

// 2.3 BOOKING NEW (READY)
$readySql = "
    SELECT
        PROJECTCODE AS PO_GREIGE,
        SUMMARIZEDDESCRIPTION AS BENANG
    FROM ITXVIEW_BOOKING_NEW
    WHERE SALESORDERCODE = ? AND ORDERLINE = ?
";
$readyStmt = db2_prepare($conn1, $readySql);

// 2.4 BOOKING BLM READY (dipakai untuk ADDITIONALDATA..)
$blmSql = "
    SELECT
        ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
        COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
    FROM ITXVIEW_BOOKING_BLM_READY
    WHERE
        SUBCODE01 = ?
        AND SUBCODE02 = ?
        AND SUBCODE03 = ?
        AND SUBCODE04 = ?
        AND ORIGDLVSALORDLINESALORDERCODE = ?
        AND ITEMTYPEAFICODE IN ('KGF','FKG')
";
$blmStmt = db2_prepare($conn1, $blmSql);

// =====================
// 3. HELPER FOR PREPARED FETCH
// =====================
function fetchPreparedRow($stmt, array $params, array $default = ['BENANG' => '', 'PO_GREIGE' => ''])
{
    if (!$stmt) return $default;
    if (!db2_execute($stmt, $params)) {
        return $default;
    }
    $row = db2_fetch_assoc($stmt);
    return $row ?: $default;
}

$data = [];

while ($row = db2_fetch_assoc($mainStmt)) {

    $orderCode = $row['SALESORDERCODE'];
    $orderLine = $row['ORDERLINE'];

    // ========== DETAIL ITXVIEWBONORDER ==========
    $itx = fetchPreparedRow($itxStmt, [$orderCode, $orderLine], []);
    if (!$itx) {
        // kalau tidak ketemu, skip baris ini
        continue;
    }

    // Tentukan subcode04 untuk RAJUT/BOOKING
    $subcode04 = ($itx['ITEMTYPEAFICODE'] === 'KFF') ? $itx['RESERVATION_SUBCODE04'] : $itx['SUBCODE04'];

    $isAKJorAKW = in_array($itx['AKJ'], ['AKJ', 'AKW'], true);

    // ========== RAJUT ==========
    if ($isAKJorAKW || !empty($itx['ADDITIONALDATA']) || !empty($itx['LEGACYORDER'])) {
        $d_rajut = ['BENANG' => '', 'PO_GREIGE' => ''];
    } else {
        $d_rajut = fetchPreparedRow(
            $rajutStmt,
            [
                $itx['SUBCODE01'],
                $itx['SUBCODE02'],
                $itx['SUBCODE03'],
                $subcode04,
                $orderCode
            ]
        );
    }

    // ========== READY (BOOKING_NEW) ==========
    if ($isAKJorAKW || !empty($itx['ADDITIONALDATA'])) {
        $d_ready = ['BENANG' => '', 'PO_GREIGE' => ''];
    } else {
        $d_ready = fetchPreparedRow($readyStmt, [$orderCode, $orderLine]);
    }

    // ========== BLM READY (ADDITIONALDATA..6A) ==========
    $benang_blm = [];
    $po_blm = [];

    $additionalFields = [
        'ADDITIONALDATA',
        'ADDITIONALDATA2',
        'ADDITIONALDATA3',
        'ADDITIONALDATA4',
        'ADDITIONALDATA5',
        'ADDITIONALDATA6',
        'ADDITIONALDATA6A',
    ];

    if (!$isAKJorAKW) {
        foreach ($additionalFields as $field) {
            $val = strtoupper($itx[$field] ?? '');
            if (empty($val)) continue;

            $res = fetchPreparedRow(
                $blmStmt,
                [
                    $itx['SUBCODE01'],
                    $itx['SUBCODE02'],
                    $itx['SUBCODE03'],
                    $subcode04,
                    $val
                ]
            );

            if (!empty($res['BENANG'])) {
                $benang_blm[] = htmlspecialchars($res['BENANG']);
            }
            if (!empty($res['PO_GREIGE'])) {
                $po_blm[] = htmlspecialchars($res['PO_GREIGE']);
            }else if (empty($res['PO_GREIGE'] && !empty($val))) {
                $po_blm[] = htmlspecialchars($val);
            }
        }
    }

    // ========== GABUNG BENANG & PO_GREIGE ==========
    $benangList = array_filter([
        htmlspecialchars($d_rajut['BENANG'] ?? ''),
        htmlspecialchars($d_ready['BENANG'] ?? ''),
        ...$benang_blm
    ]);

    $poList = array_filter([
        htmlspecialchars($d_rajut['PO_GREIGE'] ?? ''),
        htmlspecialchars($d_ready['PO_GREIGE'] ?? ''),
        ...$po_blm
    ]);

    $data[] = [
        'SALESORDERCODE' => $orderCode,
        'NO_PO'          => $row['NO_PO'] ?? '',
        'LEGALNAME1'     => $row['LEGALNAME1'] ?? '',
        'JENIS_KAIN'     => $row['JENIS_KAIN'] ?? '',
        'AKJ'            => $row['AKJ'] ?? '',
        'ITEMCODE'       => $row['ITEMCODE'] ?? '',
        'NOTETAS'        => $row['NOTETAS'] ?? '',
        'GRAMASI'        => isset($row['GRAMASI']) ? (float) $row['GRAMASI'] : null,
        'LEBAR'          => isset($row['LEBAR']) ? (float) $row['LEBAR'] : null,
        'COLOR_STANDARD' => $row['COLOR_STANDARD'] ?? '',
        'WARNA'          => $row['WARNA'] ?? '',
        'KODE_WARNA'     => $row['KODE_WARNA'] ?? '',
        'COLORREMARKS'   => $row['COLORREMARKS'] ?? '',
        // di sini pakai string HTML siap tampil, bukan array mentah
        'BENANG'         => implode('<br><br>', $benangList),
        'PO_GREIGE'      => implode('<br><br>', $poList),
    ];
}

echo json_encode(['success' => true, 'data' => $data]);
?>
