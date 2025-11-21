<?php
include "../../koneksi.php";

$is_revision = isset($_GET['is_revision']) ? (int)$_GET['is_revision'] : 0;

$sqlApproved = "
  SELECT id, customer, code, tgl_approve_lab, pic_lab, status, approvalrmpdatetime
  FROM approval_bon_order
  WHERE is_revision = $is_revision
  ORDER BY id DESC
";
$resApproved = mysqli_query($con, $sqlApproved);

$rowsApproved = [];
$codes = [];
while ($r = mysqli_fetch_assoc($resApproved)) {
    $rowsApproved[] = $r;
    $codes[] = strtoupper(trim($r['code']));
}

function db2_quote($s) { return str_replace("'", "''", $s); }

foreach ($rowsApproved as $row) {
    $code   = strtoupper(trim($row['code']));

    $statusClass = ($row['status'] === 'Approved') ? 'text-success' : 'text-danger';

    echo "<tr>";
    echo   "<td style='display: none;'>" . htmlspecialchars($row['id']) . "</td>";
    echo   "<td>" . htmlspecialchars($row['customer']) . "</td>";
    echo   "<td>
              <a href=\"#\" class=\"btn btn-primary btn-sm open-detail\"
                 data-code=\"" . htmlspecialchars($code) . "\"
                 data-toggle=\"modal\" data-target=\"#detailModal\">" .
                 htmlspecialchars($code) .
              "</a>
            </td>";
    echo "<td>" . (!empty($row['approvalrmpdatetime']) 
                    ? htmlspecialchars(date('Y-m-d', strtotime($row['approvalrmpdatetime']))) 
                    : '') . "</td>";
    echo   "<td>" . htmlspecialchars($row['tgl_approve_lab']) . "</td>";
    echo   "<td>" . htmlspecialchars($row['pic_lab']) . "</td>";
    echo   "<td><strong class=\"" . $statusClass . "\">" . htmlspecialchars($row['status']) . "</strong></td>";
    echo "</tr>";
}
