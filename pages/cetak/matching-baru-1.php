<?php
ini_set("error_reporting", 1);
session_start();
$con=mysqli_connect("10.0.1.91","dit","4dm1n","db_laborat");
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
<title>Cetak Kartu Matching</title>
<style>
.hurufvertical {
 writing-mode:tb-rl;
    -webkit-transform:rotate(-90deg);
    -moz-transform:rotate(-90deg);
    -o-transform: rotate(-90deg);
    -ms-transform:rotate(-90deg);
    transform: rotate(180deg);
    white-space:nowrap;
    float:left;
}	
	</style>
</head>

<body>
<?php 
	$qry=mysqli_query($con,"SELECT *,DATE_FORMAT(now(),'%d %M %Y') as tgl FROM tbl_matching WHERE no_resep='$idkk'");
	$data=mysqli_fetch_array($qry);
?>
<table width="100%" border="0" >
     <tr style="font-size: 10px;">
      <td width="13%">GRAMASI PERMINTAAN:</td>
      <td width="10%"><strong><?Php echo $data['lebar']." x ".$data['gramasi']." gr/m2";?></strong></td>
      <td width="11%">GRAMASI AKTUAL:</td>
      <td width="11%">&nbsp;</td>
      <td width="17%">BERAT:</td>
      <td width="15%">No. Form : FW-12-LAB-05</td>
      <td width="9%">No. Revisi : 03</td>
      <td width="14%">Tgl. Terbit : 10 Januari 2018</td>
    </tr>
</table>
<table width="100%" border="0" class="table-list1">
  <tbody>
    
    <tr>
      <td width="6%" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">R</strong><span>CODE</span></td>
      <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="7%" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_resep'];?></strong></td>
      <td width="7%" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">I</strong>TEM</td>
      <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td colspan="7" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_item'];?></strong></td>
      <td colspan="4" align="center" style="border-bottom:0px #000000 solid;"><strong style="font-size: 24px;">KARTU MATCHING</strong></td>
      <td width="8%" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">L</strong>ANGGANAN</td>
      <td width="1%" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="17%" style="border-left:0px #000000 solid;"><strong style="font-size: 9px;"><?Php echo $data['langganan'];?></strong></td>
    </tr>
    <tr>
      <td style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">M</strong>ATCHER</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;">&nbsp;</td>
      <td style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">P</strong>O <strong style="font-size: 21px;">G</strong>REIGE</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td colspan="7" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_po'];?></strong></td>
      <td colspan="4" align="left" valign="top" style="border-top:0px #000000 solid;">CATATAN:</td>
      <td style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">N</strong>O. ORDER</td>
      <td style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_order'];?></strong></td>
    </tr>
    <tr>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">T</strong>IME <strong style="font-size: 21px;">I</strong>N</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td rowspan="2" style="border-left:0px #000000 solid;"><strong><?Php echo date("d-m-Y", strtotime($data['tgl_in']));?></strong></td>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">K</strong>AIN</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td colspan="7" rowspan="2" style="border-left:0px #000000 solid;"><strong style="font-size: 8px;"><?Php echo $data['jenis_kain'];?></strong></td>
      <td width="6%" rowspan="2">No. Program Celup</td>
      <td width="9%">T-Side :</td>
      <td width="6%" rowspan="2">No. Gelas</td>
      <td width="9%">T-Side :</td>
      <td rowspan="2" style="border-right:0px #000000 solid;">STD COCOK WARNA</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td style="border-left:0px #000000 solid;">1. <strong><?Php echo $data['cocok_warna'];?></strong></td>
    </tr>
    <tr>
      <td width="9%">C-Side :</td>
      <td width="9%">C-Side :</td>
      <td style="border-left:0px #000000 solid;">2.</td>
    </tr>
    <tr>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">T</strong>ARGET</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td rowspan="2" style="border-left:0px #000000 solid;">&nbsp;</td>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 22px;">B</strong>ENANG</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td colspan="7" rowspan="2" style="border-left:0px #000000 solid;"><strong style="font-size: 8px;"><?Php echo $data['benang'];?></strong></td>
      <td rowspan="4">T-Side</td>
      <td>L : R :</td>
      <td rowspan="4">C-Side</td>
      <td>L : R :</td>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">W</strong>ARNA</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td rowspan="2" style="border-left:0px #000000 solid;"><strong style="font-size: 9px;"><?Php echo $data['warna'];?></strong></td>
    </tr>
    <tr>
      <td align="right">&nbsp;&nbsp; &deg;C X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Menit</td>
      <td align="right">&nbsp;&nbsp; &deg;C X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Menit</td>
    </tr>
    <tr>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">D</strong>ELIVERY</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td rowspan="2" style="border-left:0px #000000 solid;">&nbsp;</td>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">T</strong>IME <strong style="font-size: 21px;">O</strong>UT</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="4%" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">&nbsp;</td>
      <td width="5%" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;"><span style="border-left:0px #000000 solid;"><span style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">C</strong>IE <strong style="font-size: 21px;">W</strong>I</span></span></td>
      <td width="1%" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="3%" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">&nbsp;</td>
      <td width="5%" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;"><span style="border-left:0px #000000 solid;"><strong style="font-size: 8px;"><strong style="font-size: 21px;">C</strong>IE <strong style="font-size: 21px;">T</strong>INT</strong></span></td>
      <td width="1%" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td width="3%" rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">&nbsp;</td>
      <td>PH :</td>
      <td>&nbsp;</td>
      <td rowspan="2" style="border-right:0px #000000 solid;"><strong style="font-size: 21px;">N</strong>O <strong style="font-size: 21px;">W</strong>ARNA</td>
      <td rowspan="2" style="border-right:0px #000000 solid; border-left:0px #000000 solid;">:</td>
      <td rowspan="2" style="border-left:0px #000000 solid;"><strong><?Php echo $data['no_warna'];?></strong></td>
    </tr>
    <tr>
      <td>R.C. :</td>
      <td>Soaping :</td>
    </tr>
  </tbody>
</table>
<table width="100%" border="0" class="table-list1">
  <tbody>
    <tr align="center">
      <td width="1%">&nbsp;</td>
      <td width="2%"><strong><font size="-1">D/A CODE</font></strong></td>
	  <td colspan="4"><strong><font size="+2">D/A NAME</font></strong></td>
      <td width="5%"><strong><font size="+2">1</font></strong></td>
      <td width="5%"><strong><font size="+2">2</font></strong></td>
      <td width="5%"><strong><font size="+2">3</font></strong></td>
      <td width="5%"><strong><font size="+2">4</font></strong></td>
      <td width="5%"><strong><font size="+2">5</font></strong></td>
      <td width="5%"><strong><font size="+2">6</font></strong></td>
      <td width="5%"><strong><font size="+2">7</font></strong></td>
      <td width="5%"><strong><font size="+2">8</font></strong></td>
      <td width="5%"><strong><font size="+2">9</font></strong></td>
      <td width="5%"><strong><font size="+2">10</font></strong></td>
      <td width="5%"><strong><font size="+2">11</font></strong></td>
      <td width="5%"><strong><font size="+2">12</font></strong></td>
      <td width="5%"><strong><font size="+2">13</font></strong></td>
      <td width="5%"><strong><font size="+2">14</font></strong></td>
	  <td width="5%"><strong><font size="+2">15</font></strong></td>
    </tr>
    <?php 
		  $no=1;
		  $qry1=mysqli_query($con,"SELECT * FROM tbl_matching_detail WHERE id_matching='$data[id]' and jenis='cotton' ORDER BY id ASC");
		  while($r=mysqli_fetch_array($qry1)){?>
    <tr>
      <?php if($no<2){ ?><td rowspan="<?php $sp=12;echo $sp-$no;?>"><a class="hurufvertical"><strong>SIDE A</strong></a></td> <?php } ?>
      <td align="center" style="height: 17px;"><?php echo strtoupper($r['kode']);?></td>
      <td colspan="4"><?php echo $r['nama'];?></td>
      <td align="center"><?php echo $r['lab'];?></td>
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
    <?php $no++;} ?>
    <?php  for ($i=$no; $i <= 7; $i++) { ?>
    <tr>
     <?php if($i<2){?><td rowspan="11" style="border-bottom: double;"><a class="hurufvertical"><strong>SIDE A</strong></a></td> <?php } ?>
      <td style="height: 17px;" >&nbsp;</td>
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
      <td style="height: 17px;">&nbsp;</td>
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
      <td style="height: 17px;">&nbsp;</td>
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
      <td style="height: 17px;">&nbsp;</td>
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
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
	  <td colspan="4" align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
      <td align="center" style="height: 17px; border-bottom: double;">&nbsp;</td>
    </tr>
    <?php 
		  $no1=1;
		  $qry2=mysqli_query($con,"SELECT * FROM tbl_matching_detail WHERE id_matching='$data[id]' and jenis='polyester' ORDER BY id ASC");
		  while($r1=mysqli_fetch_array($qry2)){?>
    <tr>
      <?php if($no1<2){ ?><td rowspan="<?php $sp1=15;echo $sp1-$no1;?>"><a class="hurufvertical"><strong>SIDE B</strong></a></td> <?php } ?>
      <td align="center" style="height: 17px;"><?php echo strtoupper($r1['kode']);?></td>
      <td colspan="4"><?php echo $r1['nama'];?></td>
      <td align="center"><?php echo $r1['lab'];?></td>
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
    <?php $no1++;} ?>
    <?php  for ($i1=$no1; $i1 <= 7; $i1++) { ?>
    <tr>
     <?php if($i1<2){?><td rowspan="11"><a class="hurufvertical"><strong>SIDE B</strong></a></td> <?php } ?>
      <td style="height: 17px;">&nbsp;</td>
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
      <td style="height: 17px;">&nbsp;</td>
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
      <td style="height: 17px;">&nbsp;</td>
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
      <td style="height: 17px;">&nbsp;</td>
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
      <td align="center" style="height: 17px;">&nbsp;</td>
      <td colspan="4" align="center">&nbsp;</td>
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
      <td rowspan="9"><a class="hurufvertical"><strong>SAMPLE</strong></a></td>
      <td rowspan="6" align="center">KAIN</td>
      <td width="4%" align="center"><a style="font-size: 8px;">Greige</a></td>
      <td width="4%" align="center"><a style="font-size: 8px;"><?php if($data['ck_greige']>0){?>
      &#10004;<?php } ?></a></td>
		<td width="4%" rowspan="2" align="left"><a style="font-size: 9px;">D65</a></td>
      <td width="5%" rowspan="2" align="center"><?php if($data['ck_d65']>0){?>&#10004;<?php } ?></td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
      <td rowspan="6" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><a style="font-size: 8px;">Bleaching</a></td>
      <td width="2%" align="center"><a style="font-size: 8px;"><?php if($data['ck_bleaching']>0){?>
        &#10004;
        <?php } ?>
      </a></td>
    </tr>
    <tr>
      <td align="center"><a style="font-size: 8px;">Non H2O2</a></td>
      <td align="center"><a style="font-size: 8px;"><?php if($data['ck_nh2o2']>0){?>
        &#10004;
        <?php } ?>
      &nbsp;</a></td>
		<td rowspan="2" align="left"><a style="font-size: 9px;">F02</a></td>
      <td rowspan="2" align="center"><?php if($data['ck_f02']>0){?>&#10004;<?php } ?></td>
    </tr>
    <tr>
      <td align="center"><a style="font-size: 8px;">Preset</a></td>
      <td align="center"><a style="font-size: 8px;">
        <?php if($data['ck_preset']>0){?>
        &#10004;
        <?php } ?>
      &nbsp;</a></td>
    </tr>
    <tr>
      <td align="center"><a style="font-size: 8px;">Non Preset</a></td>
      <td align="center"><a style="font-size: 8px;">
        <?php if($data['ck_npreset']>0){?>
        &#10004;
        <?php } ?>
      &nbsp;</a></td>
		<td rowspan="2" align="left"><a style="font-size: 9px;">F11</a></td>
      <td rowspan="2" align="center"><?php if($data[ck_f11]>0){?>&#10004;<?php } ?></td>
    </tr>
    <tr>
      <td align="center"><a style="font-size: 8px;">Peach</a></td>
      <td align="center"><a style="font-size: 8px;">
        <?php if($data['ck_tarik']>0){?>
        &#10004;
        <?php } ?>
      &nbsp;</a></td>
    </tr>
    <tr>
      <td rowspan="3" align="center" style="height: 0.65in;">QTY ORDER</td>
      <td colspan="2" rowspan="3" align="center"><strong><?Php echo $data['qty_order'];?></strong></td>
		<td align="left"><a style="font-size: 9px;">U35</a></td>
      <td align="center"><?php if($data['ck_u35']>0){?>&#10004;<?php } ?></td>
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
      <td rowspan="3" align="center">&nbsp;</td>
      <td rowspan="3" align="center">&nbsp;</td>
      <td rowspan="3" align="center">&nbsp;</td>
      <td rowspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
		<td align="left"><a style="font-size: 9px;">A</a></td>
      <td align="center"><?php if($data['ck_a']>0){?>&#10004;<?php } ?></td>
    </tr>
    <tr>
		<td align="left"><a style="font-size: 9px;">R. LIGHT</a></td>
      <td align="center"><?php if($data['ck_rlight']>0){?>&#10004;<?php } ?></td>
    </tr>
  </tbody>
</table>	
</body>
</html>