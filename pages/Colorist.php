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
    $datauser = mysqli_query($con,"SELECT id, nama, is_active FROM tbl_colorist ORDER BY nama ASC");
    $no = 1;
    $n = 1;
    $c = 0;
    ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a href="#" data-toggle="modal" data-target="#DataColorist" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add</a>
                </div>
                <div class="box-body">
                    <table width="100%" class="table table-bordered table-hover display" id="example2">
                        <thead class="btn-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $col = 0;
                            while ($rowd = mysqli_fetch_array($datauser)) {
                                $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
                            ?>
                                <tr bgcolor="<?php echo $bgcolor; ?>">
                                    <td align="center"><?php echo $no; ?></td>
                                    <td align="center"><?php echo $rowd['nama']; ?></td>
                                    <td align="center"><?php if ($rowd['is_active'] == 'TRUE') {
                                                            echo '<button class="btn btn-xs btn-info">Aktif</button>';
                                                        } else {
                                                            echo '<button class="btn btn-xs btn-danger">Non-Aktif</button>';
                                                        } ?></td>
                                    <td align="center"><button data-toggle="modal" data-target="#EditColorist<?php echo $rowd['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></button></td>
                                </tr>
                                <div class="modal fade modal-super-scaled" id="EditColorist<?php echo $rowd['id'] ?>">
                                    <div class="modal-dialog ">
                                        <div class="modal-content">
                                            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=POSTedit_colorist">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title">EDIT Data Colorist</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="id" name="id" value="<?php echo $rowd['id'] ?>
                                                    ">
                                                    <div class="form-group row">
                                                        <label for="nama" class="col-md-3 control-label">Nama</label>
                                                        <div class="col-md-6">
                                                            <input readonly type="text" class="form-control" id="nama" name="nama" required value="<?php echo $rowd['nama'] ?>" readonly>
                                                            <span class="help-block with-errors"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="sts" class="col-md-3 control-label">Status</label>
                                                        <div class="col-md-4">
                                                            <select name="sts" class="form-control" id="sts" required>
                                                                <option <?php if ($rowd['is_active'] == 'TRUE') echo 'selected' ?> value="TRUE">Aktif</option>
                                                                <option <?php if ($rowd['is_active'] == 'FALSE') echo 'selected' ?> value="FALSE">Tidak Aktif</option>
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
                            <?php
                                $no++;
                            } ?>
                        </tbody>
                    </table>
                    <div class="modal fade modal-super-scaled" id="DataColorist">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_colorist">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add Data Colorist</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="nama" class="col-md-3 control-label">Nama</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="nama" name="nama" required placeholder="-NAMA-">
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="sts" class="col-md-3 control-label">Status</label>
                                            <div class="col-md-4">
                                                <select name="sts" class="form-control" id="sts" required>
                                                    <option value="TRUE">Aktif</option>
                                                    <option value="FALSE">Tidak Aktif</option>
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
        $("#DataColorist").on('shown.bs.modal', function() {
            $('#nama').focus();
        })
    })
</script>