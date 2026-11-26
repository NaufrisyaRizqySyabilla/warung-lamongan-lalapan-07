<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$id = (int) ($_GET['id'] ?? 0);

try {

    $st = $pdo->prepare("
        DELETE FROM menu
        WHERE id_menu = ?
    ");

    $st->execute([$id]);

    set_flash('success', 'Menu berhasil dihapus.');

} catch (PDOException $e) {

    set_flash(
        'error',
        'Menu tidak dapat dihapus karena sudah digunakan dalam riwayat transaksi. Ubah status menjadi Habis saja.'
    );

}

redirect('menu/index.php');
