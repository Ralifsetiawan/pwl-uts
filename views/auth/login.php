<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body style="min-height: 100vh; background: radial-gradient(circle at 20% 15%, rgba(255, 106, 19, 0.35), transparent 45%), linear-gradient(160deg, #1f1030 0%, #3a1458 100%);"
      class="d-flex align-items-center justify-content-center">

    <div class="card border-0 shadow-lg" style="width: 100%; max-width: 420px; border-radius: 16px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white mb-2"
                      style="width: 48px; height: 48px; background: linear-gradient(135deg, #ff8a3d, #ff6a13);">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                </span>
                <h4 class="mb-1">Manajemen Akun</h4>
                <p class="text-muted small mb-0">Masuk pakai email dan password kamu.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 small"><?= e($error) ?></div>
            <?php endif; ?>

            <!-- form dikirim lewat POST ke /auth/authenticate -->
            <form action="<?= BASE_URL ?>/auth/authenticate" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
        </div>
    </div>

</body>
</html>