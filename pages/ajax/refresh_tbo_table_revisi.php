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
   Helper: ambil detail line DB2 per CODE
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
if ($resSnap) {
    while ($r = mysqli_fetch_assoc($resSnap)) {
        $lastMySQLByCode[strtoupper(trim($r['code']))] = $r;
    }
}

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
   Ambil kandidat DB2 (header) lalu tentukan siapa yang pending
   --------------------------- */
$sqlTBO = "
WITH base AS (
    SELECT
        isa.CODE                                AS CODE,
        ip.LANGGANAN || ip.BUYER                AS CUSTOMER,
        isa.TGL_APPROVEDRMP                     AS TGL_APPROVE_RMP,

        CASE WHEN aC.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || aC.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || aC.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC,
        CASE WHEN a2.VALUESTRING IS NOT NULL AND ad2.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(ad2.OPTIONS, '(?:^|;)' || a2.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(ad2.OPTIONS,'(?:^|;)' || a2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi2,
        CASE WHEN a3.VALUESTRING IS NOT NULL AND ad3.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(ad3.OPTIONS, '(?:^|;)' || a3.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(ad3.OPTIONS,'(?:^|;)' || a3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi3,
        CASE WHEN a4.VALUESTRING IS NOT NULL AND ad4.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(ad4.OPTIONS, '(?:^|;)' || a4.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(ad4.OPTIONS,'(?:^|;)' || a4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi4,
        CASE WHEN a5.VALUESTRING IS NOT NULL AND ad5.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(ad5.OPTIONS, '(?:^|;)' || a5.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(ad5.OPTIONS,'(?:^|;)' || a5.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi5,

        n1.VALUESTRING AS RevisiN,
        n2.VALUESTRING AS DRevisi2,
        n3.VALUESTRING AS DRevisi3,
        n4.VALUESTRING AS DRevisi4,
        n5.VALUESTRING AS DRevisi5,

        dt1.VALUEDATE AS Revisi1Date,
        dt2.VALUEDATE AS Revisi2Date,
        dt3.VALUEDATE AS Revisi3Date,
        dt4.VALUEDATE AS Revisi4Date,
        dt5.VALUEDATE AS Revisi5Date

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

    LEFT JOIN ADSTORAGE n1 ON n1.UNIQUEID = s.ABSUNIQUEID AND n1.FIELDNAME = 'RevisiN'
    LEFT JOIN ADSTORAGE n2 ON n2.UNIQUEID = s.ABSUNIQUEID AND n2.FIELDNAME = 'DRevisi2'
    LEFT JOIN ADSTORAGE n3 ON n3.UNIQUEID = s.ABSUNIQUEID AND n3.FIELDNAME = 'DRevisi3'
    LEFT JOIN ADSTORAGE n4 ON n4.UNIQUEID = s.ABSUNIQUEID AND n4.FIELDNAME = 'DRevisi4'
    LEFT JOIN ADSTORAGE n5 ON n5.UNIQUEID = s.ABSUNIQUEID AND n5.FIELDNAME = 'DRevisi5'

    LEFT JOIN ADSTORAGE dt1 ON dt1.UNIQUEID = s.ABSUNIQUEID AND dt1.FIELDNAME = 'Revisi1Date'
    LEFT JOIN ADSTORAGE dt2 ON dt2.UNIQUEID = s.ABSUNIQUEID AND dt2.FIELDNAME = 'Revisi2Date'
    LEFT JOIN ADSTORAGE dt3 ON dt3.UNIQUEID = s.ABSUNIQUEID AND dt3.FIELDNAME = 'Revisi3Date'
    LEFT JOIN ADSTORAGE dt4 ON dt4.UNIQUEID = s.ABSUNIQUEID AND dt4.FIELDNAME = 'Revisi4Date'
    LEFT JOIN ADSTORAGE dt5 ON dt5.UNIQUEID = s.ABSUNIQUEID AND dt5.FIELDNAME = 'Revisi5Date'

    LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE

    WHERE isa.APPROVEDRMP IS NOT NULL
      AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
),
ranked AS (
    SELECT b.*,
           ROW_NUMBER() OVER (PARTITION BY b.CODE ORDER BY (b.TGL_APPROVE_RMP IS NULL) ASC, b.TGL_APPROVE_RMP DESC) AS rn
    FROM base b
)
SELECT
    CODE, CUSTOMER, TGL_APPROVE_RMP,
    RevisiC, Revisi2, Revisi3, Revisi4, Revisi5,
    RevisiN, DRevisi2, DRevisi3, DRevisi4, DRevisi5,
    Revisi1Date, Revisi2Date, Revisi3Date, Revisi4Date, Revisi5Date,
    COALESCE(NULLIF(TRIM(DRevisi5),''),NULLIF(TRIM(DRevisi4),''),NULLIF(TRIM(DRevisi3),''),NULLIF(TRIM(DRevisi2),''),NULLIF(TRIM(RevisiN),'')) AS REVISIN_LAST,
    COALESCE(NULLIF(TRIM(Revisi5),''),NULLIF(TRIM(Revisi4),''),NULLIF(TRIM(Revisi3),''),NULLIF(TRIM(Revisi2),''),NULLIF(TRIM(RevisiC),'')) AS REVISIC_LAST
FROM ranked
WHERE rn = 1
  AND COALESCE(NULLIF(TRIM(RevisiC),''),NULLIF(TRIM(Revisi2),''),NULLIF(TRIM(Revisi3),''),NULLIF(TRIM(Revisi4),''),NULLIF(TRIM(Revisi5),'')) IS NOT NULL
  AND COALESCE(NULLIF(TRIM(RevisiN),''),NULLIF(TRIM(DRevisi2),''),NULLIF(TRIM(DRevisi3),''),NULLIF(TRIM(DRevisi4),''),NULLIF(TRIM(DRevisi5),'')) IS NOT NULL
";
$resultTBO = db2_exec($conn1, $sqlTBO, ['cursor' => DB2_SCROLLABLE]);

// Ambil daftar PIC sekali
$picOptions = '';
$resPIC = mysqli_query($con, "SELECT username FROM tbl_user WHERE pic_bonorder=1 ORDER BY id ASC");
while ($r = mysqli_fetch_assoc($resPIC)) {
    $u = htmlspecialchars($r['username']);
    $picOptions .= "<option value=\"{$u}\">{$u}</option>";
}

// Cetak <tr> untuk baris pending
while ($row = db2_fetch_assoc($resultTBO)) {
    $code = strtoupper(trim($row['CODE']));
    $headerSnap = $lastMySQLByCode[$code] ?? null;

    $need = false;
    if ($headerSnap === null) {
        $need = true; // data baru
    } else {
        if (revisionsDiffer($row, $headerSnap)) {
            $need = true;
        } else {
            $db2Lines   = get_db2_lines($conn1, $code);
            $mysqlLines = $lastLinesByCode[$code] ?? [];
            if (linesDiffer($db2Lines, $mysqlLines)) $need = true;
        }
    }
    if (!$need) continue;

    $customer = trim($row['CUSTOMER']);
    $tgl = trim($row['TGL_APPROVE_RMP']);

    // --- PRINT ROW ---
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
            <?= $picOptions ?>
          </select>
          <button class="btn btn-success btn-sm approve-btn" data-code="<?= $code ?>">Approve</button>
          <button class="btn btn-danger btn-sm reject-btn"  data-code="<?= $code ?>">Reject</button>

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
    <?php
}
