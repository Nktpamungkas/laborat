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
  $datauser = mysqli_query($con,"SELECT * FROM tbl_matcher ORDER BY nama ASC");
  $no = 1;
  $n = 1;
  $c = 0;
  ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <a href="#" data-toggle="modal" data-target="#DataMatcher" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add</a>
        </div>
        <div class="box-body">
          <table width="100%" class="table table-bordered table-hover display" id="example2">
            <thead class="btn-primary">
              <tr>
                <th width="5%">No</th>
                <th width="57%">Nama</th>
                <th width="13%">Status</th>
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
                  <th><?php echo $rowd['nama']; ?></th>
                  <th><?php echo $rowd['status']; ?></th>
                  <th><a href="#" id='<?php echo $rowd['id'] ?>' class="btn btn-info matcher_edit"><i class="fa fa-edit"></i> </a></th>
                </tr>
              <?php
                $no++;
              } ?>
            </tbody>
          </table>
          <div class="modal fade modal-super-scaled" id="DataMatcher">
            <div class="modal-dialog ">
              <div class="modal-content">
                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_matcher" enctype="multipart/form-data">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Data Matcher</h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                      <label for="nama" class="col-md-3 control-label">Nama</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <span class="help-block with-errors"></span>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="sts" class="col-md-3 control-label">Status</label>
                      <div class="col-md-4">
                        <select name="sts" class="form-control" id="sts" required>
                          <option value="Aktif">Aktif</option>
                          <option value="Tidak Aktif">Tidak Aktif</option>
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
          </div>
          <!-- Modal Popup untuk Edit-->
          <div id="MatcherEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

          </div>
</body>

</html>
<script>
  $(document).ready(function() {
    $("#DataMatcher").on('shown.bs.modal', function() {
      $('#nama').focus();
    })
  })
</script>