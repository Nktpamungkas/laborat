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
            <td width="10%" align="center"><strong>ERP KODE</strong></td>
            <td width="5%" align="center"><strong>LAB</td>
            <td width="5%" align="center"><strong>Adj-1</td>
            <td width="5%" align="center"><strong>Adj-2</strong></td>
            <td width="5%" align="center"><strong>Adj-3</strong></td>
            <td width="5%" align="center"><strong>Adj-4</strong></td>
            <td width="5%" align="center"><strong>Adj-5</strong></td>
            <td width="5%" align="center"><strong>Adj-6</strong></td>
            <td width="5%" align="center"><strong>Adj-7</strong></td>
            <td colspan="2" align="center"><strong>BODY</strong></td>
        </tr>
        <?php
            $resep1 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 1 limit 1");
            $rsp1 = mysqli_fetch_array($resep1);
            
            $KodeBaru = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp1[kode]' limit 1");
            $kdbr = mysqli_fetch_array($KodeBaru);
            
            if($kdbr['code_new']){ 
                $kode_lama = $rsp1['kode'];
                $kode_baru = $kdbr['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp1[kode]' limit 1");
                $kdbr_now = mysqli_fetch_array($KodeBaru_now);
                if($kdbr_now['code'] && $kdbr_now['code_new']){
                    $kode_lama = $kdbr_now['code'];
                    $kode_baru = $kdbr_now['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama = $rsp1['kode'];
                    $kode_baru = $kdbr['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc1']) != 0) echo floatval($rsp1['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc2']) != 0) echo floatval($rsp1['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc3']) != 0) echo floatval($rsp1['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc4']) != 0) echo floatval($rsp1['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc5']) != 0) echo floatval($rsp1['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc6']) != 0) echo floatval($rsp1['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc7']) != 0) echo floatval($rsp1['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp1['conc8']) != 0) echo floatval($rsp1['conc8']) ?></td>
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
            $KodeBaru2 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp2[kode]'
                                                                limit 1");
            $kdbr2 = mysqli_fetch_array($KodeBaru2);

            if($kdbr2['code_new']){ 
                $kode_lama2 = $rsp2['kode'];
                $kode_baru2 = $kdbr2['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now2 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp2[kode]' limit 1");
                $kdbr_now2 = mysqli_fetch_array($KodeBaru_now2);
                if($kdbr_now2['code'] && $kdbr_now2['code_new']){
                    $kode_lama2 = $kdbr_now2['code'];
                    $kode_baru2 = $kdbr_now2['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama2 = $rsp2['kode'];
                    $kode_baru2 = $kdbr2['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama2; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru2; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc1']) != 0) echo floatval($rsp2['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc2']) != 0) echo floatval($rsp2['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc3']) != 0) echo floatval($rsp2['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc4']) != 0) echo floatval($rsp2['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc5']) != 0) echo floatval($rsp2['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc6']) != 0) echo floatval($rsp2['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc7']) != 0) echo floatval($rsp2['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp2['conc8']) != 0) echo floatval($rsp2['conc8']) ?></td>
        </tr>
        <?php
            $resep3 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 3 
                                                                order by flag asc limit 1");
            $rsp3 = mysqli_fetch_array($resep3);
            
            $KodeBaru3 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp3[kode]'
                                                                limit 1");
            $kdbr3 = mysqli_fetch_array($KodeBaru3);

            if($kdbr3['code_new']){ 
                $kode_lama3 = $rsp3['kode'];
                $kode_baru3 = $kdbr3['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now3 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp3[kode]' limit 1");
                $kdbr_now3 = mysqli_fetch_array($KodeBaru_now3);
                if($kdbr_now3['code'] && $kdbr_now3['code_new']){
                    $kode_lama3 = $kdbr_now3['code'];
                    $kode_baru3 = $kdbr_now3['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama3 = $rsp3['kode'];
                    $kode_baru3 = $kdbr3['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama3; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru3; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc1']) != 0) echo floatval($rsp3['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc2']) != 0) echo floatval($rsp3['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc3']) != 0) echo floatval($rsp3['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc4']) != 0) echo floatval($rsp3['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc5']) != 0) echo floatval($rsp3['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc6']) != 0) echo floatval($rsp3['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc7']) != 0) echo floatval($rsp3['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp3['conc8']) != 0) echo floatval($rsp3['conc8']) ?></td>
        </tr>
        <?php
            $resep4 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 4 
                                                                order by flag asc limit 1");
            $rsp4 = mysqli_fetch_array($resep4);
                    
            $KodeBaru4 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp4[kode]'
                                                                limit 1");
            $kdbr4 = mysqli_fetch_array($KodeBaru4);	
            
            if($kdbr4['code_new']){ 
                $kode_lama4 = $rsp4['kode'];
                $kode_baru4 = $kdbr4['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now4 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp4[kode]' limit 1");
                $kdbr_now4 = mysqli_fetch_array($KodeBaru_now4);
                if($kdbr_now4['code'] && $kdbr_now4['code_new']){
                    $kode_lama4 = $kdbr_now4['code'];
                    $kode_baru4 = $kdbr_now4['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama4 = $rsp4['kode'];
                    $kode_baru4 = $kdbr4['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama4; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru4; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc1']) != 0) echo floatval($rsp4['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc2']) != 0) echo floatval($rsp4['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc3']) != 0) echo floatval($rsp4['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc4']) != 0) echo floatval($rsp4['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc5']) != 0) echo floatval($rsp4['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc6']) != 0) echo floatval($rsp4['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc7']) != 0) echo floatval($rsp4['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp4['conc8']) != 0) echo floatval($rsp4['conc8']) ?></td>
        </tr>
        <?php
            $resep5 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 5 
                                                                order by flag asc limit 1");
            $rsp5 = mysqli_fetch_array($resep5);
                    
            $KodeBaru5 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp5[kode]'
                                                                limit 1");
            $kdbr5 = mysqli_fetch_array($KodeBaru5);	
            
            if($kdbr5['code_new']){ 
                $kode_lama5 = $rsp5['kode'];
                $kode_baru5 = $kdbr5['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now5 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp5[kode]' limit 1");
                $kdbr_now5 = mysqli_fetch_array($KodeBaru_now5);
                if($kdbr_now5['code'] && $kdbr_now5['code_new']){
                    $kode_lama5 = $kdbr_now5['code'];
                    $kode_baru5 = $kdbr_now5['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama5 = $rsp5['kode'];
                    $kode_baru5 = $kdbr5['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama5; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru5; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc1']) != 0) echo floatval($rsp5['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc2']) != 0) echo floatval($rsp5['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc3']) != 0) echo floatval($rsp5['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc4']) != 0) echo floatval($rsp5['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc5']) != 0) echo floatval($rsp5['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc6']) != 0) echo floatval($rsp5['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc7']) != 0) echo floatval($rsp5['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp5['conc8']) != 0) echo floatval($rsp5['conc8']) ?></td>
        </tr>
        <?php
            $resep6 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 6 
                                                                order by flag asc limit 1");
            $rsp6 = mysqli_fetch_array($resep6);
                    
            $KodeBaru6 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp6[kode]'
                                                                limit 1");
            $kdbr6 = mysqli_fetch_array($KodeBaru6);
            
            if($kdbr6['code_new']){ 
                $kode_lama6 = $rsp6['kode'];
                $kode_baru6 = $kdbr6['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now6 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp6[kode]' limit 1");
                $kdbr_now6 = mysqli_fetch_array($KodeBaru_now6);
                if($kdbr_now6['code'] && $kdbr_now6['code_new']){
                    $kode_lama6 = $kdbr_now6['code'];
                    $kode_baru6 = $kdbr_now6['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama6 = $rsp6['kode'];
                    $kode_baru6 = $kdbr6['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama6; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru6; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc1']) != 0) echo floatval($rsp6['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc2']) != 0) echo floatval($rsp6['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc3']) != 0) echo floatval($rsp6['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc4']) != 0) echo floatval($rsp6['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc5']) != 0) echo floatval($rsp6['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc6']) != 0) echo floatval($rsp6['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc7']) != 0) echo floatval($rsp6['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp6['conc8']) != 0) echo floatval($rsp6['conc8']) ?></td>
        </tr>
        <?php
            $resep7 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 7 
                                                                order by flag asc limit 1");
            $rsp7 = mysqli_fetch_array($resep7);
                    
            $KodeBaru7 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp7[kode]'
                                                                limit 1");
            $kdbr7 = mysqli_fetch_array($KodeBaru7);		

            if($kdbr7['code_new']){ 
                $kode_lama7 = $rsp7['kode'];
                $kode_baru7 = $kdbr7['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now7 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp7[kode]' limit 1");
                $kdbr_now7 = mysqli_fetch_array($KodeBaru_now7);
                if($kdbr_now7['code'] && $kdbr_now7['code_new']){
                    $kode_lama7 = $kdbr_now7['code'];
                    $kode_baru7 = $kdbr_now7['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama7 = $rsp7['kode'];
                    $kode_baru7 = $kdbr7['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama7; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru7; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc1']) != 0) echo floatval($rsp7['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc2']) != 0) echo floatval($rsp7['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc3']) != 0) echo floatval($rsp7['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc4']) != 0) echo floatval($rsp7['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc5']) != 0) echo floatval($rsp7['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc6']) != 0) echo floatval($rsp7['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc7']) != 0) echo floatval($rsp7['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp7['conc8']) != 0) echo floatval($rsp7['conc8']) ?></td>
        </tr>
        <?php
            $resep8 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 8 
                                                                order by flag asc limit 1");
            $rsp8 = mysqli_fetch_array($resep8);
                    
            $KodeBaru8 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp8[kode]'
                                                                limit 1");
            $kdbr8 = mysqli_fetch_array($KodeBaru8);	
            
            if($kdbr8['code_new']){ 
                $kode_lama8 = $rsp8['kode'];
                $kode_baru8 = $kdbr8['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now8 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp8[kode]' limit 1");
                $kdbr_now8 = mysqli_fetch_array($KodeBaru_now8);
                if($kdbr_now8['code'] && $kdbr_now8['code_new']){
                    $kode_lama8 = $kdbr_now8['code'];
                    $kode_baru8 = $kdbr_now8['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama8 = $rsp8['kode'];
                    $kode_baru8 = $kdbr8['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama8; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru8; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc1']) != 0) echo floatval($rsp8['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc2']) != 0) echo floatval($rsp8['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc3']) != 0) echo floatval($rsp8['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc4']) != 0) echo floatval($rsp8['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc5']) != 0) echo floatval($rsp8['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc6']) != 0) echo floatval($rsp8['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc7']) != 0) echo floatval($rsp8['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp8['conc8']) != 0) echo floatval($rsp8['conc8']) ?></td>
            <td style="font-weight: bold;">Colorist 1 : <?php echo $data['colorist1'] ?></td>
            <td style="font-weight: bold;">Colorist 2 : <?php echo $data['colorist2'] ?></td>
        </tr>
        <?php
            $resep9 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 9 
                                                                order by flag asc limit 1");
            $rsp9 = mysqli_fetch_array($resep9);
                    
            $KodeBaru9 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp9[kode]'
                                                                limit 1");
            $kdbr9 = mysqli_fetch_array($KodeBaru9);	
            
            if($kdbr9['code_new']){ 
                $kode_lama9 = $rsp9['kode'];
                $kode_baru9 = $kdbr9['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now9 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp9[kode]' limit 1");
                $kdbr_now9 = mysqli_fetch_array($KodeBaru_now9);
                if($kdbr_now9['code'] && $kdbr_now9['code_new']){
                    $kode_lama9 = $kdbr_now9['code'];
                    $kode_baru9 = $kdbr_now9['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama9 = $rsp9['kode'];
                    $kode_baru9 = $kdbr9['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama9; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru9; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc1']) != 0) echo floatval($rsp9['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc2']) != 0) echo floatval($rsp9['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc3']) != 0) echo floatval($rsp9['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc4']) != 0) echo floatval($rsp9['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc5']) != 0) echo floatval($rsp9['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc6']) != 0) echo floatval($rsp9['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc7']) != 0) echo floatval($rsp9['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp9['conc8']) != 0) echo floatval($rsp9['conc8']) ?></td>
            <td colspan="2" rowspan="2" align="center"><strong>LAB. SAMPLE</strong></td>
        </tr>
        <?php
            $resep10 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 10 
                                                                order by flag asc limit 1");
            $rsp10 = mysqli_fetch_array($resep10);
                    
            $KodeBaru10 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp10[kode]'
                                                                limit 1");
            $kdbr10 = mysqli_fetch_array($KodeBaru10);
            
            if($kdbr10['code_new']){ 
                $kode_lama10 = $rsp10['kode'];
                $kode_baru10 = $kdbr10['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now10 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp10[kode]' limit 1");
                $kdbr_now10 = mysqli_fetch_array($KodeBaru_now10);
                if($kdbr_now10['code'] && $kdbr_now10['code_new']){
                    $kode_lama10 = $kdbr_now10['code'];
                    $kode_baru10 = $kdbr_now10['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama10 = $rsp10['kode'];
                    $kode_baru10 = $kdbr10['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama10; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru10; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc1']) != 0) echo floatval($rsp10['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc2']) != 0) echo floatval($rsp10['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc3']) != 0) echo floatval($rsp10['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc4']) != 0) echo floatval($rsp10['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc5']) != 0) echo floatval($rsp10['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc6']) != 0) echo floatval($rsp10['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc7']) != 0) echo floatval($rsp10['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp10['conc8']) != 0) echo floatval($rsp10['conc8']) ?></td>
        </tr>
        <?php
            $resep11 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 11 
                                                                order by flag asc limit 1");
            $rsp11 = mysqli_fetch_array($resep11);
                    
            $KodeBaru11 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp11[kode]'
                                                                limit 1");
            $kdbr11 = mysqli_fetch_array($KodeBaru11);		

            if($kdbr11['code_new']){ 
                $kode_lama11 = $rsp11['kode'];
                $kode_baru11 = $kdbr11['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now11 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp11[kode]' limit 1");
                $kdbr_now11 = mysqli_fetch_array($KodeBaru_now11);
                if($kdbr_now11['code'] && $kdbr_now11['code_new']){
                    $kode_lama11 = $kdbr_now11['code'];
                    $kode_baru11 = $kdbr_now11['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama11 = $rsp11['kode'];
                    $kode_baru11 = $kdbr11['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama11; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru11; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc1']) != 0) echo floatval($rsp11['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc2']) != 0) echo floatval($rsp11['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc3']) != 0) echo floatval($rsp11['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc4']) != 0) echo floatval($rsp11['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc5']) != 0) echo floatval($rsp11['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc6']) != 0) echo floatval($rsp11['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc7']) != 0) echo floatval($rsp11['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp11['conc8']) != 0) echo floatval($rsp11['conc8']) ?></td>
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
            
            $KodeBaru12 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp12[kode]'
                                                                limit 1");
            $kdbr12 = mysqli_fetch_array($KodeBaru12);

            if($kdbr12['code_new']){ 
                $kode_lama12 = $rsp12['kode'];
                $kode_baru12 = $kdbr12['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now12 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp12[kode]' limit 1");
                $kdbr_now12 = mysqli_fetch_array($KodeBaru_now12);
                if($kdbr_now12['code'] && $kdbr_now12['code_new']){
                    $kode_lama12 = $kdbr_now12['code'];
                    $kode_baru12 = $kdbr_now12['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama12 = $rsp12['kode'];
                    $kode_baru12 = $kdbr12['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama12; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru12; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc1']) != 0) echo floatval($rsp12['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc2']) != 0) echo floatval($rsp12['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc3']) != 0) echo floatval($rsp12['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc4']) != 0) echo floatval($rsp12['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc5']) != 0) echo floatval($rsp12['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc6']) != 0) echo floatval($rsp12['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc7']) != 0) echo floatval($rsp12['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp12['conc8']) != 0) echo floatval($rsp12['conc8']) ?></td>
        </tr>
        <?php
            $resep13 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 13 
                                                                order by flag asc limit 1");
            $rsp13 = mysqli_fetch_array($resep13);
                    
            $KodeBaru13 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp13[kode]'
                                                                limit 1");
            $kdbr13 = mysqli_fetch_array($KodeBaru13);

            if($kdbr13['code_new']){ 
                $kode_lama13 = $rsp13['kode'];
                $kode_baru13 = $kdbr13['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now13 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp13[kode]' limit 1");
                $kdbr_now13 = mysqli_fetch_array($KodeBaru_now13);
                if($kdbr_now13['code'] && $kdbr_now13['code_new']){
                    $kode_lama13 = $kdbr_now13['code'];
                    $kode_baru13 = $kdbr_now13['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama13 = $rsp13['kode'];
                    $kode_baru13 = $kdbr13['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama13; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru13; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc1']) != 0) echo floatval($rsp13['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc2']) != 0) echo floatval($rsp13['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc3']) != 0) echo floatval($rsp13['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc4']) != 0) echo floatval($rsp13['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc5']) != 0) echo floatval($rsp13['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc6']) != 0) echo floatval($rsp13['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc7']) != 0) echo floatval($rsp13['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp13['conc8']) != 0) echo floatval($rsp13['conc8']) ?></td>
        </tr>
        <?php
            $resep14 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 14 
                                                                order by flag asc limit 1");
            $rsp14 = mysqli_fetch_array($resep14);
                    
            $KodeBaru14 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp14[kode]'
                                                                limit 1");
            $kdbr14 = mysqli_fetch_array($KodeBaru14);

            if($kdbr14['code_new']){ 
                $kode_lama14 = $rsp14['kode'];
                $kode_baru14 = $kdbr14['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now14 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp14[kode]' limit 1");
                $kdbr_now14 = mysqli_fetch_array($KodeBaru_now14);
                if($kdbr_now14['code'] && $kdbr_now14['code_new']){
                    $kode_lama14 = $kdbr_now14['code'];
                    $kode_baru14 = $kdbr_now14['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama14 = $rsp14['kode'];
                    $kode_baru14 = $kdbr14['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama14; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru14; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc1']) != 0) echo floatval($rsp14['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc2']) != 0) echo floatval($rsp14['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc3']) != 0) echo floatval($rsp14['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc4']) != 0) echo floatval($rsp14['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc5']) != 0) echo floatval($rsp14['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc6']) != 0) echo floatval($rsp14['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc7']) != 0) echo floatval($rsp14['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp14['conc8']) != 0) echo floatval($rsp14['conc8']) ?></td>
        </tr>
        <?php
            $resep15 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 15 
                                                                order by flag asc limit 1");
            $rsp15 = mysqli_fetch_array($resep15);
                    
            $KodeBaru15 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp15[kode]'
                                                                limit 1");
            $kdbr15 = mysqli_fetch_array($KodeBaru15);

            if($kdbr15['code_new']){ 
                $kode_lama15 = $rsp15['kode'];
                $kode_baru15 = $kdbr15['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now15 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp15[kode]' limit 1");
                $kdbr_now15 = mysqli_fetch_array($KodeBaru_now15);
                if($kdbr_now15['code'] && $kdbr_now15['code_new']){
                    $kode_lama15 = $kdbr_now15['code'];
                    $kode_baru15 = $kdbr_now15['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama15 = $rsp15['kode'];
                    $kode_baru15 = $kdbr15['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama15; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru15; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc1']) != 0) echo floatval($rsp15['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc2']) != 0) echo floatval($rsp15['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc3']) != 0) echo floatval($rsp15['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc4']) != 0) echo floatval($rsp15['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc5']) != 0) echo floatval($rsp15['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc6']) != 0) echo floatval($rsp15['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc7']) != 0) echo floatval($rsp15['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp15['conc8']) != 0) echo floatval($rsp15['conc8']) ?></td>
        </tr>
        <?php
            $resep16 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 16 
                                                                order by flag asc limit 1");
            $rsp16 = mysqli_fetch_array($resep16);
                    
            $KodeBaru16 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp16[kode]'
                                                                limit 1");
            $kdbr16 = mysqli_fetch_array($KodeBaru16);	
            
            if($kdbr16['code_new']){ 
                $kode_lama16 = $rsp16['kode'];
                $kode_baru16 = $kdbr16['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now16 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp16[kode]' limit 1");
                $kdbr_now16 = mysqli_fetch_array($KodeBaru_now16);
                if($kdbr_now16['code'] && $kdbr_now16['code_new']){
                    $kode_lama16 = $kdbr_now16['code'];
                    $kode_baru16 = $kdbr_now16['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama16 = $rsp16['kode'];
                    $kode_baru16 = $kdbr16['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama16; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru16; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc1']) != 0) echo floatval($rsp16['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc2']) != 0) echo floatval($rsp16['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc3']) != 0) echo floatval($rsp16['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc4']) != 0) echo floatval($rsp16['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc5']) != 0) echo floatval($rsp16['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc6']) != 0) echo floatval($rsp16['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc7']) != 0) echo floatval($rsp16['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp16['conc8']) != 0) echo floatval($rsp16['conc8']) ?></td>
        </tr>
        <?php
            $resep17 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 17 
                                                                order by flag asc limit 1");
            $rsp17 = mysqli_fetch_array($resep17);
                    
            $KodeBaru17 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp17[kode]'
                                                                limit 1");
            $kdbr17 = mysqli_fetch_array($KodeBaru17);
            
            if($kdbr17['code_new']){ 
                $kode_lama17 = $rsp17['kode'];
                $kode_baru17 = $kdbr17['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now17 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp17[kode]' limit 1");
                $kdbr_now17 = mysqli_fetch_array($KodeBaru_now17);
                if($kdbr_now17['code'] && $kdbr_now17['code_new']){
                    $kode_lama17 = $kdbr_now17['code'];
                    $kode_baru17 = $kdbr_now17['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama17 = $rsp17['kode'];
                    $kode_baru17 = $kdbr17['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama17; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru17; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc1']) != 0) echo floatval($rsp17['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc2']) != 0) echo floatval($rsp17['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc3']) != 0) echo floatval($rsp17['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc4']) != 0) echo floatval($rsp17['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc5']) != 0) echo floatval($rsp17['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc6']) != 0) echo floatval($rsp17['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc7']) != 0) echo floatval($rsp17['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp17['conc8']) != 0) echo floatval($rsp17['conc8']) ?></td>
        </tr>
        <?php
            $resep18 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 18 
                                                                order by flag asc limit 1");
            $rsp18 = mysqli_fetch_array($resep18);
                    
            $KodeBaru18 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp18[kode]'
                                                                limit 1");
            $kdbr18 = mysqli_fetch_array($KodeBaru18);
            
            if($kdbr18['code_new']){ 
                $kode_lama18 = $rsp18['kode'];
                $kode_baru18 = $kdbr18['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now18 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp18[kode]' limit 1");
                $kdbr_now18 = mysqli_fetch_array($KodeBaru_now18);
                if($kdbr_now18['code'] && $kdbr_now18['code_new']){
                    $kode_lama18 = $kdbr_now18['code'];
                    $kode_baru18 = $kdbr_now18['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama18 = $rsp18['kode'];
                    $kode_baru18 = $kdbr18['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama18; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru18; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc1']) != 0) echo floatval($rsp18['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc2']) != 0) echo floatval($rsp18['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc3']) != 0) echo floatval($rsp18['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc4']) != 0) echo floatval($rsp18['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc5']) != 0) echo floatval($rsp18['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc6']) != 0) echo floatval($rsp18['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc7']) != 0) echo floatval($rsp18['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp18['conc8']) != 0) echo floatval($rsp18['conc8']) ?></td>
        </tr>
        <?php
            $resep19 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 19 
                                                                order by flag asc limit 1");
            $rsp19 = mysqli_fetch_array($resep19);
                    
            $KodeBaru19 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp19[kode]'
                                                                limit 1");
            $kdbr19 = mysqli_fetch_array($KodeBaru19);

            if($kdbr19['code_new']){ 
                $kode_lama19 = $rsp19['kode'];
                $kode_baru19 = $kdbr19['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now19 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp19[kode]' limit 1");
                $kdbr_now19 = mysqli_fetch_array($KodeBaru_now19);
                if($kdbr_now19['code'] && $kdbr_now19['code_new']){
                    $kode_lama19 = $kdbr_now19['code'];
                    $kode_baru19 = $kdbr_now19['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama19 = $rsp19['kode'];
                    $kode_baru19 = $kdbr19['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama19; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru19; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc1']) != 0) echo floatval($rsp19['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc2']) != 0) echo floatval($rsp19['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc3']) != 0) echo floatval($rsp19['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc4']) != 0) echo floatval($rsp19['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc5']) != 0) echo floatval($rsp19['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc6']) != 0) echo floatval($rsp19['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc7']) != 0) echo floatval($rsp19['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp19['conc8']) != 0) echo floatval($rsp19['conc8']) ?></td>
        </tr>
        <?php
            $resep20 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 20 
                                                                order by flag asc limit 1");
            $rsp20 = mysqli_fetch_array($resep20);
                    
            $KodeBaru20 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp20[kode]'
                                                                limit 1");
            $kdbr20 = mysqli_fetch_array($KodeBaru20);
            
            if($kdbr20['code_new']){ 
                $kode_lama20 = $rsp20['kode'];
                $kode_baru20 = $kdbr20['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now20 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp20[kode]' limit 1");
                $kdbr_now20 = mysqli_fetch_array($KodeBaru_now20);
                if($kdbr_now20['code'] && $kdbr_now20['code_new']){
                    $kode_lama20 = $kdbr_now20['code'];
                    $kode_baru20 = $kdbr_now20['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama20 = $rsp20['kode'];
                    $kode_baru20 = $kdbr20['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama20; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru20; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc1']) != 0) echo floatval($rsp20['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc2']) != 0) echo floatval($rsp20['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc3']) != 0) echo floatval($rsp20['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc4']) != 0) echo floatval($rsp20['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc5']) != 0) echo floatval($rsp20['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc6']) != 0) echo floatval($rsp20['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc7']) != 0) echo floatval($rsp20['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp20['conc8']) != 0) echo floatval($rsp20['conc8']) ?></td>
        </tr>
        <?php
            $resep21 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 21 
                                                                order by flag asc limit 1");
            $rsp21 = mysqli_fetch_array($resep21);
            
            $KodeBaru21 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp21[kode]'
                                                                limit 1");
            $kdbr21 = mysqli_fetch_array($KodeBaru21);

            if($kdbr21['code_new']){ 
                $kode_lama21 = $rsp21['kode'];
                $kode_baru21 = $kdbr21['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now21 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp21[kode]' limit 1");
                $kdbr_now21 = mysqli_fetch_array($KodeBaru_now21);
                if($kdbr_now21['code'] && $kdbr_now21['code_new']){
                    $kode_lama21 = $kdbr_now21['code'];
                    $kode_baru21 = $kdbr_now21['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama21 = $rsp21['kode'];
                    $kode_baru21 = $kdbr21['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama21; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru21; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc1']) != 0) echo floatval($rsp21['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc2']) != 0) echo floatval($rsp21['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc3']) != 0) echo floatval($rsp21['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc4']) != 0) echo floatval($rsp21['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc5']) != 0) echo floatval($rsp21['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc6']) != 0) echo floatval($rsp21['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc7']) != 0) echo floatval($rsp21['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp21['conc8']) != 0) echo floatval($rsp21['conc8']) ?></td>
            <td width="20%" rowspan="2" align="center"><strong>BEFORE SOAPING</strong></td>
            <td width="21%" rowspan="2" align="center"><strong>T-SIDE</strong></td>
        </tr>
        <?php
            $resep22 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 22 
                                                                order by flag asc limit 1");
            $rsp22 = mysqli_fetch_array($resep22);
                    
            $KodeBaru22 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp22[kode]'
                                                                limit 1");
            $kdbr22 = mysqli_fetch_array($KodeBaru22);
            
            if($kdbr22['code_new']){ 
                $kode_lama22 = $rsp22['kode'];
                $kode_baru22 = $kdbr22['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now22 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp22[kode]' limit 1");
                $kdbr_now22 = mysqli_fetch_array($KodeBaru_now22);
                if($kdbr_now22['code'] && $kdbr_now22['code_new']){
                    $kode_lama22 = $kdbr_now22['code'];
                    $kode_baru22 = $kdbr_now22['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama22 = $rsp22['kode'];
                    $kode_baru22 = $kdbr22['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama22; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru22; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc1']) != 0) echo floatval($rsp22['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc2']) != 0) echo floatval($rsp22['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc3']) != 0) echo floatval($rsp22['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc4']) != 0) echo floatval($rsp22['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc5']) != 0) echo floatval($rsp22['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc6']) != 0) echo floatval($rsp22['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc7']) != 0) echo floatval($rsp22['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp22['conc8']) != 0) echo floatval($rsp22['conc8']) ?></td>
        </tr>
        <?php
            $resep23 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 23 
                                                                order by flag asc limit 1");
            $rsp23 = mysqli_fetch_array($resep23);
            
            $KodeBaru23 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp23[kode]'
                                                                limit 1");
            $kdbr23 = mysqli_fetch_array($KodeBaru23);
            
            if($kdbr23['code_new']){ 
                $kode_lama23 = $rsp23['kode'];
                $kode_baru23 = $kdbr23['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now23 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp23[kode]' limit 1");
                $kdbr_now23 = mysqli_fetch_array($KodeBaru_now23);
                if($kdbr_now23['code'] && $kdbr_now23['code_new']){
                    $kode_lama23 = $kdbr_now23['code'];
                    $kode_baru23 = $kdbr_now23['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama23 = $rsp23['kode'];
                    $kode_baru23 = $kdbr23['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama23; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru23; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc1']) != 0) echo floatval($rsp23['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc2']) != 0) echo floatval($rsp23['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc3']) != 0) echo floatval($rsp23['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc4']) != 0) echo floatval($rsp23['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc5']) != 0) echo floatval($rsp23['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc6']) != 0) echo floatval($rsp23['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc7']) != 0) echo floatval($rsp23['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp23['conc8']) != 0) echo floatval($rsp23['conc8']) ?></td>
            <td rowspan="7">&nbsp;</td>
            <td rowspan="7">&nbsp;</td>
        </tr>
        <?php
            $resep24 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 24 
                                                                order by flag asc limit 1");
            $rsp24 = mysqli_fetch_array($resep24);
            
            $KodeBaru24 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp24[kode]'
                                                                limit 1");
            $kdbr24 = mysqli_fetch_array($KodeBaru24);
            
            if($kdbr24['code_new']){ 
                $kode_lama24 = $rsp24['kode'];
                $kode_baru24 = $kdbr24['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now24 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp24[kode]' limit 1");
                $kdbr_now24 = mysqli_fetch_array($KodeBaru_now24);
                if($kdbr_now24['code'] && $kdbr_now24['code_new']){
                    $kode_lama24 = $kdbr_now24['code'];
                    $kode_baru24 = $kdbr_now24['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama24 = $rsp24['kode'];
                    $kode_baru24 = $kdbr24['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama24; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru24; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc1']) != 0) echo floatval($rsp24['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc2']) != 0) echo floatval($rsp24['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc3']) != 0) echo floatval($rsp24['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc4']) != 0) echo floatval($rsp24['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc5']) != 0) echo floatval($rsp24['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc6']) != 0) echo floatval($rsp24['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc7']) != 0) echo floatval($rsp24['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp24['conc8']) != 0) echo floatval($rsp24['conc8']) ?></td>
        </tr>
        <?php
            $resep25 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 25 
                                                                order by flag asc limit 1");
            $rsp25 = mysqli_fetch_array($resep25);
            
            $KodeBaru25 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp25[kode]'
                                                                limit 1");
            $kdbr25 = mysqli_fetch_array($KodeBaru25);
            
            if($kdbr25['code_new']){ 
                $kode_lama25 = $rsp25['kode'];
                $kode_baru25 = $kdbr25['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now25 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp25[kode]' limit 1");
                $kdbr_now25 = mysqli_fetch_array($KodeBaru_now25);
                if($kdbr_now25['code'] && $kdbr_now25['code_new']){
                    $kode_lama25 = $kdbr_now25['code'];
                    $kode_baru25 = $kdbr_now25['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama25 = $rsp25['kode'];
                    $kode_baru25 = $kdbr25['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama25; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru25; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc1']) != 0) echo floatval($rsp25['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc2']) != 0) echo floatval($rsp25['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc3']) != 0) echo floatval($rsp25['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc4']) != 0) echo floatval($rsp25['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc5']) != 0) echo floatval($rsp25['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc6']) != 0) echo floatval($rsp25['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc7']) != 0) echo floatval($rsp25['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp25['conc8']) != 0) echo floatval($rsp25['conc8']) ?></td>
        </tr>
        <?php
            $resep26 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 26 
                                                                order by flag asc limit 1");
            $rsp26 = mysqli_fetch_array($resep26);
            
            $KodeBaru26 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp26[kode]'
                                                                limit 1");
            $kdbr26 = mysqli_fetch_array($KodeBaru26);

            if($kdbr26['code_new']){ 
                $kode_lama26 = $rsp26['kode'];
                $kode_baru26 = $kdbr26['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now26 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp26[kode]' limit 1");
                $kdbr_now26 = mysqli_fetch_array($KodeBaru_now26);
                if($kdbr_now26['code'] && $kdbr_now26['code_new']){
                    $kode_lama26 = $kdbr_now26['code'];
                    $kode_baru26 = $kdbr_now26['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama26 = $rsp26['kode'];
                    $kode_baru26 = $kdbr26['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama26; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru26; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc1']) != 0) echo floatval($rsp26['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc2']) != 0) echo floatval($rsp26['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc3']) != 0) echo floatval($rsp26['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc4']) != 0) echo floatval($rsp26['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc5']) != 0) echo floatval($rsp26['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc6']) != 0) echo floatval($rsp26['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc7']) != 0) echo floatval($rsp26['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp26['conc8']) != 0) echo floatval($rsp26['conc8']) ?></td>
        </tr>
        <?php
            $resep27 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                and id_status = '$data[id_status]' 
                                                                and flag = 27 
                                                                order by flag asc limit 1");
            $rsp27 = mysqli_fetch_array($resep27);
                    
            $KodeBaru27 = mysqli_query($con,"SELECT code_new FROM tbl_dyestuff where code = '$rsp27[kode]'
                                                                limit 1");
            $kdbr27 = mysqli_fetch_array($KodeBaru27);

            if($kdbr27['code_new']){ 
                $kode_lama27 = $rsp27['kode'];
                $kode_baru27 = $kdbr27['code_new'];
            // JIKA KODE BARU MASUK KE KODE LAMA, PENCARIAN BERDASARKAN KODE LAMA
            }else{
                $KodeBaru_now27 = mysqli_query($con,"SELECT `code`, code_new FROM tbl_dyestuff where code_new = '$rsp27[kode]' limit 1");
                $kdbr_now27 = mysqli_fetch_array($KodeBaru_now27);
                if($kdbr_now27['code'] && $kdbr_now27['code_new']){
                    $kode_lama27 = $kdbr_now27['code'];
                    $kode_baru27 = $kdbr_now27['code_new'];
                // JIKA KODE BARU KOSONG
                }else{
                    $kode_lama27 = $rsp27['kode'];
                    $kode_baru27 = $kdbr27['code_new'];
                }
            }
        ?>
        <tr style="height: 0.2in" class="flag">
            <td style="font-weight: bold;"><?php echo $kode_lama27; ?></td>
            <td style="font-weight: bold;"><?php echo $kode_baru27; ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc1']) != 0) echo floatval($rsp27['conc1']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc2']) != 0) echo floatval($rsp27['conc2']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc3']) != 0) echo floatval($rsp27['conc3']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc4']) != 0) echo floatval($rsp27['conc4']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc5']) != 0) echo floatval($rsp27['conc5']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc6']) != 0) echo floatval($rsp27['conc6']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc7']) != 0) echo floatval($rsp27['conc7']) ?></td>
            <td style="font-weight: bold;"><?php if (floatval($rsp27['conc8']) != 0) echo floatval($rsp27['conc8']) ?></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="4">&nbsp;</td>
            <td colspan="3" align="center">T-SIDE</td>
            <td colspan="3" align="center">C.SIDE</td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="4" align="center">Temp x Time</td>
            <td colspan="3" align="center"><?php echo ($data['tside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['tside_min']) ?> min</td>
            <td colspan="3" align="center"><?php echo ($data['cside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['cside_min']) ?> min</td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="4" align="center">L:R</td>
            <td colspan="3" align="center"><?php echo $data['lr'] ?></td>
            <td colspan="3" align="center"><?php echo $data['second_lr'] ?></td>
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
            <td colspan="4" align="center">pH</td>
            <td colspan="3" align="center">&nbsp; <?php echo $data['ph'] ?></td>
            <td colspan="3">&nbsp;</td>
            <td style="font-weight: bold;">CIE WI &nbsp;&nbsp;: <?php echo number_format($data['cie_wi'], 2); ?></td>
            <td style="font-weight: bold;">CIE TINT : <?php echo number_format($data['cie_tint'], 2); ?></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="4" align="center">RC</td>
            <td colspan="3" align="center"><?php echo ($data['rc_sh']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['rc_tm']) ?> min</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="2" align="center"><strong>GREIGE</strong></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="4" align="center">Bleaching</td>
            <td colspan="3" align="center"><?php echo ($data['bleaching_sh']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['bleaching_tm']) ?> min</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="2" rowspan="4" valign="top">Info Dyeing : <?php echo $data['remark_dye'] ?></td>
        </tr>
        <tr style="height: 0.4in">
            <td colspan="4" align="center">Soaping</td>
            <td colspan="3" align="center">&nbsp;</td>
            <td colspan="3" align="center"><?php echo ($data['soaping_sh']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['soaping_tm']) ?> min</td>

        </tr>
        <tr style="height: 0.4in">
            <td colspan="4" align="center">BEFORE RC / Bleaching</td>
            <td colspan="6" align="center">AFTER RC / Bleaching</td>
        </tr>
        <tr style="height: 1.5in">
            <td colspan="4">&nbsp;</td>
            <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 0.2in" colspan="4">&nbsp;</td>
            <td colspan="3" align="center" style="height: 0.2in"><strong>Matcher</strong></td>
            <td style="height: 0.2in" colspan="3" align="center"> <strong>Buka Resep</strong></td>
            <td style="height: 0.2in" colspan="2" align="center"> <strong>Approved</strong></td>
        </tr>
        <tr>
            <td style="height: 0.2in" colspan="4">Nama</td>
            <td colspan="3" align="center" style="height: 0.2in"><?php echo $data['final_matcher'] ?></td>
            <td style="height: 0.2in" colspan="3" align="center"><?php echo $data['selesai_by'] ?></td>
            <td style="height: 0.2in" colspan="2" align="center"><?php echo $data['approve_by'] ?></td>
        </tr>
        <tr>
            <td style="height: 0.2in" colspan="4">Tanggal</td>
            <td colspan="3" align="center" style="height: 0.2in"><?php echo $data['selesai_at'] ?></td>
            <td style="height: 0.2in" colspan="3" align="center"><?php echo $data['selesai_at'] ?></td>
            <td style="height: 0.2in" colspan="2" align="center"><?php echo $data['approve_at'] ?></td>
        </tr>
        <tr>
            <td style="height: 0.4in" colspan="4">TTD</td>
            <td colspan="3" align="center" style="height: 0.4in">&nbsp;</td>
            <td style="height: 0.4in" colspan="3" align="center"><?php echo $data['selesai_by'] ?></td>
            <td style="height: 0.4in" colspan="2" align="center"><?php echo $data['approve_by'] ?></td>
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