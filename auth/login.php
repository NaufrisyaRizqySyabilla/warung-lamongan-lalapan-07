<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['user'])) {
    header('Location: ../dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';

    $st = $pdo->prepare(
        "SELECT * FROM users WHERE username = ? LIMIT 1"
    );

    $st->execute([$u]);
    $row = $st->fetch();

    if ($row && password_verify($p, $row['password'])) {

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id_user' => $row['id_user'],
            'nama' => $row['nama'],
            'username' => $row['username']
        ];

        header('Location: ../dashboard.php');
        exit;

    } else {

        $error = 'Username atau password salah.';

    }
}
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | Warung Lamongan Lalapan 07</title>

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
    "
>

    <div class="form-card col-11 col-md-4">

        <img
            src="../assets/img/logo.png"
            width="110"
            class="d-block mx-auto"
            alt="Logo Warung Lamongan Lalapan 07"
        >

        <h3 class="text-center fw-bold text-success">
            Warung Lamongan Lalapan 07
        </h3>

        <p class="text-center muted">
            Silakan masuk untuk melanjutkan
        </p>

        <?php if ($error): ?>

            <div class="alert alert-danger">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <form method="post">

            <label class="form-label">
                Username
            </label>

            <input
                class="form-control mb-3"
                name="username"
                required
                autocomplete="username"
            >

            <label class="form-label">
                Password
            </label>

            <input
                class="form-control mb-3"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            >

            <button
                type="submit"
                class="btn-green w-100"
            >
                Masuk
            </button>

        </form>

        <div class="text-center mt-3">

            <span class="muted">
                Belum punya akun?
            </span>

            <a
                href="register.php"
                class="text-success fw-bold text-decoration-none"
            >
                Daftar di sini
            </a>

        </div>

    </div>

</body>
</html>
