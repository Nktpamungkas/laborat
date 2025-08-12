<link rel="stylesheet" href="bower_components/animate/animate.min.css">

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

<script>
    let blockedResepMap = {};

    function loadScheduleTable(callback) {
        const scrollLeft = $('#tableContainer').scrollLeft();
        const scrollLeftNext = $('#tableContainerNext').scrollLeft();

        $.ajax({
            url: 'pages/ajax/generate_dyeing.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const { data, allMachines, maxPerMachine, tempListMap, tempListMapNext, oldDataList } = response;

                const priorityOrder = [
                    'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A10', 'A11', 'A12',
                    'C1', 'C2 (DYE)', 'C3 (DYE)', 'D1',
                    'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8'
                ];

                const machineKeys = priorityOrder.filter(m => allMachines.includes(m));
                allMachines.forEach(m => { if (!machineKeys.includes(m)) machineKeys.push(m); });

                const totalRows = 24;

                // --- TABEL UTAMA ---
                let html = `<div id="tableContainer" class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                    <colgroup><col style="min-width: 50px;">`;
                machineKeys.forEach(() => html += `<col style="min-width: 300px;">`);
                html += `</colgroup><thead class="table-dark"><tr><th class="sticky-col"></th>`;
                machineKeys.forEach(m => html += `<th>Mesin ${m}</th>`);
                html += `</tr><tr><th class="sticky-col">No.</th>`;
                machineKeys.forEach(m => {
                    const tempList = tempListMap[m]?.join(' ; ') || '-';
                    html += `<th><small class="text-danger">${tempList}</small></th>`;
                });
                html += `</tr></thead><tbody>`;

                for (let i = 0; i < totalRows; i++) {
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
                $('#tableContainer').scrollLeft(scrollLeft);

                // --- UPDATE justMoved status (bulk)
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
                    $.post('pages/ajax/update_is_old_data.php', { resepList: JSON.stringify(movedReseps) })
                        .done(res => {
                            console.log("✅ Bulk is_old_data updated:", res);
                            loadScheduleTable();
                        })
                        .fail(xhr => console.error("❌ Failed bulk update:", xhr.responseText));
                }

                // --- NEXT CYCLE ---
                const oldMachineMap = {};
                oldDataList.forEach(item => {
                    const machine = item.no_machine || 'UNASSIGNED';
                    if (!oldMachineMap[machine]) oldMachineMap[machine] = [];
                    oldMachineMap[machine].push(item);
                });

                // blockedResepMap = {};
                // for (const [machine, list] of Object.entries(oldMachineMap)) {
                //     list.forEach(item => {
                //         blockedResepMap[item.no_resep] = true;
                //     });
                // }

                let htmlOld = `<div class="card mt-4"><div class="card-body">
                    <h5 class="text-center text-muted">Next Cycle</h5>
                    <div id="tableContainerNext" class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <colgroup><col style="min-width: 50px;">`;
                machineKeys.forEach(() => htmlOld += `<col style="min-width: 300px;">`);
                htmlOld += `</colgroup><thead class="table-dark"><tr><th rowspan="2" class="sticky-col">No.</th>`;
                machineKeys.forEach(m => htmlOld += `<th>Mesin ${m}</th>`);
                htmlOld += `</tr><tr>`;
                machineKeys.forEach(m => {
                    const tempListNext = tempListMapNext[m]?.join(' ; ') || '-';
                    htmlOld += `<th><small class="text-danger">${tempListNext}</small></th>`;
                });
                htmlOld += `</tr></thead><tbody>`;

                for (let i = 0; i < totalRows; i++) {
                    htmlOld += `<tr><td class="sticky-col">${i + 1}</td>`;
                    machineKeys.forEach(m => {
                        const item = oldMachineMap[m]?.[i];
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

                if (callback && typeof callback === 'function') {
                    callback();
                }

                $('#tableContainer').on('scroll', function () {
                    $('#tableContainerNext').scrollLeft($(this).scrollLeft());
                });

                $('#tableContainerNext').on('scroll', function () {
                    $('#tableContainer').scrollLeft($(this).scrollLeft());
                });
            },
            error: function (xhr, status, error) {
                console.error("Failed to fetch data:", error);
                $('#schedule_table').html('<div class="alert alert-danger">Gagal memuat data schedule.</div>');
            }
        });
    }

    $(document).ready(function () {
        loadScheduleTable();
        setInterval(function () {
            const scrollTop = $(window).scrollTop();

            loadScheduleTable(function() {
                $(window).scrollTop(scrollTop);
            });
        }, 15000);

        $('#scanInput').on('keypress', function (e) {
            if (e.which === 13) {
                const noResep = $(this).val().trim();
                if (noResep === "") return;

                // if (blockedResepMap[noResep]) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Tidak Bisa Diproses',
                //         text: `No. Resep ${noResep} masih dalam Next Cycle dan belum boleh discan.`,
                //         timer: 2000,
                //         showConfirmButton: false
                //     });
                //     $(this).val("");
                //     return;
                // }

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