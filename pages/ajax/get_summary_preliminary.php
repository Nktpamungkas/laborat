<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

/* --- default: 30 hari terakhir (zona Asia/Jakarta) --- */
date_default_timezone_set('Asia/Jakarta');
$today = date('Y-m-d');
$last30 = date('Y-m-d', strtotime('-30 days', strtotime($today)));

$from = isset($_GET['from']) ? trim($_GET['from']) : '';
$to   = isset($_GET['to'])   ? trim($_GET['to'])   : '';

$valid = function($d){
  return (bool)preg_match('/^\d{4}-\d{2}-\d{2}$/', $d);
};

/* tukar kalau from > to */
if ($from !== '' && $to !== '' && $valid($from) && $valid($to) && $from > $to){
  $tmp = $from; $from = $to; $to = $tmp;
}

$where = [];
$params = [];
$types  = '';

/* jika user tidak kirim from/to, pakai default 30 hari terakhir */
if (($from === '' || !$valid($from)) && ($to === '' || !$valid($to))) {
  $from = $last30;
  $to   = $today;
}

if ($from !== '' && $valid($from)){
  $where[] = "tgl >= ?";
  $types  .= 's';
  $params[] = $from;
}
if ($to !== '' && $valid($to)){
  $where[] = "tgl <= ?";
  $types  .= 's';
  $params[] = $to;
}

$sql = "SELECT * FROM summary_preliminary";
if (!empty($where)){
  $sql .= " WHERE ".implode(" AND ", $where);
}
$sql .= " ORDER BY tgl DESC, jam DESC, id DESC";

$stmt = $con->prepare($sql);
if (!$stmt){
  echo json_encode(["ok"=>false, "message"=>$con->error]); exit;
}
if ($types !== ''){
  $stmt->bind_param($types, ...$params);
}
if (!$stmt->execute()){
  echo json_encode(["ok"=>false, "message"=>$stmt->error]); exit;
}

$res = $stmt->get_result();
$data = [];
while($row = $res->fetch_assoc()){
  if (isset($row['jam']) && $row['jam'] !== null){
    $row['jam'] = substr($row['jam'], 0, 5);
  }
  $data[] = $row;
}
$stmt->close();

echo json_encode(["ok"=>true,"data"=>$data]);
