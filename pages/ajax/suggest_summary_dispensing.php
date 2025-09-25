<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

/**
 * Input:
 *   GET date=YYYY-MM-DD
 *   GET shift=1|2|3
 *
 * Output (ringkas):
 *   ok, tgl, shift, range_start, range_end,
 *   suffix_poly, suffix_cotton, suffix_white,  // string unik dipisah spasi (kompatibel lama)
 *   botol,                                      // total semua baris (duplikat dihitung)
 *   detail: {                                   // BARU: untuk modal tabel
 *     poly:   [{suffix:"...", qty:2}, ...],
 *     cotton: [{suffix:"...", qty:3}, ...],
 *     white:  [{suffix:"...", qty:1}, ...]
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

/* rentang waktu shift */
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

/* ambil schedule + mapping dispensing */
$sql = "SELECT s.no_resep, ms.code, ms.dispensing
        FROM tbl_preliminary_schedule s
        LEFT JOIN master_suhu ms ON ms.code = s.code
        WHERE s.dispensing_start BETWEEN ? AND ?
          AND s.no_resep IS NOT NULL AND s.no_resep <> ''";

$stmt = $con->prepare($sql);
if (!$stmt){ json_error($con->error); }
$stmt->bind_param('ss', $start, $end);
if (!$stmt->execute()){ json_error($stmt->error); }
$res = $stmt->get_result();

/* hitung:
   - total_rows  : semua baris (duplikat ikut)
   - *_set       : daftar unik utk field lama suffix_*
   - *_count     : hitung per suffix utk tabel detail
*/
$total_rows = 0;
$poly_set = [];   $cotton_set = [];   $white_set = [];
$poly_count = []; $cotton_count = []; $white_count = [];

while($row = $res->fetch_assoc()){
  $nr = trim($row['no_resep']);
  if ($nr === '') continue;
  $total_rows++;

  $d = isset($row['dispensing']) ? trim((string)$row['dispensing']) : '';

  if ($d === '1'){
    $poly_set[$nr] = true;
    $poly_count[$nr] = ($poly_count[$nr] ?? 0) + 1;
  }elseif ($d === '2'){
    $cotton_set[$nr] = true;
    $cotton_count[$nr] = ($cotton_count[$nr] ?? 0) + 1;
  }elseif ($d === '3'){
    $white_set[$nr] = true;
    $white_count[$nr] = ($white_count[$nr] ?? 0) + 1;
  }
}
$stmt->close();

/* urut alfabet biar rapi */
ksort($poly_count); ksort($cotton_count); ksort($white_count);

$detail_poly = [];
foreach($poly_count as $suf=>$q){ $detail_poly[] = ["suffix"=>$suf, "qty"=>(int)$q]; }
$detail_cotton = [];
foreach($cotton_count as $suf=>$q){ $detail_cotton[] = ["suffix"=>$suf, "qty"=>(int)$q]; }
$detail_white = [];
foreach($white_count as $suf=>$q){ $detail_white[] = ["suffix"=>$suf, "qty"=>(int)$q]; }

echo json_encode([
  "ok" => true,
  "tgl" => $date,
  "shift" => $shift,
  "range_start" => $start,
  "range_end"   => $end,

  // kompatibel lama: string daftar unik (dipisah spasi)
  "suffix_poly"   => implode(' ', array_keys($poly_set)),
  "suffix_cotton" => implode(' ', array_keys($cotton_set)),
  "suffix_white"  => implode(' ', array_keys($white_set)),

  "botol" => $total_rows,

  // baru: detail untuk modal tabel
  "detail" => [
    "poly"   => $detail_poly,
    "cotton" => $detail_cotton,
    "white"  => $detail_white,
  ]
], JSON_UNESCAPED_UNICODE);
