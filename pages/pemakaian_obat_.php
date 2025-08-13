<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>
<?php
// Set nilai-nilai $_POST ke dalam session saat formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['tgl'] = $_POST['tgl'];
    $_SESSION['tgl2'] = $_POST['tgl2'];
    $_SESSION['warehouse'] = $_POST['warehouse'];
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LAB - Data Pemakaian Obat Tutup Transaksi Gd. Kimia</title>
</head>
<style>
    .modal-backdrop {
    z-index: 1040 !important;
    }
    .modal {
    z-index: 1050 !important;
    }
    td.details-control {
        background: url('bower_components/DataTable/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('bower_components/DataTable/img/details_close.png') no-repeat center center;
    }

    th {
        font-size: 10pt;
    }

    td {
        font-size: 9pt;
    }

    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm_filter label input.form-control {
        width: 500px;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #Table-sm>thead>tr>td {
        border: 1px solid #ddd;
    }

    .btn-circle {
        border-radius: 10px;
        color: black;
        font-weight: 800;
    }

    .btn-grp>a,
    .btn-grp>button {
        margin-top: 2px;
    }
</style>
<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 999; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4); 
}

.modal-content {
  background-color: #fefefe;
  margin: 5% auto;
  padding: 20px;
  border-radius: 6px;
  width: 60%;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.close {
  color: #aaa;
  float: right;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
}
#Table-obat tbody tr:hover {
    background-color: #f2f9ff; /* biru muda */
    cursor: pointer;
}
#Table-obat.table-bordered th,
#Table-obat.table-bordered td {
    border: 1px solid #6c757d; /* abu tua, bisa diganti hitam (#000) */
}

.modal-dialog.modal-custom {
    max-width: 95%;  /* bisa kamu ubah ke 90%, 98%, dll */
    width: 95%;
    margin: 30px auto;
}

.btn-fixed {
        display: inline-block;
        width: 100px; /* kamu bisa ubah jadi 80px atau 90px sesuai keinginan */
        text-align: center;
        padding: 6px 0;
    }

    td {
        text-align: center; /* agar tombol di tengah kolom */
        vertical-align: middle;
    }

    .btn-fixed {
    display: inline-block;
    min-width: 100px;
    text-align: center;
}

</style>
<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Filter Data</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <!-- <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1"> -->                    
                <form action="" method="post">
                <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-2" style="display: flex; gap: 10px;">
                            <input type="date" class="form-control" required
                                    placeholder="Tanggal Awal" name="tgl"
                                    value="<?php if (isset($_POST['submit'])) {
                                        echo $_POST['tgl'];
                                    } ?>"
                                    required>                               
                            </div>
                            <div class="col-sm-2" style="display: flex; gap: 10px;">
                                <input type="date" class="form-control" required
                                    placeholder="Tanggal Akhir" name="tgl2"
                                    value="<?php if (isset($_POST['submit'])) {
                                        echo $_POST['tgl2'];
                                    } ?>"
                                    required>   
                                    
                                       <input name="time" type="text" class="form-control" id="time"
                                    placeholder="00:00" pattern="[0-9]{2}:[0-9]{2}$"
                                    title=" e.g 14:25" onkeyup="
                                                        var time = this.value;
                                                        if (time.match(/^\d{2}$/) !== null) {
                                                            this.value = time + ':';
                                                        } else if (time.match(/^\d{2}\:\d{2}$/) !== null) {
                                                            this.value = time + '';
                                                        }" value="<?php if (isset($_POST['submit'])) {
                                                            echo $_POST['time'];
                                                        } ?>" size="5" maxlength="5"
                                    >
                            </div>
                            <!-- <div class="col-sm-2">
                                <select name="warehouse" class="form-control"
                                        style="width: 100%;" required>
                                        <?php
                                        $sqlDB = "SELECT  
                                                            TRIM(CODE) AS CODE,
                                                            LONGDESCRIPTION 
                                                        FROM
                                                            LOGICALWAREHOUSE
                                                            WHERE CODE IN('M510','M101')
                                                        ORDER BY 
                                                            CODE ASC";
                                        $stmt = db2_exec($conn1, $sqlDB);
                                        while ($rowdb = db2_fetch_assoc($stmt)) {
                                            ?>
                                                <option value="<?= $rowdb['CODE']; ?>"
                                                    <?php if ($rowdb['CODE'] == $_POST['warehouse']) {
                                                        echo "SELECTED";
                                                    } ?>>
                                                    <?= $rowdb['CODE'] . " - " . $rowdb['LONGDESCRIPTION'];?>
                                                </option>
                                        <?php } ?>
                                    </select>
                            </div> -->
                            <div class="col-sm-2">
                            <button type="submit" name="submit"
                                class="btn btn-primary btn-sm"><i
                                    class="icofont icofont-search-alt-1"></i> Cari data</button>
                            </div>                            
                        </div>
                    </div>                    
                </form>         
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">                
                <div class="box-header with-border">
                <div class="card-header table-card-header">
                    <h5>LAPORAN BULANAN PEMAKAIAN OBAT GUDANG KIMIA</h5>
                </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">  
                        <div class="card-header mb-3 d-flex justify-content-end">
                            <a href="pages/cetak/cetak_lap_sumarry_pemakaian_obat.php?" 
                            class="btn btn-primary" 
                            target="_blank"  data-warehouse="<?= $warehouse ?>">Cetak Excel</a><br><br>
                        </div>                        
                            <table id="Table-obat" class="table table-bordered table-hover" style="width: 100%;">
                            <?php
                                $code = $row['KODE_OBAT'];
                                $tgl1 = $_POST['tgl'];
                                $tgl2 = $_POST['tgl2'];
                                $jam = $_POST['time'];
                                // $warehouse = $_POST['warehouse'];
                                $code1 = $row['DECOSUBCODE01'];
                                $code2 = $row['DECOSUBCODE02'];
                                $code3 = $row['DECOSUBCODE03'];

                                $tahunBulan = date('Y-m', strtotime($tgl1));
                                $kode_obat = $row['KODE_OBAT'];

                                $date = new DateTime($tgl1);
                                $date->modify('-1 month');
                                $tahunBulan2 = $date->format('Y-m');

                                    $q_qty_stock = mysqli_query($con, "SELECT 
                                    *
                                    from tblopname_11 ta 
                                    where 
                                    tgl_tutup ='$tgl2'
                                    and KODE_OBAT = 'E-3-003'Y
                                    and LOGICALWAREHOUSECODE in ('M510')
                                    order by KODE_OBAT asc");        
                                        
                                ?>
                                
                                <thead>
                                    <tr>
                                        <th>Kode Obat</th>
                                        <th>Dyestuff/Chemical</th>
                                        <th>warehouse</th>
                                        <th>Stock tgl 31</th>
                                        <th>Pemakaian tgl 30 </th> 
                                        <th>stock awal tgl 30-07-25</th>
                                        <th>Tanggal tutup</th>    
                                        <th>Tanggal tutup dan waktu</th>                                      
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_array($q_qty_stock)) {
                                    // Hitung detik tambahan berdasarkan baris
                                    $detikTambah = floor(($no - 1) / 20); // tiap 20 baris, tambah 1 detik
                                
                                    // Pisahkan jam dan tanggal awal
                                    $datetimeAwal = "$tgl1 $jam"; // Misal: 2025-07-30 23:01
                                
                                    // Buat objek DateTime tanpa detik
                                    $dt = new DateTime($datetimeAwal . ':00');

                                    // Tambah detik secara aktual
                                    $dt->modify("+$detikTambah seconds");

                                    // Format datetime ke string
                                    $waktu = $dt->format('Y-m-d H:i:s');

                                $stock_keluar = db2_exec($conn1, "SELECT 
                                        ITEMTYPECODE,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                        SATUAN_TRANSFER
                                        FROM 
                                        (SELECT
                                            s.ITEMTYPECODE,
                                            s.DECOSUBCODE01,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,
                                            SUM(s.USERPRIMARYQUANTITY) AS QTY_TRANSFER,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                ELSE s.USERPRIMARYUOMCODE
                                            END AS SATUAN_TRANSFER
                                        FROM
                                            STOCKTRANSACTION s
                                        WHERE
                                            s.ITEMTYPECODE = 'DYC'
                                            AND s.CREATIONDATETIME
                                                BETWEEN '$tgl1 23:01:00' AND '$tgl2 23:00:59'
                                            AND s.TEMPLATECODE IN ('120','098','303')
                                            AND s.LOGICALWAREHOUSECODE in ('M510')
                                            and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                            s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                            s.DECOSUBCODE03 = '$row[DECOSUBCODE03]' and 
                                             s.LOGICALWAREHOUSECODE = '$row[LOGICALWAREHOUSECODE]' 
                                        GROUP BY
                                            s.ITEMTYPECODE,
                                            s.DECOSUBCODE01,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,    
                                            s.USERPRIMARYUOMCODE)
                                        GROUP BY 
                                        ITEMTYPECODE,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        SATUAN_TRANSFER");
                                    $row_stock_transfer = db2_fetch_assoc($stock_keluar);                                                                
                                
                                
                                    $qty_Stock =  $row['BASEPRIMARYQUANTITYUNIT'] ;

                                        $qty_Transfer =  $row_stock_transfer['QTY_TRANSFER'] ;

                                    $stock_tgl_sebelumnya = ($row['BASEPRIMARYQUANTITYUNIT'] + $row_stock_transfer['QTY_TRANSFER']);

                                    ?>                               
                                    <tr>
                                        <td><?php echo $row['KODE_OBAT'] ?></td>
                                        <td><?php echo $row['LONGDESCRIPTION'] ?></td>
                                        <td><?php echo $row['LOGICALWAREHOUSECODE'] ?></td>
                                        <td><?= $qty_Stock ?></td>                                                                                                         
                                        <td>
                                       <?= $qty_Transfer ?>
                                        </td> 
                                        <td><?= $stock_tgl_sebelumnya ?> </td>
                                        <td><?= $tgl1 ?></td> 
                                        <td><?= $waktu ?> </td>                                       
                                </tr>                                   
                                <?php
                                    // $no++;

                                    // $ipaddress = $_SERVER['REMOTE_ADDR'];
                                    // $today = date('Y-m-d');

                                    // include_once("koneksi.php");
                                                                
                                    // // Escape semua input untuk mencegah SQL Injection
                                    // $kode_obat = mysqli_real_escape_string($con, $row['KODE_OBAT']);
                                    // $nama_obat = mysqli_real_escape_string($con, $row['LONGDESCRIPTION']);
                                    // $qty_awal = floatval(str_replace(',', '', $qty_awal));
                                    // $stock_masuk = floatval(str_replace(',', '', $qty_masuk));
                                    // $stock_keluar = floatval(str_replace(',', '', $qty_Keluar));
                                    // $stock_transfer = floatval(str_replace(',', '', $qty_Transfer));
                                    // $stock_balance = floatval(str_replace(',', '', $qty_stock_balance));
                                    // $stock_minimum = floatval(str_replace(',', '', $qty_stock_minimum));
                                    // $buka_po = floatval(str_replace(',', '', $qty_stock_buka_PO));
                                    // $pakai_belum_timbang = floatval(str_replace(',', '', $qty_stock_pakai_belum_timbang));
                                    // $balance_future = floatval(str_replace(',', '', $sisa_stock_balance_future));
                                    // $status_ = mysqli_real_escape_string($con, $keterangan);
                                    // $note = mysqli_real_escape_string($con, $row_stock_minimum['NOTELAB']);
                                    // $sertifikat = mysqli_real_escape_string($con, $row_stock_minimum['CERTIFICATION']);
                                    // $ip = mysqli_real_escape_string($con, $ipaddress);
                                    // $warehouse = mysqli_real_escape_string($con, $_POST['warehouse']);

                                    // $sql = "INSERT INTO tblopname_11a (
                                    //             kode_obat,
                                    //             nama_obat,
                                    //             qty_awal,
                                    //             stock_masuk,
                                    //             stock_keluar,
                                    //             stock_transfer,
                                    //             stock_balance,
                                    //             stock_minimum,
                                    //             buka_po,
                                    //             stock_pakai_blum_timbang,
                                    //             stock_balance_future,
                                    //             status_,
                                    //             note,
                                    //             ket_sertifikat,
                                    //             tgl_tarik_data,
                                    //             ip_address,
                                    //             logicalwarehouse
                                    //         ) VALUES (
                                    //             '$kode_obat',
                                    //             '$nama_obat',
                                    //             $qty_awal,
                                    //             $stock_masuk,
                                    //             $stock_keluar,
                                    //             $stock_transfer,
                                    //             $stock_balance,
                                    //             $stock_minimum,
                                    //             $buka_po,
                                    //             $pakai_belum_timbang,
                                    //             $balance_future,
                                    //             '$status_',
                                    //             '$note',
                                    //             '$sertifikat',
                                    //             '$today',
                                    //             '$ip',
                                    //             '$warehouse'
                                    //         )";

                                    // $result = mysqli_query($con, $sql);

                                    // if (!$result) {
                                    //     die("Error executing query: " . mysqli_error($con));
                                    // }
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>