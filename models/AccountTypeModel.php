<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

// Model = satu-satunya tempat yang ngomong langsung ke database (tabel account_type)
class AccountTypeModel
{
    private $db;

    public function __construct()
    {
        global $conn;      // koneksi PDO dari config/database.php
        $this->db = $conn;
    }

    // ambil semua tipe akun yang belum dihapus; $q = kata kunci pencarian (boleh kosong)
    public function getAll($q = '')
    {
        $like = '%' . $q . '%';
        $stmt = $this->db->prepare(
            "SELECT * FROM account_type
             WHERE deleted_at IS NULL AND (name LIKE ? OR description LIKE ?)
             ORDER BY name ASC"
        );
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM account_type WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function create($data)
    {
        $id = Uuid::uuid4()->toString(); // primary key berupa UUID

        // created_at & updated_at diisi NOW() manual karena kolomnya NOT NULL tanpa default
        $stmt = $this->db->prepare(
            "INSERT INTO account_type (id, name, description, created_at, updated_at)
             VALUES (?, ?, ?, NOW(), NOW())"
        );
        $stmt->execute([$id, $data['name'], $data['description']]);
        return $id;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE account_type SET name = ?, description = ?, updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }

    // SOFT DELETE: baris nggak dihapus beneran, cuma deleted_at diisi
    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE account_type SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // hitung akun aktif yang masih pakai tipe ini (buat cegah hapus tipe yang lagi dipakai)
    public function countAccounts($id)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM account WHERE account_type_id = ? AND deleted_at IS NULL"
        );
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }
}
