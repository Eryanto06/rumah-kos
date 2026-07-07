<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $data = [
            'title'  => 'Data Admin',
            'admins' => $model->where('role', 'admin')->findAll(),
        ];
        return view('admin/admin/index', $data);
    }

    public function tambah()
    {
        return view('admin/admin/tambah', ['title' => 'Tambah Admin']);
    }

    public function simpan()
    {
        $model = new UserModel();
        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[user.email]',
            'username' => 'required|min_length[4]|is_unique[user.username]',
            'password' => 'required|min_length[6]',
            'no_hp'    => 'required',
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

        // FIX: set role='admin' via builder() karena 'role' gak di $allowedFields (C7 fix).
        $newAdminId = $model->getInsertID();
        $model->builder()->where('id_user', $newAdminId)->update(['role' => 'admin']);
        return redirect()->to('/admin/admin')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    // === METHOD EDIT BARU ===
    public function edit($id)
    {
        $model = new UserModel();
        $admin = $model->find($id);

        if (!$admin || $admin['role'] !== 'admin') {
            return redirect()->to('/admin/admin')->with('error', 'Data admin tidak ditemukan.');
        }

        return view('admin/admin/edit', [
            'title' => 'Edit Admin',
            'admin' => $admin,
        ]);
    }

    // === METHOD UPDATE BARU ===
    public function update($id)
    {
        $model = new UserModel();
        $admin = $model->find($id);

        if (!$admin) {
            return redirect()->to('/admin/admin')->with('error', 'Admin tidak ditemukan.');
        }

        // FIX: cek role target — tanpa ini admin bisa membajak akun user biasa
        // via /admin/admin/update/{user_id} (overwrite email/password user).
        if ($admin['role'] !== 'admin') {
            return redirect()->to('/admin/admin')->with('error', 'Hanya akun admin yang bisa diedit via menu ini. Akun user diedit via menu User.');
        }

        $rules = [
            'nama'  => 'required|min_length[3]',
            'no_hp' => 'required',
        ];

        // FIX: pakai is_unique dengan id_user, supaya nilai yang sama persis
        // (mis. beda case / whitespace) tidak menyalahkan baris ini sendiri.
        $email    = $this->request->getPost('email');
        $username = $this->request->getPost('username');
        $rules['email']    = "required|valid_email|is_unique[user.email,id_user,{$id}]";
        $rules['username'] = "required|min_length[4]|is_unique[user.username,id_user,{$id}]";

        // Validasi password (kalau diisi)
        $passwordBaru = $this->request->getPost('password');
        if (!empty($passwordBaru)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama'     => $this->request->getPost('nama'),
            'email'    => $email,
            'username' => $username,
            'no_hp'    => $this->request->getPost('no_hp'),
        ];

        // Update password hanya kalau diisi
        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        $model->update($id, $dataUpdate);

        return redirect()->to('/admin/admin')->with('success', 'Data admin berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $model = new UserModel();
        $target = $model->find($id);

        if (!$target) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan.');
        }

        // FIX: hanya role admin yang boleh dihapus di sini
        if ($target['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Hanya akun admin yang bisa dihapus via menu ini.');
        }

        // Cegah hapus diri sendiri
        if ((string)$id === (string)session()->get('id_user')) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        // FIX: cegah hapus admin terakhir → lockout
        $adminCount = $model->where('role', 'admin')->countAllResults();
        if ($adminCount <= 1) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus admin terakhir! Tambahkan admin lain dulu.');
        }

        $model->delete($id);
        return redirect()->to('/admin/admin')->with('success', 'Admin berhasil dihapus.');
    }
}