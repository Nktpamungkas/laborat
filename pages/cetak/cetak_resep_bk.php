<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$ids = $_GET['ids'];
$idm = $_GET['idm'];
$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con,"INSERT INTO log_status_matching SET
          `ids` = '$idm', 
          `status` = 'print', 
          `info` = 'cetak resep', 
          `do_by` = '".$_SESSION['userLAB']."', 
          `do_at` = '$time', 
          `ip_address` = '$ip_num'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <link href="styles_cetak.css" rel="stylesheet" type="text/css"> -->
    <title>Cetak Form Tempelan Laborat</title>
</head>
<style>
    body,
    td,
    th {
        /*font-family: Courier New, Courier, monospace; */
        font-family: sans-serif, Roman, serif;
        font-size: 8pt;
    }

    pre {
        font-family: sans-serif, Roman, serif;
        clear: both;
        margin: 0px auto 0px;
        padding: 0px;
        white-space: pre-wrap;
        /* Since CSS 2.1 */
        white-space: -moz-pre-wrap;
        /* Mozilla, since 1999 */
        white-space: -pre-wrap;
        /* Opera 4-6 */
        white-space: -o-pre-wrap;
        /* Opera 7 */
        word-wrap: break-word;

    }

    body {
        margin: 0px auto 0px;
        padding: 2px;
        font-size: 8pt;
        color: #000;
        width: 98%;
        background-position: top;
        background-color: #fff;
    }

    .table-list {
        clear: both;
        text-align: left;
        border-collapse: collapse;
        margin: 0px 0px 10px 0px;
        background: #fff;
    }

    .table-list td {
        color: #333;
        font-size: 8pt;
        border-color: #fff;
        border-collapse: collapse;
        vertical-align: center;
        padding: 3px 5px;
        border-bottom: 1px #000000 solid;
        border-left: 1px #000000 solid;
        border-right: 1px #000000 solid;


    }

    .table-list1 {
        clear: both;
        text-align: left;
        border-collapse: collapse;
        margin: 0px 0px 5px 0px;
        background: #fff;
    }

    .table-list1 td {
        color: #333;
        font-size: 8pt;
        border-color: #fff;
        border-collapse: collapse;
        vertical-align: center;
        padding: 1px 3px;
        border-bottom: 1px #000000 solid;
        border-top: 1px #000000 solid;
        border-left: 1px #000000 solid;
        border-right: 1px #000000 solid;


    }

    #nocetak {
        display: none;
    }

    @page {
        size: F4;
        margin: 10px 10px 10px 10px;
        font-size: 8pt !important;
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }

    @media print {
        @page {
            size: F4;
            margin: 10px 10px 10px 10px;
            font-size: 8pt !important;
        }

        html,
        body {
            height: 330mm;
            width: 210mm;
            background: #FFF;
            overflow: visible;
        }

        /* body {
            padding-top: 15mm;
        } */

        .table-ttd {
            border-collapse: collapse;
            width: 100%;
            font-size: 8pt !important;
        }

        .table-ttd tr,
        .table-ttd tr td {
            border: 0.5px solid black;
            padding: 4px;
            padding: 4px;
            font-size: 8pt !important;
        }
    }

    .table-ttd {
        border-collapse: collapse;
        width: 100%;
        font-size: 8pt !important;
    }

    .table-ttd tr,
    .table-ttd tr td {
        border: 1px solid black;
        padding: 5px;
        padding: 5px;
        font-size: 8pt !important;
    }

    tr {
        page-break-before: always;
        page-break-inside: avoid;
        font-size: 8pt !important;
    }

    .tablee td,
    .tablee th {
        /* border: 1px solid black; */
        padding: 5px;
        font-size: 8pt !important;

    }

    .rotation {
        transform: rotate(-90deg);
        /* Legacy vendor prefixes that you probably don't need... */
        /* Safari */
        -webkit-transform: rotate(-90deg);
        /* Firefox */
        -moz-transform: rotate(-90deg);
        /* IE */
        -ms-transform: rotate(-90deg);
        /* Opera */
        -o-transform: rotate(-90deg);
        /* Internet Explorer */
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }

    ul,
    li {
        list-style-type: none;
        font-size: 8pt !important;
    }

    .tablee tr:nth-child(even) {
        background-color: #f2f2f2;
        font-size: 8pt !important;
    }

    .table-ttd thead tr td,
    #tr-footer {
        font-weight: bold;
    }

    .tablee th {
        padding-top: 1ptpx;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
        font-size: 8pt !important;
    }
</style>
<style media="print">
    @page {
        size: auto;
        margin: 15px;
    }

    html,
    body {
        height: 100%;
    }
</style>


<body>
    <?php
    $qry = mysqli_query($con,"SELECT * , a.id as id_status, b.id as id_matching
    from db_laborat.tbl_status_matching a 
    join db_laborat.tbl_matching b on a.idm = b.no_resep 
    where a.id = '$ids'
    ORDER BY a.id desc limit 1");
    $data = mysqli_fetch_array($qry);
    ?>
    <br>
    <!--<div align="right" style="font-size: 12px;">FW-12-LAB-04</div>-->
    <table width="100%" border="0" class="table-list1">
        <tr>
          <td style="border-right:0px #000000 solid;">Recipe Code</td>
          <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
          <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['recipe_code']; ?></strong></td>
          <td style="border-right:0px #000000 solid;">Color Code</td>
          <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
          <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['color_code']; ?></strong></td>
          <td colspan="3" style="text-align: right;" ><span style="font-size: 9px;">FW-12-LAB-04</span></td>
        </tr>
        <tr>
            <td width="9%" style="border-right:0px #000000 solid;">Suffix</td>
            <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td width="20%" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_resep']; ?></strong></td>
            <td width="10%" style="border-right:0px #000000 solid;">LAB DIP No</td>
            <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td width="31%" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_warna']; ?></strong></td>
            <td width="15%" style="border-right:0px #000000 solid;">Gramasi Aktual</td>
            <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td width="12%" style="border-left:0px #000000 solid;"><strong><?Php if ($data['lebar_aktual'] != "") {
                                                                                echo floatval($data['lebar_aktual']) . " x " . floatval($data['gramasi_aktual']) . " gr/m2";
                                                                            } else {
                                                                                echo "<font color=white>" . floatval($data['lebar_aktual']) . "</font>&nbsp;&nbsp;&nbsp;&nbsp; x <font color=white> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . floatval($data['gramasi_aktual']) . "</font> gr/m2";
                                                                            } ?></strong></td>
        </tr>
        <tr>
            <td style="border-right:0px #000000 solid;">Tanggal</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><strong><?php echo date("Y-m-d"); ?></strong></td>
            <td style="border-right:0px #000000 solid;">Warna</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['warna']; ?></strong></td>
            <td style="border-right:0px #000000 solid;">Gramasi Permintaan</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><strong><?Php echo floatval($data['lebar']) . " x " . floatval($data['gramasi']) . " gr/m2"; ?></strong></td>
        </tr>
        <tr>
            <td style="border-right:0px #000000 solid;">Item</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_item']; ?></strong></td>
            <td style="border-right:0px #000000 solid;">Langganan</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><?Php echo $data['langganan']; ?></td>
            <td style="border-right:0px #000000 solid;">% Kadar Air</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><strong><?Php echo floatval($data['kadar_air']); ?> %</strong></td>
        </tr>
        <tr>
            <td style="border-right:0px #000000 solid;">PO Greige</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_po']; ?></strong></td>
            <td style="border-right:0px #000000 solid;"><?php if ($data['jenis_matching'] != 'L/D') {
                                                            echo 'No. Order';
                                                        } else {
                                                            echo 'Request No';
                                                        } ?></td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><?Php echo $data['no_order']; ?></td>
            <td style="border-right:0px #000000 solid;">Jml Percobaan</td>
            <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['percobaan_ke']; ?></strong></td>
        </tr>
        <tr style="height: 0.4in">
            <td valign="top" style="border-right:0px #000000 solid;">Jenis Kain</td>
            <td valign="top" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td valign="top" style="border-left:0px #000000 solid;"><?Php echo $data['jenis_kain']; ?></td>
            <td valign="top" style="border-right:0px #000000 solid;">Benang</td>
            <td valign="top" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td valign="top" style="border-left:0px #000000 solid;"><?Php echo $data['benang']; ?></td>
            <td valign="top" style="border-right:0px #000000 solid;">Cocok Warna</td>
            <td valign="top" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td valign="top" style="border-left:0px #000000 solid;"><?Php echo $data['cocok_warna']; ?></td>
        </tr>
    </table>
    <table width="100%" border="1" class="table-list1">
        <tr style="height: 0.3in">
            <td width="8%" align="center"><strong>KODE</strong></td>
            <td width="8%" align="center"><strong>NEW KODE</strong></td>
            <td width="5%" align="center"><strong>LAB</td>
            <td width="5%" align="center"><strong>Adj-1</td>
            <td width="5%" align="center"><strong>Adj-2</strong></td>
            <td width="5%" align="center"><strong>Adj-3</strong></td>
            <td width="5%" align="center"><strong>Adj-4</strong></td>
            <td width="5%" align="center"><strong>Adj-5</strong></td>
            <td width="5%" align="center"><strong>Adj-6</strong></td>
            <td width="5%" align="center"><strong>Adj-7</strong></td>
            <td width="5%" align="center"><strong>Adj-8</strong></td>
            <td width="5%" align="center"><strong>Adj-9</strong></td>
            <td colspan="2" align="center"><strong>BODY</strong></td>
        </tr>
        <?php
        $resep1 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 1 limit 1");
        $rsp1 = mysqli_fetch_array($resep1);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp1['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc1']) != 0) echo floatval($rsp1['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc2']) != 0) echo floatval($rsp1['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc3']) != 0) echo floatval($rsp1['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc4']) != 0) echo floatval($rsp1['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc5']) != 0) echo floatval($rsp1['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc6']) != 0) echo floatval($rsp1['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc7']) != 0) echo floatval($rsp1['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc8']) != 0) echo floatval($rsp1['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc9']) != 0) echo floatval($rsp1['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc10']) != 0) echo floatval($rsp1['conc10']) ?></td>
            <?php
            $sql_Norder1 = mysqli_query($con,"SELECT `order` from tbl_orderchild 
            where id_matching = '$data[id_matching]' and id_status = '$data[id_status]' order by flag limit 0,50");
            $iteration = 1;
            ?>
            <td colspan="2" rowspan="7" valign="top">
                <?php while ($no = mysqli_fetch_array($sql_Norder1)) { ?>
                    <?php echo $iteration++ . '.(' . $no['order'] ?>)&nbsp;&nbsp;&nbsp;
                <?php } ?>
				<div align="right"><strong style="font-size: 21px;"><?php if($data['salesman_sample']=="1"){ echo "S/S"; } ?></strong></div>
            </td>
        </tr>
        <?php
        $resep2 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 2 
                                                              order by flag asc limit 1");
        $rsp2 = mysqli_fetch_array($resep2);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp2['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc1']) != 0) echo floatval($rsp2['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc2']) != 0) echo floatval($rsp2['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc3']) != 0) echo floatval($rsp2['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc4']) != 0) echo floatval($rsp2['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc5']) != 0) echo floatval($rsp2['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc6']) != 0) echo floatval($rsp2['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc7']) != 0) echo floatval($rsp2['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc8']) != 0) echo floatval($rsp2['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc9']) != 0) echo floatval($rsp2['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc10']) != 0) echo floatval($rsp2['conc10']) ?></td>
        </tr>
        <?php
        $resep3 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 3 
                                                              order by flag asc limit 1");
        $rsp3 = mysqli_fetch_array($resep3);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp3['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc1']) != 0) echo floatval($rsp3['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc2']) != 0) echo floatval($rsp3['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc3']) != 0) echo floatval($rsp3['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc4']) != 0) echo floatval($rsp3['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc5']) != 0) echo floatval($rsp3['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc6']) != 0) echo floatval($rsp3['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc7']) != 0) echo floatval($rsp3['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc8']) != 0) echo floatval($rsp3['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc9']) != 0) echo floatval($rsp3['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc10']) != 0) echo floatval($rsp3['conc10']) ?></td>
        </tr>
        <?php
        $resep4 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 4 
                                                              order by flag asc limit 1");
        $rsp4 = mysqli_fetch_array($resep4);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp4['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc1']) != 0) echo floatval($rsp4['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc2']) != 0) echo floatval($rsp4['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc3']) != 0) echo floatval($rsp4['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc4']) != 0) echo floatval($rsp4['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc5']) != 0) echo floatval($rsp4['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc6']) != 0) echo floatval($rsp4['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc7']) != 0) echo floatval($rsp4['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc8']) != 0) echo floatval($rsp4['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc9']) != 0) echo floatval($rsp4['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc10']) != 0) echo floatval($rsp4['conc10']) ?></td>
        </tr>
        <?php
        $resep5 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 5 
                                                              order by flag asc limit 1");
        $rsp5 = mysqli_fetch_array($resep5);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp5['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc1']) != 0) echo floatval($rsp5['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc2']) != 0) echo floatval($rsp5['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc3']) != 0) echo floatval($rsp5['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc4']) != 0) echo floatval($rsp5['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc5']) != 0) echo floatval($rsp5['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc6']) != 0) echo floatval($rsp5['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc7']) != 0) echo floatval($rsp5['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc8']) != 0) echo floatval($rsp5['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc9']) != 0) echo floatval($rsp5['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc10']) != 0) echo floatval($rsp5['conc10']) ?></td>
        </tr>
        <?php
        $resep6 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 6 
                                                              order by flag asc limit 1");
        $rsp6 = mysqli_fetch_array($resep6);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp6['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc1']) != 0) echo floatval($rsp6['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc2']) != 0) echo floatval($rsp6['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc3']) != 0) echo floatval($rsp6['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc4']) != 0) echo floatval($rsp6['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc5']) != 0) echo floatval($rsp6['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc6']) != 0) echo floatval($rsp6['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc7']) != 0) echo floatval($rsp6['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc8']) != 0) echo floatval($rsp6['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc9']) != 0) echo floatval($rsp6['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc10']) != 0) echo floatval($rsp6['conc10']) ?></td>
        </tr>
        <?php
        $resep7 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 7 
                                                              order by flag asc limit 1");
        $rsp7 = mysqli_fetch_array($resep7);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp7['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc1']) != 0) echo floatval($rsp7['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc2']) != 0) echo floatval($rsp7['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc3']) != 0) echo floatval($rsp7['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc4']) != 0) echo floatval($rsp7['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc5']) != 0) echo floatval($rsp7['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc6']) != 0) echo floatval($rsp7['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc7']) != 0) echo floatval($rsp7['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc8']) != 0) echo floatval($rsp7['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc9']) != 0) echo floatval($rsp7['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc10']) != 0) echo floatval($rsp7['conc10']) ?></td>
        </tr>
        <?php
        $resep8 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 8 
                                                              order by flag asc limit 1");
        $rsp8 = mysqli_fetch_array($resep8);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp8['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc1']) != 0) echo floatval($rsp8['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc2']) != 0) echo floatval($rsp8['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc3']) != 0) echo floatval($rsp8['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc4']) != 0) echo floatval($rsp8['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc5']) != 0) echo floatval($rsp8['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc6']) != 0) echo floatval($rsp8['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc7']) != 0) echo floatval($rsp8['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc8']) != 0) echo floatval($rsp8['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc9']) != 0) echo floatval($rsp8['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc10']) != 0) echo floatval($rsp8['conc10']) ?></td>
            <td style="font-weight: bold;">Colorist 1 : <?php echo $data['colorist1'] ?></td>
            <td style="font-weight: bold;">Colorist 2 : <?php echo $data['colorist2'] ?></td>
        </tr>
        <?php
        $resep9 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 9 
                                                              order by flag asc limit 1");
        $rsp9 = mysqli_fetch_array($resep9);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp9['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc1']) != 0) echo floatval($rsp9['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc2']) != 0) echo floatval($rsp9['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc3']) != 0) echo floatval($rsp9['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc4']) != 0) echo floatval($rsp9['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc5']) != 0) echo floatval($rsp9['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc6']) != 0) echo floatval($rsp9['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc7']) != 0) echo floatval($rsp9['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc8']) != 0) echo floatval($rsp9['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc9']) != 0) echo floatval($rsp9['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc10']) != 0) echo floatval($rsp9['conc10']) ?></td>
            <td colspan="2" rowspan="2" align="center"><strong>LAB. SAMPLE</strong></td>
        </tr>
        <?php
        $resep10 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 10 
                                                              order by flag asc limit 1");
        $rsp10 = mysqli_fetch_array($resep10);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp10['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc1']) != 0) echo floatval($rsp10['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc2']) != 0) echo floatval($rsp10['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc3']) != 0) echo floatval($rsp10['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc4']) != 0) echo floatval($rsp10['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc5']) != 0) echo floatval($rsp10['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc6']) != 0) echo floatval($rsp10['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc7']) != 0) echo floatval($rsp10['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc8']) != 0) echo floatval($rsp10['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc9']) != 0) echo floatval($rsp10['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc10']) != 0) echo floatval($rsp10['conc10']) ?></td>
        </tr>
        <?php
        $resep11 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 11 
                                                              order by flag asc limit 1");
        $rsp11 = mysqli_fetch_array($resep11);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp11['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc1']) != 0) echo floatval($rsp11['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc2']) != 0) echo floatval($rsp11['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc3']) != 0) echo floatval($rsp11['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc4']) != 0) echo floatval($rsp11['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc5']) != 0) echo floatval($rsp11['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc6']) != 0) echo floatval($rsp11['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc7']) != 0) echo floatval($rsp11['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc8']) != 0) echo floatval($rsp11['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc9']) != 0) echo floatval($rsp11['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc10']) != 0) echo floatval($rsp11['conc10']) ?></td>
            <?php
            $sql_Norder1 = mysqli_query($con,"SELECT `order` from tbl_orderchild 
            where id_matching = '$data[id_matching]' and id_status = '$data[id_status]' order by flag limit 51,100");
            $iteration = 1;
            ?>
            <td colspan="2" rowspan="10" valign="top">
                <?php while ($no = mysqli_fetch_array($sql_Norder1)) { ?>
                    <?php echo $iteration++ . '.(' . $no['order']; ?>)&nbsp;&nbsp;&nbsp;
                <?php } ?>
            </td>
        </tr>
        <?php
        $resep12 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 12
                                                              order by flag asc limit 1");
        $rsp12 = mysqli_fetch_array($resep12);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp12['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc1']) != 0) echo floatval($rsp12['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc2']) != 0) echo floatval($rsp12['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc3']) != 0) echo floatval($rsp12['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc4']) != 0) echo floatval($rsp12['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc5']) != 0) echo floatval($rsp12['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc6']) != 0) echo floatval($rsp12['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc7']) != 0) echo floatval($rsp12['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc8']) != 0) echo floatval($rsp12['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc9']) != 0) echo floatval($rsp12['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc10']) != 0) echo floatval($rsp12['conc10']) ?></td>
        </tr>
        <?php
        $resep13 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 13 
                                                              order by flag asc limit 1");
        $rsp13 = mysqli_fetch_array($resep13);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp13['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc1']) != 0) echo floatval($rsp13['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc2']) != 0) echo floatval($rsp13['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc3']) != 0) echo floatval($rsp13['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc4']) != 0) echo floatval($rsp13['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc5']) != 0) echo floatval($rsp13['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc6']) != 0) echo floatval($rsp13['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc7']) != 0) echo floatval($rsp13['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc8']) != 0) echo floatval($rsp13['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc9']) != 0) echo floatval($rsp13['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc10']) != 0) echo floatval($rsp13['conc10']) ?></td>
        </tr>
        <?php
        $resep14 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 14 
                                                              order by flag asc limit 1");
        $rsp14 = mysqli_fetch_array($resep14);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp14['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc1']) != 0) echo floatval($rsp14['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc2']) != 0) echo floatval($rsp14['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc3']) != 0) echo floatval($rsp14['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc4']) != 0) echo floatval($rsp14['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc5']) != 0) echo floatval($rsp14['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc6']) != 0) echo floatval($rsp14['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc7']) != 0) echo floatval($rsp14['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc8']) != 0) echo floatval($rsp14['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc9']) != 0) echo floatval($rsp14['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc10']) != 0) echo floatval($rsp14['conc10']) ?></td>
        </tr>
        <?php
        $resep15 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 15 
                                                              order by flag asc limit 1");
        $rsp15 = mysqli_fetch_array($resep15);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp15['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc1']) != 0) echo floatval($rsp15['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc2']) != 0) echo floatval($rsp15['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc3']) != 0) echo floatval($rsp15['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc4']) != 0) echo floatval($rsp15['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc5']) != 0) echo floatval($rsp15['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc6']) != 0) echo floatval($rsp15['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc7']) != 0) echo floatval($rsp15['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc8']) != 0) echo floatval($rsp15['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc9']) != 0) echo floatval($rsp15['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc10']) != 0) echo floatval($rsp15['conc10']) ?></td>
        </tr>
        <?php
        $resep16 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 16 
                                                              order by flag asc limit 1");
        $rsp16 = mysqli_fetch_array($resep16);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp16['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc1']) != 0) echo floatval($rsp16['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc2']) != 0) echo floatval($rsp16['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc3']) != 0) echo floatval($rsp16['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc4']) != 0) echo floatval($rsp16['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc5']) != 0) echo floatval($rsp16['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc6']) != 0) echo floatval($rsp16['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc7']) != 0) echo floatval($rsp16['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc8']) != 0) echo floatval($rsp16['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc9']) != 0) echo floatval($rsp16['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc10']) != 0) echo floatval($rsp16['conc10']) ?></td>
        </tr>
        <?php
        $resep17 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 17 
                                                              order by flag asc limit 1");
        $rsp17 = mysqli_fetch_array($resep17);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp17['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc1']) != 0) echo floatval($rsp17['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc2']) != 0) echo floatval($rsp17['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc3']) != 0) echo floatval($rsp17['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc4']) != 0) echo floatval($rsp17['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc5']) != 0) echo floatval($rsp17['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc6']) != 0) echo floatval($rsp17['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc7']) != 0) echo floatval($rsp17['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc8']) != 0) echo floatval($rsp17['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc9']) != 0) echo floatval($rsp17['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc10']) != 0) echo floatval($rsp17['conc10']) ?></td>
        </tr>
        <?php
        $resep18 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 18 
                                                              order by flag asc limit 1");
        $rsp18 = mysqli_fetch_array($resep18);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp18['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc1']) != 0) echo floatval($rsp18['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc2']) != 0) echo floatval($rsp18['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc3']) != 0) echo floatval($rsp18['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc4']) != 0) echo floatval($rsp18['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc5']) != 0) echo floatval($rsp18['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc6']) != 0) echo floatval($rsp18['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc7']) != 0) echo floatval($rsp18['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc8']) != 0) echo floatval($rsp18['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc9']) != 0) echo floatval($rsp18['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc10']) != 0) echo floatval($rsp18['conc10']) ?></td>
        </tr>
        <?php
        $resep19 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 19 
                                                              order by flag asc limit 1");
        $rsp19 = mysqli_fetch_array($resep19);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp19['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc1']) != 0) echo floatval($rsp19['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc2']) != 0) echo floatval($rsp19['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc3']) != 0) echo floatval($rsp19['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc4']) != 0) echo floatval($rsp19['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc5']) != 0) echo floatval($rsp19['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc6']) != 0) echo floatval($rsp19['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc7']) != 0) echo floatval($rsp19['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc8']) != 0) echo floatval($rsp19['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc9']) != 0) echo floatval($rsp19['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc10']) != 0) echo floatval($rsp19['conc10']) ?></td>
        </tr>
        <?php
        $resep20 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 20 
                                                              order by flag asc limit 1");
        $rsp20 = mysqli_fetch_array($resep20);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp20['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc1']) != 0) echo floatval($rsp20['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc2']) != 0) echo floatval($rsp20['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc3']) != 0) echo floatval($rsp20['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc4']) != 0) echo floatval($rsp20['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc5']) != 0) echo floatval($rsp20['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc6']) != 0) echo floatval($rsp20['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc7']) != 0) echo floatval($rsp20['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc8']) != 0) echo floatval($rsp20['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc9']) != 0) echo floatval($rsp20['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc10']) != 0) echo floatval($rsp20['conc10']) ?></td>
        </tr>
        <?php
        $resep21 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 21 
                                                              order by flag asc limit 1");
        $rsp21 = mysqli_fetch_array($resep21);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp21['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc1']) != 0) echo floatval($rsp21['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc2']) != 0) echo floatval($rsp21['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc3']) != 0) echo floatval($rsp21['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc4']) != 0) echo floatval($rsp21['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc5']) != 0) echo floatval($rsp21['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc6']) != 0) echo floatval($rsp21['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc7']) != 0) echo floatval($rsp21['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc8']) != 0) echo floatval($rsp21['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc9']) != 0) echo floatval($rsp21['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc10']) != 0) echo floatval($rsp21['conc10']) ?></td>
            <td width="20%" rowspan="2" align="center"><strong>BEFORE SOAPING</strong></td>
            <td width="21%" rowspan="2" align="center"><strong>T-SIDE</strong></td>
        </tr>
        <?php
        $resep22 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 22 
                                                              order by flag asc limit 1");
        $rsp22 = mysqli_fetch_array($resep22);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp22['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc1']) != 0) echo floatval($rsp22['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc2']) != 0) echo floatval($rsp22['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc3']) != 0) echo floatval($rsp22['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc4']) != 0) echo floatval($rsp22['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc5']) != 0) echo floatval($rsp22['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc6']) != 0) echo floatval($rsp22['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc7']) != 0) echo floatval($rsp22['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc8']) != 0) echo floatval($rsp22['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc9']) != 0) echo floatval($rsp22['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc10']) != 0) echo floatval($rsp22['conc10']) ?></td>
        </tr>
        <?php
        $resep23 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 23 
                                                              order by flag asc limit 1");
        $rsp23 = mysqli_fetch_array($resep23);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp23['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc1']) != 0) echo floatval($rsp23['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc2']) != 0) echo floatval($rsp23['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc3']) != 0) echo floatval($rsp23['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc4']) != 0) echo floatval($rsp23['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc5']) != 0) echo floatval($rsp23['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc6']) != 0) echo floatval($rsp23['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc7']) != 0) echo floatval($rsp23['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc8']) != 0) echo floatval($rsp23['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc9']) != 0) echo floatval($rsp23['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc10']) != 0) echo floatval($rsp23['conc10']) ?></td>
            <td rowspan="7">&nbsp;</td>
            <td rowspan="7">&nbsp;</td>
        </tr>
        <?php
        $resep24 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 24 
                                                              order by flag asc limit 1");
        $rsp24 = mysqli_fetch_array($resep24);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp24['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc1']) != 0) echo floatval($rsp24['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc2']) != 0) echo floatval($rsp24['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc3']) != 0) echo floatval($rsp24['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc4']) != 0) echo floatval($rsp24['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc5']) != 0) echo floatval($rsp24['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc6']) != 0) echo floatval($rsp24['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc7']) != 0) echo floatval($rsp24['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc8']) != 0) echo floatval($rsp24['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc9']) != 0) echo floatval($rsp24['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc10']) != 0) echo floatval($rsp24['conc10']) ?></td>
        </tr>
        <?php
        $resep25 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 25 
                                                              order by flag asc limit 1");
        $rsp25 = mysqli_fetch_array($resep25);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp25['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc1']) != 0) echo floatval($rsp25['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc2']) != 0) echo floatval($rsp25['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc3']) != 0) echo floatval($rsp25['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc4']) != 0) echo floatval($rsp25['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc5']) != 0) echo floatval($rsp25['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc6']) != 0) echo floatval($rsp25['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc7']) != 0) echo floatval($rsp25['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc8']) != 0) echo floatval($rsp25['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc9']) != 0) echo floatval($rsp25['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc10']) != 0) echo floatval($rsp25['conc10']) ?></td>
        </tr>
        <?php
        $resep26 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 26 
                                                              order by flag asc limit 1");
        $rsp26 = mysqli_fetch_array($resep26);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp26['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc1']) != 0) echo floatval($rsp26['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc2']) != 0) echo floatval($rsp26['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc3']) != 0) echo floatval($rsp26['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc4']) != 0) echo floatval($rsp26['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc5']) != 0) echo floatval($rsp26['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc6']) != 0) echo floatval($rsp26['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc7']) != 0) echo floatval($rsp26['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc8']) != 0) echo floatval($rsp26['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc9']) != 0) echo floatval($rsp26['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc10']) != 0) echo floatval($rsp26['conc10']) ?></td>
        </tr>
        <?php
        $resep27 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                              and id_status = '$data[id_status]' 
                                                              and flag = 27 
                                                              order by flag asc limit 1");
        $rsp27 = mysqli_fetch_array($resep27);
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $rsp27['kode'] ?></td>
            <td style="font-weight: bold;">&nbsp;</td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc1']) != 0) echo floatval($rsp27['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc2']) != 0) echo floatval($rsp27['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc3']) != 0) echo floatval($rsp27['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc4']) != 0) echo floatval($rsp27['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc5']) != 0) echo floatval($rsp27['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc6']) != 0) echo floatval($rsp27['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc7']) != 0) echo floatval($rsp27['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc8']) != 0) echo floatval($rsp27['conc8']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc9']) != 0) echo floatval($rsp27['conc9']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc10']) != 0) echo floatval($rsp27['conc10']) ?></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="3">&nbsp;</td>
            <td colspan="4" align="center">T-SIDE</td>
            <td colspan="5" align="center">C.SIDE</td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="3" align="center">Temp x Time</td>
            <td colspan="4" align="center"><?php echo floatval($data['tside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo floatval($data['tside_min']) ?> min</td>
            <td colspan="5" align="center"><?php echo floatval($data['cside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo floatval($data['cside_min']) ?> min</td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="3" align="center">L:R</td>
            <td colspan="4" align="center"><?php echo $data['lr'] ?></td>
            <td colspan="5" align="center"><?php echo $data['second_lr'] ?></td>
            <td valign="top"><strong>PROSES</strong> : <?php echo $data['proses'] ?></td>
            <?php $sqlLampu =  mysqli_query($con,"SELECT lampu from vpot_lampbuy where buyer = '$data[buyer]' order by flag"); ?>
            <td valign="top">
                <strong>LAMPU : </strong>
                <?php $ii = 1;
                while ($lampu = mysqli_fetch_array($sqlLampu)) {
                    echo $ii++ . '(' . $lampu['lampu'] . '), ';
                } ?>
            </td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="3" align="center">pH</td>
            <td colspan="4" align="center">&nbsp; <?php echo $data['ph'] ?></td>
            <td colspan="5">&nbsp;</td>
            <td style="font-weight: bold;">CIE WI &nbsp;&nbsp;: <?php echo number_format($data['cie_wi'], 2); ?></td>
            <td style="font-weight: bold;">CIE TINT : <?php echo number_format($data['cie_tint'], 2); ?></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="3" align="center">RC</td>
            <td colspan="4" align="center"><?php echo floatval($data['rc_sh']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo floatval($data['rc_tm']) ?> min</td>
            <td colspan="5">&nbsp;</td>
            <td colspan="2" align="center"><strong>GREIGE</strong></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="3" align="center">Bleaching</td>
            <td colspan="4" align="center"><?php echo floatval($data['bleaching_sh']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo floatval($data['bleaching_tm']) ?> min</td>
            <td colspan="5">&nbsp;</td>
            <td colspan="2" rowspan="4" valign="top">Info Dyeing : <?php echo $data['remark_dye'] ?></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="3" align="center">Soaping</td>
            <td colspan="4" align="center">&nbsp;</td>
            <td colspan="5" align="center"><?php echo floatval($data['soaping_sh']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo floatval($data['soaping_tm']) ?> min</td>

        </tr>
        <tr style="height: 0.4in">
            <td colspan="7" align="center">BEFORE RC / Bleaching</td>
            <td colspan="5" align="center">AFTER RC / Bleaching</td>
        </tr>
        <tr style="height: 1.5in">
            <td colspan="7">&nbsp;</td>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 0.2in" colspan="3">&nbsp;</td>
            <td style="height: 0.2in" colspan="4" align="center"> <strong>Matcher</strong></td>
            <td style="height: 0.2in" colspan="4" align="center"> <strong>Buka Resep</strong></td>
            <td style="height: 0.2in" colspan="3" align="center"> <strong>Approved</strong></td>
        </tr>
        <tr>
            <td style="height: 0.2in" colspan="3">Nama</td>
            <td style="height: 0.2in" colspan="4" align="center"><?php echo $data['final_matcher'] ?></td>
            <td style="height: 0.2in" colspan="4" align="center"><?php echo $data['selesai_by'] ?></td>
            <td style="height: 0.2in" colspan="3" align="center"><?php echo $data['approve_by'] ?></td>
        </tr>
        <tr>
            <td style="height: 0.2in" colspan="3">Tanggal</td>
            <td style="height: 0.2in" colspan="4" align="center"><?php echo $data['selesai_at'] ?></td>
            <td style="height: 0.2in" colspan="4" align="center"><?php echo $data['selesai_at'] ?></td>
            <td style="height: 0.2in" colspan="3" align="center"><?php echo $data['approve_at'] ?></td>
        </tr>
        <tr>
            <td style="height: 0.4in" colspan="3">TTD</td>
            <td style="height: 0.4in" colspan="4" align="center">&nbsp;</td>
            <td style="height: 0.4in" colspan="4" align="center"><?php echo $data['selesai_by'] ?></td>
            <td style="height: 0.4in" colspan="3" align="center"><?php echo $data['approve_by'] ?></td>
        </tr>
    </table>
    <!-- <div align="left" style="font-size: 12px;">TTD :</div> -->
</body>

</html>
<script>
    setTimeout(function() {
        window.print()
    }, 1500);
</script>