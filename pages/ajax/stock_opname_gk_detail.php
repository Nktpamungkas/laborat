<?php
// koneksi ke DB
include "../../koneksi.php";
include "../../includes/Penomoran_helper.php";

$tgl_tutup = $_POST['tgl_tutup'];
$warehouse = $_POST['warehouse'];

//cek apakah data sudah di migrasi ke tabel stock opname
$check = mysqli_query($con,"select id from tbl_stock_opname_gk 
            WHERE 
            tgl_tutup = '$tgl_tutup'
            and not KODE_OBAT='E-1-000' ") ;
$row_count=mysqli_num_rows($check);
mysqli_free_result($check);
if($row_count==0){
    $insert = mysqli_query($con,"INSERT INTO  tbl_stock_opname_gk (ITEMTYPECODE,KODE_OBAT,LONGDESCRIPTION,LOTCODE,LOGICALWAREHOUSECODE,tgl_tutup,total_qty,BASEPRIMARYUNITCODE,pakingan_standar)
           SELECT 
            ITEMTYPECODE,
            KODE_OBAT,
            LONGDESCRIPTION,
            LOTCODE,
            LOGICALWAREHOUSECODE,
            tgl_tutup,
            SUM(BASEPRIMARYQUANTITYUNIT) AS total_qty,
            BASEPRIMARYUNITCODE,
            '0'
        FROM tblopname_11 o
        WHERE 
            tgl_tutup = '$tgl_tutup'
            and not KODE_OBAT='E-1-000'
        GROUP BY  
            ITEMTYPECODE,
            KODE_OBAT,
            LONGDESCRIPTION,
            LOTCODE,
            LOGICALWAREHOUSECODE,
            tgl_tutup,
            BASEPRIMARYUNITCODE
        ORDER BY KODE_OBAT ASC ") ;
}

if(trim($warehouse," ")=="M101"){
    $query = "SELECT *
        FROM tbl_stock_opname_gk 
        WHERE 
            tgl_tutup = '$tgl_tutup'
            AND LOGICALWAREHOUSECODE = '$warehouse'
        ORDER BY KODE_OBAT ASC";
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
                    <th class='text-center'>QTY Dus</th>
                    <th class='text-center'>Standar packaging</th>
                    <th class='text-center'>Total Stock</th>
                    <th class='text-center'>Konfirmasi</th>
                </tr>
            </thead>";
        echo "<tbody>";

        while ($row = mysqli_fetch_assoc($stmt)) {
            if($row['konfirmasi']){
                $btn="<i class='fa fa-check' aria-hidden='true'></i> OK";
                $dus=Penomoran_helper::nilaiKeRibuan($row['qty_dus']);
            }else{
                $btn="<button class='btn btn-warning btn-sm detail' title='Detail' data-toggle='tooltip' ><i class='fa fa-info'></i></button>";
                if($_POST['akses']!="TIDAKJADI"){
                    $btn.="  <button class='btn btn-primary btn-sm confirm' title='Confirm' data-toggle='tooltip' ><i class='fa fa-check-square-o'></i></button>";
                }
                $dus=Penomoran_helper::nilaiKeRibuan($row['qty_dus']);
            }
            echo "<tr data-id='".$row['id']."' data-ps='".doubleval($row['pakingan_standar'])."' data-ts='".$row['total_stock']."' data-ko='".htmlspecialchars($row['KODE_OBAT'])."'>
                    <td class='text-center'>{$no}</td>
                    <td>" . htmlspecialchars($row['KODE_OBAT']) . "</td>
                    <td>" . htmlspecialchars($row['LONGDESCRIPTION']) . "</td>
                    <td>" . htmlspecialchars($row['LOTCODE']) . "</td>
                    <td class='text-center'>" . htmlspecialchars($row['LOGICALWAREHOUSECODE']) . "</td>
                    <td class='text-right'>".Penomoran_helper::nilaiKeRibuan($row['total_qty']*1000)." GR</td>
                    <td class='text-right' id='td_dus_".$row['id']."' >$dus</td>
                    <td class='text-right' id='ps_".$row['id']."'>".Penomoran_helper::nilaiKeRibuan(doubleval($row['pakingan_standar']))."</td>
                    <td class='text-right' id='ts_".$row['id']."' >".Penomoran_helper::nilaiKeRibuan($row['total_stock'])."</td>
                    <td class='text-center' id='confirm_".$row['id']."'>$btn</td>
                </tr>";
            $no++;
        }

        echo "</tbody></table>";
    }else{
        echo "Data Tutup Buku Tidak Tersedia";
    }
}
else if(trim($warehouse," ")=="M510"){
    $query = "SELECT *
        FROM tbl_stock_opname_gk 
        WHERE 
            tgl_tutup = '$tgl_tutup'
            AND LOGICALWAREHOUSECODE = '$warehouse'
        ORDER BY KODE_OBAT ASC";
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
                    <th class='text-center'>Kategori</th>
                    <th class='text-center'>Standar</th>
                    <th class='text-center'>Total Stock</th>
                    <th class='text-center'>Konfirmasi</th>
                </tr>
            </thead>";
        echo "<tbody>";

        while ($row = mysqli_fetch_assoc($stmt)) {
            if($row['konfirmasi']){
                $btn="<i class='fa fa-check' aria-hidden='true'></i> OK";
            }else{
                $btn="<button class='btn btn-warning btn-sm detail' title='Detail' data-toggle='tooltip' ><i class='fa fa-info'></i></button>";
                if($_POST['akses']!="TIDAKJADI"){
                    $btn.="  <button class='btn btn-primary btn-sm confirm' title='Confirm' data-toggle='tooltip' ><i class='fa fa-check-square-o' aria-hidden='true'></i></button>";
                }
            }
            echo "<tr data-id='".$row['id']."' data-ps='".doubleval($row['pakingan_standar'])."' data-ts='".$row['total_stock']."' data-ko='".htmlspecialchars($row['KODE_OBAT'])."'>
                    <td class='text-center'>{$no}</td>
                    <td>" . htmlspecialchars($row['KODE_OBAT']) . "</td>
                    <td>" . htmlspecialchars($row['LONGDESCRIPTION']) . "</td>
                    <td>" . htmlspecialchars($row['LOTCODE']) . "</td>
                    <td class='text-center'>" . htmlspecialchars($row['LOGICALWAREHOUSECODE']) . "</td>
                    <td class='text-right'>".Penomoran_helper::nilaiKeRibuan($row['total_qty']*1000)." GR</td>
                    <td class='text-right' id='td_dus_".$row['id']."' >".ucfirst($row['kategori'])."<br/>Qty : ".Penomoran_helper::nilaiKeRibuan($row['qty_dus'])."</td>
                    <td class='text-right' id='ps_".$row['id']."'>".Penomoran_helper::nilaiKeRibuan(doubleval($row['pakingan_standar']))."</td>
                    <td class='text-right' id='ts_".$row['id']."' >".Penomoran_helper::nilaiKeRibuan($row['total_stock'])."</td>
                    <td class='text-center' id='confirm_".$row['id']."'>$btn</td>
                </tr>";
            $no++;
        }

        echo "</tbody></table>";
    }else{
        echo "Data Tutup Buku Tidak Tersedia";
    }
}
 else {
    echo "<p class='text-warning'>Tidak ada data untuk ditampilkan.</p>";
}
?>