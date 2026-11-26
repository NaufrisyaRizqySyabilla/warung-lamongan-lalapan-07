<?php

function save_menu(PDO $pdo, ?array $old = null): array
{
    $nama = trim($_POST['nama_menu'] ?? '');
    $kategori = (int) ($_POST['id_kategori'] ?? 0);
    $harga = (float) ($_POST['harga'] ?? 0);
    $status = $_POST['status_menu'] ?? 'Tersedia';

    $gambar = $old['gambar_menu'] ?? null;

    // Validasi data menu
    if ($nama === '' || $kategori < 1 || $harga <= 0) {
        throw new Exception(
            'Lengkapi nama, kategori, dan harga dengan benar.'
        );
    }

    // Validasi status menu
    if (!in_array($status, ['Tersedia', 'Tidak Tersedia'], true)) {
        $status = 'Tersedia';
    }

    // Proses upload foto
    if (!empty($_FILES['gambar']['name'])) {

        $f = $_FILES['gambar'];

        if ($f['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload foto gagal.');
        }

        if ($f['size'] > 3 * 1024 * 1024) {
            throw new Exception('Ukuran foto maksimal 3 MB.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))
            ->file($f['tmp_name']);

        $ext = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ][$mime] ?? null;

        if (!$ext) {
            throw new Exception(
                'Format foto harus JPG, PNG, atau WebP.'
            );
        }

        $filename = bin2hex(random_bytes(12)) . '.' . $ext;

        $dest = __DIR__ . '/../uploads/menu/' . $filename;

        if (!move_uploaded_file($f['tmp_name'], $dest)) {
            throw new Exception('Tidak dapat menyimpan foto.');
        }

        $gambar = 'uploads/menu/' . $filename;
    }

    return [
        $nama,
        $kategori,
        $harga,
        $status,
        $gambar
    ];
}
