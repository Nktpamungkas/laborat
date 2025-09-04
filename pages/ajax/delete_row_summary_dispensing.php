<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

$id = (int)($_POST['id'] ?? 0);
if ($id<=0){ echo json_encode(["ok"=>false,"message"=>"ID tidak valid"]); exit; }

$stmt = $con->prepare("DELETE FROM summary_dispensing WHERE id=?");
if(!$stmt){ echo json_encode(["ok"=>false,"message"=>$con->error]); exit; }
$stmt->bind_param("i", $id);
if(!$stmt->execute()){ echo json_encode(["ok"=>false,"message"=>$stmt->error]); exit; }
$stmt->close();

echo json_encode(["ok"=>true,"message"=>"Dihapus"]);
