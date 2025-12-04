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

 if ($search_esc !== '') {
     $like = "%" . $search_esc . "%";
     $sql = "SELECT TRIM(CODE) AS CODE FROM PROJECT p WHERE TRIM(CODE) LIKE '$like' FETCH FIRST 10 ROWS ONLY";
 } else {
     $sql = "SELECT TRIM(CODE) AS CODE FROM PROJECT p FETCH FIRST 10 ROWS ONLY";
 }

 $res = db2_exec($conn1, $sql);
 if ($res) {
     while ($row = db2_fetch_assoc($res)) {
         $code = $row['CODE'] ?? '';
         $id = htmlspecialchars($code, ENT_QUOTES);
         $results[] = [ 'id' => $id, 'text' => $code, 'code' => $code ];
     }
 }

 echo json_encode($results);

?>
