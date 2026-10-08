<?php

require_once __DIR__ . '/../models/AccountModel.php';
require_once __DIR__ . '/../models/AccountTypeModel.php';

// Controller CRUD Akun. URL: /account
class Account
{
    private $model;
    private $typeModel; // dipakai buat isi dropdown "Tipe Akun"
    private $load;

    public function __construct()
    {
        $this->model     = new AccountModel();
        $this->typeModel = new AccountTypeModel();
        $this->load      = new Loader();
    }

    // rapikan input form (trim spasi)
    private function collect($post)
    {
        return [
            'name'                  => trim($post['name'] ?? ''),
            'email'                 => trim($post['email'] ?? ''),
            'password'              => $post['password'] ?? '',
            'account_type_id'       => trim($post['account_type_id'] ?? ''),
            'status'                => trim($post['status'] ?? ''),
            'identification_type'   => trim($post['identification_type'] ?? ''),
            'identification_number' => trim($post['identification_number'] ?? ''),
        ];
    }

    // validasi input; $id = null saat tambah, berisi id saat ubah
    private function validate($data, $id = null)
    {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Nama wajib diisi.';
        }

        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid.';
        } elseif ($this->model->emailExists($data['email'], $id)) {
            $errors['email'] = 'Email sudah dipakai.';
        }

        // password wajib saat tambah; saat ubah boleh kosong (= nggak diganti)
        if ($id === null && $data['password'] === '') {
            $errors['password'] = 'Password wajib diisi.';
        } elseif ($data['password'] !== '' && strlen($data['password']) < 6) {
            $errors['password'] = 'Password minimal 6 karakter.';
        }

        if ($this->typeModel->getById($data['account_type_id']) === null) {
            $errors['account_type_id'] = 'Pilih tipe akun.';
        }

        if (!in_array($data['status'], ['Aktif', 'Nonaktif'], true)) {
            $errors['status'] = 'Pilih status.';
        }

        if (!in_array($data['identification_type'], ['NIM', 'NIP'], true)) {
            $errors['identification_type'] = 'Pilih jenis identitas.';
        }

        if ($data['identification_number'] === '') {
            $errors['identification_number'] = 'Nomor identitas wajib diisi.';
        }

        return $errors;
    }

    // GET /account?q=... -> daftar akun + pencarian
    public function index()
    {
        $q = trim($_GET['q'] ?? '');
        $this->load->view('views/account/index.php', [
            'title'    => 'Manajemen Akun',
            'accounts' => $this->model->getAll($q),
            'q'        => $q,
        ]);
    }

    // GET /account/create -> form tambah
    public function create()
    {
        $this->load->view('views/account/form.php', [
            'title'  => 'Tambah Akun',
            'isEdit' => false,
            'types'  => $this->typeModel->getAll(),
            'values' => [
                'name' => '', 'email' => '', 'account_type_id' => '',
                'status' => 'Aktif', 'identification_type' => 'NIM', 'identification_number' => '',
            ],
            'errors' => [],
        ]);
    }

    // POST /account/store -> simpan akun baru
    public function store()
    {
        $data   = $this->collect($_POST);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->load->view('views/account/form.php', [
                'title'  => 'Tambah Akun',
                'isEdit' => false,
                'types'  => $this->typeModel->getAll(),
                'values' => $data,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->create($data);
        flash('success', 'Akun berhasil ditambahkan.');
        redirect('/account');
    }

    // GET /account/{id}/edit
    public function edit($id)
    {
        $account = $this->model->getById($id);
        if ($account === null) {
            http_response_code(404);
            echo '404 - Akun tidak ditemukan';
            return;
        }

        $this->load->view('views/account/form.php', [
            'title'  => 'Ubah Akun',
            'isEdit' => true,
            'id'     => $id,
            'types'  => $this->typeModel->getAll(),
            'values' => $account,
            'errors' => [],
        ]);
    }

    // POST /account/{id}/update
    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Akun tidak ditemukan';
            return;
        }

        $data   = $this->collect($_POST);
        $errors = $this->validate($data, $id);

        if (!empty($errors)) {
            $this->load->view('views/account/form.php', [
                'title'  => 'Ubah Akun',
                'isEdit' => true,
                'id'     => $id,
                'types'  => $this->typeModel->getAll(),
                'values' => $data,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->update($id, $data);
        flash('success', 'Akun berhasil diubah.');
        redirect('/account');
    }

    // POST /account/{id}/delete (soft delete)
    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Akun tidak ditemukan';
            return;
        }

        // jangan sampai hapus akun sendiri yang lagi login
        if ($id === ($_SESSION['user']['id'] ?? null)) {
            flash('danger', 'Kamu tidak bisa menghapus akun yang sedang dipakai login.');
            redirect('/account');
        }

        $this->model->delete($id);
        flash('success', 'Akun berhasil dihapus.');
        redirect('/account');
    }
}
