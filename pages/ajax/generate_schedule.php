<?php
session_start();
include '../../koneksi.php';

if (isset($_POST['schedules'])) {
    $schedules = json_decode($_POST['schedules'], true);

    // print_r($schedules);
    $maxRows = 24;
    $scheduleChunks = [];

    // Membagi nomor resep per group menjadi beberapa chunk
    foreach ($schedules as $groupName => $noReseps) {
        $scheduleChunks[$groupName] = array_chunk($noReseps, $maxRows);
    }

    // Menyimpan ID dari tbl_preliminary_schedule untuk tiap resep di tiap chunk
    $idMap = [];

    foreach ($scheduleChunks as $groupName => $chunks) {
        $idMap[$groupName] = [];

        foreach ($chunks as $chunkIndex => $chunk) {
            $idMap[$groupName][$chunkIndex] = [];

            foreach ($chunk as $no_resep) {
                $stmt = $con->prepare("SELECT id FROM tbl_preliminary_schedule 
                                       WHERE no_resep = ? AND status = 'ready' AND (no_machine IS NULL OR no_machine = '') 
                                       ORDER BY id DESC LIMIT 1");
                $stmt->bind_param("s", $no_resep);
                $stmt->execute();
                $stmt->bind_result($id);

                if ($stmt->fetch()) {
                    $idMap[$groupName][$chunkIndex][] = $id;
                    $stmt->close();

                    // Tandai ID ini agar tidak digunakan lagi sementara
                    $stmtUpdate = $con->prepare("UPDATE tbl_preliminary_schedule SET no_machine = 'TEMP_USED' WHERE id = ?");
                    $stmtUpdate->bind_param("i", $id);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                } else {
                    $idMap[$groupName][$chunkIndex][] = null;
                    $stmt->close();
                }
            }
        }
    }
?>
    <h4 style="margin-left: 5px;">Schedule Celup</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center" style="table-layout: fixed; min-width: 2560px; width: 100%;">
            <thead class="table-dark">
                <tr>
                    <th rowspan="2" style="width: 3%;">No</th>
                    <?php foreach ($scheduleChunks as $groupName => $chunks): ?>
                        <?php
                            // Ambil keterangan dari master_suhu berdasarkan group
                            $keterangan = '';
                            $suhu = null;

                            // Ambil dyeing dan suhu dari master_suhu
                            $stmt = $con->prepare("SELECT dyeing, suhu FROM master_suhu WHERE `group` = ? LIMIT 1");
                            $stmt->bind_param("s", $groupName);
                            $stmt->execute();
                            $stmt->bind_result($dyeingValue, $suhu);
                            $stmt->fetch();
                            $stmt->close();

                            // Konversi dyeing ke keterangan
                            if ($dyeingValue == "1") {
                                $keterangan = 'POLY';
                            } elseif ($dyeingValue == "2") {
                                $keterangan = 'COTTON';
                            }

                            $machines = [];

                            if ($keterangan === 'COTTON' && $suhu == 80) {
                                // ✅ Khusus: COTTON & suhu 80 hanya A6 dan C1
                                $machines = ['A6', 'C1'];
                            } elseif ($keterangan) {
                                // ✅ Selain itu, ambil dari DB, tapi exclude A6 dan C1
                                $stmtMesin = $con->prepare("
                                    SELECT no_machine 
                                    FROM master_mesin 
                                    WHERE keterangan = ? AND no_machine NOT IN ('A6', 'C1')
                                ");
                                $stmtMesin->bind_param("s", $keterangan);
                                $stmtMesin->execute();
                                $resultMesin = $stmtMesin->get_result();

                                while ($row = $resultMesin->fetch_assoc()) {
                                    $machines[] = $row['no_machine'];
                                }
                                $stmtMesin->close();
                            }

                            // Temp Group
                            // $groupTemp = [];
                            // $stmtTemp = $con->prepare("SELECT product_name FROM master_suhu WHERE `group` = ?");
                            // $stmtTemp->bind_param("s", $groupName);
                            // $stmtTemp->execute();
                            // $resultProd = $stmtTemp->get_result();
                            // while ($row = $resultProd->fetch_assoc()) {
                            //     $groupTemp[] = $row['product_name'];
                            // }
                            // $stmtTemp->close();

                            // Gabungkan dengan koma
                            // $tempList = implode(' ; ', $groupTemp);
                            $stmtTemp = $con->prepare("SELECT program, suhu, product_name FROM master_suhu WHERE `group` = ? LIMIT 1");
                            $stmtTemp->bind_param("s", $groupName);
                            $stmtTemp->execute();
                            $result = $stmtTemp->get_result();
                            $row = $result->fetch_assoc();
                            $stmtTemp->close();

                            if ($row['program'] == 1) {
                                $tempGroup = 'Constant ' . $row['suhu'];
                            } elseif ($row['program'] == 2) {
                                $tempGroup = 'Raising ' . $row['product_name'];
                            }
                        ?>

                        <?php foreach ($chunks as $chunkIndex => $chunk): ?>
                            <th colspan="1">
                                <div class="form-group dropdown-container" style="display: table; margin: 0 auto;">
                                    <select class="form-control input-sm" aria-label="Pilih Mesin untuk <?= htmlspecialchars($groupName) ?>" data-group="<?= htmlspecialchars($groupName) ?>">
                                        <option value="">Pilih Mesin</option>
                                        <?php foreach ($machines as $machine): ?>
                                            <option value="<?= htmlspecialchars($machine) ?>"><?= htmlspecialchars($machine) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- <?= htmlspecialchars($groupName) ?> <br> -->
                                <!-- [<small><?= htmlspecialchars($tempList) ?></small>] -->
                                <small class="text-danger"><?= htmlspecialchars($tempGroup) ?></small>
                            </th>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php for ($i = 0; $i < $maxRows; $i++): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <?php foreach ($scheduleChunks as $groupName => $chunks): ?>
                            <?php foreach ($chunks as $chunkIndex => $chunk): ?>
                                <td>
                                    <?php if (isset($chunk[$i])): ?>
                                        <?php
                                            $no_resep = $chunk[$i];
                                            $id_schedule = $idMap[$groupName][$chunkIndex][$i] ?? null;
                                        ?>
                                        <?php if ($id_schedule): ?>
                                            <span class="resep-item" data-id="<?= $id_schedule ?>" data-resep="<?= htmlspecialchars($no_resep) ?>" data-group="<?= htmlspecialchars($groupName) ?>">
                                                <?= htmlspecialchars($no_resep) ?>
                                            </span>
                                        <?php else: ?>
                                            <?= htmlspecialchars($no_resep) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div class="text-center" style="margin-bottom: 20px;">
            <button id="submitForDisp" class="btn btn-primary"><i class="fa fa-save"></i> Submit For Dispensing List</button>
        </div>
    </div>
<?php
    // Bersihkan tanda sementara
    $con->query("UPDATE tbl_preliminary_schedule SET no_machine = NULL WHERE no_machine = 'TEMP_USED'");
} else {
    echo '<div class="alert alert-warning mt-4">Data tidak tersedia.</div>';
}
?>
