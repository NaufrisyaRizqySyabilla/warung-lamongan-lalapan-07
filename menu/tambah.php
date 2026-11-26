<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

require_once __DIR__ . '/form.php';

// Mengambil daftar kategori
$cats = $pdo->query("
    SELECT *
    FROM kategori
    ORDER BY nama_kategori
")->fetchAll();

$error = '';

// Proses tambah menu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        [$n, $k, $h, $s, $g] = save_menu($pdo);

        $st = $pdo->prepare("
            INSERT INTO menu (
                nama_menu,
                id_kategori,
                harga,
                status_menu,
                gambar_menu
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $st->execute([
            $n,
            $k,
            $h,
            $s,
            $g
        ]);

        redirect('menu/index.php');

    } catch (Throwable $e) {

        $error = $e->getMessage();

    }

}

$pageTitle = 'Tambah Menu';

require __DIR__ . '/../includes/header.php';

?>

<div class="page-heading">

    <h1>Tambah Menu</h1>

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
            required
        >

        <label class="form-label">
            Kategori
        </label>

        <select
            class="form-select mb-3"
            name="id_kategori"
            required
        >

            <option value="">
                Pilih kategori
            </option>

            <?php foreach ($cats as $c): ?>

                <option value="<?= $c['id_kategori'] ?>">
                    <?= e($c['nama_kategori']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <label class="form-label">
            Harga (Rp)
        </label>

        <input
            type="number"
            min="1"
            class="form-control mb-3"
            name="harga"
            required
        >

        <label class="form-label">
            Foto Menu
        </label>

        <input
            type="file"
            class="form-control mb-3"
            name="gambar"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <small class="muted">
            JPG/PNG/WebP, maksimal 3 MB. Foto bisa diganti kapan saja.
        </small>

        <label class="form-label d-block mt-3">
            Status
        </label>

        <select
            class="form-select mb-3"
            name="status_menu"
        >

            <option>
                Tersedia
            </option>

            <option value="Tidak Tersedia">
                Habis
            </option>

        </select>

        <button class="btn-green">
            Simpan Menu
        </button>

    </form>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
