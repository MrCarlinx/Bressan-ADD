<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';

if (!revalidar_admin($pdo)) {
    header('Location: ' . page('login.php'));
    exit;
}
