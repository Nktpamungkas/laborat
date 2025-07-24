<?php
// koneksi ke DB
include "../../koneksi.php";

$tgl_tutup = $_POST['tgl_tutup'];
$warehouse = $_POST['warehouse'];

// echo "<pre>";
// print_r($_POST); // Debug POST value
// echo "</pre>";

$query = "SELECT 
        ITEMTYPECODE,
        KODE_OBAT,
        LONGDESCRIPTION,
        LOTCODE,
        LOGICALWAREHOUSECODE,
        tgl_tutup,
        SUM(BASEPRIMARYQUANTITYUNIT) AS total_qty,
        BASEPRIMARYUNITCODE 
    FROM tblopname_11
    WHERE 
        tgl_tutup = '$tgl_tutup'
        AND LOGICALWAREHOUSECODE = '$warehouse'
    GROUP BY  
        ITEMTYPECODE,
        KODE_OBAT,
        LONGDESCRIPTION,
        LOTCODE,
        LOGICALWAREHOUSECODE,
        tgl_tutup,
        BASEPRIMARYUNITCODE
    ORDER BY KODE_OBAT ASC";

// echo "<pre>$query</pre>";

$stmt = mysqli_query($con, $query);
if (!$stmt) {
    echo "<p class='text-danger'>Query gagal: " . mysqli_error($con) . "</p>";
    exit;
}

if (mysqli_num_rows($stmt) > 0) {
    $no = 1;
    echo "<table class='table table-bordered table-striped' id='detailmasukTable'>";
    echo "<thead>
                <tr>
                    <th class='text-center'>No</th>
                    <th class='text-center'>Kode Obat</th>
                    <th class='text-center'>Nama Obat</th>
                    <th class='text-center'>Lot</th>
                    <th class='text-center'>Logical Warehouse</th>
                    <th class='text-center'>Qty (Ending Balance)</th>
                </tr>
          </thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_assoc($stmt)) {
        echo "<tr>
                <td class='text-center'>{$no}</td>
                <td>" . htmlspecialchars($row['KODE_OBAT']) . "</td>
                <td>" . htmlspecialchars($row['LONGDESCRIPTION']) . "</td>
                <td>" . htmlspecialchars($row['LOTCODE']) . "</td>
                <td class='text-center'>" . htmlspecialchars($row['LOGICALWAREHOUSECODE']) . "</td>
                <td class='text-right'>" . number_format((float) $row['total_qty'], 2) . "</td>
              </tr>";
        $no++;
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
}
?>