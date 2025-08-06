<?php
session_start();
$user_ip = $_SERVER['REMOTE_ADDR'];
$lock_file = __DIR__ . '/../access.lock';

if (isset($_SESSION['is_locked_owner']) && $_SESSION['is_locked_owner'] === true) {
    file_put_contents($lock_file, json_encode([
        'ip' => $user_ip,
        'username' => $_SESSION['userLAB'],
        'timestamp' => time()
    ]));
}
