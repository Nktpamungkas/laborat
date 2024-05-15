<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
$data=mysqli_query($con,"SELECT * FROM tbl_test_qc WHERE id='$idkk' ORDER BY id DESC LIMIT 1");
$r=mysqli_fetch_array($data);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<title>Cetak Label</title>
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
<table width="100%" border="0"style="width: 7in;">
  <tbody>    
    <tr>
      <td align="left" valign="top" style="height: 1.6in;"><table width="100%" border="0" class="table-list1" style="width: 2.3in;">
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size: 8px;"><?php echo $r['buyer'];?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;">
            <?php $pos = strpos($r['pelanggan'], "/");
	  if($pos>0){$pos1=$pos;
	  $result = substr($r['pelanggan'],0, $pos1);
	  $pos2=$pos+1;
	  $result1 = substr($r['pelanggan'], $pos2);}		  
?>
            <?php echo substr($result,0,13)."/".substr($result1,0,13);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo substr($r['no_po'],0,31);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><strong><?php echo $r['no_order'];?></strong><?php echo " (".$r['no_item'].")";?></div></td>
        </tr>
        <tr>
          <td valign="top" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:6px;"><?php echo substr($r['jenis_kain'],0,100);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><span style="font-size:7px;"><?php echo "<strong><span style='font-size:9px;'>".substr($r['warna'],0,60)."</span></strong>/".substr($r['no_warna'],0,15);?></span></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'],0,18)));?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo $r['treatment'];?></div>            <div style="font-size:9px;"></div></td>
          </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><span style="font-size:9px;"><?php echo $r['no_counter'];?></span></td>
          </tr>
      </table></td>
      <td align="left" valign="top" style="height: 1.6in;"><table width="100%" border="0" class="table-list1" style="width: 2.3in;">
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size: 8px;"><?php echo $r['buyer'];?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;">
            <?php $pos = strpos($r['pelanggan'], "/");
	  if($pos>0){$pos1=$pos;
	  $result = substr($r['pelanggan'],0, $pos1);
	  $pos2=$pos+1;
	  $result1 = substr($r['pelanggan'], $pos2);}		  
?>
            <?php echo substr($result,0,13)."/".substr($result1,0,13);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo substr($r['no_po'],0,31);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><strong><?php echo $r['no_order'];?></strong><?php echo " (".$r['no_item'].")";?></div></td>
        </tr>
        <tr>
          <td valign="top" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:6px;"><?php echo substr($r['jenis_kain'],0,100);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><span style="font-size:7px;"><?php echo "<strong><span style='font-size:9px;'>".substr($r['warna'],0,60)."</span></strong>/".substr($r['no_warna'],0,15);?></span></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'],0,18)))."/".substr($r['proses_fin'],0,14);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo $r['treatment'];?></div>            <div style="font-size:9px;"></div></td>
          </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><span style="font-size:9px;"><?php echo $r['no_counter'];?></span></td>
          </tr>
      </table></td>
      <td align="left" valign="top" style="height: 1.6in;"><table width="100%" border="0" class="table-list1" style="width: 2.3in;">
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size: 8px;"><?php echo $r['buyer'];?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;">
            <?php $pos = strpos($r['pelanggan'], "/");
	  if($pos>0){$pos1=$pos;
	  $result = substr($r['pelanggan'],0, $pos1);
	  $pos2=$pos+1;
	  $result1 = substr($r['pelanggan'], $pos2);}		  
?>
            <?php echo substr($result,0,13)."/".substr($result1,0,13);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo substr($r['no_po'],0,31);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><strong><?php echo $r['no_order'];?></strong><?php echo " (".$r['no_item'].")";?></div></td>
        </tr>
        <tr>
          <td valign="top" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:6px;"><?php echo substr($r['jenis_kain'],0,100);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><span style="font-size:7px;"><?php echo "<strong><span style='font-size:9px;'>".substr($r['warna'],0,60)."</span></strong>/".substr($r['no_warna'],0,15);?></span></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'],0,18)))."/".substr($r['proses_fin'],0,14);?></div></td>
        </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><div style="font-size:9px;"><?php echo $r['treatment'];?></div></td>
          </tr>
        <tr>
          <td style="border-top:0px #000000 solid; border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; border-right:0px #000000 solid;"><span style="font-size:9px;"><?php echo $r['no_counter'];?></span></td>
          </tr>
      </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>