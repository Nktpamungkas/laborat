<?php session_start(); ?>
<?php
    include "../koneksi.php"; // pastikan $con adalah koneksi mysqli

    // Ambil identitas user sekarang (sesuaikan sumbernya)
    $meUser = $_SESSION['userLAB'] ?? ($_SESSION['userLAB'] ?? 'unknown');
    $meIp   = $_SERVER['REMOTE_ADDR'] ?? '';

    // --- Ambil data terakhir dari log_preliminary
    $sqlCekLog   = "SELECT * FROM log_preliminary ORDER BY id DESC LIMIT 1";
    $resultCekLog = mysqli_query($con, $sqlCekLog);
    if (!$resultCekLog) {
        die("Query gagal: " . mysqli_error($con));
    }
    $lastCekLog = mysqli_fetch_assoc($resultCekLog);

    // Normalisasi status terakhir (jika ada)
    $lastStatusCekLog = strtolower(trim($lastCekLog['status'] ?? ''));

    // Apakah baris terakhir itu milik kita sendiri?
    $isSelf = false;
    if ($lastCekLog) {
        $lastUser = strtolower(trim($lastCekLog['username'] ?? ''));
        $thisUser = strtolower(trim($meUser));
        $isSameIp = empty($lastCekLog['ip_comp']) ? true : ($lastCekLog['ip_comp'] === $meIp); // opsional
        $isSelf   = ($lastUser === $thisUser) && $isSameIp;
    }

    // --- Aturan akses diperbaiki:
    // - Jika belum ada data  → BOLEH
    // - Jika status terakhir = 'keluar dari halaman' → BOLEH
    // - Jika pemegang = diri sendiri (apa pun statusnya kecuali "keluar dari halaman") → BOLEH
    // - Selain itu → BLOKIR
    $bolehAkses = false;
    if (!$lastCekLog) {
        $bolehAkses = true;
    } elseif ($lastStatusCekLog === 'keluar dari halaman') {
        $bolehAkses = true;
    } elseif ($isSelf) {
        $bolehAkses = true;
    }

    if (!$bolehAkses) {
        http_response_code(423); // Locked
        $pemegang = htmlspecialchars($lastCekLog['username'] ?? '-', ENT_QUOTES);
        $status   = htmlspecialchars($lastCekLog['status'] ?? '-', ENT_QUOTES);
        $waktu    = htmlspecialchars($lastCekLog['creationdatetime'] ?? '-', ENT_QUOTES);

        echo "<center>
                <h3>Halaman sedang dipakai oleh <b>{$pemegang}</b>.</h3>
                <p>Status terakhir: <b>{$status}</b> pada {$waktu}.</p>
                <p>Silakan coba lagi nanti.</p>
                <h5>
                    Jika anda yakin tidak ada yg akses halaman ini selain anda, silakan
                    <a href='index1.php?p=clear_lock&confirm=yes' style='color:red;'>klik di sini</a>
                    untuk menghapus Active Lock.
                </h5>
            </center>";
        exit;
    }

    // --- Jika lolos sampai sini, boleh lanjut render halaman
?>
<script>
    // --- fungsi kirim status ke server
    let lastSent = 0;
    function updateStatus(status) {
        console.log("Status:", status);
        const now = Date.now();
        if (status === "aktif di tab ini" && now - lastSent < 1200) return;
        lastSent = now;
        fetch("pages/ajax/update_status_preliminary.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "status=" + encodeURIComponent(status)
        });
    }

    // === Status yang dipakai (Bahasa Indonesia) ===
    // "aktif di tab ini"      : user aktif & tab terlihat
    // "tidak di tab ini"      : tab tidak terlihat (pindah tab / jendela tersembunyi)
    // "jendela diminimalkan"  : heuristic saat jendela diduga minimize (opsional, tidak selalu terdeteksi)
    // "diam (idle)"           : tidak ada aktivitas > 5 menit
    // "keluar dari halaman"   : halaman ditutup/refresh/navigasi

    // --- deteksi tab terlihat / tidak terlihat
    document.addEventListener("visibilitychange", function () {
        if (document.hidden) {
        updateStatus("tidak di tab ini"); // tab disembunyikan (bisa karena pindah tab / minimize)
        } else {
        updateStatus("aktif di tab ini"); // tab kembali terlihat
        }
    });

    // --- deteksi aktivitas user di tab (reset idle)
    let activityTimeout;
    function userIsActive() {
        clearTimeout(activityTimeout);
        // Saat ada aktivitas, tandai aktif (kalau tab terlihat, ini yang paling relevan)
        updateStatus("aktif di tab ini");

        // Kalau 5 menit tidak ada aktivitas, ubah jadi idle
        activityTimeout = setTimeout(() => {
        updateStatus("diam (idle)");
        }, 5 * 60 * 1000);
    }

    ["mousemove", "keydown", "click", "input", "wheel", "touchstart"].forEach(evt => {
        document.addEventListener(evt, userIsActive, { passive: true });
    });

    // --- Heuristic: coba deteksi minimize (opsional, tidak selalu valid lintas browser)
    // Beberapa browser akan memicu resize dengan ukuran sangat kecil/0 saat minimize.
    // Jika tidak terjadi di browser-mu, event ini hanya akan terabaikan.
    let minimizeTimer = null;
    window.addEventListener("resize", () => {
        clearTimeout(minimizeTimer);
        minimizeTimer = setTimeout(() => {
        const w = window.innerWidth;
        const h = window.innerHeight;
        // Ambang batas kecil → anggap diminimalkan
        if (document.hidden && (w === 0 || h === 0 || (w < 10 && h < 10))) {
            updateStatus("jendela diminimalkan");
        }
        }, 150);
    });

    // --- EXIT HANDLING (lebih andal)
    let exitSent = false;
    function sendExit() {
        if (exitSent) return;
        exitSent = true;

        // siapkan payload
        const body = "status=" + encodeURIComponent("keluar dari halaman");

        // 1) coba kirim via sendBeacon (paling stabil saat unload)
        let ok = false;
        try {
            if (navigator.sendBeacon) {
                const blob = new Blob([body], { type: "application/x-www-form-urlencoded" });
                ok = navigator.sendBeacon("pages/ajax/update_status_preliminary.php", blob);
            }
        } catch (_) {}

        // 2) fallback: fetch keepalive
        if (!ok) {
            try {
                fetch("pages/ajax/update_status_preliminary.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body,
                    keepalive: true
                });
            } catch (_) {}
        }
    }

    // --- saat halaman ditutup/refresh/navigasi
    // gunakan tiga jalur untuk keandalan lintas browser
    window.addEventListener("pagehide", (e) => {
        // jika masuk bfcache (persisted), jangan anggap benar-benar keluar
        if (!e.persisted) sendExit();
    });
    window.addEventListener("beforeunload", sendExit);
    window.addEventListener("unload", sendExit);

    // --- jalankan pertama kali saat halaman dibuka
    window.onload = userIsActive;
</script>


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
    .table#schedule-mesin>tbody>tr>td {
        padding: 5px;
    }
    th.sticky-col,
    td.sticky-col {
        position: sticky;
        left: -1px;
        background-color: white;
        z-index: 2;
        box-shadow: inset -1px 0 #ccc;
    }
    thead tr:nth-child(2) th.sticky-col {
        z-index: 4;
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

<?php if ($showButton): ?>
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
<?php endif; ?>

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
                                        localStorage.setItem('hideTableSchedule', 'true');
                                        localStorage.setItem('skipRepeatCheck', 'true');
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
                        if (/session/i.test(data.message)) {
                            window.location.href = "/laborat/login";
                        }
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

        const hideTable = localStorage.getItem('hideTableSchedule');
        const skipRepeat = localStorage.getItem('skipRepeatCheck');

        if (hideTable === 'true') {
            $('#scheduleWrapper').hide();
            $('#repeatWrapper').hide();
            localStorage.removeItem('hideTableSchedule');
        }

        if (skipRepeat === 'true') {
            localStorage.removeItem('skipRepeatCheck'); // ✅ Hapus supaya tidak permanen
            window.skipRepeatCheck = true;
        }
        
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
                dataType: 'json',
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
                        if (/session/i.test(response.message)) {
                            window.location.href = "/laborat/login";
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
    let dataTableRepeat = null;
    function checkRepeatItems() {
        if (window.skipRepeatCheck) {
            $('#repeatWrapper').hide();
            $('#scheduleWrapper')
                .removeClass('col-xs-8')
                .addClass('col-xs-12');
            return;
        }
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

                    if (dataTableRepeat) {
                        dataTableRepeat.destroy();
                    }

                    $('#tableRepeat').html(`
                        <thead class="bg-red">
                            <tr>
                                <th><div align="center">No</div></th>
                                <th><div align="center">No. Resep</div></th>
                                <th><div align="center">Temp</div></th>
                                <th><div align="center">Status</div></th>
                            </tr>
                        </thead>
                        <tbody id="repeatBody"></tbody>
                    `);

                    const $repeatBody = $('#repeatBody');
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

                    requestAnimationFrame(() => {
                        Promise.resolve().then(() => {
                            dataTableRepeat = $('#tableRepeat').DataTable({
                                pageLength: 5,
                                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                                destroy: true,
                                pagingType: "simple_numbers",
                                language: {
                                paginate: {
                                    previous: '<i class="fa fa-angle-left"></i>',
                                    next: '<i class="fa fa-angle-right"></i>'
                                }
                                }
                            });
                        });
                    });
                } else {
                    // Tidak ada data repeat
                    $('#scheduleWrapper')
                        .removeClass('col-xs-8')
                        .addClass('col-xs-12');

                    $('#repeatWrapper').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil data REPEAT:', error);

                $('#scheduleWrapper')
                    .removeClass('col-xs-8')
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
                        <td ${isOldStyle}>${item.no_resep} - ${item.jenis_matching}</td>
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
