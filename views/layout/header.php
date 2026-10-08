<?php
global $page;                       // nama halaman aktif dari index.php (buat highlight menu)
$flash = pullFlash();               // ambil pesan sukses/error (kalau ada)
$user  = $_SESSION['user'] ?? null; // data user yang lagi login

// daftar menu sidebar: url => [label, icon]
$menus = [
    'account'      => ['Akun',       'bi-people'],
    'account-type' => ['Tipe Akun',  'bi-person-badge'],
    'actions'      => ['Jenis Aksi', 'bi-lightning-charge'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Manajemen Akun') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="app-shell" id="appShell">

        <div class="sidebar">
            <a href="<?= BASE_URL ?>/" class="sidebar-brand">
                <span class="brand-mark"><i class="bi bi-shield-lock"></i></span>
                Manajemen Akun
            </a>

            <ul class="sidebar-nav">
                <?php foreach ($menus as $url => [$label, $icon]): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/<?= $url ?>" class="nav-link <?= $page === $url ? 'active' : '' ?>">
                            <i class="bi <?= $icon ?>"></i> <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="main">
            <header class="topbar">
                <?php if ($user): ?>
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <div class="text-end small lh-sm">
                            <div class="fw-semibold"><?= e($user['name']) ?></div>
                            <div class="text-muted"><?= e($user['email']) ?></div>
                        </div>
                        <!-- logout wajib POST (lihat $postOnlyActions di index.php) -->
                        <form action="<?= BASE_URL ?>/auth/logout" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </header>
            <div class="content">
                <?php if ($flash): ?>
                    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show">
                        <?= e($flash['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
