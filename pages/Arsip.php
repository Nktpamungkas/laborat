<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Status Matching</title>
</head>
<style>
    .dataTables_wrapper .myfilter .dataTables_filter {
        float: left
    }

    .dataTables_wrapper .mylength .dataTables_length {
        float: right
    }

    #Table-join_filter label input.form-control {
        width: 500px;
    }

    th {
        font-size: 10pt;
    }

    td {
        font-size: 10pt;
    }

    #Table-join td,
    #Table-join th {
        border: 0.1px solid #ddd;
    }

    #Table-join th {
        color: black;
        background: #4CAF50;
    }

    #Table-join tr:hover {
        background-color: rgb(151, 170, 212);
    }

    .input-xs {
        height: 22px !important;
        padding: 2px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }

    .text-bold {
        font-weight: bold;
        font-style: italic;
        font-family: sans-serif;
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

<body>
    <div class="row">
        <div class="box">
            <div class="box-header with-border">
                <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                    <button id="delete-all" class="btn btn-sm btn-danger" style="margin-bottom : 4px;"><i class="fa fa-trash"></i> Hapus Kumpulan</button>
                    <table id="Table-join" class="table table-sm display compact" style="width: 100%;">
                        <thead>
                            <tr class="alert-success" style="border: 1px solid #ddd;">
                                <th style="border: 1px solid #ddd;"><input type="checkbox" id="all"></th>
                                <th style="border: 1px solid #ddd;">####</th>
                                <th style="border: 1px solid #ddd;">Grp</th>
                                <th style="border: 1px solid #ddd;">Matcher</th>
                                <th style="border: 1px solid #ddd;">Rcode</th>
                                <th style="border: 1px solid #ddd;">No.Order</th>
                                <th style="border: 1px solid #ddd;">Langganan</th>
                                <th style="border: 1px solid #ddd;">Warna</th>
                                <th style="border: 1px solid #ddd;">No.warna</th>
                                <th style="border: 1px solid #ddd;">No.item</th>
                                <th style="border: 1px solid #ddd;">Po.greige</th>
                                <th style="border: 1px solid #ddd;">Cck-warna</th>
                                <th style="border: 1px solid #ddd;">Tg.apprv</th>
								<th style="border: 1px solid #ddd;">Tg.arsip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- i do some magic here dude -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-3d-slit" id="ModalMergeOrder" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div id="body_ModalMergeOrder" class="modal-dialog" style="width:95%">

        </div>
    </div>
</body>


<script>
    $(function() {
        var dataTable = $('#Table-join').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
                [0, 'desc']
            ],
            "pageLength": 25,
            // dom: "<'myfilter'f><'mylength'l>t",
            "ajax": {
                url: "pages/ajax/data_server_arsip.php",
                type: "post",
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                "targets": [1, 2, 3],
                "className": "text-center"
            }, {
                orderable: false,
                targets: 0
            }, {
                "targets": [4],
                "className": "text-bold"
            }, {
                "visible": false,
                "targets": [3]
            }],
            language: {
                searchPlaceholder: "You can search by Rcode, Color, No.Color, Order number, PO number & Tg.Arsip"
            },
        });

        new $.fn.dataTable.FixedHeader(dataTable);

        $('#all').on('click', function() {
            if ($(this).is(':checked')) {
                $('input:checkbox').prop('checked', true)
            } else {
                $('input:checkbox').prop('checked', false)
            }
        })

        $('#delete-all').on('click', function() {
            let checkbox = $('tbody input:checkbox:checked');
            let count = checkbox.length
            let last = checkbox.last().attr('id')

            if (last == undefined) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Anda belum memilih !',
                    showConfirmButton: true,
                })
            } else {
                Swal.fire({
                    title: "Autentikasi",
                    text: "Apakah anda yakin untuk menghapus resep ini ",
                    showCancelButton: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        SpinnerShow()
                        $(checkbox).each(function(i) {
                            var idm = $(this).attr('idm');
                            var id_matching = $(this).attr('id_matching');
                            var id_status = $(this).attr('id_status');
                            var no_order = $(this).attr('no_order');
                            var urutan = $(this).attr('id');
                            var tr = $(this).closest('tr');
                            $.ajax({
                                dataType: "json",
                                type: "POST",
                                url: "pages/ajax/Delete_by_3ID.php",
                                data: {
                                    idm: idm,
                                    id_matching: id_matching,
                                    id_status: id_status,
                                    no_order: no_order,
                                    why_batal: 'Hapus Kumpulan'
                                },
                                success: function(response) {
                                    if (last == urutan) {
                                        dataTable.row(tr).remove().draw(), SpinnerHide(), Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: 'Semua arsip termarking berhasil di hapus !',
                                            showConfirmButton: true,
                                        })
                                    } else {

                                    }
                                },
                                error: function() {
                                    alert("Error");
                                }
                            });
                        });
                    } else if (result.value !== "") {
                        console.log('button cancel clicked !')
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi Error',
                        })
                    }
                });
            }
        })
    })

    $(document).on('click', '.delete', function() {
        var idm = $(this).attr('idm');
        var id_matching = $(this).attr('id_matching');
        var id_status = $(this).attr('id_status');
        var no_order = $(this).attr('no_order');
        if ($(this).attr('why_batal') == "") {
            var why_batal = 'Rejected by leader/SPV';
        } else {
            var why_batal = $(this).attr('why_batal');
        }
        console.log(why_batal)
        Swal.fire({
            title: "Autentikasi",
            text: "Apakah anda yakin untuk menghapus resep ini ",
            showCancelButton: true,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: "pages/ajax/Delete_by_3ID.php",
                    data: {
                        idm: idm,
                        id_matching: id_matching,
                        id_status: id_status,
                        no_order: no_order,
                        why_batal: why_batal
                    },
                    success: function(response) {
                        console.log(response)
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Resep ' + idm + ' berhasil di hapus !',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 1505);
                    },
                    error: function() {
                        alert("Error");
                    }
                });
            } else if (result.value !== "") {
                console.log('button cancel clicked !')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Password yang anda masukan salah !',
                })
            }
        });
    })

    var spinner = new jQuerySpinner({
        parentId: 'block-full-page'
    });

    function disableScroll() {
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
            window.onscroll = function() {
                window.scrollTo(scrollLeft, scrollTop);
            };
    }

    function enableScroll() {
        window.onscroll = function() {};
    }

    function SpinnerShow() {
        spinner.show();
        disableScroll()
    }

    function SpinnerHide() {
        spinner.hide();
        enableScroll();
    }
</script>

</html>