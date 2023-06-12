<?PHP
ini_set("error_reporting", 1);
session_start();
// include "koneksi.php";
$host="svr4";
$username="timdit";
$password="4dm1n";
$db_name="TM";
$connInfo = array( "Database"=>$db_name, "UID"=>$username, "PWD"=>$password);
$conn     = sqlsrv_connect( $host, $connInfo);
$cn=mysqli_connect("10.0.1.91","dit","4dm1n","db_qc");
$conn1=mysqli_connect("10.0.1.91","dit","4dm1n","dbknitt");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Bahan Baku Masuk</title>

</head>

<body>
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> Filter No Oder</h3>
              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" name="form1" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                 <div class="col-sm-4">
                  <div class="input-group margin">
                  <div class="input-group-addon">
                    <i class="fa fa-search"></i>
                  </div>
                <input type="text" class="form-control" name="no_order" placeholder="No Order">
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-arrow-circle-right"></i> </button>
                    </span>
              </div>
				  </div>
				  </div>
                                         
              </div>
              
              <!-- /.box-body -->
              <div class="box-footer">
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Detail Order</h3>
        <br><br>
        <?php if($_POST['no_order']!="") { ?><b>No Order: <?php echo $_POST['no_order']; ?></b> <?php } ?>
        <br />
        Tgl Order :<br />
        Buyer :<br />
        Customer :
      </div>
      <div class="box-body">  

<table id="example1" class="table table-bordered table-hover display " width="100%">
<thead class="btn-success">
   <tr>
      <th width="38"><div align="center">No</div></th>
      <th width="404"><div align="center">No Lot</div></th>
      <th width="215"><div align="center">No Warna</div></th>
      <th width="215"><div align="center">Warna</div></th>
      <th width="215"><div align="center">Lebar</div></th>
      <th width="215"><div align="center">Gramasi</div></th>
      <th width="215"><div align="center">Nett Qty Order</div></th>
      <th width="215"><div align="center">Roll</div></th>
      <th width="215"><div align="center">STATUS</div></th>
   </tr>
</thead>
<tbody>
  <?php 
	if($_POST['fasilitas']=="Ya"){
		$fasilitas=" AND (b.proct LIKE '%-_' OR b.proct LIKE '%-__') ";
	}else if($_POST['fasilitas']=="Tidak"){
		$fasilitas=" AND NOT (b.proct LIKE  '%-_' OR b.proct LIKE '%-__') ";
	}else{
		$fasilitas=" ";
	}
	$no=0;
	$col=1;
	$sql=sqlsrv_query($conn," SELECT c.ProductNumber,SUM(b.Weight) as berat,a.PONo FROM StockMovement a
INNER JOIN StockMovementDetails b ON a.ID=b.StockMovementID
INNER JOIN ProductMaster c ON c.ID=b.ProductID
WHERE a.TransactionType='4' AND a.TransactionStatus='1' AND a.PONo='$_POST[pono]' AND NOT a.PONo='' AND (a.FromToID='16' OR a.FromToID='31' OR a.FromToID='34')
GROUP BY c.ProductNumber,b.Weight,a.PONo ");
	while($r=sqlsrv_fetch_array($sql)){
		$no++;
		$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
		$sql1=sqlsrv_query($conn,"SELECT c.ProductNumber,SUM(b.Weight) as berat FROM StockMovement a
INNER JOIN StockMovementDetails b ON a.ID=b.StockMovementID
INNER JOIN ProductMaster c ON c.ID=b.ProductID
INNER JOIN ProductProp d ON d.ID=b.ProductPropID
WHERE d.PONo='$r[PONo]' AND a.TransactionType='5' AND a.TransactionStatus='0'
GROUP BY c.ProductNumber ");
		$r1=sqlsrv_fetch_array($sql1);
		$qry1=mysqli_query($cn,"SELECT kd_benang_fs FROM tbl_exim_import_detail WHERE kd_benang_in='$r[ProductNumber]' ORDER BY kd_benang_in ASC");
		$r2=mysqli_fetch_array($qry1);
		$sql3=mysqli_query($conn1,"SELECT sum(berat) as KG FROM tbl_pergerakan_benang a
INNER JOIN tbl_pergerakan_benang_detail b ON a.id=b.id_benang WHERE a.pono='$_POST[pono]' AND b.proct='$r[ProductNumber]' AND a.transtatus='4'");
		 $r3=mysqli_fetch_array($sql3);
		 $sql5=mysqli_query($conn1,"SELECT sum(berat_awal-berat) as KG,sum(if(berat_awal>berat,1,0)) as Roll from tbl_inspeksi a
INNER JOIN tbl_inspeksi_detail b ON a.id=b.id_inspeksi WHERE a.no_po='$_POST[pono]'");
		 $r5=mysqli_fetch_array($sql5);
	?>
   <tr bgcolor="<?php echo $bgcolor; ?>">
     <td align="center"><?php echo $no; ?></td>
     <td align="center"><?php echo $r['ProductNumber'];?></td>
     <td align="center"><?php echo $r2['kd_benang_fs'];?></td>
     <td align="right"><?php echo number_format($r['berat'],2);?></td>
     <td align="center"><a href="#" class="open_detail" id="<?php echo $r['PONo']; ?>"><?php echo $r1['ProductNumber'];?></a></td>
     <td align="right"><?php echo number_format($r1['berat'],2);?></td>
     <td align="right"><?php if($r5['KG']>0){echo $r5['KG'];}else{echo "0.00";}?></td>
     <td align="right"><?php if($r3['KG']>0){echo $r3['KG'];}else{echo"0.00";}?></td>
     <td align="center"><?php if($r1['status']!="SELESAI"){echo"<b class='label label-success'>Selesai</b>";}else{echo "<b class='label label-primary'>Sedang Produksi</b>";}?></td>
   </tr>
   <?php } ?>
   </tbody>
</table>
</form>
<!-- Modal Popup untuk Edit--> 
<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>
      </div>
    </div>
  </div>
</div>

</body>
</html>