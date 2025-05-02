<?php
include "../../koneksi.php";
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM master_mesin WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Query gagal dieksekusi"]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(["error" => "ID tidak ditemukan"]);
}
