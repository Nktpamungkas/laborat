<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

function json_error($m){ echo json_encode(["ok"=>false,"message"=>$m]); exit; }

$date  = isset($_GET['date'])  ? trim($_GET['date'])  : '';
$shift = isset($_GET['shift']) ? trim($_GET['shift']) : '';

if ($date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)){
  $date = date('Y-m-d');
}
if ($shift === '' || !preg_match('/^[123]$/', $shift)){
  // fallback: tentukan dari jam sekarang
  $h = (int)date('G');
  $shift = ($h>=7 && $h<=14) ? '1' : (($h>=15 && $h<=22) ? '2' : '3');
}

if ($shift === '1'){
  $start = $date . ' 07:00:00';
  $end   = $date . ' 14:59:59';
}elseif ($shift === '2'){
  $start = $date . ' 15:00:00';
  $end   = $date . ' 22:59:59';
}else{
  $start = $date . ' 23:00:00';
  $next  = date('Y-m-d', strtotime($date.' +1 day'));
  $end   = $next . ' 06:59:59';
}

$cutoff = '2025-09-25';

if ($date <= $cutoff) {
  $time_column = 'darkroom_start';
} else {
  $time_column = 'darkroom_end';
}

$sql = "SELECT DISTINCT s.no_resep
        FROM tbl_preliminary_schedule s
        WHERE s.$time_column BETWEEN ? AND ?
          AND s.no_resep IS NOT NULL AND s.no_resep <> ''";

$stmt = $con->prepare($sql);
if (!$stmt){ json_error($con->error); }
$stmt->bind_param('ss', $start, $end);
if (!$stmt->execute()){ json_error($stmt->error); }
$res = $stmt->get_result();

$list = [];
while($row = $res->fetch_assoc()){
  $nr = trim($row['no_resep']);
  if ($nr!=='') $list[] = $nr;
}
$stmt->close();

$list = array_values(array_unique($list));
sort($list, SORT_NATURAL | SORT_FLAG_CASE);

echo json_encode([
  "ok" => true,
  "tgl" => $date,
  "shift" => $shift,
  "range_start" => $start,
  "range_end"   => $end,

  "list"   => $list,             // array unik
  "suffix" => implode(' ', $list),
  "jumlah" => count($list)
], JSON_UNESCAPED_UNICODE);
