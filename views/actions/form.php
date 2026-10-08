<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><?= $isEdit ? 'Ubah Jenis Aksi' : 'Tambah Jenis Aksi' ?></h5></div>
            <div class="card-body">
                <form action="<?= $isEdit ? BASE_URL . '/actions/' . e($id) . '/update' : BASE_URL . '/actions/store' ?>" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Nama Aksi</label>
                        <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                               value="<?= e($values['name'] ?? '') ?>">
                        <div class="invalid-feedback"><?= e($errors['name'] ?? '') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="4" class="form-control"><?= e($values['description'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= BASE_URL ?>/actions" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
