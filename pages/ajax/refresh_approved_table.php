<?php
include "../../koneksi.php";

$is_revision = isset($_GET['is_revision']) ? (int)$_GET['is_revision'] : 0;

$sqlApproved = "SELECT * FROM approval_bon_order WHERE is_revision = $is_revision ORDER BY id DESC";
$resultApproved = mysqli_query($con, $sqlApproved);

while ($row = mysqli_fetch_assoc($resultApproved)) {
    $statusClass = $row['status'] == 'Approved' ? 'text-success' : 'text-danger';
    echo "<tr>
        <td style='display: none;'>{$row['id']}</td>
        <td>{$row['customer']}</td>
        <td>
            <a href=\"#\" class=\"btn btn-primary btn-sm open-detail\" data-code=\"" . htmlspecialchars($row['code']) . "\" data-toggle=\"modal\" data-target=\"#detailModal\">" .
                htmlspecialchars($row['code']) .
            "</a>
        </td>
        <td>{$row['tgl_approve_rmp']}</td>
        <td>{$row['tgl_approve_lab']}</td>
        <td>{$row['tgl_rejected_lab']}</td>
        <td>{$row['pic_lab']}</td>
        <td><strong class=\"$statusClass\">{$row['status']}</strong></td>
    </tr>";
}
?>
