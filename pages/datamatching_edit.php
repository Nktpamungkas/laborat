<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$modal_id = $_GET['id'];
$modal = mysqli_query($con,"SELECT * FROM `tbl_status_matching` WHERE id='$modal_id' ");
while ($r = mysqli_fetch_array($modal)) {
?>
  <div class="modal-dialog ">
    <div class="modal-content">
      <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=Schedule-matching" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edit Matching</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="id" name="id" value="<?php echo $r['id']; ?>">
          <div class="form-group">
            <label for="grp" class="col-md-3 control-label">Group</label>
            <div class="col-md-2">
              <select name="grp" class="form-control select2" id="grp" required style="width: 100%">
                <option value="A" <?php if ($r['grp'] == "A") {
                                    echo "SELECTED";
                                  } ?>>A</option>
                <option value="B" <?php if ($r['grp'] == "B") {
                                    echo "SELECTED";
                                  } ?>>B</option>
                <option value="C" <?php if ($r['grp'] == "C") {
                                    echo "SELECTED";
                                  } ?>>C</option>
                <option value="D" <?php if ($r['grp'] == "D") {
                                    echo "SELECTED";
                                  } ?>>D</option>
                <option value="E" <?php if ($r['grp'] == "E") {
                                    echo "SELECTED";
                                  } ?>>E</option>
                <option value="F" <?php if ($r['grp'] == "F") {
                                    echo "SELECTED";
                                  } ?>>F</option>
              </select>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="matcher" class="col-md-3 control-label">Matcher</label>
            <div class="col-md-6">
              <select name="matcher" class="form-control select2" id="matcher" required>
                <?php $qrymc = mysqli_query($con,"SELECT * FROM tbl_matcher ORDER BY nama ASC");
                while ($dmc = mysqli_fetch_array($qrymc)) { ?>
                  <option value="<?php echo $dmc['nama']; ?>" <?php if ($dmc['nama'] == $r['matcher']) {
                                                              echo "selected";
                                                            } ?>><?php echo $dmc['nama']; ?></option>
                <?php } ?>
              </select>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="ket_st" class="col-md-3 control-label">Ket. Status</label>
            <div class="col-md-3">
              <select name="ket_st" class="form-control select2" id="ket_st" required>
                <option value="Normal" <?php if ($r['kt_status'] == "Normal") {
                                          echo "SELECTED";
                                        } ?>>Normal</option>
                <option value="Urgent" <?php if ($r['kt_status'] == "Urgent") {
                                          echo "SELECTED";
                                        } ?>>Urgent</option>

              </select>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="ket" class="col-md-3 control-label">Keterangan</label>
            <div class="col-md-8">
              <textarea class="form-control" id="keteee" name="keteee"><?php echo $r['ket']; ?></textarea>
              <span class="help-block with-errors"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" value="submit" name="savee" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
  <script>
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()
    });
  </script>
<?php } ?>