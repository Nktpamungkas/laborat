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
            </div>
            <div class="form-group" id="tempWrapper">
                <label for="temp" class="col-sm-2 control-label">Temp</label>
                <div class="col-sm-2">
                    <input type="text" onkeypress="return blockQuote(event)" class="form-control style-ph" name="temp" id="temp" placeholder="Input Temp" required autocomplete="off">
                </div>
            </div>
            <div class="form-group" id="productNameWrapper">
                <div class="col-sm-offset-2 col-sm-4">
                    <p id="productNameDisplay" style="font-weight: bold; color: #0073b7;"></p>
                </div>
            </div>
            <div class="box-footer">
                <div class="col-sm-3">
                    <button type="submit" id="exsecute" value="save" class="btn btn-block btn-social btn-linkedin" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <li class="pull-right">
                    <button type="button" id="exsecute_schedule" class="btn btn-danger btn-sm text-black"><strong>SUBMIT FOR SCHEDULE PROCESS ! <i class="fa fa-save"></i></strong></button>
                </li>
            </div>
            <div class="box-body">
                <table id="tablee" class="table" width="100%">
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
                        <!-- Data akan ditampilkan di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    const input = document.getElementById("no_resep");
    const bottleWrapper = document.getElementById("bottleQtyWrapper");
    const tempWrapper = document.getElementById("tempWrapper");
    const productNameWrapper = document.getElementById("productNameWrapper");
    let inputBuffer = '';
    let lastTime = 0;
    let timer = null;

    input.addEventListener("input", function (e) {
        const now = new Date().getTime();
        const delta = now - lastTime;

        if (delta > 10000 && input.value.length > 1) {
            input.value = "";
            inputBuffer = "";
            input.style.borderColor = 'red';
            input.style.borderWidth = '2px'; // Menambah ketebalan border
            input.style.animation = 'shake 0.3s ease'; // Tambahkan animasi
            setTimeout(() => {
                input.style.borderColor = '';
                input.style.borderWidth = ''; // Kembalikan ketebalan border
                input.style.animation = ''; // Hilangkan animasi
            }, 300);
            return;
        }

        inputBuffer = input.value;
        lastTime = now;

        clearTimeout(timer);
        timer = setTimeout(() => {
            inputBuffer = "";

            // ✨ Cek apakah scan diawali dengan "DR"
            if (input.value.substring(0, 2).toUpperCase() === "DR") {
                productNameWrapper.innerHTML = '';

                setTimeout(setupTempListenersDR, 100);

                // DR case → tampilkan 2 bottle & 2 temp
                bottleWrapper.innerHTML = `
                    <label for="bottle_qty_1" class="col-sm-2 control-label">Bottle Quantity (1)</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control style-ph" name="bottle_qty_1" id="bottle_qty_1" placeholder="Input Bottle Qty 1" required autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                        <input type="number" class="form-control style-ph" name="bottle_qty_2" id="bottle_qty_2" placeholder="Input Bottle Qty 2" required autocomplete="off">
                    </div>
                `;
                tempWrapper.innerHTML = `
                    <label for="temp_1" class="col-sm-2 control-label">Temp (1)</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control style-ph" name="temp_1" id="temp_1" placeholder="Input Temp 1" required autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control style-ph" name="temp_2" id="temp_2" placeholder="Input Temp 2" required autocomplete="off">
                    </div>
                `;

                productNameWrapper.innerHTML = `
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-2">
                        <p id="productNameDisplay_1" style="font-weight: bold; color: #0073b7;"></p>
                    </div>
                    <div class="col-sm-2">
                        <p id="productNameDisplay_2" style="font-weight: bold; color: #0073b7;"></p>
                    </div>
                `;
            } else {
                // Normal case → tampilkan 1 bottle & 1 temp
                bottleWrapper.innerHTML = `
                    <label for="bottle_qty" class="col-sm-2 control-label">Bottle Quantity</label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control style-ph" name="bottle_qty" id="bottle_qty" placeholder="Input Bottle Quantity" required autocomplete="off">
                    </div>
                `;
                tempWrapper.innerHTML = `
                    <label for="temp" class="col-sm-2 control-label">Temp</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control style-ph" name="temp" id="temp" placeholder="Input Temp" required autocomplete="off">
                    </div>
                `;
                productNameWrapper.innerHTML = `
                    <div class="col-sm-offset-2 col-sm-4">
                        <p id="productNameDisplay" style="font-weight: bold; color: #0073b7;"></p>
                    </div>
                `;
                setTimeout(() => {
                    listenTempWithId('#temp', '#productNameDisplay');
                }, 100);
            }

        }, 100); // tunggu sebentar agar input selesai
    });


    input.addEventListener("paste", function(e) {
        e.preventDefault();
    });

    window.onload = function() {
        input.focus();
    };
</script>
<script>
    $(document).ready(function() {
        loadData();
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
                    console.log(response); // Debugging response
                    // Tampilkan pesan sukses atau gagal
                    // if (response.session === "LIB_SUCCESS") {
                    //     toastr.success("Data berhasil disimpan !")
                    //     // Reset form setelah berhasil simpan
                    //     document.querySelector("form").reset();
                    //     input.focus(); // Fokus kembali ke input no_resep
                    //     loadData();
                    // } else {
                    //     alert('Gagal menyimpan data.');
                    // }

                    if (response.success) {
                        toastr.success(response.message);
                        $('form')[0].reset();
                        $('#no_resep').focus();

                        $('#productNameDisplay').text('');
                        $('#productNameDisplay_1').text('');
                        $('#productNameDisplay_2').text('');
                        
                        loadData();
                    } else {
                        toastr.error(response.message);
                        if (response.errors) {
                            console.error("Detail error:", response.errors);
                        }
                    }
                },
                error: function() {
                    console.log(response); // Debugging response
                    alert('Terjadi kesalahan saat mengirim data!');
                }
            });
        });
    });
</script>
<script>
    function loadData() {
        fetch("pages/ajax/GetData_PreliminarySchedule.php")
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById("dataBody");
                tbody.innerHTML = ""; // Kosongkan dulu

                data.forEach((item, index) => {
                    const row = `<tr>
                        <td align="center">${index + 1}</td>
                        <td>${item.no_resep}</td>
                        <td>${item.product_name}</td>
                        <td align="center">
                            <button class="btn btn-danger btn-sm" onclick="deleteData(${item.id})"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
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

    // Jalankan pertama kali saat halaman dibuka
    loadData();

    // Auto-refresh tiap 3 detik
    setInterval(loadData, 3000);

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

</script>
