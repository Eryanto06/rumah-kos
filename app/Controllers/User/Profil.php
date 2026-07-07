<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profil extends BaseController {

    public function index()
    {
        $model = new UserModel();
        $id_user = session()->get('id_user');
        
        // FIX H10: exclude password dari select supaya hash gak bocor ke view.
        // FIX: sertakan field rekening supaya user bisa lihat & edit rekeningnya.
        return view('user/profil', [
            'title' => 'Profil Saya',
            'user'  => $this->getUserWithRekening($model, $id_user),
        ]);
    }

    public function update()
    {
        $model = new UserModel();
        $id_user = session()->get('id_user');
        $user = $model->find($id_user);

        // === FIX H18: Require password lama untuk konfirmasi perubahan profil ===
        // Cegah akun takeover: walau session dicuri, attacker gak bisa ubah profil
        // tanpa tahu password lama.
        $passwordLama = $this->request->getPost('password_lama');
        if (empty($passwordLama) || !password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password lama salah. Masukkan password Anda saat ini untuk konfirmasi perubahan profil.');
        }

        // === FIX H17: Email change butuh verifikasi — flag sebagai 'pending_email' ===
        $emailBaru = $this->request->getPost('email');
        $emailLama = $user['email'];
        $emailBerubah = (strtolower($emailBaru) !== strtolower($emailLama));

        // === VALIDASI ===
        $rules = [
            'nama'  => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[user.email,id_user,{$id_user}]",
            'no_hp' => 'required|min_length[10]|max_length[15]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama'  => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('no_hp'),
        ];

        // === FIX BUG: simpan info rekening user supaya admin bisa transfer refund ===
        // (checkout, pindah kamar, sewa ditolak). Tanpa ini, admin harus chat user
        // manual untuk tanya no rekening — rawan penipuan & lama.
        $namaBank       = $this->request->getPost('nama_bank');
        $nomorRekening  = $this->request->getPost('nomor_rekening');
        $namaPemilikRek = $this->request->getPost('nama_pemilik_rek');
        $ewalletType    = $this->request->getPost('ewallet_type');
        $ewalletNumber  = $this->request->getPost('ewallet_number');

        // Validasi: kalau user isi nomor rekening, nama bank & pemilik WAJIB diisi.
        if (!empty($nomorRekening) && (empty($namaBank) || empty($namaPemilikRek))) {
            return redirect()->back()->withInput()->with('error', 'Nama Bank & Nama Pemilik Rekening wajib diisi jika Nomor Rekening diisi.');
        }
        // Validasi: kalau user isi e-wallet, type & number WAJIB diisi.
        if (!empty($ewalletNumber) && empty($ewalletType)) {
            return redirect()->back()->withInput()->with('error', 'Jenis E-Wallet wajib dipilih jika Nomor E-Wallet diisi.');
        }
        // Validasi nomor rekening: hanya digit & spasi (anti XSS via rekening).
        if (!empty($nomorRekening) && !preg_match('/^[0-9\s\-]+$/', $nomorRekening)) {
            return redirect()->back()->withInput()->with('error', 'Nomor rekening hanya boleh angka, spasi, atau tanda hubung.');
        }
        if (!empty($ewalletNumber) && !preg_match('/^[0-9\s\-]+$/', $ewalletNumber)) {
            return redirect()->back()->withInput()->with('error', 'Nomor e-wallet hanya boleh angka, spasi, atau tanda hubung.');
        }

        $dataUpdate['nama_bank']        = $namaBank ?: null;
        $dataUpdate['nomor_rekening']   = $nomorRekening ?: null;
        $dataUpdate['nama_pemilik_rek'] = $namaPemilikRek ?: null;
        $dataUpdate['ewallet_type']     = $ewalletType ?: null;
        $dataUpdate['ewallet_number']   = $ewalletNumber ?: null;

        // FIX H17: kalau email berubah, JANGAN langsung apply. Simpan di session
        // sebagai 'pending_email', kirim verifikasi ke email baru, baru apply setelah
        // user klik link verifikasi. Untuk simplifikasi (SMTP belum aktif), kita
        // TETAP apply email baru tapi require password lama (sudah di atas) sebagai guard.
        if ($emailBerubah) {
            // Catat perubahan email ke log untuk audit trail
            log_message('info', "[Profil::update] User #{$id_user} ganti email: {$emailLama} → {$emailBaru}. Verifikasi password lama OK.");
            $dataUpdate['email'] = $emailBaru;
        }

        // === Handle upload foto dengan VALIDASI ===
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {

            // FIX CRITICAL: MIME bisa di-spoof, extension bisa .php.
            // Validasi extension eksplisit + verifikasi gambar via getimagesize.
            $allowedMime = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower($file->getExtension());
            if (!in_array($ext, $allowedExt, true) || !in_array($file->getMimeType(), $allowedMime, true)) {
                return redirect()->back()->withInput()->with('error', 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.');
            }

            // Validasi ukuran (max 2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran foto maksimal 2MB.');
            }

            // FIX: verifikasi file benar-benar gambar (bukan .php dengan MIME palsu)
            $tmpPath = $file->getTempName();
            $imgInfo = @getimagesize($tmpPath);
            if ($imgInfo === false) {
                return redirect()->back()->withInput()->with('error', 'File bukan gambar valid.');
            }

            // FIX: pakai nama dengan extension dari whitelist (bukan getRandomName yang jaga extension asli).
            // FIX BUG: $file->move() throw HTTPException saat gagal — bukan return false.
            // Bungkus try-catch supaya kalau gagal, user dapat pesan error jelas & foto lama tidak terhapus.
            try {
                $namaFoto = $file->getRandomName();
                $namaFoto = pathinfo($namaFoto, PATHINFO_FILENAME) . '.' . $ext;
                $file->move(ROOTPATH . 'public/uploads/', $namaFoto);
            } catch (\Throwable $e) {
                log_message('error', '[Profil::update] Gagal upload foto: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal upload foto: ' . $e->getMessage() . '. Coba lagi.');
            }

            // Hapus foto lama
            if (!empty($user['foto'])) {
                $pathLama = ROOTPATH . 'public/uploads/' . $user['foto'];
                if (file_exists($pathLama) && $user['foto'] !== $namaFoto) {
                    @unlink($pathLama);
                }
            }

            $dataUpdate['foto'] = $namaFoto;
        }

        $model->update($id_user, $dataUpdate);

        // Update session
        session()->set([
            'nama'  => $dataUpdate['nama'],
            'email' => $dataUpdate['email'] ?? $user['email'],
            'foto'  => $dataUpdate['foto'] ?? $user['foto'],
        ]);

        return redirect()->to('/user/profil')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Ambil data user + field rekening (defensif).
     * Kalau migration belum dijalankan (kolom nama_bank dll belum ada),
     * fallback ke select tanpa field rekening supaya tidak error.
     */
    private function getUserWithRekening($model, $id_user)
    {
        $colsRekening = ['nama_bank', 'nomor_rekening', 'nama_pemilik_rek', 'ewallet_type', 'ewallet_number'];
        $colsAda = [];
        foreach ($colsRekening as $c) {
            if (kolom_ada('user', $c)) {
                $colsAda[] = $c;
            }
        }
        $select = 'id_user, nama, email, username, no_hp, foto, role'
                 . (!empty($colsAda) ? ', ' . implode(', ', $colsAda) : '');
        return $model->select($select)->find($id_user);
    }
}