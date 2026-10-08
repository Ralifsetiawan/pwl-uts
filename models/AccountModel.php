<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

// Model untuk tabel account (join ke account_type buat nampilin nama tipe)
class AccountModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    // daftar akun + pencarian by nama, email, NIM/NIP, atau nama tipe akun
    public function getAll($q = '')
    {
        $like = '%' . $q . '%';
        $stmt = $this->db->prepare(
            "SELECT a.*, t.name AS account_type_name
             FROM account a
             JOIN account_type t ON t.id = a.account_type_id
             WHERE a.deleted_at IS NULL
               AND (a.name LIKE ? OR a.email LIKE ? OR a.identification_number LIKE ? OR t.name LIKE ?)
             ORDER BY a.name ASC"
        );
        $stmt->execute([$like, $like, $like, $like]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    // dipakai saat login: cari akun aktif berdasarkan email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE email = ? AND deleted_at IS NULL");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    // cek email sudah dipakai atau belum (termasuk akun yang sudah soft delete,
    // karena kolom email UNIQUE tetap berlaku untuk baris yang soft delete).
    // $exceptId = id akun yang lagi diedit (biar email miliknya sendiri nggak dianggap dobel)
    public function emailExists($email, $exceptId = null)
    {
        if ($exceptId === null) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM account WHERE email = ?");
            $stmt->execute([$email]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM account WHERE email = ? AND id <> ?");
            $stmt->execute([$email, $exceptId]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create($data)
    {
        $id = Uuid::uuid4()->toString();

        // password JANGAN disimpan polos -> di-hash (bcrypt) pakai password_hash
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO account
                (id, name, email, password, account_type_id, status,
                 identification_type, identification_number, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        $stmt->execute([
            $id,
            $data['name'],
            $data['email'],
            $hash,
            $data['account_type_id'],
            $data['status'],
            $data['identification_type'],
            $data['identification_number'],
        ]);
        return $id;
    }

    public function update($id, $data)
    {
        $params = [
            $data['name'],
            $data['email'],
            $data['account_type_id'],
            $data['status'],
            $data['identification_type'],
            $data['identification_number'],
        ];

        if ($data['password'] !== '') {
            // password diisi -> ikut diganti
            $sql      = "UPDATE account SET name = ?, email = ?, account_type_id = ?, status = ?,
                         identification_type = ?, identification_number = ?, password = ?, updated_at = NOW()
                         WHERE id = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            // password dikosongin -> password lama dipertahankan
            $sql = "UPDATE account SET name = ?, email = ?, account_type_id = ?, status = ?,
                    identification_type = ?, identification_number = ?, updated_at = NOW()
                    WHERE id = ?";
        }
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // soft delete
    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE account SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
