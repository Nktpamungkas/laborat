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
        transform: translateY(20px) scale(0.85);
        transition: opacity 0.5s ease, transform 0.5s ease;
        pointer-events: none; /* agar tidak bisa diklik saat invisible */
        will-change: transform, opacity;
    }

    #actionButtons.show {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }
    #actionButtons button {
        line-height: 0.8;
        padding: 8px 12px;
        min-width: 80px;
        height: 40px;
        text-align: center;
        font-size: 11px;
    }

    #actionButtons button strong {
        font-size: 16px;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">

                <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <!-- Input Scan -->
                    <div style="display: flex; flex-direction: column;">
                        <label for="scanInput">Scan Resep</label>
                        <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="width: 250px;">
                    </div>

                    <!-- Tombol YES/NO -->
                    <div id="actionButtons" style="display: none; flex-direction: row; gap: 5px; margin-top: 22px;">
                        <button id="btnYes" class="btn btn-success">F1<br><strong>YES</strong></button>
                        <button id="btnNo" class="btn btn-danger">F2<br><strong>NO</strong></button>
                    </div>
                </div>


                <div id="tableContainer" style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    
                    <div id="tableWrapper" style="width: 100%;">
                        <h4 class="text-center"><strong>DARK ROOM END</strong></h4>
                        <table id="tableCombined" class="table table-bordered" width="100%">
                            <thead class="bg-green">
                                <tr>
                                    <th><div align="center">No</div></th>
                                    <th><div align="center">No. Resep</div></th>
                                    <th><div align="center">Temp</div></th>
                                    <th><div align="center">Status</div></th>
                                    <th><div align="center">Dark Room End</div></th>
                                </tr>
                            </thead>
                            <tbody id="dataBodyCombined">
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
    $(document).ready(function () {
        let currentNoResep = ""; // Simpan resep yang discan
        loadData();

        // $('#scanInput').on('keypress', function (e) {
        //     if (e.which === 13) { // Enter key
        //         currentNoResep = $(this).val().trim();
        //         if (currentNoResep !== "") {
        //             $('#actionButtons').show();
        //             $(this).prop('disabled', true); // Disable input sementara
        //         }
        //     }
        // });
        $('#scanInput').on('keypress', function (e) {
            if (e.which === 13) {
                currentNoResep = $(this).val().trim();
                if (currentNoResep !== "") {
                    $.ajax({
                        url: 'pages/ajax/check_status_darkroom.php',
                        method: 'POST',
                        data: { no_resep: currentNoResep },
                        dataType: 'json',
                        success: function (res) {
                            if (res.status == "in_progress_darkroom") {
                                // $('#actionButtons').show();
                                setTimeout(() => {
                                    $('#actionButtons').addClass('show');
                                }, 500);
                                $('#scanInput').prop('disabled', true);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Tidak Bisa Diproses',
                                    text: `No. Resep ini belum bisa di proses saat ini.`,
                                });
                                resetInput();
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat memeriksa status resep.',
                            });
                            resetInput();
                        }
                    });
                }
            }
        });

        $('#btnYes').on('click', function () {
            if (currentNoResep !== "") {
                Swal.fire({
                    title: 'Confirmation',
                    text: `Is it already OK?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Process',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatusEnd(currentNoResep);
                        resetInput();
                    }
                });
            }
        });

        $('#btnNo').on('click', function () {
            if (currentNoResep !== "") {
                Swal.fire({
                    title: 'Confirmation',
                    text: `You want to REPEAT from the start?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Process',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatusRepeat(currentNoResep);
                        resetInput();
                    }
                });
            }
        });

        function resetInput() {
            $('#scanInput').val("").prop('disabled', false).focus();
            // $('#actionButtons').hide();
            $('#actionButtons').removeClass('show');
            currentNoResep = "";
        }

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
                        text: `No. Resep ${noResep} telah diproses sebagai END.`,
                        timer: 1200,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui status (END).',
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
                        text: `No. Resep ${noResep} telah diproses sebagai REPEAT.`,
                        timer: 1200,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memperbarui status (REPEAT).',
                    });
                }
            });
        }

        $(document).on('keydown', function (e) {
            if ($('#actionButtons').is(':visible')) {
                if (e.key === "F1") {
                    e.preventDefault();
                    $('#btnYes').click();
                } else if (e.key === "F2") {
                    e.preventDefault();
                    $('#btnNo').click();
                }
            }
        });
    });
</script>
<script>
    function loadData() {
        fetch("pages/ajax/GetData_DarkroomEndList.php")
            .then(response => response.json())
            .then(data => {
                const tbodyCombined = document.getElementById("dataBodyCombined");
                tbodyCombined.innerHTML = "";

                let index = 0;
                const now = new Date();

                data.forEach((item) => {
                    index++;
                    const rowNumber = index;
                    const bgColor = index % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220)";

                    // let warningText = "-";
                    // if (item.darkroom_end) {
                    //     const startTime = new Date(item.darkroom_end);
                    //     const diffMs = now - startTime;
                    //     const diffMins = diffMs / 1000 / 60;

                    //     warningText = diffMins > 90
                    //         ? `<span class="blink-warning">⚠ ${item.darkroom_end}</span>`
                    //         : item.darkroom_end;
                    // }

                    const row = `<tr style="background-color: ${bgColor}">
                        <td align="center">${rowNumber}</td>
                        <td align="center">${item.no_resep}</td>
                        <td align="center">${item.product_name}</td>
                        <td align="center">${item.status}</td>
                        <td align="center">${item.darkroom_end ?? '-'}</td>
                    </tr>`;

                    tbodyCombined.innerHTML += row;
                });
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
