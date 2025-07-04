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
                                <!-- <input name="time" type="text" class="form-control" id="time"
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
                                    required> -->
                            </div>
                            <div class="col-sm-2" style="display: flex; gap: 10px;">
                                <input type="date" class="form-control" required
                                    placeholder="Tanggal Akhir" name="tgl2"
                                    value="<?php if (isset($_POST['submit'])) {
                                        echo $_POST['tgl2'];
                                    } ?>"
                                    required>
                                <!-- <input name="time2" type="text" class="form-control" id="time2"
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
                                    required> -->
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
                <!-- <?php
                include 'koneksi.php'; // koneksi ke MySQL
                
                $ipaddress = $_SERVER['REMOTE_ADDR'];

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                    if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {

                        // Siapkan statement prepared
                        $sql2 = "DELETE FROM tbl_keluar_obat WHERE IP_ADDRESS = ?";
                        $stmt2 = mysqli_prepare($con, $sql2);

                        if ($stmt2) {
                            // Bind parameter IP address
                            mysqli_stmt_bind_param($stmt2, "s", $ipaddress);
                            mysqli_stmt_execute($stmt2);
                            
                            // // Eksekusi query
                            // if (mysqli_stmt_execute($stmt2)) {
                            //     // echo "<div class='alert alert-success'>Data dengan IP $ipaddress berhasil dihapus.</div>";
                            // } else {
                            //     // echo "<div class='alert alert-danger'>Gagal menghapus dari tbl_keluar_obat: " . mysqli_error($con) . "</div>";
                            // }

                            mysqli_stmt_close($stmt2);
                        } else {
                            // echo "<div class='alert alert-danger'>Gagal menyiapkan statement: " . mysqli_error($con) . "</div>";
                        }

                    } else {
                        // echo "<div class='alert alert-warning'>IP address tidak valid.</div>";
                    }
                }
                ?> -->

            </div>
        </div>
    </div>
    <!-- <?php if (isset($_POST['submit'])): ?>
        <div class="row">
        <div class="col-xs-12">
            <div class="box">                
                <div class="box-header with-border">
                <div class="card-header table-card-header">
                    <h5>LAPORAN HARIAN PEMAKAIAN OBAT GUDANG KIMIA</h5>
                </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">                 
                            <table id="Table-obat" class="table Table-obat display compact" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No. Group Line</th>
                                        <th>Tanggal & Jam</th>
                                        <th>Kode Obat</th>
                                        <th>QTY TARGET</th>
                                        <th>QTY Actual</th>
                                        <th>SATUAN</th>
                                        <th>KETERANGAN</th>
                                        <th>NAMA OBAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    set_time_limit(0);
                                    if ($_POST['time'] && $_POST['time2']) {
                                        $where_time = "AND s.TRANSACTIONTIME BETWEEN '$_POST[time]' AND '$_POST[time2]'";
                                    } else {
                                        $where_time = "";
                                    }
                                    // if ($_POST['warehouse'] == 'M510 dan M101') {
                                    //     $where_warehouse = "AND s.LOGICALWAREHOUSECODE IN ('M510', 'M101')";
                                    //     $where_warehouse2 = "AND s.LOGICALWAREHOUSECODE IN ('M510', 'M101')";
                                    // } else {
                                    //     $where_warehouse = "AND s.LOGICALWAREHOUSECODE = '$_POST[warehouse]'";
                                    //     $where_warehouse2 = "AND s.LOGICALWAREHOUSECODE = '$_POST[warehouse]'";
                                    // }
                                    $db_stocktransaction = db2_exec($conn1, "SELECT 
                                                                                    * 
                                                                                FROM 
                                                                                (SELECT
                                                                                   s.TRANSACTIONDATE || ' ' || s.TRANSACTIONTIME AS TGL,
                                                                                    s.TRANSACTIONTIME AS WAKTU,
                                                                                    s.TRANSACTIONDATE AS TGL_TRANSAKSI,
                                                                                    TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) AS TGL_WAKTU,
                                                                                    CASE
                                                                                        WHEN s.PRODUCTIONORDERCODE IS NULL THEN COALESCE(s.ORDERCODE, s.LOTCODE)
                                                                                        ELSE s.PRODUCTIONORDERCODE
                                                                                    END AS PRODUCTIONORDERCODE,
                                                                                    s.ORDERLINE,
                                                                                    s.DECOSUBCODE01,
                                                                                    s.DECOSUBCODE02,
                                                                                    s.DECOSUBCODE03,
                                                                                    CASE
                                                                                        WHEN s.TEMPLATECODE = '120' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                        WHEN s.TEMPLATECODE = '303' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                        WHEN s.TEMPLATECODE = '304' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                        WHEN s.TEMPLATECODE = '203' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                        WHEN s.TEMPLATECODE = '201' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                        WHEN s.TEMPLATECODE IN ('QCT','OPN','QCR') THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                    END AS KODE_OBAT,
                                                                                    CASE 
                                                                                        WHEN  s.USERPRIMARYUOMCODE = 't'THEN s.USERPRIMARYQUANTITY * 1000000
                                                                                        WHEN  s.USERPRIMARYUOMCODE = 'kg'THEN s.USERPRIMARYQUANTITY * 1000
                                                                                        ELSE  s.USERPRIMARYQUANTITY
                                                                                    END AS AKTUAL_QTY,
                                                                                    CASE 
                                                                                        WHEN  s.USERPRIMARYUOMCODE = 't'THEN 'g  '
                                                                                        WHEN  s.USERPRIMARYUOMCODE = 'kg'THEN 'g  '
                                                                                        ELSE  s.USERPRIMARYUOMCODE
                                                                                    END AS SATUAN,
                                                                                    s.LOGICALWAREHOUSECODE,
                                                                                    p.LONGDESCRIPTION,
                                                                                    s.TEMPLATECODE,
                                                                                    CASE
                                                                                        WHEN s.TEMPLATECODE = '303' THEN l2.LONGDESCRIPTION
                                                                                        WHEN s.TEMPLATECODE = '203' THEN l.LONGDESCRIPTION
                                                                                        WHEN s.TEMPLATECODE = '201' THEN l.LONGDESCRIPTION
                                                                                        ELSE NULL
                                                                                    END AS KETERANGAN
                                                                                FROM
                                                                                    STOCKTRANSACTION s
                                                                                LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                                                                    AND p.SUBCODE01 = s.DECOSUBCODE01
                                                                                    AND p.SUBCODE02 = s.DECOSUBCODE02
                                                                                    AND p.SUBCODE03 = s.DECOSUBCODE03
                                                                                LEFT JOIN INTERNALDOCUMENT i ON i.PROVISIONALCODE = s.ORDERCODE
                                                                                LEFT JOIN ORDERPARTNER o ON o.CUSTOMERSUPPLIERCODE = i.ORDPRNCUSTOMERSUPPLIERCODE
                                                                                LEFT JOIN LOGICALWAREHOUSE l ON l.CODE = o.CUSTOMERSUPPLIERCODE
                                                                                LEFT JOIN STOCKTRANSACTION s2 ON s2.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND s2.DETAILTYPE = 2
                                                                                LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s2.LOGICALWAREHOUSECODE
                                                                                WHERE
                                                                                    s.ITEMTYPECODE = 'DYC'
                                                                                    AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                                                    AND NOT s.TEMPLATECODE = '313'
                                                                                    AND (s.DETAILTYPE = 1 OR s.DETAILTYPE = 0)
                                                                                    AND s.LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                                                                ORDER BY
                                                                                    s.PRODUCTIONORDERCODE ASC)
                                                                                WHERE
                                                                                    TGL_WAKTU BETWEEN '$_POST[tgl] $_POST[time]:00' AND '$_POST[tgl2] $_POST[time2]:00'");
                                    $no = 1;
                                    while ($row_stocktransaction = db2_fetch_assoc($db_stocktransaction)) {
                                        $db_reservation = db2_exec($conn1, "SELECT 
                                                                                    TRIM(p.PRODUCTIONORDERCODE) || '-' || TRIM(p.GROUPSTEPNUMBER) AS NO_RESEP,
                                                                                    p.GROUPSTEPNUMBER,
                                                                                    SUM(p.USERPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
                                                                                    CASE
                                                                                        WHEN p2.CODE LIKE '%T1%' OR p2.CODE LIKE '%T2%' OR p2.CODE LIKE '%T3%' OR p2.CODE LIKE '%T4%' OR p2.CODE LIKE '%T5%' OR p2.CODE LIKE '%T6%' OR p2.CODE LIKE '%T7%' THEN 'Tambah Obat'
                                                                                        WHEN p2.CODE LIKE '%R1%' OR p2.CODE LIKE '%R2%' OR p2.CODE LIKE '%R3%' OR p2.CODE LIKE '%R4%' OR p2.CODE LIKE '%R5%' OR p2.CODE LIKE '%R6%' OR p2.CODE LIKE '%R7%' THEN 'Perbaikan'
                                                                                        -- ELSE 'Normal'
                                                                                        -- ELSE p.PRODRESERVATIONLINKGROUPCODE
                                                                                        ELSE 
                                                                                            CASE
                                                                                                WHEN p.PRODRESERVATIONLINKGROUPCODE IS NULL THEN COALESCE(p3.OPERATIONCODE, p.PRODRESERVATIONLINKGROUPCODE)
                                                                                                ELSE p.PRODRESERVATIONLINKGROUPCODE
                                                                                            END
                                                                                    END AS KETERANGAN
                                                                                FROM
                                                                                    PRODUCTIONRESERVATION p
                                                                                LEFT JOIN PRODRESERVATIONLINKGROUP p2 ON p2.CODE = p.PRODRESERVATIONLINKGROUPCODE 
                                                                                LEFT JOIN PRODUCTIONDEMANDSTEP p3 ON p3.STEPNUMBER = p.GROUPSTEPNUMBER AND p3.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                                                WHERE 
                                                                                    p.PRODUCTIONORDERCODE = '$row_stocktransaction[PRODUCTIONORDERCODE]' 
                                                                                    AND GROUPLINE = '$row_stocktransaction[ORDERLINE]'
                                                                                    -- AND p.SUBCODE01 = '$row_stocktransaction[DECOSUBCODE01]' 
                                                                                    -- AND p.SUBCODE02 = '$row_stocktransaction[DECOSUBCODE02]' 
                                                                                    -- AND p.SUBCODE03 = '$row_stocktransaction[DECOSUBCODE03]'
                                                                                GROUP BY
                                                                                    p.PRODUCTIONORDERCODE,
                                                                                    p.GROUPSTEPNUMBER,
                                                                                    p2.CODE,
                                                                                    p3.OPERATIONCODE,
                                                                                    p.PRODRESERVATIONLINKGROUPCODE");
                                        $row_reservation = db2_fetch_assoc($db_reservation);
                                        ?>
                                        <tr>
                                            <td><?php if ($row_reservation['NO_RESEP']) {
                                                echo $row_reservation['NO_RESEP'];
                                            } else {
                                                echo $row_stocktransaction['PRODUCTIONORDERCODE'];
                                            } ?>
                                            </td>
                                            <td><?= $row_stocktransaction['TGL']; ?></td>
                                            <td><?= $row_stocktransaction['KODE_OBAT']; ?></td>
                                            <td>
                                                <?php
                                                $qtyReservation = $row_reservation['USERPRIMARYQUANTITY'] ?? 0; // jika null, jadikan 0
                                                if (substr(number_format($qtyReservation, 2), -3) == '.00'): ?>
                                                    <?= number_format($qtyReservation, 0); ?>
                                                <?php else: ?>
                                                    <?= number_format($qtyReservation, 2); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $qty = $row_stocktransaction['AKTUAL_QTY'] ?? 0; // jika null, jadikan 0
                                                if (substr(number_format($qty, 2), -3) == '.00'): ?>
                                                        <?= number_format($qty, 0); ?>
                                                <?php else: ?>
                                                        <?= number_format($qty, 2); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $row_stocktransaction['SATUAN']; ?></td>
                                            <td>
                                                <?php if ($row_stocktransaction['TEMPLATECODE'] == '303' or $row_stocktransaction['TEMPLATECODE'] == '203' or $row_stocktransaction['TEMPLATECODE'] == '201'): ?>
                                                        <?= $row_stocktransaction['KETERANGAN']; ?>
                                                <?php else: ?>
                                                        <?= $row_reservation['KETERANGAN']; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $row_stocktransaction['LONGDESCRIPTION']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                            // $keterangan = (
                                            //     $row_stocktransaction['TEMPLATECODE'] == '303' ||
                                            //     $row_stocktransaction['TEMPLATECODE'] == '203' ||
                                            //     $row_stocktransaction['TEMPLATECODE'] == '201'
                                            // ) ? $row_stocktransaction['KETERANGAN'] : $row_reservation['KETERANGAN'];

                                            // $grouplineNo = !empty($row_reservation['NO_RESEP'])
                                            //     ? $row_reservation['NO_RESEP']
                                            //     : $row_stocktransaction['PRODUCTIONORDERCODE'];

                                            // $ipaddress = $_SERVER['REMOTE_ADDR'];

                                            // include_once("koneksi.php"); // pastikan ini pakai mysqli_connect

                                            // $sql = "INSERT INTO tbl_keluar_obat (
                                            //             TANGGAL,
                                            //             WAKTU,
                                            //             KODE_OBAT,
                                            //             NO_GROUPLINE,
                                            //             QTY_TARGET,
                                            //             QTY_ACTUAL,
                                            //             SATUAN,
                                            //             KETERANGAN,
                                            //             NAMA_OBAT,
                                            //             SUBCODE01,
                                            //             SUBCODE02,
                                            //             SUBCODE03,
                                            //             LOGICALWAREHOUSECODE,
                                            //             IP_ADDRESS
                                            //         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                                            // $stmt = mysqli_prepare($con, $sql);

                                            // if ($stmt) {
                                            //     // Bind parameter ke prepared statement
                                            //     mysqli_stmt_bind_param(
                                            //         $stmt,
                                            //         "ssssddssssssss", // s = string, d = double
                                            //         $row_stocktransaction['TGL_TRANSAKSI'],
                                            //         $row_stocktransaction['WAKTU'],
                                            //         $row_stocktransaction['KODE_OBAT'],
                                            //         $grouplineNo,
                                            //         $qtyReservation,
                                            //         $qty,
                                            //         $row_stocktransaction['SATUAN'],
                                            //         $keterangan,
                                            //         $row_stocktransaction['LONGDESCRIPTION'],
                                            //         $row_stocktransaction['DECOSUBCODE01'],
                                            //         $row_stocktransaction['DECOSUBCODE02'],
                                            //         $row_stocktransaction['DECOSUBCODE03'],
                                            //         $row_stocktransaction['LOGICALWAREHOUSECODE'],
                                            //         $ipaddress
                                            //     );

                                            //     // Eksekusi statement
                                            //     if (mysqli_stmt_execute($stmt)) {
                                            //         $success_flag = 1;
                                            //         // echo "Insert success!";
                                            //     } else {
                                            //         echo "<pre>Gagal insert: " . mysqli_stmt_error($stmt) . "</pre>";
                                            //     }

                                            //     mysqli_stmt_close($stmt);
                                            // } else {
                                            //     echo "<pre>Gagal prepare statement: " . mysqli_error($con) . "</pre>";
                                            // }
                                        }
                                            ?>

                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    <?php endif; ?> -->

    <div class="row">
        <div class="col-xs-12">
            <div class="box">                
                <div class="box-header with-border">
                <div class="card-header table-card-header">
                    <h5>LAPORAN BULANAN PEMAKAIAN OBAT GUDANG KIMIA</h5>
                </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">                 
                            <table id="Table-obat" class="table Table-obat display compact" style="width: 100%;">
                            <?php
                            $db_stocktransaction = db2_exec($conn1,"SELECT DECOSUBCODE01,
                                        DECOSUBCODE02,
                                        DECOSUBCODE03,
                                        KODE_OBAT,
                                        sum(AKTUAL_QTY) AS AKTUAL_QTY_KELUAR,
                                        CASE 
                                            WHEN SATUAN ='kg'THEN 'g'
                                            WHEN SATUAN = 't' THEN 'g'
                                            ELSE SATUAN
                                        END AS SATUAN,
                                        LONGDESCRIPTION,
                                        QTY_MASUK,
                                        SATUAN_MASUK
                                    FROM 
                                    (
                                    SELECT           
                                        s.DECOSUBCODE01,
                                        s.DECOSUBCODE02,
                                        s.DECOSUBCODE03,
                                        CASE
                                            WHEN s.TEMPLATECODE = '120' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                            WHEN s.TEMPLATECODE = '303' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                            WHEN s.TEMPLATECODE = '304' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                            WHEN s.TEMPLATECODE = '203' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                            WHEN s.TEMPLATECODE = '201' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                            WHEN s.TEMPLATECODE = '098' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                            ELSE s.TEMPLATECODE
                                        END AS KODE_OBAT,
                                        CASE 
                                            WHEN s.ORDERCOUNTERCODE LIKE '%I01%' THEN 0
                                            WHEN s.TEMPLATECODE = '303' AND s2.LOGICALWAREHOUSECODE ='M510' THEN 0
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN s.USERPRIMARYQUANTITY * 1000
                                            WHEN s.USERPRIMARYUOMCODE = 't' THEN s.USERPRIMARYQUANTITY * 1000000
                                            ELSE s.USERPRIMARYQUANTITY
                                        END AS AKTUAL_QTY,
                                        CASE 
                                            WHEN s.ORDERCOUNTERCODE LIKE '%I01%' AND s.USERPRIMARYUOMCODE = 'g' 
                                            THEN 'kg' 
                                            WHEN s.USERPRIMARYUOMCODE = 'kg' THEN 'g'
                                            ELSE s.USERPRIMARYUOMCODE 
                                        END AS SATUAN,
                                        p.LONGDESCRIPTION,
                                        s.TEMPLATECODE,
                                        CASE 
                                            WHEN s3.QTY_MASUK IS NULL THEN 0
                                            ELSE s3.QTY_MASUK
                                        END AS QTY_MASUK,
                                        s3.SATUAN_MASUK
                                    FROM
                                        STOCKTRANSACTION s
                                    LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                        AND p.SUBCODE01 = s.DECOSUBCODE01
                                        AND p.SUBCODE02 = s.DECOSUBCODE02
                                        AND p.SUBCODE03 = s.DECOSUBCODE03
                                    LEFT JOIN STOCKTRANSACTION s2 ON s2.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND s2.DETAILTYPE = 2
                                    LEFT JOIN ( SELECT
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
                                                    AND s.TRANSACTIONDATE BETWEEN '2025-05-01' AND '2025-06-24'
                                                    AND s.TEMPLATECODE IN ('QC1','QCT','304','OPN','204')
                                                    AND s.LOGICALWAREHOUSECODE ='$_POST[warehouse]'
                                                GROUP BY
                                                    s.ITEMTYPECODE,
                                                    s.DECOSUBCODE01,
                                                    s.DECOSUBCODE02,
                                                    s.DECOSUBCODE03,
                                                    s.USERPRIMARYUOMCODE) s3 ON s3.ITEMTYPECODE = s.ITEMTYPECODE
                                                    AND s3.DECOSUBCODE01 = s.DECOSUBCODE01
                                                    AND s3.DECOSUBCODE02 = s.DECOSUBCODE02
                                                    AND s3.DECOSUBCODE03 = s.DECOSUBCODE03
                                    LEFT JOIN ( SELECT DISTINCT 
                                                p.PRODUCTIONORDERCODE,
                                                p.GROUPLINE,
                                                CASE
                                                    WHEN p2.CODE LIKE '%T1%' OR p2.CODE LIKE '%T2%' OR p2.CODE LIKE '%T3%' OR p2.CODE LIKE '%T4%' OR p2.CODE LIKE '%T5%' OR p2.CODE LIKE '%T6%' OR p2.CODE LIKE '%T7%' THEN 'Tambah Obat'
                                                    WHEN p2.CODE LIKE '%R1%' OR p2.CODE LIKE '%R2%' OR p2.CODE LIKE '%R3%' OR p2.CODE LIKE '%R4%' OR p2.CODE LIKE '%R5%' OR p2.CODE LIKE '%R6%' OR p2.CODE LIKE '%R7%' THEN 'Perbaikan'
                                                    -- ELSE 'Normal'
                                                    -- ELSE p.PRODRESERVATIONLINKGROUPCODE
                                                    ELSE 
                                                        CASE
                                                            WHEN p.PRODRESERVATIONLINKGROUPCODE IS NULL THEN COALESCE(p3.OPERATIONCODE, p.PRODRESERVATIONLINKGROUPCODE)
                                                            ELSE p.PRODRESERVATIONLINKGROUPCODE
                                                        END
                                                END AS KETERANGAN
                                            FROM
                                                PRODUCTIONRESERVATION p
                                            LEFT JOIN PRODRESERVATIONLINKGROUP p2 ON p2.CODE = p.PRODRESERVATIONLINKGROUPCODE 
                                            LEFT JOIN PRODUCTIONDEMANDSTEP p3 ON p3.STEPNUMBER = p.GROUPSTEPNUMBER AND p3.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                    ) n2 ON n2.PRODUCTIONORDERCODE = s.PRODUCTIONORDERCODE
                                                    AND n2.GROUPLINE = s.ORDERLINE
                                WHERE  
                                    s.ITEMTYPECODE = 'DYC'
                                    AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                    AND NOT s.TEMPLATECODE IN ('313','QCR','QCT','OPN','125')
                                    AND (s.DETAILTYPE = 1 OR s.DETAILTYPE = 0)
                                    AND s.LOGICALWAREHOUSECODE ='$_POST[warehouse]'
                                    AND TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) BETWEEN '$_POST[tgl] 07:00:00' AND '$_POST[tgl2] 12:00:00' 
                                ORDER BY
                                    s.PRODUCTIONORDERCODE ASC
                                    )
                                    GROUP BY 
                                    DECOSUBCODE01,
                                    DECOSUBCODE02,
                                    DECOSUBCODE03,
                                    KODE_OBAT,
                                    CASE 
                                        WHEN SATUAN ='kg'THEN 'g'
                                        WHEN SATUAN = 't' THEN 'g'
                                        ELSE SATUAN
                                    END,
                                    LONGDESCRIPTION,
                                    QTY_MASUK,
                                    SATUAN_MASUK
                                    ORDER BY KODE_OBAT ASC");                           
                                        
                                ?>
                                
                                <thead>
                                    <tr>
                                        <th>Kode Obat</th>
                                        <th>Dyestuff/Chemical</th>
                                        <th>Stock Awal (gr)</th>
                                        <th>Masuk</th>
                                        <th>Pemakaian</th>
                                        <th>Tranasfer ke Gudang Lain</th>
                                        <th>Stock Balance</th>
                                        <th>Stock Minimum</th>
                                        <th>Buka PO</th>
                                        <th>Pemakaian(belum timbang)</th>
                                        <th>Stock Balance(future)</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th>Certification</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                while ($row = db2_fetch_assoc($db_stocktransaction)) {
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

                                    $stock_minimum = db2_exec($conn1, " SELECT 
                                            i.ITEMTYPECODE,
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
                                            CASE 
                                                WHEN p.BASEPRIMARYUNITCODE = 't' THEN i.SAFETYSTOCK *1000000
                                                WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN i.SAFETYSTOCK *1000
                                                ELSE i.SAFETYSTOCK
                                            END AS SAFETYSTOCK,
                                            CASE 
                                                WHEN p.BASEPRIMARYUNITCODE = 't' THEN 'g'
                                                WHEN p.BASEPRIMARYUNITCODE = 'kg' THEN 'g'
                                                ELSE p.BASEPRIMARYUNITCODE
                                            END AS BASEPRIMARYUNITCODE,
                                            CASE 
                                                WHEN a.VALUESTRING = 1 THEN 'BV'
                                                WHEN a.VALUESTRING = 2 THEN 'NON BV'
                                                ELSE ''
                                            END CERTIFICATION
                                            FROM 
                                            ITEMWAREHOUSELINK i 
                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = i.ITEMTYPECODE 
                                            AND p.SUBCODE01 = i.SUBCODE01
                                            AND p.SUBCODE02 = i.SUBCODE02 
                                            AND p.SUBCODE03 = i.SUBCODE03 
                                            AND p.SUBCODE04 = i.SUBCODE04 
                                            AND p.SUBCODE05 = i.SUBCODE05 
                                            AND p.SUBCODE06 = i.SUBCODE06 
                                            AND p.SUBCODE07 = i.SUBCODE07 
                                            AND p.SUBCODE08 = i.SUBCODE08 
                                            AND p.SUBCODE09 = i.SUBCODE09 
                                            AND p.SUBCODE10 = i.SUBCODE10 
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME ='Certification'
                                            WHERE  
                                            i.ITEMTYPECODE ='DYC'
                                            AND i.LOGICALWAREHOUSECODE = '$_POST[warehouse]'
                                            AND i.SUBCODE01 = '$row[DECOSUBCODE01]'
                                            AND i.SUBCODE02 = '$row[DECOSUBCODE02]' 
                                            AND i.SUBCODE03 = '$row[DECOSUBCODE03]' ");
                                    $row_stock_minimum = db2_fetch_assoc($stock_minimum);

                                    $buka_po = db2_exec($conn1, "SELECT 
                                                        p2.CONFIRMEDDUEDATE AS due_date,
                                                        CEIL(SUM(p.USERPRIMARYQUANTITY)) AS QTY
                                                    FROM PURCHASEORDERLINE p
                                                    LEFT JOIN PURCHASEORDER p2 ON p2.CODE = p.PURCHASEORDERCODE
                                                    WHERE
                                                       p.SUBCODE01 = '$row[DECOSUBCODE01]' AND
                                                       p.SUBCODE02 = '$row[DECOSUBCODE02]' AND
                                                       p.SUBCODE03 = '$row[DECOSUBCODE03]' AND
                                                       p.SUBCODE04 = '$row[DECOSUBCODE04]' AND
                                                       p.SUBCODE05 = '$row[DECOSUBCODE05]' AND
                                                       p.SUBCODE06 = '$row[DECOSUBCODE06]' AND
                                                       p.SUBCODE07 = '$row[DECOSUBCODE07]' AND
                                                        p2.CONFIRMEDDUEDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                    GROUP BY p2.CONFIRMEDDUEDATE");
                            $row_buka_po = db2_fetch_assoc($buka_po);



                                    $q_qty_awal = mysqli_query($con, "SELECT * 
                                    FROM stock_awal_obat_gdKimia 
                                    WHERE kode_obat = '$row[KODE_OBAT]'
                                    ORDER BY kode_obat ASC");
                                    $row_qty_awal = mysqli_fetch_array($q_qty_awal);

                                    ?>                               
                                    <tr>
                                        <td><?php echo $row['KODE_OBAT'] ?></td>
                                        <td><?php echo $row['LONGDESCRIPTION'] ?></td>
                                        <td>
                                        <?php if (substr(number_format($row_qty_awal['qty_awal'], 2), -3) == '.00'): ?>
                                                                                <?= number_format($row_qty_awal['qty_awal'], 0); ?>
                                                                        <?php else: ?>
                                                                                <?= number_format($row_qty_awal['qty_awal'], 2); ?>
                                                                        <?php endif; ?>
                                        </td>
                                        <td>
                                        <?php if(substr(number_format($row['QTY_MASUK'], 2), -3) == '.00') : ?>
                                                                            <?= number_format($row['QTY_MASUK'], 0); ?>
                                                                        <?php else : ?>
                                                                            <?= number_format($row['QTY_MASUK'], 2); ?>
                                                                        <?php endif; ?>
                                        </td>
                                        <td>
                                        <?php if (substr(number_format($row['AKTUAL_QTY_KELUAR'], 2), -3) == '.00'): ?>
                                                                                <?= number_format($row['AKTUAL_QTY_KELUAR'], 0); ?>
                                                                        <?php else: ?>
                                                                                <?= number_format($row['AKTUAL_QTY_KELUAR'], 2); ?>
                                                                        <?php endif; ?>
                                        </td>
                                        <td> </td>
                                        <td> <?php if (substr(number_format($row_balance['STOCK_BALANCE'], 2), -3) == '.00'): ?>
                                                                                <?= number_format($row_balance['STOCK_BALANCE'], 0); ?>
                                                                        <?php else: ?>
                                                                                <?= number_format($row_balance['STOCK_BALANCE'], 2); ?>
                                                                        <?php endif; ?>
                                        </td>
                                        <td><?php if (substr(number_format($row_stock_minimum['SAFETYSTOCK'], 2), -3) == '.00'): ?>
                                            <?= number_format($row_stock_minimum['SAFETYSTOCK'], 0); ?>
                                        <?php else: ?>
                                            <?= number_format($row_stock_minimum['SAFETYSTOCK'], 2); ?>
                                        <?php endif; ?>
                                        </td>
                                        <td><?php if (substr(number_format($row_buka_po['QTY'], 2), -3) == '.00'): ?>
                                            <?= number_format($row_buka_po['QTY'], 0); ?>
                                        <?php else: ?>
                                            <?= number_format($row_buka_po['QTY'], 2); ?>
                                        <?php endif; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo  $row_stock_minimum['CERTIFICATION']?></td>
                                    </tr>
                                   
                                    <?php $no++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="modal fade modal-3d-slit" id="ModalMergeOrder" data-backdrop="static" data-keyboard="false"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div id="body_ModalMergeOrder" class="modal-dialog" style="width:95%">

        </div>
    </div> -->
</body>

</html>
<script>
    $(document).ready(function() {
        const myTable = $('#Table-obat').DataTable({
            "ordering": false,
            "pageLength": 25,
            responsive: true,
            language: {
                searchPlaceholder: "Search..."
            },
        })        
    })
</script>
