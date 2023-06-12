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
        font-size: 9pt !important;
    }

    #dataku td,
    #dataku th {
        border: 1px solid #ddd;
        padding: 4px;
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
        var dataTable = $('#dataku').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                url: "pages/ajax/data_server_proses.php",
                type: "post",
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                "targets": [3],
                "className": "text-center"
            }, {
                "orderable": false,
                "targets": [0, 1, 2, 3, 4]
            }],
        });

        $(document).on('click', '._action', function() {
            var id = $(this).attr('attr-data');
            var value = $(this).html();
            $('#id_edit').val(id)
            $('#is_active_edit').val(value)
            $('#ProssesEdit').modal('show');
        })

        $('#exsecute').on('click', function() {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/ON_OFF_proses.php",
                data: {
                    id: $('#id_edit').val(),
                    is_active: $('#is_active_edit').find(':selected').val(),
                },
                success: function(response) {
                    if (response == "LIB_SUCCSS") {
                        toastr.success('Data berhasil di ubah !');
                        $("#ProssesEdit").modal('hide');
                        dataTable.ajax.reload(null, false)
                    } else {
                        toastr.error("ajax error !")
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        })
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
                                <th width="5%">No</th>
                                <th width="57%">DESC</th>
                                <th width="13%">IS_ACTIVE</th>
                                <th width="15%">Creared_at</th>
                                <th width="15%">Created_by</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- I DO SOME MAGIC HERE  -->
                        </tbody>
                    </table>
                    <div class="modal fade modal-super-scaled" id="DataUser">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_proses" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Data Proses</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="Product_name" class="col-md-3 control-label">Proses Desc</label>
                                            <div class="col-md-6">
                                                <input type="Product_Name" class="form-control" id="Proses_desc" name="Proses_desc" required>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="is_active" class="col-md-3 control-label">Is Active ?</label>
                                            <div class="col-md-6">
                                                <select name="is_active" class="form-control" id="is_active" required>
                                                    <option value="TRUE">TRUE</option>
                                                    <option value="FALSE">FALSE</option>
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
                    <div id="ProssesEdit" class="modal fade modal-super-scaled">
                        <div class="modal-dialog ">
                            <div class="modal-content" id="bodyDyessEdit">
                                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" id="frmDyesEdit">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">ON/OFF proses</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="id_edit" name="id">
                                        <div class="form-group">
                                            <label for="level" class="col-md-3 control-label">Is Active ?</label>
                                            <div class="col-md-6">
                                                <select name="is_active_edit" class="form-control" id="is_active_edit" required>
                                                    <option value="TRUE">TRUE</option>
                                                    <option value="FALSE">FALSE</option>
                                                </select>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                        <button type="button" id="exsecute" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
</body>
<script>
    $(document).ready(function() {
        $("#DataUser").on('shown.bs.modal', function() {
            $('#Proses_desc').focus();
        })
    })
</script>

</html>