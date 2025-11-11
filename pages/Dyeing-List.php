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
    .search-hit {
        outline: 3px solid #28a745;
        animation: pulse 1s ease-in-out 5;
    }
    @keyframes pulse {
        0% { outline-color: #28a745; }
        50% { outline-color: #7bd89d; }
        100% { outline-color: #28a745; }
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <h4 id="cottonHeader" class="text-center" style="margin: -20px 0;"><strong>DYEING</strong></h4>
        <div class="clearfix" style="margin-bottom: 10px;">
            <div class="pull-left">
                <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="width: 250px;" autofocus>
            </div>
            <div class="pull-right form-inline">
                <label for="searchSuffix">Search : </label>
                <input type="text" id="searchSuffix" name="searchSuffix" placeholder="Search Suffix…" class="form-control" style="max-width: 250px;" autocomplete="off" aria-label="Cari No. Resep">
            </div>
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

        <!-- Search Results Modal -->
        <div class="modal fade" id="searchResultsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" style="width:60%">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Search Results</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                    <table id="searchResultsTable" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>No Resep</th>
                            <th>Mesin</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody><!-- filled by JS --></tbody>
                    </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        <!-- /Search Results Modal -->

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
                machineKeys.forEach(m => html += `<th data-machine="${m}">Mesin ${m}</th>`);
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
                            const isTest = cell.is_test == "1";
                            html += `<td class="${warningClass} ${moveClass}"
                                                    data-machine="${machine}"
                                                    data-row="${i}"
                                                    data-cycle="now"
                                                    data-no-resep="${cell.no_resep}">
                                        <div style="display:flex;justify-content:space-around;white-space:nowrap;">
                                            <span>${cell.no_resep}  ${isTest ? '<span class="label label-warning">TEST REPORT</span>' : ''}</span>
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
                $('.search-hit').removeClass('search-hit');
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
                machineKeys.forEach(m => htmlOld += `<th data-machine="${m}">Mesin ${m}</th>`);
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
                            const isTest = item.is_test == "1";
                            htmlOld += `<td
                                            data-machine="${m}"
                                            data-row="${i}"
                                            data-cycle="next"
                                            data-no-resep="${item.no_resep}">
                                            <div style="display:flex;justify-content:space-around;white-space:nowrap;">
                                                <span>${item.no_resep}  ${isTest ? '<span class="label label-warning">TEST REPORT</span>' : ''}</span>
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
                $('#tableContainerNext').scrollLeft(scrollLeftNext);

                buildIndexes({ data, oldDataList, machineKeys });

                const qCurrent = $('#searchSuffix').val();
                if (qCurrent) searchBySuffix(qCurrent, { showModal: false, notifyWhenEmpty: false });

                if (callback && typeof callback === 'function') {
                    callback();
                }              

                $('#tableContainer').on('scroll', function () {
                    $('#tableContainerNext').scrollLeft($(this).scrollLeft());
                });

                $('#tableContainerNext').on('scroll', function () {
                    $('#tableContainer').scrollLeft($(this).scrollLeft());
                });

                // simpan supaya bisa dipakai di modal pencarian
                window._tempListMap = tempListMap || {};
                window._tempListMapNext = tempListMapNext || {};
            },
            error: function (xhr, status, error) {
                console.error("Failed to fetch data:", error);
                $('#schedule_table').html('<div class="alert alert-danger">Gagal memuat data schedule.</div>');
            }
        });
    }

    $(document).ready(function () {
        //  SEARCH 
        $('#searchSuffix').on('keydown', function(e){
            if (e.key === 'Enter') {
                e.preventDefault();
                const q = $(this).val();
                searchBySuffix(q, { showModal: true, notifyWhenEmpty: true });
            }
        });

        // function debounce(fn, ms=300){ let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); }; }
        // $('#searchSuffix').on('input', debounce(function(){ searchBySuffix($(this).val()); }, 300));

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

<script>
    let positionMapNow = {};   // { NO_RESEP: {machine, row, status} }
    let positionMapNext = {};  // { NO_RESEP: {machine, row, status} }
    let machineOrder = [];

    function buildIndexes({ data, oldDataList, machineKeys }) {
        positionMapNow = {};
        positionMapNext = {};
        machineOrder = machineKeys.slice();

        // NOW
        machineKeys.forEach(m => {
            const entries = data[m] || [];
            entries.forEach((cell, idx) => {
            if (cell && cell.no_resep) {
                const key = cell.no_resep.trim().toUpperCase();
                (positionMapNow[key] ||= []).push({ machine: m, row: idx, status: cell.status || '' });
            }
            });
        });

        // NEXT
        const oldMap = {};
        (oldDataList || []).forEach(item => {
            const mm = item.no_machine || 'UNASSIGNED';
            (oldMap[mm] ||= []).push(item);
        });
        machineKeys.forEach(m => {
            (oldMap[m] || []).forEach((item, idx) => {
            if (item && item.no_resep) {
                const key = item.no_resep.trim().toUpperCase();
                (positionMapNext[key] ||= []).push({ machine: m, row: idx, status: item.status || '' });
            }
            });
        });
    }

    // Helper: scroll horizontal ke kolom mesin dengan alignment & padding
    function scrollToMachine($container, machine, align = 'center', pad = 24) {
        // cari <th> kolom mesin di baris header pertama
        const $th = $container.find(`thead tr:first th[data-machine="${machine}"]`);
        if (!$th.length) return;

        // posisi kolom relatif ke container
        const colLeftAbs = $th.offset().left - $container.offset().left + $container.scrollLeft();
        const colWidth   = $th.outerWidth();
        const viewW      = $container.innerWidth();

        let targetLeft;

        switch (align) {
            case 'left':   // tampilkan kolom di sisi kiri dengan padding
            targetLeft = colLeftAbs - pad;
            break;
            case 'right':  // tampilkan kolom di sisi kanan dengan padding
            targetLeft = colLeftAbs - (viewW - colWidth) + pad;
            break;
            default:       // 'center' → pusatkan kolom
            targetLeft = colLeftAbs - (viewW - colWidth) / 2;
            break;
        }

        $container.animate({ scrollLeft: Math.max(targetLeft, 0) }, 250);
    }

    // gulir ke sel + highlight
    function jumpToCell(machine, row, cycle) {
        const containerId = cycle === 'next' ? '#tableContainerNext' : '#tableContainer';
        const $container = $(containerId);
        const $cell = $(`${containerId} td[data-machine="${machine}"][data-row="${row}"][data-cycle="${cycle}"]`);
        if ($cell.length === 0) return;

        // --- scroll horizontal ---
        scrollToMachine($container, machine, 'center', 24);

        // --- scroll vertikal ---
        const top = $cell.offset().top - 120;
        $('html, body').animate({ scrollTop: top }, 250);

        // highlight
        // $('.search-hit').removeClass('search-hit');
        $cell.addClass('search-hit');
    }

    // util: normalisasi
    function norm(txt){ return (txt||'').trim().toUpperCase(); }

    // cari: no_resep diawali (prefix) dengan input
    function searchBySuffix(q, opts = {}){
        const { showModal = true, notifyWhenEmpty = true } = opts;
        const needle = norm(q);
        if (!needle){ $('.search-hit').removeClass('search-hit'); return; }

        const collectMatches = (map, cycleName) => {
            const out = [];
            Object.keys(map).forEach(key => {
                if (key.startsWith(needle)) {
                    // ambil SEMUA posisi untuk resep ini
                    map[key].forEach((pos, i) => {
                        out.push({
                            no_resep: key,
                            machine: pos.machine,
                            row: pos.row,
                            status: pos.status || '',
                            cycle: cycleName,
                            idx: i + 1  // nomor kemunculan (1,2,3,…)
                        });
                    });
                }
            });
            return out;
        };

        const results = [
            ...collectMatches(positionMapNow,  'now'),
            ...collectMatches(positionMapNext, 'next')
        ];

        // bersihkan highlight lama
        $('.search-hit').removeClass('search-hit');

        if (results.length === 0) {
            if (notifyWhenEmpty) {
                Swal.fire({ icon:'info', title:'Tidak ditemukan', text:'Data tidak ditemukan' });
            }
            return;
        }

        // highlight SEMUA kemunculan
        results.forEach(r => {
            const containerId = r.cycle === 'next' ? '#tableContainerNext' : '#tableContainer';
            $(`${containerId} td[data-machine="${r.machine}"][data-row="${r.row}"][data-cycle="${r.cycle}"]`)
            .addClass('search-hit');
        });

        if (results.length === 1) {
            const r = results[0];
            if (r.cycle === 'next') $('#tableContainerNext').closest('.card')[0]?.scrollIntoView({ behavior:'smooth' });
            jumpToCell(r.machine, r.row, r.cycle);
            return;
        }

        if (!showModal) return;

        const tbody = $('#searchResultsTable tbody');
        tbody.empty();

        // kumpulkan info per (no_resep, machine)
        const grouped = new Map();
        /*
        value:
        {
            no_resep, machine,
            picks: [{cycle,row,status}],
            statuses: Set,
            groups: Set  // label merah (group) untuk NOW/NEXT
        }
        */
        const mapNow  = window._tempListMap     || {};
        const mapNext = window._tempListMapNext || {};

        results.forEach(r => {
            const key = `${r.no_resep}__${r.machine}`;
            if (!grouped.has(key)) {
                grouped.set(key, {
                no_resep: r.no_resep,
                machine:  r.machine,
                picks:    [],
                statuses: new Set(),
                groups:   new Set(),
                });
            }
            const g = grouped.get(key);
            g.picks.push({ cycle: r.cycle, row: r.row, status: r.status || '' });
            if (r.status) g.statuses.add(r.status);

            // ambil label group per cycle
            const labelArr = (r.cycle === 'next' ? mapNext : mapNow)[r.machine] || [];
            if (labelArr.length) g.groups.add(labelArr.join(' ; '));
        });

        // ubah ke array & urutkan
        const groupedArr = Array.from(grouped.values()).sort((a,b) =>
            a.machine.localeCompare(b.machine) ||
            a.no_resep.localeCompare(b.no_resep)
        );

        // === AUTO-JUMP: jika cuma 1 baris unik, langsung lompat tanpa buka modal ===
        if (groupedArr.length === 1) {
            const g = groupedArr[0];
            const nowPick  = g.picks.filter(p => p.cycle === 'now').sort((a,b) => a.row - b.row)[0];
            const nextPick = g.picks.filter(p => p.cycle === 'next').sort((a,b) => a.row - b.row)[0];
            const target   = nowPick || nextPick;   // prioritas NOW
            if (target) {
                if (target.cycle === 'next') {
                    $('#tableContainerNext').closest('.card')[0]?.scrollIntoView({ behavior:'smooth' });
                }

                jumpToCell(g.machine, target.row, target.cycle);
            }
            return; // <-- jangan buka modal
        }

        // === jika >1 baris unik, tampilkan modal ===
        groupedArr.forEach(g => {
            // tentukan target untuk tombol Go (prioritas NOW)
            const nowPick  = g.picks.filter(p => p.cycle === 'now').sort((a,b) => a.row - b.row)[0];
            const nextPick = g.picks.filter(p => p.cycle === 'next').sort((a,b) => a.row - b.row)[0];
            const target   = nowPick || nextPick;

            const statusText = Array.from(g.statuses).join(' / ') || '-';
            const groupText  = Array.from(g.groups).join(' / ')   || '-';

            const tr = $(`
                <tr>
                <td>${g.no_resep}</td>
                <td>${g.machine}</td>
                <td>${groupText}</td>
                <td>${statusText}</td>
                <td><button class="btn btn-xs btn-primary btn-jump" type="button">Go</button></td>
                </tr>
            `);

            tr.find('.btn-jump').on('click', () => {
                $('#searchResultsModal').modal('hide');
                if (target.cycle === 'next') {
                $('#tableContainerNext').closest('.card')[0]?.scrollIntoView({ behavior:'smooth' });
                }
                jumpToCell(g.machine, target.row, target.cycle);
            });

            tbody.append(tr);
        });

        $('#searchResultsModal').modal('show');
    }
</script>