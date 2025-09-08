<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

if (!isset($_POST['id']) || $_POST['id']===''){ echo json_encode(["ok"=>false,"message"=>"ID kosong"]); exit; }
$id = (int)$_POST['id'];

$stmt = $con->prepare("DELETE FROM summary_preliminary WHERE id=?");
if(!$stmt){ echo json_encode(["ok"=>false,"message"=>$con->error]); exit; }
$stmt->bind_param('i', $id);
if(!$stmt->execute()){ echo json_encode(["ok"=>false,"message"=>$stmt->error]); exit; }

echo json_encode(["ok"=>true,"message"=>"Terhapus (ID $id)"]);
