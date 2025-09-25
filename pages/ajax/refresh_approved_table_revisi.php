<?php
require_once '../../koneksi.php';
require_once '../lib/revisi_compare.php';

// helper jika belum ada
if (!function_exists('first_non_empty')) {
    function first_non_empty(array $arr) {
        foreach ($arr as $v) { $t = trim((string)$v); if ($t !== '') return $t; }
        return '';
    }
}

/* ---------------------------
   Helper: ambil detail line DB2 per CODE (untuk hitung pending)
   --------------------------- */
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

/* ---------------------------
   Snapshot MySQL (header & line) terbaru per code
   --------------------------- */
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
if ($resSnap) while ($r = mysqli_fetch_assoc($resSnap)) $lastMySQLByCode[strtoupper(trim($r['code']))] = $r;

// line snapshot
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

/* ---------------------------
   Hitung code yang pending (untuk exclude)
   --------------------------- */
$pendingCodes = [];

$sqlTBO = "
WITH base AS (
        SELECT
      s.SALESORDERCODE                 AS CODE,
      ip.LANGGANAN || ip.BUYER         AS CUSTOMER,
      a.VALUEDATE                      AS TGL_APPROVE_RMP,
      
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
        r.CODE, r.CUSTOMER, r.TGL_APPROVE_RMP,
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
$res = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

while ($r = db2_fetch_assoc($res)) {
    $code = strtoupper(trim($r['CODE']));
    $headerSnap = $lastMySQLByCode[$code] ?? null;

    $need = false;
    if ($headerSnap === null) {
        $need = true; // baru
    } else {
        if (revisionsDiffer($r, $headerSnap)) {
            $need = true;
        } else {
            $db2Lines   = get_db2_lines($conn1, $code);
            $mysqlLines = $lastLinesByCode[$code] ?? [];
            if (linesDiffer($db2Lines, $mysqlLines)) $need = true;
        }
    }
    if ($need) $pendingCodes[$code] = true;
}

/* ---------------------------
   Ambil baris riwayat: hanya 1 terbaru per code, exclude pending
   --------------------------- */
$whereExclude = '';
if (!empty($pendingCodes)) {
    $in = implode(',', array_map(function($c) use ($con){ return "'" . mysqli_real_escape_string($con,$c) . "'"; }, array_keys($pendingCodes)));
    $whereExclude = " AND UPPER(a.code) NOT IN ($in) ";
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
{$whereExclude}
ORDER BY a.id DESC
";
$resultApproved = mysqli_query($con, $sqlApproved);

// Persiapkan snapshot line per code untuk modal (riwayat)
$lastLinesByCode = [];
$qLines2 = mysqli_query($con, "
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
if ($qLines2) {
    while ($r = mysqli_fetch_assoc($qLines2)) {
        $codeKey = strtoupper(trim($r['code']));
        if (!isset($lastLinesByCode[$codeKey])) $lastLinesByCode[$codeKey] = [];
        $lastLinesByCode[$codeKey][] = $r;
    }
}

// Cetak <tr> untuk riwayat
while ($row = mysqli_fetch_assoc($resultApproved)) {
    $codeApp = strtoupper(trim($row['code']));
    $reviN_last = first_non_empty([$row['drevisi5'],$row['drevisi4'],$row['drevisi3'],$row['drevisi2'],$row['revisin']]);
    $reviC_last = first_non_empty([$row['revisi5'],$row['revisi4'],$row['revisi3'],$row['revisi2'],$row['revisic']]);
    $linesSnap = $lastLinesByCode[$codeApp] ?? [];
    $linesJsonAttr = htmlspecialchars(json_encode($linesSnap), ENT_QUOTES);
    ?>
    <tr>
      <td style="display:none;"><?= (int)$row['id'] ?></td>
      <td>
        <div style="margin-bottom:2px; word-break:break-word;"><?= htmlspecialchars($row['customer']) ?></div>
        <div style="display:flex; align-items:center; font-weight:700;">
          <span style="flex:1 1 auto; min-width:0; word-break:break-word;">
            <?= htmlspecialchars($reviN_last ?? '', ENT_QUOTES, 'UTF-8') ?>
          </span>
          <span style="flex:0 0 auto; margin-left:auto;">
            <?= htmlspecialchars($reviC_last ?? '', ENT_QUOTES, 'UTF-8') ?>
          </span>
      </div>
      </td>
      <td>
        <a href="#"
          class="btn btn-primary btn-sm open-detail"
          data-code="<?= htmlspecialchars($row['code']) ?>"
          data-toggle="modal" data-target="#detailModal">
          <?= htmlspecialchars($row['code']) ?>
        </a>
      </td>
      <td><?= htmlspecialchars((string)($row['tgl_approve_rmp']  ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
      <td><?= htmlspecialchars((string)($row['tgl_approve_lab']  ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
      <td><?= htmlspecialchars((string)($row['tgl_rejected_lab'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
      <td><?= htmlspecialchars((string)($row['pic_lab'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
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
    <?php
}
