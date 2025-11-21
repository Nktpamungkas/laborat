<?php
include "koneksi.php";
require_once __DIR__ . "/lib/revisi_compare.php";

/**
 * Toggle: ikutkan perbandingan LINE (orderline).
 * TRUE = selain header, cek snapshot line juga
 */
$ENABLE_LINE_DIFF = true;

/**
 * Helper ambil revisi per ORDERLINE dari DB2 (SESUAI Approved_get_order_detail_revision_json.php)
 * return: [ orderline => [revisic,revisi2..5,revisin,drevisi2..5,revisi1date..5date] ]
 */
function get_db2_lines($conn1, $codeUpper) {
    $sql = "
    SELECT
        ORDERLINE,
        MAX(RevisiC)     AS RevisiC,
        MAX(RevisiC1)    AS RevisiC1,
        MAX(RevisiC2)    AS RevisiC2,
        MAX(RevisiC3)    AS RevisiC3,
        MAX(RevisiC4)    AS RevisiC4,
        MAX(Revisid)     AS Revisid,
        MAX(Revisi2)    AS Revisi2,
        MAX(Revisi3)    AS Revisi3,
        MAX(Revisi4)    AS Revisi4,
        MAX(Revisi5)    AS Revisi5,
        MAX(Revisi1Date) AS Revisi1Date,
        MAX(Revisi2Date) AS Revisi2Date,
        MAX(Revisi3Date) AS Revisi3Date,
        MAX(Revisi4Date) AS Revisi4Date,
        MAX(Revisi5Date) AS Revisi5Date
    FROM (
        SELECT
            i.SALESORDERCODE,
            i.ORDERLINE,
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

            sd.VALUESTRING  AS Revisid,
            sd1.VALUESTRING AS Revisi2,
            sd2.VALUESTRING AS Revisi3,
            sd3.VALUESTRING AS Revisi4,
            sd4.VALUESTRING AS Revisi5,

            sdt1.VALUEDATE AS Revisi1Date,
            sdt2.VALUEDATE AS Revisi2Date,
            sdt3.VALUEDATE AS Revisi3Date,
            sdt4.VALUEDATE AS Revisi4Date,
            sdt5.VALUEDATE AS Revisi5Date

        FROM ITXVIEWBONORDER i
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
    $codeVar = $codeUpper;
    db2_bind_param($stmt, 1, "codeVar", DB2_PARAM_IN);
    $ok = db2_execute($stmt);

    // $out = [];
    // if ($ok) {
    //     while ($r = db2_fetch_assoc($stmt)) {
    //         $ol = (string)($r['ORDERLINE'] ?? '');
    //         $out[$ol] = [
    //             'revisic'     => (string)($r['REVISIC']  ?? ''),
    //             'revisi2'     => (string)($r['REVISIC1'] ?? ''),
    //             'revisi3'     => (string)($r['REVISIC2'] ?? ''),
    //             'revisi4'     => (string)($r['REVISIC3'] ?? ''),
    //             'revisi5'     => (string)($r['REVISIC4'] ?? ''),
    //             'revisin'     => (string)($r['REVISID']  ?? ''),
    //             'drevisi2'    => (string)($r['REVISI2'] ?? ''),
    //             'drevisi3'    => (string)($r['REVISI3'] ?? ''),
    //             'drevisi4'    => (string)($r['REVISI4'] ?? ''),
    //             'drevisi5'    => (string)($r['REVISI5'] ?? ''),
    //             'revisi1date' => (string)($r['REVISI1DATE'] ?? ''),
    //             'revisi2date' => (string)($r['REVISI2DATE'] ?? ''),
    //             'revisi3date' => (string)($r['REVISI3DATE'] ?? ''),
    //             'revisi4date' => (string)($r['REVISI4DATE'] ?? ''),
    //             'revisi5date' => (string)($r['REVISI5DATE'] ?? ''),
    //         ];
    //     }
    // }
    // return $out;

    $lines = [];
    if ($ok) {
        while ($r = db2_fetch_assoc($stmt)) {
            $lines[] = [
                'orderline'   => (string)($r['ORDERLINE'] ?? ''),
                'revisic'     => (string)($r['REVISIC']  ?? ''),
                'revisic1'    => (string)($r['REVISIC1'] ?? ''),
                'revisic2'    => (string)($r['REVISIC2'] ?? ''),
                'revisic3'    => (string)($r['REVISIC3'] ?? ''),
                'revisic4'    => (string)($r['REVISIC4'] ?? ''),

                'revisid'     => (string)($r['REVISID']  ?? ''),
                'revisi2'    => (string)($r['REVISI2'] ?? ''),
                'revisi3'    => (string)($r['REVISI3'] ?? ''),
                'revisi4'    => (string)($r['REVISI4'] ?? ''),
                'revisi5'    => (string)($r['REVISI5'] ?? ''),

                'revisi1date' => (string)($r['REVISI1DATE'] ?? ''),
                'revisi2date' => (string)($r['REVISI2DATE'] ?? ''),
                'revisi3date' => (string)($r['REVISI3DATE'] ?? ''),
                'revisi4date' => (string)($r['REVISI4DATE'] ?? ''),
                'revisi5date' => (string)($r['REVISI5DATE'] ?? ''),
            ];
        }
    }
    return $lines;
}

/** Wrapper opsional biar “kayak versi lama” */
function has_line_diff($conn1, $codeUpper) {
    // pakai snapshot line dari MySQL yang SUDAH di-load di atas
    global $lastLinesByCode;

    $codeKey    = strtoupper(trim($codeUpper));
    $mysqlLines = $lastLinesByCode[$codeKey] ?? [];

    // aturan: kalau MySQL belum punya snapshot line utk code ini -> JANGAN dianggap beda
    if (empty($mysqlLines)) {
        return false;
    }

    // baru panggil DB2 kalau memang ada snapshot di MySQL
    $db2Lines = get_db2_lines($conn1, $codeKey);
    if (empty($db2Lines)) {
        // kalau gagal ambil dari DB2, supaya aman kita anggap "tidak beda"
        // (boleh diubah jadi true kalau kamu lebih suka "konservatif")
        return false;
    }

    return linesDiffer($db2Lines, $mysqlLines);
}


/* --------------- 1) Snapshot MySQL (header terakhir per code) --------------- */
$sqlSnap = "
SELECT a.*
FROM approval_bon_order a
JOIN (
  SELECT code, MAX(id) AS max_id
  FROM approval_bon_order
  WHERE is_revision = 1
  GROUP BY code
) m ON m.max_id = a.id
WHERE a.is_revision = 1
";
$resSnap = mysqli_query($con, $sqlSnap);

$lastMySQLByCode = [];
if ($resSnap) {
    while ($r = mysqli_fetch_assoc($resSnap)) {
        $lastMySQLByCode[strtoupper(trim($r['code']))] = $r;
    }
}

/* --------------- (opsional) siapkan snapshot line utk approvedTable (riwayat) --------------- */
$lastLinesByCode = [];
$qLines = mysqli_query($con, "
SELECT lr.*
FROM line_revision lr
JOIN approval_bon_order a ON a.id = lr.approval_id
JOIN (
  SELECT code, MAX(id) AS max_id
  FROM approval_bon_order
  WHERE is_revision = 1
  GROUP BY code
) m ON m.max_id = a.id
WHERE a.is_revision = 1
ORDER BY lr.code, lr.orderline
");
if ($qLines) {
    while ($r = mysqli_fetch_assoc($qLines)) {
        $codeKey = strtoupper(trim($r['code']));
        if (!isset($lastLinesByCode[$codeKey])) $lastLinesByCode[$codeKey] = [];
        $lastLinesByCode[$codeKey][] = $r;
    }
}

/* --------------- 2) Ambil kandidat “siap approval” dari DB2 (header) --------------- */
$sqlTBO = "
    WITH base AS (
        SELECT
      s.SALESORDERCODE                 AS CODE,
      ip.LANGGANAN || ip.BUYER         AS CUSTOMER,
      a.VALUEDATE                      AS TGL_APPROVE_RMP,
      aa.VALUETIMESTAMP                AS APPROVAL_RMP_DATETIME,
      
      CASE WHEN a10.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
          AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a10.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a10.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC,
      CASE WHEN a11.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
          AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a11.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a11.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi2,
      CASE WHEN a12.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
          AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a12.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a12.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi3,
      CASE WHEN a13.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
          AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a13.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a13.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi4,
      CASE WHEN a14.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
          AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a14.VALUESTRING || '=')
          THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a14.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi5,
      
      a15.VALUESTRING AS RevisiN,
      a16.VALUESTRING AS DRevisi2,
      a17.VALUESTRING AS DRevisi3,
      a18.VALUESTRING AS DRevisi4,
      a19.VALUESTRING AS DRevisi5,
      
      dt1.VALUEDATE AS Revisi1Date,
      dt2.VALUEDATE AS Revisi2Date,
      dt3.VALUEDATE AS Revisi3Date,
      dt4.VALUEDATE AS Revisi4Date,
      dt5.VALUEDATE AS Revisi5Date
      
      FROM
          SALESORDERLINE s
      LEFT JOIN SALESORDER s2 ON
          s2.CODE = s.SALESORDERCODE
      LEFT JOIN ADSTORAGE a ON
              s2.ABSUNIQUEID = a.UNIQUEID
              AND a.FIELDNAME = 'ApprovalDate'
      LEFT JOIN ADSTORAGE aa ON
              s2.ABSUNIQUEID = aa.UNIQUEID
              AND aa.FIELDNAME = 'ApprovalRMPDateTime'     
      LEFT JOIN ADSTORAGE a10 ON s2.ABSUNIQUEID = a10.UNIQUEID AND a10.FIELDNAME = 'RevisiC'
      LEFT JOIN ADADDITIONALDATA adC ON adC.NAME = a10.FIELDNAME
      LEFT JOIN ADSTORAGE a11 ON s2.ABSUNIQUEID = a11.UNIQUEID AND a11.FIELDNAME = 'Revisi2'
      LEFT JOIN ADSTORAGE a12 ON s2.ABSUNIQUEID = a12.UNIQUEID AND a12.FIELDNAME = 'Revisi3'
      LEFT JOIN ADSTORAGE a13 ON s2.ABSUNIQUEID = a13.UNIQUEID AND a13.FIELDNAME = 'Revisi4'
      LEFT JOIN ADSTORAGE a14 ON s2.ABSUNIQUEID = a14.UNIQUEID AND a14.FIELDNAME = 'Revisi5'
      LEFT JOIN ADSTORAGE a15 ON s2.ABSUNIQUEID = a15.UNIQUEID AND a15.FIELDNAME = 'RevisiN'
      LEFT JOIN ADSTORAGE a16 ON s2.ABSUNIQUEID = a16.UNIQUEID AND a16.FIELDNAME = 'DRevisi2'
      LEFT JOIN ADSTORAGE a17 ON s2.ABSUNIQUEID = a17.UNIQUEID AND a17.FIELDNAME = 'DRevisi3'
      LEFT JOIN ADSTORAGE a18 ON s2.ABSUNIQUEID = a18.UNIQUEID AND a18.FIELDNAME = 'DRevisi4'
      LEFT JOIN ADSTORAGE a19 ON s2.ABSUNIQUEID = a19.UNIQUEID AND a19.FIELDNAME = 'DRevisi5'
      LEFT JOIN ADSTORAGE dt1 ON dt1.UNIQUEID = s2.ABSUNIQUEID AND dt1.FIELDNAME = 'Revisi1Date'
      LEFT JOIN ADSTORAGE dt2 ON dt2.UNIQUEID = s2.ABSUNIQUEID AND dt2.FIELDNAME = 'Revisi2Date'
      LEFT JOIN ADSTORAGE dt3 ON dt3.UNIQUEID = s2.ABSUNIQUEID AND dt3.FIELDNAME = 'Revisi3Date'
      LEFT JOIN ADSTORAGE dt4 ON dt4.UNIQUEID = s2.ABSUNIQUEID AND dt4.FIELDNAME = 'Revisi4Date'
      LEFT JOIN ADSTORAGE dt5 ON dt5.UNIQUEID = s2.ABSUNIQUEID AND dt5.FIELDNAME = 'Revisi5Date'  
      LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s2.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s2.CODE
      
      WHERE a.VALUEDATE IS NOT NULL
            AND DATE(s2.CREATIONDATETIME) > DATE('2025-06-01')
    ),
    ranked AS (
        SELECT b.*, ROW_NUMBER() OVER
          (PARTITION BY b.CODE ORDER BY (b.TGL_APPROVE_RMP IS NULL) ASC, b.TGL_APPROVE_RMP DESC) AS rn
        FROM base b
    ),
    line_revisi AS (
        SELECT 
            *
        FROM
            (
            SELECT
                DISTINCT 
        s.SALESORDERCODE AS CODE,
        s.LINESTATUS,
                CASE
                    WHEN a5.VALUESTRING <> '0'
                    OR a.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a6.VALUESTRING <> '0'
                    OR a1.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a7.VALUESTRING <> '0'
                    OR a2.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a8.VALUESTRING <> '0'
                    OR a3.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a9.VALUESTRING <> '0'
                    OR a4.VALUESTRING IS NOT NULL THEN 'REVISI'
                    ELSE NULL
                END AS REVISI_LINE,
                CASE
                    WHEN a10.VALUESTRING <> '0'
                    OR a15.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a11.VALUESTRING <> '0'
                    OR a16.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a12.VALUESTRING <> '0'
                    OR a17.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a13.VALUESTRING <> '0'
                    OR a18.VALUESTRING IS NOT NULL THEN 'REVISI'
                    WHEN a14.VALUESTRING <> '0'
                    OR a19.VALUESTRING IS NOT NULL THEN 'REVISI'
                    ELSE NULL
                END AS REVISI_HEADER
            FROM
                SALESORDERLINE s
            LEFT JOIN SALESORDER s2 ON
                s2.CODE = s.SALESORDERCODE
            LEFT JOIN ADSTORAGE a ON
                s.ABSUNIQUEID = a.UNIQUEID
                AND a.FIELDNAME = 'Revisid'
            LEFT JOIN ADSTORAGE a1 ON
                s.ABSUNIQUEID = a1.UNIQUEID
                AND a1.FIELDNAME = 'Revisi2'
            LEFT JOIN ADSTORAGE a2 ON
                s.ABSUNIQUEID = a2.UNIQUEID
                AND a2.FIELDNAME = 'Revisi3'
            LEFT JOIN ADSTORAGE a3 ON
                s.ABSUNIQUEID = a3.UNIQUEID
                AND a3.FIELDNAME = 'Revisi4'
            LEFT JOIN ADSTORAGE a4 ON
                s.ABSUNIQUEID = a4.UNIQUEID
                AND a4.FIELDNAME = 'Revisi5'
            LEFT JOIN ADSTORAGE a5 ON
                s.ABSUNIQUEID = a5.UNIQUEID
                AND a5.FIELDNAME = 'RevisiC'
            LEFT JOIN ADSTORAGE a6 ON
                s.ABSUNIQUEID = a6.UNIQUEID
                AND a6.FIELDNAME = 'RevisiC1'
            LEFT JOIN ADSTORAGE a7 ON
                s.ABSUNIQUEID = a7.UNIQUEID
                AND a7.FIELDNAME = 'RevisiC2'
            LEFT JOIN ADSTORAGE a8 ON
                s.ABSUNIQUEID = a8.UNIQUEID
                AND a8.FIELDNAME = 'RevisiC3'
            LEFT JOIN ADSTORAGE a9 ON
                s.ABSUNIQUEID = a9.UNIQUEID
                AND a9.FIELDNAME = 'RevisiC4'
            LEFT JOIN ADSTORAGE a10 ON
                s2.ABSUNIQUEID = a10.UNIQUEID
                AND a10.FIELDNAME = 'RevisiC'
            LEFT JOIN ADSTORAGE a11 ON
                s2.ABSUNIQUEID = a11.UNIQUEID
                AND a11.FIELDNAME = 'Revisi2'
            LEFT JOIN ADSTORAGE a12 ON
                s2.ABSUNIQUEID = a12.UNIQUEID
                AND a12.FIELDNAME = 'Revisi3'
            LEFT JOIN ADSTORAGE a13 ON
                s2.ABSUNIQUEID = a13.UNIQUEID
                AND a13.FIELDNAME = 'Revisi4'
            LEFT JOIN ADSTORAGE a14 ON
                s2.ABSUNIQUEID = a14.UNIQUEID
                AND a14.FIELDNAME = 'Revisi5'
            LEFT JOIN ADSTORAGE a15 ON
                s2.ABSUNIQUEID = a15.UNIQUEID
                AND a15.FIELDNAME = 'RevisiN'
            LEFT JOIN ADSTORAGE a16 ON
                s2.ABSUNIQUEID = a16.UNIQUEID
                AND a16.FIELDNAME = 'DRevisi2'
            LEFT JOIN ADSTORAGE a17 ON
                s2.ABSUNIQUEID = a17.UNIQUEID
                AND a17.FIELDNAME = 'DRevisi3'
            LEFT JOIN ADSTORAGE a18 ON
                s2.ABSUNIQUEID = a18.UNIQUEID
                AND a18.FIELDNAME = 'DRevisi4'
            LEFT JOIN ADSTORAGE a19 ON
                s2.ABSUNIQUEID = a19.UNIQUEID
                AND a19.FIELDNAME = 'DRevisi5'
        ) s
        WHERE
            s.REVISI_HEADER IS NULL
            AND s.REVISI_LINE IS NOT NULL
            AND s.LINESTATUS = 1
    )

    SELECT
        r.CODE, r.CUSTOMER, r.TGL_APPROVE_RMP, r.APPROVAL_RMP_DATETIME,
        r.RevisiC, r.Revisi2, r.Revisi3, r.Revisi4, r.Revisi5,
        r.RevisiN, r.DRevisi2, r.DRevisi3, r.DRevisi4, r.DRevisi5,
        r.Revisi1Date, r.Revisi2Date, r.Revisi3Date, r.Revisi4Date, r.Revisi5Date,
        COALESCE(NULLIF(TRIM(r.DRevisi5),''),NULLIF(TRIM(r.DRevisi4),''),NULLIF(TRIM(r.DRevisi3),''),NULLIF(TRIM(r.Revisi2),''),NULLIF(TRIM(r.RevisiN),'')) AS REVISIN_LAST,
        COALESCE(NULLIF(TRIM(r.Revisi5),''),NULLIF(TRIM(r.Revisi4),''),NULLIF(TRIM(r.Revisi3),''),NULLIF(TRIM(r.Revisi2),''),NULLIF(TRIM(r.RevisiC),'')) AS REVISIC_LAST
    FROM ranked r
    WHERE r.rn = 1
    AND (
            (
            COALESCE(NULLIF(TRIM(r.RevisiC),''),NULLIF(TRIM(r.Revisi2),''),NULLIF(TRIM(r.Revisi3),''),NULLIF(TRIM(r.Revisi4),''),NULLIF(TRIM(r.Revisi5),'')) IS NOT NULL
            AND
            COALESCE(NULLIF(TRIM(r.RevisiN),''),NULLIF(TRIM(r.DRevisi2),''),NULLIF(TRIM(r.DRevisi3),''),NULLIF(TRIM(r.DRevisi4),''),NULLIF(TRIM(r.DRevisi5),'')) IS NOT NULL
            )
            OR
            r.CODE IN (SELECT CODE FROM line_revisi)
        )
";
$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

/* --------------- 3) Tentukan yang pending (TBO) --------------- */
$tboRows = [];
while ($row = db2_fetch_assoc($resultTBO)) {
    $codeUpper = strtoupper(trim($row['CODE']));
    $snap = $lastMySQLByCode[$codeUpper] ?? null;

    $need = false;
    if ($snap === null) {
        $need = true; // data baru
    } else {
        $headerDiff = revisionsDiffer($row, $snap); // ABAIKAN tanggal
        $lineDiff   = $ENABLE_LINE_DIFF ? has_line_diff($conn1, $codeUpper) : false;
        if ($headerDiff || $lineDiff) $need = true;
    }
    if ($need) $tboRows[] = $row;
}

/* --------------- 4) Approved (riwayat): exclude yang lagi pending --------------- */
$excludeList = "";
if (!empty($tboRows)) {
    $codes = [];
    foreach ($tboRows as $r) {
        $codes[] = "'" . mysqli_real_escape_string($con, strtoupper(trim($r['CODE']))) . "'";
    }
    $excludeList = " AND UPPER(a.code) NOT IN (" . implode(",", $codes) . ") ";
}

$sqlApproved = "
SELECT a.*
FROM approval_bon_order a
JOIN (
  SELECT code, MAX(id) AS max_id
  FROM approval_bon_order
  WHERE is_revision = 1
  GROUP BY code
) m ON m.max_id = a.id
WHERE a.is_revision = 1
{$excludeList}
ORDER BY a.id DESC
";
$resultApproved = mysqli_query($con, $sqlApproved);
?>
<style>
.modal-full{width:98%;max-width:98%}
.btn-outline-purple{background:transparent;color:#6f42c1;border:1px solid #6f42c1}
.btn-outline-purple:hover,.btn-outline-purple:focus{background:#6f42c1;color:#fff}

#detailModal .modal-body{
  max-height: 90vh;
  overflow-y: auto;
}
#detailModal .table-responsive{
  overflow-x: auto;
}
#detailApprovedTable thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 2; 
    /* border: 0.5px solid #ddd; */
    padding: 8px;
  }
</style>

<div class="row">
  <div class="col-xs-12">
    <!-- ========== TABEL 1: Data Siap Approval ========== -->
    <div class="box">
      <div class="box-body">
        <div class="card mb-4">
          <div class="card-header text-white"><h3 class="card-title">Data Siap Approval</h3></div>
          <div class="card-body">
            <table class="table table-bordered table-sm" id="tboTable">
              <thead class="bg-primary text-white">
                <tr>
                  <th>Customer</th>
                  <th>No Bon Order</th>
                  <th>Tgl Approved RMP</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($tboRows as $row):
                $code = strtoupper(trim($row['CODE']));
                $customer = trim($row['CUSTOMER']);
                $tgl = substr(trim($row['APPROVAL_RMP_DATETIME'] ?? ''), 0, 10);

                $approvalDtRaw = trim($row['APPROVAL_RMP_DATETIME'] ?? '');
                $approvalDtStr = '';
                if ($approvalDtRaw !== '') {
                    try {
                        $dt = new DateTime($approvalDtRaw);
                        $approvalDtStr = $dt->format('Y-m-d H:i:s');
                    } catch (Exception $e) { $approvalDtStr = $approvalDtRaw; }
                }
              ?>
                <tr>
                  <td style="padding:4px 8px;">
                    <div style="margin-bottom:2px; word-break:break-word;"><?= htmlspecialchars($customer) ?></div>
                    <div style="display:flex; align-items:center; font-weight:700;">
                      <span style="flex:1 1 auto; min-width:0; word-break:break-word;"><?= htmlspecialchars($row['REVISIN_LAST']) ?></span>
                      <span style="flex:0 0 auto; margin-left:auto;"><?= htmlspecialchars($row['REVISIC_LAST']) ?></span>
                    </div>
                  </td>
                  <td>
                    <!-- Klik dari TBO: tampilkan kondisi DB2 terkini -->
                    <a href="#" class="btn btn-primary btn-sm open-detail"
                       data-code="<?= $code ?>" data-toggle="modal" data-target="#detailModal">
                      <?= $code ?>
                    </a>
                  </td>
                  <td><?= htmlspecialchars($tgl) ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <select class="form-control form-control-sm pic-select" data-code="<?= $code ?>">
                        <option value="">-- Pilih PIC --</option>
                        <?php $resultPIC = mysqli_query($con,"SELECT * FROM tbl_user WHERE pic_bonorder=1 ORDER BY id ASC");
                        while ($rowPIC = mysqli_fetch_assoc($resultPIC)): ?>
                          <option value="<?= htmlspecialchars($rowPIC['username']) ?>"><?= htmlspecialchars($rowPIC['username']) ?></option>
                        <?php endwhile; ?>
                      </select>
                      <button class="btn btn-success btn-sm approve-btn" data-code="<?= $code ?>" data-approval-rmp-dt="<?= htmlspecialchars($approvalDtStr, ENT_QUOTES) ?>">Approve</button>
                      <!-- <button class="btn btn-danger btn-sm reject-btn"  data-code="<?= $code ?>">Reject</button> -->

                      <button class="btn btn-outline-purple btn-sm revisi-btn"
                        data-code="<?= $code ?>"
                        data-revisic="<?= htmlspecialchars($row['REVISIC']  ?? '', ENT_QUOTES) ?>"
                        data-revisi2="<?= htmlspecialchars($row['REVISI2']  ?? '', ENT_QUOTES) ?>"
                        data-revisi3="<?= htmlspecialchars($row['REVISI3']  ?? '', ENT_QUOTES) ?>"
                        data-revisi4="<?= htmlspecialchars($row['REVISI4']  ?? '', ENT_QUOTES) ?>"
                        data-revisi5="<?= htmlspecialchars($row['REVISI5']  ?? '', ENT_QUOTES) ?>"
                        data-revisin="<?= htmlspecialchars($row['REVISIN']  ?? '', ENT_QUOTES) ?>"
                        data-drevisi2="<?= htmlspecialchars($row['DREVISI2'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi3="<?= htmlspecialchars($row['DREVISI3'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi4="<?= htmlspecialchars($row['DREVISI4'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi5="<?= htmlspecialchars($row['DREVISI5'] ?? '', ENT_QUOTES) ?>"
                        data-revisi1date="<?= htmlspecialchars($row['REVISI1DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi2date="<?= htmlspecialchars($row['REVISI2DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi3date="<?= htmlspecialchars($row['REVISI3DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi4date="<?= htmlspecialchars($row['REVISI4DATE'] ?? '', ENT_QUOTES) ?>"
                        data-revisi5date="<?= htmlspecialchars($row['REVISI5DATE'] ?? '', ENT_QUOTES) ?>">
                        Detail Revisi
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>  
      </div>
    </div>

    <!-- ========== TABEL 2: Riwayat Approval (exclude pending) ========== -->
    <div class="box">
      <div class="box-body">
        <div class="card">
          <div class="card-header text-white"><h3 class="card-title">Tabel Approval Revisi Bon Order</h3></div>
          <div class="card-body">
            <table class="table table-bordered table-sm" id="approvedTable">
              <thead class="bg-success text-white">
                <tr>
                  <th style="display:none;">ID</th>
                  <th>Customer</th>
                  <th>No Bon Order</th>
                  <th>Tgl Approved RMP</th>
                  <th>Tgl Approved Lab</th>
                  <!-- <th>Tgl Rejected Lab</th> -->
                  <th>PIC Lab</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
              <?php while ($row = mysqli_fetch_assoc($resultApproved)):
                $codeApp   = strtoupper(trim($row['code']));
                $reviN_last = first_non_empty([$row['drevisi5'],$row['drevisi4'],$row['drevisi3'],$row['drevisi2'],$row['revisin']]);
                $reviC_last = first_non_empty([$row['revisi5'],$row['revisi4'],$row['revisi3'],$row['revisi2'],$row['revisic']]);
                $linesSnap  = $lastLinesByCode[$codeApp] ?? [];
                $linesJsonAttr = htmlspecialchars(json_encode($linesSnap), ENT_QUOTES);
              ?>
                <tr>
                  <td style="display:none;"><?= (int)$row['id'] ?></td>
                  <td>
                    <div style="margin-bottom:2px; word-break:break-word;"><?= htmlspecialchars($row['customer']) ?></div>
                    <div style="display:flex; align-items:center; font-weight:700;">
                      <span style="flex:1 1 auto; min-width:0; word-break:break-word;"><?= htmlspecialchars($reviN_last) ?></span>
                      <span style="flex:0 0 auto; margin-left:auto;"><?= htmlspecialchars($reviC_last) ?></span>
                    </div>
                  </td>
                  <td>
                    <!-- Klik dari APPROVED -->
                    <a href="#"
                      class="btn btn-primary btn-sm open-detail"
                      data-code="<?= htmlspecialchars($row['code']) ?>"
                      data-toggle="modal" data-target="#detailModal">
                      <?= htmlspecialchars($row['code']) ?>
                    </a>
                  </td>
                  <!-- <td><?= htmlspecialchars($row['tgl_approve_rmp']) ?></td> -->
                  <td>
                      <?= !empty($row['approvalrmpdatetime']) 
                          ? htmlspecialchars(date('Y-m-d', strtotime($row['approvalrmpdatetime']))) 
                          : '' ?>
                  </td>
                  <td><?= htmlspecialchars($row['tgl_approve_lab']) ?></td>
                  <!-- <td><?= htmlspecialchars($row['tgl_rejected_lab']) ?></td> -->
                  <td><?= htmlspecialchars($row['pic_lab']) ?></td>
                  <td>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                      <strong class="<?= ($row['status']==='Approved'?'text-success':'text-danger') ?>"><?= htmlspecialchars($row['status']) ?></strong>
                      <button class="btn btn-outline-purple btn-sm revisi-btn"
                        data-code="<?= $codeApp ?>"
                        data-revisic="<?= htmlspecialchars($row['revisic']  ?? '', ENT_QUOTES) ?>"
                        data-revisi2="<?= htmlspecialchars($row['revisi2']  ?? '', ENT_QUOTES) ?>"
                        data-revisi3="<?= htmlspecialchars($row['revisi3']  ?? '', ENT_QUOTES) ?>"
                        data-revisi4="<?= htmlspecialchars($row['revisi4']  ?? '', ENT_QUOTES) ?>"
                        data-revisi5="<?= htmlspecialchars($row['revisi5']  ?? '', ENT_QUOTES) ?>"
                        data-revisin="<?= htmlspecialchars($row['revisin']  ?? '', ENT_QUOTES) ?>"
                        data-drevisi2="<?= htmlspecialchars($row['drevisi2'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi3="<?= htmlspecialchars($row['drevisi3'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi4="<?= htmlspecialchars($row['drevisi4'] ?? '', ENT_QUOTES) ?>"
                        data-drevisi5="<?= htmlspecialchars($row['drevisi5'] ?? '', ENT_QUOTES) ?>"
                        data-revisi1date="<?= htmlspecialchars($row['revisi1date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi2date="<?= htmlspecialchars($row['revisi2date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi3date="<?= htmlspecialchars($row['revisi3date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi4date="<?= htmlspecialchars($row['revisi4date'] ?? '', ENT_QUOTES) ?>"
                        data-revisi5date="<?= htmlspecialchars($row['revisi5date'] ?? '', ENT_QUOTES) ?>">
                        Detail Revisi
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>     
      </div>
    </div>
  </div>
</div>

<!-- ========== Modal Detail Order ========== -->
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-full">
    <div class="modal-content">
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Detail Order</h4></div>
      <div class="modal-body" id="modal-content"><p>Loading data...</p></div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
    </div>
  </div>
</div>

<!-- ========== Modal Detail Revisi (Header) ========== -->
<div id="revisiModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Detail Revisi</h4></div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-condensed" id="revisionTable">
          <thead><tr><th>Revisi Category</th><th>Detail Revisi</th><th style="width:140px;">Tanggal Revisi</th></tr></thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
    </div>
  </div>
</div>

<script>
// ==== Modal Detail (Approved vs TBO) ====
// $(document).on('click', '.open-detail', function(e){
//   e.preventDefault();
//   var code = $(this).data('code');
//   var $btn = $(this);
//   $('#modal-content').html('<p>Loading data...</p>');

//   // dari approvedTable -> render dari snapshot MySQL (data attribute)
//   var raw = $btn.attr('data-linesjson');
//   if (typeof raw !== 'undefined') {
//     try {
//       var rows = raw ? JSON.parse(raw) : [];
//       renderDetailTableFromLines(rows, 'Snapshot Line (MySQL) — ' + code);

//     } catch (err) {
//       $('#modal-content').html('<p class="text-danger">Gagal membaca snapshot line.</p>');
//     }
//     return;
//   }

//   // dari tboTable -> ambil HTML dari endpoint lama
//   $.ajax({
//     url: 'pages/ajax/Approved_get_order_detail_revision.php',
//     type: 'POST',
//     data: { code: code },
//     success: function(res){
//       $('#modal-content').html(res);
//       if ($.fn.DataTable.isDataTable('#detailApprovedTable')) $('#detailApprovedTable').DataTable().destroy();
//       $('#detailApprovedTable').DataTable({ paging:true, searching:true, ordering:true, order:[[0,'asc']] });
//     },
//     error: function(){ $('#modal-content').html('<p class="text-danger">Gagal memuat data.</p>'); }
//   });
// });
// ==== Modal Detail Order (load HTML dari server) ====
// ==== Modal Detail Order (load HTML dari server) ====
$(document).on('click', '.open-detail', function(e){
  e.preventDefault();
  var code = $(this).data('code');

  $('#modal-content').html('<p>Loading data...</p>');

  $.ajax({
    url: 'pages/ajax/Approved_get_order_detail_revision.php',
    type: 'POST',
    dataType: 'json',
    data: { code: code },
    success: function(res){
      if (!res.success) {
          $('#modal-content').html('<p class="text-danger">Gagal memuat data.</p>');
          return;
      }

      // Bangun tabel dari JSON
      let html = `
          <style>
              .table.table-bordered > tbody > tr.has-revisi > td { border-bottom-color: transparent; }
              .table.table-bordered > tbody > tr.revisi-summary > td { border-top-color: transparent; }
              .table.table-bordered > tbody > tr.revisi-summary td:first-child { border-left: none; background:#fafafa; }
              .table > tbody > tr.has-revisi > td { padding-bottom:6px; }
              .table > tbody > tr.revisi-summary > td { padding-top:6px; }
              .btn-outline-purple{background-color:transparent;color:#6f42c1;border:1px solid #6f42c1}
              .btn-outline-purple:hover,.btn-outline-purple:focus{background:#6f42c1;color:#fff}
          </style>
          <table class='table table-bordered table-striped' id='detailApprovedTable'>
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Bon Order</th>
                      <th>No PO</th>
                      <th>Nama Buyer</th>
                      <th>Jenis Kain</th>
                      <th>AKJ</th>
                      <th>Itemcode</th>
                      <th>Notetas</th>
                      <th>Gramasi</th>
                      <th>Lebar</th>
                      <th>Color Standard</th>
                      <th>Warna</th>
                      <th>Kode Warna</th>
                      <th>Color Remarks</th>
                      <th>Benang</th>
                      <th>PO Greige</th>
                  </tr>
              </thead>
              <tbody>
      `;

      res.data.forEach((item, i) => {
        html += `
            <tr class='${item.HAS_REVISI ? 'has_revisi' : ''}'>
                <td>${i + 1}</td>
                <td>${item.SALESORDERCODE ?? ''}</td>
                <td>${item.NO_PO ?? ''}</td>
                <td>${item.LEGALNAME1 ?? ''}</td>
                <td>${item.JENIS_KAIN ?? ''}</td>
                <td>${item.AKJ ?? ''}</td>
                <td>${item.ITEMCODE ?? ''}</td>
                <td>${item.NOTETAS ?? ''}</td>
                <td>${(item.GRAMASI ?? 0).toFixed(2)}</td>
                <td>${(item.LEBAR ?? 0).toFixed(2)}</td>
                <td>${item.COLOR_STANDARD ?? ''}</td>
                <td>${item.WARNA ?? ''}</td>
                <td>${item.KODE_WARNA ?? ''}</td>
                <td>${item.COLORREMARKS ?? ''}</td>
                <td>${item.BENANG || ''}</td>
                <td>${item.PO_GREIGE || ''}</td>
            </tr>

          ${item.HAS_REVISI && `
            <tr class='revisi-summary'>
              <td></td>
              <td colspan='15' style=\"background:#fafafa;\">
                  <div style=\"display:flex; align-items:center; gap: 50px; flex-wrap:wrap;\">
                  <div><strong><span>${item.LAST_D_ESC ?? '-'}</span></strong></div>
                  <div><strong><span>${item.LAST_C_ESC ?? '-'}</span></strong></div>
                  <button type='button' class='btn btn-outline-purple btn-xs revisi-btn' ${item.DATA_ATTRS}
                          style='margin-left:auto;'>Detail Revisi</button>
                  </div>
              </td>
            </tr>
          `}
        `;
      });

      html += `</tbody></table>`;
      $('#modal-content').html(html);

      // init DataTable (stabil di modal)
      var dt = $('#detailApprovedTable').DataTable({
        destroy: true,
        deferRender: true,
        autoWidth: false,
        scrollX: true,                // tabel lebar aman di modal
        paging: true,
        searching: true,
        ordering: true,
        order: [[0, 'asc']],          // kolom 1 = orderline
        columnDefs: [
          { targets: 0, type: 'num' },  // sorting numerik utk orderline
          { targets: '_all', defaultContent: '' }
        ]
      });

      // adjust kolom setelah modal benar2 tampil (biar header/width rapi)
      $('#detailModal')
        .off('shown.bs.modal.dtfix')
        .on('shown.bs.modal.dtfix', function(){ dt.columns.adjust().draw(false); })
        .modal('show');
    },
    error: function(){
      $('#modal-content').html('<p class="text-danger">Gagal memuat data.</p>');
    }
  });
});

// optional: bersihkan saat modal ditutup (hindari “Cannot reinitialise”/leak)
$('#detailModal').on('hidden.bs.modal', function(){
  if ($.fn.DataTable.isDataTable('#detailApprovedTable')) {
    $('#detailApprovedTable').DataTable().destroy();
  }
  $('#modal-content').empty();
});


// tombol Detail Revisi di tabel dalam modal (pakai nama kolom baru)
$(document).on('click', '#detailApprovedTable .revisi-btn', function(){
  var rows = [
    { cat: String($(this).data('revisic') || ''),  det: String($(this).data('revisid')  || ''), dt: $(this).data('revisi1date') || '' },
    { cat: String($(this).data('revisic1')|| ''),  det: String($(this).data('revisi2') || ''), dt: $(this).data('revisi2date') || '' },
    { cat: String($(this).data('revisic2')|| ''),  det: String($(this).data('revisi3') || ''), dt: $(this).data('revisi3date') || '' },
    { cat: String($(this).data('revisic3')|| ''),  det: String($(this).data('revisi4') || ''), dt: $(this).data('revisi4date') || '' },
    { cat: String($(this).data('revisic4')|| ''),  det: String($(this).data('revisi5') || ''), dt: $(this).data('revisi5date') || '' }
  ];
  var $tbody = $('#revisionTable tbody'); $tbody.empty();
  rows.forEach(function(r){
    var c = String(r.cat||'').trim();
    var d = String(r.det||'').trim();
    var t = String(r.dt||'').trim();

    if(!c && !d && !t) return;

    var $tr=$('<tr/>');
    $tr.append($('<td/>').text(String(r.cat||'').trim() || '-'));
    $tr.append($('<td/>').text(d));
    $tr.append($('<td/>').text(String(r.dt||'').trim()));
    $tbody.append($tr);
  });
  if ($tbody.children().length===0){
    $tbody.append($('<tr/>').append($('<td colspan="3" class="text-center text-muted"/>').text('Tidak ada detail revisi yang terisi.')));
  }
  $('#revisiModal').modal('show');
});

function renderDetailTableFromLines(rows, title){
  var html = '';
  html += '<h4 style="margin-top:0;">'+ (title||'Detail Line') +'</h4>';
  html += '<div class="table-responsive">';
  html += '<table class="table table-bordered table-striped table-condensed" id="detailApprovedTable">';
  html += '<thead><tr>';
  html += '<th>Order Line</th><th>RevisiC</th><th>RevisiC1</th><th>RevisiC2</th><th>RevisiC3</th><th>RevisiC4</th>';
  html += '<th>Revisid</th><th>Revisi2</th><th>Revisi3</th><th>Revisi4</th><th>Revisi5</th>';
  html += '<th>Rev1 Date</th><th>Rev2 Date</th><th>Rev3 Date</th><th>Rev4 Date</th><th>Rev5 Date</th>';
  html += '</tr></thead><tbody>';

  (rows||[]).forEach(function(r){
    html += '<tr>';
    html += '<td>'+ esc(r.orderline) +'</td>';
    html += '<td>'+ esc(r.revisic)  +'</td>';
    html += '<td>'+ esc(r.revisic1) +'</td>';
    html += '<td>'+ esc(r.revisic2) +'</td>';
    html += '<td>'+ esc(r.revisic3) +'</td>';
    html += '<td>'+ esc(r.revisic4) +'</td>';

    html += '<td>'+ esc(r.revisid)  +'</td>';
    html += '<td>'+ esc(r.revisi2) +'</td>';
    html += '<td>'+ esc(r.revisi3) +'</td>';
    html += '<td>'+ esc(r.revisi4) +'</td>';
    html += '<td>'+ esc(r.revisi5) +'</td>';

    html += '<td>'+ esc(r.revisi1date) +'</td>';
    html += '<td>'+ esc(r.revisi2date) +'</td>';
    html += '<td>'+ esc(r.revisi3date) +'</td>';
    html += '<td>'+ esc(r.revisi4date) +'</td>';
    html += '<td>'+ esc(r.revisi5date) +'</td>';
    html += '</tr>';
  });

  html += '</tbody></table></div>';
  $('#modal-content').html(html);
  if ($.fn.DataTable.isDataTable('#detailApprovedTable')) $('#detailApprovedTable').DataTable().destroy();
  $('#detailApprovedTable').DataTable({ paging:true, searching:true, ordering:true, order:[[0,'asc']] });
}

function esc(x){ return $('<div/>').text(x==null?'':String(x)).html(); }

// ==== Modal Detail Revisi (header-only) ====
function openRevisionModalFromBtn($btn){
  var rows = [
    { cat: $btn.data('revisic')  || '', det: $btn.data('revisin')  || '', dt: $btn.data('revisi1date') || '' },
    { cat: $btn.data('revisi2')  || '', det: $btn.data('drevisi2') || '', dt: $btn.data('revisi2date') || '' },
    { cat: $btn.data('revisi3')  || '', det: $btn.data('drevisi3') || '', dt: $btn.data('revisi3date') || '' },
    { cat: $btn.data('revisi4')  || '', det: $btn.data('drevisi4') || '', dt: $btn.data('revisi4date') || '' },
    { cat: $btn.data('revisi5')  || '', det: $btn.data('drevisi5') || '', dt: $btn.data('revisi5date') || '' }
  ];
  var $tbody = $('#revisionTable tbody'); $tbody.empty();
  rows.forEach(function(r){
    var det = String(r.det||'').trim(); if(det==='') return;
    var $tr = $('<tr/>');
    $tr.append($('<td/>').text(String(r.cat||'').trim() || '-'));
    $tr.append($('<td/>').text(det));
    $tr.append($('<td/>').text(String(r.dt||'').trim()));
    $tbody.append($tr);
  });
  if ($tbody.children().length===0){
    $tbody.append($('<tr/>').append($('<td colspan="3" class="text-center text-muted"/>').text('Tidak ada detail revisi yang terisi.')));
  }
  $('#revisiModal').modal('show');
}
$(document).on('click', '#approvedTable tbody .revisi-btn', function(e){ e.preventDefault(); e.stopPropagation(); openRevisionModalFromBtn($(this)); });
$(document).on('click', '#tboTable tbody .revisi-btn',      function(e){ e.preventDefault(); e.stopPropagation(); openRevisionModalFromBtn($(this)); });
</script>

<script>
$(document).ready(function(){
  const tboTable = $('#tboTable').DataTable();
  const approvedTable = $('#approvedTable').DataTable({
    order: [[0,'desc']],
    columnDefs: [{ targets:0, visible:false }]
  });

  function getPIC(code){ return $("select.pic-select[data-code='"+code+"']").val(); }
  function getCustomer(code){ return $("tr:has(button[data-code='"+code+"']) td:first").text(); }
  function getTglApproveRMP(code){ return $("tr:has(button[data-code='"+code+"']) td:eq(2)").text(); }

  function getApprovalRmpDateTime(code) {
    return $("button.approve-btn[data-code='"+code+"']").data('approval-rmp-dt') || '';
  }

  function reloadTboTable(){
    $.get("pages/ajax/refresh_tbo_table_revisi.php", function (html) {
      const $rows = $($.parseHTML(html)).filter('tr');
      tboTable.clear();
      if ($rows.length) tboTable.rows.add($rows);
      tboTable.draw(false);
      tboTable.columns.adjust().draw(false);
    });
  }

  function reloadApprovedTable(isRevision=1){
    $.get("pages/ajax/refresh_approved_table_revisi.php", { is_revision:isRevision }, function (html) {
      const $rows = $($.parseHTML(html)).filter('tr');
      approvedTable.clear();
      if ($rows.length) approvedTable.rows.add($rows);
      // kembalikan ke state awal: kolom 0 hidden & sort desc id
      approvedTable.order([0,'desc']).draw(false);
      approvedTable.columns.adjust().draw(false);
    });
  }

  // (opsional) badge counter existing
  function toInt(x){ try{ if(typeof x==='string'&&x.trim().startsWith('{')){const o=JSON.parse(x);for(const k in o){if(Object.hasOwn(o,k)&&!isNaN(parseInt(o[k],10))) return parseInt(o[k],10);}} }catch(e){} const n=parseInt(String(x).replace(/[^\d-]/g,''),10); return isNaN(n)?0:n; }
  function updateBadge(tboCount, tboRevisiCount){ const total=tboCount+tboRevisiCount; $('#notifTBO').text(total); $('#notifTBOText').text(tboCount); $('#notifTBOText_revisi').text(tboRevisiCount); }
  function refreshTBOCount(){ $.get('pages/ajax/get_total_tbo.php', function(d1){ $.get('pages/ajax/get_total_tbo_revisi.php', function(d2){ updateBadge(toInt(d1), toInt(d2)); }); }); }

  function submitApproval(code, action){
    const pic = getPIC(code);
    const customer = getCustomer(code);
    const tgl_approve_rmp = getTglApproveRMP(code);
    const buttons = $("button[data-code='"+code+"']");
    const $revBtn = $("button.revisi-btn[data-code='"+code+"']");
    const approvalrmpdatetime = getApprovalRmpDateTime(code);

    const revisiPayload = {
      revisic:     $revBtn.data('revisic')     || '',
      revisi2:     $revBtn.data('revisi2')     || '',
      revisi3:     $revBtn.data('revisi3')     || '',
      revisi4:     $revBtn.data('revisi4')     || '',
      revisi5:     $revBtn.data('revisi5')     || '',
      revisin:     $revBtn.data('revisin')     || '',
      drevisi2:    $revBtn.data('drevisi2')    || '',
      drevisi3:    $revBtn.data('drevisi3')    || '',
      drevisi4:    $revBtn.data('drevisi4')    || '',
      drevisi5:    $revBtn.data('drevisi5')    || '',
      revisi1date: $revBtn.data('revisi1date') || '',
      revisi2date: $revBtn.data('revisi2date') || '',
      revisi3date: $revBtn.data('revisi3date') || '',
      revisi4date: $revBtn.data('revisi4date') || '',
      revisi5date: $revBtn.data('revisi5date') || ''
    };

    buttons.prop('disabled', true);
    if(!pic){
      Swal.fire({icon:'warning', title:'PIC belum dipilih', text:'Silakan pilih PIC Lab terlebih dahulu.'});
      buttons.prop('disabled', false);
      return;
    }

    Swal.fire({title:`${action} Bon Order?`, text:`Kode: ${code} | PIC: ${pic}`, icon:'question', showCancelButton:true, confirmButtonText:action, cancelButtonText:'Batal'})
      .then((result)=>{
        if(!result.isConfirmed){ buttons.prop('disabled', false); return; }
        Swal.fire({title:'Memproses...', text:'Mohon tunggu sebentar.', didOpen:()=>Swal.showLoading(), allowOutsideClick:false});

        // ambil snapshot line CURRENT (DB2) untuk disimpan saat approve
        $.getJSON('pages/ajax/Approved_get_order_detail_revision_json.php', { code }, function(payload){
          const lines_json = JSON.stringify(payload.lines || []);

          $.post("pages/ajax/approve_bon_order_lab.php", {
            code, customer, tgl_approve_rmp, pic_lab:pic, status:action, is_revision:1, approvalrmpdatetime,
            ...revisiPayload, lines_json
          }, function(resp){
            Swal.fire({icon:'success', title:'Berhasil', text:resp});
            reloadTboTable();
            reloadApprovedTable(1);
            // if (typeof refreshTBOCount === 'function') refreshTBOCount();
            // if (typeof refreshTBORCount === 'function') refreshTBORCount();
          }).fail(function(){
            Swal.fire({icon:'error', title:'Gagal', text:'Terjadi kesalahan saat menyimpan data.'});
            buttons.prop('disabled', false);
          });

        }).fail(function(){
          Swal.fire({icon:'error', title:'Gagal', text:'Tidak bisa mengambil detail line (DB2).'});
          buttons.prop('disabled', false);
        });
      });
  }

  $('#tboTable tbody').on('click', '.approve-btn', function(){ submitApproval($(this).data('code'), 'Approved'); });
  $('#tboTable tbody').on('click', '.reject-btn',  function(){ submitApproval($(this).data('code'), 'Rejected'); });

  // refreshTBOCount();
});
</script>
