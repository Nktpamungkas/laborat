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
    <title>LAB - laporan Pemakaian Obat Gd. Kimia</title>
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
                                    required>
                            </div>
                            <div class="col-sm-2" style="display: flex; gap: 10px;">
                                <input type="date" class="form-control" required
                                    placeholder="Tanggal Akhir" name="tgl2"
                                    value="<?php if (isset($_POST['submit'])) {
                                        echo $_POST['tgl2'];
                                    } ?>"
                                    required>
                                <input name="time2" type="text" class="form-control" id="time2"
                                    placeholder="00:00" pattern="[0-9]{2}:[0-9]{2}$"
                                    title=" e.g 14:25" onkeyup="
                                                        var time = this.value;
                                                        if (time.match(/^\d{2}$/) !== null) {
                                                            this.value = time + ':';
                                                        } else if (time.match(/^\d{2}\:\d{2}$/) !== null) {
                                                            this.value = time + '';
                                                        }" value="<?php if (isset($_POST['submit'])) {
                                                            echo $_POST['time2'];
                                                        } ?>" size="5" maxlength="5"
                                    required>
                            </div>
                            <div class="col-sm-2">
                                <select name="warehouse" class="form-control"
                                        style="width: 100%;" required>
                                        <option value="M510 dan M101">M510 & M101</option>
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
                        $sql2 = "DELETE FROM tb_stock_gd_kimia WHERE IP_ADDRESS = ?";
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
                <div class="box-body">
                    <div class="form-group">
                        <?php
                            if (isset($_POST['warehouse'])) {
                                if ($_POST['warehouse'] == 'M510 dan M101' || $_POST['warehouse'] == 'M510') {
                                    ?>
                            <div class="col-sm-12">
                                                <strong><h4 style="margin-bottom: 0;">Note :</h4></strong>
                                                <h5 style="margin-top: 0;">Data List semua obat yang di Checklist pada Product</h5>
                                            </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-sm-12">
                                        <strong><h4 style="margin-bottom: 0;">Note :</h4></strong>
                                                <h5 style="margin-top: 0;">Data List penggunaan obat yang ada transaksi saja</h5>
                                            </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>


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
                            if ($_POST['warehouse'] == 'M510 dan M101') {
                                $where_warehouse = "IN ('M510', 'M101')";                               
                            } else {
                                $where_warehouse = "in('$_POST[warehouse]')";
                            }                                                      
                        $warehouse1 = $_POST['warehouse'];

                            if ($_POST['warehouse'] == 'M510 dan M101'||$_POST['warehouse'] == 'M510') {
                                $Balance_stock = db2_exec($conn1, "SELECT DISTINCT 
                                            ITEMTYPECODE,
                                            KODE_OBAT,
                                            LONGDESCRIPTION,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            CERTIFICATION,
                                            NOTELAB,
                                            SAFETYSTOCK,
                                            SAFETYSTOCK_CHECK
                                            from
                                            (SELECT 
                                                p.ITEMTYPECODE,
                                                TRIM(p.SUBCODE01) || '-' || TRIM(p.SUBCODE02) || '-' || TRIM(p.SUBCODE03) AS KODE_OBAT, 
                                                p.LONGDESCRIPTION,
                                                p.SUBCODE01 as DECOSUBCODE01,
                                                p.SUBCODE02 as DECOSUBCODE02,
                                                p.SUBCODE03 as DECOSUBCODE03,                                          
                                                CASE 
                                                    WHEN a.VALUESTRING = 1 THEN 'BV'
                                                    WHEN a.VALUESTRING = 2 THEN 'NON BV'
                                                    ELSE ''
                                                END AS CERTIFICATION,
                                                a2.VALUESTRING AS NOTELAB,
                                                d.LOGICALWAREHOUSECODE,
                                                CASE 
                                                    WHEN p.BASEPRIMARYUNITCODE = 't' THEN d.SAFETYSTOCK *1000000
                                                    WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN d.SAFETYSTOCK *1000
                                                    ELSE d.SAFETYSTOCK
                                                END AS SAFETYSTOCK,
                                                CASE 
                                                    WHEN p.BASEPRIMARYUNITCODE = 't' THEN (d.SAFETYSTOCK *1000000)+(d.SAFETYSTOCK *1000000)*0.2 
                                                    WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN (d.SAFETYSTOCK *1000)+(d.SAFETYSTOCK *1000)*0.2
                                                    ELSE d.SAFETYSTOCK+(d.SAFETYSTOCK *0.2)
                                                END AS SAFETYSTOCK_CHECK
                                                FROM 
                                                PRODUCT p    
                                                LEFT JOIN ITEMWAREHOUSELINK d ON 
                                                d.ITEMTYPECODE = p.ITEMTYPECODE 
                                                    AND d.SUBCODE01 = p.SUBCODE01
                                                    AND d.SUBCODE02 = p.SUBCODE02 
                                                    AND d.SUBCODE03 = p.SUBCODE03 
                                                    AND d.SUBCODE04 = p.SUBCODE04 
                                                    AND d.SUBCODE05 = p.SUBCODE05 
                                                    AND d.SUBCODE06 = p.SUBCODE06 
                                                    AND d.SUBCODE07 = p.SUBCODE07 
                                                    AND d.SUBCODE08 = p.SUBCODE08 
                                                    AND d.SUBCODE09 = p.SUBCODE09 
                                                    AND d.SUBCODE10 = p.SUBCODE10 	              
                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME ='Certification'
                                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME ='NoteLab' 
                                                LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = p.ABSUNIQUEID AND a3.FIELDNAME ='ShowChemical'                                			
                                                WHERE 
                                                p.ITEMTYPECODE ='DYC'
                                                AND a3.VALUEBOOLEAN = '1'
                                                AND d.LOGICALWAREHOUSECODE $where_warehouse
                                                -- AND b.DECOSUBCODE01 = 'D' 
                                                -- AND b.DECOSUBCODE02 = '1' 
                                                -- AND b.DECOSUBCODE03 = '020'
                                                )
                                                ORDER BY KODE_OBAT ASC;");
                            } else {
                                $Balance_stock = db2_exec($conn1, " SELECT DISTINCT
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        KODE_OBAT,
                                        LONGDESCRIPTION,
                                        SAFETYSTOCK,
                                        SAFETYSTOCK_CHECK,
                                         CERTIFICATION,
                                                NOTELAB
                                    FROM 
                                    (
                                    SELECT           
                                        s.DECOSUBCODE01,
                                        s.DECOSUBCODE02,
                                        s.DECOSUBCODE03,
                                        TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,                                        
                                        p.LONGDESCRIPTION,
                                        s.TEMPLATECODE,
                                        CASE 
                                                WHEN p.BASEPRIMARYUNITCODE = 't' THEN d.SAFETYSTOCK *1000000
                                                WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN d.SAFETYSTOCK *1000
                                                ELSE d.SAFETYSTOCK
                                            END AS SAFETYSTOCK,
                                            CASE 
                                                WHEN p.BASEPRIMARYUNITCODE = 't' THEN (d.SAFETYSTOCK *1000000)+(d.SAFETYSTOCK *1000000)*0.2 
                                                WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN (d.SAFETYSTOCK *1000)+(d.SAFETYSTOCK *1000)*0.2
                                                ELSE d.SAFETYSTOCK+(d.SAFETYSTOCK *0.2)
                                            END AS SAFETYSTOCK_CHECK,
                                             CASE 
                                                    WHEN a.VALUESTRING = 1 THEN 'BV'
                                                    WHEN a.VALUESTRING = 2 THEN 'NON BV'
                                                    ELSE ''
                                                END CERTIFICATION,
                                                a2.VALUESTRING AS NOTELAB
                                    FROM
                                        STOCKTRANSACTION s
                                    LEFT JOIN PRODUCT p  ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                        AND p.SUBCODE01 = s.DECOSUBCODE01
                                        AND p.SUBCODE02 = s.DECOSUBCODE02
                                        AND p.SUBCODE03 = s.DECOSUBCODE03  
                                    LEFT JOIN ITEMWAREHOUSELINK d ON 
                                        d.ITEMTYPECODE = s.ITEMTYPECODE 
                                        AND d.LOGICALWAREHOUSECODE = s.LOGICALWAREHOUSECODE
                                            AND d.SUBCODE01 = s.DECOSUBCODE01
                                            AND d.SUBCODE02 = s.DECOSUBCODE02 
                                            AND d.SUBCODE03 = s.DECOSUBCODE03 
                                            AND d.SUBCODE04 = s.DECOSUBCODE04 
                                            AND d.SUBCODE05 = s.DECOSUBCODE05 
                                            AND d.SUBCODE06 = s.DECOSUBCODE06 
                                            AND d.SUBCODE07 = s.DECOSUBCODE07 
                                            AND d.SUBCODE08 = s.DECOSUBCODE08 
                                            AND d.SUBCODE09 = s.DECOSUBCODE09 
                                            AND d.SUBCODE10 = s.DECOSUBCODE10
                                    LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = p.ABSUNIQUEID AND a3.FIELDNAME ='ShowChemical'
                                      LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME ='Certification'
                                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME ='NoteLab'    
                                WHERE  
                                    s.ITEMTYPECODE = 'DYC'
                                    AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                    AND (s.DETAILTYPE = 1 OR s.DETAILTYPE = 0)
                                    AND a3.VALUEBOOLEAN = 1
                                    AND s.LOGICALWAREHOUSECODE $where_warehouse
                                    -- AND  s.DECOSUBCODE01 = 'D' 
                                    -- AND  s.DECOSUBCODE02 = '1'
                                    -- AND  s.DECOSUBCODE03  = '020'
                                    -- AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$_POST[tgl] $_POST[time]:00' AND '$_POST[tgl2] $_POST[time2]:00' 
                                    AND (
                                        (s.TRANSACTIONDATE > '$_POST[tgl]' OR (s.TRANSACTIONDATE = '$_POST[tgl]' AND s.TRANSACTIONTIME >= '$_POST[time]:00'))
                                        AND (s.TRANSACTIONDATE < '$_POST[tgl2]' OR (s.TRANSACTIONDATE = '$_POST[tgl2]' AND s.TRANSACTIONTIME <= '$_POST[time2]:00'))
                                    )
                                    )
                                    -- WHERE KODE_OBAT <>  'E-1-000'
                                    ORDER BY KODE_OBAT ASC ");
                            }                          
                                        
                                ?>
                                
                                <thead>
                                    <tr>
                                        <th>Kode Obat</th>
                                        <th>Dyestuff/Chemical</th>
                                        <th>Stock Awal (gr)</th>
                                        <th>Masuk</th>
                                        <th>Total Pemakaian (gr)</th>
                                        <th>Transfer (gr)</th>
                                        <?php if ($_POST['warehouse'] == 'M510 dan M101'): ?>
                                        <th>Total OUT</th>
                                        <?php endif; ?>
                                        <th>Stock Balance</th>
                                        <th>Stock Minimum</th>
                                        <?php
                                        if ($_POST['warehouse'] == 'M510 dan M101') {
                                            echo "<th>Sisa PO</th>";
                                        } elseif ($_POST['warehouse'] == 'M101') {
                                            echo "<th>Sisa PO</th>";
                                        }
                                        ?>
                                        <?php if ($_POST['warehouse'] == 'M510'||$_POST['warehouse'] == 'M101'): ?>
                                        <th>Pemakaian(belum timbang)</th>
                                        <th>Stock Balance(future)</th>
                                        <?php endif; ?>
                                        <?php
                                        if ($_POST['warehouse'] == 'M510 dan M101') {
                                            echo "<th>Status</th>";
                                        } elseif ($_POST['warehouse'] == 'M101') {
                                            echo "<th>Status</th>";
                                        }
                                        ?>
                                        <th>Note</th>
                                        <th>Certification</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php

                                if ($_POST['warehouse'] == 'M510 dan M101') {
                                    $wheretemplate = "WHERE TEMPLATE <> '303'";
                                    $wheretemplate2 = "WHERE TEMPLATE <> '304'";
                                } else {
                                    $wheretemplate = "";
                                    $wheretemplate2 = "";
                                }

                                $insertData = []; // 🔹 tampung semua data di sini
                                
                                $no = 1;
                                while ($row = db2_fetch_assoc($Balance_stock)) {

                                    
                                        $Balance_stock_gd_pisah = db2_exec($conn1, "SELECT 
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
                                            AND LOGICALWAREHOUSECODE $where_warehouse
                                            AND DETAILTYPE = 1
                                            AND DECOSUBCODE01 = '{$row['DECOSUBCODE01']}' 
                                            AND DECOSUBCODE02 = '{$row['DECOSUBCODE02']}' 
                                            AND DECOSUBCODE03 = '{$row['DECOSUBCODE03']}' 
                                        GROUP BY 
                                            ITEMTYPECODE,
                                            b.DECOSUBCODE01,
                                            b.DECOSUBCODE02,
                                            b.DECOSUBCODE03,
                                            b.BASEPRIMARYUNITCODE");

                                        $row_Balance_stock_gd_pisah = db2_fetch_assoc($Balance_stock_gd_pisah);
                                  

                                        $stock_transfer = db2_exec($conn1, "SELECT 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                            SATUAN_TRANSFER
                                            from
                                              (SELECT 
                                            ITEMTYPECODE,
                                            TEMPLATE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            sum(QTY_TRANSFER) AS QTY_TRANSFER,
                                            SATUAN_TRANSFER
                                            from
                                            (SELECT    s.TRANSACTIONDATE,
                                            s.TRANSACTIONNUMBER,
                                            CASE 
                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                ELSE s.TEMPLATECODE
                                            END  as TEMPLATE,
                                            s3.LOGICALWAREHOUSECODE,
                                            s.LOTCODE,
                                            s.ORDERCODE,
                                            s.ORDERLINE, 
                                            s.TEMPLATECODE,
                                            s.ITEMTYPECODE,
                                            s.DECOSUBCODE01,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,
                                            s2.LONGDESCRIPTION, 
                                            u.LONGDESCRIPTION AS DESC_USERGENERIC,
                                            p.LONGDESCRIPTION as NAMA_OBAT,
                                            TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                                ELSE s.USERPRIMARYQUANTITY
                                            END AS QTY_TRANSFER,
                                            CASE 
                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                ELSE s.USERPRIMARYUOMCODE
                                            END AS SATUAN_TRANSFER,
                                            CASE 
                                                WHEN  s.TEMPLATECODE = '201' THEN s2.LONGDESCRIPTION 
                                                WHEN  s.TEMPLATECODE = '203' AND i2.DESTINATIONWAREHOUSECODE = 'M512' THEN 'Transfer ke Finishing'
                                                WHEN  s.TEMPLATECODE = '203' AND i2.DESTINATIONWAREHOUSECODE = 'P101' THEN 'Transfer ke YND'
                                                WHEN  s.TEMPLATECODE = '303' AND s3.LOGICALWAREHOUSECODE = 'M512' THEN 'Transfer ke Finishing'
                                                WHEN  s.TEMPLATECODE = '303' AND s3.LOGICALWAREHOUSECODE = 'P101' THEN 'Transfer ke YND'
                                                WHEN  s3.TEMPLATECODE = '304' THEN 'Transfer ke ' || s3.LOGICALWAREHOUSECODE
                                            END AS KETERANGAN
                                        FROM
                                            STOCKTRANSACTION s
                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                                                AND p.SUBCODE01 = s.DECOSUBCODE01
                                                                AND p.SUBCODE02 = s.DECOSUBCODE02
                                                                AND p.SUBCODE03 = s.DECOSUBCODE03
                                        LEFT JOIN STOCKTRANSACTIONTEMPLATE s2 ON s2.CODE = s.TEMPLATECODE 
                                        LEFT JOIN INTERNALDOCUMENT i ON i.PROVISIONALCODE = s.ORDERCODE
                                        LEFT JOIN ORDERPARTNER o ON o.CUSTOMERSUPPLIERCODE = i.ORDPRNCUSTOMERSUPPLIERCODE
                                        LEFT JOIN LOGICALWAREHOUSE l ON l.CODE = o.CUSTOMERSUPPLIERCODE
                                        LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND NOT s3.LOGICALWAREHOUSECODE IN ('M510','M101') AND s3.DETAILTYPE = 2
                                        LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s3.LOGICALWAREHOUSECODE
                                        LEFT JOIN USERGENERICGROUP u ON u.CODE = s.DECOSUBCODE01 AND u.USERGENERICGROUPTYPECODE ='S09'
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME ='KeteranganDYC'
                                        LEFT JOIN ( SELECT 
                                            i.INTDOCUMENTPROVISIONALCODE,
                                            i.INTDOCPROVISIONALCOUNTERCODE,
                                            i.ORDERTYPE,
                                            i.ORDERLINE ,
                                            i.ITEMTYPEAFICODE,
                                            i.SUBCODE01,
                                            i.SUBCODE02,
                                            i.SUBCODE03,
                                            i.SUBCODE04,
                                            i.SUBCODE05,
                                            i.SUBCODE06,
                                            i.SUBCODE07,
                                            i.SUBCODE08,
                                            i.SUBCODE09,
                                            i.SUBCODE10,
                                            i.ITEMDESCRIPTION,
                                            i.DESTINATIONWAREHOUSECODE,
                                            i.RECEIVINGDATE,
                                            i.WAREHOUSECODE AS WAREHOUSE_ASAL
                                            FROM 
                                            INTERNALDOCUMENTLINE i 
                                            WHERE i.ITEMTYPEAFICODE ='DYC'
                                        ) i2 ON i2.INTDOCUMENTPROVISIONALCODE = s.ORDERCODE AND i2.ORDERLINE = s.ORDERLINE 
                                        AND i2.SUBCODE01 = s.DECOSUBCODE01
                                        AND i2.SUBCODE02 = s.DECOSUBCODE02
                                        AND i2.SUBCODE03 = s.DECOSUBCODE03
                                        WHERE
                                            s.ITEMTYPECODE = 'DYC'
                                            AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                            AND (
                                                (s.TRANSACTIONDATE > '$_POST[tgl]' OR (s.TRANSACTIONDATE = '$_POST[tgl]' AND s.TRANSACTIONTIME >= '$_POST[time]:00'))
                                                AND (s.TRANSACTIONDATE < '$_POST[tgl2]' OR (s.TRANSACTIONDATE = '$_POST[tgl2]' AND s.TRANSACTIONTIME <= '$_POST[time2]:00'))
                                            )
                                            AND s.TEMPLATECODE IN ('201','203','303')
                                            AND s.LOGICALWAREHOUSECODE $where_warehouse
                                            AND s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' 
                                            AND s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' 
                                            AND s.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                            )  AS sub
                                            $wheretemplate
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            TEMPLATE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            SATUAN_TRANSFER) 
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            SATUAN_TRANSFER");                                   
                                    $row_stock_transfer = db2_fetch_assoc($stock_transfer);

                                    $qty_pakai = db2_exec($conn1, "SELECT 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            sum(AKTUAL_QTY_KELUAR) AS AKTUAL_QTY_KELUAR,
                                            SATUAN
                                            FROM 
                                            (SELECT
                                                s.ITEMTYPECODE,
                                                s.DECOSUBCODE01,
                                                s.DECOSUBCODE02,
                                                s.DECOSUBCODE03,
                                                CASE 
                                                    when s.CREATIONUSER = 'azwani.najwa'   AND s.TEMPLATECODE = '098' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05') then 0
                                                    WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                    WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                                    ELSE s.USERPRIMARYQUANTITY
                                                END AS AKTUAL_QTY_KELUAR,
                                                CASE 
                                                    WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                    WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                    ELSE s.USERPRIMARYUOMCODE
                                                END AS SATUAN,
                                                TRIM(s.LOGICALWAREHOUSECODE) || '' || TRIM(s.TEMPLATECODE)  AS  LOGICALTEMPLATE
                                            FROM
                                                STOCKTRANSACTION s
                                            WHERE
                                                s.ITEMTYPECODE = 'DYC'
                                                -- AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                AND (
                                                    (s.TRANSACTIONDATE > '$_POST[tgl]' OR (s.TRANSACTIONDATE = '$_POST[tgl]' AND s.TRANSACTIONTIME >= '$_POST[time]:00'))
                                                    AND (s.TRANSACTIONDATE < '$_POST[tgl2]' OR (s.TRANSACTIONDATE = '$_POST[tgl2]' AND s.TRANSACTIONTIME <= '$_POST[time2]:00'))
                                                )
                                                AND s.TEMPLATECODE IN ('120','098')
                                                and not (s.CREATIONUSER = 'azwani.najwa'   AND s.TEMPLATECODE = '098' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05'))
                                                AND s.LOGICALWAREHOUSECODE  $where_warehouse
                                                and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                                s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                                s.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                                )
                                                WHERE LOGICALTEMPLATE !='M101098'
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            SATUAN");
                                        $row_qty_pakai = db2_fetch_assoc($qty_pakai);

                                        $warehouse = $_POST['warehouse'] ?? '';
                                        $tgl_input1 = $_POST['tgl2']; 
                                        $tgl_filter_masuk = date('Y-m-d', strtotime($tgl_input1 . ' -1 months'));

                                        if ($_POST['warehouse'] == 'M510 dan M101') {
                                            $stock_masuk = db2_exec($conn1, " SELECT
                                            ITEMTYPECODE,
                                                    DECOSUBCODE01,
                                                    DECOSUBCODE02,
                                                    DECOSUBCODE03,
                                                    sum(QTY_MASUK) AS QTY_MASUK,
                                                    SATUAN_MASUK
                                            FROM
                                            (SELECT 
                                                    ITEMTYPECODE,
                                                    TEMPLATE,
                                                    DECOSUBCODE01,
                                                    DECOSUBCODE02,
                                                    DECOSUBCODE03,
                                                    sum(QTY_MASUK) AS QTY_MASUK,
                                                    SATUAN_MASUK
                                                    FROM 
                                                    (SELECT    s.TRANSACTIONDATE,
                                                            s.TRANSACTIONNUMBER,
                                                            CASE 
                                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                                ELSE s.TEMPLATECODE
                                                            END  as TEMPLATE,
                                                            s3.LOGICALWAREHOUSECODE AS terimadarigd,
                                                            s.TEMPLATECODE,
                                                            s.ITEMTYPECODE,
                                                            s.DECOSUBCODE01,
                                                            s.DECOSUBCODE02,
                                                            s.DECOSUBCODE03,
                                                            s2.LONGDESCRIPTION, 
                                                            p.LONGDESCRIPTION as NAMA_OBAT,
                                                            TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03) AS KODE_OBAT,
                                                            CASE 
                                                                when s.CREATIONUSER = 'MT_STI'   AND s.TEMPLATECODE = 'OPN' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05') then 0
                                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000                                                               
                                                                ELSE s.USERPRIMARYQUANTITY
                                                            END AS QTY_MASUK,
                                                            CASE 
                                                                WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                                WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                                ELSE s.USERPRIMARYUOMCODE
                                                            END AS SATUAN_MASUK,
                                                            CASE 
                                                                WHEN  s.TEMPLATECODE = 'OPN' THEN s2.LONGDESCRIPTION 
                                                                WHEN  s.TEMPLATECODE = 'QCT' THEN s.ORDERCODE 
                                                                WHEN  s.TEMPLATECODE = '304' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '303' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '203' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '204' THEN 'Terima dari ' || trim(s3.LOGICALWAREHOUSECODE)
                                                                WHEN  s.TEMPLATECODE = '125' THEN 'Retur dari ' || trim(s.ORDERCODE )
                                                            END AS KETERANGAN                    
                                                        FROM
                                                            STOCKTRANSACTION s
                                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                                                                AND p.SUBCODE01 = s.DECOSUBCODE01
                                                                                AND p.SUBCODE02 = s.DECOSUBCODE02
                                                                                AND p.SUBCODE03 = s.DECOSUBCODE03
                                                        LEFT JOIN STOCKTRANSACTIONTEMPLATE s2 ON s2.CODE = s.TEMPLATECODE 
                                                        LEFT JOIN INTERNALDOCUMENT i ON i.PROVISIONALCODE = s.ORDERCODE
                                                        LEFT JOIN ORDERPARTNER o ON o.CUSTOMERSUPPLIERCODE = i.ORDPRNCUSTOMERSUPPLIERCODE
                                                        LEFT JOIN LOGICALWAREHOUSE l ON l.CODE = o.CUSTOMERSUPPLIERCODE
                                                        LEFT JOIN STOCKTRANSACTION s3 ON s3.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND NOT s3.LOGICALWAREHOUSECODE = 'M101' AND  s3.DETAILTYPE = 1
                                                        LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s3.LOGICALWAREHOUSECODE
                                                        WHERE
                                                            s.ITEMTYPECODE = 'DYC'
                                                            -- AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                            AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                            AND (
                                                                (s.TRANSACTIONDATE > '$_POST[tgl]' OR (s.TRANSACTIONDATE = '$_POST[tgl]' AND s.TRANSACTIONTIME >= '$_POST[time]:00'))
                                                                AND (s.TRANSACTIONDATE < '$_POST[tgl2]' OR (s.TRANSACTIONDATE = '$_POST[tgl2]' AND s.TRANSACTIONTIME <= '$_POST[time2]:00'))
                                                            )
                                                            AND s.TEMPLATECODE IN ('QCT','304','OPN','204','125')
                                                            AND NOT COALESCE(TRIM( CASE 
                                                                WHEN s3.TEMPLATECODE IS NOT NULL THEN s3.TEMPLATECODE
                                                                ELSE s.TEMPLATECODE
                                                            END), '') || COALESCE(TRIM(CASE 
                                                                WHEN  s3.LOGICALWAREHOUSECODE IS NOT NULL THEN  s3.LOGICALWAREHOUSECODE
                                                                ELSE  s.LOGICALWAREHOUSECODE
                                                            END), '')  IN ('OPNM101','303M101','304M510')
                                                            AND s.LOGICALWAREHOUSECODE $where_warehouse
                                                            -- and s.CREATIONUSER != 'MT_STI'
                                                            AND s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' 
                                                            AND s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' 
                                                            AND s.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                                            )  AS sub
                                                            WHERE TEMPLATE <> '304'
                                                            GROUP BY 
                                                            ITEMTYPECODE,
                                                            TEMPLATE,
                                                            DECOSUBCODE01,
                                                            DECOSUBCODE02,
                                                            DECOSUBCODE03,
                                                            SATUAN_MASUK)
                                                             GROUP BY 
                                                            ITEMTYPECODE,
                                                            DECOSUBCODE01,
                                                            DECOSUBCODE02,
                                                            DECOSUBCODE03,
                                                            SATUAN_MASUK");
                                        } else {
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
                                                    when s.CREATIONUSER = 'MT_STI' AND s.TEMPLATECODE = 'OPN' and (s.TRANSACTIONDATE ='2025-07-13' or s.TRANSACTIONDATE ='2025-10-05') then 0
                                                    WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                                    WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000                                                    
                                                    ELSE s.USERPRIMARYQUANTITY
                                                END AS QTY_MASUK,
                                                CASE 
                                                    WHEN s.USERPRIMARYUOMCODE = 't' THEN 'g'
                                                    WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                                    ELSE s.USERPRIMARYUOMCODEz
                                                END AS SATUAN_MASUK
                                            FROM
                                                STOCKTRANSACTION s
                                            WHERE
                                                s.ITEMTYPECODE = 'DYC'
                                                -- AND s.TRANSACTIONDATE BETWEEN '$tgl_filter_masuk' AND '$_POST[tgl2]'
                                                AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                AND (
                                                    (s.TRANSACTIONDATE > '$_POST[tgl]' OR (s.TRANSACTIONDATE = '$_POST[tgl]' AND s.TRANSACTIONTIME >= '$_POST[time]:00'))
                                                    AND (s.TRANSACTIONDATE < '$_POST[tgl2]' OR (s.TRANSACTIONDATE = '$_POST[tgl2]' AND s.TRANSACTIONTIME <= '$_POST[time2]:00'))
                                                )
                                                AND s.TEMPLATECODE IN ('QCT','304','OPN','204','125')
                                                -- and s.CREATIONUSER != 'MT_STI'
                                                AND s.LOGICALWAREHOUSECODE  $where_warehouse
                                                and s.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                                s.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                                s.DECOSUBCODE03 = '$row[DECOSUBCODE03]')
                                            GROUP BY 
                                            ITEMTYPECODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            SATUAN_MASUK");
                                    }
                                    $row_stock_masuk = db2_fetch_assoc($stock_masuk);

                                    $tgl_input = $_POST['tgl']; // Misal: 2025-08-15
                                    $tgl_sebelumnya = date('Y-m-01', strtotime('-6 months', strtotime($tgl_input)));
                                    
                                    $buka_po = db2_exec($conn1, "SELECT 
                                            COUNTERCODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            DECOSUBCODE04,
                                            DECOSUBCODE05,
                                            sum(QTY) AS QTY,
                                            BASEPRIMARYUNITCODE
                                            from
                                            (SELECT 
                                            v.ISTANCECODE,
                                            v.COUNTERCODE,
                                            v.DECOSUBCODE01,
                                            v.DECOSUBCODE02,
                                            v.DECOSUBCODE03,
                                            v.DECOSUBCODE04,
                                            v.DECOSUBCODE05,
                                            v.DECOSUBCODE06,
                                            v.DECOSUBCODE07,
                                            CASE 
                                                WHEN v.BASEPRIMARYUNITCODE = 'kg' THEN sum(v.BASEPRIMARYQUANTITY)*1000
                                                WHEN v.BASEPRIMARYUNITCODE = 't' THEN sum(v.BASEPRIMARYQUANTITY)*1000000
                                                else sum(v.BASEPRIMARYQUANTITY)
                                            END AS QTY,
                                            CASE 
                                                WHEN v.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                                WHEN v.BASEPRIMARYUNITCODE = 't' THEN 'g'
                                                else v.BASEPRIMARYUNITCODE
                                            END AS BASEPRIMARYUNITCODE,
                                            date(p.CREATIONDATETIME) AS PO_DATE,
                                            p.CREATIONDATETIME 
                                            FROM 
                                            VIEWAVANALYSISPART1 v 
                                            LEFT JOIN PURCHASEORDERLINE p ON p.PURCHASEORDERCODE = v.ISTANCECODE AND p.ORDERLINE = v.ISTANCELINE 
                                            AND p.SUBCODE01 = v.DECOSUBCODE01 
                                            AND p.SUBCODE02 = v.DECOSUBCODE02 
                                            AND p.SUBCODE03 = v.DECOSUBCODE03 
                                            WHERE 
                                            v.ISTANCETYPE = '6'
                                             AND v.LOGICALWAREHOUSECODE $where_warehouse
                                            AND date(p.CREATIONDATETIME) BETWEEN '$tgl_sebelumnya' AND '$_POST[tgl2]'
                                            and v.DECOSUBCODE01 = '$row[DECOSUBCODE01]' AND
                                            v.DECOSUBCODE02 = '$row[DECOSUBCODE02]' AND
                                            v.DECOSUBCODE03 = '$row[DECOSUBCODE03]'
                                            GROUP BY 
                                            v.ISTANCECODE,
                                            v.COUNTERCODE,
                                            v.DECOSUBCODE01,
                                            v.DECOSUBCODE02,
                                            v.DECOSUBCODE03,
                                            v.DECOSUBCODE04,
                                            v.DECOSUBCODE05,
                                            v.DECOSUBCODE06,
                                            v.DECOSUBCODE07,
                                            v.BASEPRIMARYUNITCODE,
                                            p.CREATIONDATETIME)
                                            GROUP BY 
                                            COUNTERCODE,
                                            DECOSUBCODE01,
                                            DECOSUBCODE02,
                                            DECOSUBCODE03,
                                            DECOSUBCODE04,
                                            DECOSUBCODE05,
                                            BASEPRIMARYUNITCODE");
                                    $row_buka_po = db2_fetch_assoc($buka_po);

                                    $pakai_belum_timbang = db2_exec($conn1, "SELECT 
                                                                                LOGICALWAREHOUSECODE,
                                                                                COUNTERCODE,
                                                                                ITEMTYPECODE,
                                                                                DECOSUBCODE01,
                                                                                DECOSUBCODE02,
                                                                                DECOSUBCODE03,
                                                                               STATUS,
                                                                                sum(BASEPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
                                                                                BASEPRIMARYUNITCODE
                                                                            FROM 
                                                                                (
                                                                                SELECT 
                                                                                    v.WAREHOUSECODE AS LOGICALWAREHOUSECODE,
                                                                                    p.PRODUCTIONORDERCOUNTERCODE AS COUNTERCODE,
                                                                                    v.PRODUCTIONORDERCODE AS ISTANCECODE,
                                                                                    v.ITEMTYPEAFICODE AS ITEMTYPECODE,
                                                                                    v.ISSUEDATE AS DUEDATE,
                                                                                    v.SUBCODE01 AS DECOSUBCODE01,
                                                                                    v.SUBCODE02 AS DECOSUBCODE02,
                                                                                    v.SUBCODE03 AS DECOSUBCODE03,
                                                                            	    v.PROGRESSSTATUS as STATUS,
                                                                                    p.STATUS AS PRODUCTIONORDER_STATUS,
                                                                                    CASE 
                                                                                        WHEN v.BASEPRIMARYUOMCODE ='kg' THEN v.BASEPRIMARYQUANTITY * 1000
                                                                                        WHEN v.BASEPRIMARYUOMCODE ='t' THEN v.BASEPRIMARYQUANTITY * 1000000
                                                                                        ELSE BASEPRIMARYQUANTITY
                                                                                    END AS BASEPRIMARYQUANTITY,
                                                                                    v.BASEPRIMARYUOMCODE AS BASEPRIMARYUNITCODE
                                                                                    FROM 
                                                                                        PRODUCTIONRESERVATION v 
                                                                                    LEFT JOIN PRODUCTIONORDER p ON p.CODE = v.PRODUCTIONORDERCODE 
                                                                                    WHERE 
                                                                                        v.ITEMTYPEAFICODE ='DYC'
                                                                            		    AND v.PROGRESSSTATUS = 0
                                                                                        AND p.STATUS = 0
                                                                                        AND p.PRODUCTIONORDERCOUNTERCODE = '640'
                                                                                        AND v.WAREHOUSECODE  = '$_POST[warehouse]'
                                                                                        AND v.SUBCODE01 = '$row[DECOSUBCODE01]' 
                                                                                        AND v.SUBCODE02 = '$row[DECOSUBCODE02]' 
                                                                                        AND v.SUBCODE03 = '$row[DECOSUBCODE03]' 
                                                                                        AND v.ISSUEDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]')
                                                                                GROUP BY 
                                                                                    LOGICALWAREHOUSECODE,
                                                                                    COUNTERCODE,
                                                                            	    STATUS,
                                                                                    ITEMTYPECODE,
                                                                                    DECOSUBCODE01,
                                                                                    DECOSUBCODE02,
                                                                                    DECOSUBCODE03,
                                                                                    BASEPRIMARYUNITCODE");
                                    $row_pakai_belum_timbang = db2_fetch_assoc($pakai_belum_timbang);


                                    $code = $row['KODE_OBAT'];
                                    $tgl1 = $_POST['tgl'];
                                    $tgl2 = $_POST['tgl2'];
                                    $time = $_POST['time'];
                                    $time2 = $_POST['time2'];
                                    $warehouse =  $where_warehouse;
                                    $code1 = $row['DECOSUBCODE01'];
                                    $code2 = $row['DECOSUBCODE02'];
                                    $code3 = $row['DECOSUBCODE03'];
                                    
                                    $tahunBulan = date('Y-m', strtotime($tgl1));
                                    $kode_obat = $row['KODE_OBAT'];

                                    $date = new DateTime($tgl1);
                                    $date->modify('-1 month');
                                    $tahunBulan2 = $date->format('Y-m');
                                    $tgl_kurang_satu = date('Y-m-d', strtotime($tgl1 . ' -1 day'));
                                    // echo $time;
                                    // echo $tahunBulan2;

                                    if($tahunBulan2 == '2025-08') {
                                        $q_qty_awal = mysqli_query($con, "SELECT kode_obat,
                                        SUBCODE01,
                                        SUBCODE02,
                                        SUBCODE03,
                                        SUM(qty_awal) as qty_awal 
                                        FROM stock_awal_obat_gdkimia_1
                                        WHERE kode_obat = '$kode_obat'
                                        AND logicalwarehouse  $where_warehouse
                                        group by 
                                        kode_obat,
                                        SUBCODE01,
                                        SUBCODE02,
                                        SUBCODE03  
                                        ORDER BY kode_obat ASC");
                                    }else{
                                        $q_qty_awal = mysqli_query($con, "SELECT 
                                        tgl_tutup,
                                        DATE_FORMAT(DATE_SUB(tgl_tutup, INTERVAL 1 MONTH), '%Y-%m') AS tahun_bulan,
                                        KODE_OBAT,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        SUM(BASEPRIMARYQUANTITYUNIT*1000) AS qty_awal
                                    FROM                                                      
                                     (SELECT distinct 
                                        tgl_tutup,
                                        DATE_FORMAT(DATE_SUB(tgl_tutup, INTERVAL 1 MONTH), '%Y-%m') AS tahun_bulan,
                                        KODE_OBAT,
                                        LONGDESCRIPTION,
                                        DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        LOGICALWAREHOUSECODE,
                                        WAREHOUSELOCATIONCODE,
                                        WHSLOCATIONWAREHOUSEZONECODE,
                                        LOTCODE,
                                        BASEPRIMARYQUANTITYUNIT
                                    FROM tblopname_11 t
                                    WHERE 
                                        KODE_OBAT = '$kode_obat'
                                        AND LOGICALWAREHOUSECODE  $where_warehouse
                                        AND tgl_tutup = (
                                            SELECT MAX(tgl_tutup)
                                            FROM tblopname_11
                                            WHERE 
                                                KODE_OBAT = '$kode_obat'
                                                AND LOGICALWAREHOUSECODE  $where_warehouse
                                                AND tgl_tutup = '$tgl1'
                                        )) AS SUB
                                    GROUP BY tgl_tutup, KODE_OBAT");                                        
                                    }                                

                                    $row_qty_awal = mysqli_fetch_array($q_qty_awal);
                                    // var_dump($row_qty_awal);

                                    $qty_masuk = (substr(number_format($row_stock_masuk['QTY_MASUK'], 2), -3) == '.00')
                                        ? number_format($row_stock_masuk['QTY_MASUK'], 0)
                                        : number_format($row_stock_masuk['QTY_MASUK'], 2);

                                    $qty_Keluar = (substr(number_format($row_qty_pakai['AKTUAL_QTY_KELUAR'], 2), -3) == '.00')
                                        ? number_format($row_qty_pakai['AKTUAL_QTY_KELUAR'], 0)
                                        : number_format($row_qty_pakai['AKTUAL_QTY_KELUAR'], 2);

                                    $qty_Transfer = (substr(number_format($row_stock_transfer['QTY_TRANSFER'], 2), -3) == '.00')
                                        ? number_format($row_stock_transfer['QTY_TRANSFER'], 0)
                                        : number_format($row_stock_transfer['QTY_TRANSFER'], 2);

                                    $qty_awal = (substr(number_format($row_qty_awal['qty_awal'], 2), -3) == '.00')
                                        ? number_format($row_qty_awal['qty_awal'], 0)
                                        : number_format($row_qty_awal['qty_awal'], 2);

                                    $qty_stock_balance = (substr(number_format($row['STOCK_BALANCE'], 2), -3) == '.00')
                                        ? number_format($row['STOCK_BALANCE'], 0)
                                        : number_format($row['STOCK_BALANCE'], 2);

                                    $qty_Balance_stock_gd_pisah = (substr(number_format($row_Balance_stock_gd_pisah['STOCK_BALANCE'], 2), -3) == '.00')
                                        ? number_format($row_Balance_stock_gd_pisah['STOCK_BALANCE'], 0)
                                        : number_format($row_Balance_stock_gd_pisah['STOCK_BALANCE'], 2);

                                    $qty_stock_minimum = (substr(number_format($row['SAFETYSTOCK'], 2), -3) == '.00')
                                        ? number_format($row['SAFETYSTOCK'], 0)
                                        : number_format($row['SAFETYSTOCK'], 2);

                                    $qty_stock_buka_PO = (substr(number_format($row_buka_po['QTY'], 2), -3) == '.00')
                                        ? number_format($row_buka_po['QTY'], 0)
                                        : number_format($row_buka_po['QTY'], 2);
                                        
                                    $qty_stock_pakai_belum_timbang = (substr(number_format($row_pakai_belum_timbang['USERPRIMARYQUANTITY'], 2), -3) == '.00')
                                        ? number_format($row_pakai_belum_timbang['USERPRIMARYQUANTITY'], 0)
                                        : number_format($row_pakai_belum_timbang['USERPRIMARYQUANTITY'], 2);
                                        
                                    $sisa_stock = ($row_qty_awal['qty_awal'] + $row['QTY_MASUK']) - $row['AKTUAL_QTY_KELUAR'];

                                    $qty_balance_ = (float) $row['STOCK_BALANCE'];
                                    $qty_Balance_stock_gd_pisah_ = (float) $row_Balance_stock_gd_pisah['STOCK_BALANCE'];
                                    $buka_po_qty_ = isset($row_buka_po['QTY']) ? (float) $row_buka_po['QTY'] : 0;
                                    $pakai_belum_timbang_ = isset($row_pakai_belum_timbang['USERPRIMARYQUANTITY']) ? (float) $row_pakai_belum_timbang['USERPRIMARYQUANTITY'] : 0;
                                    $savetystock_ = isset($row['SAFETYSTOCK']) ? (float) $row['SAFETYSTOCK'] : 0;
                                    $SAFETYSTOCK_CHECK = isset($row['SAFETYSTOCK_CHECK']) ? (float) $row['SAFETYSTOCK_CHECK'] : 0;
                                    
                                    $pakai_ = isset($row_qty_pakai['AKTUAL_QTY_KELUAR']) ? (float) $row_qty_pakai['AKTUAL_QTY_KELUAR'] : 0;
                                    $transfer_ = isset($row_stock_transfer['QTY_TRANSFER']) ? (float) $row_stock_transfer['QTY_TRANSFER'] : 0;

                                    if ($warehouse1 == "M510") {
                                        $stock_balance_future_ = (((float) $row_Balance_stock_gd_pisah['STOCK_BALANCE']) - (isset($row_pakai_belum_timbang['USERPRIMARYQUANTITY']) ? (float) $row_pakai_belum_timbang['USERPRIMARYQUANTITY'] : 0));
                                    } elseif ($warehouse1 == "M101") {
                                        $stock_balance_future_ = (((float) $row_Balance_stock_gd_pisah['STOCK_BALANCE']) + (isset($row_buka_po['QTY']) ? (float) $row_buka_po['QTY'] : 0)) - (isset($row_pakai_belum_timbang['USERPRIMARYQUANTITY']) ? (float) $row_pakai_belum_timbang['USERPRIMARYQUANTITY'] : 0);
                                    }                                    

                                    $sisa_stock_balance_future = (substr(number_format($stock_balance_future_, 2), -3) == '.00')
                                        ? number_format($stock_balance_future_, 0)
                                        : number_format($stock_balance_future_, 2);

                                    $total_out = ($pakai_ + $transfer_);
                                    $qty_total_out = (substr(number_format($total_out, 2), -3) == '.00')
                                        ? number_format($total_out, 0)
                                        : number_format($total_out, 2);

                                if ($warehouse1 == "M510" || $_POST['warehouse'] == 'M510 dan M101' ) {
                                    $totalTY_ = $qty_Balance_stock_gd_pisah_ + $buka_po_qty_;
                                    $status = ($totalTY_ < $savetystock_)
                                        ? 'SEGERA ORDER'
                                        : (($totalTY_ >= $savetystock_ && $totalTY_ < $SAFETYSTOCK_CHECK)
                                            ? 'HITUNG KEBUTUHAN ORDER'
                                            : '');
                                    $style = '';
                                } elseif ($warehouse1 == "M101") {
                                        $totalTY_ = $qty_Balance_stock_gd_pisah_ + $buka_po_qty_;
                                        $status = ($totalTY_ < $savetystock_)
                                            ? 'SEGERA ORDER'
                                            : (($totalTY_ >= $savetystock_ && $totalTY_ < $SAFETYSTOCK_CHECK)
                                                ? 'HITUNG KEBUTUHAN ORDER'
                                                : '');
                                        $style = '';
                                }

                                    if ($status == 'SEGERA ORDER') {
                                        $style = 'background-color: #f44336; color: white; font-weight: bold;'; // merah cerah + teks putih
                                    } elseif ($status == 'HITUNG KEBUTUHAN ORDER') {
                                        $style = 'background-color: #fff176; color: black; font-weight: bold;'; // kuning terang + teks hitam
                                    }
                                    
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
                                            data-tgl_filter_masuk="<?= $tgl_filter_masuk ?>" 
                                            data-tgl2="<?= $tgl2 ?>" 
                                            data-time="<?= $time ?>" 
                                            data-time2="<?= $time2 ?>" 
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
                                            data-time="<?= $time ?>" 
                                            data-time2="<?= $time2 ?>" 
                                            data-warehouse="<?= $warehouse ?>" 
                                            data-toggle="modal" data-target="#detailModal_pakai">
                                            <?= $qty_Keluar ?>
                                            </a>
                                        </td>                                                                             
                                        <td>
                                        <a width = "100%" href="#" class="btn btn-primary btn-sm btn-fixed open-detail1" 
                                            data-code1="<?= $code1 ?>" 
                                            data-code2="<?= $code2 ?>"                                                
                                            data-code3="<?= $code3 ?>"  
                                            data-tgl1="<?= $tgl1 ?>" 
                                            data-tgl2="<?= $tgl2 ?>"
                                            data-time="<?= $time ?>" 
                                            data-time2="<?= $time2 ?>" 
                                            data-warehouse = "<?= $warehouse ?>"
                                            data-toggle="modal"
                                            data-target="#detailModal_transfer">
                                            <?= $qty_Transfer ?>
                                            </a>
                                        </td>
                                        <?php if ($_POST['warehouse'] == 'M510 dan M101'): ?>
                                        <td><?php echo $qty_total_out ?></td>
                                        <?php endif; ?>
                                        <td><?php echo $qty_Balance_stock_gd_pisah; ?></td>
                                        <td><?php echo $qty_stock_minimum ?></td>
                                        <?php
                                            if ($_POST['warehouse'] == 'M510 dan M101') {
                                                echo '<td>
                                                        <a width="100%" href="#" class="btn btn-primary btn-sm btn-fixed open-detail3" 
                                                            data-code1="' . $code1 . '"
                                                            data-code2="' . $code2 . '" 
                                                            data-code3="' . $code3 . '" 
                                                            data-tgl_sebelumnya="' . $tgl_sebelumnya . '" 
                                                            data-tgl2="' . $tgl2 . '"
                                                            data-warehouse="' . $warehouse . '" 
                                                            data-toggle="modal" data-target="#detailModal_sisaPo">'
                                                                                                . $qty_stock_buka_PO .
                                                                                                '</a>
                                                    </td>';
                                            } elseif ($_POST['warehouse'] == 'M101') {
                                                echo '<td>
                                                        <a width="100%" href="#" class="btn btn-primary btn-sm btn-fixed open-detail3" 
                                                            data-code1="' . $code1 . '"
                                                            data-code2="' . $code2 . '" 
                                                            data-code3="' . $code3 . '" 
                                                            data-tgl_sebelumnya="' . $tgl_sebelumnya . '" 
                                                            data-tgl2="' . $tgl2 . '"
                                                            data-warehouse="' . $warehouse . '" 
                                                            data-toggle="modal" data-target="#detailModal_sisaPo">'
                                                . $qty_stock_buka_PO .
                                                '</a>
                                                    </td>';
                                            }
                                        ?>                                        
                                        <?php if ($_POST['warehouse'] == 'M510'||$_POST['warehouse'] == 'M101'): ?>
                                            <td>
                                            <a width = "100%" href="#" class="btn btn-primary btn-sm btn-fixed open-detail4" 
                                            data-code1="<?= $code1 ?>"
                                                    data-code2="<?= $code2 ?>" 
                                                    data-code3="<?= $code3 ?>" 
                                                    data-tgl1="<?= $tgl1 ?>"
                                                    data-tgl2="<?= $tgl2 ?>" 
                                                    data-time="<?= $time ?>"
                                                    data-time2="<?= $time2 ?>" 
                                                    data-warehouse="<?= $warehouse ?>" 
                                                    data-toggle="modal"
                                                    data-target="#detailModal_qty_blm_timbang">
                                                    <?= $qty_stock_pakai_belum_timbang ?>
                                                </a>
                                            </td>
                                        <td><?php echo $sisa_stock_balance_future ?></td>
                                        <?php endif; ?>
                                        <?php if ($_POST['warehouse'] == 'M510 dan M101'||$_POST['warehouse'] == 'M101'): ?>
                                        <td style="<?= $style ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </td>
                                        <?php endif; ?>
                                        <td><?php echo  $row['NOTELAB']?></td>
                                        <td><?php echo  $row['CERTIFICATION']?></td>
                                </tr>                                   
                                <?php
                                    $no++;

                                    $ipaddress = $_SERVER['REMOTE_ADDR'];
                                    $today = date('Y-m-d');

                                    include_once("koneksi.php");

                                // Escape & convert
                                $kode_obat = mysqli_real_escape_string($con, $row['KODE_OBAT']);
                                $nama_obat = mysqli_real_escape_string($con, $row['LONGDESCRIPTION']);
                                $qty_awal = floatval(str_replace(',', '', $qty_awal));
                                $stock_masuk = floatval(str_replace(',', '', $qty_masuk));
                                $stock_keluar = floatval(str_replace(',', '', $qty_Keluar));
                                $stock_transfer = floatval(str_replace(',', '', $qty_Transfer));
                                $stock_balance = floatval(str_replace(',', '', $qty_Balance_stock_gd_pisah));
                                $stock_minimum = floatval(str_replace(',', '', $qty_stock_minimum));
                                $buka_po = floatval(str_replace(',', '', $qty_stock_buka_PO));
                                $pakai_belum_timbang = floatval(str_replace(',', '', $qty_stock_pakai_belum_timbang));
                                $balance_future = floatval(str_replace(',', '', $sisa_stock_balance_future));
                                $total_out_ = floatval(str_replace(',', '', $qty_total_out));
                                $status_ = mysqli_real_escape_string($con, $status);
                                $note = mysqli_real_escape_string($con, $row['NOTELAB']);
                                $sertifikat = mysqli_real_escape_string($con, $row['CERTIFICATION']);
                                $ip = mysqli_real_escape_string($con, $ipaddress);
                                $warehouse = mysqli_real_escape_string($con, $where_warehouse);

                                // Simpan dalam array (bukan langsung insert)
                                $insertData[] = "(
                                '$kode_obat',
                                '$nama_obat',
                                $qty_awal,
                                $stock_masuk,
                                $stock_keluar,
                                $stock_transfer,
                                $stock_balance,
                                $stock_minimum,
                                $buka_po,
                                $pakai_belum_timbang,
                                $balance_future,
                                $total_out_,
                                '$status_',
                                '$note',
                                '$sertifikat',
                                '$today',
                                '$ip',
                                '$warehouse'
                            )";

                                $no++;
                                }

                                // =========================================================
                                // Setelah semua data selesai diproses, lakukan INSERT SEKALI SAJA
                                // =========================================================
                                            if (!empty($insertData)) {
                                                $sql = "INSERT INTO tb_stock_gd_kimia (
                                                kode_obat,
                                                nama_obat,
                                                qty_awal,
                                                stock_masuk,
                                                stock_keluar,
                                                stock_transfer,
                                                stock_balance,
                                                stock_minimum,
                                                buka_po,
                                                stock_pakai_blum_timbang,
                                                stock_balance_future,
                                                total_out,
                                                status_,
                                                note,
                                                ket_sertifikat,
                                                tgl_tarik_data,
                                                ip_address,
                                                logicalwarehouse
                                            ) VALUES " . implode(',', $insertData);

                                    $result = mysqli_query($con, $sql);

                                    if (!$result) {
                                        die("❌ Error executing bulk insert: " . mysqli_error($con));
                                    } else {
                                        echo "<script>console.log('✅ Bulk insert berhasil. Jumlah data: " . count($insertData) . "');</script>";
                                    }
                                } else {
                                    echo "<script>console.log('⚠️ Tidak ada data untuk disimpan');</script>";
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
<div id="detailModal_pakai" class="modal fade" tabindex="-1" role="dialog">
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

<!-- Modal qty sisa PO -->
<div id="detailModal_sisaPo" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail QTY Sisa PO</h4>
            </div>
            
            <div class="modal-body" id="modal-content_sisaPO">
                <p>Loading data...</p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        
        </div>
    </div>
</div>

<!-- Modal qty belumtimbang-->
<div id="detailModal_qty_blm_timbang" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail QTY Belum Timbang</h4>
            </div>
            
            <div class="modal-body" id="modal-content_qty_blm_timbang">
                <p>Loading data...</p>
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
        var tgl_filter_masuk = $(this).data('tgl_filter_masuk');
        var time = $(this).data('time');
        var time2 = $(this).data('time2');
        var warehouse = $(this).data('warehouse');

        $('#modal-content_pakai').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/Pemakaian_obat_detail.php',
        type: 'POST',
        data: { code1: code1, code2: code2, code3: code3, tgl1: tgl1, tgl2: tgl2,tgl_filter_masuk:tgl_filter_masuk,time:time, time2:time2,  warehouse: warehouse },
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
        var code1 = $(this).data('code1');
        var code2 = $(this).data('code2');
        var code3 = $(this).data('code3');
        var tgl1 = $(this).data('tgl1');
        var tgl2 = $(this).data('tgl2');
        var time = $(this).data('time');
        var time2 = $(this).data('time2');
        var warehouse = $(this).data('warehouse');

        $('#modal-content').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/transfer_obat_detail.php',
        type: 'POST',
        data: { code1: code1, code2: code2, code3: code3, tgl1: tgl1, tgl2: tgl2, time: time, time2: time2, warehouse: warehouse },
        success: function(response) {
            console.log('Response received');
            $('#modal-content').html(response);

            if ($.fn.DataTable.isDataTable('#detailTransferTable')) {
                console.log('Destroying existing DataTable');
                $('#detailTransferTable').DataTable().destroy();
            }
            console.log('Initializing DataTable');
            $('#detailTransferTable').DataTable({
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
        var tgl_filter_masuk = $(this).data('tgl_filter_masuk');
        var time = $(this).data('time');
        var time2 = $(this).data('time2');
        var warehouse = $(this).data('warehouse');

        $('#modal-content_masuk').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/Masuk_obat_detail.php',
        type: 'POST',
        data: { code1: code1, code2: code2, code3: code3, tgl1: tgl1, tgl2: tgl2,tgl_filter_masuk: tgl_filter_masuk, time: time, time2: time2, warehouse: warehouse },
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
    


$(document).on('click', '.open-detail3', function() {
    var code1 = $(this).data('code1');
    var code2 = $(this).data('code2');
    var code3 = $(this).data('code3');
    var tgl_sebelumnya = $(this).data('tgl_sebelumnya'); // sudah sesuai atribut HTML
    var tgl2 = $(this).data('tgl2');
    var warehouse = $(this).data('warehouse');

    console.log('Kirim data ke AJAX:', { code1, code2, code3, tgl_sebelumnya, tgl2, warehouse });

    $('#modal-content_sisaPO').html('<p>Loading data...</p>');

    $.ajax({
        url: 'pages/ajax/Sisa_PO_obat_detail.php',
        type: 'POST',
        data: { code1, code2, code3, tgl_sebelumnya, tgl2, warehouse },
        success: function(response) {
            console.log('Response dari server:', response);
            $('#modal-content_sisaPO').html(response);

            // Pastikan DataTable diinisialisasi setelah modal selesai terbuka
            $('#detailModal_sisaPo').off('shown.bs.modal').on('shown.bs.modal', function () {
                if ($.fn.DataTable.isDataTable('#detailsisaPOTable')) {
                    $('#detailsisaPOTable').DataTable().destroy();
                }
                $('#detailsisaPOTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    order: [[0, 'asc']]
                });
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.error('Response Text:', xhr.responseText);
            $('#modal-content_sisaPO').html('<p class="text-danger">Gagal memuat data: ' + error + '</p>');
        }
    });
});

$(document).on('click', '.open-detail4', function() {
    var code1 = $(this).data('code1');
    var code2 = $(this).data('code2');
    var code3 = $(this).data('code3');
    var tgl1 = $(this).data('tgl1');
    var tgl2 = $(this).data('tgl2');
    var time = $(this).data('time');
    var time2 = $(this).data('time2');
    var warehouse = $(this).data('warehouse');

    console.log('Kirim data ke AJAX:', { code1, code2, code3, tgl1, tgl2, time, time2, warehouse });

    $('#modal-content_qty_blm_timbang').html('<p>Loading data...</p>');

    $.ajax({
        url: 'pages/ajax/pakai_obat_belum_timbang_detail.php',
        type: 'POST',
        data: { code1, code2, code3, tgl1, tgl2, time, time2, warehouse },
        success: function(response) {
            console.log('Response dari server:', response);
            $('#modal-content_qty_blm_timbang').html(response);

            // Pastikan DataTable diinisialisasi setelah modal selesai terbuka
            $('#detailModal_qty_blm_timbang').off('shown.bs.modal').on('shown.bs.modal', function () {
                if ($.fn.DataTable.isDataTable('#detailpakaibelumtimbang')) {
                    $('#detailpakaibelumtimbang').DataTable().destroy();
                }
                $('#detailpakaibelumtimbang').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    order: [[0, 'asc']]
                });
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.error('Response Text:', xhr.responseText);
            $('#modal-content_qty_blm_timbang').html('<p class="text-danger">Gagal memuat data: ' + error + '</p>');
        }
    });
});

</script>