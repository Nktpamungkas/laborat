<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'id',
    1 => 'buyer',
    2 => 'lampu',
    3 => 'flag',
    4 => 'created_at',
    5 => 'create_by'
);
// set_order_type("desc");
$sql = "SELECT id, buyer, flag, lampu, created_at, create_by FROM vpot_lampbuy";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " where buyer LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR lampu LIKE '%" . $requestData['search']['value'] . "%' ";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku1");
$totalFiltered = mysqli_num_rows($query);
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
    . $requestData['start'] . " ," . $requestData['length'] . "   ";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku2");
//----------------------------------------------------------------------------------
$data = array();
$no = 1;
while ($row = mysqli_fetch_array($query)) {
    $nestedData = array();
    $nestedData[] = $no++;
    $nestedData[] = $row["buyer"] . ' <button type="button" class="btn btn-xs btn-warning edit_lampu" attr-data="' . $row["buyer"] . '"><i class="fa fa-edit"></i></button>';
    $nestedData[] = $row["flag"];
    $nestedData[] = $row["lampu"];
    $nestedData[] = $row["created_at"];
    $nestedData[] = $row["create_by"];

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
