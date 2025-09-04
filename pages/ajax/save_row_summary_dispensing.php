<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

function toInt($v){ return ($v === '' || $v === null) ? 0 : (int)$v; }

$tgl   = $_POST['tgl']   ?? null;
$shift = $_POST['shift'] ?? null;

$ttl_kloter_poly   = toInt($_POST['ttl_kloter_poly']   ?? 0);
$ttl_kloter_cotton = toInt($_POST['ttl_kloter_cotton'] ?? 0);
$ttl_kloter_white  = toInt($_POST['ttl_kloter_white']  ?? 0);

$suffix_poly   = $_POST['suffix_poly']   ?? null;
$suffix_cotton = $_POST['suffix_cotton'] ?? null;
$suffix_white  = $_POST['suffix_white']  ?? null;

$botol = toInt($_POST['botol'] ?? 0);

$suffix_json = json_encode([
  "poly"   => $suffix_poly,
  "cotton" => $suffix_cotton,
  "white"  => $suffix_white,
], JSON_UNESCAPED_UNICODE);

$sql = "INSERT INTO summary_dispensing
        (tgl, shift, ttl_kloter_poly, ttl_kloter_cotton, ttl_kloter_white, suffix, botol)
        VALUES (?,?,?,?,?,?,?)";

$stmt = $con->prepare($sql);
if(!$stmt){ echo json_encode(["ok"=>false,"message"=>$con->error]); exit; }

$stmt->bind_param(
  "ssiiisi",
  $tgl, $shift,
  $ttl_kloter_poly, $ttl_kloter_cotton, $ttl_kloter_white,
  $suffix_json, $botol
);

if(!$stmt->execute()){ echo json_encode(["ok"=>false,"message"=>$stmt->error]); exit; }
$id = $stmt->insert_id;
$stmt->close();

echo json_encode(["ok"=>true,"id"=>$id,"message"=>"Tersimpan"], JSON_UNESCAPED_UNICODE);
