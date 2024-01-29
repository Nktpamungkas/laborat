<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
$idkk = $_REQUEST['idkk'];
$act = $_GET['g'];
//-
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="styles_cetak.css" rel="stylesheet" type="text/css">
  <title>Cetak Kartu Matching</title>
  <style>
    .hurufvertical {
      writing-mode: tb-rl;
      -webkit-transform: rotate(-90deg);
      -moz-transform: rotate(-90deg);
      -o-transform: rotate(-90deg);
      -ms-transform: rotate(-90deg);
      transform: rotate(180deg);
      white-space: nowrap;
      float: left;
    }
  </style>
</head>

<body>
  <?php
    $qry = mysqli_query($con, "SELECT *,DATE_FORMAT(now(),'%d %M %Y') as tgl FROM tbl_matching WHERE no_resep='$idkk'");
    $data = mysqli_fetch_array($qry);
    $ip_num = $_SERVER['REMOTE_ADDR'];
    mysqli_query($con, "INSERT INTO log_status_matching SET
                        `ids` = '$idkk', 
                        `status` = 'print', 
                        `info` = 'cetak kartu matching', 
                        `do_by` = '$_SESSION[userLAB]', 
                        `do_at` = '$time', 
                        `ip_address` = '$ip_num'");
  ?>
  <table width="100%" border="0">
    <tr style="font-size: 10px;">
      <td width="13%">GRAMASI PERMINTAAN:</td>
      <td width="10%"><strong><?Php echo $data['lebar'] . " x " . $data['gramasi'] . " gr/m2"; ?></strong></td>
      <td width="11%">GRAMASI AKTUAL:</td>
      <td width="11%">&nbsp;</td>
      <td width="16%">BERAT:</td>
      <td width="15%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="15%" style="text-align: right;">No. Form : FW-12-LAB-05</td>
    </tr>
  </table>
  <table width="100%" border="0" class="table-list1">
    <tbody>
      <tr>
        <td width="80" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">R</strong>SUFFIX</td>
        <td width="18" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td width="58" style="border-left:0px #000000 solid;"><strong>
            <?php
            // if (substr($data['no_resep'], 0, 2) == 'D2' OR substr($data['no_resep'], 0, 1) == 'C' OR substr($data['no_resep'], 0, 2) == 'DR' OR substr($data['no_resep'], 0, 2) == 'OB') {  
            //   echo substr($data['no_resep'], 2).'L';
            // }elseif (substr($data['no_resep'], 0, 1) == 'R' or substr($data['no_resep'], 0, 1) == 'A'){
            //   echo substr($data['no_resep'], 1).'L';
            // }
            echo $data['no_resep'];
            ?>
          </strong>
        </td>
        <td width="94" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">I</strong>TEM</td>
        <td width="12" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td colspan="5" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_item']; ?></strong></td>
        <td colspan="6" align="center" style="border-bottom:0px #000000 solid;">
          <?php if ($data['jenis_matching'] == "L/D" or $data['jenis_matching'] == "LD NOW") : ?>
            <strong style="font-size: 24px;">KARTU MATCHING L/D</strong>
          <?php else : ?>
            <strong style="font-size: 24px;">KARTU MATCHING</strong>
          <?php endif; ?>
        </td>
        <td width="154" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">L</strong>ANGGANAN</td>
        <td width="10" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td width="268" style="border-left:0px #000000 solid;"><strong style="font-size: 9px;"><?Php echo $data['langganan']; ?></strong></td>
      </tr>
      <tr>
        <td style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">M</strong>ATCHER</td>
        <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td style="border-left:0px #000000 solid;">&nbsp;</td>
        <td style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">P</strong>O <strong style="font-size: 21px;">G</strong>REIGE</td>
        <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td colspan="5" style="border-left:0px #000000 solid;"><strong><?Php if ($data['no_po'] == "NULL") {
                                                                          echo " ";
                                                                        } else {
                                                                          echo $data['no_po'];
                                                                        }   ?></strong></td>
        <td colspan="6" align="left" valign="top" style="border-top:0px #000000 solid;">CATATAN:</td>
        <td style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">P</strong>ROSES
        </td>
        <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['proses']; ?></strong></td>
      </tr>
      <tr>
        <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">T</strong>IME <strong style="font-size: 21px;">I</strong>N</td>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td rowspan="2" style="border-left:0px #000000 solid;"><strong><?Php echo date("d-m-Y", strtotime($data['tgl_in'])); ?></strong></td>
        <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">K</strong>AIN</td>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td colspan="5" rowspan="2" style="border-left:0px #000000 solid;"><strong style="font-size: 8px;"><?Php if ($data['jenis_kain'] == "NULL") {
                                                                                                              echo "";
                                                                                                            } else {
                                                                                                              echo $data['jenis_kain'];
                                                                                                            } ?></strong></td>
        <td width="90" rowspan="2">Recipe Code</td>
        <td colspan="2" rowspan="2" style="border-right:0px #000000 solid;"><strong><?Php echo $data['recipe_code']; ?></strong></td>
        <td width="107" rowspan="2">Color Code</td>
        <td colspan="2" rowspan="2" style="border-right:0px #000000 solid;"><?Php echo $data['color_code']; ?></strong></td>
        <td rowspan="2" style="border-right:0px #000000 solid;">STD COCOK WARNA</td>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td style="border-left:0px #000000 solid;">1. <strong><?Php echo $data['cocok_warna']; ?></strong></td>
      </tr>
      <tr>
        <td style="border-left:0px #000000 solid;">2.</td>
      </tr>
      <tr>
        <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">D</strong>ELIVERY</td>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td rowspan="2" style="border-left:0px #000000 solid;"><strong><?Php echo date("d-m-Y", strtotime($data['tgl_delivery'])); ?></strong></td>
        <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">B</strong>ENANG</td>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td colspan="5" rowspan="2" style="border-left:0px #000000 solid;"><strong style="font-size: 8px;"><?Php if ($data['benang'] == "NULL") {
                                                                                                              echo "";
                                                                                                            } else {
                                                                                                              echo $data['benang'];
                                                                                                            } ?></strong></td>
        <td rowspan="4">T-Side</td>
        <td width="85" style="border-right:0px #000000 solid;">L : R </td>
        <td width="82" style="border-left:0px #000000 solid;">:</td>
        <td rowspan="4">C-Side</td>
        <td width="75" style="border-right:0px #000000 solid;">L : R </td>
        <td width="75" style="border-left:0px #000000 solid;">:</td>
        <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">W</strong>ARNA</td>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td rowspan="2" style="border-left:0px #000000 solid;"><strong style="font-size: 9px;"><?Php echo $data['warna']; ?></strong></td>
      </tr>
      <tr>
        <td colspan="2" align="right">&nbsp;&nbsp; &deg;C X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Menit</td>
        <td colspan="2" align="right">&nbsp;&nbsp; &deg;C X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Menit</td>
      </tr>
      <tr><?php $i = 1;
          $sqlLamp = mysqli_query($con, "SELECT * FROM vpot_lampbuy where buyer = '$data[buyer]' order by flag"); ?>
        <td rowspan="2" style="border-right:0px #000000 solid;" colspan="3"><strong>LAMPU</strong> : <?php while ($lamp = mysqli_fetch_array($sqlLamp)) {
                                                                                                        echo $i++ . '.(' . $lamp['lampu'] . '), ';
                                                                                                      } ?>
        </td>
        <td rowspan="2" style="border-right:0px #000000 solid;">
          <strong style="font-size: 21px;">T</strong>IME <strong style="font-size: 21px;">O</strong>UT
        </td>
        <?php
        $sql_ci_y = mysqli_query($con, "SELECT * FROM tbl_status_matching WHERE idm = '$data[no_resep]'");
        $row_ci_y = mysqli_fetch_assoc($sql_ci_y);
        ?>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td width="86" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">
          <span style="border-left:0px #000000 solid;">
            <span style="border-right:0px #000000 solid;">
              <strong style="font-size: 21px;">C</strong>IE
              <strong style="font-size: 21px;">W</strong>I
            </span>
          </span> : <?= $row_ci_y['cie_wi']; ?>
        </td>
        <td width="16" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;"></td>
        <td width="85" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">
          <span style="border-left:0px #000000 solid;">
            <strong style="font-size: 8px;">
              <strong style="font-size: 21px;">C</strong>IE
              <strong style="font-size: 21px;">T</strong>INT
            </strong>
          </span> : <?= $row_ci_y['cie_tint']; ?>
        </td>
        <td width="32" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">
          <span style="border-left:0px #000000 solid;">
            <strong style="font-size: 8px;">
              <strong style="font-size: 21px;">Y</strong>Ness
            </strong>
          </span> : <?= $row_ci_y['yellowness']; ?>
        </td>
        <td width="33" rowspan="3" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">&nbsp;</td>
        <td style="border-right:0px #000000 solid;">PH </td>
        <td style="border-left:0px #000000 solid;">:</td>
        <td colspan="2">&nbsp;</td>
        <td rowspan="2" style="border-right:0px #000000 solid;">
          <strong style="font-size: 21px;">L</strong>AB
          <strong style="font-size: 21px;">D</strong>IP
          <strong style="font-size: 21px;">N</strong>O
        </td>
        <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
        <td rowspan="2" style="border-left:0px #000000 solid;">
          <strong><?Php echo $data['no_warna']; ?></strong>
        </td>
      </tr>
      <tr>
        <td style="border-right:0px #000000 solid;">RC/Bleaching</td>
        <td style="border-left:0px #000000 solid;">:</td>
        <td style="border-right:0px #000000 solid;">Soaping</td>
        <td style="border-left:0px #000000 solid;"> :</td>
      </tr>
    </tbody>
  </table>
  <table width="100%" border="0" class="table-list1">
    <tbody>
      <tr align="center">
        <td width="1%">&nbsp;</td>
        <td width="5%"><strong>
            <font size="-1">D/A CODE</font>
          </strong></td>
        <td colspan="4" width="15%"><strong>
            <font size="+2">D/A NAME</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">1</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">2</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">3</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">4</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">5</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">6</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">7</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">8</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">9</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">10</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">11</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">12</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">13</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">14</font>
          </strong></td>
        <td width="4%"><strong>
            <font size="+2">15</font>
          </strong></td>
      </tr>
      <?php
        $no = 1;
        $qry1 = mysqli_query($con, "SELECT * FROM tbl_matching_detail WHERE id_matching='$data[id]' and jenis='cotton' ORDER BY id ASC");
        while ($r = mysqli_fetch_array($qry1)) { ?>
          <tr>
            <?php if ($no < 2) { ?><td rowspan="<?php $sp = 12;
                                                echo $sp - $no; ?>"><a class="hurufvertical"><strong>SIDE A</strong></a></td> <?php } ?>
            <td align="center" style="height: 15px;"><?php echo strtoupper($r['kode']); ?></td>
            <td colspan="4"><?php echo $r['nama']; ?></td>
            <td align="center"><?php echo $r['lab']; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
      <?php $no++; } ?>
      <?php for ($i = $no; $i <= 7; $i++) { ?>
        <tr>
          <?php if ($i < 2) { ?><td rowspan="11" style="border-bottom: double;"><a class="hurufvertical"><strong>SIDE A</strong></a></td> <?php } ?>
          <td style="height: 15px;">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php } ?>
      <tr>
        <td style="height: 15px;">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="height: 15px;">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="height: 15px;">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" align="left" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">Comment Colorist<br><br><br><br><br></td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
        <td align="center" style="border-bottom:5px solid black !important; height: 50px; border-bottom: double;">&nbsp;</td>
      </tr>
      <?php
        $no1 = 1;
        $qry2 = mysqli_query($con, "SELECT * FROM tbl_matching_detail WHERE id_matching='$data[id]' and jenis='polyester' ORDER BY id ASC");
        while ($r1 = mysqli_fetch_array($qry2)) { ?>
        <tr>
          <?php if ($no1 < 2) { ?><td rowspan="<?php $sp1 = 15;
                                                echo $sp1 - $no1; ?>"><a class="hurufvertical"><strong>SIDE B</strong></a></td> <?php } ?>
          <td align="center" style="height: 15px;"><?php echo strtoupper($r1['kode']); ?></td>
          <td colspan="4"><?php echo $r1['nama']; ?></td>
          <td align="center"><?php echo $r1['lab']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php $no1++; } ?>
      <?php for ($i1 = $no1; $i1 <= 7; $i1++) { ?>
        <tr>
          <?php if ($i1 < 2) { ?><td rowspan="11"><a class="hurufvertical"><strong>SIDE B</strong></a></td> <?php } ?>
          <td style="height: 15px;">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php } ?>
      <tr>
        <td style="height: 15px;">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="height: 15px;">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="height: 15px;">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="left" colspan="5" style="height: 50px;">Comment Colorist<br><br><br><br><br></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr> 
        <?php $sqlOrder = mysqli_query($con, "SELECT * FROM tbl_orderchild where id_matching = '$data[id]' AND NOT `order` = '$data[no_order]' "); ?>
        <td rowspan="2" style="height: 80px;"><a class="hurufvertical"><strong>SAMPLE</strong></a></td>
        <td rowspan="7" colspan="5" valign="top"> 
          <?php if ($data['jenis_matching'] == "L/D") : ?>
            <strong style="font-size: 21px;">R</strong>EQUEST NO :
          <?php elseif ($data['jenis_matching'] == "LD NOW") : ?>
            <strong style="font-size: 21px;">R</strong>EQUEST NO :
          <?php else : ?>
            <strong style="font-size: 21px;">NO.</strong>ORDER :
          <?php endif; ?>
            <?php echo $data['no_order'] ?>,
            <?php while ($order = mysqli_fetch_array($sqlOrder)) {
              echo $order['order'] . ', ';
            } ?>
            <div align="right"><strong style="font-size: 21px;">
                <?php if ($data['salesman_sample'] == "1") {
                  echo "S/S";
                } ?></strong>
            </div>
            QTY ORDER : <strong><?Php echo $data['qty_order']; ?></strong>
        </td>
        <td width="4%" align="center">&nbsp;</td>
        <td width="4%" align="center">&nbsp;</td>
        <td width="4%" rowspan="2" align="left">&nbsp;</td>
        <td width="5%" rowspan="2" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
        <td rowspan="7" align="center">&nbsp;</td>
      </tr>
      <!-- 
        <tr>
          <td style="border-bottom: 0px; border-top:0px;" align="center"><a style="font-size: 8px;">&nbsp;</a></td>
          <td style="border-bottom: 0px; border-top:0px;" align="center"><a style="font-size: 8px;">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-bottom: 0px; border-top:0px;" align="center"><a style="font-size: 8px;">&nbsp;</a></td>
          <td style="border-bottom: 0px; border-top:0px;" width="2%" align="center">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" width="4%" align="left">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" width="5%" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" rowspan="2" align="left">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" rowspan="2" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" rowspan="2" align="left">&nbsp;</td>
          <td style="border-bottom: 0px; border-top:0px;" rowspan="2" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;asdsadsad</td>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;asdsad</td>
        </tr>
        <tr>
          <td colspan="5" rowspan="3" valign="top" style="height: 0.65in;"></td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td style="border-bottom: 0px;" align="left"><a style="font-size: 9px;">&nbsp;</a></td>
          <td style="border-bottom: 0px;" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
          <td rowspan="3" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td style="border-bottom: 0px; border-top:0px;" align="left"><a style="font-size: 9px;">&nbsp;</a></td>
          <td style="border-bottom: 0px; border-top:0px;" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td style=" border-top:0px;" align="left">&nbsp;</td>
          <td style="border-top:0px;" align="center">&nbsp;</td>
        </tr> 
      -->
    </tbody>
    <hr>
  </table>
  <br>
  <table width="100%" border="0" class="table-list1">
    <tbody>
      <tr>
        <td rowspan="6" style="width: 15px;"><a class="hurufvertical"><strong>SAMPLE</strong></a></td>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
      </tr>
      <tr>
        <td style="height: 30px;"><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
      </tr>
      <tr>
        <td style="height: 320px;"><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
      </tr>
      
      <tr>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
        <td style="height: 30px;" align="center"><span style="font-size:20px;">&nbsp;</span></td>
      </tr>
      <tr>
        <td style="height: 30px;"><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
      </tr>
      <tr>
        <td style="height: 320px;"><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
        <td><strong></strong></td>
      </tr>
    </tbody>
  </table>
</body>

</html>
<script>
  setTimeout(function() {
    window.print()
  }, 1500);
</script>