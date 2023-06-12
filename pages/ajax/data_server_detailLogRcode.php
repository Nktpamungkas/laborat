<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'ids',
    1 => 'status',
    2 => 'info',
    3 => 'do_by',
    4 => 'do_at',
    5 => 'ip_address'
);
$sql = "SELECT ids, `status`, info, do_by, do_at, ip_address from log_status_matching where ids = '$requestData[Rcode]' ";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " AND where ids LIKE '%" . $requestData['search']['value'] . "%' ";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku1");
$totalFiltered = mysqli_num_rows($query);
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
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
    $nestedData[] = $row["status"];
    $nestedData[] = $row["info"];
    $nestedData[] = $row["do_by"];
    $nestedData[] = substr($row["do_at"], 0, 16);
    $nestedData[] = $row["ip_address"];

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
