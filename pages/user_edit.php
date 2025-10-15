<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$modal_id = $_GET['id'];
$modal = mysqli_query($con,"SELECT * FROM `tbl_user` WHERE id='$modal_id' ");
while ($r = mysqli_fetch_array($modal)) {
?>
  <div class="modal-dialog ">
    <div class="modal-content">
      <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_user" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edit User</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="id" name="id" value="<?php echo $r['id']; ?>">
          <div class="form-group">
            <label for="username" class="col-md-3 control-label">Username</label>
            <div class="col-md-6">
              <input type="text" class="form-control" id="username" name="username" value="<?php echo $r['username']; ?>" required>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="username" class="col-md-3 control-label">Password</label>
            <div class="col-md-6">
              <input type="password" class="form-control" id="nama" name="password" value="<?php echo $r['password']; ?>" required>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="username" class="col-md-3 control-label">Re-Password</label>
            <div class="col-md-6">
              <input type="password" class="form-control" id="nama" name="re_password" required>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <input type="hidden" name="level" value="1">
          <div class="form-group">
            <label for="jabatan" class="col-md-3 control-label">Jabatan</label>
            <div class="col-md-6">
              <select name="jabatan" class="form-control" id="jabatan" required>
                <?php $sql_role = mysqli_query($con,"SELECT `role` FROM master_role");
                while ($role = mysqli_fetch_array($sql_role)) { ?>
                  <option <?php if ($r['jabatan'] == $role['role']) echo 'Selected'; ?> value="<?php echo $role['role'] ?>"><?php echo $role['role'] ?></option>
                <?php } ?>
              </select>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="thn" class="col-md-3 control-label">Tahun Mamber</label>
            <div class="col-md-6">
              <select name="thn" class="form-control" id="thn" required>
                <option value="2017" <?php if ($r['mamber'] == "2017") {
                                        echo "SELECTED";
                                      } ?>>2017</option>
                <option value="2018" <?php if ($r['mamber'] == "2018") {
                                        echo "SELECTED";
                                      } ?>>2018</option>
                <option value="2019" <?php if ($r['mamber'] == "2019") {
                                        echo "SELECTED";
                                      } ?>>2019</option>
                <option value="2020" <?php if ($r['mamber'] == "2020") {
                                        echo "SELECTED";
                                      } ?>>2020</option>
              </select>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="username" class="col-md-3 control-label">Status</label>
            <div class="col-md-6">
              <div class="radio">
                <label>
                  <input type="radio" name="status" value="Aktif" id="status_0" <?php if ($r['status'] == "Aktif") {
                                                                                  echo "checked";
                                                                                } ?>>
                  Aktif
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="status" value="Non-Aktif" id="status_1" <?php if ($r['status'] == "Non-Aktif") {
                                                                                      echo "checked";
                                                                                    } ?>>
                  Non-Aktif
                </label>
              </div>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="roles" class="col-md-3 control-label">Role CycleTime</label>
            <div class="col-md-6">
              <?php
              // Ambil semua role dari master_menu_cycletime
              $dataRoleCycletime = mysqli_query($con, "SELECT * FROM master_menu_cycletime ORDER BY id ASC");

              // Ubah data pic_cycletime user menjadi array
              $selected_roles = explode(';', $r['pic_cycletime']);

              while ($role = mysqli_fetch_array($dataRoleCycletime)) {
                $checked = in_array($role['id'], $selected_roles) ? 'checked' : '';
              ?>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="roles[]" value="<?php echo $role['id']; ?>" <?php echo $checked; ?>>
                    <?php echo $role['name_menu']; ?>
                  </label>
                </div>
              <?php } ?>
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