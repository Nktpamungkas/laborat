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
    <title>LAB - laporan Pemakaian Obat Gd. Kimia per Kategori</title>
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
                            </div>
                            <div class="col-sm-2">
                                <select name="warehouse" class="form-control"
                                        style="width: 100%;" required>
                                            <option value="M510">M510</option>
                                        <?php
                                        $sqlDB = "SELECT  
                                                            TRIM(CODE) AS CODE,
                                                            LONGDESCRIPTION 
                                                        FROM
                                                            LOGICALWAREHOUSE
                                                        ORDER BY 
                                                            CODE ASC";
                                        $stmt = db2_exec($conn1, $sqlDB);
                                        while ($rowdb = db2_fetch_assoc($stmt)) {
                                            ?>
                                                <option value="<?= $rowdb['CODE']; ?>"
                                                    <?php if ($rowdb['CODE'] == $_POST['warehouse']) {
                                                        echo "SELECTED";
                                                    } ?>>
                                                    <?= $rowdb['CODE']; ?>         <?= $rowdb['LONGDESCRIPTION']; ?>
                                                </option>
                                        <?php } ?>
                                    </select>
                            </div>
                            <div class="col-sm-2">
                            <button type="submit" name="submit"
                                class="btn btn-primary btn-sm"><i
                                    class="icofont icofont-search-alt-1"></i> Cari data</button>
                            </div>                            
                        </div>
                    </div>                    
                </form>
                <?php
                include 'koneksi.php'; // koneksi ke MySQL
                
                $ipaddress = $_SERVER['REMOTE_ADDR'];

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                    if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {

                        // Siapkan statement prepared
                        $sql2 = "DELETE FROM tb_stock_gd_kimia_kategori WHERE IP_ADDRESS = ?";
                        $stmt2 = mysqli_prepare($con, $sql2);

                        if ($stmt2) {
                            // Bind parameter IP address
                            mysqli_stmt_bind_param($stmt2, "s", $ipaddress);
                            mysqli_stmt_execute($stmt2);
                            
                            // // Eksekusi query
                            // if (mysqli_stmt_execute($stmt2)) {
                            //     // echo "<div class='alert alert-success'>Data dengan IP $ipaddress berhasil dihapus.</div>";
                            // } else {
                            //     // echo "<div class='alert alert-danger'>Gagal menghapus dari tb_stock_gd_kimia: " . mysqli_error($con) . "</div>";
                            // }

                            mysqli_stmt_close($stmt2);
                        } else {
                            // echo "<div class='alert alert-danger'>Gagal menyiapkan statement: " . mysqli_error($con) . "</div>";
                        }

                    } else {
                        // echo "<div class='alert alert-warning'>IP address tidak valid.</div>";
                    }
                }
                ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">                
                <div class="box-header with-border">
                <div class="card-header table-card-header">
                    <h5>LAPORAN PEMAKAIAN OBAT GUDANG KIMIA PERKATEGORI</h5>
                </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">  
                        <div class="card-header mb-3 d-flex justify-content-end">
                            <a href="pages/cetak/cetak_lap_sumarry_pemakaian_obat.php?" 
                            class="btn btn-primary" 
                            target="_blank">Cetak Excel</a><br><br>
                        </div>                        
                            <table id="Table-obat" class="table table-bordered table-hover" style="width: 100%;">
                            <?php
                            $db_stocktransaction = db2_exec($conn1," SELECT 
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        KODE_OBAT,
                                        sum(AKTUAL_QTY) AS AKTUAL_QTY_KELUAR,
                                        SATUAN,
                                        LONGDESCRIPTION
                                    FROM 
                                    (
                                    SELECT           
                                        s.DECOSUBCODE01,
                                        s.DECOSUBCODE02,
                                        s.DECOSUBCODE03,
                                        TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,
                                        CASE 
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                            WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                            ELSE s.USERPRIMARYQUANTITY
                                        END AS AKTUAL_QTY,
                                        CASE 
                                            WHEN s.USERPRIMARYUOMCODE = 't' THEN 'kg' 
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                            ELSE s.USERPRIMARYUOMCODE 
                                        END AS SATUAN,
                                        p.LONGDESCRIPTION,
                                        s.TEMPLATECODE
                                    FROM
                                        STOCKTRANSACTION s
                                    LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                        AND p.SUBCODE01 = s.DECOSUBCODE01
                                        AND p.SUBCODE02 = s.DECOSUBCODE02
                                        AND p.SUBCODE03 = s.DECOSUBCODE03                                
                                WHERE  
                                    s.ITEMTYPECODE = 'DYC'
                                    AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                    AND s.TEMPLATECODE IN ('120','201','203')
                                    AND (s.DETAILTYPE = 1 OR s.DETAILTYPE = 0)
                                    AND s.LOGICALWAREHOUSECODE ='$_POST[warehouse]'
                                    -- AND s.DECOSUBCODE01 = 'E'
                                    -- AND s.DECOSUBCODE02 IN ('4','8')
                                    -- AND s.DECOSUBCODE03  IN('014','011')
                                    -- AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$_POST[tgl] 07:00:00' AND '$_POST[tgl2] 12:00:00' 
                                    )
                                    GROUP BY 
                                    DECOSUBCODE01,
                                    DECOSUBCODE02,
                                    DECOSUBCODE03,
                                    KODE_OBAT,
                                    SATUAN,
                                    LONGDESCRIPTION
                                    ORDER BY KODE_OBAT ASC ");                           
                                        
                                ?>
                                
                                <thead>
                                    <tr>
                                        <th>Kode Obat</th>
                                        <th>Dyestuff/Chemical</th>
                                        <th>Stock Awal (gr)</th>
                                        <th>Masuk</th>
                                        <th>Pemakaian (gr)</th>
                                        <th>Stock Balance</th>                                       
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                while ($row = db2_fetch_assoc($db_stocktransaction)) {                                    

                                // $stock_transfer = db2_exec($conn1, "SELECT 
                                //         ITEMTYPECODE,
                                //         DECOSUBCODE01,
                                //         DECOSUBCODE02,
                                //         DECOSUBCODE03,
                                //         sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                //         SATUAN_TRANSFER
                                //         FROM 
                                //         (SELECT
                                //             s.ITEMTYPECODE,
                                //             s.DECOSUBCODE01,
                                //             s.DECOSUBCODE02,
                                //             s.DECOSUBCODE03,
                                //             CASE 
                                //                 WHEN s.USERPRIMARYUOMCODE = 't' THEN SUM(s.USERPRIMARYQUANTITY) * 1000000
                                //                 WHEN s.USERPRIMARYUOMCODE = 'kg' THEN SUM(s.USERPRIMARYQUANTITY) * 1000
                                //                 ELSE SUM(s.USERPRIMARYQUANTITY)
                                //             END AS QTY_TRANSFER,
                                //             CASE 
                                //                 WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                //                 WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                //                 ELSE s.USERPRIMARYUOMCODE
                                //             END AS SATUAN_TRANSFER
                                //         FROM
                                //             STOCKTRANSACTION s
                                //         WHERE
                                //             s.ITEMTYPECODE = 'DYC'
                                //             AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                //             AND s.TEMPLATECODE IN ('201','203')
                                //             AND s.LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                //             and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                //             s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                //             s.DECOSUBCODE03 = '$row[DECOSUBCODE03]' 
                                //         GROUP BY
                                //             s.ITEMTYPECODE,
                                //             s.DECOSUBCODE01,
                                //             s.DECOSUBCODE02,
                                //             s.DECOSUBCODE03,    
                                //             s.USERPRIMARYUOMCODE)
                                //         GROUP BY 
                                //         ITEMTYPECODE,
                                //         DECOSUBCODE01,
                                //         DECOSUBCODE02,
                                //         DECOSUBCODE03,
                                //         SATUAN_TRANSFER");
                                //     $row_stock_transfer = db2_fetch_assoc($stock_transfer);

                                    $stock_masuk = db2_exec($conn1, "SELECT 
                                    ITEMTYPECODE,
                                    DECOSUBCODE01,
                                    DECOSUBCODE02,
                                    DECOSUBCODE03,
                                    sum(QTY_MASUK) AS QTY_MASUK,
                                    SATUAN_MASUK
                                    FROM 
                                    (SELECT
                                        s.ITEMTYPECODE,
                                        s.DECOSUBCODE01,
                                        s.DECOSUBCODE02,
                                        s.DECOSUBCODE03,
                                        CASE 
                                            WHEN s.USERPRIMARYUOMCODE = 't' THEN SUM(s.USERPRIMARYQUANTITY) * 1000000
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN SUM(s.USERPRIMARYQUANTITY) * 1000
                                            ELSE SUM(s.USERPRIMARYQUANTITY)
                                        END AS QTY_MASUK,
                                        CASE 
                                            WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                            ELSE s.USERPRIMARYUOMCODE
                                        END AS SATUAN_MASUK
                                    FROM
                                        STOCKTRANSACTION s
                                    WHERE
                                        s.ITEMTYPECODE = 'DYC'
                                        AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                        AND s.TEMPLATECODE IN ('QC1','QCT','304','OPN','204')
                                        AND s.LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                        and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                        s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                        s.DECOSUBCODE03 = '$row[DECOSUBCODE03]' 
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
                                    SATUAN_MASUK");
                                    $row_stock_masuk = db2_fetch_assoc($stock_masuk);

                                    $Balance_stock = db2_exec($conn1, "SELECT 
                                            b.ITEMTYPECODE,
                                            b.DECOSUBCODE01,
                                            b.DECOSUBCODE02,
                                            b.DECOSUBCODE03,
                                            CASE 
                                                WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000
                                                WHEN b.BASEPRIMARYUNITCODE = 't' THEN sum(b.BASEPRIMARYQUANTITYUNIT)*1000000
                                                ELSE sum(b.BASEPRIMARYQUANTITYUNIT)
                                            END  AS STOCK_BALANCE,
                                            CASE 
                                                WHEN b.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                                WHEN b.BASEPRIMARYUNITCODE = 't' THEN 'g'
                                                ELSE b.BASEPRIMARYUNITCODE
                                            END  AS BASEPRIMARYUNITCODE
                                            FROM 
                                            BALANCE b 
                                            WHERE 
                                            ITEMTYPECODE ='DYC'
                                            AND LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                            AND DETAILTYPE = 1
                                            AND DECOSUBCODE01 = '$row[DECOSUBCODE01]' 
                                            AND DECOSUBCODE02 = '$row[DECOSUBCODE02]' 
                                            AND DECOSUBCODE03 = '$row[DECOSUBCODE03]' 
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            b.DECOSUBCODE01,
                                            b.DECOSUBCODE02,
                                            b.DECOSUBCODE03,
                                            b.BASEPRIMARYUNITCODE");
                                    $row_balance = db2_fetch_assoc($Balance_stock);

                                    // $stock_minimum = db2_exec($conn1, " SELECT 
                                    //         i.ITEMTYPECODE,
                                    //         i.SUBCODE01,
                                    //         i.SUBCODE02, 
                                    //         i.SUBCODE03,
                                    //         i.SUBCODE04,
                                    //         i.SUBCODE05,
                                    //         i.SUBCODE06,
                                    //         i.SUBCODE07,
                                    //         i.SUBCODE08,
                                    //         i.SUBCODE09,
                                    //         i.SUBCODE10,
                                    //         CASE 
                                    //             WHEN p.BASEPRIMARYUNITCODE = 't' THEN i.SAFETYSTOCK *1000000
                                    //             WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN i.SAFETYSTOCK *1000
                                    //             ELSE i.SAFETYSTOCK
                                    //         END AS SAFETYSTOCK,
                                    //         CASE 
                                    //             WHEN p.BASEPRIMARYUNITCODE = 't' THEN 'g'
                                    //             WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                    //             ELSE p.BASEPRIMARYUNITCODE
                                    //         END AS BASEPRIMARYUNITCODE,
                                    //         CASE 
                                    //             WHEN a.VALUESTRING = 1 THEN 'BV'
                                    //             WHEN a.VALUESTRING = 2 THEN 'NON BV'
                                    //             ELSE ''
                                    //         END CERTIFICATION,
                                    //         a2.VALUESTRING AS NOTELAB
                                    //         FROM 
                                    //         ITEMWAREHOUSELINK i 
                                    //         LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = i.ITEMTYPECODE 
                                    //         AND p.SUBCODE01 = i.SUBCODE01
                                    //         AND p.SUBCODE02 = i.SUBCODE02 
                                    //         AND p.SUBCODE03 = i.SUBCODE03 
                                    //         AND p.SUBCODE04 = i.SUBCODE04 
                                    //         AND p.SUBCODE05 = i.SUBCODE05 
                                    //         AND p.SUBCODE06 = i.SUBCODE06 
                                    //         AND p.SUBCODE07 = i.SUBCODE07 
                                    //         AND p.SUBCODE08 = i.SUBCODE08 
                                    //         AND p.SUBCODE09 = i.SUBCODE09 
                                    //         AND p.SUBCODE10 = i.SUBCODE10 
                                    //         LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME ='Certification'
                                    //         LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME ='NoteLab'
                                    //         WHERE  
                                    //         i.ITEMTYPECODE ='DYC'
                                    //         AND i.LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                    //         AND i.SUBCODE01 = '$row[DECOSUBCODE01]'
                                    //         AND i.SUBCODE02 = '$row[DECOSUBCODE02]' 
                                    //         AND i.SUBCODE03 = '$row[DECOSUBCODE03]' ");
                                    // $row_stock_minimum = db2_fetch_assoc($stock_minimum);

                                    // $buka_po = db2_exec($conn1, "SELECT 
                                    //         LOGICALWAREHOUSECODE,
                                    //         COUNTERCODE,
                                    //         DECOSUBCODE01,
                                    //         DECOSUBCODE02,
                                    //         DECOSUBCODE03,
                                    //         DECOSUBCODE04,
                                    //         DECOSUBCODE05,
                                    //         DECOSUBCODE06,
                                    //         DECOSUBCODE07,
                                    //         CASE 
                                    //             WHEN BASEPRIMARYUNITCODE = 'kg' THEN sum(BASEPRIMARYQUANTITY)*1000
                                    //             WHEN BASEPRIMARYUNITCODE = 't' THEN sum(BASEPRIMARYQUANTITY)*1000000
                                    //             else sum(BASEPRIMARYQUANTITY)
                                    //         END AS QTY,
                                    //         CASE 
                                    //             WHEN BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                    //             WHEN BASEPRIMARYUNITCODE = 't' THEN 'g'
                                    //             else BASEPRIMARYUNITCODE
                                    //         END AS BASEPRIMARYUNITCODE 
                                    //         FROM 
                                    //         VIEWAVANALYSISPART1 v 
                                    //         WHERE ISTANCETYPE = '6'
                                    //         AND LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                    //         AND DUEDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                    //         and DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                    //         DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                    //         DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                    //         GROUP BY 
                                    //         LOGICALWAREHOUSECODE,
                                    //         COUNTERCODE,
                                    //         DECOSUBCODE01,
                                    //         DECOSUBCODE02,
                                    //         DECOSUBCODE03,
                                    //         DECOSUBCODE04,
                                    //         DECOSUBCODE05,
                                    //         DECOSUBCODE06,
                                    //         DECOSUBCODE07,
                                    //         BASEPRIMARYUNITCODE");
                                    // $row_buka_po = db2_fetch_assoc($buka_po);

                                    // $pakai_belum_timbang = db2_exec($conn1, "SELECT 
                                    //         LOGICALWAREHOUSECODE,
                                    //         COUNTERCODE,
                                    //         ITEMTYPECODE,
                                    //         DECOSUBCODE01,
                                    //         DECOSUBCODE02,
                                    //         DECOSUBCODE03,
                                    //         STATUS,
                                    //         sum(BASEPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
                                    //         BASEPRIMARYUNITCODE
                                    //         FROM 
                                    //         (SELECT 
                                    //         LOGICALWAREHOUSECODE,
                                    //         COUNTERCODE,
                                    //         ISTANCECODE,
                                    //         ITEMTYPECODE,
                                    //         DUEDATE,
                                    //         DECOSUBCODE01,
                                    //         DECOSUBCODE02,
                                    //         DECOSUBCODE03,
                                    //         STATUS,
                                    //         CASE 
                                    //             WHEN BASEPRIMARYUNITCODE ='kg' THEN BASEPRIMARYQUANTITY * 1000
                                    //             WHEN BASEPRIMARYUNITCODE ='t' THEN BASEPRIMARYQUANTITY * 1000000
                                    //             ELSE BASEPRIMARYQUANTITY
                                    //         END BASEPRIMARYQUANTITY,
                                    //         BASEPRIMARYUNITCODE  
                                    //         FROM 
                                    //         VIEWAVANALYSISPART2 v 
                                    //         WHERE 
                                    //         ITEMTYPECODE ='DYC'
                                    //         AND STATUS = 0
                                    //         AND LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                    //         AND DECOSUBCODE01 = '$row[DECOSUBCODE01]'
                                    //         AND DECOSUBCODE02 = '$row[DECOSUBCODE02]'
                                    //         AND DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                    //         AND DUEDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]')
                                    //         GROUP BY 
                                    //         LOGICALWAREHOUSECODE,
                                    //         COUNTERCODE,
                                    //         STATUS,
                                    //         ITEMTYPECODE,
                                    //         DECOSUBCODE01,
                                    //         DECOSUBCODE02,
                                    //         DECOSUBCODE03,
                                    //         BASEPRIMARYUNITCODE");
                                    // $row_pakai_belum_timbang = db2_fetch_assoc($pakai_belum_timbang);                                    

                                    $q_qty_awal = mysqli_query($con, "SELECT * 
                                    FROM stock_awal_obat_gdkimia_1
                                    WHERE kode_obat = '$row[KODE_OBAT]'
                                    AND logicalwarehouse = '$_POST[warehouse]'
                                    ORDER BY kode_obat ASC");

                                    $row_qty_awal = mysqli_fetch_array($q_qty_awal);                                

                                    $code = $row['KODE_OBAT'];
                                    $tgl1 = $_POST['tgl'];
                                    $tgl2 = $_POST['tgl2'];
                                    $warehouse = $_POST['warehouse'];
                                    $code1 = $row['DECOSUBCODE01'];
                                    $code2 = $row['DECOSUBCODE02'];
                                    $code3 = $row['DECOSUBCODE03'];

                                    $qty_masuk = (substr(number_format($row_stock_masuk['QTY_MASUK'], 2), -3) == '.00')
                                        ? number_format($row_stock_masuk['QTY_MASUK'], 0)
                                        : number_format($row_stock_masuk['QTY_MASUK'], 2);

                                    $qty_Keluar = (substr(number_format($row['AKTUAL_QTY_KELUAR'], 2), -3) == '.00')
                                        ? number_format($row['AKTUAL_QTY_KELUAR'], 0)
                                        : number_format($row['AKTUAL_QTY_KELUAR'], 2);                                    

                                    $qty_awal = (substr(number_format($row_qty_awal['qty_awal'], 2), -3) == '.00')
                                        ? number_format($row_qty_awal['qty_awal'], 0)
                                        : number_format($row_qty_awal['qty_awal'], 2);

                                    $qty_stock_balance = (substr(number_format($row_balance['STOCK_BALANCE'], 2), -3) == '.00')
                                        ? number_format($row_balance['STOCK_BALANCE'], 0)
                                        : number_format($row_balance['STOCK_BALANCE'], 2);

                                    ?>                               
                                    <tr>
                                        <td><?php echo $row['KODE_OBAT'] ?></td>
                                        <td><?php echo $row['LONGDESCRIPTION'] ?></td>
                                        <td><?php echo $qty_awal ?></td>
                                        <td>
                                            <a width = "100%" href="#" class="btn btn-primary btn-sm btn-fixed open-detail2" 
                                            data-code1="<?= $code1 ?>" 
                                            data-code2="<?= $code2 ?>"                                                
                                            data-code3="<?= $code3 ?>" 
                                            data-tgl1="<?= $tgl1 ?>" 
                                            data-tgl2="<?= $tgl2 ?>" 
                                            data-warehouse="<?= $warehouse ?>"
                                                data-toggle="modal" data-target="#detailModal_masuk">
                                                <?= $qty_masuk ?>
                                            </a>
                                        </td>                                       
                                        <td>
                                            <a width = "100%" href="#" class="btn btn-primary btn-sm btn-fixed open-detail" 
                                            data-code1="<?= $code1 ?>" 
                                            data-code2="<?= $code2 ?>" 
                                            data-code3="<?= $code3 ?>" 
                                            data-tgl1="<?= $tgl1 ?>"
                                            data-tgl2="<?= $tgl2 ?>" 
                                            data-warehouse="<?= $warehouse ?>" 
                                            data-toggle="modal" data-target="#detailModal_pakai">
                                            <?= $qty_Keluar ?>
                                            </a>
                                        </td>                                                                          
                                        <td><?php echo $qty_stock_balance ?></td>
                                </tr>                                   
                                <?php
                                    $no++;

                                    $ipaddress = $_SERVER['REMOTE_ADDR'];
                                    $today = date('Y-m-d');

                                    include_once("koneksi.php");
                                                                
                                    // Escape semua input untuk mencegah SQL Injection
                                    $kode_obat = mysqli_real_escape_string($con, $row['KODE_OBAT']);
                                    $nama_obat = mysqli_real_escape_string($con, $row['LONGDESCRIPTION']);
                                    $qty_awal = floatval(str_replace(',', '', $qty_awal));
                                    $stock_masuk = floatval(str_replace(',', '', $qty_masuk));
                                    $stock_keluar = floatval(str_replace(',', '', $qty_Keluar));
                                    $stock_balance = floatval(str_replace(',', '', $qty_stock_balance));
                                    $ip = mysqli_real_escape_string($con, $ipaddress);
                                    $warehouse = mysqli_real_escape_string($con, $_POST['warehouse']);

                                    $sql = "INSERT INTO tb_stock_gd_kimia_kategori (
                                                kode_obat,
                                                nama_obat,
                                                qty_awal,
                                                stock_masuk,
                                                stock_keluar,
                                                stock_balance,                                               
                                                tgl_tarik_data,
                                                ip_address,
                                                logicalwarehouse
                                            ) VALUES (
                                                '$kode_obat',
                                                '$nama_obat',
                                                $qty_awal,
                                                $stock_masuk,
                                                $stock_keluar,
                                                $stock_balance,                                                
                                                '$today',
                                                '$ip',
                                                '$warehouse'
                                            )";

                                    $result = mysqli_query($con, $sql);

                                    if (!$result) {
                                        die("Error executing query: " . mysqli_error($con));
                                    }
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Modal qty transfer -->
<div id="detailModal_transfer" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Detail QTY Transfer</h4>
        </div>
        
        <div class="modal-body" id="modal-content">
            <div class="table-responsive">
                <table class="table table-bordered" id="detailTransferTable">
                <p>Loading data...</p>
                </table>
            </div>            
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
        
        </div>
    </div>
</div>

<!-- Modal qty pakai-->
<div id="detailModal_pakai" class="modal fade" tsabindex="-1" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Detail QTY Pemakaian</h4>
        </div>
        
        <div class="modal-body" id="modal-content_pakai">
            <div class="table-responsive">
                <table class="table table-bordered" id="detailPakaiTabel">
                <p>Loading data...</p>
                </table>
            </div>            
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
        
        </div>
    </div>
</div>

<!-- Modal qty masuk-->
<div id="detailModal_masuk" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Detail QTY Masuk</h4>
        </div>
        
        <div class="modal-body" id="modal-content_masuk">
            <div class="table-responsive">
                <table class="table table-bordered" id="detailmasukTable">
                <p>Loading data...</p>
                </table>
            </div>            
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
        
        </div>
    </div>
</div>

</html>
<script>
  $(document).ready(function () {
    $('#Table-obat').DataTable({
      ordering: false,
      pageLength: 25,
      responsive: true,
      language: {
        searchPlaceholder: "Search..."
      }
    });
  });

  $(document).on('click', '.open-detail', function() {
        var code1 = $(this).data('code1');
        var code2 = $(this).data('code2');
        var code3 = $(this).data('code3');
        var tgl1 = $(this).data('tgl1');
        var tgl2 = $(this).data('tgl2');
        var warehouse = $(this).data('warehouse');

        $('#modal-content_pakai').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/Pemakaian_obat_detail.php',
        type: 'POST',
        data: { code1: code1, code2: code2, code3: code3, tgl1: tgl1, tgl2: tgl2, warehouse: warehouse },
        success: function(response) {
            console.log('Response received');
            $('#modal-content_pakai').html(response);

            if ($.fn.DataTable.isDataTable('#detailPakaiTabel')) {
                console.log('Destroying existing DataTable');
                $('#detailPakaiTabel').DataTable().destroy();
            }
            console.log('Initializing DataTable');
            $('#detailPakaiTabel').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [[0, 'asc']]
            });
        },
        error: function() {
            $('#modal-content_pakai').html('<p class="text-danger">Gagal memuat data.</p>');
        }
        });
    });

  $(document).on('click', '.open-detail1', function() {
        var code = $(this).data('code');
        var tgl1 = $(this).data('tgl1');
        var tgl2 = $(this).data('tgl2');
        var warehouse = $(this).data('warehouse');

        $('#modal-content').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/Balance_stock_obat_detail.php',
        type: 'POST',
        data: { code: code, tgl1: tgl1, tgl2: tgl2, warehouse: warehouse },
        success: function(response) {
            console.log('Response received');
            $('#modal-content').html(response);

            if ($.fn.DataTable.isDataTable('#detailbalanceTable')) {
                console.log('Destroying existing DataTable');
                $('#detailbalanceTable').DataTable().destroy();
            }
            console.log('Initializing DataTable');
            $('#detailbalanceTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [[0, 'asc']]
            });
        },
        error: function() {
            $('#modal-content').html('<p class="text-danger">Gagal memuat data.</p>');
        }
        });
    });

    $(document).on('click', '.open-detail2', function() {
        var code1 = $(this).data('code1');
        var code2 = $(this).data('code2');
        var code3 = $(this).data('code3');
        var tgl1 = $(this).data('tgl1');
        var tgl2 = $(this).data('tgl2');
        var warehouse = $(this).data('warehouse');

        $('#modal-content_masuk').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/masuk_obat_detail_kategori.php',
        type: 'POST',
        data: { code1: code1, code2: code2, code3: code3, tgl1: tgl1, tgl2: tgl2, warehouse: warehouse },
        success: function(response) {
            console.log('Response received');
            $('#modal-content_masuk').html(response);

            if ($.fn.DataTable.isDataTable('#detailmasukTable')) {
                console.log('Destroying existing DataTable');
                $('#detailmasukTable').DataTable().destroy();
            }
            console.log('Initializing DataTable');
            $('#detailmasukTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [[0, 'asc']]
            });
        },
        error: function() {
            $('#modal-content_masuk').html('<p class="text-danger">Gagal memuat data.</p>');
        }
        });
    });

</script>