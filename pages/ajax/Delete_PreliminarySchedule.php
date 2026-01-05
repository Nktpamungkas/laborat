<?php
include "../../koneksi.php";
header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $query = "DELETE FROM tbl_preliminary_schedule WHERE id = $id";
    if (mysqli_query($con, $query)) {
        // $query2 = "DELETE FROM tbl_preliminary_schedule_element WHERE tbl_preliminary_schedule_id = $id";
        // mysqli_query($con, $query2);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'invalid_request']);
}
