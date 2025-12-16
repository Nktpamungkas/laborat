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
                // $stmt = $con->prepare("SELECT id, is_old_data FROM tbl_preliminary_schedule 
                //                        WHERE no_resep = ? AND status = 'ready' AND (no_machine IS NULL OR no_machine = '') 
                //                        ORDER BY id DESC LIMIT 1");
                $stmt = $con->prepare("SELECT ps.id, ps.is_old_data, ps.is_test, ps.is_bonresep, tm.jenis_matching
                                        FROM tbl_preliminary_schedule ps
                                        LEFT JOIN tbl_matching tm ON 
                                            CASE 
                                                WHEN LEFT(ps.no_resep, 2) = 'DR' 
                                                    THEN LEFT(ps.no_resep, LENGTH(ps.no_resep) - 2)
                                                ELSE ps.no_resep
                                            END = tm.no_resep
                                        WHERE ps.no_resep = ? AND ps.status = 'ready' AND (ps.no_machine IS NULL OR ps.no_machine = '') 
                                        ORDER BY ps.id DESC LIMIT 1");

                $stmt->bind_param("s", $no_resep);
                $stmt->execute();
                $stmt->bind_result($id, $is_old_data, $is_test, $is_bonresep, $jenis_matching);

                if ($stmt->fetch()) {
                    $idMap[$groupName][$chunkIndex][] = ['id' => $id, 'is_old' => $is_old_data, 'is_test'   => (int)$is_test, 'is_bonresep' => (int)$is_bonresep, 'matching' => $jenis_matching];
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
    <div style="display: flex; gap: 10px; padding: 10px 0px;">
        <h4 style="margin-left: 5px;">Schedule Celup</h4>
        <button id="undo" class="btn btn-primary" title="undo" style="border-radius: 50%;"><i class="fa fa-undo" aria-hidden="true"></i></button>
    </div>
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="table table-bordered table-striped align-middle text-center" id="schedule-mesin" style="table-layout: auto; width: 100%;">
            <thead class="table-dark">
                <tr>
                    <th rowspan="2" style="min-width: 50px;" class="sticky-col">No</th>
                    <?php foreach ($scheduleChunks as $groupName => $chunks): ?>
                        <?php
                            // Ambil keterangan dari master_suhu berdasarkan group
                            $keterangan = '';
                            $suhu = null;

                            // Ambil dyeing dan suhu dari master_suhu
                            if ($groupName === 'BON_RESEP') {
                                $keterangan = '';
                                $suhu = null;
                                $machines = [];
                                $tempGroup = 'BON RESEP';
                            } else {
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
                            }

                            // ✅ Ambil mesin yang sedang terjadwal/berproses agar bisa di-exclude
                            $excludedMachines = [];
                            $stmtExclude = $con->prepare("
                                SELECT DISTINCT no_machine 
                                FROM tbl_preliminary_schedule 
                                WHERE is_old_data = 1 AND is_old_cycle = 0 AND status IN ('scheduled', 'in_progress_dispensing', 'in_progress_dyeing')
                            ");
                            $stmtExclude->execute();
                            $resultExclude = $stmtExclude->get_result();

                            while ($row = $resultExclude->fetch_assoc()) {
                                $excludedMachines[] = $row['no_machine'];
                            }
                            $stmtExclude->close();

                            // ✅ Filter final mesin: hanya mesin yang tidak ada di $excludedMachines
                            $machines = array_values(array_diff($machines, $excludedMachines));

                            if ($groupName !== 'BON_RESEP') {
                                // Temp Group hanya untuk group normal (yang ada di master_suhu)
                                $stmtTemp = $con->prepare("SELECT program, suhu, product_name FROM master_suhu WHERE `group` = ? LIMIT 1");
                                $stmtTemp->bind_param("s", $groupName);
                                $stmtTemp->execute();
                                $result = $stmtTemp->get_result();
                                $row = $result->fetch_assoc();
                                $stmtTemp->close();

                                if ($row) {
                                    if ((int)$row['program'] === 1) {
                                        $tempGroup = 'Constant ' . $row['suhu'];
                                    } elseif ((int)$row['program'] === 2) {
                                        $tempGroup = 'Raising ' . $row['product_name'];
                                    } else {
                                        $tempGroup = $groupName;
                                    }
                                } else {
                                    $tempGroup = $groupName; // fallback aman kalau group tidak ada di master_suhu
                                }
                            }
                            // else: BON_RESEP tetap pakai $tempGroup = 'BON RESEP' dari atas
                        ?>

                        <?php foreach ($chunks as $chunkIndex => $chunk): ?>
                            <th colspan="1" style="min-width: 115px;">
                                <!-- <div class="form-group dropdown-container" style="display: table; margin: 0 auto;">
                                    <select class="form-control input-sm" aria-label="Pilih Mesin untuk <?= htmlspecialchars($groupName) ?>" data-group="<?= htmlspecialchars($groupName) ?>">
                                        <option value="">Pilih Mesin</option>
                                        <?php foreach ($machines as $machine): ?>
                                            <option value="<?= htmlspecialchars($machine) ?>"><?= htmlspecialchars($machine) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <small class="text-danger"><?= htmlspecialchars($tempGroup) ?></small> -->
                                <?php if ($groupName === 'BON_RESEP'): ?>
                                    <div class="form-group dropdown-container" style="display: table; margin: 0 auto;">
                                        <select class="form-control input-sm" disabled data-group="BON_RESEP">
                                            <option value="BONRESEP" selected>BON RESEP</option>
                                        </select>
                                    </div>
                                    <small class="text-danger">BON RESEP</small>
                                <?php else: ?>
                                    <div class="form-group dropdown-container" style="display: table; margin: 0 auto;">
                                        <select class="form-control input-sm" aria-label="Pilih Mesin untuk <?= htmlspecialchars($groupName) ?>" data-group="<?= htmlspecialchars($groupName) ?>">
                                            <option value="">Pilih Mesin</option>
                                            <?php foreach ($machines as $machine): ?>
                                                <option value="<?= htmlspecialchars($machine) ?>"><?= htmlspecialchars($machine) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <small class="text-danger"><?= htmlspecialchars($tempGroup) ?></small>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php for ($i = 0; $i < $maxRows; $i++): ?>
                    <tr>
                        <td class="sticky-col"><?= $i + 1 ?></td>
                        <?php foreach ($scheduleChunks as $groupName => $chunks): ?>
                            <?php foreach ($chunks as $chunkIndex => $chunk): ?>
                                <?php
                                    $id_info = $idMap[$groupName][$chunkIndex][$i] ?? null;
                                    $id_schedule = $id_info['id'] ?? null;
                                    $is_old_data = $id_info['is_old'] ?? 0;
                                    $is_test     = (int)($id_info['is_test'] ?? 0);
                                    $is_bonresep = (int)($id_info['is_bonresep'] ?? 0);

                                    // Atur style td jika is_old_data == 1
                                    $tdStyle = $is_old_data == 1 ? 'background-color: pink;' : '';
                                    $badge   = $is_test === 1 ? ' <span class="label label-warning">TEST REPORT</span>' : '';
                                ?>
                                <td style="<?= $tdStyle ?>">
                                    <?php if (isset($chunk[$i])): ?>
                                        <?php $no_resep = $chunk[$i]; ?>
                                        <?php if ($id_schedule): ?>
                                            <span class="resep-item"
                                                data-id="<?= $id_schedule ?>"
                                                data-resep="<?= htmlspecialchars($no_resep) ?>"
                                                data-group="<?= htmlspecialchars($groupName) ?>"
                                                data-bonresep="<?= $is_bonresep ?>">
                                                <?= htmlspecialchars($no_resep) ?> <?= $badge ?>
                                                <!-- <?= htmlspecialchars($no_resep) . ' - ' . htmlspecialchars($id_info['matching'] ?? '') ?> -->
                                            </span>
                                        <?php else: ?>
                                            <!-- <?= htmlspecialchars($no_resep) ?> -->
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
