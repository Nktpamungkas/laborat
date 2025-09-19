<?php
include "../../koneksi.php";
header('Content-Type: application/json; charset=utf-8');

// Terima code via GET/POST
$code = isset($_GET['code']) ? $_GET['code'] : (isset($_POST['code']) ? $_POST['code'] : '');
$code = strtoupper(trim((string)$code));

if ($code === '') {
    echo json_encode(['ok' => false, 'error' => 'CODE kosong', 'lines' => []]);
    exit;
}

$sql = "
SELECT
    ORDERLINE,
    /* Ambil nilai akhir per ORDERLINE */
    MAX(RevisiC)   AS RevisiC,
    MAX(RevisiC1)  AS RevisiC1,
    MAX(RevisiC2)  AS RevisiC2,
    MAX(RevisiC3)  AS RevisiC3,
    MAX(RevisiC4)  AS RevisiC4,
    MAX(Revisid)   AS Revisid,
    MAX(Revisi2)  AS Revisi2,
    MAX(Revisi3)  AS Revisi3,
    MAX(Revisi4)  AS Revisi4,
    MAX(Revisi5)  AS Revisi5,
    MAX(Revisi1Date) AS Revisi1Date,
    MAX(Revisi2Date) AS Revisi2Date,
    MAX(Revisi3Date) AS Revisi3Date,
    MAX(Revisi4Date) AS Revisi4Date,
    MAX(Revisi5Date) AS Revisi5Date
FROM (
    SELECT
        i.SALESORDERCODE,
        i.ORDERLINE,

        /* --------- Kategori (C-group) pakai label dari OPTIONS --------- */
        CASE
          WHEN sc.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc.VALUESTRING || '=([^;]*)',1,1,'',1)
        END AS RevisiC,

        CASE
          WHEN sc1.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc1.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc1.VALUESTRING || '=([^;]*)',1,1,'',1)
        END AS RevisiC1,

        CASE
          WHEN sc2.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc2.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc2.VALUESTRING || '=([^;]*)',1,1,'',1)
        END AS RevisiC2,

        CASE
          WHEN sc3.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc3.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc3.VALUESTRING || '=([^;]*)',1,1,'',1)
        END AS RevisiC3,

        CASE
          WHEN sc4.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
            AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || sc4.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || sc4.VALUESTRING || '=([^;]*)',1,1,'',1)
        END AS RevisiC4,

        /* --------- Detail (D-group) langsung VALUESTRING --------- */
        sd.VALUESTRING  AS Revisid,
        sd1.VALUESTRING AS Revisi2,
        sd2.VALUESTRING AS Revisi3,
        sd3.VALUESTRING AS Revisi4,
        sd4.VALUESTRING AS Revisi5,

        /* --------- Tanggal --------- */
        sdt1.VALUEDATE AS Revisi1Date,
        sdt2.VALUEDATE AS Revisi2Date,
        sdt3.VALUEDATE AS Revisi3Date,
        sdt4.VALUEDATE AS Revisi4Date,
        sdt5.VALUEDATE AS Revisi5Date

    FROM ITXVIEWBONORDER i

    /* Join AD di LEVEL LINE - gunakan ABSUNIQUEID_SALESORDERLINE */
    LEFT JOIN ADSTORAGE sc   ON sc.UNIQUEID  = i.ABSUNIQUEID_SALESORDERLINE AND sc.FIELDNAME  = 'RevisiC'
    LEFT JOIN ADADDITIONALDATA adC  ON adC.NAME  = sc.FIELDNAME

    LEFT JOIN ADSTORAGE sc1  ON sc1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc1.FIELDNAME = 'RevisiC1'
    LEFT JOIN ADADDITIONALDATA adC1 ON adC1.NAME = sc1.FIELDNAME

    LEFT JOIN ADSTORAGE sc2  ON sc2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc2.FIELDNAME = 'RevisiC2'
    LEFT JOIN ADADDITIONALDATA adC2 ON adC2.NAME = sc2.FIELDNAME

    LEFT JOIN ADSTORAGE sc3  ON sc3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc3.FIELDNAME = 'RevisiC3'
    LEFT JOIN ADADDITIONALDATA adC3 ON adC3.NAME = sc3.FIELDNAME

    LEFT JOIN ADSTORAGE sc4  ON sc4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sc4.FIELDNAME = 'RevisiC4'
    LEFT JOIN ADADDITIONALDATA adC4 ON adC4.NAME = sc4.FIELDNAME

    LEFT JOIN ADSTORAGE sd   ON sd.UNIQUEID  = i.ABSUNIQUEID_SALESORDERLINE AND sd.FIELDNAME  = 'Revisid'
    LEFT JOIN ADSTORAGE sd1  ON sd1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd1.FIELDNAME = 'Revisi2'
    LEFT JOIN ADSTORAGE sd2  ON sd2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd2.FIELDNAME = 'Revisi3'
    LEFT JOIN ADSTORAGE sd3  ON sd3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd3.FIELDNAME = 'Revisi4'
    LEFT JOIN ADSTORAGE sd4  ON sd4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sd4.FIELDNAME = 'Revisi5'

    LEFT JOIN ADSTORAGE sdt1 ON sdt1.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt1.FIELDNAME = 'Revisi1Date'
    LEFT JOIN ADSTORAGE sdt2 ON sdt2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt2.FIELDNAME = 'Revisi2Date'
    LEFT JOIN ADSTORAGE sdt3 ON sdt3.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt3.FIELDNAME = 'Revisi3Date'
    LEFT JOIN ADSTORAGE sdt4 ON sdt4.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt4.FIELDNAME = 'Revisi4Date'
    LEFT JOIN ADSTORAGE sdt5 ON sdt5.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND sdt5.FIELDNAME = 'Revisi5Date'

    WHERE i.SALESORDERCODE = ?
) X
GROUP BY ORDERLINE
/* Tampilkan hanya yang punya isi kategori/detail (minimal salah satu) */
HAVING COALESCE(
         NULLIF(TRIM(MAX(RevisiC)) , ''),
         NULLIF(TRIM(MAX(RevisiC1)), ''),
         NULLIF(TRIM(MAX(RevisiC2)), ''),
         NULLIF(TRIM(MAX(RevisiC3)), ''),
         NULLIF(TRIM(MAX(RevisiC4)), ''),
         NULLIF(TRIM(MAX(Revisid)) , ''),
         NULLIF(TRIM(MAX(Revisi2)), ''),
         NULLIF(TRIM(MAX(Revisi3)), ''),
         NULLIF(TRIM(MAX(Revisi4)), ''),
         NULLIF(TRIM(MAX(Revisi5)), '')
       ) IS NOT NULL
ORDER BY ORDERLINE
";

$stmt = db2_prepare($conn1, $sql);

/* Penting: db2_bind_param butuh variabel by-ref, bukan literal "code" */
$codeVar = $code;
db2_bind_param($stmt, 1, "codeVar", DB2_PARAM_IN);

$ok = db2_execute($stmt);

$lines = [];
if ($ok) {
    while ($r = db2_fetch_assoc($stmt)) {
        $lines[] = [
            'orderline'   => (string)($r['ORDERLINE'] ?? ''),

            // C-group → revisic, revisic1..revisic4
            'revisic'     => (string)($r['REVISIC']  ?? ''),
            'revisic1'    => (string)($r['REVISIC1'] ?? ''),
            'revisic2'    => (string)($r['REVISIC2'] ?? ''),
            'revisic3'    => (string)($r['REVISIC3'] ?? ''),
            'revisic4'    => (string)($r['REVISIC4'] ?? ''),

            // D-group → revisid, revisi2..revisi5
            'revisid'     => (string)($r['REVISID']  ?? ''),
            'revisi2'    => (string)($r['REVISI2'] ?? ''),
            'revisi3'    => (string)($r['REVISI3'] ?? ''),
            'revisi4'    => (string)($r['REVISI4'] ?? ''),
            'revisi5'    => (string)($r['REVISI5'] ?? ''),

            // Dates (tetap, meski compare kita abaikan)
            'revisi1date' => (string)($r['REVISI1DATE'] ?? ''),
            'revisi2date' => (string)($r['REVISI2DATE'] ?? ''),
            'revisi3date' => (string)($r['REVISI3DATE'] ?? ''),
            'revisi4date' => (string)($r['REVISI4DATE'] ?? ''),
            'revisi5date' => (string)($r['REVISI5DATE'] ?? ''),
        ];
    }
    echo json_encode(['ok' => true, 'lines' => $lines], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['ok' => false, 'error' => 'Query gagal dieksekusi', 'lines' => []]);
}
