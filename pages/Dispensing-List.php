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
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            
            <div class="box-body">
                <div style="margin-bottom: 10px;">
                    <input type="text" id="scanInput" placeholder="Scan here..." class="form-control" style="width: 250px;" autofocus>
                </div>
                <table id="tablee" class="table" width="100%">
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        loadData();

        $('#scanInput').on('keypress', function (e) {
            if (e.which === 13) { // Enter key
                const noResep = $(this).val().trim();
                if (noResep !== "") {
                    updateStatus(noResep);
                    $(this).val("");
                }
            }
        });

        function updateStatus(noResep) {
            $.ajax({
                url: 'pages/ajax/scan_dispensing_update_status.php',
                method: 'POST',
                data: { no_resep: noResep },
                success: function (response) {
                    console.log("Update sukses:", response);
                    loadData(); // Refresh data tabel
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Diperbarui!',
                        text: `No. Resep ${noResep} telah diproses.`,
                        timer: 1200,
                        showConfirmButton: false
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Gagal update:", error);
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
                const tbody = document.getElementById("dataBody");
                tbody.innerHTML = "";

                data.forEach((item, index) => {
                    const rowNumber = (index % 16) + 1;
                    const groupIndex = Math.floor(index / 16);
                    const bgColor = groupIndex % 2 === 0 ? "rgb(250, 235, 215)" : "rgb(220, 220, 220);";

                    const row = `<tr style="background-color: ${bgColor}">
                        <td align="center">${rowNumber}</td>
                        <td>${item.no_resep}</td>
                        <td>${item.product_name}</td>
                        <td>${item.no_machine}</td>
                        <td>${item.status}</td>
                    </tr>`;
                    tbody.innerHTML += row;
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
