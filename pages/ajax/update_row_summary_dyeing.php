<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

function toInt($v){ return ($v === '' || $v === null) ? 0 : (int)$v; }

$id = (int)($_POST['id'] ?? 0);
if ($id<=0){ echo json_encode(["ok"=>false,"message"=>"ID tidak valid"]); exit; }

$tgl   = $_POST['tgl']   ?? null;
$shift = $_POST['shift'] ?? null;

$ttl_kloter_poly   = toInt($_POST['ttl_kloter_poly']   ?? 0);
$ttl_kloter_cotton = toInt($_POST['ttl_kloter_cotton'] ?? 0);

$suffix_poly   = $_POST['suffix_poly']   ?? null;
$suffix_cotton = $_POST['suffix_cotton'] ?? null;

$botol = toInt($_POST['botol'] ?? 0);

$suffix_json = json_encode([
  "poly"   => $suffix_poly,
  "cotton" => $suffix_cotton,
], JSON_UNESCAPED_UNICODE);

$sql="UPDATE summary_dyeing
      SET tgl=?, shift=?,
          ttl_kloter_poly=?, ttl_kloter_cotton=?,
          suffix=?, botol=?
      WHERE id=?";
$stmt=$con->prepare($sql);
if(!$stmt){ echo json_encode(["ok"=>false,"message"=>$con->error]); exit; }

$stmt->bind_param(
  "ssiisii",
  $tgl,$shift,
  $ttl_kloter_poly,$ttl_kloter_cotton,
  $suffix_json,$botol,$id
);

if(!$stmt->execute()){ echo json_encode(["ok"=>false,"message"=>$stmt->error]); exit; }
$stmt->close();

echo json_encode(["ok"=>true,"message"=>"Update selesai"]);
