<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'id',
    1 => 'no_resep',
    2 => 'no_order',
    3 => 'warna',
    4 => 'no_warna',
    5 => 'no_item',
    6 => 'langganan',
    7 => 'no_po',
    8 => 'status',
);
// set_order_type("desc");
$sql = "SELECT a.`id`, a.`no_resep`, a.`no_order`, a.`warna`, a.`no_warna`, a.`no_item`, a.`langganan`, a.`no_po`, ifnull(b.`status`, 'siap bagi') as `status` 
FROM tbl_matching a left join tbl_status_matching b on a.`no_resep` = b.`idm`
where a.status_bagi = 'siap bagi' and ifnull(b.`status`, 'siap bagi') = 'siap bagi' ";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
if (!empty($requestData['search']['value'])) {
    $sql .= " and a.`no_resep` LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.`no_order` LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.`warna` LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.`no_warna` LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.`no_item` LIKE '%" . $requestData['search']['value'] . "%' ";
    // $sql .= " OR a.`langganan` LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.`no_po` LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR ifnull(b.`status`, 'belum bagi') LIKE '%" . $requestData['search']['value'] . "%' ";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku1");
$totalFiltered = mysqli_num_rows($query);
$sql .= " GROUP by a.`no_resep` ORDER BY a.`id` desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku2");
//----------------------------------------------------------------------------------
$data = array();
$no = 1;
while ($row = mysqli_fetch_array($query)) {
    $status =  '<a href="#" class="btn btn-xs btn-success"><strong>' . $row['status'] . '</strong></a href="#">';
    $nestedData = array();

    $nestedData[] = '<div class="btn-group-vertical" role="group" aria-label="..."><a href="javascript:void(0)" class="_hapus btn btn-xs btn-danger"><i class="fa fa-trash"></i></a><a href="index1.php?p=edit_matching&rcode=' . $row['no_resep'] . '" class="_edit btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a><a style="color: black;" target="_blank" href="pages/cetak/matching.php?idkk=' . $row['no_resep'] . '" class="btn btn-xs btn-warning"><i class="fa fa-print"></i></a></div>';
    $nestedData[] = '<a href="javascript:void(0)" class="pilih">' . $row["no_resep"] . '</a>';
    $nestedData[] = $row["no_order"];
    $nestedData[] = $row["no_po"];
    $nestedData[] = $row["warna"];
    $nestedData[] = $row["no_warna"];
    $nestedData[] = $row["langganan"];
    $nestedData[] = $row["no_item"];
    $nestedData[] = $status;

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
