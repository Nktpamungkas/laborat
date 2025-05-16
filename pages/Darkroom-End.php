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
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <div style="display: flex; flex-direction: column;">
                        <label for="scanInputEnd">End</label>
                        <input type="text" id="scanInputEnd" placeholder="Scan here to end..." class="form-control" style="width: 250px;">
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <label for="scanInputRepeat">Repeat</label>
                        <input type="text" id="scanInputRepeat" placeholder="Scan here to repeat..." class="form-control" style="width: 250px;">
                    </div>
                </div>

                <!-- Container for tables with display flex -->
                <div id="tableContainer" style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    
                    <!-- DARK ROOM Poly Table -->
                    <div id="polyTableWrapper" style="flex: 1; min-width: 300px; display: block;">
                        <h4 id="polyHeader" class="text-center"><strong>DARK ROOM POLY</strong></h4>
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
                                    <th>
                                        <div align="center">No. Mesin</div>
                                    </th>
                                    <th>
                                        <div align="center">Status</div>
                                    </th>
                                    <th>
                                        <div align="center">Dark Room End</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="dataBodyPoly">
                                <!-- Data will be displayed here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- DARK ROOM Cotton Table -->
                    <div id="cottonTableWrapper" style="flex: 1; min-width: 300px; display: block;">
                        <h4 id="cottonHeader" class="text-center"><strong>DARK ROOM COTTON</strong></h4>
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
                                    <th>
                                        <div align="center">No. Mesin</div>
                                    </th>
                                    <th>
                                        <div align="center">Status</div>
                                    </th>
                                    <th>
                                        <div align="center">Dark Room End</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="dataBodyCotton">
                                <!-- Data will be displayed here -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        loadData();

        $('#scanInputEnd').on('keypress', function (e) {
            if (e.which === 13) { // Enter key
                const noResep = $(this).val().trim();
                if (noResep !== "") {
                    updateStatusEnd(noResep);
                    $(this).val("");
                }
            }
        });

        $('#scanInputRepeat').on('keypress', function (e) {
            if (e.which === 13) {
                const noResep = $(this).val().trim();
                if (noResep !== "") {
                    updateStatusRepeat(noResep);
                    $(this).val("");
                }
            }
        });

        function updateStatusEnd(noResep) {
            $.ajax({
                url: 'pages/ajax/scan_end_darkroom_update_status.php',
                method: 'POST',
                data: { no_resep: noResep },
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

        function updateStatusRepeat(noResep) {
            $.ajax({
                url: 'pages/ajax/scan_repeat_darkroom_update_status.php',
                method: 'POST',
                data: { no_resep: noResep },
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
    });
</script>
<script>
    function loadData() {
        fetch("pages/ajax/GetData_DispensingList.php")
            .then(response => response.json())
            .then(data => {
                const tbodyPoly = document.getElementById("dataBodyPoly");
                const tbodyCotton = document.getElementById("dataBodyCotton");
                const polyTableWrapper = document.getElementById("polyTableWrapper");
                const cottonTableWrapper = document.getElementById("cottonTableWrapper");

                tbodyPoly.innerHTML = "";
                tbodyCotton.innerHTML = "";

                let hasPolyData = false;
                let hasCottonData = false;

                let polyIndex = 0;
                let cottonIndex = 0;

                data.forEach((item) => {
                    let row = "";
                    let bgColor = "";

                    if (item.keterangan && item.keterangan.trim().toUpperCase() === "POLY") {
                        polyIndex++;
                        const groupIndex = Math.floor((polyIndex - 1) / 16);
                        const rowNumber = (polyIndex - 1) % 16 + 1;
                        bgColor = groupIndex % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";
                        
                        row = `<tr style="background-color: ${bgColor}">
                            <td align="center">${rowNumber}</td>
                            <td align="center">${item.no_resep}</td>
                            <td align="center">${item.product_name}</td>
                            <td align="center">${item.no_machine}</td>
                            <td align="center">${item.status}</td>
                            <td align="center">${item.darkroom_end ?? '-'}</td>
                        </tr>`;
                        tbodyPoly.innerHTML += row;
                        hasPolyData = true;

                    } else if (item.keterangan && item.keterangan.trim().toUpperCase() === "COTTON") {
                        cottonIndex++;
                        const groupIndex = Math.floor((cottonIndex - 1) / 16);
                        const rowNumber = (cottonIndex - 1) % 16 + 1;
                        bgColor = groupIndex % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";

                        row = `<tr style="background-color: ${bgColor}">
                            <td align="center">${rowNumber}</td>
                            <td align="center">${item.no_resep}</td>
                            <td align="center">${item.product_name}</td>
                            <td align="center">${item.no_machine}</td>
                            <td align="center">${item.status}</td>
                            <td align="center">${item.darkroom_end ?? '-'}</td>
                        </tr>`;
                        tbodyCotton.innerHTML += row;
                        hasCottonData = true;
                    }
                });

                // Sembunyikan jika tidak ada data
                polyTableWrapper.style.display = hasPolyData ? "block" : "none";
                cottonTableWrapper.style.display = hasCottonData ? "block" : "none";

                // Layout jika hanya satu tabel
                const tableContainer = document.getElementById("tableContainer");
                if (hasPolyData && hasCottonData) {
                    tableContainer.style.display = "flex";
                    tableContainer.style.justifyContent = "space-between";
                } else {
                    tableContainer.style.display = "block";
                    tableContainer.style.justifyContent = "center";
                }
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });
    }
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
