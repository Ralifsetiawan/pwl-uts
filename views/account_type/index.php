<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Manajemen Tipe Akun</h4>
    <a href="<?= BASE_URL ?>/account-type/create" class="btn btn-primary btn-sm">
        <i class="ri-add-line"></i> Tambah Tipe Akun
    </a>
</div>

<!-- form pencarian: GET ?q=kata-kunci -->
<form action="<?= BASE_URL ?>/account-type" method="GET" class="input-group mb-3">
    <span class="input-group-text"><i class="ri-search-line"></i></span>
    <input type="text" name="q" class="form-control" value="<?= e($q) ?>" placeholder="Cari nama atau deskripsi...">
    <button class="btn btn-outline-primary" type="submit">Cari</button>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>
                <?php endif; ?>

                <?php foreach ($items as $row): ?>
                <tr>
                    <td class="fw-semibold"><?= e($row['name']) ?></td>
                    <td class="text-muted"><?= e($row['description']) ?></td>
                    <td><?= e(date('d M Y H:i', strtotime($row['created_at']))) ?></td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>/account-type/<?= e($row['id']) ?>/edit" class="btn btn-outline-warning btn-sm">
                            <i class="ri-edit-2-fill"></i>
                        </a>
                        <form action="<?= BASE_URL ?>/account-type/<?= e($row['id']) ?>/delete" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin hapus tipe akun ini?')">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="ri-delete-bin-6-fill"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>