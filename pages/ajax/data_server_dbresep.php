<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$requestData = $_REQUEST;
$columns = array(
    0 => 'idm',
    1 => 'flag',
    2 => 'no_order',
    3 => 'grp',
    4 => 'jenis_kain',
    5 => 'order',
    6 => 'lot',
    7 => 'no_item',
    8 => 'no_po',
    9 => 'no_warna',
    10 => 'warna',
    11 => 'langganan',
    12 => 'created_at',
    13 => 'created_by',
    14 => 'action'
);

//----------------------------------------------------------------------------------
// SELECT a.id, a.idm,b.no_order, c.flag, a.grp, a.jenis_kain, c.`order`, c.lot,
// b.no_item, b.no_po, b.no_warna, b.warna, a.approve_at
// FROM tbl_status_matching a
// join tbl_matching b on b.no_resep = a.idm
// join tbl_orderchild c on c.id_status = a.id and c.id_matching = b.id
// group by c.id, c.`order`
// order by a.idm, c.flag;

$sql = "SELECT a.id, a.idm,b.no_order, c.flag, a.grp, b.jenis_kain, c.`order`, c.lot, b.no_item, b.no_po, b.no_warna, b.warna, c.created_at,c.created_by, b.langganan";
$sql .= " FROM tbl_status_matching a";
$sql .= " join tbl_matching b on b.no_resep = a.idm";
$sql .= " join tbl_orderchild c on c.id_status = a.id and c.id_matching = b.id";
$sql .= " group by c.id, c.`order`";

$query = mysqli_query($con,$sql) or die("data_server.php: get dataku 0");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

//----------------------------------------------------------------------------------
$sql = "SELECT a.id, a.idm, b.no_order, c.flag, a.grp, b.jenis_kain, c.`order`, c.lot, b.no_item, b.no_po, b.no_warna, b.warna, c.created_at, c.created_by, b.langganan";
$sql .= " FROM tbl_status_matching a";
$sql .= " join tbl_matching b on b.no_resep = a.idm";
$sql .= " join tbl_orderchild c on c.id_status = a.id and c.id_matching = b.id";
$sql .= " where a.approve = 'TRUE' AND a.status = 'selesai' ";

if (!empty($requestData['search']['value'])) {
    //----------------------------------------------------------------------------------
    $sql .= " AND (b.no_warna LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_order LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.no_item LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR b.warna LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR a.idm LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR c.order LIKE '%" . $requestData['search']['value'] . "%')";
}
//----------------------------------------------------------------------------------
$query = mysqli_query($con,$sql) or die("data_server.php: get dataku 1");
$totalFiltered = mysqli_num_rows($query);
$sql .= "GROUP BY c.id, c.`order` ORDER BY a." . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . ", c.flag asc  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

$query = mysqli_query($con,$sql) or die("data_server.php: get dataku 2");

$data = array();
$no = 1;
while ($row = mysqli_fetch_array($query)) {
    $nestedData = array();
    $nestedData[] =
        '<b>▕ Rcode > ' . $row["idm"] . ' &nbsp;&nbsp;▕&nbsp;&nbsp;J.kain > ' . $row['jenis_kain'] . '
        <br />
        ▕  No.Warna > ' . $row["no_warna"] . ' &nbsp;&nbsp;▕&nbsp;&nbsp; Warna > ' . $row['warna'] . '&nbsp;&nbsp;▕&nbsp;&nbsp;P.Order >' . $row['no_order'] . '&nbsp;&nbsp;▕&nbsp;&nbsp;  No.item > ' . $row['no_item'] . '</b>▕ 
        <li class="btn-group" role="group" aria-label="...">
        <a href="index1.php?p=Detail-status-approved&idm=' . $row['id'] . '" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-search"></i></a>
        <a href="pages/cetak/cetak_resep.php?ids=' . $row['id'] . '&idm=' . $row['idm'] . '" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-fw fa-print"></i></a>
      </li>';
    $nestedData[] = $row["no_order"];
    $nestedData[] = $row["flag"];
    $nestedData[] = $row["grp"];
    $nestedData[] = $row["jenis_kain"];
    $nestedData[] = $row["order"];
    $nestedData[] = $row["lot"];
    $nestedData[] = $row["no_item"];
    $nestedData[] = $row["no_po"];
    $nestedData[] = $row["no_warna"];
    $nestedData[] = $row["warna"];
    $nestedData[] = $row["langganan"];
    $nestedData[] = substr($row["created_at"], 0, 10);
    $nestedData[] = $row["created_by"];
    //     $nestedData[] = '<li class="btn-group" role="group" aria-label="...">
    //     <a href="index1.php?p=Detail-status-approved&idm=' . $row['id'] . '" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-search"></i></a>
    //     <a href="pages/cetak/cetak_resep.php?ids=' . $row['id'] . '&idm=' . $row['idm'] . '" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-fw fa-print"></i></a>
    //   </li>';
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
