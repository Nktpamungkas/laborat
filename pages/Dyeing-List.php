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
        <h4 id="cottonHeader" class="text-center"><strong>DYEING</strong></h4>
        <div style="margin-bottom: 10px;">
            <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="width: 250px;" autofocus>
        </div>
        <div class="box">
            <div id="schedule_table"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // function loadScheduleTable() {
    //     $.ajax({
    //         url: 'pages/ajax/generate_dyeing.php',
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(response) {
    //             const { data, maxPerMachine, tempListMap } = response;
    //             // const machineKeys = Object.keys(data);
    //             const machineKeysRaw = Object.keys(data);
    //             // Prioritas mesin yang diinginkan
    //             const priorityOrder = [
    //                 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A10', 'A11',
    //                 'C1', 'D1',
    //                 'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8'
    //             ];

    //             // Urutkan key sesuai urutan prioritas
    //             const machineKeys = priorityOrder.filter(machine => machineKeysRaw.includes(machine));

    //             // Tambahkan mesin yang tidak ada di prioritas (jika ada), tetap ditampilkan di belakang
    //             machineKeysRaw.forEach(machine => {
    //                 if (!machineKeys.includes(machine)) {
    //                     machineKeys.push(machine);
    //                 }
    //             });

    //             const machineCount = machineKeys.length;

    //             // (1) Tidak perlu hitung min-width yg terlalu besar.
    //             //    Kita hanya membiarkan .table-responsive yang mengatur scroll.
    //             let html = `
    //                 <div class="table-responsive" style="max-height: 750px; overflow: auto;">
    //                     <table class="table table-bordered table-striped align-middle text-center"
    //                         style="table-layout: auto; width: 100%;">
    //                         <colgroup>
    //                             <col style="min-width: 50px;"> <!-- Kolom “No.” cukup kecil saja -->
    //             `;

    //             machineKeys.forEach(() => {
    //                 html += `<col style="min-width: 300px;">`;
    //             });

    //             html += `
    //                         </colgroup>
    //                         <thead class="table-dark">
    //                             <tr>
    //                                 <th rowspan="2">No.</th>
    //             `;

    //             // Judul Mesin
    //             machineKeys.forEach(machine => {
    //                 html += `<th>Mesin ${machine}</th>`;
    //             });
    //             html += `</tr><tr>`;

    //             // Baris Temp List
    //             machineKeys.forEach(machine => {
    //                 const tempList = Array.isArray(tempListMap[machine]) && tempListMap[machine].length
    //                             ? tempListMap[machine].join(' ; ')
    //                             : '-';
    //                 html += `<th><small class="text-danger">${tempList}</small></th>`;
    //             });
    //             html += `</tr>
    //                         </thead>
    //                         <tbody>
    //             `;

    //             // Baris Data (maxPerMachine baris)
    //             for (let i = 0; i < maxPerMachine; i++) {
    //                 html += `<tr><td>${i + 1}</td>`;
    //                 machineKeys.forEach(machine => {
    //                     const rowsForMachine = data[machine];
    //                     const cell = rowsForMachine[i];

    //                     if (cell) {
    //                         const now = new Date();
    //                         let warningClass = '';
    //                         if (cell.dyeing_start) {
    //                             const startTime = new Date(cell.dyeing_start);
    //                             const diffMs = now - startTime;
    //                             const diffMins = diffMs / 1000 / 60;
    //                             const processTime = parseFloat(cell.waktu) || 0;

    //                             if (diffMins > (120 + processTime)) {
    //                                 warningClass = 'blink-warning';
    //                             }
    //                         }

    //                         html += `
    //                             <td class="${warningClass}">
    //                                 <div style="display: flex; justify-content: space-around; white-space: nowrap;">
    //                                     <span>${cell.no_resep}</span>
    //                                     <span class="text-muted">${cell.status}</span>
    //                                 </div>
    //                             </td>
    //                         `;
    //                     } else {
    //                         html += `<td></td>`;
    //                     }
    //                 });
    //                 html += `</tr>`;
    //             }

    //             html += `
    //                         </tbody>
    //                     </table>
    //                 </div>
    //             `;

    //             $('#schedule_table').html(html);
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Failed to fetch data:", error);
    //             $('#schedule_table').html('<div class="alert alert-danger">Gagal memuat data schedule.</div>');
    //         }
    //     });
    // }

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

                        oldItems.forEach(entry => {
                            data[machine].push({
                                no_resep: entry.no_resep,
                                status: entry.status,
                                group: null,
                                product_name: null,
                                dyeing_start: null,
                                waktu: null,
                                justMoved: true
                            });
                        });

                        if (data[machine].length > maxPerMachine) {
                            maxPerMachine = data[machine].length;
                        }

                        delete oldMachineMap[machine]; // sudah pindah semua old data mesin ini
                    }
                }

                // Mulai render tabel utama
                let html = `
                    <div class="table-responsive" style="max-height: 750px; overflow: auto;">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <colgroup><col style="min-width: 50px;">`;
                machineKeys.forEach(() => html += `<col style="min-width: 300px;">`);
                html += `</colgroup>
                            <thead class="table-dark">
                                <tr><th rowspan="2">No.</th>`;
                machineKeys.forEach(m => html += `<th>Mesin ${m}</th>`);
                html += `</tr><tr>`;
                machineKeys.forEach(m => {
                    const tempList = Array.isArray(tempListMap[m]) && tempListMap[m].length ? tempListMap[m].join(' ; ') : '-';
                    html += `<th><small class="text-danger">${tempList}</small></th>`;
                });
                html += `</tr></thead><tbody>`;

                for (let i = 0; i < maxPerMachine; i++) {
                    html += `<tr><td>${i + 1}</td>`;
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

</script>

<script>
    $(document).ready(function () {
        loadScheduleTable(); // 🚀 Load awal

        $('#scanInput').on('keypress', function (e) {
            if (e.which === 13) { // Enter key
                const noResep = $(this).val().trim();
                if (noResep !== "") {
                    updateStatus(noResep);
                    $(this).val("");
                }
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