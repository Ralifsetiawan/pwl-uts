<?php
// Fungsi bantuan kecil yang dipakai di banyak file

// e() = singkatan htmlspecialchars, dipakai tiap nampilin data biar aman dari XSS
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// flash message: pesan yang tampil sekali (mis. "Data berhasil disimpan")
function flash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// ambil flash message lalu hapus dari session supaya nggak muncul lagi
function pullFlash()
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

// pindah halaman (path relatif terhadap BASE_URL, mis. '/account')
function redirect($path)
{
    header('Location: ' . BASE_URL . $path);
    exit;
}
