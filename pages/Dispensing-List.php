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
                                            <div align="center">No</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Resep</div>
                                        </th>
                                        <th>
                                            <div align="center">Temp</div>
                                        </th>
                                        <!-- <th>
                                            <div align="center">No. Mesin</div>
                                        </th> -->
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
                                            <div align="center">No</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Resep</div>
                                        </th>
                                        <th>
                                            <div align="center">Temp</div>
                                        </th>
                                        <!-- <th>
                                            <div align="center">No. Mesin</div>
                                        </th> -->
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
                                            <div align="center">No</div>
                                        </th>
                                        <th>
                                            <div align="center">No. Resep</div>
                                        </th>
                                        <th>
                                            <div align="center">Temp</div>
                                        </th>
                                        <!-- <th>
                                            <div align="center">No. Mesin</div>
                                        </th> -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    $(document).ready(function() {
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
                    loadData();
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

                const tbodyPoly = document.getElementById("dataBodyPoly");
                const tbodyCotton = document.getElementById("dataBodyCotton");
                const tbodyWhite = document.getElementById("dataBodyWhite");

                tbodyPoly.innerHTML = "";
                tbodyCotton.innerHTML = "";
                tbodyWhite.innerHTML = "";

                renderTable(data, tbodyPoly, "1");
                renderTable(data, tbodyCotton, "2");
                renderTable(data, tbodyWhite, "");

                enableSortableTables();

                document.getElementById("polyTableWrapper").style.display = tbodyPoly.innerHTML.trim() ? "block" : "none";
                document.getElementById("cottonTableWrapper").style.display = tbodyCotton.innerHTML.trim() ? "block" : "none";
                document.getElementById("whiteTableWrapper").style.display = tbodyWhite.innerHTML.trim() ? "block" : "none";

                const visibleTables = [tbodyPoly, tbodyCotton, tbodyWhite].filter(t => t.innerHTML.trim() !== "").length;
                document.getElementById("tableContainer").style.display = visibleTables > 1 ? "flex" : "block";
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });
    }

    function renderTable(dataArray, tbodyElement, dispensingCode) {
        const rowsPerBlock = 16;
        const filtered = dataArray.filter(item => {
            const code = item.dispensing?.trim() ?? "";
            return (dispensingCode === "" && (code !== "1" && code !== "2")) || code === dispensingCode;
        });

        filtered.forEach((item, index) => {
            const groupIndex = Math.floor(index / rowsPerBlock);
            const rowNumber = (index % rowsPerBlock) + 1;
            const bgColor = groupIndex % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";
            const isActiveStatus = item.status === 'scheduled' || item.status === 'in_progress_dispensing';

            let rowHTML = `<tr style="background-color: ${bgColor}; ${!isActiveStatus ? 'color: #ccc;' : ''}" 
                            data-id="${item.id}">
                <td align="center" class="checkbox-cell" style="display: none;">
                    <input type="checkbox" class="row-checkbox">
                </td>
                <td align="center" class="row-number">${rowNumber}</td>`;

            if (isActiveStatus) {
                rowHTML += `
                    <td align="center">${item.no_resep} - ${item.jenis_matching}</td>
                    <td align="center">${item.product_name}</td>
                    <td align="center">${item.status}</td>
                `;
            } else {
                rowHTML += `
                    <td colspan="3" align="center"></td>
                `;
            }

            rowHTML += `</tr>`;

            tbodyElement.innerHTML += rowHTML;
        });
    }

    function enableSortableTables() {
        const options = {
            animation: 150,
            ghostClass: 'sortable-ghost',
            handle: 'td',
            onEnd: function (evt) {
                const tbody = evt.from;
                const rows = Array.from(tbody.querySelectorAll("tr"));

                // ✅ Update semua order_index berdasarkan urutan sekarang
                const newOrder = rows.map((row, index) => ({
                    id: row.getAttribute("data-id"),
                    order_index: index + 1
                }));

                // ✅ Update tampilan
                updateRowStyles(tbody);
                updateRowNumbers(tbody);

                // ✅ Kirim semua ID dan order_index ke server
                fetch("pages/ajax/UpdateOrderIndexes.php", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ orders: newOrder })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        console.error("Gagal simpan urutan:", data.message);
                    }
                })
                .catch(err => console.error("AJAX error:", err));
            }
        };

        Sortable.create(document.getElementById("dataBodyPoly"), options);
        Sortable.create(document.getElementById("dataBodyCotton"), options);
        Sortable.create(document.getElementById("dataBodyWhite"), options);
    }

    function updateRowStyles(tbody) {
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const rowsPerBlock = 16;

        rows.forEach((row, index) => {
            const groupIndex = Math.floor(index / rowsPerBlock);
            const bgColor = groupIndex % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";
            row.style.backgroundColor = bgColor;
        });
    }

    function updateRowNumbers(tbody) {
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const rowsPerBlock = 16;
        let activeIndex = 0;

        rows.forEach((row) => {
            const numberCell = row.querySelector(".row-number");

            if (numberCell) {
                const rowNumber = (activeIndex % rowsPerBlock) + 1;
                numberCell.textContent = rowNumber;
                activeIndex++;
            }
        });
    }
</script>

<script>
    let sortables = []; // Simpan semua sortable instance di sini
    let isLocked = true;

    function enableSortableTables() {
        const tbodyIds = ["dataBodyPoly", "dataBodyCotton", "dataBodyWhite"];
        sortables = [];

        tbodyIds.forEach(id => {
            const tbody = document.getElementById(id);
            const sortable = Sortable.create(tbody, {
                animation: 150,
                ghostClass: "sortable-ghost",
                disabled: true,

                multiDrag: true,
                selectedClass: "sortable-selected",

                onSelect: function (evt) {
                    const row = evt.item;
                },
                onDeselect: function (evt) {
                    const row = evt.item;
                },

                onEnd: function (evt) {
                    const tbody = evt.from;
                    const rows = Array.from(tbody.querySelectorAll("tr"));

                    const newOrder = rows.map((row, index) => ({
                        id: row.getAttribute("data-id"),
                        order_index: index + 1
                    }));

                    updateRowStyles(tbody);
                    updateRowNumbers(tbody);

                    fetch("pages/ajax/UpdateOrderIndexes.php", {
                        method: "POST",
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ orders: newOrder })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            console.error("Gagal simpan urutan:", data.message);
                        }
                    })
                    .catch(err => console.error("AJAX error:", err));
                }
            });

            sortables.push(sortable); // Simpan
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

    window.addEventListener("DOMContentLoaded", function () {
        enableSortableTables();

        // Awal: drag terkunci => scan aktif
        document.getElementById("scanInput").disabled = false;

        // Set tombol awal ke status terkunci
        const btn = document.getElementById("toggleLockBtn");
        btn.innerHTML = "🔓 Unlock Drag";
        btn.className = "btn btn-success";
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
