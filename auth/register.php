<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['user'])) {
    header('Location: ../dashboard.php');
    exit;
}

$error = '';
$nama = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $username === '' || $password === '') {

        $error = 'Semua kolom wajib diisi.';

    } elseif (strlen($password) < 6) {

        $error = 'Password minimal 6 karakter.';

    } else {

        $cek = $pdo->prepare(
            "SELECT id_user FROM users WHERE username = ? LIMIT 1"
        );

        $cek->execute([$username]);

        if ($cek->fetch()) {

            $error = 'Username sudah digunakan. Silakan gunakan username lain.';

        } else {

            $st = $pdo->prepare(
                "INSERT INTO users (nama, username, password)
                 VALUES (?, ?, ?)"
            );

            $st->execute([
                $nama,
                $username,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            header('Location: login.php?created=1');
            exit;
        }
    }
}

?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Akun | Warung Lamongan Lalapan 07</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body
    class="d-flex align-items-center justify-content-center min-vh-100"
    style="
        background: linear-gradient(
            135deg,
            #123524 0%,
            #24583b 50%,
            #75834b 100%
        );
        padding: 20px 0;
    "
>

    <div
        class="form-card col-11 col-md-5"
        style="max-width: 550px;"
    >

        <img
            src="../assets/img/logo.png"
            width="110"
            class="d-block mx-auto"
            alt="Logo Warung Lamongan Lalapan 07"
        >

        <h3 class="text-center fw-bold text-success">
            Warung Lamongan Lalapan 07
        </h3>

        <p class="text-center muted mb-4">
            Silakan buat akun untuk melanjutkan
        </p>

        <?php if ($error): ?>

            <div class="alert alert-danger">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <form method="post">

            <label class="form-label">
                Nama Lengkap
            </label>

            <input
                type="text"
                class="form-control mb-3"
                name="nama"
                value="<?= e($nama) ?>"
                placeholder="Masukkan nama lengkap"
                required
                autocomplete="name"
            >

            <label class="form-label">
                Username
            </label>

            <input
                type="text"
                class="form-control mb-3"
                name="username"
                value="<?= e($username) ?>"
                placeholder="Buat username"
                required
                autocomplete="username"
            >

            <label class="form-label">
                Password
            </label>

            <input
                type="password"
                class="form-control mb-3"
                name="password"
                placeholder="Minimal 6 karakter"
                minlength="6"
                required
                autocomplete="new-password"
            >

            <button
                type="submit"
                class="btn-green w-100"
            >
                Daftar Akun
            </button>

        </form>

        <div class="text-center mt-3">

            <span class="muted">
                Sudah punya akun?
            </span>

            <a
                href="login.php"
                class="text-success fw-bold text-decoration-none"
            >
                Login di sini
            </a>

        </div>

    </div>

</body>
</html>
