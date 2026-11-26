<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

require_login();

$pageTitle = 'Dashboard';

// Menghitung jumlah menu
$menuCount = (int) $pdo->query("
    SELECT COUNT(*)
    FROM menu
")->fetchColumn();

// Menghitung total transaksi
$transCount = (int) $pdo->query("
    SELECT COUNT(*)
    FROM transaksi
")->fetchColumn();

// Menghitung pemasukan hari ini
$today = (float) $pdo->query("
    SELECT COALESCE(SUM(total_harga), 0)
    FROM transaksi
    WHERE DATE(tanggal_transaksi) = CURDATE()
")->fetchColumn();

// Mengambil 5 transaksi terbaru
$latest = $pdo->query("
    SELECT
        t.*,
        u.nama
    FROM transaksi t
    JOIN users u ON u.id_user = t.id_user
    ORDER BY t.id_transaksi DESC
    LIMIT 5
")->fetchAll();

require __DIR__ . '/includes/header.php';

?>

<div class="hero">

    <h2>
        Selamat Datang di Warung Lamongan Lalapan 07!
    </h2>

    <p class="mb-0">
        Nikmat, lezat, dan bikin kenyang.
    </p>

</div>

<div class="page-heading">

    <div>

        <h1>Dashboard</h1>

        <div class="muted">
            Ringkasan aktivitas warung
        </div>

    </div>

    <a class="btn-green" href="transaksi/pemesanan.php">
        <i class="fa fa-plus"></i>
        Buat Pesanan
    </a>

</div>

<div class="row g-3 mb-4">

    <div class="col-6 col-lg-4">

        <div class="stat-card">

            <i class="fa fa-bowl-food"></i>

            <div class="muted">
                Jumlah Menu
            </div>

            <div class="num">
                <?= $menuCount ?>
            </div>

        </div>

    </div>

    <div class="col-6 col-lg-4">

        <div class="stat-card">

            <i class="fa fa-receipt"></i>

            <div class="muted">
                Total Transaksi
            </div>

            <div class="num">
                <?= $transCount ?>
            </div>

        </div>

    </div>

    <div class="col-12 col-lg-4">

        <div class="stat-card">

            <i class="fa fa-wallet"></i>

            <div class="muted">
                Pemasukan Hari Ini
            </div>

            <div class="num">
                <?= rupiah($today) ?>
            </div>

        </div>

    </div>

</div>

<div class="panel">

    <h5 class="fw-bold text-success">
        Transaksi Terbaru
    </h5>

    <div class="table-responsive">

        <table class="table">

            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Jenis Pesanan</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($latest as $r): ?>

                    <tr>

                        <td>
                            <?= e($r['kode_transaksi']) ?>
                        </td>

                        <td>
                            <?= e($r['jenis_pesanan']) ?>
                        </td>

                        <td>
                            <?= rupiah($r['total_harga']) ?>
                        </td>

                        <td>
                            <?= e($r['tanggal_transaksi']) ?>
                        </td>

                        <td>
                            <a href="transaksi/detail.php?id=<?= $r['id_transaksi'] ?>">
                                Detail
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php if (!$latest): ?>

                    <tr>
                        <td colspan="5" class="text-center muted">
                            Belum ada transaksi.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
