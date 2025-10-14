<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>user</title>
</head>

<body>
  <?php
  $datauser = mysqli_query($con,"SELECT
                                    a.*,
                                    GROUP_CONCAT(b.name_menu ORDER BY b.id SEPARATOR ', ') AS roles
                                  FROM
                                    tbl_user a
                                  LEFT JOIN master_menu_cycletime b ON FIND_IN_SET(b.id, REPLACE(a.pic_cycletime, ';', ',')) > 0
                                  GROUP BY
                                    a.username, a.pic_cycletime
                                  ORDER BY
                                    a.username ASC");
  $no = 1;
  $n = 1;
  $c = 0;
  ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <a href="#" data-toggle="modal" data-target="#DataUser" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add</a>
        </div>
        <div class="box-body">
          <table width="100%" class="table table-bordered table-hover display" id="example2">
            <thead class="btn-primary">
              <tr>
                <th width="5%">No</th>
                <th width="57%">UserName</th>
                <th width="15%">Jabatan</th>
                <th width="13%">Status</th>
                <th width="13%">Role CycleTime</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $col = 0;
              while ($rowd = mysqli_fetch_array($datauser)) {
                $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
              ?>
                <tr bgcolor="<?php echo $bgcolor; ?>">
                  <th><?php echo $no; ?></th>
                  <th><?php echo $rowd['username']; ?></th>
                  <th><?php echo $rowd['jabatan'] ?></th>
                  <th><?php echo $rowd['status']; ?></th>
                  <th><?php echo $rowd['roles']; ?></th>
                  <th><a href="#" id='<?php echo $rowd['id'] ?>' class="btn btn-info user_edit"><i class="fa fa-edit"></i> </a></th>
                </tr>
              <?php
                $no++;
              } ?>
            </tbody>
          </table>
          <div class="modal fade modal-super-scaled" id="DataUser">
            <div class="modal-dialog ">
              <div class="modal-content">
                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_user" enctype="multipart/form-data">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Data User</h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                      <label for="username" class="col-md-3 control-label">Username</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" id="username" name="username" required>
                        <span class="help-block with-errors"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="username" class="col-md-3 control-label">Password</label>
                      <div class="col-md-6">
                        <input type="password" class="form-control" id="nama" name="password" required placeholder="Field ini wajib terisi..">
                        <span class="help-block with-errors"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="username" class="col-md-3 control-label">Re-Password</label>
                      <div class="col-md-6">
                        <input type="password" class="form-control" id="nama" name="re_password" required placeholder="Field ini wajib terisi..">
                        <span class="help-block with-errors"></span>
                      </div>
                    </div>
                    <input type="hidden" name="level" class="form-control" id="level" value="1" required placeholder="Field ini wajib terisi..">
                    <div class="form-group">
                      <label for="jabatan" class="col-md-3 control-label">Jabatan</label>
                      <div class="col-md-6">
                        <select name="jabatan" class="form-control" id="jabatan" required>
                          <?php $sql_role = mysqli_query($con,"SELECT `role` FROM master_role");
                          while ($role = mysqli_fetch_array($sql_role)) { ?>
                            <option value="<?php echo $role['role'] ?>"><?php echo $role['role'] ?></option>
                          <?php } ?>
                          <option selected disabled>-Pilih-</option>
                        </select>
                        <span class="help-block with-errors"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="thn" class="col-md-3 control-label">Tahun Mamber</label>
                      <div class="col-md-6">
                        <select name="thn" class="form-control" id="thn" required>
                          <option value="2017">2017</option>
                          <option value="2018">2018</option>
                          <option value="2019">2019</option>
                          <option value="2020">2020</option>
                          <option value="2020">2021</option>
                          <option value="2020">2022</option>
                          <option selected disabled>-Pilih-</option>
                        </select>
                        <span class="help-block with-errors"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="status" class="col-md-3 control-label">Status</label>
                      <div class="col-md-6">
                        <div class="radio">
                          <label>
                            <input type="radio" name="status" value="Aktif" id="status_0" checked>
                            Aktif
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" name="status" value="Non-Aktif" id="status_1">
                            Non-Aktif
                          </label>
                        </div>
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
          </div>
          <!-- Modal Popup untuk Edit-->
          <div id="UserEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

          </div>
</body>

</html>
<script>
  $(document).ready(function() {
    $("#DataUser").on('shown.bs.modal', function() {
      $('#username').focus();
    })
  })
</script>