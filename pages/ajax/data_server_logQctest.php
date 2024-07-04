<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';

$requestData = $_REQUEST;

$columns = array(
    0 => 'maxid',
    1 => 'no_counter'
);

$sql = "SELECT MAX(id) as maxid, no_counter FROM log_qc_test";

$query = mysqli_query($con, $sql) or die("data_server.php: failed to fetch data");
$totalData = mysqli_num_rows($query);

$totalFiltered = $totalData;

if (!empty($requestData['search']['value'])) {
    $sql .= " WHERE no_counter LIKE '%" . $requestData['search']['value'] . "%' ";
}

$sql .= " GROUP BY no_counter ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT "
    . $requestData['start'] . "," . $requestData['length'];

$query = mysqli_query($con, $sql) or die("data_server.php: failed to fetch filtered data");
$totalFiltered = mysqli_num_rows($query);

$data = array();
$no = 1;

while ($row = mysqli_fetch_array($query)) {
    $nestedData = array();
    $nestedData[] = $no++;
    $nestedData[] = $row["no_counter"];

    $data[] = $nestedData;
}

$json_data = array(
    "draw"            => intval($requestData['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data"            => $data
);

echo json_encode($json_data);
