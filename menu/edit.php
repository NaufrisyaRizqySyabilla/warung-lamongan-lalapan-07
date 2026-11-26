<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

require_once __DIR__ . '/form.php';

// Mengambil ID menu
$id = (int) ($_GET['id'] ?? 0);

// Mengambil data menu
$st = $pdo->prepare(
    "SELECT * FROM menu WHERE id_menu = ?"
);

$st->execute([$id]);
$m = $st->fetch();

if (!$m) {
    redirect('menu/index.php');
}

// Mengambil daftar kategori
$cats = $pdo->query("
    SELECT *
    FROM kategori
    ORDER BY nama_kategori
")->fetchAll();

$error = '';

// Proses edit menu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        [$n, $k, $h, $s, $g] = save_menu($pdo, $m);

        $up = $pdo->prepare("
            UPDATE menu
            SET
                nama_menu = ?,
                id_kategori = ?,
                harga = ?,
                status_menu = ?,
                gambar_menu = ?
            WHERE id_menu = ?
        ");

        $up->execute([
            $n,
            $k,
            $h,
            $s,
            $g,
            $id
        ]);

        redirect('menu/index.php');

    } catch (Throwable $e) {

        $error = $e->getMessage();

    }
}

$pageTitle = 'Edit Menu';

require __DIR__ . '/../includes/header.php';

?>

<div class="page-heading">

    <h1>Edit Menu</h1>

    <a href="index.php" class="btn btn-light">
        Kembali
    </a>

</div>

<?php if ($error): ?>

    <div class="alert alert-danger">
        <?= e($error) ?>
    </div>

<?php endif; ?>

<div class="form-card">

    <form method="post" enctype="multipart/form-data">

        <label class="form-label">
            Nama Menu
        </label>

        <input
            class="form-control mb-3"
            name="nama_menu"
            value="<?= e($m['nama_menu']) ?>"
            required
        >

        <label class="form-label">
            Kategori
        </label>

        <select
            class="form-select mb-3"
            name="id_kategori"
        >

            <?php foreach ($cats as $c): ?>

                <option
                    value="<?= $c['id_kategori'] ?>"
                    <?= $m['id_kategori'] == $c['id_kategori'] ? 'selected' : '' ?>
                >
                    <?= e($c['nama_kategori']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <label class="form-label">
            Harga
        </label>

        <input
            type="number"
            class="form-control mb-3"
            name="harga"
            value="<?= e($m['harga']) ?>"
            required
        >

        <label class="form-label">
            Foto Saat Ini
        </label>

        <img
            class="image-preview mb-2"
            src="../<?= e($m['gambar_menu'] ?: 'assets/img/logo.png') ?>"
        >

        <input
            type="file"
            class="form-control mb-3"
            name="gambar"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <label class="form-label">
            Status
        </label>

        <select
            class="form-select mb-3"
            name="status_menu"
        >

            <option
                value="Tersedia"
                <?= $m['status_menu'] === 'Tersedia' ? 'selected' : '' ?>
            >
                Tersedia
            </option>

            <option
                value="Tidak Tersedia"
                <?= $m['status_menu'] !== 'Tersedia' ? 'selected' : '' ?>
            >
                Habis
            </option>

        </select>

        <button class="btn-green">
            Simpan Perubahan
        </button>

    </form>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
