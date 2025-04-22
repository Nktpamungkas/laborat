<style>
    input::placeholder {
        font-style: italic;
        font-size: 12px;
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
                    <input type="text" class="form-control style-ph" name="temp" id="temp" placeholder="Input Temp" required autocomplete="off">
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
                            <th width="24">
                                <div align="center">No</div>
                            </th>
                            <th width="24">
                                <div align="center">Suffix</div>
                            </th>
                            <th width="24">
                                <div align="center">Temp 1</div>
                            </th>
                            <th width="24">
                                <div align="center">Temp 2</div>
                            </th>
                            <th width="24">
                                <div align="center">Qty Bottle 1</div>
                            </th>
                            <th width="24">
                                <div align="center">Qty Bottle 2</div>
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
    let inputBuffer = '';
    let lastTime = 0;
    let timer = null;

    input.addEventListener("input", function (e) {
        const now = new Date().getTime();
        const delta = now - lastTime;

        if (delta > 100 && input.value.length > 1) {
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
                    if (response.session === "LIB_SUCCESS") {
                        toastr.success("Data berhasil disimpan !")
                        // Reset form setelah berhasil simpan
                        document.querySelector("form").reset();
                        input.focus(); // Fokus kembali ke input no_resep
                        loadData();
                    } else {
                        alert('Gagal menyimpan data.');
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
    function loadData() {
        fetch("pages/ajax/GetData_PreliminarySchedule.php")
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById("dataBody");
                tbody.innerHTML = ""; // Kosongkan dulu

                data.forEach((item, index) => {
                    const row = `<tr>
                        <td>${index + 1}</td>
                        <td>${item.no_resep}</td>
                        <td>${item.temp_1}</td>
                        <td>${item.temp_2}</td>
                        <td>${item.bottle_qty_1}</td>
                        <td>${item.bottle_qty_2 || "-"}</td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });
    }

    // Jalankan pertama kali saat halaman dibuka
    loadData();

    // Auto-refresh tiap 3 detik
    setInterval(loadData, 3000);
</script>