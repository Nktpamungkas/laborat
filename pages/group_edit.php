<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
    $modal=mysqli_query($con,"SELECT * FROM `tbl_status_matching` WHERE id='$modal_id' ");
while ($r=mysqli_fetch_array($modal)) {
    ?>
<div class="modal-dialog ">
  <div class="modal-content">
    <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_group" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Status Matching</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id" name="id" value="<?php echo $r['id']; ?>">
		<input type="hidden" id="grp" name="grp" value="<?php echo $r['grp']; ?>">  
        <div class="form-group">
          <label for="tgl_masuk" class="col-sm-3 control-label">Tgl Masuk</label>
          <div class="col-sm-4">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="tgl_masuk" type="text" class="form-control pull-right" id="datepicker3" placeholder="0000-00-00" value="<?php echo $r['tgl_masuk'];?>" required/>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="tgl_siap_kain" class="col-sm-3 control-label">Tgl Siap Kain</label>
          <div class="col-sm-4">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="tgl_siap_kain" type="text" class="form-control pull-right" id="datepicker1" placeholder="0000-00-00" value="<?php echo $r['tgl_siap_kain'];?>" />
            </div>
          </div>
        </div>
		<div class="form-group">
          <label for="tgl_mulai" class="col-sm-3 control-label">Tgl Mulai</label>
          <div class="col-sm-4">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="tgl_mulai" type="text" class="form-control pull-right" id="datepicker2" placeholder="0000-00-00" value="<?php echo $r['tgl_mulai'];?>" />
            </div>
          </div>
        </div>
		<div class="form-group">
          <label for="sts" class="col-md-3 control-label">Status</label>
          <div class="col-md-3">
            <select class="form-control" name="sts">
			<option value="buka"<?php if($r['status']=="buka"){ echo "SELECTED";} ?>>Buka</option>	
			<option value="batal"<?php if($r['status']=="batal"){ echo "SELECTED";} ?>>Batal</option>
			<option value="tahan"<?php if($r['status']=="tahan"){ echo "SELECTED";} ?>>Tahan</option>
			<option value="selesai"<?php if($r['status']=="selesai"){ echo "SELECTED";} ?>>Selesai</option>	
			<option value="tutup"<?php if($r['status']=="tutup"){ echo "SELECTED";} ?>>Tutup</option>	
			</select>
            <span class="help-block with-errors"></span>
          </div>
        </div>  
		<div class="form-group">
          <label for="tgl_selesai" class="col-sm-3 control-label">Tgl Selesai</label>
          <div class="col-sm-4">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="tgl_selesai" type="text" class="form-control pull-right" id="datepicker4" placeholder="0000-00-00" value="<?php echo $r['tgl_selesai'];?>" />
            </div>
          </div>
        </div>   
        <div class="form-group">
          <label for="cek_warna" class="col-md-3 control-label">Cek Warna</label>
          <div class="col-md-6">
            <input type="text"  name="cek_warna" class="form-control" value="<?php echo $r['cek_warna'];?>" placeholder="Cek Warna">
            <span class="help-block with-errors"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="cek_dye" class="col-md-3 control-label">Cek Dye</label>
          <div class="col-md-6">
            <input type="text"  name="cek_dye" class="form-control" value="<?php echo $r['cek_dye'];?>" placeholder="Cek Dye">
            <span class="help-block with-errors"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="koreksi_resep" class="col-md-3 control-label">Koreksi Resep</label>
          <div class="col-md-6">
            <input type="text"  name="koreksi_resep" class="form-control" value="<?php echo $r['koreksi_resep'];?>" placeholder="Koreksi Resep">
            <span class="help-block with-errors"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="ket" class="col-md-3 control-label">Keterangan</label>
          <div class="col-md-8">
            <textarea class="form-control" id="ket" name="ket"><?php echo $r['ket'];?></textarea>
            <span class="help-block with-errors"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script>
  //Date picker
  $('#datepicker').datepicker({
      autoclose: true,
	  todayHighlight: true,
      format: 'yyyy-mm-dd'
    }),
    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true,
	  todayHighlight: true,
      format: 'yyyy-mm-dd'
    }),
    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true,
	  todayHighlight: true,
      format: 'yyyy-mm-dd'
    }),
    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true,
	  todayHighlight: true,
      format: 'yyyy-mm-dd'
    }),
	//Date picker
    $('#datepicker4').datepicker({
      autoclose: true,
	  todayHighlight: true,
      format: 'yyyy-mm-dd'
    });  


$(function () {
  //Initialize Select2 Elements
 $('.select2').select2()
});
</script>
<?php
} ?>
