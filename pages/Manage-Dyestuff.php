<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        var dataTable = $('#dataku').DataTable({
            "dom": 'Blfrtip',
            fixedHeader: true,
            "buttons": [
                'excel', 'pdf'
            ],
            aLengthMenu: [
                [25, 50, 100, 200, 2000],
                [25, 50, 100, 200, 'All']
            ],
            // iDisplayLength: -1,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                url: "pages/ajax/data_server_dyestuff.php",
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
                "targets": [0, 1, 2, 3, 4, 5]
            }],
        });

        $(document).on('click', '.dyess_edit', function(e) {
            var m = $(this).attr("id");
            $.ajax({
                url: "pages/ajax/Get_dyess_to_edit.php",
                type: "POST",
                data: {
                    id: m,
                },
                success: function(response) {
                    var aData = JSON.parse(response);
                    $('#id_edit').val(aData.id)
                    $('#ket_edit').val(aData.ket)
                    $('#Code_edit').val(aData.code)
					$('#Code_New_edit').val(aData.code_new)
                    $('#Product_Name_edit').val(aData.Product_Name)
                    $('#Product_Unit_edit').val(aData.Product_Unit)
                    $('#is_active_edit').val(aData.is_active)

                    $('#DyessEdit').modal('show');
                }
            });
        });

        // validation

        var form1 = $('#frmDyesEdit');
        var error1 = $('.alert-danger', form1);

        form1.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error text-sm',
            // focusInvalid: false,
            ignore: "",
            rules: {
                Code: {
                    required: true,
                }
            },
            // messege error-------------------------------------------------------
            messages: {
                Code_edit: {
                    required: "This field is required !"
                },
            },

            invalidHandler: function(event, validator) { //display error alert on form submit
                // success1.hide();
                error1.show();
                // App.scrollTo(error1, -200);
            },

            errorPlacement: function(error, element) { // render error placement for each input type
                var cont = $(element).parent('.input-group');
                if (cont.length > 0) {
                    cont.after(error);
                } else {
                    element.after(error);
                }
            },

            highlight: function(element) { // hightlight error inputs

                $(element)
                    .closest('.form-group').addClass(
                        'has-error'); // set error class to the control group
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass(
                        'has-error'); // set error class to the control group
            },

            submitHandler: function(form) {
                // success1.show();
                error1.hide();
            }
        });
        $.validator.setDefaults({
            debug: true,
            success: 'valid'
        });
        // })

        $('#exsecute').click(function(e) {
            e.preventDefault();
            if ($("#frmDyesEdit").valid()) {
                dyess_insert_edit($('#id_edit').val(), $('#ket_edit').val(), $('#Code_edit').val(), $('#Code_New_edit').val(), $('#Product_Name_edit').val(), $('#liquid_powder_edit').val(), $('#Product_Unit_edit').find(':selected').val(), $('#is_active_edit').find(':selected').val())
            }
        })


        function dyess_insert_edit(id, Ket, Code, code_new, Product_Name, liquid_powder, Product_Unit, is_active) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/dyess_insert_edit.php",
                data: {
                    id: id,
                    Ket: Ket,
                    Code: Code,
					code_new: code_new,
                    Product_Name: Product_Name,
                    liquid_powder: liquid_powder,
                    Product_Unit: Product_Unit,
                    is_active: is_active
                },
                success: function(response) {
                    if (response == "LIB_SUCCSS") {
                        toastr.success('Data berhasil di ubah !');
                        $("#DyessEdit").modal('hide');
                        dataTable.ajax.reload(null, false)
                    } else {
                        toastr.error("ajax error !")
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        }

        $('#DataUser').on('shown.bs.modal', function() {
            $('#code').focus();
        })
    });
</script>

<body>
    <!-- <php
    $sql_dyestuff = mysql_query("SELECT id, code, Product_Name, is_active FROM tbl_dyestuff ORDER BY id");
    $no = 1;
    $n = 1;
    $c = 0;
    ?> -->
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
                                <th width="15%">Code</th>
								<th width="15%">ERP Code</th>
                                <th width="57%">Product Name</th>
                                <th width="57%">Liquid/Powder</th>
                                <th width="57%">UoM</th>
                                <th width="13%">IS ACTIVE</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- I DO SOME MAGIC HERE  -->
                        </tbody>
                    </table>
                    <div class="modal fade modal-super-scaled" id="DataUser">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_dyess" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Data Dyestuff</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!-- <input type="hidden" id="id" name="id"> -->
                                        <div class="form-group">
                                            <label for="code" class="col-md-3 control-label">code</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="code" name="Code" maxlength="12" required>
                                                <span class="help-block with-errors">max 12 karakter</span>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label for="code_new" class="col-md-3 control-label">ERP code</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="code_new" name="Code_new" maxlength="7">
                                                <span class="help-block with-errors">max 7 karakter</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product_name" class="col-md-3 control-label">Product Name</label>
                                            <div class="col-md-6">
                                                <input type="Product_Name" class="form-control" id="Product_name" name="Product_name" required>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="liquid_powder" class="col-md-3 control-label">Liquid / Powder</label>
                                            <div class="col-md-6">
                                                <select name="liquid_powder" id="liquid_powder" class="form-control" required>
                                                    <option value="Liquid">Liquid</option>
                                                    <option value="Powder">Powder</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product_Name" class="col-md-3 control-label">Satuan </label>
                                            <div class="col-md-6">
                                                <select name="Product_Unit" id="Product_Unit" class="form-control" required>
                                                    <option value="2">-</option>
                                                    <option value="1">%</option>
                                                    <option value="0">Gr/L</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ket" class="col-md-3 control-label">Commentline</label>
                                            <div class="col-md-6">
                                                <select name="ket" id="ket" class="form-control">
                                                    <option value="-">-</option>
                                                    <option value="Suhu">Suhu</option>
                                                </select>
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
                    <div id="DyessEdit" class="modal fade modal-super-scaled">
                        <div class="modal-dialog ">
                            <div class="modal-content" id="bodyDyessEdit">
                                <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" id="frmDyesEdit">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Edit Dyestuff</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="id_edit" name="id">
                                        <div class="form-group">
                                            <label for="Code" class="col-md-3 control-label">Code</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="Code_edit" name="Code" maxlength="12" required>
                                                <span class="help-block with-errors">Max 12 Karakter</span>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label for="Code_New" class="col-md-3 control-label">ERP Code</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="Code_New_edit" name="Code_new" maxlength="7">
                                                <span class="help-block with-errors">Max 7 Karakter</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product_Name_edit" class="col-md-3 control-label">Product Name</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="Product_Name_edit" name="Product_Name" required>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="liquid_powder" class="col-md-3 control-label">Liquid / Powder</label>
                                            <div class="col-md-6">
                                                <select name="liquid_powder" id="liquid_powder_edit" class="form-control" required>
                                                    <option value="Liquid">Liquid</option>
                                                    <option value="Powder">Powder</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product_Name" class="col-md-3 control-label">Satuan </label>
                                            <div class="col-md-6">
                                                <select name="Product_Unit" id="Product_Unit_edit" class="form-control" required>
                                                    <option value="2">-</option>
                                                    <option value="1">%</option>
                                                    <option value="0">Gr/L</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ket" class="col-md-3 control-label">Commentline</label>
                                            <div class="col-md-6">
                                                <select name="ket" id="ket_edit" class="form-control">
                                                    <option value="-">-</option>
                                                    <option value="Suhu">Suhu</option>
                                                </select>
                                            </div>
                                        </div>
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

</html>