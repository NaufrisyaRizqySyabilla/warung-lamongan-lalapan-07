<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$pageTitle = 'Pengelolaan Menu';

$q = trim($_GET['q'] ?? '');

$sql = "
    SELECT
        m.*,
        k.nama_kategori
    FROM menu m
    JOIN kategori k ON k.id_kategori = m.id_kategori
";

$params = [];

if ($q !== '') {

    $sql .= "
        WHERE
            m.nama_menu LIKE ?
            OR k.nama_kategori LIKE ?
    ";

    $params = [
        "%$q%",
        "%$q%"
    ];

}

$sql .= "
    ORDER BY
        k.nama_kategori,
        m.nama_menu
";

$st = $pdo->prepare($sql);
$st->execute($params);

$rows = $st->fetchAll();

require __DIR__ . '/../includes/header.php';

?>

<div class="page-heading">

    <div>
        <h1>Pengelolaan Menu</h1>

        <div class="muted">
            Tambah, ubah, dan kelola ketersediaan menu.
        </div>
    </div>

    <a class="btn-green" href="tambah.php">
        <i class="fa fa-plus"></i>
        Tambah Menu
    </a>

</div>

<form class="row g-2 mb-3">

    <div class="col">
        <input
            class="form-control"
            name="q"
            value="<?= e($q) ?>"
            placeholder="Cari nama menu atau kategori..."
        >
    </div>

    <div class="col-auto">
        <button class="btn-green">
            Cari
        </button>
    </div>

</form>

<div class="panel">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($rows as $m): ?>

                    <tr>

                        <td>
                            <img
                                src="../<?= e($m['gambar_menu'] ?: 'assets/img/logo.png') ?>"
                                width="65"
                                height="55"
                                style="object-fit:cover;border-radius:8px"
                            >
                        </td>

                        <td class="fw-bold">
                            <?= e($m['nama_menu']) ?>
                        </td>

                        <td>
                            <?= e($m['nama_kategori']) ?>
                        </td>

                        <td>
                            <?= rupiah($m['harga']) ?>
                        </td>

                        <td>
                            <span class="status-pill <?= $m['status_menu'] === 'Tersedia' ? '' : 'status-off' ?>">
                                <?= e($m['status_menu'] === 'Tersedia' ? 'Tersedia' : 'Habis') ?>
                            </span>
                        </td>

                        <td>

                            <a
                                class="btn btn-sm btn-soft"
                                href="edit.php?id=<?= $m['id_menu'] ?>"
                            >
                                Edit
                            </a>

                            <a
                                class="btn btn-sm btn-outline-danger"
                                data-confirm="Hapus menu ini? Menu yang sudah pernah masuk transaksi tidak dapat dihapus."
                                href="hapus.php?id=<?= $m['id_menu'] ?>"
                            >
                                Hapus
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php if (!$rows): ?>

                    <tr>
                        <td colspan="6" class="text-center">
                            Menu tidak ditemukan.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
