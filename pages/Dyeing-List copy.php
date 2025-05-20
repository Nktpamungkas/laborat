
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div id="schedule_table"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $.ajax({
        url: 'pages/ajax/fetch_dyeing_list.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            const schedules = JSON.stringify(response);

            $.ajax({
                url: 'pages/ajax/generate_dyeing.php',
                type: 'POST',
                dataType: 'json',
                data: { schedules: schedules },
                success: function(data) {
   
                    const { columns, groupInfo, scheduleData, maxRows } = data;

                    let html = `
                        <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
                            <table class="table table-bordered table-striped align-middle text-center" style="table-layout: fixed; min-width: 1200px; width: 100%;">
                                <colgroup>
                                    <col style="width: 3%;"> <!-- Kolom No -->
                    `;
                    columns.forEach(() => {
                        html += `<col style="width: ${Math.floor(95 / columns.length)}%;">`; // Bagi sisa lebarnya
                    });
                    html += `
                                </colgroup>
                                <thead class="table-dark">
                                    <tr>
                                        <th rowspan="2">No</th>
                    `;

                    // Baris 1: Mesin
                    columns.forEach(col => {
                        html += `<th>Mesin ${col.machine || '-'}</th>`;
                    });

                    html += `</tr><tr>`;

                    // Baris 2: Group + Product
                    columns.forEach(col => {
                        const groupName = col.group;
                        const productNames = groupInfo[groupName] || '';
                        html += `<th><small>[${productNames}]</small></th>`;
                        // html += `<th>
                        //         <div style="display: flex; flex-direction: column; justify-content: center; height: 100%;">
                        //             <span>${groupName}</span>
                        //             <small>[${productNames}]</small>
                        //         </div>
                        //         </th>`;
                    });

                    html += `</tr></thead><tbody>`;

                    for (let i = 0; i < maxRows; i++) {
                        html += `<tr><td>${i + 1}</td>`;

                        columns.forEach(col => {
                            const group = col.group;
                            const chunkIndex = col.chunk_index;
                            const cell = scheduleData[i] &&
                                        scheduleData[i][group] &&
                                        scheduleData[i][group][chunkIndex];

                            if (cell) {
                                html += `<td>
                                        <div style="display: flex; justify-content: space-evenly;">
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
                }
            });
        }
    });
</script>
