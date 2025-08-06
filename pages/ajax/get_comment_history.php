<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    session_start();
    if (!$con_nowprd) {
        die(print_r(sqlsrv_errors(), true));
    }

    $ids = mysqli_real_escape_string($con, $_GET['ids'] ?? '');
    $idm = mysqli_real_escape_string($con, $_GET['idm'] ?? '');
    $adj = mysqli_real_escape_string($con, $_GET['adj'] ?? '');

    $sql = "SELECT comment, created_at, created_by FROM tbl_comment 
            WHERE ids = '$ids' AND idm = '$idm' AND adj = '$adj' 
            ORDER BY id DESC";
    $res = mysqli_query($con, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $created_by = (int)$row['created_by']; // cast to int for safety

        $getUserName = "SELECT username FROM [nowprd].[users]
                        WHERE id = $created_by AND menu = 'prd_bukuresep.php'";
        $res_user = sqlsrv_query($con_nowprd, $getUserName);
        $userName = sqlsrv_fetch_array($res_user, SQLSRV_FETCH_ASSOC);

        $data[] = [
            'comment'       => $row['comment'],
            'created_at'    => $row['created_at'],
            'username'      => $userName['username'] ?? 'tidak diketahui'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>
