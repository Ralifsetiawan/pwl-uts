# UTS Pemrograman Web Lanjut - Manajemen Akun

PHP Native MVC (berbasis `pwl-sample`) + PDO + MySQL + Bootstrap 5.

## Fitur
- Login email + password (password di-hash `password_hash`)
- CRUD + pencarian: **Akun**, **Tipe Akun**, **Jenis Aksi**
- Soft delete (`deleted_at`), primary key UUID

## Cara jalanin
1. Pastikan database `pbl_ti_2025_a_ridhoalifsetiawan` sudah ada beserta 3 tabelnya (lihat `database/schema.sql`).
2. Install dependency: `composer install`
3. Isi data awal (tipe akun, aksi, admin): `composer seed`
4. Jalankan server: `composer serve` lalu buka http://localhost:5000
   - atau taruh folder ini di `htdocs` XAMPP (mod_rewrite harus aktif) dan buka http://localhost/pwl-uts
5. Login: `admin@pnj.ac.id` / `admin123`

Kredensial DB (default XAMPP: root, tanpa password) ada di `config/database.php`.

## Struktur
```
index.php            front controller + routing + penjaga login
core/                Loader (view) & helpers (e, flash, redirect)
controllers/         Auth, Account, AccountType, Actions
models/              AccountModel, AccountTypeModel, ActionsModel
views/               layout, auth, account, account_type, actions
tools/seed.php       data awal
```
