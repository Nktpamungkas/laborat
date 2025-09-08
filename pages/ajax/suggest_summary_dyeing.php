<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

/**
 * Input:
 *   GET date=YYYY-MM-DD
 *   GET shift=1|2|3
 *
 * Output:
 *   ok, tgl, shift, range_start, range_end,
 *   suffix_poly, suffix_cotton,     // daftar unik (dipisah spasi)
 *   botol,                          // total baris (duplikat dihitung)
 *   detail: {
 *     poly:   [{suffix:"...", qty: N}, ...],
 *     cotton: [{suffix:"...", qty: N}, ...]
 *   }
 */

function json_error($msg){ echo json_encode(["ok"=>false,"message"=>$msg]); exit; }

$date  = isset($_GET['date'])  ? trim($_GET['date'])  : '';
$shift = isset($_GET['shift']) ? trim($_GET['shift']) : '';

if ($date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)){
  $date = date('Y-m-d');
}
if ($shift === '' || !preg_match('/^[123]$/', $shift)){
  $h = (int)date('G');
  $shift = ($h>=7 && $h<=14) ? '1' : (($h>=15 && $h<=22) ? '2' : '3');
}

/* rentang waktu per shift */
if ($shift === '1'){
  $start = $date . ' 07:00:00';
  $end   = $date . ' 14:59:59';
}elseif ($shift === '2'){
  $start = $date . ' 15:00:00';
  $end   = $date . ' 22:59:59';
}else{ // shift 3 lintas hari
  $start = $date . ' 23:00:00';
  $next  = date('Y-m-d', strtotime($date.' +1 day'));
  $end   = $next . ' 06:59:59';
}

/* ambil schedule + mapping dyeing (1=poly, 2=cotton) */
$sql = "SELECT s.no_resep, ms.code, ms.dyeing AS dy
        FROM tbl_preliminary_schedule s
        LEFT JOIN master_suhu ms ON ms.code = s.code
        WHERE s.creationdatetime BETWEEN ? AND ?
          AND s.no_resep IS NOT NULL AND s.no_resep <> ''";

$stmt = $con->prepare($sql);
if (!$stmt){ json_error($con->error); }
$stmt->bind_param('ss', $start, $end);
if (!$stmt->execute()){ json_error($stmt->error); }
$res = $stmt->get_result();

/* hitungan:
   - total_rows: semua baris (duplikat dihitung)
   - *_set     : daftar unik utk output ringkas (string)
   - *_count   : hitung per suffix untuk modal tabel
*/
$total_rows = 0;
$poly_set = [];   $cotton_set = [];
$poly_count = []; $cotton_count = [];

while($row = $res->fetch_assoc()){
  $nr = trim($row['no_resep']);
  if ($nr === '') continue;
  $total_rows++;

  $dy = isset($row['dy']) ? trim((string)$row['dy']) : '';

  if ($dy === '1'){ // poly
    $poly_set[$nr] = true;
    $poly_count[$nr] = ($poly_count[$nr] ?? 0) + 1;
  }elseif ($dy === '2'){ // cotton
    $cotton_set[$nr] = true;
    $cotton_count[$nr] = ($cotton_count[$nr] ?? 0) + 1;
  }
}
$stmt->close();

/* urut alfabet biar rapi */
ksort($poly_count); ksort($cotton_count);

$detail_poly = [];
foreach($poly_count as $suf=>$q){ $detail_poly[] = ["suffix"=>$suf, "qty"=>(int)$q]; }
$detail_cotton = [];
foreach($cotton_count as $suf=>$q){ $detail_cotton[] = ["suffix"=>$suf, "qty"=>(int)$q]; }

echo json_encode([
  "ok" => true,
  "tgl" => $date,
  "shift" => $shift,
  "range_start" => $start,
  "range_end"   => $end,

  "suffix_poly"   => implode(' ', array_keys($poly_set)),
  "suffix_cotton" => implode(' ', array_keys($cotton_set)),

  "botol" => $total_rows,

  "detail" => [
    "poly"   => $detail_poly,
    "cotton" => $detail_cotton,
  ]
], JSON_UNESCAPED_UNICODE);
