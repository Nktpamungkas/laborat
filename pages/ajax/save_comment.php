<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    session_start();

    $ids        = $_POST['ids'] ?? '';
    $idm        = $_POST['idm'] ?? '';
    $adj_no     = $_POST['adj_no'] ?? '';
    $comment    = $_POST['comment'] ?? 0;
    $action     = $_POST['action'] ?? ''; // insert atau update

    $cek = mysqli_query($con, "SELECT 1 FROM tbl_comment WHERE ids = '$ids' AND idm = '$idm' AND adj = '$adj_no'");
    if (mysqli_num_rows($cek) > 0) {
        // Jika sudah ada, lakukan update
        $update = "UPDATE tbl_comment SET comment = '$comment' WHERE ids = '$ids' AND idm = '$idm' AND adj = '$adj_no'";
        if (mysqli_query($con, $update)) {
            echo 'EDITED';
        } else {
            echo 'ERROR: ' . mysqli_error($con); // Tampilkan error dari MySQL
        }
    }else{
        $insert = "INSERT INTO tbl_comment (ids, idm, adj, comment)
                    VALUES ('$ids', '$idm', '$adj_no', '$comment')";
        if (mysqli_query($con, $insert)) {
            echo 'SAVED';
        } else {
            echo 'ERROR: ' . mysqli_error($con); // Tampilkan error dari MySQL
        }
    }

    mysqli_close($con);
?>
