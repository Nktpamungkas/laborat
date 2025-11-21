<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';

$kemarin = date('Y-m-d', strtotime('-1 day'));
$today = date('Y-m-d');

$todays = date('N'); // 1 = Senin, 7 = Minggu

if ($todays == 1) {
    // Hari ini Senin, jadi kemarin dianggap Hari Sabtu (2 hari sebelumnya)
    $kemarin = date('Y-m-d', strtotime('-2 days'));
} else {
    // Hari selain Senin, kemarin = 1 hari sebelum hari ini
    $kemarin = date('Y-m-d', strtotime('-1 day'));
    // $kemarin = "2025-09-23";
}

$tanggalAwal = '2025-06-01';

// Ambil semua PIC
$rekap = [];
$resPIC = mysqli_query($con, "SELECT username FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($resPIC)) {
    $pic = $row['username'];
    $rekap[$pic] = [
        'approved' => 0,
        'reject' => 0,
        'matching_ulang' => 0,
        'ok' => 0
    ];
}

$sqlApproved = "SELECT * FROM approval_bon_order WHERE tgl_approve_lab ='$kemarin' ORDER BY id DESC";
$resultApproved = mysqli_query($con, $sqlApproved);
$approve_today = mysqli_num_rows($resultApproved);
// echo mysqli_num_rows($resultApproved);

// Rekap Approved & Rejected dari approval_bon_order
// $resApproval = mysqli_query($con, "SELECT
//                                         pic_lab,
//                                         `status`
//                                     FROM
//                                         approval_bon_order
//                                     WHERE
//                                         (STATUS = 'Approved'
//                                         AND tgl_approve_lab between '$kemarin' AND '$today')
//                                         OR (STATUS = 'Rejected'
//                                             AND tgl_rejected_lab between '$kemarin' AND '$today')
//                                             ");
$resApproval = mysqli_query($con, "SELECT
                                        pic_lab,
                                        `status`
                                    FROM
                                        approval_bon_order
                                    WHERE
                                        (STATUS = 'Approved'
                                        AND tgl_approve_lab = '$kemarin')
                                        OR (STATUS = 'Rejected'
                                            AND tgl_rejected_lab = '$kemarin')
                                            ");

while ($row = mysqli_fetch_assoc($resApproval)) {
    $pic = $row['pic_lab'];
    $status = strtolower(trim($row['status']));

    if (!isset($rekap[$pic])) {
        $rekap[$pic] = [
            'approved' => 0,
            'reject' => 0,
            'matching_ulang' => 0,
            'ok' => 0
        ];
    }

    if ($status === 'approved') {
        $rekap[$pic]['approved'] += 1;
    } elseif ($status === 'rejected') {
        $rekap[$pic]['reject'] += 1;
    }
}

// Rekap status_matching_bon_order JOIN approval_bon_order (ambil yg code match & sesuai tanggal H-1)
// $resStatus = mysqli_query($con, "SELECT 
//                                     smb.pic_check, 
//                                     LOWER(TRIM(smb.status_bonorder)) AS status_bonorder
//                                 FROM status_matching_bon_order smb
//                                 JOIN approval_bon_order ab ON ab.code = smb.salesorder
//                                 WHERE 
//                                     (
//                                         (ab.status = 'Approved' AND ab.tgl_approve_lab between '$kemarin' and '$today') OR
//                                         (ab.status = 'Rejected' AND ab.tgl_rejected_lab between '$kemarin' and '$today')
//                                     )
//                             ");
$resStatus = mysqli_query($con, "SELECT 
                                    smb.pic_check, 
                                    LOWER(TRIM(smb.status_bonorder)) AS status_bonorder
                                FROM status_matching_bon_order smb
                                JOIN approval_bon_order ab ON ab.code = smb.salesorder
                                WHERE 
                                    (
                                        (ab.status = 'Approved' AND ab.tgl_approve_lab =  '$kemarin') OR
                                        (ab.status = 'Rejected' AND ab.tgl_rejected_lab =  '$kemarin')
                                    )
                            ");

while ($row = mysqli_fetch_assoc($resStatus)) {
    $pic = $row['pic_check'];
    $status = $row['status_bonorder'];

    if (!isset($rekap[$pic])) {
        $rekap[$pic] = [
            'approved' => 0,
            'reject' => 0,
            'matching_ulang' => 0,
            'ok' => 0
        ];
    }

    if ($status === 'matching ulang' || $status === 'matching_ulang') {
        $rekap[$pic]['matching_ulang'] += 1;
    } elseif ($status === 'ok') {
        $rekap[$pic]['ok'] += 1;
    }
}

// Total Bon Order diterima H-1 (via query dari ITXVIEW)
$approvedCodes = [];
$resCode = mysqli_query($con, "SELECT code FROM approval_bon_order");
while ($r = mysqli_fetch_assoc($resCode)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}
$codeList = implode(",", $approvedCodes);

$sqlTBO1 = "SELECT DISTINCT 
                isa.CODE AS CODE,
                COALESCE(ip.LANGGANAN, '') || COALESCE(ip.BUYER, '') AS CUSTOMER,
                isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP,
                VARCHAR_FORMAT(a.VALUETIMESTAMP, 'YYYY-MM-DD HH24:MI:SS') AS ApprovalRMPDateTime
            FROM ITXVIEW_SALESORDER_APPROVED isa
            LEFT JOIN SALESORDER s
                ON s.CODE = isa.CODE
            LEFT JOIN ITXVIEW_PELANGGAN ip
                ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                AND ip.CODE = s.CODE
            LEFT JOIN ADSTORAGE a
                ON a.UNIQUEID = s.ABSUNIQUEID
                AND a.FIELDNAME = 'ApprovalRMPDateTime'
            WHERE a.VALUETIMESTAMP IS NOT NULL
                AND DATE(a.VALUETIMESTAMP) = '$kemarin'
";
if (!empty($codeList)) {
    $sqlTBO1 .= " AND isa.CODE NOT IN ($codeList)";
}

$resultTBO1 = db2_exec($conn1, $sqlTBO1, ['cursor' => DB2_SCROLLABLE]);
$totalH11 = db2_num_rows($resultTBO1);

$totalH1 = $approve_today + $totalH11;
// Hitung total per status
$totalApproved = $totalReject = $totalMatchingUlang = $totalOK = 0;
foreach ($rekap as $data) {
    $totalApproved += $data['approved'];
    $totalReject += $data['reject'];
    $totalMatchingUlang += $data['matching_ulang'];
    $totalOK += $data['ok'];
}

$sisaReview = $totalH1 - ($totalApproved + $totalReject);
?>

<div class="col-md-6">
    <div class="box">
        <h4 class="text-center" style="font-weight: bold;">REKAP STATUS BON ORDER <span class="text-center" style="font-weight: bold;">H-1 (<?=$kemarin; ?>)</span></h4>

        <table class="table table-chart">
            <thead class="table-secondary">
                <tr class="text-center">
                    <th style="text-align: center;">PIC</th>
                    <th style="text-align: center;">Approved</th>
                    <!-- <th style="text-align: center;">Reject</th> -->
                    <th style="text-align: center;">Matching Ulang</th>
                    <th style="text-align: center;">OK</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rekap as $pic => $data): ?>
                    <tr>
                        <td><?= htmlspecialchars($pic) ?></td>
                        <td class="text-center"><?= $data['approved'] ?></td>
                        <!-- <td class="text-center"><?= $data['reject'] ?></td> -->
                        <td class="text-center"><?= $data['matching_ulang'] ?></td>
                        <td class="text-center"><?= $data['ok'] ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr class="fw-bold table-light">
                    <th>Total</th>
                    <th style="text-align: center;"><?= $totalApproved ?></th>
                    <!-- <th style="text-align: center;"><?= $totalReject ?></th> -->
                    <th style="text-align: center;"><?= $totalMatchingUlang ?></th>
                    <th style="text-align: center;"><?= $totalOK ?></th>
                </tr>
                <tr class="table-warning fw-bold">
                    <th>Total Bon Order Diterima H-1</th>
                    <th colspan="4" style="text-align: center;"><?= $totalH1 ?></th>
                    <!-- <th colspan="4" style="text-align: center;"><?= $totalH1 ?></th> -->
                </tr>
                <tr class="table-danger fw-bold">
                    <th>Sisa Bon Order Belum Direview</th>
                    <th colspan="4" style="text-align: center;"><?= $totalH11 ?></th>
                    <!-- <th colspan="4" style="text-align: center;"><?= max(0, $sisaReview) ?></th> -->
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
/* ===== Helpers ===== */
function timer_to_hours($s){
    $s = strtolower((string)$s);
    $hari=0;$jam=0;$menit=0;
    if (preg_match('/(\d+)\s*hari/',$s,$m))  $hari=(int)$m[1];
    if (preg_match('/(\d+)\s*jam/',$s,$m))   $jam=(int)$m[1];
    if (preg_match('/(\d+)\s*menit/',$s,$m)) $menit=(int)$m[1];
    return ($hari*24)+$jam+($menit/60);
}
function hours_to_points($h){
    if ($h < 24)   return 10;
    if ($h <= 48)  return 9;
    if ($h <= 72)  return 8;
    if ($h <= 96)  return 7;
    if ($h <= 120) return 6;
    if ($h <= 144) return 5;
    if ($h <= 168) return 4;
    if ($h <= 192) return 3;
    if ($h <= 216) return 2;
    if ($h <= 240) return 1;
    return 0;
}
function compute_user_totals(array $data, string $user){
    $stages = ['Preliminary','Dispensing','Dyeing','Darkroom'];
    $totA = $totP = $totTA = $totTP = 0;
    foreach ($stages as $st){
        $rows = $data[$st][$user] ?? [];
        foreach ($rows as $r){
            $totA  += (int)($r['awarded']  ?? 0);
            $totP  += (int)($r['possible'] ?? 0);
            $totTA += (int)(($r['t_awarded']  === '' ? 0 : $r['t_awarded']));
            $totTP += (int)(($r['t_possible'] === '' ? 0 : $r['t_possible']));
        }
    }
    $sumA = $totA + $totTA;
    $sumP = $totP + $totTP;
    $ratio = $sumP > 0 ? ($sumA / $sumP) : 0;
    return compact('totA','totP','totTA','totTP','sumA','sumP','ratio');
}

/* ===== Query per stage (UNION) — sama seperti page sebelumnya ===== */
$sql = "
SELECT
  ps.no_resep                                              AS no_resep,
  'Preliminary'                                            AS stage,
  sm.timer                                                 AS timer,
  ps.username,
  ps.user_dispensing,
  ps.user_dyeing,
  COALESCE(ps.user_darkroom_end, ps.user_darkroom_start)   AS user_darkroom,
  ps.is_test
FROM tbl_preliminary_schedule ps
INNER JOIN tbl_status_matching sm
  ON sm.idm = (
      CASE
        WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep, 2) IN ('-A','-B')
          THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep) - 2)
        ELSE ps.no_resep
      END
  )
WHERE DATE(ps.creationdatetime) = ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  'Dispensing' AS stage,
  sm.timer,
  ps.username,
  ps.user_dispensing,
  ps.user_dyeing,
  COALESCE(ps.user_darkroom_end, ps.user_darkroom_start) AS user_darkroom,
  ps.is_test
FROM tbl_preliminary_schedule ps
INNER JOIN tbl_status_matching sm
  ON sm.idm = (
      CASE
        WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep, 2) IN ('-A','-B')
          THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep) - 2)
        ELSE ps.no_resep
      END
  )
WHERE DATE(ps.dispensing_start) = ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  'Dyeing' AS stage,
  sm.timer,
  ps.username,
  ps.user_dispensing,
  ps.user_dyeing,
  COALESCE(ps.user_darkroom_end, ps.user_darkroom_start) AS user_darkroom,
  ps.is_test
FROM tbl_preliminary_schedule ps
INNER JOIN tbl_status_matching sm
  ON sm.idm = (
      CASE
        WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep, 2) IN ('-A','-B')
          THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep) - 2)
        ELSE ps.no_resep
      END
  )
WHERE DATE(ps.dyeing_start) = ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  'Darkroom' AS stage,
  sm.timer,
  ps.username,
  ps.user_dispensing,
  ps.user_dyeing,
  COALESCE(ps.user_darkroom_end, ps.user_darkroom_start) AS user_darkroom,
  ps.is_test
FROM tbl_preliminary_schedule ps
INNER JOIN tbl_status_matching sm
  ON sm.idm = (
      CASE
        WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep, 2) IN ('-A','-B')
          THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep) - 2)
        ELSE ps.no_resep
      END
  )
WHERE (
        (ps.darkroom_start IS NOT NULL AND DATE(ps.darkroom_start) = ?)
     OR (ps.darkroom_end   IS NOT NULL AND DATE(ps.darkroom_end)   = ?)
      )
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

ORDER BY no_resep, stage
";

$stmt = $con->prepare($sql);
if (!$stmt) die("Query prepare gagal: ".$con->error);
$stmt->bind_param("sssss", $kemarin, $kemarin, $kemarin, $kemarin, $kemarin);
$stmt->execute();
$res = $stmt->get_result();

$data = [
  'Preliminary' => [],
  'Dispensing'  => [],
  'Dyeing'      => [],
  'Darkroom'    => [],
];

while ($row = $res->fetch_assoc()) {
    $job       = $row['no_resep'];      // pakai no_resep asli (bisa -A/-B)
    $hours     = timer_to_hours($row['timer']);
    $points    = hours_to_points($hours);
    $possible  = 10;
    $isTest    = ((int)$row['is_test'] === 1);
    $stage     = $row['stage'];

    // helper simpan per user & job
    $put = function (&$bucket, $user) use ($job, $points, $possible, $isTest) {
        $user = strtoupper(trim((string)$user));
        if ($user === '') return;
        if (!isset($bucket[$user][$job])) {
            $bucket[$user][$job] = [
                'awarded'    => null,
                'possible'   => null,
                't_awarded'  => '',
                't_possible' => '',
            ];
        }
        if ($isTest) {
            $bucket[$user][$job]['t_awarded']  = $points;
            $bucket[$user][$job]['t_possible'] = $possible;
        } else {
            $bucket[$user][$job]['awarded']  = $points;
            $bucket[$user][$job]['possible'] = $possible;
        }
    };

    switch ($stage) {
        case 'Preliminary':
            $put($data['Preliminary'], $row['username']);
            break;
        case 'Dispensing':
            $put($data['Dispensing'],  $row['user_dispensing']);
            break;
        case 'Dyeing':
            $put($data['Dyeing'],      $row['user_dyeing']);
            break;
        case 'Darkroom':
            $put($data['Darkroom'],    $row['user_darkroom']); // COALESCE end/start
            break;
    }
}
$stmt->close();

/* ===== Susun daftar user & stage dari $data, dan hitung POINT per user ===== */
$stageOrder = ['Preliminary','Dispensing','Dyeing','Darkroom'];
$userStageList = []; // [USER] => set stage
$allUsers = [];

foreach ($stageOrder as $st){
    foreach ($data[$st] as $user => $rowsAssoc){
        if ($user==='' || empty($rowsAssoc)) continue;
        $allUsers[$user] = true;
        if (!isset($userStageList[$user])) $userStageList[$user] = [];
        $userStageList[$user][$st] = true;
    }
}
$allUsers = array_keys($allUsers);
sort($allUsers, SORT_NATURAL | SORT_FLAG_CASE);

$userTotals = []; // [USER] => totals (sumA,sumP,ratio)
foreach ($allUsers as $u){
    $userTotals[$u] = compute_user_totals($data, $u);
}
?>
<style>
    .table-points-awarded {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    .table-points-awarded td,
    .table-points-awarded th {
      border: 1px solid #ababab;
      padding: 8px;
      text-align: center;
    }
    .table-points-awarded th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #0068c2;
      color: white;
    }
</style>
<div class="col-md-6">
  <div class="box">
    <h4 class="text-center" style="font-weight:bold;">
      REKAP POINTS AWARDED H-1 (<?= htmlspecialchars($kemarin); ?>)
    </h4>

    <table class="table table-points-awarded" style="width:100%;">
      <thead class="table-secondary">
        <tr class="text-center" style="background:#eee;">
          <th style="text-align:center; width:40%;">USER</th>
          <th style="text-align:center; width:40%;">STAGE</th>
          <th style="text-align:center; width:20%;">POINT</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($allUsers)): ?>
          <?php foreach ($allUsers as $user): ?>
            <?php
              // stage untuk user ini (urut sesuai stageOrder)
              $stList = [];
              foreach ($stageOrder as $st) {
                if (isset($userStageList[$user][$st])) $stList[] = $st;
              }
              if (empty($stList)) continue;

              $rowspan   = count($stList);
              $totals    = $userTotals[$user] ?? ['sumA'=>0,'sumP'=>0,'ratio'=>0];
              $ratioText = number_format((float)$totals['ratio'], 4, '.', ''); // 4 desimal
            ?>
            <?php foreach ($stList as $i => $stageName): ?>
              <tr>
                <?php if ($i === 0): ?>
                  <td rowspan="<?= $rowspan; ?>" style="vertical-align:middle; font-weight:bold;">
                    <?= htmlspecialchars($user); ?>
                  </td>
                <?php endif; ?>

                <td><?= htmlspecialchars($stageName); ?></td>

                <?php if ($i === 0): ?>
                  <td rowspan="<?= $rowspan; ?>" class="text-center"
                      style="vertical-align:middle; background:#cfe8ff; font-weight:bold;">
                    <?= $ratioText; ?>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>