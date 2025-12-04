<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../koneksi.php';

 $results = [];

// Accept search parameter (from select2) via GET 'search' or 'term'
 $search = '';
 if (isset($_GET['search'])) $search = trim($_GET['search']);
 if (empty($search) && isset($_GET['term'])) $search = trim($_GET['term']);

 // basic sanitization for DB2 single-quoted literals
 $search_esc = str_replace("'", "''", $search);

 // Build SQL: search by ITEMCODE or any SUBCODE01-04 and return rows where SUBCODE04 is not empty; limit to 10 rows
 if ($search_esc !== '') {
     $like = "%" . $search_esc . "%";
     // Build a concatenated itemcode from subcodes and search that as well.
     // Use COALESCE to treat NULL subcodes as empty strings before concatenation.
     $concat = "COALESCE(TRIM(SUBCODE01),'') || COALESCE(TRIM(SUBCODE02),'') || COALESCE(TRIM(SUBCODE03),'') || COALESCE(TRIM(SUBCODE04),'')";
     $sql = "SELECT DISTINCT 
          TRIM(SUBCODE01) AS SUBCODE01, 
          TRIM(SUBCODE02) AS SUBCODE02, 
          TRIM(SUBCODE03) AS SUBCODE03, 
          TRIM(SUBCODE04) AS SUBCODE04 
      FROM PRODUCT p 
      WHERE TRIM(ITEMTYPECODE) = 'KGF' 
          AND (
              TRIM(SUBCODE01) LIKE '$like'
              OR TRIM(SUBCODE02) LIKE '$like'
              OR TRIM(SUBCODE03) LIKE '$like'
              OR TRIM(SUBCODE04) LIKE '$like'
              OR ($concat) LIKE '$like'
          )
      FETCH FIRST 10 ROWS ONLY";
 } else {
     $sql = "SELECT DISTINCT TRIM(SUBCODE01) AS SUBCODE01, TRIM(SUBCODE02) AS SUBCODE02, TRIM(SUBCODE03) AS SUBCODE03, TRIM(SUBCODE04) AS SUBCODE04 FROM PRODUCT p WHERE TRIM(ITEMTYPECODE) = 'KGF' FETCH FIRST 10 ROWS ONLY";
 }

 $res = db2_exec($conn1, $sql);
 if ($res) {
     while ($row = db2_fetch_assoc($res)) {
                $s1 = $row['SUBCODE01'] ?? '';
                $s2 = $row['SUBCODE02'] ?? '';
                $s3 = $row['SUBCODE03'] ?? '';
                $s4 = $row['SUBCODE04'] ?? '';
                // Build ITEMCODE as concatenation of subcodes 01..04
                $itemcode = $s1 . $s2 . $s3 . $s4;
                // Build label showing subcodes and itemcode; use concatenated ITEMCODE as id
                $label = trim($s1 . ' - ' . $s2 . ' - ' . $s3 . ' - ' . $s4 . ' (' . $itemcode . ')');
                $id = htmlspecialchars($itemcode, ENT_QUOTES);
                $results[] = [ 'id' => $id, 'text' => $label, 'itemcode' => $itemcode, 'subcode01'=>$s1, 'subcode02'=>$s2, 'subcode03'=>$s3, 'subcode04'=>$s4 ];
     }
 }

 echo json_encode($results);

?>
