<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .blink-warning {
        /* animation: blink 1s infinite; */
        color: red;
        font-weight: bold;
    }

    /* @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    } */
    .table-bordered>thead>tr>th {
        border-bottom-width: 0 !important;
    }
     .table-responsive thead tr:nth-child(1) th {
        position: sticky;
        top: 0;
        z-index: 3;
        background-color: #f8f9fa;
    }

    .table-responsive thead tr:nth-child(2) th {
        position: sticky;
        top: 33px;
        z-index: 2;
        background-color: #f8f9fa;
    }
    .table>tbody>tr>td {
        padding: 5px;
    }
    th.sticky-col,
    td.sticky-col {
        position: sticky;
        left: -1px;
        background-color: white;
        z-index: 2;
        box-shadow: inset -1px 0 #ccc;
    }
    thead tr:nth-child(2) th.sticky-col {
        z-index: 4;
    }
</style>
<style>
    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .slide-up {
        animation: slideUp 0.5s ease-out;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <h4 id="cottonHeader" class="text-center" style="margin: -20px 0;"><strong>DYEING</strong></h4>
        <div style="margin-bottom: 10px;">
            <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="width: 250px;" autofocus>
        </div>
        <div class="box">
            <div id="schedule_table"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- <script>
    let blockedResepMap = {};

    function loadScheduleTable() {
        $.ajax({
            url: 'pages/ajax/generate_dyeing.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const { data, maxPerMachine: initialMax, tempListMap, oldDataList } = response;

                const priorityOrder = [
                    'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A10', 'A11',
                    'C1', 'D1',
                    'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8'
                ];

                // Prioritaskan mesin sesuai urutan, sisanya tambah di akhir
                const machineKeysRaw = Object.keys(data);
                const machineKeys = priorityOrder.filter(m => machineKeysRaw.includes(m));
                machineKeysRaw.forEach(m => { if (!machineKeys.includes(m)) machineKeys.push(m); });

                let maxPerMachine = initialMax;

                // Bikin map old data per mesin
                const oldMachineMap = {};
                oldDataList.forEach(item => {
                    const machine = item.no_machine;
                    if (!oldMachineMap[machine]) oldMachineMap[machine] = [];
                    oldMachineMap[machine].push(item);
                });

                console.log('Data utama (per mesin):', data);
                console.log('Old data per mesin:', oldMachineMap);

                // Pindahkan semua old data ke data utama jika data utama mesin kosong
                for (const [machine, oldItems] of Object.entries(oldMachineMap)) {
                    const hasDataInMain = data[machine] && data[machine].length > 0;
                    console.log(`Mesin ${machine} - data utama kosong?`, !hasDataInMain, 'Jumlah old data:', oldItems.length);

                    if (!hasDataInMain) {
                        if (!data[machine]) data[machine] = [];

                        const updatePromises = [];

                        oldItems.forEach(entry => {
                            const alreadyExists = data[machine].some(item => item.no_resep === entry.no_resep);
                            if (!alreadyExists) {
                                data[machine].push({
                                    no_resep: entry.no_resep,
                                    status: entry.status,
                                    group: null,
                                    product_name: null,
                                    dyeing_start: null,
                                    waktu: null,
                                    justMoved: true
                                });

                                const promise = $.ajax({
                                    url: 'pages/ajax/update_is_old_data.php',
                                    method: 'POST',
                                    data: { no_resep: entry.no_resep }
                                });

                                updatePromises.push(promise);
                            }
                        });

                        // ⏳ Tunggu semua update selesai sebelum lanjut
                        $.when(...updatePromises).done(function () {
                            console.log("✅ Semua update is_old_data selesai");
                            renderTables();
                        });


                        if (data[machine].length > maxPerMachine) {
                            maxPerMachine = data[machine].length;
                        }

                        delete oldMachineMap[machine]; // sudah pindah semua old data mesin ini
                    }
                }

                // 🛡️ Refresh blockedResepMap setiap load
                blockedResepMap = {};
                for (const [machine, list] of Object.entries(oldMachineMap)) {
                    list.forEach(item => {
                        blockedResepMap[item.no_resep] = true;
                    });
                }

                // Mulai render tabel utama
                let html = `
                    <div class="table-responsive" style="max-height: 750px; overflow: auto;">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <colgroup><col style="min-width: 50px;">`;
                machineKeys.forEach(() => html += `<col style="min-width: 300px;">`);
                html += `</colgroup>
                            <thead class="table-dark">
                                <tr><th class="sticky-col"></th>`;
                machineKeys.forEach(m => html += `<th>Mesin ${m}</th>`);
                html += `</tr><tr><th class="sticky-col">No.</th>`;
                machineKeys.forEach(m => {
                    const tempList = Array.isArray(tempListMap[m]) && tempListMap[m].length ? tempListMap[m].join(' ; ') : '-';
                    html += `<th><small class="text-danger">${tempList}</small></th>`;
                });
                html += `</tr></thead><tbody>`;

                for (let i = 0; i < maxPerMachine; i++) {
                    html += `<tr><td class="sticky-col">${i + 1}</td>`;
                    machineKeys.forEach(machine => {
                        const cell = data[machine]?.[i];
                        if (cell) {
                            const now = new Date();
                            let warningClass = '';
                            if (cell.dyeing_start) {
                                const start = new Date(cell.dyeing_start);
                                const diffMin = (now - start) / 60000;
                                const proc = parseFloat(cell.waktu) || 0;
                                if (diffMin > (120 + proc)) warningClass = 'blink-warning';
                            }
                            const moveClass = cell.justMoved ? 'slide-up' : '';
                            html += `<td class="${warningClass} ${moveClass}">
                                        <div style="display: flex; justify-content: space-around; white-space: nowrap;">
                                            <span>${cell.no_resep}</span>
                                            <span class="text-muted">${cell.status}</span>
                                        </div>
                                    </td>`;
                        } else {
                            html += `<td></td>`;
                        }
                    });
                    html += `</tr>`;
                }

                html += `</tbody></table></div>`;
                $('#schedule_table').html(html);

                // Setelah render utama
                for (const machine in data) {
                    data[machine].forEach(item => {
                        if (item?.justMoved) delete item.justMoved;
                    });
                }

                // Render tabel old data yang belum pindah
                const remainingOldMachines = Object.keys(oldMachineMap).filter(m => oldMachineMap[m].length > 0);
                if (remainingOldMachines.length > 0) {
                    const oldMax = Math.max(...remainingOldMachines.map(m => oldMachineMap[m].length));
                    let htmlOld = `<div class="card mt-4"><div class="card-body">
                        <h5 class="text-center text-muted">Next Cycle</h5>
                        <div class="table-responsive" style="max-height: 500px; overflow: auto;">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <colgroup><col style="min-width: 50px;">`;
                    remainingOldMachines.forEach(() => htmlOld += `<col style="min-width: 300px;">`);
                    htmlOld += `</colgroup>
                            <thead class="table-dark">
                                <tr><th rowspan="2">No.</th>`;
                    remainingOldMachines.forEach(m => htmlOld += `<th>Mesin ${m}</th>`);
                    htmlOld += `</tr><tr>`;
                    remainingOldMachines.forEach(m => {
                        const tempList = Array.isArray(tempListMap[m]) && tempListMap[m].length ? tempListMap[m].join(' ; ') : '-';
                        htmlOld += `<th><small class="text-danger">${tempList}</small></th>`;
                    });
                    htmlOld += `</tr></thead><tbody>`;

                    for (let i = 0; i < oldMax; i++) {
                        htmlOld += `<tr><td>${i + 1}</td>`;
                        remainingOldMachines.forEach(m => {
                            const item = oldMachineMap[m][i];
                            if (item) {
                                htmlOld += `<td>
                                    <div style="display: flex; justify-content: space-around; white-space: nowrap;">
                                        <span>${item.no_resep}</span>
                                        <span class="text-muted">${item.status}</span>
                                    </div>
                                </td>`;
                            } else {
                                htmlOld += `<td></td>`;
                            }
                        });
                        htmlOld += `</tr>`;
                    }

                    htmlOld += `</tbody></table></div></div></div>`;
                    $('#schedule_table').append(htmlOld);
                }
            },
            error: function (xhr, status, error) {
                console.error("Failed to fetch data:", error);
                $('#schedule_table').html('<div class="alert alert-danger">Gagal memuat data schedule.</div>');
            }
        });
    }

</script> -->

<script>
    let blockedResepMap = {};

    function loadScheduleTable() {
        const scrollLeft = $('#tableContainer').scrollLeft();
        const scrollLeftNext = $('#tableContainerNext').scrollLeft();
        
        $.ajax({
            url: 'pages/ajax/generate_dyeing.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const { data, maxPerMachine: initialMax, tempListMap, oldDataList } = response;
                const priorityOrder = [
                    'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A10', 'A11',
                    'C1', 'D1',
                    'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8'
                ];

                const machineKeysRaw = Object.keys(data);
                const machineKeys = priorityOrder.filter(m => machineKeysRaw.includes(m));
                machineKeysRaw.forEach(m => { if (!machineKeys.includes(m)) machineKeys.push(m); });

                let maxPerMachine = initialMax;

                const oldMachineMap = {};
                oldDataList.forEach(item => {
                    const machine = item.no_machine;
                    if (!oldMachineMap[machine]) oldMachineMap[machine] = [];
                    oldMachineMap[machine].push(item);
                });

                console.log('Data utama (per mesin):', data);
                console.log('Old data per mesin:', oldMachineMap);

                for (const [machine, oldItems] of Object.entries(oldMachineMap)) {
                    const hasDataInMain = data[machine] && data[machine].length > 0;

                    if (!hasDataInMain) {
                        if (!data[machine]) data[machine] = [];

                        oldItems.forEach(entry => {
                            const alreadyExists = data[machine].some(item => item.no_resep === entry.no_resep);
                            if (!alreadyExists) {
                                data[machine].push({
                                    no_resep: entry.no_resep,
                                    status: entry.status,
                                    group: null,
                                    product_name: null,
                                    dyeing_start: null,
                                    waktu: null,
                                    justMoved: true
                                });
                            }
                        });

                        if (data[machine].length > maxPerMachine) {
                            maxPerMachine = data[machine].length;
                        }

                        delete oldMachineMap[machine];
                    }
                }

                blockedResepMap = {};
                for (const [machine, list] of Object.entries(oldMachineMap)) {
                    list.forEach(item => {
                        blockedResepMap[item.no_resep] = true;
                    });
                }

                let html = `<div id="tableContainer" class="table-responsive" {!--style="max-height: 750px; overflow: auto;"--}>
                        <table class="table table-bordered table-striped align-middle text-center">
                        <colgroup><col style="min-width: 50px;">`;
                machineKeys.forEach(() => html += `<col style="min-width: 300px;">`);
                html += `</colgroup><thead class="table-dark"><tr><th class="sticky-col"></th>`;
                machineKeys.forEach(m => html += `<th>Mesin ${m}</th>`);
                html += `</tr><tr><th class="sticky-col">No.</th>`;
                machineKeys.forEach(m => {
                    const tempList = Array.isArray(tempListMap[m]) && tempListMap[m].length ? tempListMap[m].join(' ; ') : '-';
                    html += `<th><small class="text-danger">${tempList}</small></th>`;
                });
                html += `</tr></thead><tbody>`;

                for (let i = 0; i < maxPerMachine; i++) {
                    html += `<tr><td class="sticky-col">${i + 1}</td>`;
                    machineKeys.forEach(machine => {
                        const cell = data[machine]?.[i];
                        if (cell) {
                            const now = new Date();
                            let warningClass = '';
                            let shouldStop = false;

                            if (cell.dyeing_start) {
                                const start = new Date(cell.dyeing_start);
                                const diffMin = (now - start) / 60000;
                                const proc = parseFloat(cell.waktu) || 0;

                                if (diffMin >= 1) {
                                    warningClass = 'blink-warning';
                                    shouldStop = true;
                                }
                            }

                            const moveClass = cell.justMoved ? 'slide-up' : '';

                            html += `<td class="${warningClass} ${moveClass}">
                                        <div style="display: flex; justify-content: space-around; white-space: nowrap;">
                                            <span>${cell.no_resep}</span>
                                            <span class="text-muted">${cell.status}</span>
                                        </div>
                                    </td>`;

                            if (shouldStop) {
                                // Kirim permintaan update status ke stop_dyeing
                                $.ajax({
                                    url: 'pages/ajax/scan_dyeing_update_status.php',
                                    method: 'POST',
                                    data: {
                                        no_resep: cell.no_resep,
                                        force_stop: true
                                    },
                                    success: function (response) {
                                        console.log("✅ Resep dihentikan otomatis:", cell.no_resep);
                                    },
                                    error: function (xhr) {
                                        console.error("❌ Gagal update stop_dyeing:", cell.no_resep, xhr.responseText);
                                    }
                                });
                            }

                        } else {
                            html += `<td></td>`;
                        }
                    });
                    html += `</tr>`;
                }

                html += `</tbody></table></div>`;
                $('#schedule_table').html(html);
                $('#tableContainer').scrollLeft(scrollLeft);

                // ✅ Gabungkan semua justMoved jadi satu array dan kirim sekaligus
                const movedReseps = [];
                for (const machine in data) {
                    data[machine].forEach(item => {
                        if (item?.justMoved) {
                            movedReseps.push(item.no_resep);
                            delete item.justMoved;
                        }
                    });
                }

                if (movedReseps.length > 0) {
                    $.ajax({
                        url: 'pages/ajax/update_is_old_data.php',
                        method: 'POST',
                        data: { resepList: JSON.stringify(movedReseps) },
                        success: function (res) {
                            console.log("✅ Bulk is_old_data updated:", res);
                        },
                        error: function (xhr) {
                            console.error("❌ Failed bulk update:", xhr.responseText);
                        }
                    });
                }

                const remainingOldMachines = Object.keys(oldMachineMap).filter(m => oldMachineMap[m].length > 0);
                if (remainingOldMachines.length > 0) {
                    const oldMax = Math.max(...remainingOldMachines.map(m => oldMachineMap[m].length));
                    let htmlOld = `<div class="card mt-4"><div class="card-body">
                        <h5 class="text-center text-muted">Next Cycle</h5>
                        <div id="tableContainerNext" class="table-responsive" {!--style="max-height: 750px; overflow: auto;"--}>
                        <table class="table table-bordered table-striped align-middle text-center">
                            <colgroup><col style="min-width: 50px;">`;
                    remainingOldMachines.forEach(() => htmlOld += `<col style="min-width: 300px;">`);
                    htmlOld += `</colgroup><thead class="table-dark"><tr><th rowspan="2" class="sticky-col">No.</th>`;
                    remainingOldMachines.forEach(m => htmlOld += `<th>Mesin ${m}</th>`);
                    htmlOld += `</tr><tr>`;
                    remainingOldMachines.forEach(m => {
                        const tempList = Array.isArray(tempListMap[m]) && tempListMap[m].length ? tempListMap[m].join(' ; ') : '-';
                        htmlOld += `<th><small class="text-danger">${tempList}</small></th>`;
                    });
                    htmlOld += `</tr></thead><tbody>`;

                    for (let i = 0; i < oldMax; i++) {
                        htmlOld += `<tr><td class="sticky-col">${i + 1}</td>`;
                        remainingOldMachines.forEach(m => {
                            const item = oldMachineMap[m][i];
                            if (item) {
                                htmlOld += `<td><div style="display: flex; justify-content: space-around; white-space: nowrap;">
                                                <span>${item.no_resep}</span>
                                                <span class="text-muted">${item.status}</span>
                                            </div></td>`;
                            } else {
                                htmlOld += `<td></td>`;
                            }
                        });
                        htmlOld += `</tr>`;
                    }

                    htmlOld += `</tbody></table></div></div></div>`;
                    $('#schedule_table').append(htmlOld);
                    $('#tableContainerNext').scrollLeft(scrollLeftNext);
                }
            },
            error: function (xhr, status, error) {
                console.error("Failed to fetch data:", error);
                $('#schedule_table').html('<div class="alert alert-danger">Gagal memuat data schedule.</div>');
            }
        });
    }

    $(document).ready(function () {
        loadScheduleTable();
        setInterval(loadScheduleTable, 15000);

        $('#scanInput').on('keypress', function (e) {
            if (e.which === 13) {
                const noResep = $(this).val().trim();
                if (noResep === "") return;

                if (blockedResepMap[noResep]) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Bisa Diproses',
                        text: `No. Resep ${noResep} masih dalam Next Cycle dan belum boleh discan.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $(this).val("");
                    return;
                }

                updateStatus(noResep);
                $(this).val("");
            }
        });
    });

    function updateStatus(noResep) {
        $.ajax({
            url: 'pages/ajax/scan_dyeing_update_status.php',
            method: 'POST',
            data: { no_resep: noResep },
            success: function (response) {
                console.log("Update sukses:", response);
                loadScheduleTable();
                Swal.fire({
                    icon: 'success',
                    title: 'Status Diperbarui!',
                    text: `No. Resep ${noResep} telah diproses.`,
                    timer: 1200,
                    showConfirmButton: false
                });
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memperbarui status.',
                });
            }
        });
    }
</script>