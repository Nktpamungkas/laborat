<style>
    .stage-box {
        background: #f9f9f9;
        padding: 10px 15px;
        margin-bottom: 5px;
        border-left: 5px solid #337ab7;
        border-radius: 4px;
        animation: fadeIn 0.5s ease-in;
    }
    .stage-label {
        font-weight: bold;
    }
    .label-status {
        margin-left: 10px;
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 3px;
    }
    .cycle-header {
        margin-top: 10px;
        font-weight: bold;
        color: #3c8dbc;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .pagination > li > a {
        color: #3c8dbc;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="row" style="margin-top: 10px;">
                <div class="col-sm-2">
                    <input type="text" id="filterResep" class="form-control" placeholder="Filter no_resep..." oninput="filterLogs()" />
                </div>
            </div>
            <br>

            <div class="box-body" id="logContainer">
                <!-- log will be loaded here -->
            </div>
            <div class="box-footer text-center">
                <ul class="pagination pagination-sm" id="pagination"></ul>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const itemsPerPage = 5;
    let allLogs = [];

    function loadLogs() {
        $.getJSON('pages/ajax/fetch_logs.php', function (data) {
            allLogs = data;
            renderPage(1);
            setupPagination(data.length);
        });
    }

    function renderPage(page) {
        const filter = $('#filterResep').val().toLowerCase();
        const filteredLogs = allLogs.filter(log => log.no_resep.toLowerCase().includes(filter));
        
        const start = (page - 1) * itemsPerPage;
        const logs = filteredLogs.slice(start, start + itemsPerPage);

        let grouped = {};
        logs.forEach(item => {
            if (!grouped[item.no_resep]) grouped[item.no_resep] = [];
            grouped[item.no_resep].push(item);
        });

        let html = '';
        for (let resep in grouped) {
            html += `<h4><span class="glyphicon glyphicon-list-alt"></span> <strong>No Resep: ${resep}</strong></h4>`;
            const byCycle = groupByCycle(grouped[resep]);

            for (let c in byCycle) {
                html += `<div class="cycle-header">➤ Siklus ${c}</div>`;
                byCycle[c].forEach(log => {
                    html += `
                        <div class="stage-box">
                            <span class="stage-label">Stage ${log.stage}:</span>
                            <span class="label label-status label-${statusColor(log.status)}">${log.status}</span>
                            <br><small><span class="glyphicon glyphicon-time"></span> ${log.waktu}</small>
                        </div>`;
                });
            }
        }

        $('#logContainer').html(html);
        setupPagination(filteredLogs.length);
    }

    function filterLogs() {
        renderPage(1);
    }

    function setupPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        let pagHtml = '';
        for (let i = 1; i <= totalPages; i++) {
            pagHtml += `<li><a href="#" onclick="renderPage(${i}); return false;">${i}</a></li>`;
        }
        $('#pagination').html(pagHtml);
    }

    function groupByCycle(logs) {
        const grouped = {};
        logs.forEach(log => {
            if (!grouped[log.cycle]) grouped[log.cycle] = [];
            grouped[log.cycle].push(log);
        });
        return grouped;
    }

    function statusColor(status) {
        switch (status) {
            case 'scheduled': return 'info';
            case 'in_order_dispensing': return 'primary';
            case 'in_order_dyeing': return 'warning';
            case 'in_order_darkroom': return 'danger';
            case 'repeat': return 'default';
            case 'end': return 'success';
            default: return 'default';
        }
    }

    $(document).ready(function () {
        loadLogs();
        setInterval(loadLogs, 5000);
    });
</script>