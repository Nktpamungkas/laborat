<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

function I($k){ return isset($_POST[$k]) && $_POST[$k]!=='' ? (int)$_POST[$k] : 0; }
function S($k){ return isset($_POST[$k]) && $_POST[$k]!=='' ? $_POST[$k] : null; }

$cols = "
  tgl,jam,shift,kloter,jenis_kain,status,
  visual_hendrik_ld,visual_hendrik_bulk,
  visual_gunawan_ld,visual_gunawan_bulk,
  visual_ferdinan_ld,visual_ferdinan_bulk,
  visual_gugum_ld,visual_gugum_bulk,
  visual_ganang_ld,visual_ganang_bulk,
  visual_citra_ld,visual_citra_bulk,
  visual_joni_ld,visual_joni_bulk,
  color_hendrik_ld,color_hendrik_bulk,
  color_gunawan_ld,color_gunawan_bulk,
  color_ferdinan_ld,color_ferdinan_bulk,
  color_gugum_ld,color_gugum_bulk,
  color_ganang_ld,color_ganang_bulk,
  color_citra_ld,color_citra_bulk,
  color_joni_ld,color_joni_bulk,
  resep_asal,x6,t_report,t_ulang,t_gabung,warna_ctrl,resep_lain,jml
";
$placeholders = implode(',', array_fill(0, 6 + 14 + 14 + 8, '?'));

$sql = "INSERT INTO summary_preliminary ($cols) VALUES ($placeholders)";
$stmt = $con->prepare($sql);
if(!$stmt){ echo json_encode(["ok"=>false,"message"=>$con->error]); exit; }

$vals = [
  S('tgl'), S('jam'), S('shift'), I('kloter'), S('jenis_kain'), S('status'),
  I('visual_hendrik_ld'), I('visual_hendrik_bulk'),
  I('visual_gunawan_ld'), I('visual_gunawan_bulk'),
  I('visual_ferdinan_ld'), I('visual_ferdinan_bulk'),
  I('visual_gugum_ld'),   I('visual_gugum_bulk'),
  I('visual_ganang_ld'),  I('visual_ganang_bulk'),
  I('visual_citra_ld'),   I('visual_citra_bulk'),
  I('visual_joni_ld'),    I('visual_joni_bulk'),
  I('color_hendrik_ld'), I('color_hendrik_bulk'),
  I('color_gunawan_ld'), I('color_gunawan_bulk'),
  I('color_ferdinan_ld'), I('color_ferdinan_bulk'),
  I('color_gugum_ld'),   I('color_gugum_bulk'),
  I('color_ganang_ld'),  I('color_ganang_bulk'),
  I('color_citra_ld'),   I('color_citra_bulk'),
  I('color_joni_ld'),    I('color_joni_bulk'),
  I('resep_asal'), I('x6'), I('t_report'), I('t_ulang'),
  I('t_gabung'), I('warna_ctrl'), I('resep_lain'), I('jml')
];
$types = 'sss' . 'i' . 's' . 's' . str_repeat('i', 14) . str_repeat('i', 14) . str_repeat('i', 8);

$stmt->bind_param($types, ...$vals);
if(!$stmt->execute()){ echo json_encode(["ok"=>false,"message"=>$stmt->error]); exit; }

echo json_encode(["ok"=>true, "id"=>$con->insert_id, "message"=>"Tersimpan"]);
