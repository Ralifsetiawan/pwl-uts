<?php
global $page;                       // nama halaman aktif dari index.php (buat highlight menu)
$flash = pullFlash();               // ambil pesan sukses/error (kalau ada)
$user  = $_SESSION['user'] ?? null; // data user yang lagi login

// daftar menu sidebar: url => [label, icon]
$menus = [
    'account'      => ['Akun',       'ri-team-fill'],
    'account-type' => ['Tipe Akun',  'ri-id-card-fill'],
    'actions'      => ['Jenis Aksi', 'ri-flashlight-fill'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Manajemen Akun') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="app-shell" id="appShell">

        <div class="sidebar">
            <a href="<?= BASE_URL ?>/" class="sidebar-brand">
                <span class="brand-mark"><i class="ri-shield-user-fill"></i></span>
                Manajemen Akun
            </a>

            <ul class="sidebar-nav">
                <?php foreach ($menus as $url => [$label, $icon]): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/<?= $url ?>" class="nav-link <?= $page === $url ? 'active' : '' ?>">
                            <i class="<?= $icon ?>"></i> <?= $label ?>
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
                                <i class="ri-logout-circle-r-line"></i> Logout
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