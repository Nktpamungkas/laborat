<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "koneksi.php";

/* =========================
   Helpers
========================= */
function timer_to_hours($s)
{
  $s = strtolower((string)$s);
  $hari = 0;
  $jam = 0;
  $menit = 0;
  if (preg_match('/(\d+)\s*hari/', $s, $m))  $hari = (int)$m[1];
  if (preg_match('/(\d+)\s*jam/', $s, $m))   $jam = (int)$m[1];
  if (preg_match('/(\d+)\s*menit/', $s, $m)) $menit = (int)$m[1];
  return ($hari * 24) + $jam + ($menit / 60);
}
function hours_to_points($h)
{
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
function slug_id($s)
{
  $s = strtoupper(trim((string)$s));
  if ($s === '') return 'NA';
  $s = preg_replace('/[^A-Z0-9]+/', '-', $s);
  return trim($s, '-');
}
/* Normalisasi no_resep: hilangkan -A/-B jika awalan DR */
function base_job($job)
{
  $job = (string)$job;
  if (stripos($job, 'DR') === 0 && (substr($job, -2) === '-A' || substr($job, -2) === '-B')) {
    return substr($job, 0, -2);
  }
  return $job;
}

/* =========================
   Tanggal Range (default: H-1; Senin => H-2)
========================= */
$today = new DateTime('today');
$dow   = (int)date('N'); // 1=Mon

if ($dow === 1) {
  $defaultFrom = (clone $today)->modify('-2 day')->format('Y-m-d');
  $defaultTo   = (clone $today)->modify('-2 day')->format('Y-m-d');
} else {
  $defaultFrom = (clone $today)->modify('-1 day')->format('Y-m-d');
  $defaultTo   = (clone $today)->modify('-1 day')->format('Y-m-d');
}

$dateFrom = (isset($_GET['date_from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date_from']))
  ? $_GET['date_from'] : $defaultFrom;

$dateTo = (isset($_GET['date_to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date_to']))
  ? $_GET['date_to'] : $defaultTo;

if ($dateFrom > $dateTo) {
  $tmp = $dateFrom;
  $dateFrom = $dateTo;
  $dateTo = $tmp;
}

/* =========================
   Query per stage (UNION) + kolom tgl
========================= */
$sql = "
SELECT
  ps.no_resep                                              AS no_resep,
  DATE(ps.creationdatetime)                                AS tgl,
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
WHERE DATE(ps.creationdatetime) BETWEEN ? AND ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  DATE(ps.dispensing_start)                                AS tgl,
  'Dispensing'                                             AS stage,
  sm.timer,
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
WHERE DATE(ps.dispensing_start) BETWEEN ? AND ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  DATE(ps.dyeing_start)                                    AS tgl,
  'Dyeing'                                                 AS stage,
  sm.timer,
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
WHERE DATE(ps.dyeing_start) BETWEEN ? AND ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  CASE
    WHEN ps.darkroom_end IS NOT NULL AND DATE(ps.darkroom_end) BETWEEN ? AND ? THEN DATE(ps.darkroom_end)
    WHEN ps.darkroom_start IS NOT NULL AND DATE(ps.darkroom_start) BETWEEN ? AND ? THEN DATE(ps.darkroom_start)
    ELSE NULL
  END                                                     AS tgl,
  'Darkroom'                                              AS stage,
  sm.timer,
  ps.username,
  ps.user_dispensing,
  ps.user_dyeing,
  COALESCE(ps.user_darkroom_end, ps.user_darkroom_start)  AS user_darkroom,
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
        (ps.darkroom_end   IS NOT NULL AND DATE(ps.darkroom_end)   BETWEEN ? AND ?)
     OR (ps.darkroom_start IS NOT NULL AND DATE(ps.darkroom_start) BETWEEN ? AND ?)
      )
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

ORDER BY tgl, no_resep, stage
";

$stmt = $con->prepare($sql);
if (!$stmt) die("Query prepare gagal: " . $con->error);

$stmt->bind_param(
  str_repeat('s', 14),
  $dateFrom,
  $dateTo,  // Preliminary
  $dateFrom,
  $dateTo,  // Dispensing
  $dateFrom,
  $dateTo,  // Dyeing
  $dateFrom,
  $dateTo,  // Darkroom CASE end
  $dateFrom,
  $dateTo,  // Darkroom CASE start
  $dateFrom,
  $dateTo,  // Darkroom WHERE end
  $dateFrom,
  $dateTo   // Darkroom WHERE start
);

$stmt->execute();
$res = $stmt->get_result();

/* =========================
   Struktur data:
   $data[stage][USER][TGL][BASE_JOB]
========================= */
$stageOrder = ['Preliminary', 'Dispensing', 'Dyeing', 'Darkroom'];
$data = [
  'Preliminary' => [],
  'Dispensing'  => [],
  'Dyeing'      => [],
  'Darkroom'    => [],
];

while ($row = $res->fetch_assoc()) {
  if (empty($row['tgl'])) continue;

  $tgl      = $row['tgl'];
  $origJob  = $row['no_resep'];
  $base     = base_job($origJob);

  $hours    = timer_to_hours($row['timer']);
  $points   = hours_to_points($hours);
  $possible = 10;

  $isTest   = ((int)$row['is_test'] === 1);
  $stage    = $row['stage'];

  $userMap = [
    'Preliminary' => $row['username'],
    'Dispensing'  => $row['user_dispensing'],
    'Dyeing'      => $row['user_dyeing'],
    'Darkroom'    => $row['user_darkroom'],
  ];
  $user = strtoupper(trim((string)($userMap[$stage] ?? '')));
  if ($user === '') continue;

  if (!isset($data[$stage][$user][$tgl][$base])) {
    $data[$stage][$user][$tgl][$base] = [
      'awarded'    => null,
      'possible'   => null,
      't_awarded'  => '',
      't_possible' => '',
    ];
  }

  if ($isTest) {
    $data[$stage][$user][$tgl][$base]['t_awarded']  = $points;
    $data[$stage][$user][$tgl][$base]['t_possible'] = $possible;
  } else {
    $data[$stage][$user][$tgl][$base]['awarded']  = $points;
    $data[$stage][$user][$tgl][$base]['possible'] = $possible;
  }
}
$stmt->close();

/* =========================
   Dedup berantai per user PER HARI
========================= */
$assignedByUser = []; // [USER][TGL] => set(BASE_JOB)

foreach ($stageOrder as $st) {
  foreach ($data[$st] as $user => &$byDate) {
    foreach ($byDate as $tgl => &$rowsAssoc) {

      if (!isset($assignedByUser[$user])) $assignedByUser[$user] = [];
      if (!isset($assignedByUser[$user][$tgl])) $assignedByUser[$user][$tgl] = [];

      foreach ($rowsAssoc as $base => $_r) {
        if (isset($assignedByUser[$user][$tgl][$base])) {
          unset($rowsAssoc[$base]);
        } else {
          $assignedByUser[$user][$tgl][$base] = true;
        }
      }
      unset($_r);
    }
    unset($rowsAssoc);
  }
  unset($byDate);
}

/* =========================
   Kumpulkan user yang masih punya data
========================= */
$allUsers = [];
foreach ($stageOrder as $st) {
  foreach ($data[$st] as $user => $byDate) {
    if ($user === '') continue;
    $has = false;
    foreach ($byDate as $tgl => $rows) {
      if (!empty($rows)) {
        $has = true;
        break;
      }
    }
    if ($has) $allUsers[$user] = true;
  }
}
$allUsers = array_keys($allUsers);
sort($allUsers);

$activeUser = isset($_GET['user']) && $_GET['user'] !== '' ? strtoupper($_GET['user']) : ($allUsers[0] ?? '');
if ($activeUser !== '' && !in_array($activeUser, $allUsers, true)) {
  $activeUser = $allUsers[0] ?? '';
}

/* =========================
   Renderers
========================= */
function render_user_stage_table($stageTitle, $rowsAssoc)
{
  echo '<div class="panel panel-default" style="margin:0;">';
  echo '  <div class="panel-body" style="padding:0;">';
  echo '    <table class="table table-bordered table-condensed" style="margin-bottom:0;">';
  echo '      <thead>';
  echo '        <tr class="active"><th class="text-center" colspan="5" style="font-size:13px; letter-spacing:.3px;">' . $stageTitle . '</th></tr>';
  echo '        <tr class="active">';
  echo '          <th class="text-center" style="width:40%;">JOB</th>';
  echo '          <th class="text-center" style="width:12%;">POINTS<br>AWARDED</th>';
  echo '          <th class="text-center" style="width:12%;">POSSIBLE<br>POINTS</th>';
  echo '          <th class="text-center" style="width:12%;">TEST REPORT<br>POINTS AWARDED</th>';
  echo '          <th class="text-center" style="width:12%;">TEST REPORT<br>POSSIBLE POINTS</th>';
  echo '        </tr>';
  echo '      </thead>';
  echo '      <tbody>';

  $totA = 0;
  $totP = 0;
  $totTA = 0;
  $totTP = 0;

  if (!empty($rowsAssoc)) {
    foreach ($rowsAssoc as $base => $r) {
      $aw  = ($r['awarded']  === null ? '' : (int)$r['awarded']);
      $ps  = ($r['possible'] === null ? '' : (int)$r['possible']);
      $taw = ($r['t_awarded']  === '' ? '' : (int)$r['t_awarded']);
      $tps = ($r['t_possible'] === '' ? '' : (int)$r['t_possible']);

      $totA  += (int)($r['awarded']  ?? 0);
      $totP  += (int)($r['possible'] ?? 0);
      $totTA += (int)(($r['t_awarded']  === '' ? 0 : $r['t_awarded']));
      $totTP += (int)(($r['t_possible'] === '' ? 0 : $r['t_possible']));

      echo '<tr>';
      echo '  <td>' . htmlspecialchars($base) . '</td>';
      echo '  <td class="text-center">' . $aw . '</td>';
      echo '  <td class="text-center">' . $ps . '</td>';
      echo '  <td class="text-center">' . $taw . '</td>';
      echo '  <td class="text-center">' . $tps . '</td>';
      echo '</tr>';
    }
  } else {
    echo '<tr><td colspan="5" class="text-center text-muted">No data</td></tr>';
  }

  echo '<tr class="active">';
  echo '  <td class="text-right"><strong>Total</strong></td>';
  echo '  <td class="text-center"><strong>' . $totA . '</strong></td>';
  echo '  <td class="text-center"><strong>' . $totP . '</strong></td>';
  echo '  <td class="text-center"><strong>' . ($totTA ?: 0) . '</strong></td>';
  echo '  <td class="text-center"><strong>' . ($totTP ?: 0) . '</strong></td>';
  echo '</tr>';

  echo '      </tbody>';
  echo '    </table>';
  echo '  </div>';
  echo '</div>';
}

function compute_user_day_totals(array $data, string $user, string $tgl)
{
  $stages = ['Preliminary', 'Dispensing', 'Dyeing', 'Darkroom'];
  $totA = $totP = $totTA = $totTP = 0;

  foreach ($stages as $st) {
    $rows = $data[$st][$user][$tgl] ?? [];
    foreach ($rows as $r) {
      $totA  += (int)($r['awarded']  ?? 0);
      $totP  += (int)($r['possible'] ?? 0);
      $totTA += (int)(($r['t_awarded']  === '' ? 0 : $r['t_awarded']));
      $totTP += (int)(($r['t_possible'] === '' ? 0 : $r['t_possible']));
    }
  }

  $sumA  = $totA + $totTA;
  $sumP  = $totP + $totTP;
  $ratio = ($sumP > 0) ? ($sumA / $sumP) : 0;

  return compact('sumA', 'sumP', 'ratio', 'totA', 'totP', 'totTA', 'totTP');
}

function get_user_dates(array $data, string $user, array $stageOrder)
{
  $tglSet = [];
  foreach ($stageOrder as $st) {
    $byDate = $data[$st][$user] ?? [];
    foreach ($byDate as $tgl => $rows) {
      if (!empty($rows)) $tglSet[$tgl] = true;
    }
  }
  $tglList = array_keys($tglSet);
  sort($tglList);
  return $tglList;
}

/* render table ringkas: Tanggal | Ratio | Detail(modal) */
function render_user_daily_summary_modal(array $data, string $user, array $stageOrder)
{
  $tglList = get_user_dates($data, $user, $stageOrder);
  $uSlug   = slug_id($user);

  $tableId   = 'dt-summary-' . $uSlug;
  $modalsHtml = '';

  echo '<table id="' . $tableId . '" data-user="' . htmlspecialchars($user, ENT_QUOTES) . '" class="table table-bordered table-condensed dt-summary" style="width:100%;">';
  echo '  <thead>';
  echo '    <tr class="active">';
  echo '      <th class="text-center" style="width:18%;">Tanggal</th>';
  echo '      <th class="text-center" style="width:15%;">Ratio</th>';
  echo '      <th class="text-center">Detail</th>';
  echo '    </tr>';
  echo '  </thead>';
  echo '  <tbody>';

  if (empty($tglList)) {
    echo '<tr><td colspan="3" class="text-center text-muted">No data</td></tr>';
    echo '  </tbody></table>';
    return;
  }

  foreach ($tglList as $tgl) {
    $dSlug   = str_replace('-', '', $tgl);
    $modalId = 'modal-' . $uSlug . '-' . $dSlug;

    $day      = compute_user_day_totals($data, $user, $tgl);
    $ratioTxt = number_format($day['ratio'], 4, '.', '');

    echo '<tr>';
    echo '  <td class="text-center"><strong>' . htmlspecialchars($tgl) . '</strong></td>';
    echo '  <td class="text-center"><span class="ratio-pill">' . $ratioTxt . '</span></td>';
    echo '  <td class="text-center">';
    echo '    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#' . $modalId . '">Detail</button>';
    echo '  </td>';
    echo '</tr>';

    // ===== modal content (DI LUAR TABLE) =====
    ob_start();

    $rowsPre = $data['Preliminary'][$user][$tgl] ?? [];
    $rowsDis = $data['Dispensing'][$user][$tgl]  ?? [];
    $rowsDye = $data['Dyeing'][$user][$tgl]      ?? [];
    $rowsDrk = $data['Darkroom'][$user][$tgl]    ?? [];

    $hasAny  = !empty($rowsPre) || !empty($rowsDis) || !empty($rowsDye) || !empty($rowsDrk);

    echo '<div class="modal fade" id="' . $modalId . '" tabindex="-1" role="dialog" aria-hidden="true">';
    echo '  <div class="modal-dialog modal-lg" role="document">';
    echo '    <div class="modal-content">';
    echo '      <div class="modal-header">';
    echo '        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    echo '        <h4 class="modal-title">Detail Points - ' . htmlspecialchars($user) . ' | ' . htmlspecialchars($tgl) . '</h4>';
    echo '      </div>';
    echo '      <div class="modal-body">';

    echo '        <div class="day-detail-head">';
    echo '          <div class="day-detail-badge">Ratio: ' . $ratioTxt . '</div>';
    // echo '          <div class="text-muted" style="font-size:12px;">Total A/P: '.$day["sumA"].'/'.$day["sumP"].'</div>';
    echo '        </div>';

    if ($hasAny) {
      echo '        <div class="stage-scroller">';
      if (!empty($rowsPre)) {
        echo '<div class="stage-card">';
        render_user_stage_table('PRELIMINARY', $rowsPre);
        echo '</div>';
      }
      if (!empty($rowsDis)) {
        echo '<div class="stage-card">';
        render_user_stage_table('DISPENSING',  $rowsDis);
        echo '</div>';
      }
      if (!empty($rowsDye)) {
        echo '<div class="stage-card">';
        render_user_stage_table('DYEING',      $rowsDye);
        echo '</div>';
      }
      if (!empty($rowsDrk)) {
        echo '<div class="stage-card">';
        render_user_stage_table('DARKROOM',    $rowsDrk);
        echo '</div>';
      }
      echo '        </div>';
    } else {
      echo '        <div class="alert alert-info" style="margin:0;">Tidak ada data pada tanggal <strong>' . htmlspecialchars($tgl) . '</strong>.</div>';
    }

    echo '      </div>';
    echo '      <div class="modal-footer">';
    echo '        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';

    $modalsHtml .= ob_get_clean();
  }

  echo '  </tbody>';
  echo '</table>';

  // render semua modal setelah table (HTML valid)
  echo $modalsHtml;
}
?>
<style>
  .nav-tabs>li.active>a {
    font-weight: 700;
  }

  .ratio-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 14px;
    background: #cfe8ff;
    border: 1px solid #9fd0ff;
    font-weight: 700;
  }

  .day-detail-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    flex-wrap: wrap;
  }

  .day-detail-badge {
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 14px;
    background: #f9fbff;
    border: 1px solid #e3eefc;
    white-space: nowrap;
  }

  /* tampilan awal per stage */
  .stage-scroller {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    white-space: nowrap;
    padding-bottom: 6px;
  }

  .stage-card {
    display: inline-block;
    vertical-align: top;
    width: 440px;
    margin-right: 12px;
  }

  @media (max-width:768px) {
    .stage-card {
      width: 420px;
    }
  }

  .stage-card .panel-body {
    max-height: 560px;
    overflow-y: auto;
  }

  .stage-card thead tr:nth-child(1) th {
    position: sticky;
    top: 0;
    z-index: 3;
    background: #51f7a3;
  }

  .stage-card thead tr:nth-child(2) th {
    position: sticky;
    top: 28px;
    z-index: 2;
    background: #f5f5f5;
  }

  /* Bootstrap 3 modal-lg fix if needed (optional)
     Default BS3 modal-lg width is 900px on >= 992px */
</style>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <form class="form-inline" method="get" action="">
          <input type="hidden" name="p" value="Points-Awarded">

          <div class="form-group">
            <label for="date_from">Tanggal</label>
            <input type="date" id="date_from" name="date_from" class="form-control input-sm"
              value="<?php echo htmlspecialchars($dateFrom); ?>">
            <span style="margin:0 6px;">s/d</span>
            <input type="date" id="date_to" name="date_to" class="form-control input-sm"
              value="<?php echo htmlspecialchars($dateTo); ?>">
            <button type="submit" class="btn btn-primary btn-sm" style="margin-left:8px;">Tampilkan</button>
          </div>
        </form>
      </div>

      <div class="box-body">
        <?php if (empty($allUsers)): ?>
          <div class="alert alert-info">
            Tidak ada data untuk range <strong><?php echo htmlspecialchars($dateFrom); ?></strong>
            s/d <strong><?php echo htmlspecialchars($dateTo); ?></strong>.
          </div>
        <?php else: ?>

          <ul class="nav nav-tabs" role="tablist" id="userTabs">
            <?php foreach ($allUsers as $u):
              $uid = slug_id($u);
              $act = ($u === $activeUser) ? 'active' : '';
            ?>
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
              $uid = slug_id($u);
              $act = ($u === $activeUser) ? 'active' : '';
              $tglList = get_user_dates($data, $u, $stageOrder);
            ?>
              <div role="tabpanel" class="tab-pane <?php echo $act; ?>" id="tab-<?php echo $uid; ?>">
                <?php if (!empty($tglList)): ?>
                  <div class="alert alert-info" style="margin-bottom:10px;">
                    Menampilkan <strong>ratio per hari</strong> untuk user <strong><?php echo htmlspecialchars($u); ?></strong>
                    pada range <strong><?php echo htmlspecialchars($dateFrom); ?></strong> s/d <strong><?php echo htmlspecialchars($dateTo); ?></strong>.
                  </div>

                  <?php render_user_daily_summary_modal($data, $u, $stageOrder); ?>

                <?php else: ?>
                  <div class="alert alert-info">
                    Tidak ada job untuk user <strong><?php echo htmlspecialchars($u); ?></strong> pada range
                    <strong><?php echo htmlspecialchars($dateFrom); ?></strong> s/d <strong><?php echo htmlspecialchars($dateTo); ?></strong>.
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    // simpan tab user aktif di hash (refresh tetap tab yang sama)
    $('#userTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
      var key = $(e.target).data('userkey');
      if (key) {
        if (history.replaceState) {
          var url = location.pathname + location.search;
          history.replaceState(null, '', url + '#' + encodeURIComponent(key));
        } else {
          location.hash = encodeURIComponent(key);
        }
      }
    });

    // restore tab dari hash
    var h = decodeURIComponent((location.hash || '').replace('#', ''));
    if (h) {
      $('#userTabs a').each(function() {
        if ($(this).data('userkey') === h) {
          $(this).tab('show');
        }
      });
    }

    // optional: saat modal dibuka, scroll modal body ke atas
    $(document).on('shown.bs.modal', '.modal', function() {
      $(this).find('.modal-body').scrollTop(0);
    });
  })();
</script>
<script>
  (function() {
    function initSummaryTables() {
      $('.dt-summary').each(function() {
        var $t = $(this);
        if ($.fn.DataTable.isDataTable(this)) return;

        $t.DataTable({
          dom: 'Bfrtip', // posisi tombol (B) + filter (f) + table (t) + info (i) + paging (p)
          pageLength: 25,
          lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
          ],
          order: [
            [0, 'asc']
          ], // Tanggal
          autoWidth: false,
          responsive: false,
          columnDefs: [{
              targets: 2,
              orderable: false,
              searchable: false,
              className: 'text-center'
            },
            {
              targets: [0, 1],
              className: 'text-center'
            }
          ],
          buttons: [{
              extend: 'copy',
              text: 'Copy',
              title: 'Point Awarded - ' + ($t.data('user') || ''),
              exportOptions: {
                columns: [0, 1]
              }
            },
            {
              extend: 'excel',
              text: 'Excel',
              title: 'Point Awarded - ' + ($t.data('user') || ''),
              exportOptions: {
                columns: [0, 1]
              }
            },
            {
              extend: 'csv',
              text: 'CSV',
              title: 'Point Awarded - ' + ($t.data('user') || ''),
              exportOptions: {
                columns: [0, 1]
              }
            },
            {
              extend: 'pdf',
              text: 'PDF',
              title: 'Point Awarded - ' + ($t.data('user') || ''),
              exportOptions: {
                columns: [0, 1]
              }
            }
          ]
        });
      });
    }

    // init pertama kali
    $(document).ready(function() {
      initSummaryTables();
    });

    $('#userTabs a[data-toggle="tab"]').on('shown.bs.tab', function() {
      initSummaryTables();
      $.fn.dataTable.tables({
        visible: true,
        api: true
      }).columns.adjust();
    });

  })();
</script>