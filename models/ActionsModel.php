<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

// Model untuk tabel actions (jenis aksi / tipe akses: Create, Read, Update, Delete)
class ActionsModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    // semua aksi yang belum dihapus, bisa dicari lewat nama / deskripsi
    public function getAll($q = '')
    {
        $like = '%' . $q . '%';
        $stmt = $this->db->prepare(
            "SELECT * FROM actions
             WHERE deleted_at IS NULL AND (name LIKE ? OR description LIKE ?)
             ORDER BY name ASC"
        );
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM actions WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function create($data)
    {
        $id   = Uuid::uuid4()->toString();
        $stmt = $this->db->prepare(
            "INSERT INTO actions (id, name, description, created_at, updated_at)
             VALUES (?, ?, ?, NOW(), NOW())"
        );
        $stmt->execute([$id, $data['name'], $data['description']]);
        return $id;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE actions SET name = ?, description = ?, updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }

    // soft delete
    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE actions SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
