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

    #epcTable td:last-child {
        text-align: center;
        width: 1rem; /* fix width kolom action */
        padding: 0.5rem;
    }

    #epcTable td, #epcTable th {
        text-align: center;
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

        <!-- RFID Trigerred Modal -->
            <div class="modal fade modal-super-scaled" id="modalRFID" data-backdrop="static" data-keyboard="true" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" style="width:55%">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">List of data scanned by RFID</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-scrollable" style="border: none;">
                                <table id="epcTable" class="table table-bordered" style="width:100%; padding: 1rem">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Resep</th>
                                            <th>Temp</th>
                                            <th>No Mesin</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="submitBtnRFID" class="btn btn-out btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- RFID Trigerred Modal  -->
    </div>
</div>

<?php require './includes/socket_helper.php' ?>
<script>
    let dyeingData = []
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

                dyeingData = Object.entries(data) // [ [key, value], ... ]
                    .flatMap(([groupKey, arr]) =>
                        (arr || [])
                        .filter(item => item !== null)
                        .map(item => ({
                            ...item,
                            no_machine: groupKey // tambahin nama group ke dalam object
                        }))
                );

                dyeingData = [...dyeingData, ...oldDataList]

                const priorityOrder = [
                    'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A10', 'A11', 'A12',
                    'C1', 'C2 (DYE)', 'C3 (DYE)', 'D1',
                    'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8'
                ];

                // Start websocket to room 2
                subscribe(3)

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
        // MODULE RFID
            let filteredDyeingData = [] // For submit payload
            let deletedDRData = [] // For tag deleted no_resep with DR
            
            epcTable = $('#epcTable').DataTable({
                paging: true,
                searching: true,
                info: true,
                columns: [
                    { title: "No" },
                    { title: "No Resep" },
                    { title: "Temp" },
                    { title: "No Mesin" },
                    { title: "Status" },
                    { title: "Action", orderable: false } // kolom tombol
                ]
            });

            // Listen for registers (when items add or increase)
            socket.on('register', ({
                roomId,
                epc,
                tags
            }) => globalProcessOnListenSocket({
                roomId,
                tags,
                epcTable,
                filteredData: filteredDyeingData,
                globalData: dyeingData,
                status: "in_progress_dispensing",
                columns: [
                    (row, index) => index, // nomor urut
                    (row) => row.no_resep?.trim(),
                    "product_name",
                    "no_machine",
                    "status",
                    (row) => `<button class="btn btn-danger btn-sm remove-row" data-epc="${row.no_resep?.trim()}">x</button>`
                ]
            }));

            // Listen for dispatch (when items removed or decrease)
            socket.on('dispatch', ({
                roomId,
                epc,
                tags
            }) => globalProcessOnListenSocket({
                roomId,
                tags,
                epcTable,
                filteredData: filteredDyeingData,
                globalData: dyeingData,
                status: "in_progress_dispensing",
                columns: [
                    (row, index) => index, // nomor urut
                    (row) => row.no_resep?.trim(),
                    "product_name",
                    "no_machine",
                    "status",
                    (row) => `<button class="btn btn-danger btn-sm remove-row" data-epc="${row.no_resep?.trim()}">x</button>`
                ]
            }));

            // Listen success_subscribe (when iddle)
            socket.on('success_subscribe', ({
                roomId,
                epc,
                tags
            }) => globalProcessOnListenSocketForIddle({
                roomId,
                tags,
                epcTable,
                deletedDRData,
                filteredData: filteredDyeingData,
                globalData: dyeingData,
                status: "in_progress_dispensing",
                columns: [
                    (row, index) => index, // nomor urut
                    (row) => row.no_resep?.trim(),
                    "product_name",
                    "no_machine",
                    "status",
                    (row) => `<button class="btn btn-danger btn-sm remove-row" data-epc="${row.no_resep?.trim()}">x</button>`
                ]
            }));

            // Event handler tombol remove
            $('#epcTable').on('click', '.remove-row', function () {
                const row = $(this).closest('tr');
                const noResep = $(this).data('epc');  // ambil data-epc dari tombol

                // Hapus dari DataTables
                epcTable.row(row).remove().draw(false);

                if (noResep.startsWith("DR") && noResep.length > 2) {
                     // ✅ Masukkan string no_resep ke deletedDRData kalau belum ada
                    if (!deletedDRData.includes(noResep)) {
                        deletedDRData.push(noResep);
                    }
                }

                // Hapus juga dari filteredDispensingData
                filteredDyeingData = filteredDyeingData.filter(item => item.no_resep.trim() !== noResep);
            });

            $('#submitBtnRFID').on('click', function () {
                const noResepList = filteredDyeingData.map((item) => item.no_resep.trim())
                updateStatusBatch(noResepList)
            });

            function updateStatusBatch(noResepList) {
                if (!Array.isArray(noResepList) || noResepList.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Ditemukan',
                        text: 'Tidak ada No. Resep yang dikirim.',
                    });
                    return;
                }

                let total = noResepList.length;
                let successCount = 0;
                let failCount = 0;
                let failedItems = [];

                // Disable tombol & tampilkan loader swal
                $('#submitBtnRFID').prop('disabled', true);
                Swal.fire({
                    title: 'Memproses...',
                    html: 'Mohon tunggu hingga semua data selesai diproses.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                // Promise chain biar tunggu semua ajax selesai
                let requests = noResepList.map(noResep => {
                    return $.ajax({
                        url: 'pages/ajax/scan_dyeing_update_status.php',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            no_resep: noResep,
                        },
                    }).then(res => {
                        if (res.success) {
                            successCount++;
                        } else {
                            failCount++;
                            failedItems.push(noResep + " (" + (res.message || res.error || "gagal") + ")");
                            if (/session/i.test(res.message)) {
                                window.location.href = "/laborat/login";
                            }
                        }
                    })
                    .catch(xhr => {
                        failCount++;
                        failedItems.push(noResep + " (AJAX error)");
                        console.error("AJAX error:", xhr.responseText);
                    });
                });

                Promise.all(requests).then(() => {
                    loadScheduleTable();

                    // Tutup swal loading
			        Swal.close();

                    if (failCount === 0) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Semua Berhasil!',
                            text: `${successCount} dari ${total} resep berhasil diproses.`,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else if (successCount === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Semua Gagal!',
                            html: `Tidak ada resep yang berhasil.<br><br><b>Detail:</b><br>${failedItems.join('<br>')}`,
                            width: 600
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sebagian Gagal!',
                            html: `${successCount} berhasil, ${failCount} gagal.<br><br><b>Detail gagal:</b><br>${failedItems.join('<br>')}`,
                            width: 600
                        });
                    }

                    popoutModal()
                })
                .finally(() => {
                    // Enable tombol lagi
                    $('#submitBtnRFID').prop('disabled', false);
                });
            }
        // MODULE RFID
        
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

                if (/session/i.test(response.message)) {
                    window.location.href = "/laborat/login";
                }
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