<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'id',
    1 => 'code',
	2 => 'code_new',
    3 => 'Product_Name',
    4 => 'liquid_powder',
    5 => 'Product_Unit',
    6 => 'is_active'
);
// set_order_type("desc");
$sql = "SELECT id, code, code_new, Product_Name, liquid_powder, Product_Unit, is_active FROM tbl_dyestuff";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " where code LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR Product_Name LIKE '%" . $requestData['search']['value'] . "%' ";
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
    if ($row["Product_Unit"] == 1) {
        $uom = "%";
    } elseif ($row["Product_Unit"] == 0) {
        $uom = "Gr/L";
    }else{
        $uom = "-";
    }
    if ($row["is_active"] == 'FALSE') {
        $status = '<button type="button" class="btn btn-xs btn-danger">' . $row['is_active'] . '</button>';
    } else {
        $status = '<button type="button" class="btn btn-xs btn-success">' . $row['is_active'] . '</button>';
    }
    $nestedData = array();
    $nestedData[] = $no++;
    $nestedData[] = $row["code"];
	$nestedData[] = $row["code_new"];
    $nestedData[] = $row["Product_Name"];
    $nestedData[] = $row["liquid_powder"];
    $nestedData[] = $uom;
    $nestedData[] = $status;
    $nestedData[] = '<button class="btn btn-sm btn-warning dyess_edit" id="' . $row["id"] . '"><i class="fa fa-edit"></i></button>';

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
