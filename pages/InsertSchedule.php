
<style>
    #productNameWrapper {
        margin-bottom: 0;
    }
    #productNameDisplay {
        margin: -15px 0 10px !important;
    }
    .table>tbody>tr>td {
        padding: 5px;
    }
    .freeze-column {
        position: sticky;
        left: -1px;
        background-color: white;
        z-index: 2;
        border-right: 1px solid #ddd;
    }
    .table thead .freeze-column {
        z-index: 3;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <h4 id="cottonHeader" class="text-center"><strong>INSERT SCHEDULE</strong></h4>

        <div style="margin-bottom: 5px;">
            <button class="btn btn-success" data-toggle="modal" data-target="#insertModal">
                <i class="fa fa-indent" aria-hidden="true"></i> Insert Schedule
            </button>
        </div>

        <div class="box">
            <div id="schedule_table"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="insertForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="insertModalLabel">Insert Schedule</h4>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label for="no_resep">No Resep</label>
            <input type="text" class="form-control" id="no_resep" name="no_resep" placeholder="Scan here" required>
          </div>

          <div class="form-group" id="machineSelectGroup">
            <label for="no_machine">No Mesin</label>
            <select class="form-control" id="no_machine" name="no_machine" required>
              <option value="">Pilih Mesin</option>
            </select>
          </div>

          <div class="form-group" id="tempWrapper">
            <label for="temp">Temp</label>
            <input type="text" class="form-control style-ph" name="temp" id="temp" placeholder="Input Temp" required autocomplete="off">
          </div>

          <div class="form-group" id="productNameWrapper" style="display: none;">
            <p id="productNameDisplay" style="font-weight: bold; color: #0073b7;"></p>
          </div>

          <div class="form-group" id="bottleQtyWrapper">
            <label for="bottle_qty">Bottle Quantity</label>
            <input type="number" class="form-control style-ph" name="bottle_qty" id="bottle_qty" placeholder="Input Bottle Quantity" required autocomplete="off">
          </div>

          <input type="hidden" name="group" id="group">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let tempScanTimer;
    $('#temp').on('input', function () {
        clearTimeout(tempScanTimer); // reset timer setiap kali input berubah

        const code = $(this).val().trim();
        const machine = $('#no_machine').val().trim();

        if (code.length >= 6) {
            tempScanTimer = setTimeout(function () {
                $.ajax({
                    url: 'pages/ajax/get_program_by_code_for_insert.php',
                    method: 'GET',
                    data: { code: code, machine: machine },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        
                        if (response.status === 'success') {
                            $('#productNameDisplay').text(response.product_name);
                            $('#group').val(response.group)
                            $('#productNameWrapper').show();
                        } else {
                            $('#productNameDisplay').text('');
                            Swal.fire({
                                icon: 'error',
                                title: 'Kode Tidak Ditemukan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengambil data.'
                        });
                    }
                });
            }, 300);
        } else {
            $('#productNameDisplay').text('');
        }
    });
</script>

<script>
    $('#insertForm').on('submit', function(e) {
        e.preventDefault();

        const noResep = $('#no_resep').val().trim();
        const noMachine = $('#no_machine').val().trim();
        const temp = $('#temp').val().trim();
        const bottleQty = $('#bottle_qty').val().trim();
        const group = $('#group').val().trim();

        if (!noResep || !noMachine || !temp || !bottleQty) {
            Swal.fire({
                icon: 'warning',
                title: 'Form belum lengkap',
                text: 'Isi semua field terlebih dahulu.'
            });
            return;
        }

        $.ajax({
            url: 'pages/ajax/insert_schedule.php',
            method: 'POST',
            data: {
                no_resep: noResep,
                no_machine: noMachine,
                temp: temp,
                bottle_qty: bottleQty,
                id_group: group
            },
            dataType: 'json',
            success: function(response) {
                try {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1200,
                            showConfirmButton: false
                        });
                        $('#insertModal').modal('hide');
                        resetInsertForm();
                        loadScheduleTable();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                } catch (err) {
                    console.error("Parse error:", err);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal menyisipkan data.'
                });
            }
        });
    });

    let machineCounts = {};

    function loadMachineOptions() {
        $.ajax({
            url: 'pages/ajax/get_machines.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const machines = response.machines;
                machineCounts = response.machine_counts || {};
                const $select = $('#no_machine');
                const $selectGroup = $('#machineSelectGroup');

                if (machines.length === 0) {
                    $selectGroup.hide();
                    return;
                }

                $selectGroup.show();
                $select.empty().append('<option value="">Pilih Mesin</option>');

                machines.forEach(machine => {
                    $select.append(`<option value="${machine}">${machine}</option>`);
                });
            },
            error: function(xhr) {
                console.error("Gagal memuat daftar mesin:", xhr.responseText);
            }
        });
    }

    $('#no_machine').on('change', function () {
        var selectedMachine = $(this).val();

        if (selectedMachine !== '') {
            $('#temp').prop('disabled', false);

            // Hitung sisa slot
            const used = machineCounts[selectedMachine] || 0;
            const remaining = 24 - used;

            $('#bottle_qty').prop('disabled', false)
                            .attr('max', remaining)
                            .attr('placeholder', `Maksimal ${remaining}`)
                            .val('');
        } else {
            $('#temp').prop('disabled', true);
            $('#temp').val('');
            $('#bottle_qty').val('').prop('disabled', true).removeAttr('max');
        }
    });

    $(document).ready(function () {
        $('#insertModal').on('shown.bs.modal', function () {
            loadMachineOptions();
        });

        $('#insertModal').on('hidden.bs.modal', function () {
            resetInsertForm();
        });
    });

    function resetInsertForm() {
        $('#insertForm')[0].reset();
        $('#productNameDisplay').text('');
        $('#productNameWrapper').hide();
        $('#group').val('');
        $('#temp').prop('disabled', true);
        $('#bottle_qty').val('').prop('disabled', true).removeAttr('max').attr('placeholder', 'Input Bottle Quantity');
    }
</script>

<script>
    function loadScheduleTable() {
        $.ajax({
            url: 'pages/ajax/fetch_schedule_insert.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const { data, maxPerMachine, tempListMap } = response;
                // console.log(response);
                
                let html = `
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped align-middle text-center" style="table-layout: auto; width: 100%; margin-bottom: 10px;">
                            <colgroup>
                                <col style="min-width: 50px;"> <!-- Kolom No -->
                `;

                const machineCount = Object.keys(data).length;
                const colWidth = Math.floor(97 / machineCount);

                Object.keys(data).forEach(() => {
                    // html += `<col style="width: ${colWidth}%;">`;
                    html += `<col style="min-width: 300px;">`;
                });

                html += `</colgroup><thead class="table-dark">`;

                // Row 1: Judul Mesin
                html += `<tr><th rowspan="2" class="freeze-column">${( Object.keys(data).length === 0) ? 'No Data' : 'No.'}</th>`;
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
                    html += `<tr><td class="freeze-column">${i + 1}</td>`;
                    Object.values(data).forEach(rows => {
                        const cell = rows[i];
                        if (cell) {
                            html += `<td>
                                <div style="display: flex; justify-content: space-around;">
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
        loadScheduleTable();

        $('#temp').prop('disabled', true);

        $('#no_machine').on('change', function () {
            var selectedMachine = $(this).val();

            if (selectedMachine !== '') {
                $('#temp').prop('disabled', false);
            } else {
                $('#temp').prop('disabled', true);
                $('#temp').val('');
            }
        });
    });
</script>