<style>
    input::placeholder {
        font-style: italic;
        font-size: 12px;
    }
    #bottleQtyWrapper {
        margin-bottom: 0;
    }
    #productNameWrapper {
        margin-bottom: 0;
    }
    #productNameDisplay, #productNameDisplay_1, #productNameDisplay_2 {
        margin: -10px 0 10px !important;
    }
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }
    #tableSchedule {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        font-size: 9pt !important;
    }

    .form-group.has-error .form-control, .form-group.has-error .input-group-addon {
        background-color: #ff6347;
    }

</style>
<div class="box box-info">
    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
        <div class="box-header with-border">
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label for="no_resep" class="col-sm-2 control-label">Kartu Matching</label>
                <div class="col-sm-2">
                    <input name="no_resep" type="text" class="form-control style-ph" id="no_resep" placeholder="scan here..." required autocomplete="off" autofocus>
                </div>
            </div>
            
            <div class="form-group" id="bottleQtyWrapper">
                <label for="bottle_qty" class="col-sm-2 control-label">Bottle Quantity</label>
                <div class="col-sm-2">
                    <input type="number" class="form-control style-ph" name="bottle_qty" id="bottle_qty" placeholder="Input Bottle Quantity" required autocomplete="off">
                </div>
            </div><br>

            <div class="form-group" id="tempWrapper">
                <label for="temp" class="col-sm-2 control-label">Temp</label>
                <div class="col-sm-2">
                    <input type="text" onkeypress="return blockQuote(event)" class="form-control style-ph" name="temp" id="temp" placeholder="Input Temp" required autocomplete="off">
                </div>
            </div>
            <div class="form-group" id="productNameWrapper" style="display:  none;">
                <div class="col-sm-offset-2 col-sm-4">
                    <p id="productNameDisplay" style="font-weight: bold; color: #0073b7;"></p>
                </div>
            </div>

            <?php
                include "../koneksi.php";

                $query = mysqli_query($con, "SELECT is_scheduling FROM tbl_is_scheduling LIMIT 1");
                $row = mysqli_fetch_assoc($query);
                $showButton = ($row['is_scheduling'] == 0);
            ?>

            <?php if ($showButton): ?>
                <div class="box-footer">
                    <div class="col-sm-3">
                        <button type="submit" id="exsecute" value="save" class="btn btn-block btn-social btn-linkedin" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>
<div class="row">
    <!-- Wrapper untuk tabel utama (Schedule) -->
    <div id="scheduleWrapper" class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <li class="pull-right">
                    <button type="button"
                            id="execute_schedule"
                            class="btn btn-danger btn-sm text-black"
                            <?php if (!$showButton): ?>disabled<?php endif; ?>>
                        <strong>SUBMIT FOR SCHEDULE PROCESS ! <i class="fa fa-save"></i></strong>
                    </button>
                </li>
            </div>
            <div class="box-body">
                <table id="tableSchedule"
                       class="table table-bordered table-striped"
                       width="100%">
                    <thead class="bg-green">
                        <tr>
                            <th>
                                <div align="center">No</div>
                            </th>
                            <th>
                                <div align="center">Suffix</div>
                            </th>
                            <th>
                                <div align="center">Temp</div>
                            </th>
                            <th>
                                <div align="center">Action</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="dataBody">
                        <!-- Data akan ditampilkan di sini oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Wrapper untuk daftar “REPEAT ITEMS” (secara default tersembunyi) -->
    <div id="repeatWrapper" class="col-xs-4" style="display: none;">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">REPEAT ITEMS</h4>
            </div>
            <div class="box-body">
                <table id="tableRepeat"
                       class="table table-bordered table-striped"
                       width="100%">
                    <thead class="bg-red">
                        <tr>
                            <th><div align="center">No</div></th>
                            <th><div align="center">No. Resep</div></th>
                            <th><div align="center">Temp</th>
                            <th><div align="center">Status</div></th>
                        </tr>
                    </thead>
                    <tbody id="repeatBody">
                        <!-- Data REPEAT akan di‐inject oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    include "../koneksi.php";

    $query = mysqli_query($con, "SELECT is_scheduling FROM tbl_is_scheduling LIMIT 1");
    $row = mysqli_fetch_assoc($query);
    $is_scheduling = ($row['is_scheduling'] == 1);
?>
<?php if ($is_scheduling): ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div id="schedule_table"></div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#execute_schedule').click(function() {

            $('#exsecute').closest('.box-footer').hide();

            // 1. Atur is_scheduling
            $.ajax({
                url: 'pages/ajax/is_scheduling_btn.php',
                type: 'POST',
                success: function(response) {
                    console.log(response);

                    // 2. Lanjut ambil data schedule
                    $.ajax({
                        url: 'pages/ajax/fetch_schedule.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            
                            var schedules = JSON.stringify(response);
                            // 3. Generate schedule dengan data yang di-fetch
                            $.ajax({
                                url: 'pages/ajax/generate_schedule.php',
                                type: 'POST',
                                data: { schedules: schedules },
                                success: function(data) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Data berhasil diproses.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                    $('#schedule_table').html(data);
                                }
                            });
                        }
                    });

                }
            });
        });
    });

</script>

<script>
    $(document).ready(function () {
        // Load schedule awal
        $.ajax({
            url: 'pages/ajax/fetch_schedule.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var schedules = JSON.stringify(response);

                $.ajax({
                    url: 'pages/ajax/generate_schedule.php',
                    type: 'POST',
                    data: { schedules: schedules },
                    success: function (data) {
                        $('#schedule_table').html(data);

                        $('#undo').on('click', function () {
                            fetch('pages/ajax/undo_schedule.php', {
                                method: 'POST'
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert('Gagal undo schedule.');
                                }
                            })
                            .catch(err => {
                                console.error('Error:', err);
                                alert('Terjadi kesalahan.');
                            });
                        });
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error in fetch_schedule.php:', status, error);
                console.log('Response Text:', xhr.responseText);
            }
        });

        $('#schedule_table').on('click', '#submitForDisp', function () {
            let dataToSubmit = [];
            const selects = document.querySelectorAll('#schedule_table select');

            let allSelected = true;

            selects.forEach(select => {
                const options = select.querySelectorAll('option');
                // Hanya hitung opsi yang:
                // 1) value-nya tidak kosong (bukan placeholder)
                // 2) style.display tidak 'none'
                const visibleMachineOptions = Array.from(options).filter(opt => {
                    return opt.value !== "" && opt.style.display !== 'none';
                });

                const isMachineAvailable = visibleMachineOptions.length > 0;
                const formGroup = select.closest('.form-group');

                if (isMachineAvailable && !select.value) {
                    // Jika masih ada mesin yang “visible” dan user belum memilih → error
                    allSelected = false;
                    if (formGroup) formGroup.classList.add('has-error');
                } else {
                    if (formGroup) formGroup.classList.remove('has-error');
                }
            });

            if (!allSelected) {
                alert('Semua kolom yang memiliki mesin yang tersedia harus dipilih sebelum mengirim.');
                return;
            }

            // Jika validasi lulus, kumpulkan data
            selects.forEach(select => {
                const machine = select.value;
                const th = select.closest('th');
                const colIndex = Array.from(th.parentNode.children).indexOf(th);

                document.querySelectorAll('#schedule_table tbody tr').forEach(row => {
                    const td = row.querySelectorAll('td')[colIndex];
                    if (!td) return;

                    const span = td.querySelector('.resep-item');
                    if (span) {
                        const id_schedule = span.getAttribute('data-id');
                        const no_resep = span.getAttribute('data-resep');
                        const group = span.getAttribute('data-group');

                        if (id_schedule && machine) {
                            dataToSubmit.push({
                                id_schedule,
                                no_resep,
                                machine,
                                group
                            });
                        }
                    }
                });
            });

            console.log('Data to submit:', dataToSubmit);

            if (dataToSubmit.length > 0) {
                let all_ids = [];
                document.querySelectorAll('#schedule_table .resep-item').forEach(el => {
                    const id = el.getAttribute('data-id');
                    if (id) all_ids.push(id);
                });

                fetch('pages/ajax/submit_dispensing.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        assignments: dataToSubmit,
                        all_ids: all_ids
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        localStorage.setItem('showSuccessAlert', '1');
                        window.location.href = 'index1.php?p=Dispensing-List';
                    } else {
                        alert('Terjadi kesalahan saat menyimpan');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Terjadi error saat mengirim data');
                });
            } else {
                alert('Tidak ada resep yang diproses.');
            }
        });

        $('#schedule_table').on('change', 'select', function () {
            const allSelects = $('#schedule_table select');
            const selectedValues = [];

            // Ambil semua mesin yang sudah dipilih
            allSelects.each(function () {
                const val = $(this).val();
                if (val) {
                    selectedValues.push(val);
                }
            });

            // Untuk setiap select, sembunyikan opsi yang sudah dipilih di select lain
            allSelects.each(function () {
                const currentSelect = $(this);
                const currentValue = currentSelect.val();

                currentSelect.find('option').each(function () {
                    const option = $(this);

                    if (option.val() === '') {
                        option.show(); // selalu tampilkan opsi default
                        return;
                    }

                    // Selalu tampilkan dulu, lalu sembunyikan jika perlu
                    option.show();

                    // Sembunyikan jika sudah dipilih di select lain (kecuali dirinya sendiri)
                    if (
                        selectedValues.includes(option.val()) &&
                        option.val() !== currentValue
                    ) {
                        option.hide();
                    }
                });
            });
        });

        // $('#schedule_table').on('change', 'select', function () {
        //     const allSelects = $('#schedule_table select');
        //     const selectedValues = [];

        //     // Ambil semua mesin yang sudah dipilih
        //     allSelects.each(function () {
        //         const val = $(this).val();
        //         if (val) {
        //             selectedValues.push(val);
        //         }
        //     });

        //     // Ambil mesin yang sedang digunakan dari DB
        //     $.get('pages/ajax/get_busy_machines.php', function (busyMachines) {
        //         console.log(busyMachines);
                
        //         // Gabungkan value yang dipilih user + yang sedang digunakan di DB
        //         const allBlockedMachines = selectedValues.concat(busyMachines);

        //         allSelects.each(function () {
        //             const currentSelect = $(this);
        //             const currentValue = currentSelect.val();

        //             currentSelect.find('option').each(function () {
        //                 const option = $(this);

        //                 if (option.val() === '') {
        //                     option.show(); // selalu tampilkan opsi default
        //                     return;
        //                 }

        //                 option.show();

        //                 if (
        //                     allBlockedMachines.includes(option.val()) &&
        //                     option.val() !== currentValue
        //                 ) {
        //                     option.hide();
        //                 }
        //             });
        //         });
        //     }, 'json');
        // });

    });
</script>

<script>
    const input = document.getElementById("no_resep");
    const bottleWrapper = document.getElementById("bottleQtyWrapper");
    const tempWrapper = document.getElementById("tempWrapper");
    const productNameWrapper = document.getElementById("productNameWrapper");
    let inputBuffer = '';
    let lastTime = 0;
    let timer = null;

    // input.addEventListener("input", function (e) {
    //     const now = new Date().getTime();
    //     const delta = now - lastTime;

    //     if (delta > 10000 && input.value.length > 1) {
    //         input.value = "";
    //         inputBuffer = "";
    //         input.style.borderColor = 'red';
    //         input.style.borderWidth = '2px'; // Menambah ketebalan border
    //         input.style.animation = 'shake 0.3s ease'; // Tambahkan animasi
    //         setTimeout(() => {
    //             input.style.borderColor = '';
    //             input.style.borderWidth = ''; // Kembalikan ketebalan border
    //             input.style.animation = ''; // Hilangkan animasi
    //         }, 300);
    //         return;
    //     }
        
    //     inputBuffer = input.value;
    //     lastTime = now;

    //     clearTimeout(timer);
    //     timer = setTimeout(() => {
    //         inputBuffer = "";

    //         // ✨ Cek apakah scan diawali dengan "DR"
    //         // if (input.value.substring(0, 2).toUpperCase() === "DR") {
    //         //     productNameWrapper.innerHTML = '';

    //         //     setTimeout(setupTempListenersDR, 100);

    //         //     // DR case → tampilkan 2 bottle & 2 temp
    //         //     bottleWrapper.innerHTML = `
    //         //         <label for="bottle_qty_1" class="col-sm-2 control-label">Bottle Quantity (1)</label>
    //         //         <div class="col-sm-2">
    //         //             <input type="number" class="form-control style-ph" name="bottle_qty_1" id="bottle_qty_1" placeholder="Input Bottle Qty 1" required autocomplete="off">
    //         //         </div>
    //         //         <div class="col-sm-2">
    //         //             <input type="number" class="form-control style-ph" name="bottle_qty_2" id="bottle_qty_2" placeholder="Input Bottle Qty 2" required autocomplete="off">
    //         //         </div>
    //         //     `;
    //         //     tempWrapper.innerHTML = `
    //         //         <label for="temp_1" class="col-sm-2 control-label">Temp (1)</label>
    //         //         <div class="col-sm-2">
    //         //             <input type="text" class="form-control style-ph" name="temp_1" id="temp_1" placeholder="Input Temp 1" required autocomplete="off">
    //         //         </div>
    //         //         <div class="col-sm-2">
    //         //             <input type="text" class="form-control style-ph" name="temp_2" id="temp_2" placeholder="Input Temp 2" required autocomplete="off">
    //         //         </div>
    //         //     `;

    //         //     productNameWrapper.innerHTML = `
    //         //         <label class="col-sm-2 control-label"></label>
    //         //         <div class="col-sm-2">
    //         //             <p id="productNameDisplay_1" style="font-weight: bold; color: #0073b7;"></p>
    //         //         </div>
    //         //         <div class="col-sm-2">
    //         //             <p id="productNameDisplay_2" style="font-weight: bold; color: #0073b7;"></p>
    //         //         </div>
    //         //     `;
    //         // } else {
    //         //     // Normal case → tampilkan 1 bottle & 1 temp
    //         //     bottleWrapper.innerHTML = `
    //         //         <label for="bottle_qty" class="col-sm-2 control-label">Bottle Quantity</label>
    //         //         <div class="col-sm-2">
    //         //             <input type="number" class="form-control style-ph" name="bottle_qty" id="bottle_qty" placeholder="Input Bottle Quantity" required autocomplete="off">
    //         //         </div>
    //         //     `;
    //         //     tempWrapper.innerHTML = `
    //         //         <label for="temp" class="col-sm-2 control-label">Temp</label>
    //         //         <div class="col-sm-2">
    //         //             <input type="text" class="form-control style-ph" name="temp" id="temp" placeholder="Input Temp" required autocomplete="off">
    //         //         </div>
    //         //     `;
    //         //     productNameWrapper.innerHTML = `
    //         //         <div class="col-sm-offset-2 col-sm-4">
    //         //             <p id="productNameDisplay" style="font-weight: bold; color: #0073b7;"></p>
    //         //         </div>
    //         //     `;
    //         //     setTimeout(() => {
    //         //         listenTempWithId('#temp', '#productNameDisplay');
    //         //     }, 100);
    //         // }

    //     }, 100); // tunggu sebentar agar input selesai
    // });


    // input.addEventListener("paste", function(e) {
    //     e.preventDefault();
    // });

    // window.onload = function() {
    //     input.focus();
    // };
</script>
<script>
    $(document).ready(function() {

        loadData();
        checkRepeatItems();

        $('#exsecute').click(function(e) {
            e.preventDefault();
            var no_resep = $('#no_resep').val();

            // Cek jika ada bottle_qty_1, jika tidak pakai bottle_qty
            var bottle_qty_1 = $('#bottle_qty_1').val() ? $('#bottle_qty_1').val() : $('#bottle_qty').val();

            // Jika ada input bottle_qty_2, ambil nilainya, jika tidak, set ke 0
            var bottle_qty_2 = $('#bottle_qty_2').val() ? $('#bottle_qty_2').val() : 0;

            // Cek apakah input temp_1 dan temp_2 ada, jika ada ambil nilainya
            var temp_1 = $('#temp_1').val() ? $('#temp_1').val() : $('#temp').val();
            var temp_2 = $('#temp_2').val() ? $('#temp_2').val() : 0;

            if (!temp_1 || temp_1.trim() === '') {
                return false;
            }

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                dataType: 'json', // ✅ Tambahkan ini!
                type: 'POST',
                url: "pages/ajax/Insert_PreliminarySchedule.php",
                data: {
                    no_resep: no_resep,
                    bottle_qty_1: bottle_qty_1,
                    bottle_qty_2: bottle_qty_2,
                    temp_1: temp_1,
                    temp_2: temp_2
                },
                success: function(response) {

                    if (response.success) {
                        toastr.success(response.message);
                        $('form')[0].reset();
                        $('#no_resep').focus();

                        $('#productNameDisplay').text('');
                        $('#productNameDisplay_1').text('');
                        $('#productNameDisplay_2').text('');

                        loadData();
                        checkRepeatItems();
                    } else {
                        toastr.error(response.message);
                        if (response.errors) {
                            console.error("Detail error:", response.errors);
                        }
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengirim data!');
                }
            });
        });
    });
</script>
<script>
    function checkRepeatItems() {
        $.ajax({
            url: 'pages/ajax/GetRepeatItems.php',
            type: 'GET',
            dataType: 'json',
            success: function(repeatData) {

                if (Array.isArray(repeatData) && repeatData.length > 0) {

                    $('#scheduleWrapper')
                        .removeClass('col-xs-12')
                        .addClass('col-xs-8');

                    $('#repeatWrapper').show();

                    const $repeatBody = $('#repeatBody');
                    $repeatBody.empty();

                    repeatData.forEach((item, idx) => {
                        const nomor = idx + 1;
                        const rowHtml = `
                            <tr>
                                <td align="center">${nomor}</td>
                                <td>${item.no_resep}</td>
                                <td>${item.product_name || '-'}</td>
                                <td align="center">${item.status}</td>
                            </tr>
                        `;
                        $repeatBody.append(rowHtml);
                    });
                } else {
                    $('#scheduleWrapper')
                        .removeClass('col-xs-7')
                        .addClass('col-xs-12');

                    $('#repeatWrapper').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil data REPEAT:', error);
                // Jika error, kita tetap sembunyikan repeatWrapper
                $('#scheduleWrapper')
                    .removeClass('col-xs-7')
                    .addClass('col-xs-12');
                $('#repeatWrapper').hide();
            }
        });
    }

    let dataTableSchedule = null;
    function loadData() {
        fetch("pages/ajax/GetData_PreliminarySchedule.php")
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById("dataBody");
                const executeBtn = document.getElementById("execute_schedule");

                // ✅ Hancurkan DataTable sebelum ubah isi DOM
                if (dataTableSchedule) {
                    dataTableSchedule.destroy();
                    $('#tableSchedule').empty(); // Kosongkan seluruh tabel (thead dan tbody)
                }

                let thead = `
                    <thead class="bg-green">
                        <tr>
                            <th><div align="center">No</div></th>
                            <th><div align="center">Suffix</div></th>
                            <th><div align="center">Temp</div></th>
                            <th><div align="center">Action</div></th>
                        </tr>
                    </thead>
                `;

                let rows = '';
                data.forEach((item, index) => {
                    const isOldStyle = item.is_old_data == 1 ? 'style="background-color: pink;"' : '';
                    rows += `<tr>
                        <td ${isOldStyle} align="center">${index + 1}</td>
                        <td ${isOldStyle}>${item.no_resep}</td>
                        <td ${isOldStyle}>${item.product_name}</td>
                        <td align="center">
                            <button class="btn btn-danger btn-sm" onclick="deleteData(${item.id})" <?php if (!$showButton): ?>disabled<?php endif; ?>><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </td>
                    </tr>`;
                });

                $('#tableSchedule').html(thead + '<tbody id="dataBody">' + rows + '</tbody>');

                // ✅ Re-init setelah table sudah dibentuk kembali
                dataTableSchedule = $('#tableSchedule').DataTable({
                    pageLength: -1,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    destroy: true // tambahan untuk pastikan override instance lama
                });

                executeBtn.disabled = data.length === 0;
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });
    }
    
    function deleteData(id) {
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("pages/ajax/Delete_PreliminarySchedule.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "id=" + id
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data berhasil dihapus.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadData();
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Data tidak berhasil dihapus. ' || result.message,
                            icon: 'error'
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Oops!',
                        text: 'Terjadi kesalahan saat menghapus data.',
                        icon: 'error'
                    });
                    console.error("Gagal menghapus data:", err);
                });
            }
        });
    }

    function blockQuote(event) {
        const char = String.fromCharCode(event.which || event.keyCode);
        const blockedChars = ["'", '"', "<", ">"];
        if (blockedChars.includes(char)) {
            return false;
        }
        return true;
    }

</script>

<script>
    $(document).ready(function () {

        let tempScanTimer;
        $('#temp').on('input', function () {
            clearTimeout(tempScanTimer); // reset timer setiap kali input berubah

            const code = $(this).val().trim();

            if (code.length >= 6) {
                tempScanTimer = setTimeout(function () {
                    $.ajax({
                        url: 'pages/ajax/get_program_by_code.php',
                        method: 'GET',
                        data: { code: code },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#productNameDisplay').text(response.product_name);
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

        $('#no_resep').on('input', function () {
            clearTimeout(tempScanTimer);

            const code = $(this).val().trim();

            // const startsWithDR = code.startsWith('DR');
            // const endsWithSuffix = code.endsWith('-A') || code.endsWith('-B');

            // Jika diawali dengan DR, tapi belum diakhiri -A atau -B, jangan kirim AJAX
            // if (startsWithDR && !endsWithSuffix) return;

            tempScanTimer = setTimeout(function () {
                    $.ajax({
                    url: 'pages/ajax/get_temp_code_by_noresep.php',
                    method: 'GET',
                    data: { no_resep: code },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        console.log(response.codes.length)
                        
                        if (response.success) {
                            const codes = response.codes;
                            
                            if (codes.length === 2) {
                                $('#temp').val(codes[0]).trigger('input');
                                // $('#temp_1').val(codes[0]).trigger('input');
                                // $('#temp_2').val(codes[1]).trigger('input');
                            } else if (codes.length === 1) {                           
                                $('#temp').val(codes[0]).trigger('input');
                            }
                        }
                    },
                    error: function() {
                        console.error('Gagal mengambil data dari server.');
                    }
                });
            }, 300);

        });

        function setupTempListenersDR() {
            listenTempWithId('#temp_1', '#productNameDisplay_1');
            listenTempWithId('#temp_2', '#productNameDisplay_2');
        }

        function listenTempWithId(tempSelector, displaySelector) {
            let timer;

            $(document).off('input', tempSelector).on('input', tempSelector, function () {
                clearTimeout(timer);
                const code = $(this).val().trim();

                if (code.length >= 6) {
                    timer = setTimeout(() => {
                        $.ajax({
                            url: 'pages/ajax/get_program_by_code.php',
                            method: 'GET',
                            data: { code },
                            dataType: 'json',
                            success: function (response) {
                                if (response.status === 'success') {
                                    $(displaySelector).text(response.product_name);
                                } else {
                                    $(displaySelector).text('');
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Kode Tidak Ditemukan',
                                        text: response.message
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat mengambil data.'
                                });
                            }
                        });
                    }, 300);
                } else {
                    $(displaySelector).text('');
                }
            });
        }

    });
</script>

<script>
    ['bottle_qty', 'bottle_qty_1', 'bottle_qty_2'].forEach(function(id) {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function () {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        }
    });
</script>
