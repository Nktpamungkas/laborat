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
    #actionButtons {
        display: flex;
        gap: 5px;
        margin-top: 24px;
        opacity: 0;
        transform: translateX(20px) scale(0.5);
        transition: opacity 0.5s ease, transform 0.5s ease;
        pointer-events: none;
        will-change: transform, opacity;
    }

    #actionButtons.show {
        opacity: 1;
        transform: translateX(0) scale(1);
        pointer-events: auto;
    }

    #actionSelect button {
        position: relative;
        line-height: 0.8;
        padding: 16px 12px 8px;
        min-width: 80px;
        height: 40px;
        text-align: center;
        font-size: 14px;
        font-weight: 700;
    }

    .shortcut-label {
        position: absolute;
        top: 4px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        font-weight: bold;
        line-height: 1;
        color: #333;
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
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">

                <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <!-- Tombol Repeat/End -->
                    <div id="actionSelect" style="display: flex; gap: 10px;">
                        <button class="btn btn-outline-primary action-btn" data-action="repeat">
                            <span class="shortcut-label">p</span>Repeat
                        </button>
                    </div>

                    <!-- Input Scan -->
                    <div style="display: flex; flex-direction: column;">
                        <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="width: 250px;">
                    </div>
                </div>


                <div id="tableContainer" style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    
                    <!-- Tabel -->
                    <div id="tableWrapper" style="flex: 2;">
                        <h4 class="text-center"><strong>END DATA</strong></h4>
                        <table id="tableCombined" class="table table-bordered" width="100%">
                            <thead class="bg-green">
                                <tr>
                                    <th><div align="center">No</div></th>
                                    <th><div align="center">No. Resep</div></th>
                                    <th><div align="center">Warna</div></th>
                                    <th><div align="center">Group</div></th>
                                    <th><div align="center">Temp</div></th>
                                    <th><div align="center">Status</div></th>
                                    <th><div align="center">Info</div></th>
                                </tr>
                            </thead>
                            <tbody id="dataBodyCombined"></tbody>
                        </table>
                    </div>

                    <!-- Selected List -->
                    <div id="selectedListContainer" style="flex: 1; min-width: 250px;">
                        <h5><strong>Selected List:</strong></h5>
                        <div id="selectedList"></div>
                        <button id="submitAll" class="btn btn-success" style="margin-top: 10px;" disabled>SUBMIT</button>
                    </div>

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
                                                <th>Warna</th>
                                                <th>Group</th>
                                                <th>Temp</th>
                                                <th>Status</th>
                                                <th>Info</th>
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
    </div>
</div>

<?php require './includes/socket_helper.php' ?>
<script>
    let endData = []
    let currentAction = "";
    let repeatList = [];

    function renderSelectedList() {
        const container = $("#selectedList");
        container.empty();

        const buildList = (label, list, color) => {
            if (list.length === 0) return "";

            const rows = list.map((item, index) =>
                `<li style="margin-bottom: 4px;">
                    ${index + 1}. ${item} 
                    <button class="btn btn-xs btn-danger remove-btn" data-type="${label}" data-index="${index}">x</button>
                </li>`).join("");

            return `<div style="margin-top:10px;">
                <strong style="color:${color}">${label.toUpperCase()} (${list.length})</strong>
                <ul>${rows}</ul>
            </div>`;
        };

        container.append(buildList("repeat", repeatList, "red"));

        const total = repeatList.length;
        $("#submitAll").prop("disabled", total === 0);
    }

    function loadData() {
        fetch("pages/ajax/GetData_Status_End.php")
            .then(response => response.json())
            .then(data => {
                endData = data
                
                // Start websocket to room 3
                subscribe(1)

                if ($.fn.DataTable.isDataTable('#tableCombined')) {
                    $('#tableCombined').DataTable().destroy();
                }

                const tbodyCombined = document.getElementById("dataBodyCombined");
                tbodyCombined.innerHTML = "";
                let index = 0;

                data.forEach((item) => {
                    index++;
                    const bgColor = index % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";
                    const row = `<tr style="background-color: ${bgColor}">
                        <td align="center">${index}</td>
                        <td align="center">${item.no_resep}</td>
                        <td align="center">${item.warna}</td>
                        <td align="center">${item.grp}</td>
                        <td align="center">${item.product_name}</td>
                        <td align="center">${item.status}</td>
                        <td align="center">${item.info}</td>
                    </tr>`;
                    tbodyCombined.innerHTML += row;
                });

                $('#tableCombined').DataTable({
                    pageLength: 20,
                    lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]]
                });
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });
    }

    function selectedItem(scanned){
        if (scanned === "" || currentAction === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Aksi Terlebih Dahulu',
                text: 'Silakan pilih tombol Repeat atau End, sebelum scan.'
            });
            return;
        }

        const exists = [...repeatList].includes(scanned);
        if (exists) {
            return; 
        }

        if (currentAction === "repeat") repeatList.push(scanned);

        renderSelectedList();
    }


    $(document).ready(function () {
        // MODULE RFID
            let filteredEndData = [] // For submit payload
            let deletedDRData = [] // For tag deleted no_resep with DR

            epcTable = $('#epcTable').DataTable({
                paging: true,
                searching: true,
                info: true,
                columns: [
                    { title: "No" },
                    { title: "No Resep" },
                    { title: "Warna" },
                    { title: "Group" },
                    { title: "Temp" },
                    { title: "Status" },
                    { title: "Info" },
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
                filteredData: filteredEndData,
                globalData: endData,
                checkFn: (item, docId) => {
                    const existsOnSelected = [...repeatList].includes(docId.trim())
                    if (existsOnSelected) {
                        addMessage(`SUCCESS_SUBSCRIBE: Already on selected ${docId}`)
                        return false // supaya ga di-push
                    }

                    // kalau lolos, baru cocokkan resep
                    return item.no_resep.trim() == docId.trim()
                },
                columns: [
                    (row, index) => index, // nomor urut
                    (row) => row.no_resep?.trim(),
                    "warna",
                    "grp",
                    "product_name",
                    "status",
                    "info",
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
                filteredData: filteredEndData,
                globalData: endData,
                checkFn: (item, docId) => {
                    const existsOnSelected = [...repeatList].includes(docId.trim())
                    if (existsOnSelected) {
                        addMessage(`SUCCESS_SUBSCRIBE: Already on selected ${docId}`)
                        return false // supaya ga di-push
                    }

                    // kalau lolos, baru cocokkan resep
                    return item.no_resep.trim() == docId.trim()
                },
                columns: [
                    (row, index) => index, // nomor urut
                    (row) => row.no_resep?.trim(),
                    "warna",
                    "grp",
                    "product_name",
                    "status",
                    "info",
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
                filteredData: filteredEndData,
                globalData: endData,
                checkFn: (item, docId) => {
                    const existsOnSelected = [...repeatList].includes(docId.trim())
                    if (existsOnSelected) {
                        addMessage(`SUCCESS_SUBSCRIBE: Already on selected ${docId}`)
                        return false // supaya ga di-push
                    }

                    // kalau lolos, baru cocokkan resep
                    return item.no_resep.trim() == docId.trim()
                },
                columns: [
                    (row, index) => index, // nomor urut
                    (row) => row.no_resep?.trim(),
                    "warna",
                    "grp",
                    "product_name",
                    "status",
                    "info",
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

                // Hapus juga dari filteredEndData
                filteredEndData = filteredEndData.filter(item => item.no_resep.trim() !== noResep);
            });

            $('#submitBtnRFID').on('click', function () {
                filteredEndData.map((item) => {
                    selectedItem(item.no_resep.trim())
                })

                $('#modalRFID').modal('hide');
            });
        // MODULE RFID

        loadData();

        setInterval(loadData, 20000);

        // Pilih tombol aksi
        $(".action-btn").on("click", function () {
            $(".action-btn").removeClass("btn-primary").addClass("btn-outline-primary");
            $(this).removeClass("btn-outline-primary").addClass("btn-primary");

            currentAction = $(this).data("action");
            $("#scanInput").focus();
        });

        // Input scan
        $('#scanInput').on('keypress', function (e) {
            if (e.which === 13) {
                const scanned = $(this).val().trim();
                if (scanned === "" || currentAction === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Aksi Terlebih Dahulu',
                        text: 'Silakan pilih tombol Repeat atau End, sebelum scan.'
                    });
                    return;
                }

                const exists = [...repeatList].includes(scanned);
                if (exists) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sudah Ada',
                        text: `No. Resep ${scanned} sudah dimasukkan.`
                    });
                    $(this).val("").focus();
                    return;
                }

                // Validasi
                $.get(`pages/ajax/validate_scan_end.php?no_resep=${scanned}`, function(response) {
                    if (!response.valid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Tidak Ditemukan',
                            text: `No. Resep "${scanned}" tidak tersedia di list.`
                        });
                        $('#scanInput').val('').focus();
                        return;
                    }

                    // Jika valid, baru masukkan ke list
                    if (currentAction === "repeat") repeatList.push(scanned);

                    $('#scanInput').val("").focus();
                    renderSelectedList();
                });
            }
        });

        // Hapus item dari list
        $('#selectedList').on('click', '.remove-btn', function () {
            const type = $(this).data("type");
            const index = $(this).data("index");

            if (type === "repeat") repeatList.splice(index, 1);

            renderSelectedList();
        });

        // Submit semua
        $('#submitAll').on('click', function () {
            const payload = {
                repeat: repeatList,
            };

            Swal.fire({
                title: 'SUBMIT?',
                text: 'Pastikan data yang akan dikirim sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'pages/ajax/submit_batch_status_end.php',
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json',
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Semua data berhasil dikirim.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            repeatList = [];
                            renderSelectedList();
                            loadData();
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat mengirim data.',
                            });
                        }
                    });
                }
            });
        });

        // Shortcut tombol: p, q, y
        $(document).on('keydown', function (e) {
            const key = e.key.toLowerCase();
            if (['p', 'q', 'y'].includes(key)) {
                e.preventDefault();
            }
            
            if (key === 'p') {
                $("[data-action='repeat']").click();
            } else if (key === 'q') {
                $("[data-action='end']").click();
            }
        });
    });
</script>