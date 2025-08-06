<?php
session_start();
$lock_file = __DIR__ . '/../access.lock';

if (isset($_SESSION['is_locked_owner']) && $_SESSION['is_locked_owner'] === true) {
    if (file_exists($lock_file)) {
        unlink($lock_file);
    }
    unset($_SESSION['is_locked_owner']);
}
