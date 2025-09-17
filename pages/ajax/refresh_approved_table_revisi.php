<?php
require_once '../../koneksi.php';
require_once '../lib/revisi_compare.php';

error_reporting(E_ERROR | E_PARSE);
header('Content-Type: text/html; charset=utf-8');

$is_revision = isset($_GET['is_revision']) ? (int)$_GET['is_revision'] : 1;

/** A)  */
$qSnap = "
SELECT a.*
FROM approval_bon_order a
JOIN (
  SELECT code, MAX(id) AS max_id
  FROM approval_bon_order
  WHERE is_revision = {$is_revision}
  GROUP BY code
) m ON m.max_id = a.id
WHERE a.is_revision = {$is_revision}
";
$resSnap = mysqli_query($con, $qSnap);
$lastMySQLByCode = [];
if ($resSnap) {
  while ($r = mysqli_fetch_assoc($resSnap)) {
    $lastMySQLByCode[strtoupper(trim($r['code']))] = $r;
  }
}

/** B) */
$sqlTBO = "
WITH base AS (
    SELECT
        isa.CODE                                AS CODE,
        ip.LANGGANAN || ip.BUYER                AS CUSTOMER,
        isa.TGL_APPROVEDRMP                     AS TGL_APPROVE_RMP,
        CASE WHEN aC.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || aC.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || aC.VALUESTRING || '=([^;]*)',1,1,'',1) END AS RevisiC,
        CASE WHEN a2.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a2.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a2.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi2,
        CASE WHEN a3.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a3.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a3.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi3,
        CASE WHEN a4.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a4.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a4.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi4,
        CASE WHEN a5.VALUESTRING IS NOT NULL AND adC.OPTIONS IS NOT NULL
             AND REGEXP_LIKE(adC.OPTIONS, '(?:^|;)' || a5.VALUESTRING || '=')
             THEN REGEXP_SUBSTR(adC.OPTIONS,'(?:^|;)' || a5.VALUESTRING || '=([^;]*)',1,1,'',1) END AS Revisi5,
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
    LEFT JOIN ITXVIEW_PELANGGAN ip
        ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
       AND ip.CODE = s.CODE
    WHERE isa.APPROVEDRMP IS NOT NULL
      AND DATE(s.CREATIONDATETIME) > DATE('2025-06-01')
),
ranked AS (
    SELECT b.*, ROW_NUMBER() OVER
      (PARTITION BY b.CODE ORDER BY (b.TGL_APPROVE_RMP IS NULL) ASC, b.TGL_APPROVE_RMP DESC) AS rn
    FROM base b
)
SELECT
    CODE, RevisiC, Revisi2, Revisi3, Revisi4, Revisi5,
    RevisiN, DRevisi2, DRevisi3, DRevisi4, DRevisi5,
    Revisi1Date, Revisi2Date, Revisi3Date, Revisi4Date, Revisi5Date
FROM ranked
WHERE rn = 1
";
$resDB2 = db2_exec($conn1, $sqlTBO, ['cursor'=>DB2_SCROLLABLE]);

$pendingCodes = [];
while ($row = db2_fetch_assoc($resDB2)) {
    $code = strtoupper(trim($row['CODE']));
    $snap = $lastMySQLByCode[$code] ?? null;
    if ($snap === null || revisionsDiffer($row, $snap)) {
        $pendingCodes[$code] = true;
    }
}

/** C)*/
$excludeList = "";
if (!empty($pendingCodes)) {
    $codes = [];
    foreach (array_keys($pendingCodes) as $c) {
        $codes[] = "'" . mysqli_real_escape_string($con, $c) . "'";
    }
    $excludeList = " AND UPPER(a.code) NOT IN (" . implode(",", $codes) . ") ";
}

$q = "
SELECT a.*
FROM approval_bon_order a
JOIN (
  SELECT code, MAX(id) AS max_id
  FROM approval_bon_order
  WHERE is_revision = {$is_revision}
  GROUP BY code
) m ON m.max_id = a.id
WHERE a.is_revision = {$is_revision}
{$excludeList}
ORDER BY a.id DESC
";
$res = mysqli_query($con, $q);

/** D) Output <tr> */
while ($row = mysqli_fetch_assoc($res)):
  $reviN_last = first_non_empty([$row['drevisi5'], $row['drevisi4'], $row['drevisi3'], $row['drevisi2'], $row['revisin']]);
  $reviC_last = first_non_empty([$row['revisi5'], $row['revisi4'], $row['revisi3'], $row['revisi2'], $row['revisic']]);
  $codeApp = strtoupper(trim($row['code']));
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
    <a href="#" class="btn btn-primary btn-sm open-detail" data-code="<?= htmlspecialchars($row['code']) ?>" data-toggle="modal" data-target="#detailModal">
      <?= htmlspecialchars($row['code']) ?>
    </a>
  </td>
  <td><?= htmlspecialchars($row['tgl_approve_rmp']) ?></td>
  <td><?= htmlspecialchars($row['tgl_approve_lab']) ?></td>
  <td><?= htmlspecialchars($row['tgl_rejected_lab']) ?></td>
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