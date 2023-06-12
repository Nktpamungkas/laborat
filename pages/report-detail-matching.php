<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$modal=mysqli_query($con,"SELECT * FROM `tbl_matching` WHERE id='$modal_id' ");
while($r=mysqli_fetch_array($modal)){
?>
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_user" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Dyes & Chemical</h4>
              </div>
              <div class="modal-body">
              <table id="example3" class="table table-bordered table-hover display nowrap" width="100%">
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
  $sql=mysqli_query($con," SELECT * FROM `tbl_matching_detail` WHERE id_matching='$modal_id' ORDER BY id ASC ");
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
              <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Close</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
  </div>
          <!-- /.modal-dialog -->
          <?php } ?>
