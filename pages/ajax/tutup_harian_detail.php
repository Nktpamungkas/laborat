<?php
// koneksi ke DB
include "../../koneksi.php";

$tgl_tutup = $_POST['tgl_tutup'];
$warehouse = $_POST['warehouse'];

$query = "SELECT
     ITEMTYPECODE,
        KODE_OBAT,
        LONGDESCRIPTION,
        LOTCODE,
        LOGICALWAREHOUSECODE,
        TGL_TUTUP,
        SUM(BASEPRIMARYQUANTITYUNIT) AS total_qty,
        BASEPRIMARYUNITCODE 
     FROM 
    (SELECT DISTINCT
        ITEMTYPECODE,
        KODE_OBAT,
        LONGDESCRIPTION,
        LOTCODE,
        LOGICALWAREHOUSECODE,
        WHSLOCATIONWAREHOUSEZONECODE,
        TGL_TUTUP,
        BASEPRIMARYQUANTITYUNIT,
        BASEPRIMARYUNITCODE 
    FROM tblopname_11
    WHERE 
        tgl_tutup = '$tgl_tutup'
        AND LOGICALWAREHOUSECODE = '$warehouse'
        and not KODE_OBAT='E-1-000') as sub
    GROUP BY  
        ITEMTYPECODE,
        KODE_OBAT,
        LONGDESCRIPTION,
        LOTCODE,
        LOGICALWAREHOUSECODE,
        TGL_TUTUP,
        BASEPRIMARYUNITCODE
    ORDER BY KODE_OBAT ASC ";

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
        $value = (string) $row['total_qty'];

        if (strpos($value, '.') !== false) {
            // Hapus nol di belakang desimal, tapi jangan hilangkan titik kalau hasilnya bilangan bulat
            $formatted = rtrim(rtrim($value, '0'), '.');

            // Jika desimalnya habis (misal 50.), tambahkan .00
            if (strpos($formatted, '.') === false) {
                $formatted .= '.00';
            } else {
                // Kalau desimalnya tinggal 1 digit, tambahkan 0
                $decimal_part = explode('.', $formatted)[1];
                if (strlen($decimal_part) === 1) {
                    $formatted .= '0';
                }
            }
        } else {
            // Bilangan bulat → tambahkan .00
            $formatted = $value . '.00';
        }

        echo "<tr>
                <td class='text-center'>{$no}</td>
                <td>" . htmlspecialchars($row['KODE_OBAT']) . "</td>
                <td>" . htmlspecialchars($row['LONGDESCRIPTION']) . "</td>
                <td>" . htmlspecialchars($row['LOTCODE']) . "</td>
                <td class='text-center'>" . htmlspecialchars($row['LOGICALWAREHOUSECODE']) . "</td>
                <td class='text-right'>{$formatted}</td>
              </tr>";
        $no++;
    }

    echo "</tbody></table>";
} else {
    echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
}
?>