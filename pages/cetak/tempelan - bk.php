<?php
$con=mysql_connect("svr4","dit","4dm1n");
$db=mysql_select_db("db_lab",$con)or die("Gagal Koneksi");
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
//-
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<title>Cetak Form Tempelan Laborat</title>
</head>

<body>
<?php 
	$qry=mysql_query("SELECT *,DATE_FORMAT(now(),'%d %M %Y') as tgl FROM tbl_tempelan WHERE no_resep='$idkk'");
	$data=mysql_fetch_array($qry);
?>
<br>	
<div align="right" style="font-size: 12px;">FW-12-LAB-04</div>
<table width="100%" border="0" class="table-list1">
  <tr>
      <td width="9%" style="border-right:0px #000000 solid;">No. Resep</td>
      <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="13%" style="border-left:0px #000000 solid;"><strong><?Php echo $data[no_resep];?></strong></td>
      <td width="9%" style="border-right:0px #000000 solid;">No. Warna</td>
      <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="39%" style="border-left:0px #000000 solid;"><strong><?Php echo $data[no_warna];?></strong></td>
      <td width="15%" style="border-right:0px #000000 solid;">Gramasi Aktual</td>
      <td width="1%"  style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="12%" style="border-left:0px #000000 solid;"><strong><?Php if($data[lebara]!=""){echo $data[lebara]." x ".$data[gramasia]." gr/m2";}else{ echo "<font color=white>".$data[lebar_a]."</font>&nbsp;&nbsp;&nbsp;&nbsp; x <font color=white> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$data[gramasi_a]."</font> gr/m2";}?></strong></td>
  </tr>
    <tr>
      <td style="border-right:0px #000000 solid;">Tanggal</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><strong><?php echo date("d-m-Y", strtotime($data[tgl]));?></strong></td>
      <td style="border-right:0px #000000 solid;">Warna</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><strong><?Php echo $data[warna];?></strong></td>
      <td style="border-right:0px #000000 solid;">Gramasi Permintaan</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><strong><?Php echo $data[lebar]." x ".$data[gramasi]." gr/m2";?></strong></td>
    </tr>
    <tr>
      <td style="border-right:0px #000000 solid;">Item</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><strong ><?Php echo $data[no_item];?></strong></td>
      <td style="border-right:0px #000000 solid;">Langganan</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><?Php echo $data[langganan];?></td>
      <td style="border-right:0px #000000 solid;">% Kadar Air</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><?Php echo $data[kadar_air];?></td>
    </tr>
    <tr>
      <td style="border-right:0px #000000 solid;">PO Greige</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><strong><?Php echo $data[no_po];?></strong></td>
      <td style="border-right:0px #000000 solid;">Jenis Kain</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><?Php echo $data[jenis_kain];?></td>
      <td style="border-right:0px #000000 solid;">Kode Resep</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><strong><?Php echo $data[kd_resep];?></strong></td>
    </tr>
    <tr style="height: 0.6in">
      <td valign="top" style="border-right:0px #000000 solid;">No. Order</td>
      <td valign="top" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td valign="top" style="border-left:0px #000000 solid;"><strong><?Php echo $data[no_order];?></strong></td>
      <td valign="top" style="border-right:0px #000000 solid;">Benang</td>
      <td valign="top" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td valign="top" style="border-left:0px #000000 solid;"><?Php echo $data[benang];?></td>
      <td valign="top" style="border-right:0px #000000 solid;">Cocok Warna</td>
      <td valign="top" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td valign="top" style="border-left:0px #000000 solid;"><?Php echo $data[cocok_warna];?></td>
    </tr>
</table>

   <table width="100%" border="1" class="table-list1">
    <tr style="height: 0.3in">
      <td width="4%" align="center"><strong>KODE</strong></td>
      <td colspan="2" align="center"><strong>DYES &amp; CHEMICAL</strong></td>
      <td width="7%" align="center"><strong>LAB</strong></td>
      <td width="8%" align="center"><strong>AKTUAL</strong></td>
      <td width="8%" align="center"><strong>DYEING I</strong></td>
      <td width="8%" align="center"><strong>DYEING II</strong></td>
      <td colspan="2" align="center"><strong>BODY</strong></td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="8">&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="2" align="center"><strong>LAB. SAMPLE</strong></td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="10">&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td width="20%" rowspan="2" align="center"><strong>BEFORE SOAPING</strong></td>
      <td width="21%" rowspan="2" align="center"><strong>T-SIDE</strong></td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td rowspan="7">&nbsp;</td>
      <td rowspan="7">&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.2in">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>
    <tr style="height: 0.4in">
      <td colspan="2">&nbsp;</td>
      <td colspan="2" align="center">T-SIDE</td>
      <td colspan="3" align="center">C.SIDE</td>
     </tr>
    <tr style="height: 0.4in">
      <td colspan="2" align="center">Temp x Time</td>
      <td colspan="2" align="center">&deg;C X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; min</td>
      <td colspan="3" align="center">&deg;C X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; min</td>
     </tr>
    <tr style="height: 0.4in">
      <td colspan="2" align="center">L:R</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td colspan="2" rowspan="2"><table width="100%" border="0">
          <tr>
            <td width="26%">Greige</td>
            <td width="23%" align="center"><?php if($data[ck_greige]>0){?>
      &#10004;<?php } ?></td>
            <td width="30%">Preset</td>
            <td width="21%" align="center"><?php if($data[ck_preset]>0){?>
        &#10004;
        <?php } ?></td>
          </tr>
          <tr>
            <td>Bleaching</td>
            <td align="center"><?php if($data[ck_bleaching]>0){?>
        &#10004;
        <?php } ?></td>
            <td>Non Preset</td>
            <td align="center"><?php if($data[ck_npreset]>0){?>
        &#10004;
        <?php } ?></td>
          </tr>
          <tr>
            <td>Non H<sub>2</sub>O<sub>2</sub></td>
            <td align="center"><?php if($data[ck_nh2o2]>0){?>
        &#10004;
        <?php } ?></td>
            <td>Peach</td>
            <td align="center"><?php if($data[ck_tarik]>0){?>
        &#10004;
        <?php } ?></td>
          </tr>
          <tr>
            <td>CIE WI</td>
            <td align="center"><?php echo $data[cie_wi];?></td>
            <td>CIE Tint</td>
            <td align="center"><?php echo $data[cie_tint];?></td>
          </tr>
      </table></td>
     </tr>
    <tr style="height: 0.4in">
      <td colspan="2" align="center">pH</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
     </tr>
    <tr style="height: 0.4in">
      <td colspan="2" align="center">RC</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td colspan="2" align="center"><strong>GREIGE</strong></td>
     </tr>
    <tr style="height: 0.4in">
      <td colspan="2" align="center">Soaping</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td colspan="2" rowspan="3">&nbsp;</td>
     </tr>
    <tr style="height: 0.4in">
      <td colspan="3" align="center">BEFORE RC</td>
      <td colspan="4" align="center">AFTER RC</td>
     </tr>
    <tr style="height: 1.5in">
      <td colspan="3">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
     </tr>
    <tr>
      <td colspan="7">Tanggal<br />
        Buka Resep :</td>
      <td colspan="2">&nbsp;</td>
    </tr>
</table>
<div align="left" style="font-size: 12px;">TTD :</div>
</body>
</html>