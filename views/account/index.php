<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Manajemen Akun</h4>
    <a href="<?= BASE_URL ?>/account/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Tambah Akun
    </a>
</div>

<!-- form pencarian: GET ?q=kata-kunci -->
<form action="<?= BASE_URL ?>/account" method="GET" class="input-group mb-3">
    <span class="input-group-text"><i class="bi bi-search"></i></span>
    <input type="text" name="q" class="form-control" value="<?= e($q) ?>"
           placeholder="Cari nama, email, NIM/NIP, atau tipe akun...">
    <button class="btn btn-outline-primary" type="submit">Cari</button>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Identitas</th>
                    <th>Tipe Akun</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($accounts)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Data akun tidak ditemukan.</td></tr>
                <?php endif; ?>

                <?php foreach ($accounts as $row): ?>
                <tr>
                    <td class="fw-semibold"><?= e($row['name']) ?></td>
                    <td><?= e($row['email']) ?></td>
                    <td>
                        <span class="badge bg-light text-dark border"><?= e($row['identification_type']) ?></span>
                        <?= e($row['identification_number']) ?>
                    </td>
                    <td><?= e($row['account_type_name']) ?></td>
                    <td>
                        <span class="badge <?= $row['status'] === 'Aktif' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= e($row['status']) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>/account/<?= e($row['id']) ?>/edit" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <!-- hapus wajib POST + konfirmasi -->
                        <form action="<?= BASE_URL ?>/account/<?= e($row['id']) ?>/delete" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin hapus akun ini?')">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
