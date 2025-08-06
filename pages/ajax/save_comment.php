<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    session_start();

    $ids        = $_POST['ids'] ?? '';
    $idm        = $_POST['idm'] ?? '';
    $adj_no     = $_POST['adj_no'] ?? '';
    $comment    = $_POST['comment'] ?? 0;
    $action     = $_POST['action'] ?? ''; // insert atau update
    $idUser     = $_POST['idUser'];

    $insert = "INSERT INTO tbl_comment (ids, idm, adj, comment, created_by)
                VALUES ('$ids', '$idm', '$adj_no', '$comment', '$idUser')";
    if (mysqli_query($con, $insert)) {
        echo 'SAVED';
    } else {
        echo 'ERROR: ' . mysqli_error($con); // Tampilkan error dari MySQL
    }

    mysqli_close($con);
?>
