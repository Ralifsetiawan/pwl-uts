<?php

require_once __DIR__ . '/../models/AccountTypeModel.php';

// Controller CRUD Tipe Akun. URL: /account-type
class AccountType
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new AccountTypeModel();
        $this->load  = new Loader();
    }

    // rapikan input dari form
    private function collect($post)
    {
        return [
            'name'        => trim($post['name'] ?? ''),
            'description' => trim($post['description'] ?? ''),
        ];
    }

    private function validate($data)
    {
        $errors = [];
        if ($data['name'] === '') {
            $errors['name'] = 'Nama tipe akun wajib diisi.';
        } elseif (mb_strlen($data['name']) > 128) {
            $errors['name'] = 'Maksimal 128 karakter.';
        }
        return $errors;
    }

    // GET /account-type?q=... -> daftar + pencarian
    public function index()
    {
        $q = trim($_GET['q'] ?? '');
        $this->load->view('views/account_type/index.php', [
            'title' => 'Tipe Akun',
            'items' => $this->model->getAll($q),
            'q'     => $q,
        ]);
    }

    // GET /account-type/create -> form tambah
    public function create()
    {
        $this->load->view('views/account_type/form.php', [
            'title'  => 'Tambah Tipe Akun',
            'isEdit' => false,
            'values' => ['name' => '', 'description' => ''],
            'errors' => [],
        ]);
    }

    // POST /account-type/store -> simpan data baru
    public function store()
    {
        $data   = $this->collect($_POST);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->load->view('views/account_type/form.php', [
                'title'  => 'Tambah Tipe Akun',
                'isEdit' => false,
                'values' => $data,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->create($data);
        flash('success', 'Tipe akun berhasil ditambahkan.');
        redirect('/account-type');
    }

    // GET /account-type/{id}/edit
    public function edit($id)
    {
        $item = $this->model->getById($id);
        if ($item === null) {
            http_response_code(404);
            echo '404 - Tipe akun tidak ditemukan';
            return;
        }

        $this->load->view('views/account_type/form.php', [
            'title'  => 'Ubah Tipe Akun',
            'isEdit' => true,
            'id'     => $id,
            'values' => $item,
            'errors' => [],
        ]);
    }

    // POST /account-type/{id}/update
    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Tipe akun tidak ditemukan';
            return;
        }

        $data   = $this->collect($_POST);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->load->view('views/account_type/form.php', [
                'title'  => 'Ubah Tipe Akun',
                'isEdit' => true,
                'id'     => $id,
                'values' => $data,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->update($id, $data);
        flash('success', 'Tipe akun berhasil diubah.');
        redirect('/account-type');
    }

    // POST /account-type/{id}/delete (soft delete)
    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Tipe akun tidak ditemukan';
            return;
        }

        // tipe yang masih dipakai akun nggak boleh dihapus
        if ($this->model->countAccounts($id) > 0) {
            flash('danger', 'Tipe akun masih dipakai oleh akun lain, tidak bisa dihapus.');
            redirect('/account-type');
        }

        $this->model->delete($id);
        flash('success', 'Tipe akun berhasil dihapus.');
        redirect('/account-type');
    }
}
