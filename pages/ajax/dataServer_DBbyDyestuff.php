<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'id',
    1 => 'id_status',
    2 => 'kode',
    3 => 'nama',
    4 => 'no_resep'
);
$sql = "select a.id, a.id_status, a.kode, a.nama, b.no_resep
from tbl_matching_detail a
join tbl_matching b on a.id_matching = b.id
join tbl_status_matching c on a.id_status = c.id
where c.status = 'selesai' and c.approve='TRUE'
group by a.id, b.no_resep
order by a.kode, a.id desc";

$query = mysqli_query($con,$sql) or die("data_server.php: get dataku 0");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

//----------------------------------------------------------------------------------
$sql = "select a.id, a.id_status, a.kode, a.nama, b.no_resep
from tbl_matching_detail a
join tbl_matching b on a.id_matching = b.id
join tbl_status_matching c on a.id_status = c.id
where c.status = 'selesai' and c.approve='TRUE' and NOT a.kode = '---' ";

if (!empty($requestData['search']['value'])) {
    //----------------------------------------------------------------------------------
    $sql .= " AND (a.kode LIKE '" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.nama LIKE '" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_resep LIKE '" . $requestData['search']['value'] . "%') ";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku 1");

$totalFiltered = mysqli_num_rows($query);
$sql .= " group by a.id, b.no_resep ORDER BY a." . $columns[$requestData['order'][0]['column']] . " ,a.id   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " , " . $requestData['length'] . "   ";
// var_dump(print_r($sql));
// die;
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku 2");

$data = array();
$no = 1;
while ($row = mysqli_fetch_array($query)) {
    $nestedData = array();
    $nestedData[] = $no++;
    $nestedData[] = $row["id"];
    $nestedData[] = $row["id_status"];
    $nestedData[] = $row["kode"];
    $nestedData[] = $row["nama"];
    $nestedData[] = $row["no_resep"];
    $nestedData[] = '<a class="btn btn-xs btn-info" href="?p=Detail-status-approved&idm=' . $row['id_status'] . '"><i class="fa fa-link"></i></a>';
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
