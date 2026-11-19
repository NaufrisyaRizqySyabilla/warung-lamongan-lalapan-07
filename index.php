<?php

require_once __DIR__ . '/config/database.php';

// Menghitung jumlah akun yang sudah terdaftar
$n = (int) $pdo->query("
    SELECT COUNT(*)
    FROM users
")->fetchColumn();

// Mengarahkan pengguna sesuai kondisi akun
header(
    'Location: ' . ($n ? 'auth/login.php' : 'auth/setup.php')
);

exit;
