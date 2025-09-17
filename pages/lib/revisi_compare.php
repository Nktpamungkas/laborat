<?php
// lib/revisi_compare.php

function _norm_str($x) { return strtoupper(trim((string)$x)); }
function _norm_date($x) {
    $x = trim((string)$x);
    if ($x === '') return '';
    $ts = strtotime(str_replace(['.', '/', 'T'], ['-', '-', ' '], $x));
    if ($ts === false) return '';
    return date('Y-m-d', $ts);
}

// bandingkan DB2 vs MySQL (snapshot terakhir). true jika ADA perbedaan
function revisionsDiffer(array $db2, array $mysql): bool {
    $mapStr = [
        'REVISIC'  => 'revisic',
        'REVISI2'  => 'revisi2',
        'REVISI3'  => 'revisi3',
        'REVISI4'  => 'revisi4',
        'REVISI5'  => 'revisi5',
        'REVISIN'  => 'revisin',
        'DREVISI2' => 'drevisi2',
        'DREVISI3' => 'drevisi3',
        'DREVISI4' => 'drevisi4',
        'DREVISI5' => 'drevisi5',
    ];
    foreach ($mapStr as $kDB2 => $kMy) {
        if (_norm_str($db2[$kDB2] ?? '') !== _norm_str($mysql[$kMy] ?? '')) return true;
    }
    $mapDate = [
        'REVISI1DATE' => 'revisi1date',
        'REVISI2DATE' => 'revisi2date',
        'REVISI3DATE' => 'revisi3date',
        'REVISI4DATE' => 'revisi4date',
        'REVISI5DATE' => 'revisi5date',
    ];
    foreach ($mapDate as $kDB2 => $kMy) {
        if (_norm_date($db2[$kDB2] ?? '') !== _norm_date($mysql[$kMy] ?? '')) return true;
    }
    return false;
}

// first non-empty helper
function first_non_empty(array $arr): string {
    foreach ($arr as $v) {
        if (trim((string)$v) !== '') return (string)$v;
    }
    return '';
}