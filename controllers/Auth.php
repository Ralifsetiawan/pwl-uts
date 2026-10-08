<?php

require_once __DIR__ . '/../models/AccountModel.php';

// Controller login/logout
class Auth
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new AccountModel();
        $this->load  = new Loader();
    }

    // GET /auth/login -> tampilkan form login
    public function login()
    {
        // kalau sudah login, nggak usah lihat form lagi
        if (!empty($_SESSION['user'])) {
            redirect('/account');
        }
        $this->load->view('views/auth/login.php', ['email' => '', 'error' => null]);
    }

    // POST /auth/authenticate -> proses login
    public function authenticate()
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->model->findByEmail($email);

        // password_verify membandingkan input dengan hash di database
        if ($user === null || !password_verify($password, $user['password'])) {
            $this->load->view('views/auth/login.php', [
                'email' => $email,
                'error' => 'Email atau password salah.',
            ]);
            return;
        }

        // akun nonaktif nggak boleh masuk
        if ($user['status'] !== 'Aktif') {
            $this->load->view('views/auth/login.php', [
                'email' => $email,
                'error' => 'Akun kamu nonaktif. Hubungi administrator.',
            ]);
            return;
        }

        session_regenerate_id(true); // ganti session id (cegah session fixation)
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
        ];

        redirect('/account');
    }

    // POST /auth/logout
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        redirect('/auth/login');
    }
}
