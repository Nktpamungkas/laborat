<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../koneksi.php';

$results = [];

// Accept search parameter (from select2) via GET 'search' or 'term'
$search = '';
if (isset($_GET['search'])) $search = trim($_GET['search']);
if (empty($search) && isset($_GET['term'])) $search = trim($_GET['term']);

// use MySQL connection ($con) from koneksi.php
// basic sanitization for MySQL
$search_esc = mysqli_real_escape_string($con, $search);

if ($search_esc !== '') {
    $like = "%" . $search_esc . "%";
    $sql = "SELECT TRIM(bao.option) AS opt FROM balance_additional_option bao WHERE TRIM(bao.type) = 'G_B' AND TRIM(bao.option) LIKE ? LIMIT 10";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        $param = $like;
        mysqli_stmt_bind_param($stmt, 's', $param);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
    } else {
        $res = false;
    }
} else {
    $sql = "SELECT TRIM(bao.option) AS opt FROM balance_additional_option bao WHERE TRIM(bao.type) = 'G_B' LIMIT 10";
    $res = mysqli_query($con, $sql);
}

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $opt = $row['opt'] ?? '';
        $id = htmlspecialchars($opt, ENT_QUOTES);
        $results[] = [ 'id' => $id, 'text' => $opt, 'option' => $opt ];
    }
}

echo json_encode($results);

?>
