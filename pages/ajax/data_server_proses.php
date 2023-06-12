<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'id',
    1 => 'nama_proses',
    2 => 'is_active',
    3 => 'created_at',
    4 => 'created_by'
);
// set_order_type("desc");
$sql = "SELECT * FROM master_proses";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " where nama_proses LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR created_at LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR created_by LIKE '%" . $requestData['search']['value'] . "%' ";
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
    if ($row['is_active'] == "TRUE") {
        $btn = '<button class="btn btn-success btn-xs btn-rounded _action" attr-data=' . $row["id"] . '>' . $row["is_active"] . '</button>';
    } else {
        $btn = '<button class="btn btn-danger btn-xs btn-rounded _action" attr-data=' . $row["id"] . '>' . $row["is_active"] . '</button>';
    }

    $nestedData = array();
    $nestedData[] = $no++;
    $nestedData[] = $row["nama_proses"];
    $nestedData[] = $btn;
    $nestedData[] = $row["created_at"];
    $nestedData[] = $row["created_by"];

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
