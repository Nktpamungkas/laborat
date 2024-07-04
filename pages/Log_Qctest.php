<style>
    th {
        font-size: 10pt;
    }

    td {
        font-size: 10pt;
    }

    .row-highlight {
        background-color: #0088cc !important;
        color: white !important;
        /* Warna highlight */
        transition: background-color 0.3s ease;
        /* Efek transisi */
    }

    .input-xs {
        height: 22px !important;
        padding: 1px 2px;
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
<script src="bower_components/fastload/fastlog.js"></script>
<div class="box box-info">
    <div class="row">
        <div class="col-sm-3" style="margin-top: 15px;">
            <table id="Tables-join" class="table table-sm table-bordered table-sm display compact" style="width: 100%;">
                <thead>
                    <tr class="bg-success">
                        <th>#</th>
                        <th>No Counter</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- You know ? i do some magic here -->
                </tbody>
            </table>
        </div>
        <div class="col-sm-9" style="margin-top: 15px; margin-left:-40px;" id="lokasi_tabel">
            <table id="Log-detail" class="table table-sm table-bordered display compact" style="width: 100%;">
                <thead>
                    <tr class="bg-danger">
                        <th>#</th>
                        <th>Status</th>
                        <th>Info</th>
                        <th>User do</th>
                        <th>Date Time</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat oleh JavaScript setelah klik pada baris di tabel pertama -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var dataTable = $('#Tables-join').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [
                [0, 'desc']
            ],
            "pageLength": 25,
            "ajax": {
                url: "pages/ajax/data_server_logQctest.php",
                type: "POST",
                error: function() {
                    $(".dataku-error").html("");
                    $("#Tables-join").append('<tbody class="dataku-error"><tr><th colspan="2">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                "targets": 0, // Indeks kolom untuk nomor urut
                "className": "text-center"
            }],
            language: {
                searchPlaceholder: "Search..."
            }
        });


        $('#Tables-join tbody').on('click', 'tr', function() {
            // Hilangkan highlight dari semua baris kecuali yang diklik
            $('#Tables-join tbody tr').removeClass('row-highlight');
            $(this).addClass('row-highlight'); // Tambahkan highlight pada baris yang diklik

            var rowData = dataTable.row(this).data(); // Ambil data dari baris yang diklik
            var no_counter = rowData[1]; // Ambil nilai no_counter dari data yang diklik

            // AJAX untuk mengambil detail berdasarkan no_counter
            $.ajax({
                url: 'pages/ajax/data_server_qctest_detail.php',
                type: 'POST',
                data: {
                    no_counter: no_counter
                },
                success: function(response) {
                    // Tampilkan hasil AJAX di tabel kedua (#Log-detail)
                    $('#Log-detail tbody').html(response);
                }
            });
        });

        new $.fn.dataTable.FixedHeader(dataTable);
    });
</script>