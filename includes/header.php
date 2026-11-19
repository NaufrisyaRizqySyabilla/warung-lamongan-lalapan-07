<?php

require_once __DIR__ . '/functions.php';

require_login();

$pageTitle = $pageTitle ?? 'Warung Lamongan Lalapan 07';

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($pageTitle) ?> | Warung Lamongan Lalapan 07</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
        rel="stylesheet"
    >

    <link
        href="<?= base_url('assets/css/style.css') ?>"
        rel="stylesheet"
    >
</head>

<body>

    <div class="app-shell">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">

            <div class="brand">
                <img
                    src="<?= base_url('assets/img/logo.png') ?>"
                    alt="Logo"
                >

                <div>
                    Warung Lamongan<br>
                    Lalapan 07
                </div>
            </div>

            <nav>

                <a href="<?= base_url('dashboard.php') ?>">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>

                <a href="<?= base_url('transaksi/pemesanan.php') ?>">
                    <i class="fa-solid fa-utensils"></i>
                    Pemesanan
                </a>

                <a href="<?= base_url('menu/index.php') ?>">
                    <i class="fa-solid fa-list"></i>
                    Pengelolaan Menu
                </a>

                <a href="<?= base_url('kategori/index.php') ?>">
                    <i class="fa-solid fa-layer-group"></i>
                    Kategori
                </a>

                <a href="<?= base_url('transaksi/riwayat.php') ?>">
                    <i class="fa-solid fa-receipt"></i>
                    Riwayat Transaksi
                </a>

            </nav>

            <div class="sidebar-bottom">

                <a href="<?= base_url('auth/logout.php') ?>">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Keluar
                </a>

            </div>

        </aside>

        <!-- Konten utama -->
        <main class="main-area">

            <header class="topbar">

                <button class="btn btn-light d-lg-none" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="top-brand">

                    <img
                        src="<?= base_url('assets/img/logo.png') ?>"
                        alt="Logo"
                    >

                    <div>
                        <strong>Warung Lamongan Lalapan 07</strong>
                        <small>Nikmat, Lezat, dan Bikin Kenyang!</small>
                    </div>

                </div>

                <div class="user-chip">
                    <i class="fa-solid fa-user-circle"></i>

                    <span>
                        <?= e(current_user()['nama'] ?? 'Pengelola') ?>
                    </span>
                </div>

            </header>

            <div class="content">
