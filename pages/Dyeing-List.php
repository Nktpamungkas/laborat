<style>
    .blink-warning {
        animation: blink 1s infinite;
        color: red;
        font-weight: bold;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
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
    // $.ajax({
    //     url: 'pages/ajax/fetch_dyeing_list.php',
    //     type: 'GET',
    //     dataType: 'json',
    //     success: function(response) {
    //         const schedules = JSON.stringify(response);
    //         console.log(schedules);       

    //         $.ajax({
    //             url: 'pages/ajax/generate_dyeing_copy.php',
    //             type: 'POST',
    //             dataType: 'json',
    //             data: { schedules: schedules },
    //             success: function(data) {
    //                 // console.log(data);
                    
    //                 const { columns, groupInfo, scheduleData, maxRows } = data;

    //                 let html = `
    //                     <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
    //                         <table class="table table-bordered table-striped align-middle text-center" style="table-layout: fixed; min-width: 1200px; width: 100%;">
    //                             <colgroup>
    //                                 <col style="width: 3%;"> <!-- Kolom No -->
    //                 `;
    //                 columns.forEach(() => {
    //                     html += `<col style="width: ${Math.floor(95 / columns.length)}%;">`; // Bagi sisa lebarnya
    //                 });
    //                 html += `
    //                             </colgroup>
    //                             <thead class="table-dark">
    //                                 <tr>
    //                                     <th rowspan="2">No</th>
    //                 `;

    //                 // Baris 1: Mesin
    //                 columns.forEach(col => {
    //                     html += `
    //                             <th>
    //                                 Mesin ${col.machine || '-'} <br>
    //                                 <input type="text" class="form-control scan-resep" data-no_machine="${col.machine}" placeholder="Scan here...">
    //                             </th>`;
    //                 });

    //                 html += `</tr><tr>`;

    //                 // Baris 2: Group + Product
    //                 columns.forEach(col => {
    //                     const groupName = col.group;
    //                     const productNames = groupInfo[groupName] || '';
    //                     html += `<th><small>[${productNames}]</small></th>`;
    //                 });

    //                 html += `</tr></thead><tbody>`;

    //                 for (let i = 0; i < maxRows; i++) {
    //                     html += `<tr><td>${i + 1}</td>`;

    //                     columns.forEach(col => {
    //                         const group = col.group;
    //                         const chunkIndex = col.chunk_index;
    //                         const cell = scheduleData[i] &&
    //                                     scheduleData[i][group] &&
    //                                     scheduleData[i][group][chunkIndex];

    //                         if (cell) {
    //                             html += `<td>
    //                                     <div style="display: flex; justify-content: space-evenly;">
    //                                         <span>${cell.no_resep}</span>
    //                                         <span class="text-muted">${cell.status}</span>
    //                                     </div>
    //                                 </td>`;
    //                         } else {
    //                             html += `<td></td>`;
    //                         }
    //                     });

    //                     html += `</tr>`;
    //                 }

    //                 html += `</tbody></table></div>`;

    //                 $('#schedule_table').html(html);
    //             }
    //         });
    //     }
    // });
</script>
<script>
    function loadScheduleTable() {
        $.ajax({
            url: 'pages/ajax/generate_dyeing.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const { data, maxPerMachine, tempListMap } = response;
                console.log(response);
                
                let html = `
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped align-middle text-center" style="table-layout: fixed; min-width: 2560px; width: 100%;">
                            <colgroup>
                                <col style="width: 3%;"> <!-- Kolom No -->
                `;

                const machineCount = Object.keys(data).length;
                const colWidth = Math.floor(97 / machineCount);

                Object.keys(data).forEach(() => {
                    html += `<col style="width: ${colWidth}%;">`;
                });

                html += `</colgroup><thead class="table-dark">`;

                // Row 1: Judul Mesin
                html += `<tr><th rowspan="2">${( Object.keys(data).length === 0) ? 'No Data' : 'No.'}</th>`;
                Object.keys(data).forEach(machine => {
                    html += `<th>Mesin ${machine}</th>`;
                });
                html += `</tr>`;

                // Row 2: Temp List
                html += `<tr>`;
                Object.keys(data).forEach(machine => {
                    const tempList = tempListMap[machine]?.join(' ; ') || '-';
                    html += `<th><small class="text-danger">${tempList}</small></th>`;
                });
                html += `</tr>`;

                html += `</thead><tbody>`;

                for (let i = 0; i < maxPerMachine; i++) {
                    html += `<tr><td>${i + 1}</td>`;
                    Object.values(data).forEach(rows => {
                        const cell = rows[i];
                        if (cell) {
                            const now = new Date();
                            let warningClass = '';

                            if (cell.dyeing_start) {
                                const startTime = new Date(cell.dyeing_start);
                                const diffMs = now - startTime;
                                const diffMins = diffMs / 1000 / 60;
                                const processTime = parseFloat(cell.waktu) || 0;

                                if (diffMins > (120 + processTime)) {
                                    warningClass = 'blink-warning';
                                }
                            }

                            html += `<td>
                                <div style="display: flex; justify-content: space-around;" class="${warningClass}">
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
            },
            error: function(xhr, status, error) {
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