<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Cek apakah akun sudah pernah dibuat
$count = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Pendaftaran hanya tersedia untuk akun pertama
if ($count > 0) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $username === '' || strlen($password) < 6) {

        $error = 'Lengkapi semua data. Password minimal 6 karakter.';

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

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Buat Akun | Warung Lamongan Lalapan 07</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="form-card col-11 col-md-5">

        <img
            src="../assets/img/logo.png"
            width="100"
            class="d-block mx-auto"
            alt="Logo Warung Lamongan Lalapan 07"
        >

        <h3 class="text-center fw-bold text-success">
            Buat Akun
        </h3>

        <p class="text-center muted">
            Pendaftaran hanya tersedia untuk akun pertama.
        </p>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <label class="form-label">Nama</label>
            <input
                type="text"
                class="form-control mb-3"
                name="nama"
                required
            >

            <label class="form-label">Username</label>
            <input
                type="text"
                class="form-control mb-3"
                name="username"
                required
            >

            <label class="form-label">
                Password (minimal 6 karakter)
            </label>

            <input
                type="password"
                class="form-control mb-3"
                name="password"
                required
                minlength="6"
            >

            <button type="submit" class="btn-green w-100">
                Buat Akun
            </button>

        </form>

    </div>

</body>
</html>
