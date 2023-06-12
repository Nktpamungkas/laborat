<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$idm = $_GET['idm'];
$sql = mysqli_query($con,"SELECT a.id as id_status, a.idm, a.flag, a.grp, a.matcher, a.cek_warna, a.cek_dye, a.status, a.kt_status, a.koreksi_resep, a.percobaan_ke, a.benang_aktual, a.lebar_aktual, a.gramasi_aktual, a.soaping_sh, a.soaping_tm, a.rc_sh, a.rc_tm, a.lr, a.cie_wi, a.cie_tint, a.done_matching, a.ph,
a.spektro_r, a.ket, a.created_at as tgl_buat_status, a.created_by as status_created_by, a.edited_at, a.edited_by, a.target_selesai, a.cside_c,
a.cside_min, a.tside_c, a.tside_min, a.mulai_by, a.mulai_at, a.selesai_by, a.selesai_at, a.approve_by, a.approve_at, a.approve,
b.id, b.no_resep, b.no_order, b.no_po, b.langganan, b.no_item, b.jenis_kain, b.benang, b.cocok_warna, b.warna, a.kadar_air,
b.no_warna, b.lebar, b.gramasi, b.qty_order, b.tgl_in, b.tgl_out,
b.proses, b.buyer, a.final_matcher, a.colorist1, a.colorist2, 
b.tgl_delivery, b.note, b.jenis_matching, b.tgl_buat, b.tgl_update, b.created_by
FROM tbl_status_matching a
INNER JOIN tbl_matching b ON a.idm = b.no_resep
where a.id = '$idm'
ORDER BY a.id desc limit 1");
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
                <li class="pull-right disabled bg-primary" disabled><a href="#" style="color: white;"><b>Parent > <?php echo $data['idm'] ?></b></a></li>
            </ul>
        </div>
        <div class="form-horizontal" id="form-status">
            <div class="tab-content">
                <div id="Merge-order" class="tab-pane fade in active">
                    <div class="row" style="margin-top: 20px">
                        <form action="#" id="form-merge-order" method="post">
                            <input type="hidden" id="id_matching_order" value="<?php echo $data['id'] ?>">
                            <input type="hidden" id="id_status_order" value="<?php echo $data['id_status'] ?>">
                            <input type="hidden" id="r_code_order" value="<?php echo $data['idm'] ?>">
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

<!-- disabling all input -->
<script>
    $(document).ready(function() {
        $('#form-status').find('input').prop('disabled', true)
        $('#form-status').find('select').prop('disabled', true)
        $('#form-status').find('textarea').prop('disabled', true)
        $('#form-status').find('#no_order_merger').prop("disabled", false);
        $('#form-status').find('#LOT').prop("disabled", false);
        $('#form-status').find('#addt_benang').prop("disabled", false);
    })
</script>
<!-- form validation & reload ajax table here  -->
<script>
    $(document).ready(function() {
        var dataTable = $('#additional_order_table').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
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
                    url: "pages/ajax/DeleteOrderChild.php",
                    data: {
                        id: id,
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
        })

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
<!-- PREPARATION FOR TABLE editable hold-->
<script>
    $(document).ready(function() {
        $("#lab").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(3) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_1").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(4) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_2").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(5) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_3").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(6) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_4").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(7) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_5").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(8) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_6").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(9) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_7").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(10) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_8").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(11) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_9").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(12) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
    });
</script>
<!-- EKSEKUSI SETELAH ADA PARAM DARI PREPARATION -->
<script>
    $(document).ready(function() {
        if (parseFloat($('#Adj_2').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='3']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='3']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_2').html()))
        }
        if (parseFloat($('#Adj_3').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='4']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='4']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_3').html()))
        }
        if (parseFloat($('#Adj_4').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='5']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='5']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_4').html()))
        }
        if (parseFloat($('#Adj_5').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='6']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='6']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_5').html()))
        }
        if (parseFloat($('#Adj_6').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='7']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='7']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_6').html()))
        }
        if (parseFloat($('#Adj_7').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='8']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='8']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_7').html()))
        }
        if (parseFloat($('#Adj_8').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='9']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='9']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_8').html()))
        }
        if (parseFloat($('#Adj_9').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='10']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='10']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_9').html()))
        }


        $('#tfoot').hide()

    });
</script>
<script>
    $(document).ready(function() {
        var dataTable = $('#table_hasil_celup').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
            "ordering": false,
            "lengthChange": false,
            "searching": false,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                url: "pages/ajax/data_server_GetHasilCelup_fromDyeing.php",
                type: "post",
                data: {
                    r_code: $('#idm').val(),
                    p: "Adjust_Resep_Lab"
                },
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]
                },
                {
                    "targets": [0],
                    "visible": false
                }
            ],
            createdRow: function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (colIndex == 8) {
                        $(this).attr('data-name', 'edit_kesetabilan');
                        $(this).attr('class', 'edit_kesetabilan text-center text-primary');
                        $(this).attr('data-type', 'select');
                        $(this).attr('data-pk', data[0]);
                    }
                });
            }
        });

        $('#table_hasil_celup').editable({
            container: 'body',
            selector: 'td.edit_kesetabilan',
            url: 'pages/ajax/edit_ksetabilan.php',
            title: 'Kesetabilan Resep',
            type: 'POST',
            datatype: 'json',
            source: [{
                value: "0X",
                text: "0X"
            }, {
                value: "1X",
                text: "1X"
            }, {
                value: "2X",
                text: "2X"
            }, {
                value: "3X",
                text: "3X"
            }, {
                value: "4X",
                text: "4X"
            }, {
                value: "5X",
                text: "5X"
            }, {
                value: "6X",
                text: "6X"
            }, {
                value: "7X",
                text: "7X"
            }, {
                value: "> 5X",
                text: "> 5X"
            }, ],
            validate: function(value) {
                if ($.trim(value) == '') {
                    return 'This field is required';
                }
            }
        });

        $(document).on('click', '.delete_celup', function() {
            let id = $(this).attr('data-pk');
            const conf = confirm('Apakah anda yakin ingin menghapus Hasil Celup ini ?')
            if (conf) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: "pages/ajax/Cut_relationWDye.php",
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert("Hubungi Departement DIT !");
                    }
                });
            }
        })

        $(document).on('click', '.bon_resep', function() {
            var url_bon = $(this).attr('data');
            centeredPopup(url_bon, 'myWindow', '800', '400', 'yes');
        })

        $(document).on('click', '#AddNote', function(e) {
            let m = '<?php echo $data['id_status'] ?>'
            $.ajax({
                url: "pages/ajax/modal_AddNote.php",
                type: "GET",
                data: {
                    idm: m,
                },
                success: function(ajaxData) {
                    $("#body_Addnote").html(ajaxData);
                    $("#ModalAddNote").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });
        $(document).on('click', '#seeTheNote', function(e) {
            let m = '<?php echo $data['id_status'] ?>'
            $.ajax({
                url: "pages/ajax/showTimelineNote.php",
                type: "GET",
                data: {
                    id_status: m,
                },
                success: function(ajaxData) {
                    $("#body_SeeResep").html(ajaxData);
                    $("#ModalSeeResep").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });
    })

    var popupWindow = null;

    function centeredPopup(url, winName, w, h, scroll) {
        LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
        settings =
            'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
        popupWindow = window.open(url, winName, settings)
    }
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '._addnoteclp', function(e) {
            let kk = $(this).attr('data-kk')
            let id_status = $('#id_status').val();
            // console.log(id)
            $.ajax({
                url: "pages/ajax/pop_up_addnoteclp.php",
                type: "GET",
                data: {
                    kk: kk,
                    id_status: id_status,
                },
                success: function(ajaxData) {
                    $("#body_addnoteclp").html(ajaxData);
                    $("#addnoteclp").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });

        $(document).on('click', '._seenote', function(e) {
            let id_status = $('#id_status').val();
            let kk = $(this).attr('data-kk')
            $.ajax({
                url: "pages/ajax/Pop_Up_SeeNote.php",
                type: "GET",
                data: {
                    kk: kk,
                    id_status: id_status
                },
                success: function(ajaxData) {
                    $("#body_PopUpSeeNote").html(ajaxData);
                    $("#PopUpSeeNote").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });
    })
</script>