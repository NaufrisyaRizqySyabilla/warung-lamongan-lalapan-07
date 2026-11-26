<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $n = trim($_POST['nama_kategori'] ?? '');

    if ($n !== '') {
        try {
            $st = $pdo->prepare(
                "INSERT INTO kategori(nama_kategori) VALUES(?)"
            );

            $st->execute([$n]);

        } catch (Throwable $e) {
            $error = 'Kategori sudah ada atau tidak dapat disimpan.';
        }
    }
}

// Hapus kategori
if (isset($_GET['hapus'])) {

    try {
        $st = $pdo->prepare(
            "DELETE FROM kategori WHERE id_kategori = ?"
        );

        $st->execute([(int) $_GET['hapus']]);

    } catch (Throwable $e) {
        $error = 'Kategori masih digunakan oleh menu.';
    }
}

// Mengambil daftar kategori dan jumlah menu
$rows = $pdo->query("
    SELECT
        k.*,
        COUNT(m.id_menu) jumlah
    FROM kategori k
    LEFT JOIN menu m ON m.id_kategori = k.id_kategori
    GROUP BY k.id_kategori
    ORDER BY k.nama_kategori
")->fetchAll();

$pageTitle = 'Kategori';

require __DIR__ . '/../includes/header.php';

?>

<div class="page-heading">
    <h1>Kategori Menu</h1>
</div>

<?php if ($error): ?>

    <div class="alert alert-warning">
        <?= e($error) ?>
    </div>

<?php endif; ?>

<div class="row g-3">

    <!-- Form Tambah Kategori -->
    <div class="col-md-5">

        <div class="form-card">

            <h5 class="fw-bold">
                Tambah Kategori
            </h5>

            <form method="post">

                <input
                    class="form-control mb-3"
                    name="nama_kategori"
                    placeholder="Nama kategori"
                    required
                >

                <button class="btn-green">
                    Simpan
                </button>

            </form>

        </div>

    </div>

    <!-- Daftar Kategori -->
    <div class="col-md-7">

        <div class="panel">

            <div class="table-responsive">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah Menu</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($rows as $r): ?>

                            <tr>

                                <td>
                                    <?= e($r['nama_kategori']) ?>
                                </td>

                                <td>
                                    <?= $r['jumlah'] ?>
                                </td>

                                <td>
                                    <a
                                        class="btn btn-sm btn-outline-danger"
                                        href="?hapus=<?= $r['id_kategori'] ?>"
                                        data-confirm="Hapus kategori ini?"
                                    >
                                        Hapus
                                    </a>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>