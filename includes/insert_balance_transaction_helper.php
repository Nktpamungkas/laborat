<?php
function insertBalanceTransaction($con, $schedule_id)
{
  // Ambil dari preliminary schedule
  $sqlPreliminarySchedule = "SELECT 
      ps.no_resep,
      pse.element_id,
      pse.qty
    FROM tbl_preliminary_schedule ps
    LEFT JOIN tbl_preliminary_schedule_element pse ON ps.id = pse.tbl_preliminary_schedule_id
    WHERE id = ? LIMIT 1";

  $stmtPreliminarySchedule = $con->prepare($sqlPreliminarySchedule);
  $stmtPreliminarySchedule->bind_param("i", $schedule_id);
  $stmtPreliminarySchedule->execute();
  $resPreliminarySchedule = $stmtPreliminarySchedule->get_result();
  $preliminaryScheduleRow = $resPreliminarySchedule->fetch_assoc();
  $stmtPreliminarySchedule->close();

  $action = 'Preliminary-Cycle';
  $no_resep = $preliminaryScheduleRow['no_resep'] ?? '';
  $element_id = $preliminaryScheduleRow['element_id'] ?? '';
  $qty = floatval($preliminaryScheduleRow['qty'] ?? 0);

  // jika qty null atau 0, tidak perlu insert
  if ($qty <= 0) {
    return;
  }


  // check kondisi jika diawali "DR" hanya lanjutkan yang "A"
  if ($no_resep && str_starts_with($no_resep, "DR")) {
    $lastChar = substr($no_resep, -1);
    
    if ($lastChar !== "A") {
        return; // keluar dari function
    }
  }

  // Ambil qty di balance
  $sqlBal = "SELECT BASEPRIMARYQUANTITYUNIT as qty_element_before
               FROM balance
               WHERE NUMBERID = ?
               LIMIT 1";

  $stmtBal = $con->prepare($sqlBal);
  $stmtBal->bind_param("i", $element_id);
  $stmtBal->execute();
  $resBal = $stmtBal->get_result();
  $rowBal = $resBal->fetch_assoc();
  $stmtBal->close();

  $qty_before_kg = $rowBal ? floatval($rowBal['qty_element_before']) : 0;
  $qty_before_gr = $qty_before_kg * 1000;

  // Hitung saldo
  $qty_after_gr = $qty_before_gr - $qty;
  $qty_after_kg = $qty_after_gr / 1000;

  $uom = 'gr';
  $uom_balance = 'kg';

  // Mulai transaction
  $con->begin_transaction();

  try {
    $sqlSummary = "UPDATE balance
                  SET BASEPRIMARYQUANTITYUNIT = ?, LASTUPDATEDATETIME = NOW()
                  WHERE NUMBERID = ?";

    $stmtSum = $con->prepare($sqlSummary);
    $stmtSum->bind_param("di", $qty_after_kg, $element_id);
    $stmtSum->execute();
    $stmtSum->close();

    // Insert
    $sqlInsert = "INSERT INTO balance_transactions
          (element_id, no_resep, action, uom, qty, uom_balance, qty_element_before, qty_element_after, created_at)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmtIns = $con->prepare($sqlInsert);
    $stmtIns->bind_param(
      "sssdsddd",
      $element_id,
      $no_resep,
      $action,
      $uom,
      $qty,
      $uom_balance,  // uom_balance
      $qty_before_kg,
      $qty_after_kg
    );
    

    $stmtIns->execute();
    $stmtIns->close();

    // Delete preliminary schedule element
    $sqlDelete = "DELETE FROM tbl_preliminary_schedule_element WHERE tbl_preliminary_schedule_id = ?";
    $stmtDel = $con->prepare($sqlDelete);
    $stmtDel->bind_param("i", $schedule_id);
    $stmtDel->execute();
    $stmtDel->close();

    $con->commit();
    $result = true;
  } catch (Exception  $e) {
    $con->rollback();
    $result = false;

    // Log error ke database
    $error_message = $e->getMessage();
    $payload = [
      'error' => $error_message,
      'schedule_id' => $schedule_id,
      'no_resep' => $no_resep,
      'element_id' => $element_id,
      'qty' => $qty,
      'qty_before_kg' => $qty_before_kg,
      'qty_after_kg' => $qty_after_kg
    ];
    $data = json_encode($payload);
    
    $sqlLog = "INSERT INTO log_general (entity, entity_id, action, data) VALUES (?, ?, ?, ?)";
    $stmtLog = $con->prepare($sqlLog);
    if ($stmtLog) {
      $entity = 'balance_transaction';
      $entity_id = null;
      $action = 'error';
      $stmtLog->bind_param("siss", $entity, $entity_id, $action, $data);
      $stmtLog->execute();
      $stmtLog->close();
    }
  }
  return $result;
}
