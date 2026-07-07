<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            if (session()->get('role') === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/user/dashboard');
        }
        return view('auth/login');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user  = $model->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // FIX S7+: Cegah session fixation — regenerate (destroy old) DULU, baru set auth data.
            // Kalau set dulu baru regenerate(false), session ID lama masih valid & berisi auth payload.
            session()->regenerate(true);

            session()->set([
                'logged_in' => true,
                'id_user'   => $user['id_user'],
                'nama'      => $user['nama'],
                'username'  => $user['username'],
                'email'     => $user['email'],
                'role'      => $user['role'],
                'foto'      => $user['foto'],
            ]);

            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            }

            // FIX: Cek apakah user sudah isi rekening (untuk refund).
            // Kalau belum, kirim flash message supaya user langsung isi di Profil.
            $rekeningBelumLengkap = empty($user['nomor_rekening']) && empty($user['ewallet_number']);
            // Cek juga apakah kolom rekening sudah ada di DB (defensif)
            if (!kolom_ada('user', 'nomor_rekening')) {
                $rekeningBelumLengkap = false; // skip cek kalau migration belum jalan
            }

            if ($rekeningBelumLengkap) {
                return redirect()->to('/user/dashboard')
                                 ->with('warning_rekening', 'PENTING: Silakan lengkapi data rekening bank atau e-wallet Anda di menu Profil. Data ini diperlukan untuk pengembalian dana (refund) saat checkout, pindah kamar, atau penolakan sewa. Tanpa rekening, proses refund akan tertunda.');
            }

            return redirect()->to('/user/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password salah!');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function registerSave()
    {
        $model = new UserModel();

        $rules = [
            'nama'      => 'required|min_length[3]',
            'email'     => 'required|valid_email|is_unique[user.email]',
            'username'  => 'required|min_length[4]|is_unique[user.username]',
            'password'  => 'required|min_length[6]',
            'no_hp'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'no_hp'    => $this->request->getPost('no_hp'),
            // FIX C7: 'role' dihapus dari $allowedFields. Set via builder() setelah save.
        ]);

        // FIX: set role via builder() karena 'role' gak di $allowedFields (C7 fix).
        $newUserId = $model->getInsertID();
        $model->builder()->where('id_user', $newUserId)->update(['role' => 'user']);

        // FIX Bug #1: Pakai instance UserModel BARU
        $userModelBaru = new UserModel();
        $notifModel    = new \App\Models\NotifikasiModel();
        $admins        = $userModelBaru->where('role', 'admin')->findAll();
        $namaBaru      = $this->request->getPost('nama');
        $hpBaru        = $this->request->getPost('no_hp');
        foreach ($admins as $admin) {
            $notifModel->kirim(
                $admin['id_user'],
                'Pendaftar Baru',
                'Pengguna baru mendaftar: ' . $namaBaru . ' (HP: ' . $hpBaru . '). Belum mengajukan sewa.',
                'user_baru'
            );
        }

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout()
    {
        // FIX BUG: pakai remove() + regenerate(true), BUKAN destroy().
        // destroy() bikin flashdata hilang (PHP session_destroy tandai store sebagai destroyed),
        // akibatnya pesan "Berhasil logout!" gak muncul di halaman login.
        session()->remove(['logged_in', 'id_user', 'nama', 'username', 'email', 'role', 'foto', 'auth_last_check']);
        session()->regenerate(true);
        return redirect()->to('/login')->with('success', 'Berhasil logout!');
    }

    // ============================================
    // FITUR LUPA PASSWORD
    // ============================================

    public function lupaPassword()
    {
        return view('auth/lupa_password');
    }

    public function kirimResetLink()
    {
        $email = $this->request->getPost('email');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Format email tidak valid.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // FIX: jangan enumerate email — kasih pesan identik untuk email valid & invalid.
        $genericSuccess = 'Jika email terdaftar, permintaan reset password telah diproses. Karena sistem email belum aktif, mohon hubungi Admin kos langsung untuk mendapatkan link reset password Anda.';

        if (!$user) {
            // Tetap redirect ke /login (bukan back) supaya behavior identik dengan email valid.
            return redirect()->to('/login')->with('success', $genericSuccess);
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $db = \Config\Database::connect();
        $db->table('password_reset')->insert([
            'email'      => $email,
            'token'      => $token,
            'expires_at' => $expiresAt,
            'used'       => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // FIX: $resetLink tidak pernah dikirim (email belum aktif) — hapus dead variable.
        // Admin bisa lihat token di tabel password_reset untuk dikasih ke user manual.

        return redirect()->to('/login')->with('success', $genericSuccess);
    }

    public function resetPassword($token)
    {
        $db = \Config\Database::connect();
        $reset = $db->table('password_reset')
                    ->where('token', $token)
                    ->where('used', 0)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->get()
                    ->getRowArray();

        if (!$reset) {
            return view('auth/reset_error', [
                'message' => 'Link reset password tidak valid atau sudah kedaluwarsa. Silakan ajukan reset password ulang.'
            ]);
        }

        return view('auth/reset_password', [
            'token' => $token,
            'email' => $reset['email'],
        ]);
    }

    public function updatePassword()
    {
        $token    = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm_password');

        if (empty($token) || empty($password)) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $db = \Config\Database::connect();
        $reset = $db->table('password_reset')
                    ->where('token', $token)
                    ->where('used', 0)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->get()
                    ->getRowArray();

        if (!$reset) {
            return view('auth/reset_error', [
                'message' => 'Token tidak valid atau sudah kedaluwarsa.'
            ]);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $reset['email'])->first();
        if (!$user) {
            return redirect()->to('/lupa-password')->with('error', 'User tidak ditemukan.');
        }

        // FIX: bungkus update password + invalidasi token dalam transaksi.
        // Invalidasi SEMUA token untuk email ini (bukan cuma token sekarang)
        // supaya token lain yang bocor tidak bisa dipakai ulang.
        $db->transBegin();
        try {
            // Hook hashPassword di UserModel akan hash otomatis.
            $userModel->update($user['id_user'], ['password' => $password]);

            $db->table('password_reset')
                ->where('email', $reset['email'])
                ->where('used', 0)
                ->update(['used' => 1]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Gagal update password. Coba lagi.');
            }
            $db->transCommit();
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal update password: ' . $e->getMessage());
        }

        return redirect()->to('/login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
}