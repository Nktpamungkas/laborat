<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
$time = date('Y-m-d H:i:s');

$requestData = $_REQUEST;
$columns = array(
    0 => 'id',
    1 => 'flag',
    2 => 'order',
    3 => 'lot',
    4 => 'jenis_benang',
    5 => 'created_at',
);
// set_order_type("desc");
$sql = "SELECT id, flag, `order`, lot, jenis_benang, created_at 
        from tbl_orderchild where id_matching = '$_POST[id_matching]'";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= "and order LIKE '" . $requestData['search']['value'] . "%' ";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku1");
$totalFiltered = mysqli_num_rows($query);
$sql .= " ORDER BY flag" . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
    . $requestData['start'] . " ," . $requestData['length'] . "   ";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku2");
//----------------------------------------------------------------------------------
$data = array();
$no = 1;
while ($row = mysqli_fetch_array($query)) {
    $nestedData = array();
    $nestedData[] = $row["id"];
    $nestedData[] = $row["flag"] . '. <a href="javascript:void(0)" class="btn btn-danger btn-xs _hapusOrder" data-pk="' . $row["id"] . '"><i class="fa fa-trash"></i></a>';
    $nestedData[] = $row["order"];
    $nestedData[] = $row["lot"];
    $nestedData[] = $row["jenis_benang"];
    $nestedData[] = substr($row["created_at"], 0, 10);


    $data[] = $nestedData;
}
//----------------------------------------------------------------------------------
$json_data = array(
    "draw"            => intval($requestData['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data"            => $data
);
//----------------------------------------------------------------------------------
echo json_encode($json_data);
