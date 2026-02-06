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
/* ===== Helper ===== */
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

/* ===== Ambil data per stage (UNION) ===== */
$sql = "
SELECT
  ps.no_resep                                              AS no_resep,
  'Preliminary'                                            AS stage,
  sm.timer                                                 AS timer,
  UPPER(ps.username)                                       AS user_pre,
  UPPER(ps.user_dispensing)                                AS user_dis,
  UPPER(ps.user_dyeing)                                    AS user_dye,
  UPPER(COALESCE(ps.user_darkroom_end, ps.user_darkroom_start)) AS user_drk,
  ps.is_test
FROM tbl_preliminary_schedule ps
JOIN tbl_status_matching sm
  ON sm.idm = (CASE WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep,2) IN ('-A','-B')
                    THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep)-2)
                    ELSE ps.no_resep END)
WHERE DATE(ps.creationdatetime) = ?
  AND sm.timer IS NOT NULL AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,'Dispensing',sm.timer,
  UPPER(ps.username),UPPER(ps.user_dispensing),UPPER(ps.user_dyeing),
  UPPER(COALESCE(ps.user_darkroom_end, ps.user_darkroom_start)), ps.is_test
FROM tbl_preliminary_schedule ps
JOIN tbl_status_matching sm
  ON sm.idm = (CASE WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep,2) IN ('-A','-B')
                    THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep)-2)
                    ELSE ps.no_resep END)
WHERE DATE(ps.dispensing_start) = ?
  AND sm.timer IS NOT NULL AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,'Dyeing',sm.timer,
  UPPER(ps.username),UPPER(ps.user_dispensing),UPPER(ps.user_dyeing),
  UPPER(COALESCE(ps.user_darkroom_end, ps.user_darkroom_start)), ps.is_test
FROM tbl_preliminary_schedule ps
JOIN tbl_status_matching sm
  ON sm.idm = (CASE WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep,2) IN ('-A','-B')
                    THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep)-2)
                    ELSE ps.no_resep END)
WHERE DATE(ps.dyeing_start) = ?
  AND sm.timer IS NOT NULL AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,'Darkroom',sm.timer,
  UPPER(ps.username),UPPER(ps.user_dispensing),UPPER(ps.user_dyeing),
  UPPER(COALESCE(ps.user_darkroom_end, ps.user_darkroom_start)), ps.is_test
FROM tbl_preliminary_schedule ps
JOIN tbl_status_matching sm
  ON sm.idm = (CASE WHEN ps.no_resep LIKE 'DR%' AND RIGHT(ps.no_resep,2) IN ('-A','-B')
                    THEN LEFT(ps.no_resep, CHAR_LENGTH(ps.no_resep)-2)
                    ELSE ps.no_resep END)
WHERE ((ps.darkroom_start IS NOT NULL AND DATE(ps.darkroom_start)=?)
    OR (ps.darkroom_end   IS NOT NULL AND DATE(ps.darkroom_end)  =?))
  AND sm.timer IS NOT NULL AND sm.timer <> ''
ORDER BY FIELD(stage,'Preliminary','Dispensing','Dyeing','Darkroom'), no_resep
";
$stmt = $con->prepare($sql);
if (!$stmt) die("Prepare gagal: ".$con->error);
$stmt->bind_param("sssss", $kemarin,$kemarin,$kemarin,$kemarin,$kemarin);
$stmt->execute();
$res = $stmt->get_result();

/* ===== Agregasi per STAGE & USER ===== */
$stageUser = [
  'Preliminary'=>[], 'Dispensing'=>[], 'Dyeing'=>[], 'Darkroom'=>[]
];

// mapping urutan prioritas stage
$stageIndexMap = [
    'Preliminary' => 0,
    'Dispensing'  => 1,
    'Dyeing'      => 2,
    'Darkroom'    => 3,
];

// untuk aturan:
// - per user+base_job, hanya stage paling awal yang dihitung
$jobFirstStage = [];

// untuk menghindari double count dalam 1 stage untuk user+base_job yang sama
$seen = [];

while ($row = $res->fetch_assoc()){
  $stage  = $row['stage'];

  // full job & base job (tanpa -A/-B)
  $jobFull = $row['no_resep'];
  $baseJob = $jobFull;
  if (preg_match('/^DR.+-(A|B)$/', $jobFull)) {
      $baseJob = substr($jobFull, 0, -2); // DRxxxxx saja
  }

  $isTest = (int)$row['is_test'] === 1;
  // SEMENTARA IS_TEST = 1 DILEWATI SAJA
  // if ($isTest) continue;

  // ambil user sesuai stage
  $user = '';
  if ($stage==='Preliminary')      $user = trim($row['user_pre']);
  elseif ($stage==='Dispensing')   $user = trim($row['user_dis']);
  elseif ($stage==='Dyeing')       $user = trim($row['user_dye']);
  else                             $user = trim($row['user_drk']);
  if ($user==='') continue;

  // index prioritas stage
  $idx = isset($stageIndexMap[$stage]) ? $stageIndexMap[$stage] : 999;

  // === PRIORITAS STAGE BERANTAI PER USER ===
  // Jika base job untuk user ini sudah pernah muncul di stage lebih awal,
  // baris ini di-skip (tidak dihitung lagi di stage berikutnya).
  if (isset($jobFirstStage[$user][$baseJob])) {
      $firstIdx = $jobFirstStage[$user][$baseJob];

      if ($idx > $firstIdx) {
          // stage sekarang lebih akhir → abaikan
          continue;
      } elseif ($idx < $firstIdx) {
          // kalau (secara teori) ketemu stage yang lebih awal belakangan,
          // update prioritas ke yang lebih awal
          $jobFirstStage[$user][$baseJob] = $idx;
      }
  } else {
      // pertama kali lihat job ini untuk user ini
      $jobFirstStage[$user][$baseJob] = $idx;
  }

  // === DEDUPE DALAM STAGE YANG SAMA (user + baseJob) ===
  // DRxxxx-A & DRxxxx-B → dianggap 1 job di stage ini per user
  if (isset($seen[$stage][$user][$baseJob])) {
      continue;
  }
  $seen[$stage][$user][$baseJob] = true;

  // semua baris (test & non-test) yang lolos aturan di atas ikut dihitung point
  $points   = hours_to_points(timer_to_hours($row['timer']));
  $possible = 10;

  if (!isset($stageUser[$stage][$user])) {
    $stageUser[$stage][$user] = ['sumA'=>0,'sumP'=>0];
  }
  $stageUser[$stage][$user]['sumA'] += $points;
  $stageUser[$stage][$user]['sumP'] += $possible;
}

$stmt->close();

/* ===== Sort user A–Z di tiap stage ===== */
foreach ($stageUser as $st => $arr){
  ksort($arr, SORT_NATURAL | SORT_FLAG_CASE);
  $stageUser[$st] = $arr;
}

/* ===== Gabung per USER + tentukan stage prioritas tampil ===== */
$orderStages = ['Preliminary','Dispensing','Dyeing','Darkroom'];

$userAgg = []; // key: user
foreach ($orderStages as $st) {
  if (empty($stageUser[$st])) continue;

  foreach ($stageUser[$st] as $user => $tot) {
    if (!isset($userAgg[$user])) {
      $userAgg[$user] = [
        'sumA'      => 0,
        'sumP'      => 0,
        'bestStage' => $st,
        'bestIdx'   => array_search($st, $orderStages, true)
      ];
    }

    // akumulasi poin semua job yang lolos aturan chain
    $userAgg[$user]['sumA'] += $tot['sumA'];
    $userAgg[$user]['sumP'] += $tot['sumP'];

    // stage tampil: pilih yang paling kecil
    $idxNow = array_search($st, $orderStages, true);
    if ($idxNow < $userAgg[$user]['bestIdx']) {
      $userAgg[$user]['bestStage'] = $st;
      $userAgg[$user]['bestIdx']   = $idxNow;
    }
  }
}

/* ===== Susun kembali per STAGE untuk tampilan ===== */
$stageDisplay = [];
foreach ($orderStages as $st) {
  $stageDisplay[$st] = [];
}

foreach ($userAgg as $user => $info) {
  $st    = $info['bestStage']; // stage prioritas untuk tampilan
  $ratio = ($info['sumP'] > 0) ? $info['sumA'] / $info['sumP'] : 0;

  $stageDisplay[$st][] = [
    'user'  => $user,
    'ratio' => $ratio
  ];
}

/* Sort user A–Z di dalam masing-masing stage */
foreach ($stageDisplay as $st => $rows) {
  usort($rows, function($a, $b) {
    return strnatcasecmp($a['user'], $b['user']);
  });
  $stageDisplay[$st] = $rows;
}
?>

<div class="col-md-6">
  <div class="box">
    <h4 class="text-center" style="font-weight:bold;">
      REKAP POINTS AWARDED H-1 (<?= htmlspecialchars($kemarin); ?>)
    </h4>

    <table class="table table-chart" style="width:100%;">
      <thead class="table-secondary">
        <tr class="text-center" style="background:#eee;">
          <th style="text-align:center; width:28%;">STAGE</th>
          <th style="text-align:center; width:44%;">NAMA</th>
          <th style="text-align:center; width:28%;">POINT</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $printedAny = false;

        foreach ($orderStages as $st) {
          $rows = $stageDisplay[$st];
          if (empty($rows)) continue;

          $printedAny = true;
          $rowspan = count($rows);

          foreach ($rows as $i => $row) {
            echo '<tr>';

            // Kolom STAGE hanya dicetak sekali (rowspan)
            if ($i === 0) {
              echo '<td rowspan="'.$rowspan.'" style="vertical-align:middle;font-weight:bold;">'
                    . htmlspecialchars($st) .
                  '</td>';
            }

            echo '<td>'.htmlspecialchars($row['user']).'</td>';
            echo '<td class="text-center" style="background:#cfe8ff;font-weight:bold;">'
                    . number_format($row['ratio'], 4, '.', '') .
                 '</td>';
            echo '</tr>';
          }
        }

        if (!$printedAny) {
          echo '<tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
