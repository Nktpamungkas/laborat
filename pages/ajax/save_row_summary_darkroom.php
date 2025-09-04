<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

function toInt($v){ return ($v === '' || $v === null) ? 0 : (int)$v; }
function norm_suffix($s){
  // Pecah spasi/koma/semicolon → unique → gabung spasi-tunggal
  $arr = preg_split('/[,\s;]+/', (string)$s, -1, PREG_SPLIT_NO_EMPTY);
  $arr = array_values(array_unique(array_map('trim', $arr)));
  return implode(' ', $arr);
}

$tgl    = $_POST['tgl']   ?? '';
$shift  = $_POST['shift'] ?? null;
$jumlah = toInt($_POST['jumlah'] ?? 0);
$suffix_str = norm_suffix($_POST['suffix'] ?? '');
$ket    = $_POST['ket'] ?? null;

if ($tgl === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)){
  echo json_encode(["ok"=>false,"message"=>"TGL tidak valid"]); exit;
}

// Jika jumlah kosong, hitung dari suffix
if ($jumlah <= 0 && $suffix_str !== ''){
  $jumlah = count(preg_split('/\s+/', $suffix_str));
}

$suffix_json = json_encode(["all" => $suffix_str], JSON_UNESCAPED_UNICODE);

$sql = "INSERT INTO summary_darkroom (tgl,shift,jumlah,suffix,ket)
        VALUES (?,?,?,?,?)";
$stmt = $con->prepare($sql);
if(!$stmt){ echo json_encode(["ok"=>false,"message"=>$con->error]); exit; }

$stmt->bind_param('ssiss', $tgl, $shift, $jumlah, $suffix_json, $ket);

if(!$stmt->execute()){ echo json_encode(["ok"=>false,"message"=>$stmt->error]); exit; }
$id = $stmt->insert_id;
$stmt->close();

echo json_encode(["ok"=>true,"id"=>$id,"message"=>"Tersimpan"], JSON_UNESCAPED_UNICODE);
