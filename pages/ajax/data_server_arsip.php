<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
session_start();
$requestData = $_REQUEST;
$columns = array(
    0 => 'id_status',
    1 => 'grp',
    2 => 'matcher',
    3 => 'idm',
    4 => 'no_order',
    5 => 'langganan',
    6 => 'warna',
    7 => 'no_warna',
    8 => 'no_item',
    9 => 'no_po',
    10 => 'cocok_warna',
    11 => 'approve_at',
	12 => 'tgl_arsip'
);
$sql = "SELECT a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by, b.id as id_matching,
        a.grp, a.matcher, a.idm, b.no_order, b.langganan, b.no_warna, b.warna, b.no_item, b.no_po, b.cocok_warna, a.approve_at, a.status, lsm.do_at as tgl_arsip
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        JOIN log_status_matching lsm ON a.idm = lsm.ids AND lsm.info ='Resep di arsipkan'
        where a.status = 'arsip'";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " AND (a.idm LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.warna LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_warna LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_order LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR lsm.do_at LIKE '%" . $requestData['search']['value'] . "%' ";
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
    $nestedData = array();
    $nestedData[] = $no . '. <input type="checkbox" id="' . $no . '" id_status="' . $row['id_status'] . '" id_matching="' . $row['id_matching'] . '" idm="' . $row['idm'] . '" no_order="' . $row['no_order'] . '">';
    $nestedData[] = '<li class="btn-group" role="group"><a href="?p=Detail-status-wait-approve&idm=' . $row['id_status'] . '" class="btn btn-xs btn-info"><i class="fa fa-link"></i></a><button type="button" class="btn btn-xs btn-danger delete" id_status="' . $row['id_status'] . '" id_matching="' . $row['id_matching'] . '" idm="' . $row['idm'] . '" no_order="' . $row['no_order'] . '" why_batal="HAPUS_ARSIP"><i class="fa fa-trash"></i></button></li>';
    $nestedData[] = $row["grp"];
    $nestedData[] = $row["matcher"];
    $nestedData[] = $row["idm"];
    $nestedData[] = $row["no_order"];
    $nestedData[] = $row["langganan"];
    $nestedData[] = $row["warna"];
    $nestedData[] = $row["no_warna"];
    $nestedData[] = $row["no_item"];
    $nestedData[] = $row["no_po"];
    $nestedData[] = $row["cocok_warna"];
    $nestedData[] = $row["approve_at"];
	$nestedData[] = $row["tgl_arsip"];

    $data[] = $nestedData;
    $no++;
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
