<?php

session_start();
include '../../koneksi.php';

if (isset($_POST['schedules'])) {
    $schedules = json_decode($_POST['schedules'], true);

    // print_r($schedules);
    $isScheduling = "UPDATE tbl_is_scheduling SET is_scheduling = 0";
    mysqli_query($con, $isScheduling);
    
    $maxRows = 24;
    $scheduleChunks = [];

    // Membagi nomor resep per produk menjadi beberapa chunk
    foreach ($schedules as $productName => $noReseps) {
        $scheduleChunks[$productName] = array_chunk($noReseps, $maxRows);
    }
    ?>

    <h4 class="mt-4">Schedule Celup</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <?php
                $sql = "SELECT no_machine FROM master_mesin";
                $result = $con->query($sql);

                $machines = [];
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $machines[] = $row['no_machine'];
                    }
                } else {
                    echo "0 results";
                }
            ?>

            <thead class="table-dark">
                <tr>
                    <th rowspan="2">No</th>
                    <?php foreach ($scheduleChunks as $productName => $chunks): ?>
                        <?php foreach ($chunks as $index => $chunk): ?>
                            <th colspan="1">
                                <div class="dropdown-container">
                                    <select class="form-select form-select-sm" aria-label="Pilih Mesin untuk <?= htmlspecialchars($productName) ?>">
                                        <option value="">Pilih Mesin</option>
                                        <?php foreach ($machines as $machine): ?>
                                            <option value="<?= htmlspecialchars($machine) ?>"><?= htmlspecialchars($machine) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?= htmlspecialchars($productName) ?>
                            </th>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>



            <tbody>
                <?php
                for ($i = 0; $i < $maxRows; $i++) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';

                    foreach ($scheduleChunks as $productName => $chunks) {
                        foreach ($chunks as $chunk) {
                            echo '<td>';
                            echo isset($chunk[$i]) ? htmlspecialchars($chunk[$i]) : '';
                            echo '</td>';
                        }
                    }

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="text-center" style="margin-bottom: 20px;">
            <button id="submitForDisp" class="btn btn-primary">Submit For Dispensing List</button>
        </div>

    </div>

<?php
} else {
    echo '<div class="alert alert-warning mt-4">Data tidak tersedia.</div>';
}
?>
