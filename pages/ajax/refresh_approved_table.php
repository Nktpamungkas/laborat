<?php
include "../../koneksi.php";

$sqlApproved = "SELECT * FROM approval_bon_order ORDER BY id DESC";
$resultApproved = mysqli_query($con, $sqlApproved);

while ($row = mysqli_fetch_assoc($resultApproved)) {
    $statusClass = $row['status'] == 'Approved' ? 'text-success' : 'text-danger';
    echo "<tr>
        <td style='display: none;'>{$row['id']}</td>
        <td>{$row['customer']}</td>
        <td>{$row['code']}</td>
        <td>{$row['tgl_approve_rmp']}</td>
        <td>{$row['tgl_approve_lab']}</td>
        <td>{$row['tgl_rejected_lab']}</td>
        <td>{$row['pic_lab']}</td>
        <td><strong class='$statusClass'>{$row['status']}</strong></td>
    </tr>";
}
?>
