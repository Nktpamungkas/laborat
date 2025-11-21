<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

function json_error($m){
    echo json_encode(["ok"=>false,"message"=>$m]); 
    exit;
}

$code = isset($_GET['code']) ? trim($_GET['code']) : '';
if ($code === '') {
    json_error('Kode kosong.');
}

// ---- QUERY KE DB2, 1 CODE SAJA ----
$sql = "
WITH base AS (
    SELECT
        TRIM(isa.CODE)                     AS \"CODE\",
        ip.LANGGANAN || ip.BUYER           AS \"CUSTOMER\",
        isa.TGL_APPROVEDRMP                AS \"TGL_APPROVE_RMP\",

        /* --- Grup RevisiC/Revisi2/... dari ad*.OPTIONS --- */
        CASE WHEN aC.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || aC.VALUESTRING || '=')
            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || aC.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"RevisiC\",
        CASE WHEN a2.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a2.VALUESTRING || '=')
            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi2\",
        CASE WHEN a3.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a3.VALUESTRING || '=')
            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi3\",
        CASE WHEN a4.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a4.VALUESTRING || '=')
            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi4\",
        CASE WHEN a5.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a5.VALUESTRING || '=')
            THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a5.VALUESTRING || '=([^;]*)',1,1,'',1) END AS \"Revisi5\",

        /* --- Grup RevisiN/DRevisi* langsung VALUESTRING --- */
        n1.VALUESTRING AS \"RevisiN\",
        n2.VALUESTRING AS \"DRevisi2\",
        n3.VALUESTRING AS \"DRevisi3\",
        n4.VALUESTRING AS \"DRevisi4\",
        n5.VALUESTRING AS \"DRevisi5\",

        /* --- Grup Tanggal Revisi* --- */
        dt1.VALUEDATE AS \"Revisi1Date\",
        dt2.VALUEDATE AS \"Revisi2Date\",
        dt3.VALUEDATE AS \"Revisi3Date\",
        dt4.VALUEDATE AS \"Revisi4Date\",
        dt5.VALUEDATE AS \"Revisi5Date\"

    FROM ITXVIEW_SALESORDER_APPROVED isa
    LEFT JOIN SALESORDER s ON s.CODE = isa.CODE

    LEFT JOIN ADSTORAGE aC  ON aC.UNIQUEID = s.ABSUNIQUEID AND aC.FIELDNAME = 'RevisiC'
    LEFT JOIN ADADDITIONALDATA adC ON adC.NAME = aC.FIELDNAME
    LEFT JOIN ADSTORAGE a2  ON a2.UNIQUEID = s.ABSUNIQUEID AND a2.FIELDNAME = 'Revisi2'
    LEFT JOIN ADADDITIONALDATA ad2 ON ad2.NAME = a2.FIELDNAME
    LEFT JOIN ADSTORAGE a3  ON a3.UNIQUEID = s.ABSUNIQUEID AND a3.FIELDNAME = 'Revisi3'
    LEFT JOIN ADADDITIONALDATA ad3 ON ad3.NAME = a3.FIELDNAME
    LEFT JOIN ADSTORAGE a4  ON a4.UNIQUEID = s.ABSUNIQUEID AND a4.FIELDNAME = 'Revisi4'
    LEFT JOIN ADADDITIONALDATA ad4 ON ad4.NAME = a4.FIELDNAME
    LEFT JOIN ADSTORAGE a5  ON a5.UNIQUEID = s.ABSUNIQUEID AND a5.FIELDNAME = 'Revisi5'
    LEFT JOIN ADADDITIONALDATA ad5 ON ad5.NAME = a5.FIELDNAME

    /* nilai detail */
    LEFT JOIN ADSTORAGE n1 ON n1.UNIQUEID = s.ABSUNIQUEID AND n1.FIELDNAME = 'RevisiN'
    LEFT JOIN ADSTORAGE n2 ON n2.UNIQUEID = s.ABSUNIQUEID AND n2.FIELDNAME = 'DRevisi2'
    LEFT JOIN ADSTORAGE n3 ON n3.UNIQUEID = s.ABSUNIQUEID AND n3.FIELDNAME = 'DRevisi3'
    LEFT JOIN ADSTORAGE n4 ON n4.UNIQUEID = s.ABSUNIQUEID AND n4.FIELDNAME = 'DRevisi4'
    LEFT JOIN ADSTORAGE n5 ON n5.UNIQUEID = s.ABSUNIQUEID AND n5.FIELDNAME = 'DRevisi5'

    /* tanggal detail */
    LEFT JOIN ADSTORAGE dt1 ON dt1.UNIQUEID = s.ABSUNIQUEID AND dt1.FIELDNAME = 'Revisi1Date'
    LEFT JOIN ADSTORAGE dt2 ON dt2.UNIQUEID = s.ABSUNIQUEID AND dt2.FIELDNAME = 'Revisi2Date'
    LEFT JOIN ADSTORAGE dt3 ON dt3.UNIQUEID = s.ABSUNIQUEID AND dt3.FIELDNAME = 'Revisi3Date'
    LEFT JOIN ADSTORAGE dt4 ON dt4.UNIQUEID = s.ABSUNIQUEID AND dt4.FIELDNAME = 'Revisi4Date'
    LEFT JOIN ADSTORAGE dt5 ON dt5.UNIQUEID = s.ABSUNIQUEID AND dt5.FIELDNAME = 'Revisi5Date'

    LEFT JOIN ITXVIEW_PELANGGAN ip
        ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
       AND ip.CODE = s.CODE

    WHERE isa.APPROVEDRMP IS NOT NULL 
      AND isa.TGL_APPROVEDRMP IS NOT NULL
      AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
      AND TRIM(isa.CODE) = ?
),
ranked AS (
    SELECT b.*,
        ROW_NUMBER() OVER (
            PARTITION BY b.\"CODE\"
            ORDER BY (b.\"TGL_APPROVE_RMP\" IS NULL) ASC, b.\"TGL_APPROVE_RMP\" DESC
        ) AS rn
    FROM base b
)
SELECT
    \"CODE\",
    \"RevisiC\",\"Revisi2\",\"Revisi3\",\"Revisi4\",\"Revisi5\",
    \"RevisiN\",\"DRevisi2\",\"DRevisi3\",\"DRevisi4\",\"DRevisi5\",
    \"Revisi1Date\",\"Revisi2Date\",\"Revisi3Date\",\"Revisi4Date\",\"Revisi5Date\",
    COALESCE(
        NULLIF(TRIM(\"DRevisi5\"), ''),
        NULLIF(TRIM(\"DRevisi4\"), ''),
        NULLIF(TRIM(\"DRevisi3\"), ''),
        NULLIF(TRIM(\"DRevisi2\"), ''),
        NULLIF(TRIM(\"RevisiN\"),  '')
    ) AS \"REVISIN_LAST\",
    COALESCE(
        NULLIF(TRIM(\"Revisi5\"), ''),
        NULLIF(TRIM(\"Revisi4\"), ''),
        NULLIF(TRIM(\"Revisi3\"), ''),
        NULLIF(TRIM(\"Revisi2\"), ''),
        NULLIF(TRIM(\"RevisiC\"), '')
    ) AS \"REVISIC_LAST\"
FROM ranked
WHERE rn = 1
";

$stmt = db2_prepare($conn1, $sql);
if (!$stmt) {
    json_error('Prepare DB2 gagal.');
}

if (!db2_execute($stmt, [$code])) {
    json_error('Execute DB2 gagal.');
}

$row = db2_fetch_assoc($stmt);
if (!$row) {
    echo json_encode([
        "ok"          => true,
        "items"       => [],
        "revin_last"  => "",
        "revisic_last"=> ""
    ]);
    exit;
}

// Susun item untuk tabel modal
$items = [];

// Revisi 1
$cat1 = trim($row['RevisiC'] ?? '');
$det1 = trim($row['RevisiN'] ?? '');
$dt1  = trim($row['Revisi1Date'] ?? '');
if ($det1 !== '') {
    $items[] = ['cat' => $cat1, 'det' => $det1, 'dt' => $dt1];
}

// Revisi 2
$cat2 = trim($row['Revisi2'] ?? '');
$det2 = trim($row['DRevisi2'] ?? '');
$dt2  = trim($row['Revisi2Date'] ?? '');
if ($det2 !== '') {
    $items[] = ['cat' => $cat2, 'det' => $det2, 'dt' => $dt2];
}

// Revisi 3
$cat3 = trim($row['Revisi3'] ?? '');
$det3 = trim($row['DRevisi3'] ?? '');
$dt3  = trim($row['Revisi3Date'] ?? '');
if ($det3 !== '') {
    $items[] = ['cat' => $cat3, 'det' => $det3, 'dt' => $dt3];
}

// Revisi 4
$cat4 = trim($row['Revisi4'] ?? '');
$det4 = trim($row['DRevisi4'] ?? '');
$dt4  = trim($row['Revisi4Date'] ?? '');
if ($det4 !== '') {
    $items[] = ['cat' => $cat4, 'det' => $det4, 'dt' => $dt4];
}

// Revisi 5
$cat5 = trim($row['Revisi5'] ?? '');
$det5 = trim($row['DRevisi5'] ?? '');
$dt5  = trim($row['Revisi5Date'] ?? '');
if ($det5 !== '') {
    $items[] = ['cat' => $cat5, 'det' => $det5, 'dt' => $dt5];
}

echo json_encode([
    "ok"           => true,
    "items"        => $items,
    "revin_last"   => trim($row['REVISIN_LAST']  ?? ''),
    "revisic_last" => trim($row['REVISIC_LAST'] ?? '')
]);