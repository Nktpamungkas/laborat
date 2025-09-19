<?php

function _norm_str($x) { return strtoupper(trim((string)$x)); }

// function _norm_date($x) {
//     // dibiarkan ada; TIDAK dipakai untuk compare (tanggal diabaikan)
//     $x = trim((string)$x);
//     if ($x === '') return '';
//     $ts = strtotime(str_replace(['.', '/', 'T'], ['-', '-', ' '], $x));
//     if ($ts === false) return '';
//     return date('Y-m-d', $ts);
// }

// bandingkan DB2 vs MySQL (snapshot terakhir). true jika ADA perbedaan
function revisionsDiffer(array $db2, array $mysql): bool {
    // Bandingkan HANYA field teks (kategori/detail) -> tanggal DIABAikan
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

    // === BLOK TANGGAL DIKOMENTARI/DIABAIKAN ===
    // $mapDate = [
    //     'REVISI1DATE' => 'revisi1date',
    //     'REVISI2DATE' => 'revisi2date',
    //     'REVISI3DATE' => 'revisi3date',
    //     'REVISI4DATE' => 'revisi4date',
    //     'REVISI5DATE' => 'revisi5date',
    // ];
    // foreach ($mapDate as $kDB2 => $kMy) {
    //     if (_norm_date($db2[$kDB2] ?? '') !== _norm_date($mysql[$kMy] ?? '')) return true;
    // }

    return false;
}

// first non-empty helper
function first_non_empty(array $arr): string {
    foreach ($arr as $v) {
        if (trim((string)$v) !== '') return (string)$v;
    }
    return '';
}

/**
 * Normalisasi array line -> map[orderline]=>record(normalized).
 * $lines = array of assoc: orderline, revisic, dst
 * (tanggal tidak disimpan di sini supaya otomatis diabaikan saat compare)
 */
function _normalize_lines(array $lines): array {
    $norm = [];
    foreach ($lines as $r) {
        $k = strtoupper(trim((string)($r['orderline'] ?? '')));
        if ($k === '') continue;
        $norm[$k] = [
            'revisic'  => _norm_str($r['revisic']  ?? ''),
            'revisic1' => _norm_str($r['revisic1'] ?? ''),
            'revisic2' => _norm_str($r['revisic2'] ?? ''),
            'revisic3' => _norm_str($r['revisic3'] ?? ''),
            'revisic4' => _norm_str($r['revisic4'] ?? ''),
            'revisid'  => _norm_str($r['revisid']  ?? ''),
            'revisi2' => _norm_str($r['revisi2'] ?? ''),
            'revisi3' => _norm_str($r['revisi3'] ?? ''),
            'revisi4' => _norm_str($r['revisi4'] ?? ''),
            'revisi5' => _norm_str($r['revisi5'] ?? ''),
        ];
    }
    ksort($norm);
    return $norm;
}

/**
 * TRUE bila ada perbedaan antara set line DB2 vs MySQL snapshot.
 * $db2Lines  = array of assoc (orderline + kolom revisi* dari DB2)
 * $mysqlLines= array of assoc (orderline + kolom revisi* dari MySQL)
 * (field tanggal otomatis diabaikan karena tidak dimasukkan ke _normalize_lines)
 */
function linesDiffer(array $db2Lines, array $mysqlLines): bool {
    $a = _normalize_lines($db2Lines);
    $b = _normalize_lines($mysqlLines);
    if (count($a) !== count($b)) return true;
    foreach ($a as $ol => $rec) {
        if (!isset($b[$ol])) return true;
        if ($rec !== $b[$ol]) return true;
    }
    return false;
}
