<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$modal_id = $_GET['id'];
$modal = mysqli_query($con,"SELECT * FROM `tbl_matcher` WHERE id='$modal_id' ");
while ($r = mysqli_fetch_array($modal)) {
?>
  <div class="modal-dialog ">
    <div class="modal-content">
      <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_matcher" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edit Matcher</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="id" name="id" value="<?php echo $r['id']; ?>">
          <div class="form-group">
            <label for="nama" class="col-md-3 control-label">Nama</label>
            <div class="col-md-6">
              <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $r['nama']; ?>" required readonly>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="thn" class="col-md-3 control-label">Status</label>
            <div class="col-md-4">
              <select name="sts" class="form-control" id="sts" required>
                <option value="Aktif" <?php if ($r['status'] == "Aktif") {
                                        echo "SELECTED";
                                      } ?>>Aktif</option>
                <option value="Tidak Aktif" <?php if ($r['status'] == "Tidak Aktif") {
                                              echo "SELECTED";
                                            } ?>>Tidak Aktif</option>
              </select>
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
<?php } ?>