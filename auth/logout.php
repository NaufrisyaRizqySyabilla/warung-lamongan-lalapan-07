<?php

require_once __DIR__ . '/../includes/functions.php';

// Hapus seluruh data session
$_SESSION = [];

// Hancurkan session
session_destroy();

// Kembali ke halaman login
header('Location: login.php');
exit;

