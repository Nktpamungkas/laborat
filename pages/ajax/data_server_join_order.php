<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
session_start();
$requestData = $_REQUEST;
$columns = array(
    0 => 'id_status',
    1 => 'jenis_matching',
    2 => 'matcher',
    3 => 'idm',
    4 => 'no_order',
    5 => 'langganan',
    6 => 'warna',
    7 => 'no_warna',
    8 => 'no_item',
    9 => 'no_po',
    10 => 'cocok_warna',
    11 => 'approve_at'
);
$sql = "SELECT a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by,
         b.jenis_matching, a.matcher, a.idm, b.no_order, b.langganan, b.no_warna, b.warna, b.no_item, b.no_po, b.cocok_warna, a.approve_at, a.status
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.approve = 'TRUE' and a.status = 'selesai' ";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " AND (a.idm LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.warna LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_warna LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_order LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.langganan LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_po LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.cocok_warna LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.approve_at LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_item LIKE '%" . $requestData['search']['value'] . "%')";
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
    if ($_SESSION['jabatanLAB'] == 'Lab Head' or $_SESSION['jabatanLAB'] == 'Spv' or $_SESSION['jabatanLAB'] == 'Leader') {
        $akses = $no++ . ' <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="' . $row['idm'] . '" data-pk="' . $row['id_status'] . '" title="Arsipkan"><i class="fa fa-archive"></i></a>';
    } else if ($_SESSION['jabatanLAB'] == 'Colorist' or $_SESSION['jabatanLAB'] == 'Other') {
        $akses = $no++ . ' <li class="btn-group" role="group">
        <a href="index1.php?p=Adjust_Resep_Lab&idm=' . $row['id_status'] . '" class="btn btn-warning btn-xs" title="Perbarui resep">
        <i class="fa fa-pencil"></i></a>
        <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="' . $row['idm'] . '" data-pk="' . $row['id_status'] . '" title="Arsipkan"><i class="fa fa-archive"></i></a>
        </li>';
    } else if ($_SESSION['jabatanLAB'] == 'Bon order') {
        $akses = $no++ . ' <li class="btn-group" role="group">
        <a href="javascript:void(0)" data-attribute="' . $row['id_status'] . '" class="btn btn-info btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
        <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="' . $row['idm'] . '" data-pk="' . $row['id_status'] . '" title="Arsipkan"><i class="fa fa-archive"></i></a>
        </li>';
    } else {
        $akses = $no++ . ' <li class="btn-group" role="group">
        <a href="javascript:void(0)" data-attribute="' . $row['id_status'] . '" class="btn btn-info btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
        <a href="index1.php?p=Adjust_Resep_Lab&idm=' . $row['id_status'] . '" class="btn btn-warning btn-xs" title="Perbarui resep">
        <i class="fa fa-pencil"></i></a>
        <a href="javascript:void(0)" class="btn btn-xs btn-success _arsip" data-rcode="' . $row['idm'] . '" data-pk="' . $row['id_status'] . '" title="Arsipkan"><i class="fa fa-archive"></i></a>
        </li>';
    }
    $nestedData = array();
    $nestedData[] = $akses;
    $nestedData[] = $row["jenis_matching"];
    $nestedData[] = $row["matcher"];
    $nestedData[] = $row["idm"];
    $nestedData[] = $row["no_order"];
    $nestedData[] = $row["langganan"];
    $nestedData[] = $row["warna"];
    $nestedData[] = $row["no_warna"];
    $nestedData[] = $row["no_item"];
    $nestedData[] = $row["no_po"];
    $nestedData[] = $row["cocok_warna"];
    $nestedData[] = substr($row["approve_at"], 0, 10);

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
