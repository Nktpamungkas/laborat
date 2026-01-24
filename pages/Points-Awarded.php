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
  $hari = 0; $jam = 0; $menit = 0;
  if (preg_match('/(\d+)\s*hari/', $s, $m))  $hari  = (int)$m[1];
  if (preg_match('/(\d+)\s*jam/', $s, $m))   $jam   = (int)$m[1];
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

function clean_date($v){
  $v = trim((string)$v);
  return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : '';
}
function clean_time($v){
  $v = trim((string)$v);
  return preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $v) ? $v : '';
}

/* =========================
   Date + Time Range (input terpisah)
   default: H-1 00:00 s/d H-1 23:59 (Senin => H-2)
   Data JANGAN ditampilkan sebelum klik "Tampilkan" (run=1)
========================= */
$shouldRun = (isset($_GET['run']) && $_GET['run'] === '1');

$today = new DateTime('today');
$dow   = (int)date('N');
$baseDay = ($dow === 1) ? (clone $today)->modify('-2 day') : (clone $today)->modify('-1 day');

$defDateFrom = $baseDay->format('Y-m-d');
$defDateTo   = $baseDay->format('Y-m-d');
$defTimeFrom = '00:00';
$defTimeTo   = '23:59';

$dateFrom = isset($_GET['date_from']) ? clean_date($_GET['date_from']) : '';
$dateTo   = isset($_GET['date_to'])   ? clean_date($_GET['date_to'])   : '';
$timeFrom = isset($_GET['time_from']) ? clean_time($_GET['time_from']) : '';
$timeTo   = isset($_GET['time_to'])   ? clean_time($_GET['time_to'])   : '';

$dateFrom = ($dateFrom !== '') ? $dateFrom : $defDateFrom;
$dateTo   = ($dateTo   !== '') ? $dateTo   : $defDateTo;
$timeFrom = ($timeFrom !== '') ? $timeFrom : $defTimeFrom;
$timeTo   = ($timeTo   !== '') ? $timeTo   : $defTimeTo;

// gabung jadi datetime untuk query
$dtStart = $dateFrom . ' ' . $timeFrom;
$dtEnd   = $dateTo   . ' ' . $timeTo;

// swap kalau kebalik (swap FULL datetime, lalu turunkan lagi ke input)
if ($dtStart > $dtEnd) {
  $tmp = $dtStart; $dtStart = $dtEnd; $dtEnd = $tmp;

  $dateFrom = substr($dtStart, 0, 10);
  $timeFrom = substr($dtStart, 11, 5);
  $dateTo   = substr($dtEnd,   0, 10);
  $timeTo   = substr($dtEnd,   11, 5);
}

/* =========================
   Data containers
========================= */
$stageOrder = ['Preliminary', 'Dispensing', 'Dyeing', 'Darkroom'];
$data = [
  'Preliminary' => [],
  'Dispensing'  => [],
  'Dyeing'      => [],
  'Darkroom'    => [],
];

$allUsers = [];
$activeUser = '';

/* =========================
   SQL (Datetime filter per stage)
========================= */
$sql = "
SELECT
  ps.no_resep                                            AS no_resep,
  DATE(ps.creationdatetime)                              AS tgl,
  'Preliminary'                                          AS stage,
  sm.timer                                               AS timer,
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
WHERE ps.creationdatetime BETWEEN ? AND ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  DATE(ps.dispensing_start)                              AS tgl,
  'Dispensing'                                           AS stage,
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
WHERE ps.dispensing_start BETWEEN ? AND ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  DATE(ps.dyeing_start)                                  AS tgl,
  'Dyeing'                                               AS stage,
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
WHERE ps.dyeing_start BETWEEN ? AND ?
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

UNION ALL
SELECT
  ps.no_resep,
  DATE(
    CASE
      WHEN ps.darkroom_end   IS NOT NULL AND ps.darkroom_end   BETWEEN ? AND ? THEN ps.darkroom_end
      WHEN ps.darkroom_start IS NOT NULL AND ps.darkroom_start BETWEEN ? AND ? THEN ps.darkroom_start
      ELSE NULL
    END
  )                                                      AS tgl,
  'Darkroom'                                             AS stage,
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
  CASE
    WHEN ps.darkroom_end   IS NOT NULL AND ps.darkroom_end   BETWEEN ? AND ? THEN ps.darkroom_end
    WHEN ps.darkroom_start IS NOT NULL AND ps.darkroom_start BETWEEN ? AND ? THEN ps.darkroom_start
    ELSE NULL
  END
) IS NOT NULL
  AND sm.timer IS NOT NULL
  AND sm.timer <> ''

ORDER BY tgl, no_resep, stage
";

/* =========================
   Run query only when "Tampilkan" clicked
========================= */
if ($shouldRun) {
  $stmt = $con->prepare($sql);
  if (!$stmt) die("Query prepare gagal: " . $con->error);

  // total 14 params
  $stmt->bind_param(
    str_repeat('s', 14),
    $dtStart, $dtEnd, // Preliminary
    $dtStart, $dtEnd, // Dispensing
    $dtStart, $dtEnd, // Dyeing
    $dtStart, $dtEnd, $dtStart, $dtEnd, // Darkroom (SELECT CASE)
    $dtStart, $dtEnd, $dtStart, $dtEnd  // Darkroom (WHERE CASE)
  );

  $stmt->execute();
  $res = $stmt->get_result();

  while ($row = $res->fetch_assoc()) {
    if (empty($row['tgl'])) continue;

    $tgl     = $row['tgl'];
    $base    = base_job($row['no_resep']);
    $hours   = timer_to_hours($row['timer']);
    $points  = hours_to_points($hours);
    $possible = 10;

    $isTest = ((int)$row['is_test'] === 1);
    $stage  = $row['stage'];

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
     (job base yang sama untuk user + tanggal yg sama hanya dihitung sekali
      dan "dipakai" oleh stage paling awal)
  ========================= */
  $assignedByUser = []; // [USER][TGL][BASE_JOB] => true

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
  $tmpUsers = [];
  foreach ($stageOrder as $st) {
    foreach ($data[$st] as $user => $byDate) {
      if ($user === '') continue;
      foreach ($byDate as $tgl => $rows) {
        if (!empty($rows)) { $tmpUsers[$user] = true; break; }
      }
    }
  }
  $allUsers = array_keys($tmpUsers);
  sort($allUsers);

  $activeUser = isset($_GET['user']) && $_GET['user'] !== '' ? strtoupper($_GET['user']) : ($allUsers[0] ?? '');
  if ($activeUser !== '' && !in_array($activeUser, $allUsers, true)) {
    $activeUser = $allUsers[0] ?? '';
  }
}

/* =========================
   Render helpers
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

  $totA = 0; $totP = 0; $totTA = 0; $totTP = 0;

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
  $totA = 0; $totP = 0; $totTA = 0; $totTP = 0;

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

  return compact('sumA','sumP','ratio','totA','totP','totTA','totTP');
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
    echo '        </div>';

    if ($hasAny) {
      echo '        <div class="stage-scroller">';
      if (!empty($rowsPre)) { echo '<div class="stage-card">'; render_user_stage_table('PRELIMINARY', $rowsPre); echo '</div>'; }
      if (!empty($rowsDis)) { echo '<div class="stage-card">'; render_user_stage_table('DISPENSING',  $rowsDis); echo '</div>'; }
      if (!empty($rowsDye)) { echo '<div class="stage-card">'; render_user_stage_table('DYEING',      $rowsDye); echo '</div>'; }
      if (!empty($rowsDrk)) { echo '<div class="stage-card">'; render_user_stage_table('DARKROOM',    $rowsDrk); echo '</div>'; }
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

  echo $modalsHtml;
}
?>
<style>
  .nav-tabs>li.active>a { font-weight:700; }

  .ratio-pill{
    display:inline-block; padding:3px 10px; border-radius:14px;
    background:#cfe8ff; border:1px solid #9fd0ff; font-weight:700;
  }
  .day-detail-head{
    display:flex; align-items:center; gap:10px; margin-bottom:10px; flex-wrap:wrap;
  }
  .day-detail-badge{
    font-weight:700; padding:4px 10px; border-radius:14px;
    background:#f9fbff; border:1px solid #e3eefc; white-space:nowrap;
  }

  .stage-scroller{ overflow-x:auto; -webkit-overflow-scrolling:touch; white-space:nowrap; padding-bottom:6px; }
  .stage-card{ display:inline-block; vertical-align:top; width:440px; margin-right:12px; }
  @media (max-width:768px){ .stage-card{ width:420px; } }
  .stage-card .panel-body{ max-height:560px; overflow-y:auto; }
  .stage-card thead tr:nth-child(1) th{ position:sticky; top:0; z-index:3; background:#51f7a3; }
  .stage-card thead tr:nth-child(2) th{ position:sticky; top:28px; z-index:2; background:#f5f5f5; }
</style>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <form class="form-inline" method="get" action="">
          <input type="hidden" name="p" value="Points-Awarded">

          <div class="form-group">
            <input type="text" name="date_from" id="date_from" class="form-control input-sm date-picker" value="<?php echo htmlspecialchars($dateFrom); ?>" autocomplete="off">
            <input type="text" name="time_from" class="form-control input-sm time-picker" value="<?php echo htmlspecialchars($timeFrom); ?>" placeholder="00:00" maxlength="5" style="margin-left:6px;">
          </div>

          <span style="margin:0 10px; display:inline-block;">
            <i class="fa fa-share" aria-hidden="true"></i>
          </span>

          <div class="form-group">
            <input type="text" name="date_to" id="date_to" class="form-control input-sm date-picker" value="<?php echo htmlspecialchars($dateTo); ?>">
            <input type="text" name="time_to" class="form-control input-sm" value="<?php echo htmlspecialchars($timeTo); ?>" placeholder="00:00" maxlength="5" style="margin-left:6px;">
          </div>

          <button type="submit" name="run" value="1" class="btn btn-primary btn-sm" style="margin-left:8px;">
            Tampilkan
          </button>

          <button type="button"
                  class="btn btn-success btn-sm"
                  id="btnExportAllExcel"
                  style="margin-left:8px;"
                  <?php echo (!$shouldRun || empty($allUsers)) ? 'disabled' : ''; ?>>
            Export Excel (All Users)
          </button>
        </form>
      </div>

      <div class="box-body">

        <?php if (!$shouldRun): ?>
          <div class="alert alert-info" style="margin-bottom:0;">
            Silakan pilih range tanggal & jam, lalu klik <strong>Tampilkan</strong>.
          </div>

        <?php else: ?>

          <?php if (empty($allUsers)): ?>
            <div class="alert alert-info">
              Tidak ada data untuk range <strong><?php echo htmlspecialchars($dtStart); ?></strong>
              s/d <strong><?php echo htmlspecialchars($dtEnd); ?></strong>.
            </div>
          <?php else: ?>

            <ul class="nav nav-tabs" role="tablist" id="userTabs">
              <?php foreach ($allUsers as $u):
                $uid = slug_id($u);
                $act = ($u === $activeUser) ? 'active' : '';
              ?>
                <li role="presentation" class="<?php echo $act; ?>">
                  <a href="#tab-<?php echo $uid; ?>"
                     aria-controls="tab-<?php echo $uid; ?>"
                     role="tab"
                     data-toggle="tab"
                     data-userkey="<?php echo htmlspecialchars($u); ?>">
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
                      pada range <strong><?php echo htmlspecialchars($dtStart); ?></strong> s/d <strong><?php echo htmlspecialchars($dtEnd); ?></strong>.
                    </div>

                    <?php render_user_daily_summary_modal($data, $u, $stageOrder); ?>

                  <?php else: ?>
                    <div class="alert alert-info">
                      Tidak ada job untuk user <strong><?php echo htmlspecialchars($u); ?></strong> pada range
                      <strong><?php echo htmlspecialchars($dtStart); ?></strong> s/d <strong><?php echo htmlspecialchars($dtEnd); ?></strong>.
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>

          <?php endif; ?>

        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<!-- SheetJS untuk Export Excel multi-tab -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
(function(){
  // Tab state via hash
  $(document).on('shown.bs.tab', '#userTabs a[data-toggle="tab"]', function(e){
    var key = $(e.target).data('userkey');
    if (!key) return;
    if (history.replaceState) {
      var url = location.pathname + location.search;
      history.replaceState(null, '', url + '#' + encodeURIComponent(key));
    } else {
      location.hash = encodeURIComponent(key);
    }
  });

  // Restore tab from hash
  $(function(){
    var h = decodeURIComponent((location.hash || '').replace('#', ''));
    if (!h) return;
    $('#userTabs a').each(function(){
      if ($(this).data('userkey') === h) $(this).tab('show');
    });
  });

  // Modal scroll top
  $(document).on('shown.bs.modal', '.modal', function(){
    $(this).find('.modal-body').scrollTop(0);
  });
})();
</script>

<?php if ($shouldRun && !empty($allUsers)): ?>
<script>
(function(){
  function initSummaryTables(){
    $('.dt-summary').each(function(){
      if ($.fn.DataTable.isDataTable(this)) return;

      var $t = $(this);
      $t.DataTable({
        dom: 'Bfrtip',
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,"All"]],
        order: [[0,'asc']],
        autoWidth: false,
        responsive: false,
        columnDefs: [
          { targets: 2, orderable:false, searchable:false, className:'text-center' },
          { targets: [0,1], className:'text-center' }
        ],
        buttons: [
          { extend:'copy',  text:'Copy',  title:'Point Awarded - ' + ($t.data('user') || ''), exportOptions:{ columns:[0,1] } },
          { extend:'excel', text:'Excel', title:'Point Awarded - ' + ($t.data('user') || ''), exportOptions:{ columns:[0,1] } },
          { extend:'csv',   text:'CSV',   title:'Point Awarded - ' + ($t.data('user') || ''), exportOptions:{ columns:[0,1] } },
          { extend:'pdf',   text:'PDF',   title:'Point Awarded - ' + ($t.data('user') || ''), exportOptions:{ columns:[0,1] } }
        ]
      });
    });
  }

  $(function(){
    initSummaryTables();
  });

  $(document).on('shown.bs.tab', '#userTabs a[data-toggle="tab"]', function(){
    initSummaryTables();
    $.fn.dataTable.tables({ visible:true, api:true }).columns.adjust();
  });
})();
</script>
<?php endif; ?>

<script>
(function(){
  function sanitizeSheetName(name){
    name = (name || '').toString().trim();
    name = name.replace(/[:\\\/\?\*\[\]]/g, ' ');
    if (name.length > 31) name = name.substring(0,31);
    if (name === '') name = 'Sheet';
    return name;
  }

  function ymdToDmy(ymd){
    var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec((ymd||'').trim());
    if (!m) return ymd;
    return m[3] + '/' + m[2] + '/' + m[1];
  }

  $('#btnExportAllExcel').on('click', function(){
    if ($(this).is(':disabled')) return;
    if (typeof XLSX === 'undefined') { alert('Library XLSX (SheetJS) belum di-load.'); return; }
    if ($('.dt-summary').length === 0) return;

    var wb = XLSX.utils.book_new();

    $('.dt-summary').each(function(){
      var tbl = this;
      var $t  = $(tbl);
      var user = $t.data('user') || 'USER';
      var sheetName = sanitizeSheetName(user);

      var dt = $.fn.DataTable.isDataTable(tbl) ? $t.DataTable() : null;

      var rows = [];
      if (dt) {
        dt.rows({ search:'applied', order:'applied' }).every(function(){
          var node = this.node();
          var tgl   = $(node).find('td:eq(0)').text().trim();
          var ratio = $(node).find('td:eq(1)').text().trim();
          if (tgl) rows.push([ ymdToDmy(tgl), ratio ]);
        });
      } else {
        $t.find('tbody tr').each(function(){
          var tgl   = $(this).find('td:eq(0)').text().trim();
          var ratio = $(this).find('td:eq(1)').text().trim();
          if (tgl) rows.push([ ymdToDmy(tgl), ratio ]);
        });
      }

      var aoa = [];
      aoa.push([ 'Point Awarded - ' + user, '' ]);
      aoa.push([ 'Tanggal', 'Ratio' ]);
      for (var i=0; i<rows.length; i++) aoa.push(rows[i]);

      var ws = XLSX.utils.aoa_to_sheet(aoa);

      ws['!merges'] = ws['!merges'] || [];
      ws['!merges'].push({ s:{r:0,c:0}, e:{r:0,c:1} });
      ws['!cols'] = [{ wch:16 }, { wch:10 }];

      XLSX.utils.book_append_sheet(wb, ws, sheetName);
    });

    XLSX.writeFile(wb, 'Point Awarded - All Users.xlsx');
  });
})();
</script>

<script>
$(function () {
  if (!$.fn.datepicker) return;

  try { $('.date-picker').datepicker('destroy'); } catch(e) {}

  $('.date-picker').datepicker({
    dateFormat: 'yy-mm-dd'
  });
});
</script>