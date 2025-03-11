<?php
include "../../koneksi.php";
session_start();

if (isset($_POST['tanggal_awal']) && isset($_POST['tanggal_akhir'])) {
    $tanggal_awal  = $_POST['tanggal_awal'];
    $tanggal_akhir = $_POST['tanggal_akhir'];

    // Query dengan filter tanggal
    $query = "SELECT *, pw.PMBREAKDOWNENTRYCODE AS WORKORDERCODE
              FROM PMWORKORDERDETAIL pwd
              LEFT JOIN PMWORKORDER pw ON pw.CODE=pwd.PMWORKORDERCODE
              WHERE pwd.ASSIGNEDTOUSERID = 'clivi.lab'
              AND pwd.CREATIONDATETIME BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'";

    $result    = db2_exec($conn1, $query);
    $no        = 1;
    $statusMap = [
        0 => "Open",
        1 => "Assigned",
        2 => "In Progress",
        3 => "Closed",
        4 => "Suspended",
        5 => "Canceled",
    ];

    // Buat output tabel
    while ($value = db2_fetch_assoc($result)) {
        echo "<tr>
                <td class='text-center'>{$no}</td>
                <td class='text-center'>{$value['WORKORDERCODE']}</td>
                <td class='text-center'>" . date('Y-m-d H:i:s', strtotime($value['STARTDATE'])) . "</td>
                <td class='text-center'>" . date('Y-m-d H:i:s', strtotime($value['ENDDATE'])) . "</td>
                <td class='text-center'>{$value['REMARKS']}</td>
                <td class='text-center'>" . ($statusMap[$value['STATUS']] ?? "Unknown") . "</td>
              </tr>";
        $no++;
    }
}
