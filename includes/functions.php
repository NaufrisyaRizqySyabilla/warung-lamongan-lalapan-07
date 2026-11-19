<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function e($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function rupiah($v): string {
    return 'Rp ' . number_format((float)$v, 0, ',', '.');
}

function base_url(string $path=''): string {
    return '/lalapan_07_kasir/' . ltrim($path, '/');
}

function require_login(): void {
    if (empty($_SESSION['user'])) {
        header('Location: '.base_url('auth/login.php'));
        exit;
    }
}

function current_user(): array {
    return $_SESSION['user'] ?? [];
}

function is_chicken(string $name): bool {
    return in_array($name, ['Paket Ayam Bakar Serundeng','Paket Pecel Ayam Serundeng'], true);
}

function redirect(string $path): never {
    header('Location: '.base_url($path));
    exit;
}

function set_flash(string $type,string $message): void {
    $_SESSION['flash']=['type'=>$type,'message'=>$message];
}

function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        $f=$_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({icon:'.json_encode($f['type']).',text:'.json_encode($f['message']).',confirmButtonColor:"#176b35"}));</script>';
    }
}