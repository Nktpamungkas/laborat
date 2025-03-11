<?php
    $hostname = "10.0.0.21";
                             // $database = "NOWTEST"; // SERVER NOW 20
    $database    = "NOWPRD"; // SERVER NOW 22
    $user        = "db2admin";
    $passworddb2 = "Sunkam@24809";
    $port        = "25000";
    $conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
    $conn1       = db2_connect($conn_string, '', '');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Detail Kegiatan | Kartu Riwayat</title>
        <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bower_components/DataTable/jQuery-3.3.1/jQuery-3.3.1.min.js"></script>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="../../bower_components/xeditable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../../plugins/jquery-ui-1.12.1/jquery-ui.min.css">
    <link href="../../bower_components/toastr/toastr.css" rel="stylesheet">
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../bower_components/custom-table.css">
    <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../../bower_components/DataTable/datatables.min.css">
    <link rel="stylesheet" href="../../bower_components/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <link href="../../bower_components/sweet-alert/dist/sweetalert2.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../../bower_components/sweet-alert/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../../bower_components/circle-indicator-spinner/dist/jquery-spinner.min.css">
    <script type="text/javascript" src="../../bower_components/circle-indicator-spinner/dist/jquery-spinner.min.js"></script>
    <style>
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        .bulat {
            border-radius: 50%;
        }

        .border-dashed {
            border: 3px dashed #083255;
        }

        .border-dashed-tujuan {
            border: 3px dashed #FF0007;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        body {
            font-family: Calibri, "sans-serif", "Courier New";
            font-style: normal;
        }
    </style>

    <link rel="icon" type="image/png" href="../../dist/img/ITTI_Logo index.ico">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
        }
        .table-container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .highlight {
            font-weight: bold;
        }

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
            background-color: #337AB7;
            color: white;
        }
    </style>
</head>

<body>
    <div class="table-container">
        <div class="card">
            <table>
                <tbody>
                    <?php
                        $no_mesin         = isset($_GET['kode']) ? $_GET['kode'] : '';
                        $tanggal_kegiatan = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
                        $workordercode    = isset($_GET['workordercode']) ? $_GET['workordercode'] : '';

                        ini_set("error_reporting", 0);
                        $query_breakdown = "SELECT
                                                p.CODE AS BREAKDOWNENTRYCODE,
                                                p3.CODE AS WORKORDERCODE,
                                                p.PMBOMCODE AS NO_MESIN,
                                                p2.SHORTDESCRIPTION AS MESIN,
                                                p2.GENERICDATA2 AS TYPE,
                                                d.SHORTDESCRIPTION AS DOCUMENT,
                                                p.IDENTIFIEDDATE AS TANGGAL,
                                                p3.REMARKS AS KEGIATAN
                                            FROM
                                                PMBREAKDOWNENTRY p
                                            LEFT JOIN PMBOM p2 ON p2.CODE = p.PMBOMCODE
                                            LEFT JOIN DEPARTMENT d ON d.CODE = p2.DEPARTMENTCODE
                                            LEFT JOIN PMWORKORDER p3 ON p3.PMBREAKDOWNENTRYCODE = p.CODE
                                            WHERE
                                                p3.CODE = '$workordercode'
                                            AND p.PMBOMCODE = '$no_mesin'
                                            AND p.IDENTIFIEDDATE = '$tanggal_kegiatan'
                                            AND p.COUNTERCODE = 'PBD007'
                                            ORDER BY
                                                p.IDENTIFIEDDATE ASC";
                        $q_breakdown_header   = db2_exec($conn1, $query_breakdown);
                        $row_breakdown_header = db2_fetch_assoc($q_breakdown_header);
                    ?>
                    <tr>
                        <td colspan="2" class="highlight">Mesin:</td>
                        <td colspan="2"><?php echo $row_breakdown_header['MESIN'] ?></td>
                        <td class="highlight">Tanggal:</td>
                        <td colspan="2">
                            <?php echo date('Y-m-d', strtotime($_GET['tanggal'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="highlight">No. Mesin:</td>
                        <td colspan="2"><?php echo $row_breakdown_header['NO_MESIN'] ?></td>
                        <td class="highlight">Kegiatan:</td>
                        <td colspan="2"><?php echo $row_breakdown_header['KEGIATAN'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <center><h4><strong>List Activity</strong></h4></center>
            </div>
            <div class="card-body">
                <table width="100%" class="table table-bordered table-hover display" id="dataku" style="border: 1px solid #595959; padding:5px;">
                    <thead class="btn-primary">
                        <tr>
                            <th width="5%" style="text-align: center;">No</th>
                            <th width="15%" style="text-align: center;">No. BD</th>
                            <th width="10%" style="text-align: center;">Start Date</th>
                            <th width="10%" style="text-align: center;">End Date</th>
                            <th width="50%" style="text-align: center;">Remarks</th>
                            <th width="10%" style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $breakdownentrycode = $row_breakdown_header['BREAKDOWNENTRYCODE'];
                            $q_mesinLAB         = db2_exec($conn1, "SELECT * FROM PMWORKORDERDETAIL WHERE PMWORKORDERCODE='$workordercode'");
                            $no                 = 1;
                            while ($value = db2_fetch_assoc($q_mesinLAB)) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td class="text-center"><?php echo $breakdownentrycode ?></td>
                                <td class="text-center"><?php echo date('Y-m-d H:i:s', strtotime($value['STARTDATE'])); ?></td>
                                <td class="text-center"><?php echo date('Y-m-d H:i:s', strtotime($value['ENDDATE'])); ?></td>
                                <td class="text-center"><?php echo $value['REMARKS'] ?></td>
                                <td class="text-center">
                                    <?php
                                        $statusMap = [
                                                0 => "Open",
                                                1 => "Assigned",
                                                2 => "In Progress",
                                                3 => "Closed",
                                                4 => "Suspended",
                                                5 => "Canceled",
                                            ];

                                            echo isset($statusMap[$value['STATUS']]) ? $statusMap[$value['STATUS']] : "Unknown";
                                        ?>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>

<!-- REQUIRED JS SCRIPTS -->
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../bower_components/DataTable/datatables.min.js"></script>
<script src="../../bower_components/DataTable/dataTables.rowsGroup.js"></script>
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="../../bower_components/select2/js/select2.full.min.js"></script>
<script src="../../bower_components/toastr/toastr.js"></script>
<script type="text/javascript" src="../../bower_components/xeditable/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="../../plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="../../bower_components/jquery_validation/jquery.validate.min.js"></script>
<script>
    //Initialize Select2 Elements
    $('.select2').select2();
    $('.select3').select2();
    $('.select').select2();
    $("select2").on("select3:select2", function(evt) {
        var element = evt.params.data.element;
        var $element = $(element);

        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
    });
    //Date picker
    $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        }),
        //Date picker
        $('#datepicker1').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        }),
        //Date picker
        $('#datepicker2').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        }),
        //Date picker
        $('#datepicker3').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        })
    $('.form-control.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    })
</script>
<script>
    $(document).ready(function () {
        $('#dataku').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [ 10, 25, 50, 100],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>
