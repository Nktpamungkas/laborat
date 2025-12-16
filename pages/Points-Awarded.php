<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "koneksi.php";

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
function slug_id($s){
    $s = strtoupper(trim((string)$s));
    if ($s==='') return 'NA';
    $s = preg_replace('/[^A-Z0-9]+/', '-', $s);
    return trim($s, '-');
}
/* Normalisasi no_resep: hilangkan -A/-B jika awalan DR */
function base_job($job){
    $job = (string)$job;
    if (stripos($job, 'DR') === 0 && (substr($job, -2) === '-A' || substr($job, -2) === '-B')) {
        return substr($job, 0, -2);
    }
    return $job;
}

/* ===== Tanggal (default H-1) ===== */
$today      = new DateTime('today');
$todays     = $todays = date('N');
if ($todays == 1) {
  $defaultDay = (clone $today)->modify('-2 day')->format('Y-m-d');
} else {
  $defaultDay = (clone $today)->modify('-1 day')->format('Y-m-d');
}
$filterDate = (isset($_GET['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date'])) ? $_GET['date'] : $defaultDay;

/* ===== Query per stage (UNION) ===== */
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
$stmt->bind_param("sssss", $filterDate, $filterDate, $filterDate, $filterDate, $filterDate);
$stmt->execute();
$res = $stmt->get_result();

/* ===== Struktur data (pakai base_job sebagai key) =====
   $data[stage][USER][BASE_JOB] = [
       'awarded'    => int|null,   // is_test=0
       'possible'   => int|null,
       't_awarded'  => ''|int,     // is_test=1
       't_possible' => ''|int
   ]
*/
$stageOrder = ['Preliminary','Dispensing','Dyeing','Darkroom'];
$data = [
  'Preliminary' => [],
  'Dispensing'  => [],
  'Dyeing'      => [],
  'Darkroom'    => [],
];

while ($row = $res->fetch_assoc()) {
    $origJob  = $row['no_resep'];
    $base     = base_job($origJob);     // <-- kunci pakai base, tampil juga pakai base
    $hours    = timer_to_hours($row['timer']);
    $points   = hours_to_points($hours);
    $possible = 10;
    $isTest   = ((int)$row['is_test'] === 1);
    $stage    = $row['stage'];

    // tentukan user per stage
    $userMap = [
        'Preliminary' => $row['username'],
        'Dispensing'  => $row['user_dispensing'],
        'Dyeing'      => $row['user_dyeing'],
        'Darkroom'    => $row['user_darkroom'], // COALESCE end/start
    ];
    $user = strtoupper(trim((string)($userMap[$stage] ?? '')));
    if ($user === '') continue;

    if (!isset($data[$stage][$user][$base])) {
        $data[$stage][$user][$base] = [
            'awarded'    => null,
            'possible'   => null,
            't_awarded'  => '',
            't_possible' => '',
        ];
    }
    if ($isTest) {
        $data[$stage][$user][$base]['t_awarded']  = $points;
        $data[$stage][$user][$base]['t_possible'] = $possible;
    } else {
        $data[$stage][$user][$base]['awarded']  = $points;
        $data[$stage][$user][$base]['possible'] = $possible;
    }
}
$stmt->close();

/* ===== Dedup berantai antar stage per user (Prelim → Disp → Dye → Dark) ===== */
$assignedByUser = []; // [USER] => set(BASE_JOB) yang sudah “dipegang” stage lebih awal

foreach ($stageOrder as $st) {
    foreach ($data[$st] as $user => &$rowsAssoc) {
        foreach ($rowsAssoc as $base => $_r) {
            if (!isset($assignedByUser[$user])) $assignedByUser[$user] = [];
            if (isset($assignedByUser[$user][$base])) {
                // base ini sudah ditampilkan di stage sebelumnya -> buang
                unset($rowsAssoc[$base]);
            } else {
                // tandai base ini sebagai sudah “dipegang” oleh stage saat ini
                $assignedByUser[$user][$base] = true;
            }
        }
        unset($_r);
        // kosong? biarkan saja, nanti otomatis tidak ditampilkan
    }
    unset($rowsAssoc);
}

/* ===== Kumpulkan daftar user (yang masih punya data) ===== */
$allUsers = [];
foreach ($stageOrder as $st){
    foreach ($data[$st] as $user => $_rows){
        if ($user==='') continue;
        if (!empty($_rows)) $allUsers[$user] = true;
    }
}
$allUsers = array_keys($allUsers);
sort($allUsers);

$activeUser = isset($_GET['user']) && $_GET['user']!=='' ? strtoupper($_GET['user']) : ( $allUsers[0] ?? '' );
if ($activeUser!=='' && !in_array($activeUser, $allUsers, true)) {
    $activeUser = $allUsers[0] ?? '';
}

/* ===== Renderer: 1 user + 1 stage ===== */
function render_user_stage_table($stageTitle, $rowsAssoc){
    echo '<div class="panel panel-default" style="margin:0;">';
    echo '  <div class="panel-body" style="padding:0;">';
    echo '    <table class="table table-bordered table-condensed" style="margin-bottom:0;">';
    echo '      <thead>';
    echo '        <tr class="active"><th class="text-center" colspan="5" style="font-size:13px; letter-spacing:.3px;">'.$stageTitle.'</th></tr>';
    echo '        <tr class="active">';
    echo '          <th class="text-center" style="width:40%;">JOB</th>';
    echo '          <th class="text-center" style="width:12%;">POINTS<br>AWARDED</th>';
    echo '          <th class="text-center" style="width:12%;">POSSIBLE<br>POINTS</th>';
    echo '          <th class="text-center" style="width:12%;">TEST REPORT<br>POINTS AWARDED</th>';
    echo '          <th class="text-center" style="width:12%;">TEST REPORT<br>POSSIBLE POINTS</th>';
    echo '        </tr>';
    echo '      </thead>';
    echo '      <tbody>';

    $totA=0; $totP=0; $totTA=0; $totTP=0;

    if (!empty($rowsAssoc)) {
        foreach ($rowsAssoc as $base => $r){
            $aw  = ($r['awarded']  === null ? '' : (int)$r['awarded']);
            $ps  = ($r['possible'] === null ? '' : (int)$r['possible']);
            $taw = ($r['t_awarded']  === '' ? '' : (int)$r['t_awarded']);
            $tps = ($r['t_possible'] === '' ? '' : (int)$r['t_possible']);

            $totA  += (int)($r['awarded']  ?? 0);
            $totP  += (int)($r['possible'] ?? 0);
            $totTA += (int)(($r['t_awarded']  === '' ? 0 : $r['t_awarded']));
            $totTP += (int)(($r['t_possible'] === '' ? 0 : $r['t_possible']));

            echo '<tr>';
            echo '  <td>'.htmlspecialchars($base).'</td>'; // tampil TANPA -A/-B
            echo '  <td class="text-center">'.$aw.'</td>';
            echo '  <td class="text-center">'.$ps.'</td>';
            echo '  <td class="text-center">'.$taw.'</td>';
            echo '  <td class="text-center">'.$tps.'</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5" class="text-center text-muted">No data</td></tr>';
    }

    // total
    echo '<tr class="active">';
    echo '  <td class="text-right"><strong>Total</strong></td>';
    echo '  <td class="text-center"><strong>'.$totA.'</strong></td>';
    echo '  <td class="text-center"><strong>'.$totP.'</strong></td>';
    echo '  <td class="text-center"><strong>'.($totTA ?: 0).'</strong></td>';
    echo '  <td class="text-center"><strong>'.($totTP ?: 0).'</strong></td>';
    echo '</tr>';

    // ratio per tabel (tetap dikomentari)
    /*
    $sumA = $totA + $totTA;
    $sumP = $totP + $totTP;
    $ratio = $sumP>0 ? ($sumA/$sumP) : 0;
    echo '<tr>';
    echo '  <td class="text-right"><strong>Ratio</strong></td>';
    echo '  <td></td><td></td><td></td>';
    echo '  <td class="text-center" style="background:#cfe8ff;font-weight:bold;">'.number_format($ratio,4,'.','').'</td>';
    echo '</tr>';
    */

    echo '      </tbody>';
    echo '    </table>';
    echo '  </div>';
    echo '</div>';
}

/* ===== Summary total per user (gabungan semua stage, setelah dedup berantai) ===== */
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
    return compact('sumA','sumP','ratio');
}

/* ===== Build untuk tampilan ===== */
?>
<style>
  .stage-scroller{ overflow-x:auto; -webkit-overflow-scrolling:touch; white-space:nowrap; padding-bottom:6px; }
  .stage-card{ display:inline-block; vertical-align:top; width:440px; margin-right:12px; }
  @media (max-width:768px){ .stage-card{ width:420px; } }
  .nav-tabs>li.active>a{ font-weight:700; }

  .stage-card .panel-body{ max-height:600px; overflow-y:auto; }
  .stage-card thead tr:nth-child(1) th{ position:sticky; top:0;  z-index:3; background:#51f7a3; }
  .stage-card thead tr:nth-child(2) th{ position:sticky; top:28px; z-index:2; background:#f5f5f5; }

  .user-ratio-summary{
    display:flex; align-items:center; gap:12px;
    background:#f9fbff; border:1px solid #e3eefc; border-radius:4px;
    padding:8px 12px; margin-bottom:10px;
  }
  .user-ratio-summary .ratio-badge{
    font-weight:bold; padding:6px 10px; border-radius:16px;
    background:#cfe8ff; border:1px solid #9fd0ff;
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
                   value="<?php echo htmlspecialchars($filterDate); ?>" onchange="this.form.submit()">
          </div>
        </form>
      </div>

      <div class="box-body">
        <?php
          // daftar user yang masih punya data (setelah dedup berantai)
          if (empty($allUsers)){
              echo '<div class="alert alert-info">Tidak ada data untuk tanggal <strong>'.htmlspecialchars($filterDate).'</strong>.</div>';
          } else {
        ?>
        <ul class="nav nav-tabs" role="tablist" id="userTabs">
          <?php foreach ($allUsers as $u):
                $uid = slug_id($u); $act = ($u===$activeUser)?'active':''; ?>
            <li role="presentation" class="<?php echo $act; ?>">
              <a href="#tab-<?php echo $uid; ?>" aria-controls="tab-<?php echo $uid; ?>" role="tab"
                 data-toggle="tab" data-userkey="<?php echo htmlspecialchars($u); ?>">
                <?php echo htmlspecialchars($u); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="tab-content" style="margin-top:15px;">
          <?php foreach ($allUsers as $u):
                $uid = slug_id($u); $act = ($u===$activeUser)?'active':''; ?>
            <div role="tabpanel" class="tab-pane <?php echo $act; ?>" id="tab-<?php echo $uid; ?>">
              <?php
                $rowsPre = $data['Preliminary'][$u] ?? [];
                $rowsDis = $data['Dispensing'][$u]  ?? [];
                $rowsDye = $data['Dyeing'][$u]      ?? [];
                $rowsDrk = $data['Darkroom'][$u]    ?? [];
                $totals  = compute_user_totals($data, $u);
                $hasAny  = !empty($rowsPre) || !empty($rowsDis) || !empty($rowsDye) || !empty($rowsDrk);
              ?>

              <div class="user-ratio-summary">
                <div>
                  <div><strong>Total Points Awarded + Test Report Points Awarded:</strong> <?php echo (int)$totals['sumA']; ?></div>
                  <div><strong>Total Possible Points + Test Report Possible Points:</strong> <?php echo (int)$totals['sumP']; ?></div>
                </div>
                <div class="ratio-badge">Ratio: <?php echo number_format($totals['ratio'], 4, '.', ''); ?></div>
              </div>

              <?php if ($hasAny): ?>
                <div class="stage-scroller">
                  <?php if (!empty($rowsPre)): ?>
                    <div class="stage-card"><?php render_user_stage_table('PRELIMINARY', $rowsPre); ?></div>
                  <?php endif; ?>
                  <?php if (!empty($rowsDis)): ?>
                    <div class="stage-card"><?php render_user_stage_table('DISPENSING',  $rowsDis); ?></div>
                  <?php endif; ?>
                  <?php if (!empty($rowsDye)): ?>
                    <div class="stage-card"><?php render_user_stage_table('DYEING',      $rowsDye); ?></div>
                  <?php endif; ?>
                  <?php if (!empty($rowsDrk)): ?>
                    <div class="stage-card"><?php render_user_stage_table('DARKROOM',    $rowsDrk); ?></div>
                  <?php endif; ?>
                </div>
              <?php else: ?>
                <div class="alert alert-info">Tidak ada job untuk user <strong><?php echo htmlspecialchars($u); ?></strong> pada tanggal <strong><?php echo htmlspecialchars($filterDate); ?></strong>.</div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <?php } // endif allUsers ?>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  $('#userTabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var key = $(e.target).data('userkey');
    if (key) {
      if (history.pushState) {
        var url = location.pathname + location.search.replace(/([?&])user=[^&]*/,'$1').replace(/[?&]$/,'');
        history.replaceState(null, '', url + '#' + encodeURIComponent(key));
      } else {
        location.hash = encodeURIComponent(key);
      }
    }
  });
  var h = decodeURIComponent((location.hash||'').replace('#',''));
  if (h) {
    $('#userTabs a').each(function(){
      if ($(this).data('userkey')===h){
        $(this).tab('show');
      }
    });
  }
})();
</script>
