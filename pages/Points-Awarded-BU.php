<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "koneksi.php";

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

/* ===== Tanggal (default H-1) ===== */
$today      = new DateTime('today');
$defaultDay = (clone $today)->modify('-1 day')->format('Y-m-d');
$filterDate = (isset($_GET['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date'])) ? $_GET['date'] : $defaultDay;

/* ===== Query per stage (UNION) =====
   - Ambil dari tbl_preliminary_schedule per kolom tanggal stage
   - Join ke tbl_status_matching pakai base no_resep (hapus -A/-B bila awalan DR)
   - Hanya ambil yang timer-nya ada (NOT NULL/NOT '')
*/
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
        ps.darkroom_start IS NOT NULL AND DATE(ps.darkroom_start) = ?
      ) OR (
        ps.darkroom_end   IS NOT NULL AND DATE(ps.darkroom_end)   = ?
      )
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

ORDER BY no_resep, stage
";

$stmt = $con->prepare($sql);
if (!$stmt) die("Query prepare gagal: ".$con->error);
$stmt->bind_param("sssss", $filterDate, $filterDate, $filterDate, $filterDate, $filterDate);
$stmt->execute();
$res = $stmt->get_result();

/* ===== Struktur data: de-dupe per JOB (no_resep) per user & stage
   $data[stage][USER][JOB] = [
       'awarded' => int|null,
       'possible'=> int|null,
       't_awarded' => ''|int,
       't_possible'=> ''|int,
   ]
*/
$data = [
  'Preliminary' => [],
  'Dispensing'  => [],
  'Dyeing'      => [],
  'Darkroom'    => [],
];

while ($row = $res->fetch_assoc()) {
    $job       = $row['no_resep']; // pakai no_resep ASLI (bisa -A/-B)
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

/* ===== Render ===== */
function render_stage($title, $bucket){
    if (empty($bucket)) return;

    echo '<h3 class="text-center" style="margin-top:25px;margin-bottom:10px;">'.$title.'</h3>';
    echo '<div class="user-scroller">';

    foreach ($bucket as $user => $rowsAssoc) {
        echo '<div class="user-card">';
        echo '  <div class="panel panel-default">';
        echo '    <div class="panel-heading text-center" style="font-weight:bold;">'.htmlspecialchars($user).'</div>';
        echo '    <div class="panel-body" style="padding:0;">';
        echo '      <table class="table table-bordered table-condensed" style="margin-bottom:0;">';
        echo '        <thead>';
        echo '          <tr class="active">';
        echo '            <th class="text-center" style="width:40%;">JOB</th>';
        echo '            <th class="text-center" style="width:12%;">POINTS<br>AWARDED</th>';
        echo '            <th class="text-center" style="width:12%;">POSSIBLE<br>POINTS</th>';
        echo '            <th class="text-center" style="width:12%;">TEST REPORT<br>POINTS AWARDED</th>';
        echo '            <th class="text-center" style="width:12%;">TEST REPORT<br>POSSIBLE POINTS</th>';
        echo '          </tr>';
        echo '        </thead>';
        echo '        <tbody>';

        $totA = 0; $totP = 0;     // normal
        $totTA = 0; $totTP = 0;   // test

        foreach ($rowsAssoc as $job => $r) {
            $aw  = ($r['awarded']  === null ? '' : (int)$r['awarded']);
            $ps  = ($r['possible'] === null ? '' : (int)$r['possible']);
            $taw = ($r['t_awarded']  === '' ? '' : (int)$r['t_awarded']);
            $tps = ($r['t_possible'] === '' ? '' : (int)$r['t_possible']);

            // akumulasi total (anggap kosong = 0)
            $totA  += (int)($r['awarded']  ?? 0);
            $totP  += (int)($r['possible'] ?? 0);
            $totTA += (int)(($r['t_awarded']  === '' ? 0 : $r['t_awarded']));
            $totTP += (int)(($r['t_possible'] === '' ? 0 : $r['t_possible']));

            echo '<tr>';
            echo '  <td>'.htmlspecialchars($job).'</td>';
            echo '  <td class="text-center">'.$aw.'</td>';
            echo '  <td class="text-center">'.$ps.'</td>';
            echo '  <td class="text-center">'.$taw.'</td>';
            echo '  <td class="text-center">'.$tps.'</td>';
            echo '</tr>';
        }

        // --- Total (test 0 jika kosong) ---
        echo '<tr class="active">';
        echo '  <td class="text-right"><strong>Total</strong></td>';
        echo '  <td class="text-center"><strong>'.$totA.'</strong></td>';
        echo '  <td class="text-center"><strong>'.$totP.'</strong></td>';
        echo '  <td class="text-center"><strong>'.($totTA ?: 0).'</strong></td>';
        echo '  <td class="text-center"><strong>'.($totTP ?: 0).'</strong></td>';
        echo '</tr>';

        // --- Ratio = (normal+test) / (normal+test) di baris paling bawah, kolom terakhir ---
        $sumAwarded  = $totA + $totTA;
        $sumPossible = $totP + $totTP;
        $ratio = $sumPossible > 0 ? ($sumAwarded / $sumPossible) : 0;

        echo '<tr>';
        echo '  <td class="text-right"><strong>Ratio</strong></td>';
        echo '  <td></td>';
        echo '  <td></td>';
        echo '  <td></td>';
        echo '  <td class="text-center" style="background:#cfe8ff;font-weight:bold;">'.number_format($ratio,4,'.','').'</td>';
        echo '</tr>';

        echo '        </tbody>';
        echo '      </table>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }

    echo '</div>';
}
?>
<style>
  .user-scroller {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    white-space: nowrap;
    padding-bottom: 6px;
  }
  .user-card {
    display: inline-block;
    vertical-align: top;
    width: 560px;
    margin-right: 12px;
  }
  @media (max-width: 768px){
    .user-card { width: 520px; }
  }
</style>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <form class="form-inline" method="get" action="">
          <input type="hidden" name="p" value="Points-Awarded">
          <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" id="date" name="date" class="form-control input-sm"
                   value="<?php echo htmlspecialchars($filterDate); ?>">
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
          <!-- <span class="help-block" style="margin:5px 0 0 0;">Default H-1 (<?php echo htmlspecialchars($defaultDay); ?>)</span> -->
        </form>
      </div>

      <div class="box-body">
        <?php
          render_stage('PRELIMINARY', $data['Preliminary']);
          render_stage('DISPENSING',  $data['Dispensing']);
          render_stage('DYEING',      $data['Dyeing']);
          render_stage('DARKROOM',    $data['Darkroom']);
        ?>

        <?php if (empty($data['Preliminary']) && empty($data['Dispensing']) && empty($data['Dyeing']) && empty($data['Darkroom'])): ?>
          <div class="alert alert-info" style="margin-top:15px;">
            Tidak ada data untuk tanggal <strong><?php echo htmlspecialchars($filterDate); ?></strong>.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>