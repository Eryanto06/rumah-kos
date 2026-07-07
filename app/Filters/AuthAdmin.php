<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAdmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Silakan login sebagai admin!');
        }

        // FIX H15: re-validate user ke DB minimal 1x per 5 menit.
        // Cegah stale session: kalau admin di-delete atau di-demote,
        // session lama tetap valid sampai TTL 2 jam.
        //
        // FIX CRITICAL: session()->get() di CI4 hanya terima 1 argumen.
        // Versi sebelumnya pakai session()->get('auth_last_check', 0) →
        // throw ArgumentCountError karena strict_types=1 di Session.php.
        // Akibatnya SETIAP request ke /admin/* crash 500 → "tidak bisa buka".
        $lastCheck = session()->get('auth_last_check');
        if ($lastCheck === null) $lastCheck = 0;
        if (time() - $lastCheck > 300) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find(session()->get('id_user'));
            if (!$user || $user['role'] !== 'admin') {
                // FIX: pakai regenerate(true) + remove auth data, BUKAN destroy().
                // destroy() bikin flashdata hilang (PHP session_destroy tandai store sebagai destroyed).
                session()->remove(['logged_in', 'id_user', 'role', 'nama', 'username', 'email', 'foto', 'auth_last_check']);
                session()->regenerate(true);
                return redirect()->to('/login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
            }
            session()->set('auth_last_check', time());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
