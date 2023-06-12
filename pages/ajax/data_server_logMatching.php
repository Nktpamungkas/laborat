<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'maxid',
    1 => 'ids'
);
$sql = "SELECT MAX(id) as maxid, ids from log_status_matching";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " where ids LIKE '%" . $requestData['search']['value'] . "%' ";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku1");
$totalFiltered = mysqli_num_rows($query);
$sql .= " GROUP by ids ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
    . $requestData['start'] . " ," . $requestData['length'] . "   ";
// var_dump(print_r($sql));
// die;
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku2");
//----------------------------------------------------------------------------------
$data = array();
$no = 1;
while ($row = mysqli_fetch_array($query)) {
    $nestedData = array();
    $nestedData[] = $no++;
    $nestedData[] = $row["ids"];
    $nestedData[] = '<span class="btn btn-xs btn-danger"><i class="fa fa-history"></i></span>';

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
