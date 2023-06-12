<style>
    th {
        font-size: 10pt;
    }

    td {
        font-size: 10pt;
    }

    /* #Table-join td,
    #Table-join th {
        border: 0.1px solid #a1a1a1;
    }

    #Table-join th {
        color: white;
        background: #2b8ee0;
    }

    #Table-join tr:hover {
        background-color: rgb(151, 170, 212);
    } */

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
            <table id="Table-join" class="table table-sm table-bordered table-sm display compact" style="width: 100%;">
                <thead>
                    <tr class="bg-success">
                        <th>#</th>
                        <th>Rcode</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- You know ? i do some magic here -->
                </tbody>
            </table>
        </div>
        <div class="col-sm-9" style="margin-top: 15px; margin-left:-40px;" id="lokasi_table">
            <table id="Log-detail" class="table table-sm table-bordered display compact" style="width: 100%;">
                <thead>
                    <tr class="bg-danger">
                        <th>#</th>
                        <th>Status</th>
                        <th>Info</th>
                        <th>User do</th>
                        <th>Date Time</th>
                        <th>ip address</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>

</script>