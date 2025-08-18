<style>
    input::placeholder {
        font-style: italic;
        font-size: 12px;
    }
    #tempWrapper {
        margin-bottom: 0;
    }
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }

    .blink-warning {
        color: red;
        font-weight: bold;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.2; }
    }
</style>
<style>
    .sortable-ghost {
        opacity: 0.4;
        background-color: #ffeeba !important;
    }
    .sortable-selected {
        background-color:rgb(6, 206, 6) !important;
    }
</style>
<style>
    .table-scrollable {
        max-height: 800px;
        overflow-y: auto;
        border: 1px solid #ddd;
    }

    .table-scrollable thead th {
        position: sticky;
        top: 0;
        background-color: #00a65a ;
        z-index: 1;
    }
    .table-scrollable::-webkit-scrollbar {
        width: 6px;
    }

    .table-scrollable::-webkit-scrollbar-thumb {
        background-color: #aaa;
        border-radius: 3px;
    }

    .table-scrollable::-webkit-scrollbar-track {
        background: transparent;
    }
    .table {
        margin-bottom: 0 !important;
    }
    /* tbody {
        font-size: 9px;
    } */
    td.cycle-cell {
        width: 40px;
        text-align: center;
        font-weight: bold;
    }
    .content {
        padding: 15px 0 !important;
    }
    .col-xs-12 {
        padding: 0 !important;
    }
    tbody {
        font-size: 11px;
    }
</style>


<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="row" style="margin-bottom: 10px;">
                    <!-- Scan input di kiri -->
                    <div class="col-xs-6">
                        <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="max-width: 250px;" autofocus>
                    </div>

                    <!-- Tombol lock di kanan -->
                    <div class="col-xs-6 text-right">
                        <button id="toggleLockBtn" class="btn btn-warning">🔒 Lock Drag</button>
                    </div>
                </div>
                
                <!-- Container for tables with display flex -->
                <div id="tableContainer" style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    
                    <!-- Dispensing Poly Table -->
                    <div id="polyTableWrapper" style="flex: 1; min-width: 300px; display: block;">
                        <h4 id="polyHeader" class="text-center"><strong>DISPENSING POLY</strong></h4>
                        <div class="table-scrollable">
                            <table id="tablePoly" class="table table-bordered" width="100%">
                                <thead class="bg-green">
                                    <tr>
                                        <th>
                                            <div align="center" style="width: 5px;">No</div>
                                        </th>
                                        <th>
                                            <div align="center">Cycle</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Resep</div>
                                        </th>
                                        <th>
                                            <div align="center">Temp</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Mesin</div>
                                        </th>
                                        <th>
                                            <div align="center">Status</div>
                                        </th>
                                        <!-- <th>
                                            <div align="center">Dispensing Start</div>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody id="dataBodyPoly">
                                    <!-- Data will be displayed here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Dispensing Cotton Table -->
                    <div id="cottonTableWrapper" style="flex: 1; min-width: 300px; display: block;">
                        <h4 id="cottonHeader" class="text-center"><strong>DISPENSING COTTON</strong></h4>
                        <div class="table-scrollable">
                            <table id="tableCotton" class="table table-bordered" width="100%">
                                <thead class="bg-green">
                                    <tr>
                                        <th>
                                            <div align="center" style="width: 5px;">No</div>
                                        </th>
                                        <th>
                                            <div align="center">Cycle</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Resep</div>
                                        </th>
                                        <th>
                                            <div align="center">Temp</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Mesin</div>
                                        </th>
                                        <th>
                                            <div align="center">Status</div>
                                        </th>
                                        <!-- <th>
                                            <div align="center">Dispensing Start</div>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody id="dataBodyCotton">
                                    <!-- Data will be displayed here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Dispensing White Table -->
                    <div id="whiteTableWrapper" style="flex: 1; min-width: 300px; display: block;">
                        <h4 id="whiteHeader" class="text-center"><strong>DISPENSING WHITE</strong></h4>
                        <div class="table-scrollable">
                            <table id="tableWhite" class="table table-bordered" width="100%">
                                <thead class="bg-green">
                                    <tr>
                                        <th>
                                            <div align="center" style="width: 5px;">No</div>
                                        </th>
                                        <th>
                                            <div align="center">Cycle</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Resep</div>
                                        </th>
                                        <th>
                                            <div align="center">Temp</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Mesin</div>
                                        </th>
                                        <th>
                                            <div align="center">Status</div>
                                        </th>
                                        <!-- <th>
                                            <div align="center">Dispensing Start</div>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody id="dataBodyWhite">
                                    <!-- Data will be displayed here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script> -->
 <script src="bower_components/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    $(document).ready(function() {
        let fullGroupedData = { '1': [], '2': [], '3': [] };

        loadData();

        $('#scanInput').on('keypress', function (e) {
            if (e.which === 13) { // Enter key
                const noResep = $(this).val().trim();
                if (noResep !== "") {
                    updateStatus(noResep);
                    $(this).val("");
                }
            }
        });

        function updateStatus(noResep) {
            const dispensingCode = getDispensingCodeFromNoResep(noResep);
            if (dispensingCode === null) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Ditemukan',
                    text: `No. Resep ${noResep} tidak ditemukan di list.`,
                });
                return;
            }

            $.ajax({
                url: 'pages/ajax/scan_dispensing_update_status.php',
                method: 'POST',
                data: {
                    no_resep: noResep,
                    dispensing_code: dispensingCode
                },
                success: function (response) {
                    console.log("Update sukses:", response);
                    fetchAndRenderDataOnly();
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

        function getDispensingCodeFromNoResep(noResep) {
            const match = dispensingData.find(item => item.no_resep === noResep);
            if (!match) return null;
            return match.dispensing?.trim() ?? "";
        }
    });
</script>
<script>
    let dispensingData = [];

    function loadData() {
        fetch("pages/ajax/GetData_DispensingList.php")
            .then(response => response.json())
            .then(data => {
                dispensingData = data;

                fullGroupedData = { '1': [], '2': [], '3': [] };
                data.forEach(item => {
                    const code = item.dispensing?.trim() ?? '';
                    if (['1', '2'].includes(code)) {
                        fullGroupedData[code].push(item);
                    } else {
                        fullGroupedData['3'].push(item);
                    }
                });

                renderOnlyTables(data);
                enableSortableTables(); // hanya sekali saat awal load
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });
    }

    function fetchAndRenderDataOnly() {
        fetch("pages/ajax/GetData_DispensingList.php")
            .then(response => response.json())
            .then(data => {
                dispensingData = data;

                fullGroupedData = { '1': [], '2': [], '3': [] };
                data.forEach(item => {
                    const code = item.dispensing?.trim() ?? '';
                    if (['1', '2'].includes(code)) {
                        fullGroupedData[code].push(item);
                    } else {
                        fullGroupedData['3'].push(item);
                    }
                });

                renderOnlyTables(data); // hanya render ulang isinya
            })
            .catch(err => {
                console.error("Gagal memperbarui data:", err);
            });
    }

    function renderOnlyTables(data) {
        const tbodyPoly = document.getElementById("dataBodyPoly");
        const tbodyCotton = document.getElementById("dataBodyCotton");
        const tbodyWhite = document.getElementById("dataBodyWhite");

        tbodyPoly.innerHTML = "";
        tbodyCotton.innerHTML = "";
        tbodyWhite.innerHTML = "";

        renderTable(data, tbodyPoly, "1");
        renderTable(data, tbodyCotton, "2");
        renderTable(data, tbodyWhite, "");

        document.getElementById("polyTableWrapper").style.display = tbodyPoly.innerHTML.trim() ? "block" : "none";
        document.getElementById("cottonTableWrapper").style.display = tbodyCotton.innerHTML.trim() ? "block" : "none";
        document.getElementById("whiteTableWrapper").style.display = tbodyWhite.innerHTML.trim() ? "block" : "none";

        const visibleTables = [tbodyPoly, tbodyCotton, tbodyWhite].filter(t => t.innerHTML.trim() !== "").length;
        document.getElementById("tableContainer").style.display = visibleTables > 1 ? "flex" : "block";
    }

    function renderTable(dataArray, tbodyElement, dispensingCode) {
        const rowsPerBlock = 16;

        const filtered = dataArray.filter(item => {
            const code = item.dispensing?.trim() ?? "";
            return (dispensingCode === "" && (code !== "1" && code !== "2")) || code === dispensingCode;
        });

        const totalBlocks = Math.ceil(filtered.length / rowsPerBlock);
        tbodyElement.innerHTML = "";

        for (let blockIndex = 0; blockIndex < totalBlocks; blockIndex++) {
            const blockRows = filtered.slice(blockIndex * rowsPerBlock, (blockIndex + 1) * rowsPerBlock);

            // Hitung activeRows untuk kebutuhan cycle cell
            const activeRows = blockRows.filter(item =>
                item.status === 'scheduled' || item.status === 'in_progress_dispensing'
            );
            const middleIndex = Math.floor((activeRows.length - 1) / 2);

            blockRows.forEach((item, indexInBlock) => {
                const rowNumber = item.rowNumber;
                const bgColor = blockIndex % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";
                const isOld = item.is_old_data == "1";

                const tr = document.createElement("tr");
                tr.style.backgroundColor = bgColor;
                tr.dataset.id = item.id;

                const isVisible = item.status === 'scheduled' || item.status === 'in_progress_dispensing';

                // Sembunyikan jika status tidak diizinkan
                if (!isVisible) {
                    tr.style.display = "none";
                    tr.classList.add("not-draggable");
                } else if (item.status !== 'scheduled') {
                    tr.classList.add("not-draggable");
                }

                tr.innerHTML += `<td align="center" class="row-number">${rowNumber}</td>`;

                // Tampilkan cycleNumber di tengah baris aktif
                const activeIndex = activeRows.findIndex(i => i.id === item.id);
                if (activeIndex === middleIndex) {
                    tr.innerHTML += `<td class="cycle-cell">${item.cycleNumber}</td>`;
                } else {
                    tr.innerHTML += `<td class="cycle-cell" style="opacity: 0; pointer-events: none;"></td>`;
                }

                tr.innerHTML += `<td align="center" style="white-space: nowrap;">${item.no_resep} - ${item.jenis_matching} ${isOld ? '🕑' : ''}</td>`;
                tr.innerHTML += `<td align="center" style="white-space: nowrap;">${item.product_name}</td>`;
                tr.innerHTML += `
                    <td align="center">
                        <span 
                            class="editable-machine" 
                            data-id="${item.id}" 
                            data-group="${item.id_group}" 
                            data-current="${item.no_machine}"
                        >
                            ${item.no_machine}
                        </span>
                    </td>`;
                tr.innerHTML += `<td align="center">${item.status}</td>`;

                tbodyElement.appendChild(tr);
            });
        }
    }


    // function renderTable(dataArray, tbodyElement, dispensingCode) {
    //     const rowsPerBlock = 16;

    //     // Filter berdasarkan kode dispensing
    //     const filtered = dataArray.filter(item => {
    //         const code = item.dispensing?.trim() ?? "";
    //         return (dispensingCode === "" && (code !== "1" && code !== "2")) || code === dispensingCode;
    //     });

    //     // Kelompokkan per cycle
    //     const groupedByCycle = {};
    //     filtered.forEach(item => {
    //         const cycle = item.cycleNumber ?? 1;
    //         if (!groupedByCycle[cycle]) {
    //             groupedByCycle[cycle] = [];
    //         }
    //         groupedByCycle[cycle].push(item);
    //     });

    //     tbodyElement.innerHTML = "";

    //     Object.keys(groupedByCycle).sort((a, b) => a - b).forEach(cycleNumber => {
    //         const blockRows = groupedByCycle[cycleNumber];

    //         // Ambil hanya data aktif (scheduled / in_progress_dispensing)
    //         const activeRows = blockRows.filter(item =>
    //             item.status === 'scheduled' || item.status === 'in_progress_dispensing'
    //         );

    //         const middleIndex = Math.floor((activeRows.length - 1) / 2);

    //         activeRows.forEach((item, activeIndex) => {
    //             const rowNumber = item.rowNumber;
    //             const bgColor = cycleNumber % 2 === 0 ? "rgb(220, 220, 220)" : "rgb(250, 235, 215)";
    //             const isOld = item.is_old_data == "1";

    //             const tr = document.createElement("tr");
    //             tr.style.backgroundColor = bgColor;
    //             tr.dataset.id = item.id;

    //             if (item.status !== 'scheduled') {
    //                 tr.classList.add("not-draggable");
    //             }

    //             tr.innerHTML += `<td align="center" class="row-number">${rowNumber}</td>`;

    //             if (activeIndex === middleIndex) {
    //                 tr.innerHTML += `<td class="cycle-cell">${item.cycleNumber}</td>`;
    //             } else {
    //                 tr.innerHTML += `<td class="cycle-cell" style="opacity: 0; pointer-events: none;"></td>`;
    //             }

    //             tr.innerHTML += `<td align="center" style="white-space: nowrap;">${item.no_resep} - ${item.jenis_matching} ${isOld ? '🕑' : ''}</td>`;
    //             tr.innerHTML += `<td align="center" style="white-space: nowrap;">${item.product_name}</td>`;
    //             tr.innerHTML += `
    //                 <td align="center">
    //                     <span 
    //                         class="editable-machine" 
    //                         data-id="${item.id}" 
    //                         data-group="${item.id_group}" 
    //                         data-current="${item.no_machine}"
    //                     >
    //                         ${item.no_machine}
    //                     </span>
    //                 </td>`;
    //             tr.innerHTML += `<td align="center">${item.status}</td>`;

    //             tbodyElement.appendChild(tr);
    //         });
    //     });
    // }


    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("editable-machine")) {
            const span = e.target;
            const currentValue = span.dataset.current;
            const id = span.dataset.id;
            const group = span.dataset.group;
                
            fetch(`pages/ajax/get_mesin_options_dispensing.php?group=${encodeURIComponent(group)}`)
                .then(res => res.json())
                .then(options => {
                    const select = document.createElement("select");
                    select.style.width = "100px";
                    select.dataset.editingId = id;

                    options.forEach(opt => {
                        const option = document.createElement("option");
                        option.value = opt;
                        option.textContent = opt;
                        if (opt === currentValue) option.selected = true;
                        select.appendChild(option);
                    });

                    span.replaceWith(select);
                    select.focus();

                    select.addEventListener("blur", () => saveChange(id, select.value, span));
                    select.addEventListener("change", () => saveChange(id, select.value, span));
                    select.addEventListener("keydown", e => {
                        if (e.key === "Escape") {
                            select.replaceWith(span);
                        }
                    });
                });
        }
    });

    function saveChange(id, newValue, originalSpan) {
        const select = document.querySelector(`select[data-editing-id="${id}"]`);
        fetch("pages/ajax/update_machine_number.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, no_machine: newValue })
        })
        .then(res => res.json())
        .then(response => {
            const newSpan = document.createElement("span");
            newSpan.className = "editable-machine";
            newSpan.dataset.id = id;
            newSpan.dataset.group = originalSpan.dataset.group;
            newSpan.dataset.current = newValue;
            newSpan.textContent = newValue;

            if (response.success) {
                select.replaceWith(newSpan);

                // ✅ Tampilkan SweetAlert kalau berhasil
                Swal.fire({
                    toast: true,
                    position: 'center',
                    icon: 'success',
                    title: 'Nomor mesin berhasil diperbarui',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                alert("Gagal menyimpan perubahan");
                select.replaceWith(originalSpan);
            }
        })
        .catch(() => {
            alert("Terjadi kesalahan");
            select.replaceWith(originalSpan);
        });
    }

    // function enableSortableTables() {
    //     const options = {
    //         animation: 150,
    //         ghostClass: 'sortable-ghost',
    //         handle: 'td',
    //         onEnd: function (evt) {
    //             const tbody = evt.from;
    //             const rows = Array.from(tbody.querySelectorAll("tr"));

    //             // ✅ Update semua order_index berdasarkan urutan sekarang
    //             const newOrder = rows.map((row, index) => ({
    //                 id: row.getAttribute("data-id"),
    //                 order_index: index + 1
    //             }));

    //             // ✅ Update tampilan
    //             updateRowStyles(tbody);
    //             updateRowNumbers(tbody);
    //             updateCycleCells(tbody);

    //             // ✅ Kirim semua ID dan order_index ke server
    //             fetch("pages/ajax/UpdateOrderIndexes.php", {
    //                 method: "POST",
    //                 headers: { 'Content-Type': 'application/json' },
    //                 body: JSON.stringify({ orders: newOrder })
    //             })
    //             .then(res => res.json())
    //             .then(data => {
    //                 if (!data.success) {
    //                     console.error("Gagal simpan urutan:", data.message);
    //                 }
    //             })
    //             .catch(err => console.error("AJAX error:", err));
    //         }
    //     };

    //     Sortable.create(document.getElementById("dataBodyPoly"), options);
    //     Sortable.create(document.getElementById("dataBodyCotton"), options);
    //     Sortable.create(document.getElementById("dataBodyWhite"), options);
    // }

    // function updateRowStyles(tbody) {
    //     const rows = Array.from(tbody.querySelectorAll("tr"));
    //     const rowsPerBlock = 16;

    //     rows.forEach((row, index) => {
    //         const groupIndex = Math.floor(index / rowsPerBlock);
    //         const bgColor = groupIndex % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";
    //         row.style.backgroundColor = bgColor;
    //     });
    // }

    // function updateRowNumbers(tbody) {
    //     const rows = Array.from(tbody.querySelectorAll("tr"));
    //     const rowsPerBlock = 16;
    //     let activeIndex = 0;

    //     rows.forEach((row) => {
    //         const numberCell = row.querySelector(".row-number");

    //         if (numberCell) {
    //             const rowNumber = (activeIndex % rowsPerBlock) + 1;
    //             numberCell.textContent = rowNumber;
    //             activeIndex++;
    //         }
    //     });
    // }

    // function updateCycleCells(tbody) {
    //     const rows = Array.from(tbody.querySelectorAll("tr"));
    //     const rowsPerBlock = 16;

    //     for (let i = 0; i < rows.length; i += rowsPerBlock) {
    //         const blockRows = rows.slice(i, i + rowsPerBlock);
    //         const activeRows = blockRows.filter(row => row.querySelector(".row-number"));

    //         const middleIndex = Math.floor((activeRows.length - 1) / 2);

    //         activeRows.forEach((row, index) => {
    //             const cell = row.querySelector(".cycle-cell");
    //             if (cell) {
    //                 if (index === middleIndex) {
    //                     cell.textContent = Math.floor(i / rowsPerBlock) + 1;
    //                     cell.style.opacity = "1";
    //                     cell.style.pointerEvents = "auto";
    //                 } else {
    //                     cell.textContent = "";
    //                     cell.style.opacity = "0";
    //                     cell.style.pointerEvents = "none";
    //                 }
    //             }
    //         });
    //     }
    // }
</script>

<script>
    let sortables = []; // Simpan semua sortable instance di sini
    let isLocked = true;

    function enableSortableTables() {
        // Hapus instance lama (jika ada)
        sortables.forEach(s => s.destroy());
        sortables = [];

        document.querySelectorAll("tbody").forEach(tbody => {
            const sortable = new Sortable(tbody, {
                animation: 150,
                handle: null,
                ghostClass: "sortable-ghost", // Class saat dragging
                chosenClass: "sortable-chosen", // Class saat diklik
                dragClass: "sortable-drag", // Class elemen yang sedang diseret
                fallbackOnBody: true, // Biar ghost tidak lepas dari DOM
                swapThreshold: 0.65,
                multiDrag: true,
                selectedClass: 'sortable-selected',
                fallbackTolerance: 3,
                disabled: isLocked,
                draggable: "tr:not(.not-draggable)",
                onEnd: function (evt) {
                    if (isLocked) return;

                    const tbody = evt.from;
                    const sortable = sortables.find(s => s.el === tbody);

                    // clear sortable-selected sebelumnya => tampilan mulus
                    // tbody.querySelectorAll(".sortable-selected").forEach(row => {
                    //     row.classList.remove("sortable-selected");
                    // });

                    tbody.querySelectorAll('.sortable-selected, .sortable-ghost, .sortable-drag').forEach(el => {
                        el.classList.remove('sortable-selected', 'sortable-ghost', 'sortable-drag');
                    });

                    if (sortable && sortable.multiDrag) {
                        sortable.multiDrag._deselectMultiDrag();
                        sortable.multiDrag._selected = [];
                    }

                    const visibleRows = [...tbody.querySelectorAll("tr")];
                    const dispCode = getDispensingCodeFromTbody(tbody.id);

                    const fullData = [...fullGroupedData[dispCode]];

                    console.log("Urutan TR dalam DOM setelah drag:");
                    [...tbody.querySelectorAll("tr")].forEach((tr, i) => {
                        console.log(`${i + 1}: ID = ${tr.dataset.id}`);
                    });


                    const visibleIdOrder = visibleRows.map(row => row.dataset.id);

                    const reordered = visibleIdOrder.map((id, index) => ({
                        id,
                        order_index: index + 1
                    }));

                    console.log("Reordered dikirim ke server:", reordered);

                    fetch('pages/ajax/UpdateOrderIndexes.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(reordered)
                    })
                    .then(res => res.json())
                    .then(response => {
                        if (response.success) {
                            fetchAndRenderDataOnly();
                            setTimeout(() => {
                                enableSortableTables();
                            }, 50);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal update urutan.'
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan saat menyimpan.'
                        });
                    });
                }

            });

            sortables.push(sortable);
        });
    }

    function toggleLock() {
        isLocked = !isLocked;

        sortables.forEach(sortable => {
            sortable.option("disabled", isLocked);
        });

        const btn = document.getElementById("toggleLockBtn");
        btn.innerHTML = isLocked ? "🔓 Unlock Drag" : "🔒 Lock Drag";
        btn.className = isLocked ? "btn btn-success" : "btn btn-warning";

        const scanInput = document.getElementById("scanInput");
        scanInput.disabled = !isLocked;

        if(!isLocked) {
            document.querySelectorAll("tbody tr").forEach(row => {
                row.addEventListener("click", function () {
                    const tbody = row.closest("tbody");
                    const sortable = sortables.find(s => s.el === tbody);
                    if (!sortable) return;

                    const isSelected = row.classList.contains("sortable-selected");

                    if (isSelected) {
                        sortable.utils.deselect(row);
                        row.classList.remove("sortable-selected");
                    } else {
                        sortable.utils.select(row);
                        row.classList.add("sortable-selected");
                    }
                });
            });
        }

    }

    function getDispensingCodeFromTbody(tbodyId) {
        if (tbodyId.includes("Poly")) return "1";
        if (tbodyId.includes("Cotton")) return "2";
        return "3";
    }

    window.addEventListener("DOMContentLoaded", function () {
        enableSortableTables();

        // Awal: drag terkunci => scan aktif
        document.getElementById("scanInput").disabled = false;

        // Set tombol awal ke status terkunci
        const btn = document.getElementById("toggleLockBtn");
        btn.innerHTML = "🔓 Unlock Drag";
        btn.className = "btn btn-success";

        setInterval(() => {
            if (isLocked) {
                loadData();
            }
        }, 20000);
    });

    document.getElementById("toggleLockBtn").addEventListener("click", toggleLock);
</script>

<script>
    if (localStorage.getItem('showSuccessAlert') === '1') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data berhasil dikirim',
            timer: 1500,
            showConfirmButton: false
        });

        localStorage.removeItem('showSuccessAlert');
    }
</script>
