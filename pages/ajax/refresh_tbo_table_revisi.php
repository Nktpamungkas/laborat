<?php
error_reporting(E_ERROR | E_PARSE);
header('Content-Type: text/html; charset=utf-8');

require_once '../../koneksi.php';
require_once '../lib/revisi_compare.php';

$qSnap = "
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
$resSnap = mysqli_query($con, $qSnap);

$lastMySQLByCode = [];
if ($resSnap) {
    while ($r = mysqli_fetch_assoc($resSnap)) {
        $lastMySQLByCode[strtoupper(trim($r['code']))] = $r;
    }
}

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

    LEFT JOIN ADSTORAGE aC ON aC.UNIQUEID = s.ABSUNIQUEID AND aC.FIELDNAME = 'RevisiC'
    LEFT JOIN ADADDITIONALDATA adC ON adC.NAME = aC.FIELDNAME

    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = s.ABSUNIQUEID AND a2.FIELDNAME = 'Revisi2'
    LEFT JOIN ADADDITIONALDATA ad2 ON ad2.NAME = a2.FIELDNAME

    LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = s.ABSUNIQUEID AND a3.FIELDNAME = 'Revisi3'
    LEFT JOIN ADADDITIONALDATA ad3 ON ad3.NAME = a3.FIELDNAME

    LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s.ABSUNIQUEID AND a4.FIELDNAME = 'Revisi4'
    LEFT JOIN ADADDITIONALDATA ad4 ON ad4.NAME = a4.FIELDNAME

    LEFT JOIN ADSTORAGE a5 ON a5.UNIQUEID = s.ABSUNIQUEID AND a5.FIELDNAME = 'Revisi5'
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
$resDB2 = db2_exec($conn1, $sqlTBO, ['cursor'=>DB2_SCROLLABLE]);

$tboRows = [];
while ($row = db2_fetch_assoc($resDB2)) {
    $code = strtoupper(trim($row['CODE']));
    $snap = $lastMySQLByCode[$code] ?? null;
    if ($snap === null || revisionsDiffer($row, $snap)) {
        $tboRows[] = $row;
    }
}

foreach ($tboRows as $row):
    $code = strtoupper(trim($row['CODE']));
    $customer = trim($row['CUSTOMER']);
    $tgl = trim($row['TGL_APPROVE_RMP']);
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
    <a href="#" class="btn btn-primary btn-sm open-detail" data-code="<?= $code ?>" data-toggle="modal" data-target="#detailModal"><?= $code ?></a>
  </td>
  <td><?= htmlspecialchars($tgl) ?></td>
  <td>
    <div class="d-flex align-items-center gap-2">
      <select class="form-control form-control-sm pic-select" data-code="<?= $code ?>">
        <option value="">-- Pilih PIC --</option>
        <?php
        $resultPIC = mysqli_query($con, "SELECT * FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC");
        while ($rowPIC = mysqli_fetch_assoc($resultPIC)): ?>
          <option value="<?= htmlspecialchars($rowPIC['username']) ?>"><?= htmlspecialchars($rowPIC['username']) ?></option>
        <?php endwhile; ?>
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
<?php endforeach; ?>
