<?php
include '../../koneksi.php';

if (isset($_POST['no_counter'])) {
    $no_counter = $_POST['no_counter'];

    $sql = "SELECT * FROM log_qc_test WHERE no_counter = '$no_counter'";
    $query = mysqli_query($con, $sql) or die(mysqli_error($con));

    $html = '';
    $no = 1;
    while ($row = mysqli_fetch_array($query)) {
        $html .= '<tr>';
        $html .= '<td>' . $no++ . '</td>';
        $html .= '<td>' . $row['status'] . '</td>';
        $html .= '<td>' . $row['info'] . '</td>';
        $html .= '<td>' . $row['do_by'] . '</td>';
        $html .= '<td>' . $row['do_at'] . '</td>';
        $html .= '<td>' . $row['ip_address'] . '</td>';
        $html .= '</tr>';
    }

    echo $html;
}
