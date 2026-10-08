<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
// helper kecil: kasih class is-invalid kalau field itu error
function inv($errors, $field) { return isset($errors[$field]) ? 'is-invalid' : ''; }
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><?= $isEdit ? 'Ubah Akun' : 'Tambah Akun' ?></h5></div>
            <div class="card-body">
                <form action="<?= $isEdit ? BASE_URL . '/account/' . e($id) . '/update' : BASE_URL . '/account/store' ?>" method="POST">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control <?= inv($errors, 'name') ?>" value="<?= e($values['name'] ?? '') ?>">
                            <div class="invalid-feedback"><?= e($errors['name'] ?? '') ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control <?= inv($errors, 'email') ?>" value="<?= e($values['email'] ?? '') ?>">
                            <div class="invalid-feedback"><?= e($errors['email'] ?? '') ?></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control <?= inv($errors, 'password') ?>"
                                   placeholder="<?= $isEdit ? 'Kosongkan jika tidak ingin mengganti password' : '' ?>">
                            <div class="invalid-feedback"><?= e($errors['password'] ?? '') ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipe Akun</label>
                            <select name="account_type_id" class="form-select <?= inv($errors, 'account_type_id') ?>">
                                <option value="">-- Pilih tipe akun --</option>
                                <?php foreach ($types as $t): ?>
                                    <option value="<?= e($t['id']) ?>" <?= ($values['account_type_id'] ?? '') === $t['id'] ? 'selected' : '' ?>>
                                        <?= e($t['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= e($errors['account_type_id'] ?? '') ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select <?= inv($errors, 'status') ?>">
                                <?php foreach (['Aktif', 'Nonaktif'] as $s): ?>
                                    <option value="<?= $s ?>" <?= ($values['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= e($errors['status'] ?? '') ?></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jenis Identitas</label>
                            <select name="identification_type" class="form-select <?= inv($errors, 'identification_type') ?>">
                                <?php foreach (['NIM', 'NIP'] as $it): ?>
                                    <option value="<?= $it ?>" <?= ($values['identification_type'] ?? '') === $it ? 'selected' : '' ?>><?= $it ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= e($errors['identification_type'] ?? '') ?></div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nomor Identitas (NIM / NIP)</label>
                            <input type="text" name="identification_number" class="form-control <?= inv($errors, 'identification_number') ?>"
                                   value="<?= e($values['identification_number'] ?? '') ?>">
                            <div class="invalid-feedback"><?= e($errors['identification_number'] ?? '') ?></div>
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= BASE_URL ?>/account" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
