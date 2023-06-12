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
<style>
    #dataku {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 10pt !important;
    }

    #dataku td,
    #dataku th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #dataku tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #dataku tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #dataku th {
        padding-top: 10px;
        padding-bottom: 10px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>
<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        $('.selectLampu').select2();
        var dataTable = $('#dataku').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "ASC"]
            ],
            columns: [{
                name: 'first',
            }, {
                name: 'second',
            }, {
                name: 'first',
            }, {
                name: 'first',
            }, {
                name: 'first',
            }, {
                name: 'first',
            }],
            "ajax": {
                url: "pages/ajax/data_server_LampuBuyer.php",
                type: "post",
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5],
                "className": "text-center"
            }, {
                "orderable": false,
                "targets": [0, 1, 2, 3, 4, 5]
            }, {
                "targets": [0],
                "visible": false
            }],
            rowsGroup: [
                'second:name'
            ],
        });
    });
</script>

<body>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a href="#" data-toggle="modal" data-target="#DataUser" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Add</a>
                </div>
                <div class="box-body">
                    <table width="100%" class="table table-bordered table-hover display" id="dataku" style="border: 1px solid #595959; padding:5px;">
                        <thead class="btn-primary">
                            <tr>
                                <th width="10%">No</th>
                                <th width="15%">Buyer</th>
                                <th width="4%">Urutan</th>
                                <th width="52%">Lampu</th>
                                <th width="57%">Created_at</th>
                                <th width="13%">Created_by</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- I DO SOME MAGIC HERE  -->
                        </tbody>
                    </table>

                    <!-- modal add -->
                    <div class="modal fade modal-super-scaled" id="DataUser">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_Vpot_lampu" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Data Lampu</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="code" class="col-md-3 control-label">Buyer</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="Buyer" name="Buyer" required>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product_name" class="col-md-3 control-label">1. Lampu</label>
                                            <div class="col-md-6">
                                                <?php $sqlLampu = mysqli_query($con,"SELECT nama_lampu from master_lampu"); ?>
                                                <select style="width:300px" class="form-control selectLampu" name="lampu1" required>
                                                    <option value="" selected disabled>pilih..</option>
                                                    <?php while ($lampu = mysqli_fetch_array($sqlLampu)) { ?>
                                                        <option value="<?php echo $lampu['nama_lampu'] ?>"><?php echo $lampu['nama_lampu'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product_name" class="col-md-3 control-label">2. Lampu</label>
                                            <div class="col-md-6">
                                                <?php $sqlLampu = mysqli_query($con,"SELECT nama_lampu from master_lampu"); ?>
                                                <select style="width:300px" class="form-control selectLampu" name="lampu2">
                                                    <option value="" selected disabled>pilih..</option>
                                                    <?php while ($lampu = mysqli_fetch_array($sqlLampu)) { ?>
                                                        <option value="<?php echo $lampu['nama_lampu'] ?>"><?php echo $lampu['nama_lampu'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product_name" class="col-md-3 control-label">3. Lampu</label>
                                            <div class="col-md-6">
                                                <?php $sqlLampu = mysqli_query($con,"SELECT nama_lampu from master_lampu"); ?>
                                                <select style="width:300px" class="form-control selectLampu" name="lampu3">
                                                    <option value="" selected disabled>pilih..</option>
                                                    <?php while ($lampu = mysqli_fetch_array($sqlLampu)) { ?>
                                                        <option value="<?php echo $lampu['nama_lampu'] ?>"><?php echo $lampu['nama_lampu'] ?></option>
                                                    <?php }  ?>
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

                    <!-- modal edit -->
                    <div class="modal fade modal-super-scaled" id="DataLampu">
                        <div class="modal-dialog" id="bodyDataLampu">

                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>



                </div>
</body>

</html>
<script>
    $(document).ready(function() {
        $(document).on('click', '.edit_lampu', function(e) {
            var m = $(this).attr("attr-data");
            $.ajax({
                url: "pages/ajax/modal_edit_lampu.php",
                type: "GET",
                data: {
                    id: m,
                },
                success: function(ajaxData) {
                    $("#bodyDataLampu").html(ajaxData);
                    $("#DataLampu").modal('show', {
                        backdrop: 'true'
                    });
                }
            });
        });
        $("#DataUser").on('shown.bs.modal', function() {
            $('#Buyer').focus();
        })
    })
</script>