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
    <title>Cetak Form Tempelan Laborat</title>.
    <?php if($_GET['frm'] == 'bresep') : ?>
        <script src="../../bower_components/DataTable/jQuery-3.3.1/jQuery-3.3.1.min.js"></script>
        <link href="../../bower_components/sweet-alert/dist/sweetalert2.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="../../bower_components/sweet-alert/dist/sweetalert2.min.js"></script>
    <?php endif; ?>
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
        /* page-break-before: always; */
        /* page-break-inside: avoid; */
        /* font-size: 8pt !important; */
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

<?php if($_GET['frm'] == 'bresep') : ?>
    <style>
        table {
        border-collapse: collapse;
        }
        td {
        border: 1px solid #333;
        padding: 20px;
        position: relative;
        }
        .comment {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #555;
        white-space: pre-wrap; /* supaya line break terlihat */
        }
        #contextMenu {
        display: none;
        position: absolute;
        z-index: 1000;
        background: #fff;
        border: 1px solid #ccc;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }
        #contextMenu ul {
        list-style: none;
        padding: 0;
        margin: 0;
        }
        #contextMenu li {
        padding: 8px 12px;
        cursor: pointer;
        }
        #contextMenu li:hover {
        background: #eee;
        }
        /* Modal */
        #commentModal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        }
        #commentModalContent {
        background: #fff;
        padding: 20px;
        width: 400px;
        margin: 100px auto;
        border-radius: 5px;
        }
        #commentModalContent textarea {
        width: 100%;
        height: 150px;
        }
        #commentModalContent button {
        margin-top: 10px;
        padding: 5px 15px;
        }
    </style>
    <style>
        .tooltip-wrapper {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .tooltip-text {
            visibility: hidden;
            width: max-content;
            max-width: 300px;
            background-color: #333;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 6px 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* bisa diatur sesuai posisi tooltip */
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            white-space: pre-line;
            font-size: 0.75rem;
        }

        .tooltip-wrapper:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

    </style>
    <style>
        #tooltipContainer {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            padding: 10px;
            max-width: 800px;
            max-height: 500px;
            overflow: auto;
            box-shadow: 0 0 16px rgba(0,0,0,0.25); /* Sedikit lebih tajam bayangannya */
            z-index: 1000;
            display: none;
            border-radius: 6px;   /* Biar sedikit lebih halus */
        }

        .mini-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        .mini-table th, .mini-table td {
            padding: 6px 8px;
            border: 1px solid #ccc;
        }
    </style>
    <style>
        .stamp-box-suhuchamber {
            border: 4px solid #007bff;
            border-radius: 8px;
            width: fit-content; /* Biar panjangnya otomatis */
            min-width: 250px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #007bff;
            font-weight: bold;
            font-size: 16px;
            font-family: 'Courier New', Courier, monospace; /* gaya cap */
            letter-spacing: 1px;
            text-shadow: 0 0 2px #007bff;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            background-color: white;
            transform: rotate(-2deg); /* sedikit miring */
        }
        
        .stamp-box-warnafluorescent {
            border: 4px solid #dc3545; /* Merah tua */
            border-radius: 8px;
            width: fit-content; /* Biar panjangnya otomatis */
            min-width: 250px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc3545;
            font-weight: bold;
            font-size: 16px;
            font-family: 'Courier New', Courier, monospace; /* gaya cap */
            letter-spacing: 1px;
            text-shadow: 0 0 2px #dc3545;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            background-color: white;
            transform: rotate(-2deg); /* sedikit miring */
        }
    </style>
<?php endif; ?>

<body>
    <?php
        $qry = mysqli_query($con,"SELECT * , a.id as id_status, b.id as id_matching, 
                                    SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 1), ' ', -1) as recipe_code_1, 
	                                SUBSTRING_INDEX(SUBSTRING_INDEX(b.recipe_code, ' ', 2), ' ', -1) as recipe_code_2
                                from db_laborat.tbl_status_matching a 
                                join db_laborat.tbl_matching b on a.idm = b.no_resep 
                                where a.id = '$ids'
                                ORDER BY a.id desc limit 1");
        $data = mysqli_fetch_array($qry);
    ?>
    <?php
        if (substr(strtoupper($data['no_resep']), 0, 2) == 'R2' or substr(strtoupper($data['no_resep']), 0, 2) == 'A2' or substr(strtoupper($data['no_resep']), 0, 2) == 'D2' or substr(strtoupper($data['no_resep']), 0, 2) == 'C2') {
            $suffixcode = substr($data['no_resep'], 1);
        } elseif (substr(strtoupper($data['no_resep']), 0, 2) == 'DR' or substr(strtoupper($data['no_resep']), 0, 2) == 'OB' or substr(strtoupper($data['no_resep']), 0, 2) == 'CD') {
            $suffixcode = substr($data['no_resep'], 2);
        }

        if($_GET['frm'] == 'bresep'){
            // ADJUSTMENT 1
            function getRecipeAdj1($conn1, $recipesubcode01, $recipesubcode02, $recipesuffixcode, $recipeCode) {
                $sql = "SELECT
                            r.RECIPESUBCODE01,
                            RECIPESUFFIXCODE,
                            TRIM(SUBCODE01) || '-' || TRIM(SUBCODE02) || '-' || TRIM(SUBCODE03) AS RECIPE,
                            CONSUMPTION 
                        FROM
                            RECIPECOMPONENT r
                        WHERE
                            (r.RECIPESUBCODE01 = '{$recipesubcode01}' OR r.RECIPESUBCODE01 = '{$recipesubcode02}')
                            AND r.RECIPESUFFIXCODE = '{$recipesuffixcode}'
                            AND TRIM(SUBCODE01) || '-' || TRIM(SUBCODE02) || '-' || TRIM(SUBCODE03) = '{$recipeCode}'
                            AND r.ITEMTYPEAFICODE = 'DYC'
                        ORDER BY 
                            GROUPNUMBER ASC,
                            GROUPTYPECODE ASC,
                            \"SEQUENCE\" ASC
                        FETCH FIRST 1 ROW ONLY";

                $stmt = db2_exec($conn1, $sql);
                $row = db2_fetch_assoc($stmt);

                if ($row) {
                    $raw = $row['CONSUMPTION'];
                    $clean = rtrim(rtrim($raw, '0'), '.');
                    return $clean;
                } else {
                    return null;
                }
            }

            function getColoristName($conn1, $recipesubcode01, $recipesubcode02, $recipesuffixcode){
                $sqlColorist    = "SELECT DISTINCT
                                        TRIM(SHORTDESCRIPTION) As SHORTDESCRIPTION 
                                    FROM
                                        RECIPE r
                                    WHERE
                                        (r.SUBCODE01 = '{$recipesubcode01}' OR r.SUBCODE01 = '{$recipesubcode02}')
                                        AND SUFFIXCODE = '{$recipesuffixcode}'";
                $stmtColorist = db2_exec($conn1, $sqlColorist);
                $rowColorist  = db2_fetch_assoc($stmtColorist);
                if ($rowColorist) {
                    $rawColorist = $rowColorist['SHORTDESCRIPTION'];
                    return $rawColorist;
                } else {
                    return null;
                }
            }
            
            function getEditorName($conn1, $recipesubcode01, $recipesubcode02, $recipesuffixcode){
                $sqlEditor    = "SELECT DISTINCT
                                        TRIM(CREATIONUSER) AS CREATIONUSER 
                                    FROM
                                        RECIPE r
                                    WHERE
                                        (r.SUBCODE01 = '{$recipesubcode01}' OR r.SUBCODE01 = '{$recipesubcode02}')
                                        AND SUFFIXCODE = '{$recipesuffixcode}'";
                $stmtEditor = db2_exec($conn1, $sqlEditor);
                $rowEditor  = db2_fetch_assoc($stmtEditor);
                if ($rowEditor) {
                    $rawEditor = $rowEditor['CREATIONUSER'];
                    return $rawEditor;
                } else {
                    return null;
                }
            }
        }else{
            function getRecipeAdj1(){
                return null;
            }
            function getColoristName(){
                return null;
            }
            function getEditorName(){
                return null;
            }
        }
        
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
          <td colspan="3" style="text-align: right;" ><span style="font-size: 9px;">FW-12-LAB-04/03</span></td>
        </tr>
        <tr>
            <td width="9%" style="border-right:0px #000000 solid;">Suffix</td>
            <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td width="20%" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_resep']; ?></strong></td>
            <td width="10%" style="border-right:0px #000000 solid;">LAB DIP No</td>
            <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
            <td width="31%" style="border-left:0px #000000 solid;" id="adjButton"><strong><?Php echo $data['no_warna']; ?></strong></td>
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
    <!-- Tempat Tooltip -->
    <div id="tooltipContainer" class="hidden"></div>

    <table width="100%" border="1" class="table-list1">
        <tr style="height: 0.3in">
            <td width="8%" align="center"><strong>KODE</strong></td>
            <td width="10%" align="center"><strong>ERP KODE</strong></td>
            <td width="5%" align="center"><strong>LAB</td>
            <?php if($_GET['frm'] == 'bresep') : ?>
                <?php
                    function getCommentAdj($con, $adjNo) {
                        $ids = $_GET['ids'];
                        $idm = $_GET['idm'];
                        $sqlComment = "SELECT * FROM tbl_comment WHERE ids = '$ids' AND idm = '$idm' AND adj = '$adjNo'";
                        $resultComment  = mysqli_query($con, $sqlComment);
                        $dataComment    = mysqli_fetch_assoc($resultComment);

                        if ($dataComment) {
                            $raw = $dataComment['comment'];
                            return $raw;
                        } else {
                            return null;
                        }
                    }
                ?>
                <td class="adj" data-adj="1" align="center"><div class="tooltip-wrapper"><strong>Adj-1</strong><span class="tooltip-text"><?= getCommentAdj($con, '1') ?></span></div><div class="comment"></div></td>
                <td class="adj" data-adj="2" align="center"><div class="tooltip-wrapper"><strong>Adj-2</strong><span class="tooltip-text"><?= getCommentAdj($con, '2') ?></span></div><div class="comment"></div></td>
                <td class="adj" data-adj="3" align="center"><div class="tooltip-wrapper"><strong>Adj-3</strong><span class="tooltip-text"><?= getCommentAdj($con, '3') ?></span></div><div class="comment"></div></td>
                <td class="adj" data-adj="4" align="center"><div class="tooltip-wrapper"><strong>Adj-4</strong><span class="tooltip-text"><?= getCommentAdj($con, '4') ?></span></div><div class="comment"></div></td>
                <td class="adj" data-adj="5" align="center"><div class="tooltip-wrapper"><strong>Adj-5</strong><span class="tooltip-text"><?= getCommentAdj($con, '5') ?></span></div><div class="comment"></div></td>
                <td class="adj" data-adj="6" align="center"><div class="tooltip-wrapper"><strong>Adj-6</strong><span class="tooltip-text"><?= getCommentAdj($con, '6') ?></span></div><div class="comment"></div></td>
                <td class="adj" data-adj="7" align="center"><div class="tooltip-wrapper"><strong>Adj-7</strong><span class="tooltip-text"><?= getCommentAdj($con, '7') ?></span></div><div class="comment"></div></td>
            <?php else : ?>
                <td width="5%" align="center"><strong>Adj-1</td>
                <td width="5%" align="center"><strong>Adj-2</strong></td>
                <td width="5%" align="center"><strong>Adj-3</strong></td>
                <td width="5%" align="center"><strong>Adj-4</strong></td>
                <td width="5%" align="center"><strong>Adj-5</strong></td>
                <td width="5%" align="center"><strong>Adj-6</strong></td>
                <td width="5%" align="center"><strong>Adj-7</strong></td>
            <?php endif; ?>
            <td colspan="2" align="center"><strong>BODY</strong></td>
        </tr>
        <?php if($_GET['frm'] == 'bresep') : ?>
            <!-- BARIS -->
            <?php
                function GetDataFinalAdj($conn1, $flag, $suffixL, $suffixAdj1, $suffixAdj2, $suffixAdj3, $suffixAdj4, $suffixAdj5, $suffixAdj6, $suffixAdj7, $recipesubcode01, $recipesubcode02){
                    $queryFinal     = "WITH BASE AS (
                                        SELECT
                                            SEQUENCE,
                                            CASE 
                                                WHEN RIGHT(TRIM(RECIPESUBCODE01), 1) = 'D' THEN '1'
                                                WHEN RIGHT(TRIM(RECIPESUBCODE01), 1) = 'R' THEN '2'
                                                ELSE '1'
                                            END AS SUFFIXTYPE, 
                                            RECIPESUFFIXCODE,
                                            SUBCODE01,
                                            SUBCODE02,
                                            SUBCODE03,
                                            CONSUMPTION,
                                            COALESCE(TRIM(SUBCODE01),'') || '-' || COALESCE(TRIM(SUBCODE02),'') || '-' || COALESCE(TRIM(SUBCODE03),'') AS KODE
                                        FROM 
                                            RECIPECOMPONENT
                                        WHERE 
                                            RECIPESUBCODE01 IN ('{$recipesubcode01}', '{$recipesubcode02}')
                                            AND RECIPESUFFIXCODE IN ('{$suffixL}', 
                                                                    '{$suffixAdj1}','{$suffixAdj2}','{$suffixAdj3}',
                                                                    '{$suffixAdj4}','{$suffixAdj5}','{$suffixAdj6}','{$suffixAdj7}')
                                        AND ITEMTYPEAFICODE = 'DYC'
                                    ),
                                    PIVOTED AS (
                                        SELECT
                                            KODE,
                                            SEQUENCE,
                                            SUFFIXTYPE,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixL}' THEN CONSUMPTION END) AS LAB,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixAdj1}' THEN CONSUMPTION END) AS ADJ1,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixAdj2}' THEN CONSUMPTION END) AS ADJ2,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixAdj3}' THEN CONSUMPTION END) AS ADJ3,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixAdj4}' THEN CONSUMPTION END) AS ADJ4,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixAdj5}' THEN CONSUMPTION END) AS ADJ5,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixAdj6}' THEN CONSUMPTION END) AS ADJ6,
                                            MAX(CASE WHEN RECIPESUFFIXCODE = '{$suffixAdj7}' THEN CONSUMPTION END) AS ADJ7
                                        FROM 
                                            BASE
                                        GROUP BY 
                                            KODE, SEQUENCE, SUFFIXTYPE
                                    ),
                                    FINALDATA AS (
                                        SELECT 
                                            ROW_NUMBER() OVER (ORDER BY SUFFIXTYPE, SEQUENCE, KODE) AS FLAG,
                                            P.KODE || 
                                            CASE 
                                                WHEN LAB IS NULL THEN ' (NEW)'
                                                WHEN COALESCE(LAB,ADJ1,ADJ2,ADJ3,ADJ4,ADJ5,ADJ6,ADJ7) IS NULL THEN ' (REMOVED)'
                                                ELSE ''
                                            END AS KODE,
                                            P.SEQUENCE,
                                            P.SUFFIXTYPE,
                                            P.LAB,
                                            P.ADJ1,
                                            P.ADJ2,
                                            P.ADJ3,
                                            P.ADJ4,
                                            P.ADJ5,
                                            P.ADJ6,
                                            P.ADJ7,
                                            CASE 
                                                WHEN LAB IS NULL THEN 'NEW'
                                                WHEN COALESCE(LAB,ADJ1,ADJ2,ADJ3,ADJ4,ADJ5,ADJ6,ADJ7) IS NULL THEN 'REMOVED'
                                                ELSE ''
                                            END AS STATUSDYC
                                        FROM 
                                            PIVOTED P
                                    )
                                    SELECT 
                                        *
                                    FROM 
                                        FINALDATA
                                    WHERE 
                                        FLAG = {$flag}
                                    ORDER BY 
                                        SUFFIXTYPE, 
                                        SEQUENCE, 
                                        KODE;";
                    $resultFinal = db2_exec($conn1, $queryFinal);
                    $dataFinal = db2_fetch_assoc($resultFinal);
                    if ($dataFinal) {
                        $kode       = $dataFinal['KODE'];
                        $lab        = $dataFinal['LAB'];
                        $adj1       = $dataFinal['ADJ1'];
                        $adj2       = $dataFinal['ADJ2'];
                        $adj3       = $dataFinal['ADJ3'];
                        $adj4       = $dataFinal['ADJ4'];
                        $adj5       = $dataFinal['ADJ5'];
                        $adj6       = $dataFinal['ADJ6'];
                        $adj7       = $dataFinal['ADJ7'];
                        $statusDyc  = $dataFinal['STATUSDYC'];
                        return [
                            'kode' => $kode,
                            'lab'  => rtrim(rtrim($lab, '0'), '.'),
                            'adj1' => rtrim(rtrim($adj1, '0'), '.'),
                            'adj2' => rtrim(rtrim($adj2, '0'), '.'),
                            'adj3' => rtrim(rtrim($adj3, '0'), '.'),
                            'adj4' => rtrim(rtrim($adj4, '0'), '.'),
                            'adj5' => rtrim(rtrim($adj5, '0'), '.'),
                            'adj6' => rtrim(rtrim($adj6, '0'), '.'),
                            'adj7' => rtrim(rtrim($adj7, '0'), '.'),
                            'statusDyc' => $statusDyc
                        ];
                    } else {
                        return null;
                    }
                }
                $dataDyc1 = GetDataFinalAdj($conn1, '1', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc2 = GetDataFinalAdj($conn1, '2', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc3 = GetDataFinalAdj($conn1, '3', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc4 = GetDataFinalAdj($conn1, '4', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc5 = GetDataFinalAdj($conn1, '5', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc6 = GetDataFinalAdj($conn1, '6', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc7 = GetDataFinalAdj($conn1, '7', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc8 = GetDataFinalAdj($conn1, '8', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc9 = GetDataFinalAdj($conn1, '9', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc10 = GetDataFinalAdj($conn1, '10', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc11 = GetDataFinalAdj($conn1, '11', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc12 = GetDataFinalAdj($conn1, '12', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc13 = GetDataFinalAdj($conn1, '13', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc14 = GetDataFinalAdj($conn1, '14', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc15 = GetDataFinalAdj($conn1, '15', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc16 = GetDataFinalAdj($conn1, '16', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc17 = GetDataFinalAdj($conn1, '17', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc18 = GetDataFinalAdj($conn1, '18', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc19 = GetDataFinalAdj($conn1, '19', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc20 = GetDataFinalAdj($conn1, '20', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc21 = GetDataFinalAdj($conn1, '21', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc22 = GetDataFinalAdj($conn1, '22', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc23 = GetDataFinalAdj($conn1, '23', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc24 = GetDataFinalAdj($conn1, '24', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);
                $dataDyc25 = GetDataFinalAdj($conn1, '25', $suffixcode.'L', $suffixcode.'D-S1', $suffixcode.'D-S2', $suffixcode.'D-S3', $suffixcode.'D-S4', $suffixcode.'D-S5', $suffixcode.'D-S6', $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2']);


                function GetDataFinalSuhu($conn1, $suffix, $recipesubcode01, $recipesubcode02, $side){
                    $queryFinalSuhu = "WITH FINALDATA AS (
                                                SELECT
                                                    RECIPESUBCODE01,
                                                    RECIPESUFFIXCODE,
                                                    CASE
                                                        WHEN RIGHT(TRIM(RECIPESUBCODE01), 1) = 'D' THEN 'T-SIDE'
                                                        WHEN RIGHT(TRIM(RECIPESUBCODE01), 1) = 'R' THEN 'C-SIDE'
                                                        ELSE '1'
                                                    END AS SIDE,
                                                    COMMENTLINE
                                                FROM
                                                    RECIPECOMPONENT r
                                                WHERE
                                                    RECIPESUBCODE01 IN ('{$recipesubcode01}', '{$recipesubcode02}')
                                                    AND RECIPESUFFIXCODE IN ('{$suffix}')
                                                    AND GROUPTYPECODE = '100'
                                                    AND (NOT COMMENTLINE LIKE '%RC %'
                                                        AND NOT COMMENTLINE LIKE '%SOAPING%'
                                                        AND NOT COMMENTLINE LIKE '%BLEACHING%')
                                            )
                                            SELECT
                                                RECIPESUBCODE01,
                                                RECIPESUFFIXCODE,
                                                SIDE,
                                                COMMENTLINE
                                            FROM
                                                FINALDATA
                                            WHERE
                                                SIDE = '{$side}'";
                    $resultFinalSuhu    = db2_exec($conn1, $queryFinalSuhu);
                    $dataFinalSuhu      = db2_fetch_assoc($resultFinalSuhu);
                    if ($dataFinalSuhu) {
                        $side           = $dataFinalSuhu['SIDE'];
                        $commentline    = $dataFinalSuhu['COMMENTLINE'];
                        return [
                            'side'          => $side,
                            'commentline'   => $commentline
                        ];
                    } else {
                        return null;
                    }
                }

                $dataSuhu1TSide = GetDataFinalSuhu($conn1, $suffixcode.'L', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');
                $dataSuhu2TSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S1', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');
                $dataSuhu3TSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S2', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');
                $dataSuhu4TSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S3', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');
                $dataSuhu5TSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S4', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');
                $dataSuhu6TSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S5', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');
                $dataSuhu7TSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S6', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');
                $dataSuhu8TSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2'], 'T-SIDE');

                $dataSuhu1CSide = GetDataFinalSuhu($conn1, $suffixcode.'L', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
                $dataSuhu2CSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S1', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
                $dataSuhu3CSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S2', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
                $dataSuhu4CSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S3', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
                $dataSuhu5CSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S4', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
                $dataSuhu6CSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S5', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
                $dataSuhu7CSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S6', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
                $dataSuhu8CSide = GetDataFinalSuhu($conn1, $suffixcode.'D-S7', $data['recipe_code_1'], $data['recipe_code_2'], 'C-SIDE');
            ?>
                <!-- BARIS 1 -->
                    <tr style="height: 0.2in;" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc1['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc1['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc1['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc1['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc1['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc1['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc1['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc1['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc1['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc1['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc1['adj7']; ?></td>
                        <td style="text-align: left; vertical-align: top;" colspan="2" rowspan="5" class="adj" data-adj="info-lab">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                                <!-- Info Laborat (kiri) -->
                                <div class="tooltip-wrapper" style="max-width: 70%;">
                                    <strong>Info Laborat : <?= getCommentAdj($con, 'info-lab') ?></strong>
                                    <span class="tooltip-text"><?= getCommentAdj($con, 'info-lab') ?></span>
                                    <br>
                                    <strong>Keterangan : <?= $data['ket']; ?></strong>
                                </div>

                                <!-- QR Code (kanan) -->
                                <div style="display: flex; flex-direction: row; gap: 12px; align-items: flex-start;">
                                    <?php
                                    if (!empty($data['no_resep'])) {
                                        include('../../phpqrcode/qrlib.php');
                                        $qrcode = $data['no_resep'];

                                        if (strtoupper(substr($qrcode, 0, 2)) === 'DR') {
                                            $qrcodeA = $qrcode . '-A';
                                            $fileqrA = 'qrcode_A.png';
                                            QRcode::png($qrcodeA, $fileqrA, QR_ECLEVEL_L, 4, 0);

                                            $qrcodeB = $qrcode . '-B';
                                            $fileqrB = 'qrcode_B.png';
                                            QRcode::png($qrcodeB, $fileqrB, QR_ECLEVEL_L, 4, 0);

                                            echo '<img src="' . $fileqrA . '" alt="QR Code A" class="qrcode" />';
                                            echo '<img src="' . $fileqrB . '" alt="QR Code B" class="qrcode" />';
                                        } else {
                                            $fileqr = 'qrcode.png';
                                            QRcode::png($qrcode, $fileqr, QR_ECLEVEL_L, 4, 0);
                                            echo '<img src="' . $fileqr . '" alt="QR Code" class="qrcode" />';
                                        }
                                    }

                                    if ($data['salesman_sample'] == "1") {
                                        echo '<strong style="font-size: 21px;">S/S</strong>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <!-- BARIS 2 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc2['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc2['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc2['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc2['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc2['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc2['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc2['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc2['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc2['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc2['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc2['adj7']; ?></td>
                    </tr>
                <!-- BARIS 3 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc3['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc3['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc3['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc3['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc3['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc3['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc3['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc3['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc3['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc3['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc3['adj7']; ?></td>
                    </tr>
                <!-- BARIS 4 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc4['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc4['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc4['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc4['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc4['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc4['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc4['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc4['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc4['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc4['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc4['adj7']; ?></td>
                    </tr>
                <!-- BARIS 5 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc5['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc5['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc5['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc5['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc5['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc5['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc5['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc5['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc5['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc5['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc5['adj7']; ?></td>
                    </tr>
                <!-- BARIS 6 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc6['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc6['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc6['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc6['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc6['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc6['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc6['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc6['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc6['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc6['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc6['adj7']; ?></td>
                        <td style="font-weight: bold;">Create Resep : <?php echo $data['create_resep'] ?></td>
                        <td style="font-weight: bold;">Acc Tes Ulang OK : <?php echo $data['acc_ulang_ok'] ?></td>
                    </tr>
                <!-- BARIS 7 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc7['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc7['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc7['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc7['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc7['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc7['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc7['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc7['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc7['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc7['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc7['adj7']; ?></td>
                        <td style="font-weight: bold;">Acc Resep Pertama 1 : <?php echo $data['acc_resep1'] ?></td>
                        <td valign="top"><span style="font-weight: bold;">Acc Resep Pertama 2 : <?php echo $data['acc_resep1'] ?></span></td>
                    </tr>
                <!-- BARIS 8 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc8['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc8['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc8['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc8['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc8['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc8['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc8['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc8['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc8['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc8['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc8['adj7']; ?></td>
                        <td style="font-weight: bold;">Colorist 1 : <?php echo $data['colorist1'] ?></td>
                        <td style="font-weight: bold;">Colorist 2 : <?php echo $data['colorist2'] ?></td>
                    </tr>
                <!-- BARIS 9 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc9['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc9['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc9['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc9['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc9['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc9['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc9['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc9['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc9['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc9['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc9['adj7']; ?></td>
                        <td colspan="2" rowspan="2" align="center"><strong>LAB. SAMPLE</strong></td>
                    </tr>
                <!-- BARIS 10 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc10['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc10['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc10['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc10['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc10['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc10['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc10['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc10['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc10['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc10['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc10['adj7']; ?></td>
                    </tr>
                <!-- BARIS 11 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc11['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc11['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc11['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc11['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc11['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc11['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc11['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc11['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc11['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc11['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc11['adj7']; ?></td>
                        <td colspan="2" rowspan="10" valign="top">
                            <?php
                                if($_GET['frm'] == 'bresep'){
                                    $sqlRGB = "SELECT
                                                    CAST(a.VALUEDECIMAL AS INT) AS R,
                                                    CAST(a2.VALUEDECIMAL AS INT) AS G,
                                                    CAST(a3.VALUEDECIMAL AS INT) AS B
                                                FROM
                                                    USERGENERICGROUP u
                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = u.ABSUNIQUEID AND a.FIELDNAME = 'RGBvalueR'
                                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = u.ABSUNIQUEID AND a2.FIELDNAME = 'RGBvalueG'
                                                LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = u.ABSUNIQUEID AND a3.FIELDNAME = 'RGBvalueB'
                                                WHERE 
                                                    u.USERGENERICGROUPTYPECODE = 'CL1'
                                                    AND u.CODE = '$data[color_code]'";
                                    $stmtRGB = db2_exec($conn1, $sqlRGB);
                                    $rowRGB  = db2_fetch_assoc($stmtRGB);

                                    $r = $rowRGB['R'] ?? null;
                                    $g = $rowRGB['G'] ?? null;
                                    $b = $rowRGB['B'] ?? null;
                            ?>
                                <?php if ($r !== null && $g !== null && $b !== null) : ?>
                                    <?php $hexRGB = sprintf("#%02x%02x%02x", $r, $g, $b); ?>
                                    <div style="width: 100%; height: 200px; background-color: <?= $hexRGB; ?>; color: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: bold;">
                                        RGB(<?= $r ?>, <?= $g ?>, <?= $b ?>)
                                    </div>
                                <?php endif;  ?>
                            <?php } ?>
                        </td>
                    </tr>
                <!-- BARIS 12 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc12['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc12['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc12['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc12['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc12['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc12['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc12['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc12['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc12['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc12['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc12['adj7']; ?></td>
                    </tr>
                <!-- BARIS 13 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc13['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc13['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc13['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc13['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc13['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc13['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc13['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc13['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc13['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc13['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc13['adj7']; ?></td>
                    </tr>
                <!-- BARIS 14 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc14['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc14['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc14['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc14['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc14['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc14['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc14['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc14['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc14['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc14['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc14['adj7']; ?></td>
                    </tr>
                <!-- BARIS 15 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc15['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc15['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc15['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc15['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc15['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc15['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc15['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc15['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc15['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc15['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc15['adj7']; ?></td>
                    </tr>
                <!-- BARIS 16 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc16['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc16['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc16['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc16['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc16['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc16['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc16['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc16['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc16['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc16['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc16['adj7']; ?></td>
                    </tr>
                <!-- BARIS 17 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc17['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc17['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc17['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc17['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc17['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc17['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc17['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc17['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc17['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc17['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc17['adj7']; ?></td>
                    </tr>
                <!-- BARIS 18 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc18['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc18['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc18['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc18['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc18['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc18['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc18['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc18['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc18['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc18['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc18['adj7']; ?></td>
                    </tr>
                <!-- BARIS 19 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc19['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc19['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc19['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc19['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc19['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc19['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc19['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc19['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc19['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc19['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc19['adj7']; ?></td>
                    </tr>
                <!-- BARIS 20 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc20['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc20['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc20['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc20['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc20['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc20['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc20['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc20['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc20['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc20['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc20['adj7']; ?></td>
                    </tr>
                <!-- BARIS 21 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc21['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc21['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc21['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc21['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc21['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc21['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc21['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc21['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc21['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc21['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc21['adj7']; ?></td>
                        <td width="20%" rowspan="2" align="center"><strong>BEFORE SOAPING</strong></td>
                        <td width="21%" rowspan="2" align="center"><strong>T-SIDE</strong></td>
                    </tr>
                <!-- BARIS 22 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc22['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc22['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc22['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc22['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc22['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc22['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc22['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc22['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc22['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc22['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc22['adj7']; ?></td>
                    </tr>
                <!-- BARIS 23 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc23['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc23['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc23['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc23['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc23['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc23['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc23['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc23['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc23['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc23['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc23['adj7']; ?></td>
                        <td rowspan="7" style="text-align: center; vertical-align: middle;">
                            <?php if(is_numeric($data['suhu_chamber']) > 0) : ?>
                                <center>
                                    <div class="stamp-box-suhuchamber">
                                        SUHU CHAMBER <?= $data['suhu_chamber']; ?>°C
                                    </div>
                                </center>
                            <?php elseif($data['suhu_chamber'] == 'none') : ?>
                                <img src="../../dist/img/none suhu chamber.png" width="300" height="100" alt="Suhu Chamber">
                            <?php else : ?>
                                &nbsp;
                            <?php endif; ?>
                        </td>
                        <td rowspan="7" style="text-align: center; vertical-align: middle;">
                            <?php if($data['warna_flourescent'] == '1') : ?>
                                <center>
                                    <div class="stamp-box-warnafluorescent">
                                        WARNA FLUORESCENT
                                    </div>
                                </center>
                            <?php else : ?>
                                &nbsp;
                            <?php endif; ?>
                        </td>
                    </tr>
                <!-- BARIS 24 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc24['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc24['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc24['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc24['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc24['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc24['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc24['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc24['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc24['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc24['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc24['adj7']; ?></td>
                    </tr>
                <!-- BARIS 25 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?= $dataDyc25['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['statusDyc'] == 'NEW' ? 'background-color: #D9E1F2;' : '' ?>"><?= $dataDyc25['kode']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['adj1'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc25['lab']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['adj2'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc25['adj1']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['adj3'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc25['adj2']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['adj4'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc25['adj3']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['adj5'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc25['adj4']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['adj6'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc25['adj5']; ?></td>
                        <td style="font-weight: bold; <?= $dataDyc25['adj7'] ? 'text-decoration: line-through;' : '' ?>"><?= $dataDyc25['adj6']; ?></td>
                        <td style="font-weight: bold;"><?= $dataDyc25['adj7']; ?></td>
                    </tr>
                <!-- BARIS 26 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;">T-SIDE</td>
                        <td style="font-weight: bold;"><?= $dataSuhu1TSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu2TSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu3TSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu4TSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu5TSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu6TSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu7TSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu8TSide['commentline']; ?></td>
                    </tr>
                <!-- BARIS 27 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;">C-SIDE</td>
                        <td style="font-weight: bold;"><?= $dataSuhu1CSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu2CSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu3CSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu4CSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu5CSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu6CSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu7CSide['commentline']; ?></td>
                        <td style="font-weight: bold;"><?= $dataSuhu8CSide['commentline']; ?></td>
                    </tr>
                <!-- BARIS 28 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?php echo $kode_lama26; ?></td>
                        <td style="font-weight: bold;">Colorist Name</td>
                        <?php 
                            $coloristname1  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1');
                            $coloristname2  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2');
                            $coloristname3  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3');
                            $coloristname4  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4');
                            $coloristname5  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5');
                            $coloristname6  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6');
                            $coloristname7  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7');
                        ?>
                        <td style="font-weight: bold;">-</td>
                        <td style="font-weight: bold;"><?= $coloristname1; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname2; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname3; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname4; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname5; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname6; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname7; ?></td>
                    </tr>
                <!-- BARIS 29 -->
                    <tr style="height: 0.2in" class="flag">
                        <td style="font-weight: bold;"><?php echo $kode_lama27; ?></td>
                        <td style="font-weight: bold;">Editor Name</td>
                        <?php 
                            $editorname1  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1');
                            $editorname2  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2');
                            $editorname3  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3');
                            $editorname4  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4');
                            $editorname5  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5');
                            $editorname6  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6');
                            $editorname7  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7');
                        ?>
                        <td style="font-weight: bold;">-</td>
                        <td style="font-weight: bold;"><?= $editorname1; ?></td>
                        <td style="font-weight: bold;"><?= $editorname2; ?></td>
                        <td style="font-weight: bold;"><?= $editorname3; ?></td>
                        <td style="font-weight: bold;"><?= $editorname4; ?></td>
                        <td style="font-weight: bold;"><?= $editorname5; ?></td>
                        <td style="font-weight: bold;"><?= $editorname6; ?></td>
                        <td style="font-weight: bold;"><?= $editorname7; ?></td>
                    </tr>

                <tr style="height: 0.4in">
                    <td colspan="4">&nbsp;</td>
                    <td colspan="3" align="center">T-SIDE</td>
                    <td colspan="3" align="center">C.SIDE</td>
                </tr>
            <!-- BARIS -->
        <?php else : ?>
            <!-- BARIS -->
                <!-- BARIS 1 -->
                <?php
                    $resep1 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 1 limit 1");
                    $rsp1 = mysqli_fetch_array($resep1);
                    
                    $KodeBaru = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp1[kode]' limit 1");
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr['ket'] == 'Suhu'){
                                echo $kdbr['Product_Name'];
                            }else{
                                echo $kode_baru;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_1 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama);
                        $adj2_1 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama);
                        $adj3_1 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama);
                        $adj4_1 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama);
                        $adj5_1 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama);
                        $adj6_1 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama);
                        $adj7_1 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_1 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp1['conc1']) != 0) echo floatval($rsp1['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_1 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp1['conc2']) != 0) echo floatval($rsp1['conc2']) ?><span style="color: red;"><?= $adj1_1; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_1 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp1['conc3']) != 0) echo floatval($rsp1['conc3']) ?><span style="color: red;"><?= $adj2_1; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_1 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp1['conc4']) != 0) echo floatval($rsp1['conc4']) ?><span style="color: red;"><?= $adj3_1; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_1 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp1['conc5']) != 0) echo floatval($rsp1['conc5']) ?><span style="color: red;"><?= $adj4_1; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_1 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp1['conc6']) != 0) echo floatval($rsp1['conc6']) ?><span style="color: red;"><?= $adj5_1; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_1 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp1['conc7']) != 0) echo floatval($rsp1['conc7']) ?><span style="color: red;"><?= $adj6_1; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp1['conc8']) != 0) echo floatval($rsp1['conc8']) ?><span style="color: red;"><?= $adj7_1; ?></span></td>
                    <?php
                        $sql_Norder1 = mysqli_query($con,"SELECT `order` from tbl_orderchild 
                                                            where id_matching = '$data[id_matching]' and id_status = '$data[id_status]' order by flag limit 0,50");
                        $iteration = 1;
                    ?>
                    <?php if ($_GET['frm'] == 'bresep') : ?>
                        <td style="text-align: left; vertical-align: top;" colspan="2" rowspan="5" class="adj" data-adj="info-lab">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                                <!-- Info Laborat (kiri) -->
                                <div class="tooltip-wrapper" style="max-width: 70%;">
                                    <strong>Info Laborat : <?= getCommentAdj($con, 'info-lab') ?></strong>
                                    <span class="tooltip-text"><?= getCommentAdj($con, 'info-lab') ?></span>
                                    <br>
                                    <strong>Keterangan : <?= $data['ket']; ?></strong>
                                </div>

                                <!-- QR Code (kanan) -->
                                <div style="display: flex; flex-direction: row; gap: 12px; align-items: flex-start;">
                                    <?php
                                    if (!empty($data['no_resep'])) {
                                        include('../../phpqrcode/qrlib.php');
                                        $qrcode = $data['no_resep'];

                                        if (strtoupper(substr($qrcode, 0, 2)) === 'DR') {
                                            $qrcodeA = $qrcode . '-A';
                                            $fileqrA = 'qrcode_A.png';
                                            QRcode::png($qrcodeA, $fileqrA, QR_ECLEVEL_L, 4, 0);

                                            $qrcodeB = $qrcode . '-B';
                                            $fileqrB = 'qrcode_B.png';
                                            QRcode::png($qrcodeB, $fileqrB, QR_ECLEVEL_L, 4, 0);

                                            echo '<img src="' . $fileqrA . '" alt="QR Code A" class="qrcode" />';
                                            echo '<img src="' . $fileqrB . '" alt="QR Code B" class="qrcode" />';
                                        } else {
                                            $fileqr = 'qrcode.png';
                                            QRcode::png($qrcode, $fileqr, QR_ECLEVEL_L, 4, 0);
                                            echo '<img src="' . $fileqr . '" alt="QR Code" class="qrcode" />';
                                        }
                                    }

                                    if ($data['salesman_sample'] == "1") {
                                        echo '<strong style="font-size: 21px;">S/S</strong>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>

                    <?php else : ?>
                        <td colspan="2" rowspan="5">
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <?php while ($no = mysqli_fetch_array($sql_Norder1)) : ?>
                                        <?= $iteration++ . '.(' . $no['order'] . ')' ?>&nbsp;&nbsp;&nbsp;
                                    <?php endwhile; ?>
                                </div>
                                <div style="display: flex; justify-content: flex-end; align-items: center; gap: 16px;">
                                    <?php
                                    if (!empty($data['no_resep'])) {
                                        include('../../phpqrcode/qrlib.php');
                                        $qrcode = $data['no_resep'];

                                        if (strtoupper(substr($qrcode, 0, 2)) === 'DR') {
                                            $qrcodeA = $qrcode . '-A';
                                            $fileqrA = 'qrcode_A.png';
                                            QRcode::png($qrcodeA, $fileqrA, QR_ECLEVEL_L, 4, 0);

                                            $qrcodeB = $qrcode . '-B';
                                            $fileqrB = 'qrcode_B.png';
                                            QRcode::png($qrcodeB, $fileqrB, QR_ECLEVEL_L, 4, 0);

                                            echo '<img src="' . $fileqrA . '" alt="QR Code A" class="qrcode" />';
                                            echo '<img src="' . $fileqrB . '" alt="QR Code B" class="qrcode" />';
                                        } else {
                                            $fileqr = 'qrcode.png';
                                            QRcode::png($qrcode, $fileqr, QR_ECLEVEL_L, 4, 0);
                                            echo '<img src="' . $fileqr . '" alt="QR Code" class="qrcode" />';
                                        }
                                    }

                                    if ($data['salesman_sample'] == "1") {
                                        echo '<strong style="font-size: 21px;">S/S</strong>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                <!-- BARIS 2 -->
                <?php
                    $resep2 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 2 
                                                                        order by flag asc limit 1");
                    $rsp2 = mysqli_fetch_array($resep2);
                    $KodeBaru2 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp2[kode]'
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
                    // JIKA KODE DYESTUFF SUHU MAKA YG DITAMPILKAN ADALAH PRODUCT_NAME
                    
                ?>
                <tr style="height: 0.2in" class="flag">
                    <td style="font-weight: bold;"><?php echo $kode_lama2; ?></td>
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr2['ket'] == 'Suhu'){
                                echo $kdbr2['Product_Name'];
                            }else{
                                echo $kode_baru2;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_2 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama2);
                        $adj2_2 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama2);
                        $adj3_2 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama2);
                        $adj4_2 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama2);
                        $adj5_2 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama2);
                        $adj6_2 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama2);
                        $adj7_2 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama2);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_2 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp2['conc1']) != 0) echo floatval($rsp2['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_2 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp2['conc2']) != 0) echo floatval($rsp2['conc2']) ?><span style="color: red;"><?= $adj1_2; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_2 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp2['conc3']) != 0) echo floatval($rsp2['conc3']) ?><span style="color: red;"><?= $adj2_2; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_2 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp2['conc4']) != 0) echo floatval($rsp2['conc4']) ?><span style="color: red;"><?= $adj3_2; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_2 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp2['conc5']) != 0) echo floatval($rsp2['conc5']) ?><span style="color: red;"><?= $adj4_2; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_2 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp2['conc6']) != 0) echo floatval($rsp2['conc6']) ?><span style="color: red;"><?= $adj5_2; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_2 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp2['conc7']) != 0) echo floatval($rsp2['conc7']) ?><span style="color: red;"><?= $adj6_2; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp2['conc8']) != 0) echo floatval($rsp2['conc8']) ?><span style="color: red;"><?= $adj7_2; ?></span></td>
                </tr>
                <!-- BARIS 3 -->
                <?php
                    $resep3 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 3 
                                                                        order by flag asc limit 1");
                    $rsp3 = mysqli_fetch_array($resep3);
                    
                    $KodeBaru3 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp3[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr3['ket'] == 'Suhu'){
                                echo $kdbr3['Product_Name'];
                            }else{
                                echo $kode_baru3;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_3 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama3);
                        $adj2_3 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama3);
                        $adj3_3 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama3);
                        $adj4_3 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama3);
                        $adj5_3 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama3);
                        $adj6_3 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama3);
                        $adj7_3 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama3);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_3 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp3['conc1']) != 0) echo floatval($rsp3['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_3 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp3['conc2']) != 0) echo floatval($rsp3['conc2']) ?><span style="color: red;"><?= $adj1_3; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_3 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp3['conc3']) != 0) echo floatval($rsp3['conc3']) ?><span style="color: red;"><?= $adj2_3; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_3 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp3['conc4']) != 0) echo floatval($rsp3['conc4']) ?><span style="color: red;"><?= $adj3_3; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_3 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp3['conc5']) != 0) echo floatval($rsp3['conc5']) ?><span style="color: red;"><?= $adj4_3; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_3 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp3['conc6']) != 0) echo floatval($rsp3['conc6']) ?><span style="color: red;"><?= $adj5_3; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_3 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp3['conc7']) != 0) echo floatval($rsp3['conc7']) ?><span style="color: red;"><?= $adj6_3; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp3['conc8']) != 0) echo floatval($rsp3['conc8']) ?><span style="color: red;"><?= $adj7_3; ?></span></td>
                </tr>
                <!-- BARIS 4 -->
                <?php
                    $resep4 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 4 
                                                                        order by flag asc limit 1");
                    $rsp4 = mysqli_fetch_array($resep4);
                            
                    $KodeBaru4 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp4[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr4['ket'] == 'Suhu'){
                                echo $kdbr4['Product_Name'];
                            }else{
                                echo $kode_baru4;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_4 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama4);
                        $adj2_4 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama4);
                        $adj3_4 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama4);
                        $adj4_4 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama4);
                        $adj5_4 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama4);
                        $adj6_4 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama4);
                        $adj7_4 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama4);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_4 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp4['conc1']) != 0) echo floatval($rsp4['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_4 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp4['conc2']) != 0) echo floatval($rsp4['conc2']) ?><span style="color: red;"><?= $adj1_4; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_4 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp4['conc3']) != 0) echo floatval($rsp4['conc3']) ?><span style="color: red;"><?= $adj2_4; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_4 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp4['conc4']) != 0) echo floatval($rsp4['conc4']) ?><span style="color: red;"><?= $adj3_4; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_4 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp4['conc5']) != 0) echo floatval($rsp4['conc5']) ?><span style="color: red;"><?= $adj4_4; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_4 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp4['conc6']) != 0) echo floatval($rsp4['conc6']) ?><span style="color: red;"><?= $adj5_4; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_4 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp4['conc7']) != 0) echo floatval($rsp4['conc7']) ?><span style="color: red;"><?= $adj6_4; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp4['conc8']) != 0) echo floatval($rsp4['conc8']) ?><span style="color: red;"><?= $adj7_4; ?></span></td>
                </tr>
                <!-- BARIS 5 -->
                <?php
                    $resep5 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 5 
                                                                        order by flag asc limit 1");
                    $rsp5 = mysqli_fetch_array($resep5);
                            
                    $KodeBaru5 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp5[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr5['ket'] == 'Suhu'){
                                echo $kdbr5['Product_Name'];
                            }else{
                                echo $kode_baru5;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_5 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama5);
                        $adj2_5 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama5);
                        $adj3_5 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama5);
                        $adj4_5 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama5);
                        $adj5_5 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama5);
                        $adj6_5 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama5);
                        $adj7_5 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama5);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_5 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp5['conc1']) != 0) echo floatval($rsp5['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_5 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp5['conc2']) != 0) echo floatval($rsp5['conc2']) ?><span style="color: red;"><?= $adj1_5; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_5 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp5['conc3']) != 0) echo floatval($rsp5['conc3']) ?><span style="color: red;"><?= $adj2_5; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_5 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp5['conc4']) != 0) echo floatval($rsp5['conc4']) ?><span style="color: red;"><?= $adj3_5; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_5 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp5['conc5']) != 0) echo floatval($rsp5['conc5']) ?><span style="color: red;"><?= $adj4_5; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_5 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp5['conc6']) != 0) echo floatval($rsp5['conc6']) ?><span style="color: red;"><?= $adj5_5; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_5 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp5['conc7']) != 0) echo floatval($rsp5['conc7']) ?><span style="color: red;"><?= $adj6_5; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp5['conc8']) != 0) echo floatval($rsp5['conc8']) ?><span style="color: red;"><?= $adj7_5; ?></span></td>
                </tr>
                <!-- BARIS 6 -->
                <?php
                    $resep6 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 6 
                                                                        order by flag asc limit 1");
                    $rsp6 = mysqli_fetch_array($resep6);
                            
                    $KodeBaru6 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp6[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr6['ket'] == 'Suhu'){
                                echo $kdbr6['Product_Name'];
                            }else{
                                echo $kode_baru6;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_6 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama6);
                        $adj2_6 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama6);
                        $adj3_6 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama6);
                        $adj4_6 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama6);
                        $adj5_6 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama6);
                        $adj6_6 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama6);
                        $adj7_6 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama6);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_6 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp6['conc1']) != 0) echo floatval($rsp6['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_6 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp6['conc2']) != 0) echo floatval($rsp6['conc2']) ?><span style="color: red;"><?= $adj1_6; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_6 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp6['conc3']) != 0) echo floatval($rsp6['conc3']) ?><span style="color: red;"><?= $adj2_6; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_6 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp6['conc4']) != 0) echo floatval($rsp6['conc4']) ?><span style="color: red;"><?= $adj3_6; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_6 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp6['conc5']) != 0) echo floatval($rsp6['conc5']) ?><span style="color: red;"><?= $adj4_6; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_6 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp6['conc6']) != 0) echo floatval($rsp6['conc6']) ?><span style="color: red;"><?= $adj5_6; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_6 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp6['conc7']) != 0) echo floatval($rsp6['conc7']) ?><span style="color: red;"><?= $adj6_6; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp6['conc8']) != 0) echo floatval($rsp6['conc8']) ?><span style="color: red;"><?= $adj7_6; ?></span></td>
                    <td style="font-weight: bold;">Create Resep : <?php echo $data['create_resep'] ?></td>
                    <td style="font-weight: bold;">Acc Tes Ulang OK : <?php echo $data['acc_ulang_ok'] ?></td>
                </tr>
                <!-- BARIS 7 -->
                <?php
                    $resep7 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 7 
                                                                        order by flag asc limit 1");
                    $rsp7 = mysqli_fetch_array($resep7);
                            
                    $KodeBaru7 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp7[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr7['ket'] == 'Suhu'){
                                echo $kdbr7['Product_Name'];
                            }else{
                                echo $kode_baru7;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_7 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama7);
                        $adj2_7 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama7);
                        $adj3_7 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama7);
                        $adj4_7 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama7);
                        $adj5_7 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama7);
                        $adj6_7 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama7);
                        $adj7_7 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama7);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_7 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp7['conc1']) != 0) echo floatval($rsp7['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_7 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp7['conc2']) != 0) echo floatval($rsp7['conc2']) ?><span style="color: red;"><?= $adj1_7; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_7 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp7['conc3']) != 0) echo floatval($rsp7['conc3']) ?><span style="color: red;"><?= $adj2_7; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_7 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp7['conc4']) != 0) echo floatval($rsp7['conc4']) ?><span style="color: red;"><?= $adj3_7; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_7 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp7['conc5']) != 0) echo floatval($rsp7['conc5']) ?><span style="color: red;"><?= $adj4_7; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_7 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp7['conc6']) != 0) echo floatval($rsp7['conc6']) ?><span style="color: red;"><?= $adj5_7; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_7 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp7['conc7']) != 0) echo floatval($rsp7['conc7']) ?><span style="color: red;"><?= $adj6_7; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp7['conc8']) != 0) echo floatval($rsp7['conc8']) ?><span style="color: red;"><?= $adj7_7; ?></span></td>
                    <td style="font-weight: bold;">Acc Resep Pertama 1 : <?php echo $data['acc_resep1'] ?></td>
                    <td valign="top"><span style="font-weight: bold;">Acc Resep Pertama 2 : <?php echo $data['acc_resep1'] ?></span></td>
                </tr>
                <!-- BARIS 8 -->
                <?php
                    $resep8 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 8 
                                                                        order by flag asc limit 1");
                    $rsp8 = mysqli_fetch_array($resep8);
                            
                    $KodeBaru8 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp8[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr8['ket'] == 'Suhu'){
                                echo $kdbr8['Product_Name'];
                            }else{
                                echo $kode_baru8;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_8 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama8);
                        $adj2_8 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama8);
                        $adj3_8 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama8);
                        $adj4_8 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama8);
                        $adj5_8 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama8);
                        $adj6_8 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama8);
                        $adj7_8 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama8);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_8 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp8['conc1']) != 0) echo floatval($rsp8['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_8 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp8['conc2']) != 0) echo floatval($rsp8['conc2']) ?><span style="color: red;"><?= $adj1_8; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_8 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp8['conc3']) != 0) echo floatval($rsp8['conc3']) ?><span style="color: red;"><?= $adj2_8; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_8 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp8['conc4']) != 0) echo floatval($rsp8['conc4']) ?><span style="color: red;"><?= $adj3_8; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_8 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp8['conc5']) != 0) echo floatval($rsp8['conc5']) ?><span style="color: red;"><?= $adj4_8; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_8 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp8['conc6']) != 0) echo floatval($rsp8['conc6']) ?><span style="color: red;"><?= $adj5_8; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_8 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp8['conc7']) != 0) echo floatval($rsp8['conc7']) ?><span style="color: red;"><?= $adj6_8; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp8['conc8']) != 0) echo floatval($rsp8['conc8']) ?><span style="color: red;"><?= $adj7_8; ?></span></td>
                    <td style="font-weight: bold;">Colorist 1 : <?php echo $data['colorist1'] ?></td>
                    <td style="font-weight: bold;">Colorist 2 : <?php echo $data['colorist2'] ?></td>
                </tr>
                <!-- BARIS 9 -->
                <?php
                    $resep9 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 9 
                                                                        order by flag asc limit 1");
                    $rsp9 = mysqli_fetch_array($resep9);
                            
                    $KodeBaru9 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp9[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr9['ket'] == 'Suhu'){
                                echo $kdbr9['Product_Name'];
                            }else{
                                echo $kode_baru9;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_9 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama9);
                        $adj2_9 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama9);
                        $adj3_9 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama9);
                        $adj4_9 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama9);
                        $adj5_9 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama9);
                        $adj6_9 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama9);
                        $adj7_9 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama9);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_9 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp9['conc1']) != 0) echo floatval($rsp9['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_9 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp9['conc2']) != 0) echo floatval($rsp9['conc2']) ?><span style="color: red;"><?= $adj1_9; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_9 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp9['conc3']) != 0) echo floatval($rsp9['conc3']) ?><span style="color: red;"><?= $adj2_9; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_9 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp9['conc4']) != 0) echo floatval($rsp9['conc4']) ?><span style="color: red;"><?= $adj3_9; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_9 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp9['conc5']) != 0) echo floatval($rsp9['conc5']) ?><span style="color: red;"><?= $adj4_9; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_9 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp9['conc6']) != 0) echo floatval($rsp9['conc6']) ?><span style="color: red;"><?= $adj5_9; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_9 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp9['conc7']) != 0) echo floatval($rsp9['conc7']) ?><span style="color: red;"><?= $adj6_9; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp9['conc8']) != 0) echo floatval($rsp9['conc8']) ?><span style="color: red;"><?= $adj7_9; ?></span></td>
                    <td colspan="2" rowspan="2" align="center"><strong>LAB. SAMPLE</strong></td>
                </tr>
                <!-- BARIS 10 -->
                <?php
                    $resep10 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 10 
                                                                        order by flag asc limit 1");
                    $rsp10 = mysqli_fetch_array($resep10);
                            
                    $KodeBaru10 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp10[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr10['ket'] == 'Suhu'){
                                echo $kdbr10['Product_Name'];
                            }else{
                                echo $kode_baru10;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_10 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama10);
                        $adj2_10 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama10);
                        $adj3_10 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama10);
                        $adj4_10 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama10);
                        $adj5_10 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama10);
                        $adj6_10 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama10);
                        $adj7_10 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama10);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_10 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp10['conc1']) != 0) echo floatval($rsp10['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_10 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp10['conc2']) != 0) echo floatval($rsp10['conc2']) ?><span style="color: red;"><?= $adj1_10; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_10 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp10['conc3']) != 0) echo floatval($rsp10['conc3']) ?><span style="color: red;"><?= $adj2_10; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_10 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp10['conc4']) != 0) echo floatval($rsp10['conc4']) ?><span style="color: red;"><?= $adj3_10; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_10 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp10['conc5']) != 0) echo floatval($rsp10['conc5']) ?><span style="color: red;"><?= $adj4_10; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_10 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp10['conc6']) != 0) echo floatval($rsp10['conc6']) ?><span style="color: red;"><?= $adj5_10; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_10 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp10['conc7']) != 0) echo floatval($rsp10['conc7']) ?><span style="color: red;"><?= $adj6_10; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp10['conc8']) != 0) echo floatval($rsp10['conc8']) ?><span style="color: red;"><?= $adj7_10; ?></span></td>
                </tr>
                <!-- BARIS 11 -->
                <?php
                    $resep11 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 11 
                                                                        order by flag asc limit 1");
                    $rsp11 = mysqli_fetch_array($resep11);
                            
                    $KodeBaru11 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp11[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr11['ket'] == 'Suhu'){
                                echo $kdbr11['Product_Name'];
                            }else{
                                echo $kode_baru11;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_11 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama11);
                        $adj2_11 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama11);
                        $adj3_11 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama11);
                        $adj4_11 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama11);
                        $adj5_11 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama11);
                        $adj6_11 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama11);
                        $adj7_11 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama11);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_11 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp11['conc1']) != 0) echo floatval($rsp11['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_11 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp11['conc2']) != 0) echo floatval($rsp11['conc2']) ?><span style="color: red;"><?= $adj1_11; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_11 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp11['conc3']) != 0) echo floatval($rsp11['conc3']) ?><span style="color: red;"><?= $adj2_11; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_11 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp11['conc4']) != 0) echo floatval($rsp11['conc4']) ?><span style="color: red;"><?= $adj3_11; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_11 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp11['conc5']) != 0) echo floatval($rsp11['conc5']) ?><span style="color: red;"><?= $adj4_11; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_11 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp11['conc6']) != 0) echo floatval($rsp11['conc6']) ?><span style="color: red;"><?= $adj5_11; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_11 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp11['conc7']) != 0) echo floatval($rsp11['conc7']) ?><span style="color: red;"><?= $adj6_11; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp11['conc8']) != 0) echo floatval($rsp11['conc8']) ?><span style="color: red;"><?= $adj7_11; ?></span></td>
                    <?php
                    $sql_Norder1 = mysqli_query($con,"SELECT `order` from tbl_orderchild 
                    where id_matching = '$data[id_matching]' and id_status = '$data[id_status]' order by flag limit 51,100");
                    $iteration = 1;
                    ?>
                    <td colspan="2" rowspan="10" valign="top">
                        <?php while ($no = mysqli_fetch_array($sql_Norder1)) { ?>
                            <?php echo $iteration++ . '.(' . $no['order']; ?>)&nbsp;&nbsp;&nbsp;
                        <?php } ?>
                        <?php
                            if($_GET['frm'] == 'bresep'){
                                $sqlRGB = "SELECT
                                                CAST(a.VALUEDECIMAL AS INT) AS R,
                                                CAST(a2.VALUEDECIMAL AS INT) AS G,
                                                CAST(a3.VALUEDECIMAL AS INT) AS B
                                            FROM
                                                USERGENERICGROUP u
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = u.ABSUNIQUEID AND a.FIELDNAME = 'RGBvalueR'
                                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = u.ABSUNIQUEID AND a2.FIELDNAME = 'RGBvalueG'
                                            LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = u.ABSUNIQUEID AND a3.FIELDNAME = 'RGBvalueB'
                                            WHERE 
                                                u.USERGENERICGROUPTYPECODE = 'CL1'
                                                AND u.CODE = '$data[color_code]'";
                                $stmtRGB = db2_exec($conn1, $sqlRGB);
                                $rowRGB  = db2_fetch_assoc($stmtRGB);

                                $r = $rowRGB['R'] ?? null;
                                $g = $rowRGB['G'] ?? null;
                                $b = $rowRGB['B'] ?? null;
                        ?>
                            <?php if ($r !== null && $g !== null && $b !== null) : ?>
                                <?php $hexRGB = sprintf("#%02x%02x%02x", $r, $g, $b); ?>
                                <div style="width: 100%; height: 200px; background-color: <?= $hexRGB; ?>; color: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: bold;">
                                    RGB(<?= $r ?>, <?= $g ?>, <?= $b ?>)
                                </div>
                            <?php endif;  ?>
                        <?php } ?>
                    </td>
                </tr>
                <!-- BARIS 12 -->
                <?php
                    $resep12 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 12
                                                                        order by flag asc limit 1");
                    $rsp12 = mysqli_fetch_array($resep12);
                    
                    $KodeBaru12 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp12[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr12['ket'] == 'Suhu'){
                                echo $kdbr12['Product_Name'];
                            }else{
                                echo $kode_baru12;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_12 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama12);
                        $adj2_12 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama12);
                        $adj3_12 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama12);
                        $adj4_12 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama12);
                        $adj5_12 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama12);
                        $adj6_12 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama12);
                        $adj7_12 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama12);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_12 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp12['conc1']) != 0) echo floatval($rsp12['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_12 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp12['conc2']) != 0) echo floatval($rsp12['conc2']) ?><span style="color: red;"><?= $adj1_12; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_12 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp12['conc3']) != 0) echo floatval($rsp12['conc3']) ?><span style="color: red;"><?= $adj2_12; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_12 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp12['conc4']) != 0) echo floatval($rsp12['conc4']) ?><span style="color: red;"><?= $adj3_12; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_12 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp12['conc5']) != 0) echo floatval($rsp12['conc5']) ?><span style="color: red;"><?= $adj4_12; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_12 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp12['conc6']) != 0) echo floatval($rsp12['conc6']) ?><span style="color: red;"><?= $adj5_12; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_12 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp12['conc7']) != 0) echo floatval($rsp12['conc7']) ?><span style="color: red;"><?= $adj6_12; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp12['conc8']) != 0) echo floatval($rsp12['conc8']) ?><span style="color: red;"><?= $adj7_12; ?></span></td>
                </tr>
                <!-- BARIS 13 -->
                <?php
                    $resep13 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 13 
                                                                        order by flag asc limit 1");
                    $rsp13 = mysqli_fetch_array($resep13);
                            
                    $KodeBaru13 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp13[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr13['ket'] == 'Suhu'){
                                echo $kdbr13['Product_Name'];
                            }else{
                                echo $kode_baru13;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_13 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama13);
                        $adj2_13 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama13);
                        $adj3_13 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama13);
                        $adj4_13 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama13);
                        $adj5_13 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama13);
                        $adj6_13 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama13);
                        $adj7_13 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama13);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_13 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp13['conc1']) != 0) echo floatval($rsp13['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_13 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp13['conc2']) != 0) echo floatval($rsp13['conc2']) ?><span style="color: red;"><?= $adj1_13; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_13 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp13['conc3']) != 0) echo floatval($rsp13['conc3']) ?><span style="color: red;"><?= $adj2_13; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_13 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp13['conc4']) != 0) echo floatval($rsp13['conc4']) ?><span style="color: red;"><?= $adj3_13; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_13 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp13['conc5']) != 0) echo floatval($rsp13['conc5']) ?><span style="color: red;"><?= $adj4_13; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_13 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp13['conc6']) != 0) echo floatval($rsp13['conc6']) ?><span style="color: red;"><?= $adj5_13; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_13 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp13['conc7']) != 0) echo floatval($rsp13['conc7']) ?><span style="color: red;"><?= $adj6_13; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp13['conc8']) != 0) echo floatval($rsp13['conc8']) ?><span style="color: red;"><?= $adj7_13; ?></span></td>
                </tr>
                <!-- BARIS 14 -->
                <?php
                    $resep14 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 14 
                                                                        order by flag asc limit 1");
                    $rsp14 = mysqli_fetch_array($resep14);
                            
                    $KodeBaru14 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp14[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr14['ket'] == 'Suhu'){
                                echo $kdbr14['Product_Name'];
                            }else{
                                echo $kode_baru14;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_14 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama14);
                        $adj2_14 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama14);
                        $adj3_14 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama14);
                        $adj4_14 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama14);
                        $adj5_14 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama14);
                        $adj6_14 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama14);
                        $adj7_14 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama14);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_14 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp14['conc1']) != 0) echo floatval($rsp14['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_14 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp14['conc2']) != 0) echo floatval($rsp14['conc2']) ?><span style="color: red;"><?= $adj1_14; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_14 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp14['conc3']) != 0) echo floatval($rsp14['conc3']) ?><span style="color: red;"><?= $adj2_14; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_14 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp14['conc4']) != 0) echo floatval($rsp14['conc4']) ?><span style="color: red;"><?= $adj3_14; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_14 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp14['conc5']) != 0) echo floatval($rsp14['conc5']) ?><span style="color: red;"><?= $adj4_14; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_14 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp14['conc6']) != 0) echo floatval($rsp14['conc6']) ?><span style="color: red;"><?= $adj5_14; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_14 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp14['conc7']) != 0) echo floatval($rsp14['conc7']) ?><span style="color: red;"><?= $adj6_14; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp14['conc8']) != 0) echo floatval($rsp14['conc8']) ?><span style="color: red;"><?= $adj7_14; ?></span></td>
                </tr>
                <!-- BARIS 15 -->
                <?php
                    $resep15 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 15 
                                                                        order by flag asc limit 1");
                    $rsp15 = mysqli_fetch_array($resep15);
                            
                    $KodeBaru15 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp15[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr15['ket'] == 'Suhu'){
                                echo $kdbr15['Product_Name'];
                            }else{
                                echo $kode_baru15;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_15 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama15);
                        $adj2_15 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama15);
                        $adj3_15 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama15);
                        $adj4_15 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama15);
                        $adj5_15 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama15);
                        $adj6_15 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama15);
                        $adj7_15 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama15);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_15 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp15['conc1']) != 0) echo floatval($rsp15['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_15 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp15['conc2']) != 0) echo floatval($rsp15['conc2']) ?><span style="color: red;"><?= $adj1_15; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_15 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp15['conc3']) != 0) echo floatval($rsp15['conc3']) ?><span style="color: red;"><?= $adj2_15; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_15 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp15['conc4']) != 0) echo floatval($rsp15['conc4']) ?><span style="color: red;"><?= $adj3_15; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_15 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp15['conc5']) != 0) echo floatval($rsp15['conc5']) ?><span style="color: red;"><?= $adj4_15; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_15 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp15['conc6']) != 0) echo floatval($rsp15['conc6']) ?><span style="color: red;"><?= $adj5_15; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_15 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp15['conc7']) != 0) echo floatval($rsp15['conc7']) ?><span style="color: red;"><?= $adj6_15; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp15['conc8']) != 0) echo floatval($rsp15['conc8']) ?><span style="color: red;"><?= $adj7_15; ?></span></td>
                </tr>
                <!-- BARIS 16 -->
                <?php
                    $resep16 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 16 
                                                                        order by flag asc limit 1");
                    $rsp16 = mysqli_fetch_array($resep16);
                            
                    $KodeBaru16 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp16[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr16['ket'] == 'Suhu'){
                                echo $kdbr16['Product_Name'];
                            }else{
                                echo $kode_baru16;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_16 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama16);
                        $adj2_16 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama16);
                        $adj3_16 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama16);
                        $adj4_16 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama16);
                        $adj5_16 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama16);
                        $adj6_16 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama16);
                        $adj7_16 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama16);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_16 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp16['conc1']) != 0) echo floatval($rsp16['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_16 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp16['conc2']) != 0) echo floatval($rsp16['conc2']) ?><span style="color: red;"><?= $adj1_16; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_16 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp16['conc3']) != 0) echo floatval($rsp16['conc3']) ?><span style="color: red;"><?= $adj2_16; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_16 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp16['conc4']) != 0) echo floatval($rsp16['conc4']) ?><span style="color: red;"><?= $adj3_16; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_16 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp16['conc5']) != 0) echo floatval($rsp16['conc5']) ?><span style="color: red;"><?= $adj4_16; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_16 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp16['conc6']) != 0) echo floatval($rsp16['conc6']) ?><span style="color: red;"><?= $adj5_16; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_16 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp16['conc7']) != 0) echo floatval($rsp16['conc7']) ?><span style="color: red;"><?= $adj6_16; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp16['conc8']) != 0) echo floatval($rsp16['conc8']) ?><span style="color: red;"><?= $adj7_16; ?></span></td>
                </tr>
                <!-- BARIS 17 -->
                <?php
                    $resep17 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 17 
                                                                        order by flag asc limit 1");
                    $rsp17 = mysqli_fetch_array($resep17);
                            
                    $KodeBaru17 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp17[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr17['ket'] == 'Suhu'){
                                echo $kdbr17['Product_Name'];
                            }else{
                                echo $kode_baru17;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_17 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama17);
                        $adj2_17 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama17);
                        $adj3_17 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama17);
                        $adj4_17 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama17);
                        $adj5_17 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama17);
                        $adj6_17 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama17);
                        $adj7_17 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama17);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_17 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp17['conc1']) != 0) echo floatval($rsp17['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_17 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp17['conc2']) != 0) echo floatval($rsp17['conc2']) ?><span style="color: red;"><?= $adj1_17; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_17 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp17['conc3']) != 0) echo floatval($rsp17['conc3']) ?><span style="color: red;"><?= $adj2_17; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_17 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp17['conc4']) != 0) echo floatval($rsp17['conc4']) ?><span style="color: red;"><?= $adj3_17; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_17 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp17['conc5']) != 0) echo floatval($rsp17['conc5']) ?><span style="color: red;"><?= $adj4_17; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_17 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp17['conc6']) != 0) echo floatval($rsp17['conc6']) ?><span style="color: red;"><?= $adj5_17; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_17 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp17['conc7']) != 0) echo floatval($rsp17['conc7']) ?><span style="color: red;"><?= $adj6_17; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp17['conc8']) != 0) echo floatval($rsp17['conc8']) ?><span style="color: red;"><?= $adj7_17; ?></span></td>
                </tr>
                <!-- BARIS 18 -->
                <?php
                    $resep18 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 18 
                                                                        order by flag asc limit 1");
                    $rsp18 = mysqli_fetch_array($resep18);
                            
                    $KodeBaru18 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp18[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr18['ket'] == 'Suhu'){
                                echo $kdbr18['Product_Name'];
                            }else{
                                echo $kode_baru18;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_18 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama18);
                        $adj2_18 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama18);
                        $adj3_18 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama18);
                        $adj4_18 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama18);
                        $adj5_18 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama18);
                        $adj6_18 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama18);
                        $adj7_18 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama18);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_18 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp18['conc1']) != 0) echo floatval($rsp18['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_18 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp18['conc2']) != 0) echo floatval($rsp18['conc2']) ?><span style="color: red;"><?= $adj1_18; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_18 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp18['conc3']) != 0) echo floatval($rsp18['conc3']) ?><span style="color: red;"><?= $adj2_18; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_18 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp18['conc4']) != 0) echo floatval($rsp18['conc4']) ?><span style="color: red;"><?= $adj3_18; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_18 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp18['conc5']) != 0) echo floatval($rsp18['conc5']) ?><span style="color: red;"><?= $adj4_18; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_18 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp18['conc6']) != 0) echo floatval($rsp18['conc6']) ?><span style="color: red;"><?= $adj5_18; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_18 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp18['conc7']) != 0) echo floatval($rsp18['conc7']) ?><span style="color: red;"><?= $adj6_18; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp18['conc8']) != 0) echo floatval($rsp18['conc8']) ?><span style="color: red;"><?= $adj7_18; ?></span></td>
                </tr>
                <!-- BARIS 19 -->
                <?php
                    $resep19 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 19 
                                                                        order by flag asc limit 1");
                    $rsp19 = mysqli_fetch_array($resep19);
                            
                    $KodeBaru19 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp19[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr19['ket'] == 'Suhu'){
                                echo $kdbr19['Product_Name'];
                            }else{
                                echo $kode_baru19;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_19 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama19);
                        $adj2_19 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama19);
                        $adj3_19 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama19);
                        $adj4_19 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama19);
                        $adj5_19 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama19);
                        $adj6_19 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama19);
                        $adj7_19 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama19);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_19 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp19['conc1']) != 0) echo floatval($rsp19['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_19 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp19['conc2']) != 0) echo floatval($rsp19['conc2']) ?><span style="color: red;"><?= $adj1_19; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_19 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp19['conc3']) != 0) echo floatval($rsp19['conc3']) ?><span style="color: red;"><?= $adj2_19; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_19 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp19['conc4']) != 0) echo floatval($rsp19['conc4']) ?><span style="color: red;"><?= $adj3_19; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_19 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp19['conc5']) != 0) echo floatval($rsp19['conc5']) ?><span style="color: red;"><?= $adj4_19; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_19 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp19['conc6']) != 0) echo floatval($rsp19['conc6']) ?><span style="color: red;"><?= $adj5_19; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_19 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp19['conc7']) != 0) echo floatval($rsp19['conc7']) ?><span style="color: red;"><?= $adj6_19; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp19['conc8']) != 0) echo floatval($rsp19['conc8']) ?><span style="color: red;"><?= $adj7_19; ?></span></td>
                </tr>
                <!-- BARIS 20 -->
                <?php
                    $resep20 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 20 
                                                                        order by flag asc limit 1");
                    $rsp20 = mysqli_fetch_array($resep20);
                            
                    $KodeBaru20 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp20[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr20['ket'] == 'Suhu'){
                                echo $kdbr20['Product_Name'];
                            }else{
                                echo $kode_baru20;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_20 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama20);
                        $adj2_20 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama20);
                        $adj3_20 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama20);
                        $adj4_20 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama20);
                        $adj5_20 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama20);
                        $adj6_20 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama20);
                        $adj7_20 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama20);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_20 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp20['conc1']) != 0) echo floatval($rsp20['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_20 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp20['conc2']) != 0) echo floatval($rsp20['conc2']) ?><span style="color: red;"><?= $adj1_20; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_20 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp20['conc3']) != 0) echo floatval($rsp20['conc3']) ?><span style="color: red;"><?= $adj2_20; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_20 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp20['conc4']) != 0) echo floatval($rsp20['conc4']) ?><span style="color: red;"><?= $adj3_20; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_20 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp20['conc5']) != 0) echo floatval($rsp20['conc5']) ?><span style="color: red;"><?= $adj4_20; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_20 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp20['conc6']) != 0) echo floatval($rsp20['conc6']) ?><span style="color: red;"><?= $adj5_20; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_20 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp20['conc7']) != 0) echo floatval($rsp20['conc7']) ?><span style="color: red;"><?= $adj6_20; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp20['conc8']) != 0) echo floatval($rsp20['conc8']) ?><span style="color: red;"><?= $adj7_20; ?></span></td>
                </tr>
                <!-- BARIS 21 -->
                <?php
                    $resep21 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 21 
                                                                        order by flag asc limit 1");
                    $rsp21 = mysqli_fetch_array($resep21);
                    
                    $KodeBaru21 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp21[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr21['ket'] == 'Suhu'){
                                echo $kdbr21['Product_Name'];
                            }else{
                                echo $kode_baru21;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_21 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama21);
                        $adj2_21 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama21);
                        $adj3_21 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama21);
                        $adj4_21 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama21);
                        $adj5_21 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama21);
                        $adj6_21 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama21);
                        $adj7_21 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama21);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_21 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp21['conc1']) != 0) echo floatval($rsp21['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_21 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp21['conc2']) != 0) echo floatval($rsp21['conc2']) ?><span style="color: red;"><?= $adj1_21; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_21 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp21['conc3']) != 0) echo floatval($rsp21['conc3']) ?><span style="color: red;"><?= $adj2_21; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_21 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp21['conc4']) != 0) echo floatval($rsp21['conc4']) ?><span style="color: red;"><?= $adj3_21; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_21 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp21['conc5']) != 0) echo floatval($rsp21['conc5']) ?><span style="color: red;"><?= $adj4_21; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_21 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp21['conc6']) != 0) echo floatval($rsp21['conc6']) ?><span style="color: red;"><?= $adj5_21; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_21 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp21['conc7']) != 0) echo floatval($rsp21['conc7']) ?><span style="color: red;"><?= $adj6_21; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp21['conc8']) != 0) echo floatval($rsp21['conc8']) ?><span style="color: red;"><?= $adj7_21; ?></span></td>
                    <td width="20%" rowspan="2" align="center"><strong>BEFORE SOAPING</strong></td>
                    <td width="21%" rowspan="2" align="center"><strong>T-SIDE</strong></td>
                </tr>
                <!-- BARIS 22 -->
                <?php
                    $resep22 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 22 
                                                                        order by flag asc limit 1");
                    $rsp22 = mysqli_fetch_array($resep22);
                            
                    $KodeBaru22 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp22[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr22['ket'] == 'Suhu'){
                                echo $kdbr22['Product_Name'];
                            }else{
                                echo $kode_baru22;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_22 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama22);
                        $adj2_22 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama22);
                        $adj3_22 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama22);
                        $adj4_22 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama22);
                        $adj5_22 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama22);
                        $adj6_22 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama22);
                        $adj7_22 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama22);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_22 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp22['conc1']) != 0) echo floatval($rsp22['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_22 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp22['conc2']) != 0) echo floatval($rsp22['conc2']) ?><span style="color: red;"><?= $adj1_22; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_22 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp22['conc3']) != 0) echo floatval($rsp22['conc3']) ?><span style="color: red;"><?= $adj2_22; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_22 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp22['conc4']) != 0) echo floatval($rsp22['conc4']) ?><span style="color: red;"><?= $adj3_22; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_22 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp22['conc5']) != 0) echo floatval($rsp22['conc5']) ?><span style="color: red;"><?= $adj4_22; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_22 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp22['conc6']) != 0) echo floatval($rsp22['conc6']) ?><span style="color: red;"><?= $adj5_22; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_22 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp22['conc7']) != 0) echo floatval($rsp22['conc7']) ?><span style="color: red;"><?= $adj6_22; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp22['conc8']) != 0) echo floatval($rsp22['conc8']) ?><span style="color: red;"><?= $adj7_22; ?></span></td>
                </tr>
                <!-- BARIS 23 -->
                <?php
                    $resep23 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 23 
                                                                        order by flag asc limit 1");
                    $rsp23 = mysqli_fetch_array($resep23);
                    
                    $KodeBaru23 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp23[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr23['ket'] == 'Suhu'){
                                echo $kdbr23['Product_Name'];
                            }else{
                                echo $kode_baru23;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_23 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama23);
                        $adj2_23 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama23);
                        $adj3_23 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama23);
                        $adj4_23 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama23);
                        $adj5_23 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama23);
                        $adj6_23 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama23);
                        $adj7_23 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama23);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_23 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp23['conc1']) != 0) echo floatval($rsp23['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_23 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp23['conc2']) != 0) echo floatval($rsp23['conc2']) ?><span style="color: red;"><?= $adj1_23; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_23 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp23['conc3']) != 0) echo floatval($rsp23['conc3']) ?><span style="color: red;"><?= $adj2_23; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_23 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp23['conc4']) != 0) echo floatval($rsp23['conc4']) ?><span style="color: red;"><?= $adj3_23; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_23 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp23['conc5']) != 0) echo floatval($rsp23['conc5']) ?><span style="color: red;"><?= $adj4_23; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_23 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp23['conc6']) != 0) echo floatval($rsp23['conc6']) ?><span style="color: red;"><?= $adj5_23; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_23 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp23['conc7']) != 0) echo floatval($rsp23['conc7']) ?><span style="color: red;"><?= $adj6_23; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp23['conc8']) != 0) echo floatval($rsp23['conc8']) ?><span style="color: red;"><?= $adj7_23; ?></span></td>
                    <td rowspan="7" style="text-align: center; vertical-align: middle;">
                        <?php if($_GET['frm'] == 'bresep') : ?>
                            <?php if(is_numeric($data['suhu_chamber']) > 0) : ?>
                                <center>
                                    <div class="stamp-box-suhuchamber">
                                        SUHU CHAMBER <?= $data['suhu_chamber']; ?>°C
                                    </div>
                                </center>
                            <?php elseif($data['suhu_chamber'] == 'none') : ?>
                                <img src="../../dist/img/none suhu chamber.png" width="300" height="100" alt="Suhu Chamber">
                            <?php else : ?>
                                &nbsp;
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td rowspan="7" style="text-align: center; vertical-align: middle;">
                        <?php if($_GET['frm'] == 'bresep') : ?>
                            <?php if($data['warna_flourescent'] == '1') : ?>
                                <center>
                                    <div class="stamp-box-warnafluorescent">
                                        WARNA FLUORESCENT
                                    </div>
                                </center>
                            <?php else : ?>
                                &nbsp;
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <!-- BARIS 24 -->
                <?php
                    $resep24 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 24 
                                                                        order by flag asc limit 1");
                    $rsp24 = mysqli_fetch_array($resep24);
                    
                    $KodeBaru24 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp24[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr24['ket'] == 'Suhu'){
                                echo $kdbr24['Product_Name'];
                            }else{
                                echo $kode_baru24;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_24 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama24);
                        $adj2_24 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama24);
                        $adj3_24 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama24);
                        $adj4_24 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama24);
                        $adj5_24 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama24);
                        $adj6_24 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama24);
                        $adj7_24 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama24);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_24 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp24['conc1']) != 0) echo floatval($rsp24['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_24 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp24['conc2']) != 0) echo floatval($rsp24['conc2']) ?><span style="color: red;"><?= $adj1_24; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_24 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp24['conc3']) != 0) echo floatval($rsp24['conc3']) ?><span style="color: red;"><?= $adj2_24; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_24 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp24['conc4']) != 0) echo floatval($rsp24['conc4']) ?><span style="color: red;"><?= $adj3_24; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_24 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp24['conc5']) != 0) echo floatval($rsp24['conc5']) ?><span style="color: red;"><?= $adj4_24; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_24 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp24['conc6']) != 0) echo floatval($rsp24['conc6']) ?><span style="color: red;"><?= $adj5_24; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_24 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp24['conc7']) != 0) echo floatval($rsp24['conc7']) ?><span style="color: red;"><?= $adj6_24; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp24['conc8']) != 0) echo floatval($rsp24['conc8']) ?><span style="color: red;"><?= $adj7_24; ?></span></td>
                </tr>
                <!-- BARIS 25 -->
                <?php
                    $resep25 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 25 
                                                                        order by flag asc limit 1");
                    $rsp25 = mysqli_fetch_array($resep25);
                    
                    $KodeBaru25 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp25[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr25['ket'] == 'Suhu'){
                                echo $kdbr25['Product_Name'];
                            }else{
                                echo $kode_baru25;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_25 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama25);
                        $adj2_25 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama25);
                        $adj3_25 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama25);
                        $adj4_25 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama25);
                        $adj5_25 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama25);
                        $adj6_25 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama25);
                        $adj7_25 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama25);
                    ?>
                    <td style="font-weight: bold; <?= $adj1_25 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp25['conc1']) != 0) echo floatval($rsp25['conc1']) ?></td>
                    <td style="font-weight: bold; <?= $adj2_25 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp25['conc2']) != 0) echo floatval($rsp25['conc2']) ?><span style="color: red;"><?= $adj1_25; ?></span></td>
                    <td style="font-weight: bold; <?= $adj3_25 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp25['conc3']) != 0) echo floatval($rsp25['conc3']) ?><span style="color: red;"><?= $adj2_25; ?></span></td>
                    <td style="font-weight: bold; <?= $adj4_25 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp25['conc4']) != 0) echo floatval($rsp25['conc4']) ?><span style="color: red;"><?= $adj3_25; ?></span></td>
                    <td style="font-weight: bold; <?= $adj5_25 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp25['conc5']) != 0) echo floatval($rsp25['conc5']) ?><span style="color: red;"><?= $adj4_25; ?></span></td>
                    <td style="font-weight: bold; <?= $adj6_25 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp25['conc6']) != 0) echo floatval($rsp25['conc6']) ?><span style="color: red;"><?= $adj5_25; ?></span></td>
                    <td style="font-weight: bold; <?= $adj7_25 ? 'text-decoration: line-through;' : '' ?>"><?php if (floatval($rsp25['conc7']) != 0) echo floatval($rsp25['conc7']) ?><span style="color: red;"><?= $adj6_25; ?></span></td>
                    <td style="font-weight: bold;"><?php if (floatval($rsp25['conc8']) != 0) echo floatval($rsp25['conc8']) ?><span style="color: red;"><?= $adj7_25; ?></span></td>
                </tr>
                <!-- BARIS 26 -->
                <?php
                    $resep26 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 26 
                                                                        order by flag asc limit 1");
                    $rsp26 = mysqli_fetch_array($resep26);
                    
                    $KodeBaru26 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp26[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr26['ket'] == 'Suhu'){
                                echo $kdbr26['Product_Name'];
                            }else{
                                echo $kode_baru26;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama26);
                        $adj2_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama26);
                        $adj3_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama26);
                        $adj4_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama26);
                        $adj5_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama26);
                        $adj6_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama26);
                        $adj7_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama26);

                        $coloristname1  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1');
                        $coloristname2  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2');
                        $coloristname3  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3');
                        $coloristname4  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4');
                        $coloristname5  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5');
                        $coloristname6  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6');
                        $coloristname7  = getColoristName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7');
                        
                        $editorname1  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1');
                        $editorname2  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2');
                        $editorname3  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3');
                        $editorname4  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4');
                        $editorname5  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5');
                        $editorname6  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6');
                        $editorname7  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7');
                    ?>
                    <?php if($_GET['frm'] == 'bresep') : ?>
                        <td style="font-weight: bold;">Colorist Name</td>
                        <td style="font-weight: bold;"><?= $coloristname1; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname2; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname3; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname4; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname5; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname6; ?></td>
                        <td style="font-weight: bold;"><?= $coloristname7; ?></td>
                    <?php else : ?>
                        <td style="font-weight: bold;"><?php if (floatval($rsp26['conc1']) != 0) echo floatval($rsp26['conc1']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp26['conc2']) != 0) echo floatval($rsp26['conc2']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp26['conc3']) != 0) echo floatval($rsp26['conc3']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp26['conc4']) != 0) echo floatval($rsp26['conc4']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp26['conc5']) != 0) echo floatval($rsp26['conc5']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp26['conc6']) != 0) echo floatval($rsp26['conc6']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp26['conc7']) != 0) echo floatval($rsp26['conc7']) ?></td>
                    <?php endif; ?>
                </tr>
                <!-- BARIS 27 -->
                <?php
                    $resep27 = mysqli_query($con,"SELECT * FROM tbl_matching_detail where id_matching = '$data[id_matching]'
                                                                        and id_status = '$data[id_status]' 
                                                                        and flag = 27 
                                                                        order by flag asc limit 1");
                    $rsp27 = mysqli_fetch_array($resep27);
                            
                    $KodeBaru27 = mysqli_query($con,"SELECT * FROM tbl_dyestuff where code = '$rsp27[kode]'
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
                    <td style="font-weight: bold;">
                        <?php 
                            if($kdbr27['ket'] == 'Suhu'){
                                echo $kdbr27['Product_Name'];
                            }else{
                                echo $kode_baru27;
                            }
                        ?>
                    </td>
                    <?php 
                        $adj1_27 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama27);
                        $adj2_27 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama27);
                        $adj3_27 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama27);
                        $adj4_27 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama27);
                        $adj5_27 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama27);
                        $adj6_27 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama27);
                        $adj7_27 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama27);
                    ?>
                    <?php 
                        $adj1_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1', $kode_lama26);
                        $adj2_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2', $kode_lama26);
                        $adj3_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3', $kode_lama26);
                        $adj4_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4', $kode_lama26);
                        $adj5_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5', $kode_lama26);
                        $adj6_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6', $kode_lama26);
                        $adj7_26 = getRecipeAdj1($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7', $kode_lama26);

                        $editorname1  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S1');
                        $editorname2  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S2');
                        $editorname3  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S3');
                        $editorname4  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S4');
                        $editorname5  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S5');
                        $editorname6  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S6');
                        $editorname7  = getEditorName($conn1, $data['recipe_code_1'], $data['recipe_code_2'], $suffixcode . 'D-S7');
                    ?>
                    <?php if($_GET['frm'] == 'bresep') : ?>
                        <td style="font-weight: bold;">Editor Name</td>
                        <td style="font-weight: bold;"><?= $editorname1; ?></td>
                        <td style="font-weight: bold;"><?= $editorname2; ?></td>
                        <td style="font-weight: bold;"><?= $editorname3; ?></td>
                        <td style="font-weight: bold;"><?= $editorname4; ?></td>
                        <td style="font-weight: bold;"><?= $editorname5; ?></td>
                        <td style="font-weight: bold;"><?= $editorname6; ?></td>
                        <td style="font-weight: bold;"><?= $editorname7; ?></td>
                    <?php else : ?>
                        <td style="font-weight: bold;"><?php if (floatval($rsp27['conc1']) != 0) echo floatval($rsp27['conc1']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp27['conc2']) != 0) echo floatval($rsp27['conc2']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp27['conc3']) != 0) echo floatval($rsp27['conc3']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp27['conc4']) != 0) echo floatval($rsp27['conc4']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp27['conc5']) != 0) echo floatval($rsp27['conc5']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp27['conc6']) != 0) echo floatval($rsp27['conc6']) ?></td>
                        <td style="font-weight: bold;"><?php if (floatval($rsp27['conc7']) != 0) echo floatval($rsp27['conc7']) ?></td>
                    <?php endif; ?>
                </tr>

                <tr style="height: 0.4in">
                    <td colspan="4">&nbsp;</td>
                    <td colspan="3" align="center">T-SIDE</td>
                    <td colspan="3" align="center">C.SIDE</td>
                </tr>
            <!-- BARIS -->
        <?php endif; ?>

        <tr style="height: 0.4in">
            <td colspan="4" align="center">Temp x Time</td>
            <?php if($_GET['frm'] == 'bresep') : ?>

                <td colspan="3" align="center" style="color:red; text-decoration: line-through;"><?php echo ($data['tside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['tside_min']) ?> min</td>
                <td colspan="3" align="center" style="color:red; text-decoration: line-through;"><?php echo ($data['cside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['cside_min']) ?> min</td>
            <?php else : ?>
                <td colspan="3" align="center" ><?php echo ($data['tside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['tside_min']) ?> min</td>
                <td colspan="3" align="center"><?php echo ($data['cside_c']) ?> &deg;C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data['cside_min']) ?> min</td>
            <?php endif; ?>
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
            <td style="font-weight: bold;" colspan="2">
                CIE WI &nbsp;&nbsp;: <?php echo number_format($data['cie_wi'], 2); ?>
                <pre style="display: inline-block; margin-left: 200px;">CIE TINT : <?php echo number_format($data['cie_tint'], 2); ?></pre>
                <pre style="display: inline-block; margin-left: 200px;">YELLOWNESS : <?php echo number_format($data['yellowness'], 2); ?></pre>
            </td>
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
            <?php if($_GET['frm'] == 'bresep') : ?>
                <td style="text-align: left; vertical-align: top;" colspan="2" rowspan="4" class="adj" data-adj="info-dyeing" align="center"><div class="tooltip-wrapper"><strong>Info Dyeing : <?= getCommentAdj($con, 'info-dyeing') ?></strong><span class="tooltip-text"><?= getCommentAdj($con, 'info-dyeing') ?></span></div></td>
            <?php else : ?>
                <td colspan="2" rowspan="4" valign="top">Info Dyeing : <?php echo $data['remark_dye'] ?></td>
            <?php endif; ?>
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
            <?php if($_GET['frm'] == 'bresep') : ?>
                <td colspan="10" style="text-align: left; vertical-align: top;">
                    <?php
                        $sqlHistoryRecipe = "SELECT 
                                                GROUP_CONCAT(CONCAT(TRIM(c.no_order), ' (', TRIM(c.lot), ')') ORDER BY c.no_order SEPARATOR ', ') AS no_order
                                            FROM
                                                `tbl_hasilcelup` a
                                            LEFT JOIN tbl_montemp b ON b.id = a.id_montemp
                                            LEFT JOIN tbl_schedule c ON c.id = b.id_schedule
                                            WHERE
                                                rcode = '$data[no_resep]'";
                        $stmtHistoryRecipe  = mysqli_query($con_db_dyeing, $sqlHistoryRecipe);
                    ?>
                    <?php while ($rowHistoryRecipe = mysqli_fetch_array($stmtHistoryRecipe)) : ?>
                        <?= $rowHistoryRecipe['no_order'] ?>
                    <?php endwhile; ?>
                </td>
            <?php else : ?>
                <td colspan="4">&nbsp;</td>
                <td colspan="6">&nbsp;</td>
            <?php endif; ?>
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

    <?php if($_GET['frm'] == 'bresep') : ?>
        <!-- Custom context menu -->
        <div id="contextMenu">
            <ul>
                <li id="editComment">Edit Comment</li>
            </ul>
        </div>
        <!-- Modal -->
        <div id="commentModal">
            <div id="commentModalContent">
                <h3>Edit Comment</h3>
                <textarea id="commentInput"></textarea><br/>
                <button id="saveComment">Save</button>
                <button id="cancelComment">Cancel</button>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>
<script>
    <?php if($_GET['frm'] == 'bresep') : ?>
    <?php else : ?>
        setTimeout(function() {
            window.print()
        }, 1500);
    <?php endif; ?>
</script>
<script>
    const contextMenu = document.getElementById('contextMenu');
    const commentModal = document.getElementById('commentModal');
    const commentInput = document.getElementById('commentInput');
    let currentCell = null;

    document.querySelectorAll('.adj').forEach(td => {
        td.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        currentCell = this;
        contextMenu.style.top = `${e.pageY}px`;
        contextMenu.style.left = `${e.pageX}px`;
        contextMenu.style.display = 'block';
        });
    });

    document.getElementById('editComment').addEventListener('click', function() {
        const tooltipText = currentCell.querySelector('.tooltip-text');
        commentInput.value = tooltipText ? tooltipText.textContent : '';
        commentModal.style.display = 'block';
        contextMenu.style.display = 'none';
    });

    document.getElementById('saveComment').addEventListener('click', function() {
        const comment = currentCell.querySelector('.comment');
        // comment.textContent = commentInput.value;
        commentModal.style.display = 'none';
        // Ambil ids & idm dari URL GET (pakai PHP inline)
        const ids = '<?= $_GET['ids'] ?? '' ?>';
        const idm = '<?= $_GET['idm'] ?? '' ?>';
        const adjNo = currentCell.dataset.adj; // ⬅️ ambil data-adj

        // Kirim via $.post (pola kamu)
        $.post('../ajax/save_comment.php', {
            ids: ids,
            idm: idm,
            adj_no: adjNo, // ⬅️ kirim nomor adj
            comment: commentInput.value
        }, function(response) {
            if (response.trim() === 'SAVED') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Comment berhasil disimpan!',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true
                }).then(() => {
                    location.reload(); // reload setelah alert tertutup
                });
            } else if (response.trim() === 'EDITED') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Comment berhasil diupdate!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    location.reload(); // reload setelah alert tertutup
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menyimpan comment.'
                })
            }
        });
    });

    document.getElementById('cancelComment').addEventListener('click', function() {
        commentModal.style.display = 'none';
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#contextMenu')) {
        contextMenu.style.display = 'none';
        }
    });

    window.addEventListener('resize', () => {
        contextMenu.style.display = 'none';
    });
</script>
<script>
    const btn = document.getElementById('adjButton');
    const tooltip = document.getElementById('tooltipContainer');
    let hideTimeout;

    btn.addEventListener('mouseenter', async (e) => {
        clearTimeout(hideTimeout);

        const rect = btn.getBoundingClientRect();
        tooltip.style.top = (rect.bottom + window.scrollY) + 'px';
        tooltip.style.left = (rect.left + window.scrollX) + 'px';

        // Ambil data dari server
        const number = '<?= $data['no_warna']; ?>'; // <- Ganti dengan nilai dinamis kalau perlu
        const no_order = '<?= $data['no_order']; ?>'; // <- Ganti dengan nilai dinamis kalau perlu
        try {
            const response = await fetch('quality_result.php?number=' + number + '&no_order=' + no_order);
            const html = await response.text();
            tooltip.innerHTML = html;
            tooltip.style.display = 'block';
        } catch (err) {
            tooltip.innerHTML = '<p>Error loading data.</p>';
            tooltip.style.display = 'block';
        }
    });

    btn.addEventListener('mouseleave', () => {
        hideTimeout = setTimeout(() => {
            tooltip.style.display = 'none';
        }, 300);
    });

    tooltip.addEventListener('mouseenter', () => {
        clearTimeout(hideTimeout);
    });

    tooltip.addEventListener('mouseleave', () => {
        tooltip.style.display = 'none';
    });
</script>