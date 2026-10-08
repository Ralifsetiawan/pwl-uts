<?php

require_once __DIR__ . '/../models/ActionsModel.php';

// Controller CRUD Jenis Aksi / Tipe Akses. URL: /actions
class Actions
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new ActionsModel();
        $this->load  = new Loader();
    }

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
            $errors['name'] = 'Nama aksi wajib diisi.';
        } elseif (mb_strlen($data['name']) > 128) {
            $errors['name'] = 'Maksimal 128 karakter.';
        }
        return $errors;
    }

    // GET /actions?q=...
    public function index()
    {
        $q = trim($_GET['q'] ?? '');
        $this->load->view('views/actions/index.php', [
            'title' => 'Jenis Aksi',
            'items' => $this->model->getAll($q),
            'q'     => $q,
        ]);
    }

    // GET /actions/create
    public function create()
    {
        $this->load->view('views/actions/form.php', [
            'title'  => 'Tambah Jenis Aksi',
            'isEdit' => false,
            'values' => ['name' => '', 'description' => ''],
            'errors' => [],
        ]);
    }

    // POST /actions/store
    public function store()
    {
        $data   = $this->collect($_POST);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->load->view('views/actions/form.php', [
                'title'  => 'Tambah Jenis Aksi',
                'isEdit' => false,
                'values' => $data,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->create($data);
        flash('success', 'Jenis aksi berhasil ditambahkan.');
        redirect('/actions');
    }

    // GET /actions/{id}/edit
    public function edit($id)
    {
        $item = $this->model->getById($id);
        if ($item === null) {
            http_response_code(404);
            echo '404 - Jenis aksi tidak ditemukan';
            return;
        }

        $this->load->view('views/actions/form.php', [
            'title'  => 'Ubah Jenis Aksi',
            'isEdit' => true,
            'id'     => $id,
            'values' => $item,
            'errors' => [],
        ]);
    }

    // POST /actions/{id}/update
    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Jenis aksi tidak ditemukan';
            return;
        }

        $data   = $this->collect($_POST);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->load->view('views/actions/form.php', [
                'title'  => 'Ubah Jenis Aksi',
                'isEdit' => true,
                'id'     => $id,
                'values' => $data,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->update($id, $data);
        flash('success', 'Jenis aksi berhasil diubah.');
        redirect('/actions');
    }

    // POST /actions/{id}/delete (soft delete)
    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Jenis aksi tidak ditemukan';
            return;
        }

        $this->model->delete($id);
        flash('success', 'Jenis aksi berhasil dihapus.');
        redirect('/actions');
    }
}
