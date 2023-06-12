<?php
ini_set("error_reporting", 1);
session_start();
$host="192.168.0.72\SQLEXPRESS";
//$host="DIT\MSSQLSERVER08";
$username="sa";
$password="123";
$db_name="TICKET";
//--

// function db_connect(){
// global $host, $username, $password, $db_name;
// set_time_limit(600);
// $ctic=mssql_connect($host,$username,$password) or die ("Tidak bisa terkoneksi dengan server Database Laborat !");
// mssql_select_db($db_name) or die ("Under maintenance");
// }
function db_connect()
{
    global $host, $username, $password, $db_name;
    set_time_limit(600);
    $connInfo = array( "Database"=>$db_name, "UID"=>$username, "PWD"=>$password);
    $ctic=sqlsrv_connect( $host, $connInfo);
}

//db_connect($db_name);
include "koneksi.php";
	if(isset($_POST['save'])){
		$id=$_GET['id'];
		$kode=$_POST['kode'];
		$dyes=str_replace("'","''",$_POST['dyes']);
		$lab=str_replace("'","''",$_POST['lab']);
		$aktual=str_replace("'","''",$_POST['aktual']);
		
		$qry=mysqli_query($con,"INSERT INTO tbl_tempelan_detail SET
		id_tempelan='$id',
		kode='$kode',
		nama='$dyes',
		lab='$lab',
		aktual='$aktual'
		");
		if($qry){
			echo "<script>alert('Data Tersimpan');</script>";
			echo "<script>window.location.href='?p=Form-Tempelan-Detail&noresep=$_GET[noresep]&id=$_GET[id]';</script>";
		}
		else {
        echo "There's been a problem: " . mysqli_error();
    }
			
	}
?>	
 <div class="box box-info">
 <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
  <div class="box-header with-border">
    <h3 class="box-title">Form Tempelan Detail Dyes &amp; Chemical</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <?php
	 $sqlsvr=sqlsrv_query($ctic,"SELECT * from PRODUCT WHERE ProductCode='$_GET[kd]'");
	 $dt=sqlsrv_fetch_array($sqlsvr);
	 ?>
  <div class="box-body">
  
               <div class="form-group">
                  <label for="order" class="col-sm-2 control-label">No Resep</label>
                  <div class="col-sm-2">
                    <input name="no_resep" type="text" class="form-control" id="no_resep" 
                    value="<?php echo $_GET['noresep']; ?>" placeholder="No Resep" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="order" class="col-sm-2 control-label">Kode</label>
                  <div class="col-sm-3">
                    <input name="kode" type="text" class="form-control" id="kode" onChange="window.location='?p=Form-Tempelan-Detail&noresep=<?php echo $_GET['noresep'];?>&id=<?php echo $_GET['id'];?>&kd='+this.value" value="<?php echo $_GET['kd']; ?>" placeholder="Kode" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="dyes" class="col-sm-2 control-label">Dyes &amp; Chemical</label>
                  <div class="col-sm-8">
                    <input name="dyes" type="text" class="form-control" id="dyes" 
                    placeholder="Dyes &amp; Chemical" value="<?php echo trim($dt['ProductName']);?>" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="lab" class="col-sm-2 control-label">Lab</label>
                  <div class="col-sm-3">
                    <input name="lab" type="text" class="form-control" id="lab" value="" placeholder="Lab">
                  </div>
                </div>
                <div class="form-group">
                  <label for="aktual" class="col-sm-2 control-label">Aktual</label>
                  <div class="col-sm-3">
                    <input name="aktual" type="text" class="form-control" id="aktual" value="" placeholder="Aktual">
                  </div>
                </div> 
				   
	 </div>
<div class="box-footer">
      <div class="col-sm-2">
        <button type="submit" class="btn btn-block btn-social btn-linkedin" name="save" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
      </div>
    </div>
    <!-- /.box-footer -->
  

</form>
</div>	
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
      
		</div>    
<div class="box-body">		
<table id="example2" class="table table-bordered table-hover display" width="100%">
<thead class="bg-green">
   <tr>
      <th width="37"><div align="center">No</div></th>
      <th width="131"><div align="center">Kode</div></th>
      <th width="516"><div align="center">Dyes &amp; Chemical</div></th>
      <th width="266"><div align="center">Lab</div></th>
      <th width="241"><div align="center">Aktual</div></th>
      </tr>
</thead>
<tbody>
  <?php 
  $sql=mysqli_query($con," SELECT * FROM `tbl_tempelan_detail` a
	   INNER JOIN `tbl_tempelan` b ON b.id=a.id_tempelan
	   WHERE b.no_resep='$_GET[noresep]' ");
  while($r=mysqli_fetch_array($sql)){
	 
		$no++;
		$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
	?>
   <tr bgcolor="<?php echo $bgcolor; ?>">
     <td align="center"><?php echo $no; ?></td>
     <td align="center"><?php echo $r['kode']; ?></td>
     <td align="center"><?php echo $r['nama']; ?></td>
     <td><?php echo $r['lab'];?></td>
     <td align="center"><?php echo $r['aktual']; ?></td>
     </tr>
   <?php } ?>
   </tbody>
   
</table>  
		</div> 
	  </div>
	</div>
</div>                     		    
           
