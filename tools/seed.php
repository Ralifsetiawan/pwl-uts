<?php
// Isi data awal: 3 tipe akun, 4 jenis aksi, dan 1 akun admin.
// Jalankan sekali:  composer seed   (atau: php tools/seed.php)
// Aman dijalankan berulang (data yang sudah ada nggak diinsert lagi).

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php'; // bikin variabel $conn

use Ramsey\Uuid\Uuid;

// ---------- tipe akun ----------
$types = [
    ['Admin',     'Pengelola sistem, punya akses penuh.'],
    ['Dosen',     'Akun untuk dosen (identitas NIP).'],
    ['Mahasiswa', 'Akun untuk mahasiswa (identitas NIM).'],
];
foreach ($types as [$name, $desc]) {
    $cek = $conn->prepare("SELECT COUNT(*) FROM account_type WHERE name = ? AND deleted_at IS NULL");
    $cek->execute([$name]);
    if ($cek->fetchColumn() == 0) {
        $conn->prepare("INSERT INTO account_type (id, name, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())")
             ->execute([Uuid::uuid4()->toString(), $name, $desc]);
        echo "Tipe akun '$name' dibuat\n";
    }
}

// ---------- jenis aksi ----------
$actions = [
    ['Create', 'Menambahkan data baru.'],
    ['Delete', 'Menghapus data (soft delete).'],
    ['Read',   'Melihat data.'],
    ['Update', 'Mengubah data yang sudah ada.'],
];
foreach ($actions as [$name, $desc]) {
    $cek = $conn->prepare("SELECT COUNT(*) FROM actions WHERE name = ? AND deleted_at IS NULL");
    $cek->execute([$name]);
    if ($cek->fetchColumn() == 0) {
        $conn->prepare("INSERT INTO actions (id, name, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())")
             ->execute([Uuid::uuid4()->toString(), $name, $desc]);
        echo "Aksi '$name' dibuat\n";
    }
}

// ---------- akun admin ----------
$email = 'admin@pnj.ac.id';
$cek = $conn->prepare("SELECT COUNT(*) FROM account WHERE email = ?");
$cek->execute([$email]);
if ($cek->fetchColumn() == 0) {
    // ambil id tipe 'Admin' buat foreign key
    $t = $conn->prepare("SELECT id FROM account_type WHERE name = 'Admin' AND deleted_at IS NULL LIMIT 1");
    $t->execute();
    $adminTypeId = $t->fetchColumn();

    $conn->prepare(
        "INSERT INTO account (id, name, email, password, account_type_id, status, identification_type, identification_number, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, 'Aktif', 'NIP', ?, NOW(), NOW())"
    )->execute([
        Uuid::uuid4()->toString(),
        'Administrator',
        $email,
        password_hash('admin123', PASSWORD_DEFAULT), // password awal: admin123
        $adminTypeId,
        '198001012005011001',
    ]);
    echo "Akun admin dibuat -> $email / admin123\n";
}

echo "Selesai.\n";
