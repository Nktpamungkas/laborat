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
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">

                <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <!-- Tombol Repeat/End/Hold -->
                    <div id="actionSelect" style="display: flex; gap: 10px;">
                        <button class="btn btn-outline-primary action-btn" data-action="repeat">
                            <span class="shortcut-label">p</span>Repeat
                        </button>
                        <button class="btn btn-outline-danger action-btn" data-action="end">
                            <span class="shortcut-label">q</span>End
                        </button>
                        <button class="btn btn-outline-warning action-btn" data-action="hold">
                            <span class="shortcut-label">y</span>Hold
                        </button>
                    </div>

                    <!-- Input Scan -->
                    <div style="display: flex; flex-direction: column;">
                        <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="width: 250px;">
                    </div>
                </div>

                <!-- Tabel dan Selected List disamping -->
                <div id="tableContainer" style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    
                    <!-- Tabel -->
                    <div id="tableWrapper" style="flex: 2;">
                        <h4 class="text-center"><strong>DARK ROOM END</strong></h4>
                        <table id="tableCombined" class="table table-bordered" width="100%">
                            <thead class="bg-green">
                                <tr>
                                    <th><div align="center">No</div></th>
                                    <th><div align="center">No. Resep</div></th>
                                    <th><div align="center">Temp</div></th>
                                    <th><div align="center">Status</div></th>
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

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentAction = "";
    let repeatList = [];
    let endList = [];
    let holdList = [];

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
        container.append(buildList("end", endList, "green"));
        container.append(buildList("hold", holdList, "orange"));

        const total = repeatList.length + endList.length + holdList.length;
        $("#submitAll").prop("disabled", total === 0);
    }

    function loadData() {
        fetch("pages/ajax/GetData_DarkroomEndList.php")
            .then(response => response.json())
            .then(data => {
                const tbodyCombined = document.getElementById("dataBodyCombined");
                tbodyCombined.innerHTML = "";
                let index = 0;

                data.forEach((item) => {
                    index++;
                    const bgColor = index % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";
                    const row = `<tr style="background-color: ${bgColor}">
                        <td align="center">${index}</td>
                        <td align="center">${item.no_resep}</td>
                        <td align="center">${item.product_name}</td>
                        <td align="center">${item.status}</td>
                    </tr>`;
                    tbodyCombined.innerHTML += row;
                });
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });
    }

    $(document).ready(function () {
        loadData();

        setInterval(loadData, 5000);

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
                        text: 'Silakan pilih tombol Repeat, End, atau Hold sebelum scan.'
                    });
                    return;
                }

                const exists = [...repeatList, ...endList, ...holdList].includes(scanned);
                if (exists) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sudah Ada',
                        text: `No. Resep ${scanned} sudah dimasukkan.`
                    });
                    $(this).val("").focus();
                    return;
                }

                if (currentAction === "repeat") repeatList.push(scanned);
                else if (currentAction === "end") endList.push(scanned);
                else if (currentAction === "hold") holdList.push(scanned);

                $(this).val("").focus();
                renderSelectedList();
            }
        });

        // Hapus item dari list
        $('#selectedList').on('click', '.remove-btn', function () {
            const type = $(this).data("type");
            const index = $(this).data("index");

            if (type === "repeat") repeatList.splice(index, 1);
            else if (type === "end") endList.splice(index, 1);
            else if (type === "hold") holdList.splice(index, 1);

            renderSelectedList();
        });

        // Submit semua
        $('#submitAll').on('click', function () {
            const payload = {
                repeat: repeatList,
                end: endList,
                hold: holdList
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
                        url: 'pages/ajax/submit_batch_darkroom.php',
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
                            endList = [];
                            holdList = [];
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
            } else if (key === 'y') {
                $("[data-action='hold']").click();
            }
        });
    });
</script>