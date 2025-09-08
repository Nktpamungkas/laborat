<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

$from = isset($_GET['from']) ? trim($_GET['from']) : '';
$to   = isset($_GET['to'])   ? trim($_GET['to'])   : '';

$valid = function($d){ return (bool)preg_match('/^\d{4}-\d{2}-\d{2}$/', $d); };

if ($from !== '' && $to !== '' && $valid($from) && $valid($to) && $from > $to){
  $tmp=$from; $from=$to; $to=$tmp;
}
if ($from === '' || !$valid($from) || $to === '' || !$valid($to)){
  $to   = date('Y-m-d');
  $from = date('Y-m-d', strtotime($to.' -30 days'));
}

$sql = "SELECT id,tgl,shift,jumlah,suffix,ket
        FROM summary_darkroom
        WHERE tgl BETWEEN ? AND ?
        ORDER BY tgl ASC, id ASC";

$stmt = $con->prepare($sql);
if (!$stmt){ echo json_encode(["ok"=>false,"message"=>$con->error]); exit; }
$stmt->bind_param('ss', $from, $to);
if (!$stmt->execute()){ echo json_encode(["ok"=>false,"message"=>$stmt->error]); exit; }

function suffix_to_string($v){
  // Terima array/string → rapikan jadi string spasi-tunggal
  if (is_array($v)) $v = implode(' ', $v);
  return trim(preg_replace('/\s+/', ' ', (string)$v));
}

$res = $stmt->get_result();
$data = [];
while($row = $res->fetch_assoc()){
  $row['jumlah'] = (int)$row['jumlah'];

  // Decode JSON → ambil key "all" (fallback: "list" / array / string)
  $sfx = '';
  if (!empty($row['suffix'])){
    $j = json_decode($row['suffix'], true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($j)){
      if (array_key_exists('all', $j))       $sfx = suffix_to_string($j['all']);
      elseif (array_key_exists('list', $j))  $sfx = suffix_to_string($j['list']);
      else                                   $sfx = suffix_to_string($j); 
    }else{
      // sudah string JSON tapi bukan object/array → kirim apa adanya
      $sfx = suffix_to_string($row['suffix']);
    }
  }
  $row['suffix'] = $sfx;

  $data[] = $row;
}
$stmt->close();

echo json_encode(["ok"=>true, "data"=>$data], JSON_UNESCAPED_UNICODE);
