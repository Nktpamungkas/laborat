<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
$data=mysqli_query($con,"SELECT * FROM tbl_test_qc WHERE id='$idkk' ORDER BY id DESC LIMIT 1");
$r=mysqli_fetch_array($data);
$detail2=explode(",",$r['permintaan_testing']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<title>Form Permintaan</title>
<style>
	td{
	border-top:0px #000000 solid; 
	border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; 
	border-right:0px #000000 solid;
	}
	</style>
</head>


<body>
<table width="45%" border="0" class="table-list1">
  <tbody>
    <tr>
      <td colspan="6" align="center" valign="middle" style="height: 0.3in; font-size: 14px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>BUYER</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['buyer'];?></strong></td>
      <td><strong>BUYER</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['buyer'];?></strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>NAMA</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['created_by'];?></strong></td>
      <td><strong>NAMA</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['created_by'];?></strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>TANGGAL</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'],0,18)));?></strong></td>
      <td><strong>TANGGAL</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'],0,18)));?></strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("WASHING",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>C F-Laundering</strong></td>
      <td align="right" valign="middle"><?php if(in_array("LIGHT",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>C F-Light</strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("SUHU",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Laundering suhu 30'C</strong></td>
      <td align="right" valign="middle"><?php if(in_array("LIGHT PERSPIRATION",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>C F-Prespiration Lignt</strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("PH",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>C F-Prespiration</strong></td>
      <td align="right" valign="middle"><?php if(in_array("BLEEDING",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Bleeding</strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("WATER",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>C F-Water</strong></td>
      <td align="right" valign="middle"><?php if($r['permintaan_testing']==""){?><strong>&#9745;</strong><?php }else{?><strong>&#9744;</strong><?php } ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Full Test</strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("CROCKING",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>C F-Crocking</strong></td>
      <td align="right" valign="middle"><?php if(in_array("PH",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Form Khusus Protx2</strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("PH",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>PH 3 + PH 4</strong></td>
      <td align="right" valign="middle"><?php if(in_array("PH",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Staining</strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("COLOR MIGRATION",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Dye Transfer</strong></td>
      <td colspan="3" rowspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("CHLORIN & NON-CHLORIN",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Chlorine</strong></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><?php if(in_array("PHENOLIC YELLOWING",$detail2)){echo "<strong>&#9745;</strong>";}else{echo "<strong>&#9744;</strong>";} ?></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong>Phenolic Yellowing</strong></td>
    </tr>
    <tr>
      <td rowspan="3" valign="top"><strong>NAMA WARNA</strong></td>
      <td align="center" valign="top"><strong>:</strong></td>
      <td valign="top" style="height: 0.35in;"><strong>QC CODE</strong></td>
      <td rowspan="3" valign="top"><strong>NAMA WARNA</strong></td>
      <td align="center" valign="top"><strong>:</strong></td>
      <td valign="top"><strong>QC CODE</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td  style="height: 0.35in;">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td  style="height: 0.35in;">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</body>
</html>