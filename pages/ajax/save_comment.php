<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    session_start();

    mysqli_set_charset($con, "utf8mb4"); // biar aman untuk semua karakter

    $ids      = $_POST['ids'] ?? '';
    $idm      = $_POST['idm'] ?? '';
    $adj_no   = $_POST['adj_no'] ?? '';
    $comment  = $_POST['comment'] ?? '';
    $idUser   = $_POST['idUser'] ?? '';

    $stmt = $con->prepare("INSERT INTO tbl_comment (ids, idm, adj, comment, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $ids, $idm, $adj_no, $comment, $idUser);

    if ($stmt->execute()) {
        echo 'SAVED';
    } else {
        echo 'ERROR: ' . $stmt->error;
    }

    $stmt->close();
    mysqli_close($con);
?>
