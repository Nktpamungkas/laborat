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
        border: 0.1px solid gray;
    }

    #Table-join th {
        color: white;
        background: black;
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
<script>
    $(document).ready(function() {
        var dataTable = $('#Table-join').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "select": true,
            pageLength: 100,
            "order": [
                [2, 'desc']
            ],
            columns: [{
                name: 'first',
            }, {
                name: 'first',
            }, {
                name: 'first',
            }, {
                name: 'second',
            }, {
                name: 'first',
            }, {
                name: 'first',
            }, {
                name: 'first',
            }],
            // dom: "<'myfilter'f><'mylength'l>t",
            "ajax": {
                url: "pages/ajax/dataServer_DBbyDyestuff.php",
                type: "post",
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            rowsGroup: [
                'second:name'
            ],
            language: {
                searchPlaceholder: "You can search by Rcode, nama dyestuff & Kode"
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5, 6],
                "className": "text-center"
            }, {
                "visible": false,
                "targets": [1, 2]
            }, {
                "orderable": false,
                "targets": [0, 1, 2, 3, 4]
            }],
        });
    });
</script>

<!-- original -->
<!-- <script type="text/javascript" language="javascript">
    $(document).ready(function() {
        var groupColumn = [0];
        // var columnAction = [13];
        var dataTable = $('#Table-join').DataTable({
            "processing": true,
            "serverSide": true,
            pageLength: 10,
            "order": [
                [0, 'asc'],
                [2, 'asc']
            ],
            // dom: "<'myfilter'f><'mylength'l>t",
            "ajax": {
                url: "pages/ajax/data_server_dbresep.php",
                type: "post",
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                "targets": [2, 5, 6, 11, 12, 13],
                "className": "text-center"
            }, {
                "visible": false,
                "targets": [0, 1, 3, 4, 7, 8, 9, 10, 11]
            }, {
                "orderable": false,
                "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
            }],
            language: {
                searchPlaceholder: "You can search by Rcode, Color, No.Color & Order number..."
            },
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background-color: #797a7a; color: white;"><td colspan="8">' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
        $('#Table-join tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
                table.order([groupColumn, 'desc']).draw();
            } else {
                table.order([groupColumn, 'asc']).draw();
            }
        });
    });
</script> -->

<body>
    <div class="row">
        <div class="box">
            <div class="box-header with-border">
                <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                    <table id="Table-join" class="display compact nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>id</th>
                                <th>id_status</th>
                                <th>kode</th>
                                <th>nama</th>
                                <th>Rcode</th>
                                <th>Detail</th>
                                <!-- <th width="70px">Handle</th> -->
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
    $(document).on('click', '._merge', function(e) {
        var m = $(this).attr("data-attribute");
        $.ajax({
            url: "pages/ajax/modal_merge_order.php",
            type: "GET",
            data: {
                idm: m,
            },
            success: function(ajaxData) {
                $("#body_ModalMergeOrder").html(ajaxData);
                $("#ModalMergeOrder").modal('show', {
                    backdrop: 'false'
                });
            }
        });
    });
    $(document).ready(function() {
        $("#ModalMergeOrder").on("hidden.bs.modal", function() {
            $("#body_ModalMergeOrder").empty();
        });
    })
</script>
<script type="text/javascript">
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
        setTimeout(function() {
            spinner.hide();
            enableScroll();
            window.location.href = 'index1.php?p=Wait-approval';
        }, 1000);
    }
</script>

</html>