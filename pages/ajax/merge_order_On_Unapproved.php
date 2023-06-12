<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$idm = $_GET['idm'];
$sql = mysqli_query($con,"SELECT * from tbl_matching where no_resep = '$idm' LIMIT 1");
$data = mysqli_fetch_array($sql);
?>
<style>
    .lookupST {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 9pt !important;
    }

    .lookupST td,
    .lookupST th {
        border: 1px solid black;
        padding: 2px;
    }

    .lookupST td {
        background-color: white;
    }

    .lookupST tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .lookupST tr:hover {
        background-color: rgb(151, 170, 212);
    }

    .lookupST th {
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
        background-color: #4CAF50;
        color: white;
    }

    .input-xs {
        height: 22px !important;
        padding: 2px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }

    .input-group-xs>.form-control,
    .input-group-xs>.input-group-addon,
    .input-group-xs>.input-group-btn>.btn {
        height: 22px;
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
    }
</style>
<div class="modal-content">
    <div class="modal-body">
        <div class="container-fluid">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#Merge-order"><b>Additional Order</b></a></li>
                <li class="pull-right disabled bg-primary" disabled><a href="#" style="color: white;"><b>Parent > <?php echo $data['no_resep'] ?></b></a></li>
            </ul>
        </div>
        <div class="form-horizontal" id="form-status">
            <div class="tab-content">
                <div id="Merge-order" class="tab-pane fade in active">
                    <div class="row" style="margin-top: 20px">
                        <form action="#" id="form-merge-order" method="post">
                            <input type="hidden" id="id_matching_order" value="<?php echo $data['id'] ?>">
                            <input type="hidden" id="id_status_order" value="">
                            <input type="hidden" id="r_code_order" value="<?php echo $data['no_resep'] ?>">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <p class="text-center" style="text-shadow: black; font-weight: bold; margin-bottom: 20px;">Input Additional Order</p>
                                    <label for="Jenis_Matching" class="col-sm-2 control-label">No. Order :</label>
                                    <div class="col-sm-10">
                                        <div class="col-sm-4">
                                            <select class="form-control input-sm select2" style="width: 100%" name="no_order_merger" id="no_order_merger" placeholder="No order to merge...">
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="LOT" id="LOT" placeholder="LOT...">
                                        </div>
                                        <div class="col-sm-1 input-group">
                                            <button type="button" class="btn btn-danger" id="button-merge-order">Submit <i class="fa fa-fw fa-link" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <label for="Jenis_Matching" class="col-sm-2 control-label" style="margin-top:10px;">Jenis Benang :</label>
                                    <div class="col-sm-9" style="margin-top:10px;">
                                        <textarea name="addt_benang" id="addt_benang" class="form-control" style="margin-left: 15px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-center" style="text-shadow: black; font-weight: bold;">Additional Order <?php echo $data['idm'] ?></p>
                                    <table class="table table-bordered table-sm" id="additional_order_table" width="100%">
                                        <thead class="bg-primary">
                                            <th>id</th>
                                            <th>flag</th>
                                            <th>No .Order</th>
                                            <th>Lot</th>
                                            <th>Benang</th>
                                            <th>insert at</th>
                                        </thead>
                                        <tbody>
                                            <!-- i do some magic here dude -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- <button class="btn btn-success" id="test">test</button> -->
        <div class="modal-footer" style="border-top: 1px solid black; height: 45px;">
            <div class="pull-right">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- form validation & reload ajax table here  -->
<script>
    $(document).ready(function() {
        var dataTable = $('#additional_order_table').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
            "ordering": false,
            "lengthChange": false,
            "searching": false,
            "order": [
                [1, "desc"]
            ],
            "ajax": {
                url: "pages/ajax/data_server_AddtionalOrderExisting.php",
                type: "post",
                data: {
                    id_matching: $('#id_matching_order').val(),
                    id_status: $('#id_status_order').val()
                },
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 1, 2, 3, 5]
                },
                {
                    "targets": [0],
                    "visible": false
                }
            ],
        });

        $(document).on('click', '._hapusOrder', function() {
            let id = $(this).attr('data-pk')
            if (confirm('apakah anda yakin ingin menghapus order ini ?')) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: "pages/ajax/DeleteOrderChild_Unapproved.php",
                    data: {
                        id: id,
                        id_matching: $('#id_matching_order').val(),
                        id_status: $('#id_status_order').val()
                    },
                    success: function(response) {
                        if (response.session == "LIB_SUCCSS") {
                            toastr.success('Order Number Removed')
                            dataTable.ajax.reload()
                        } else {
                            toastr.error("System Error !")
                        }
                    },
                    error: function() {
                        alert("Error hubungi DIT");
                    }
                });
            } else {
                console.log('cancel button')
            }
        });

        var form1 = $('#form-merge-order');
        var error1 = $('.alert-danger', form1);
        form1.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error text-sm',
            // focusInvalid: false,
            ignore: "",
            rules: {
                no_order_merger: {
                    required: true,
                },
                LOT: {
                    required: true
                }
            },
            // messege error-------------------------------------------------------
            messages: {
                LOT: {
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

        $('#button-merge-order').click(function(e) {
            e.preventDefault();
            if ($("#form-merge-order").valid()) {
                insertNomor_order($('#id_matching_order').val(), $('#id_status_order').val(), $('#r_code_order').val(),
                    $('#no_order_merger').find(':selected').val(), $('#LOT').val(), $('#addt_benang').val())
            } else {
                toastr.error('Data yang anda input belum lengkap !');
            }
        });

        function insertNomor_order(id_matching, id_status, Rcode, no_order, lot, addt_benang) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/insertNomor_order.php",
                data: {
                    id_matching: id_matching,
                    id_status: id_status,
                    Rcode: Rcode,
                    no_order: no_order,
                    lot: lot,
                    addt_benang: addt_benang
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        toastr.success(Rcode + 'Added to order !')
                        $('#LOT').val("");
                        $('#addt_benang').val("");
                        $('#no_order_merger').val(null).trigger('change');
                        dataTable.ajax.reload()
                    } else {
                        toastr.error("Nomor.order sudah terdaftar !")
                    }
                },
                error: function() {
                    alert("Error hubungi DIT");
                }
            });
        }
    });
</script>
<!-- Ajax Select2  -->
<script>
    $(document).ready(function() {
        $('.form-control.select2').select2({
            minimumInputLength: 6,
            allowClear: true,
            placeholder: 'Insert No.Order ....',
            ajax: {
                dataType: 'json',
                url: 'pages/ajax/Get_no_order_to_merge.php',
                delay: 500,
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                },
            }
        })
    })
</script>