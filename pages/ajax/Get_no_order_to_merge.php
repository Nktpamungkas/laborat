<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

$search = $_GET['search'];
$sql = sqlsrv_query($conn,"SELECT * FROM TM.dbo.JobOrders WHERE DocumentNo LIKE '%$search%' ORDER BY ID asc", array(), array("Scrollable"=>"static"));
$result = sqlsrv_num_rows($sql);

if ($result > 0) {
    $list = array();
    $key = 0;
    while ($row = sqlsrv_fetch_array($sql)) {
        $list[$key]['id'] = $row['DocumentNo'];
        $list[$key]['text'] = $row['DocumentNo'];
        $key++;
    }
    echo json_encode($list);
} else {
    echo "Keyword tidak cocok!";
}
