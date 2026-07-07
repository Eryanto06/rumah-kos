<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SewaModel;
use App\Models\KamarModel;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;

class Sewa extends BaseController
{
    protected $sewaModel;
    protected $kamarModel;
    protected $pembayaranModel;
    protected $notifikasiModel;

    public function __construct()
    {
        $this->sewaModel       = new SewaModel();
        $this->kamarModel      = new KamarModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $allowedStatus = ['menunggu', 'aktif', 'ditolak', 'selesai'];
        if (!in_array($status, $allowedStatus)) {
            $status = null;
        }

        $counts = $this->sewaModel->countByStatus();

        $data = [
            'title'        => 'Pengajuan Sewa',
            'sewa'         => $this->sewaModel->getSewaWithDetail($status),
            'filter_status'=> $status,
            'counts'       => $counts,
        ];
        return view('admin/sewa/index', $data);
    }

    public function detail($id)
    {
        $sewa = $this->sewaModel->find($id);
        if (!$sewa) {
            return redirect()->to('/admin/sewa')->with('error', 'Data sewa tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $sewaDetail = $db->table('sewa s')
            ->select('s.*, u.nama, u.email, u.no_hp' . rekening_select_clause('u') . ', k.kode_kamar, k.nomor_kamar, k.harga_sewa, k.fasilitas, k.foto')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.id_sewa', $id)
            ->get()->getRowArray();

        $data = [
            'title'      => 'Detail Pengajuan Sewa',
            'sewa'       => $sewaDetail,
            'pembayaran' => $this->pembayaranModel->getPembayaranBySewa($id),
        ];
        return view('admin/sewa/detail', $data);
    }

    public function setujui($id)
    {
        $sewa = $this->sewaModel->find($id);
        if (!$sewa) {
            return redirect()->to('/admin/sewa')->with('error', 'Data sewa tidak ditemukan.');
        }

        // === FIX BUG: Validasi SEMUA input SEBELUM atomic update. ===
        // Sebelumnya: atomic update ke 'aktif' dulu, baru cek deposit/kamar/dst.
        // Akibatnya: kalau validasi gagal, status stuck 'aktif' & harus rollback manual.
        // Sekarang: cek semua dulu. Kalau ada error, status TIDAK berubah & admin bisa retry.

        // 1. Cek deposit lunas
        $deposit = $this->pembayaranModel->where('id_sewa', $id)
                                         ->where('bulan_ke', 0)
                                         ->first();
        if (!$deposit || $deposit['status'] !== 'lunas') {
            return redirect()->to('/admin/sewa')
                             ->with('error', 'Pengajuan tidak bisa disetujui! User belum melunasi Deposit. '
                                      . 'Status deposit saat ini: ' . ($deposit['status'] ?? 'TIDAK ADA') . '. '
                                      . 'Minta user upload bukti & admin verifikasi deposit dulu di menu Pembayaran.');
        }

        // 2. Cek kamar status — FIX BUG KRITIS!
        // Sebelumnya: hanya allow 'tersedia'. Tapi kamar jadi 'dibooking' saat user ajukan sewa
        // (lihat User\Sewa::ajukan). Jadi admin gak bisa approve karena kamar selalu 'dibooking'.
        // Fix: allow 'tersedia' ATAU 'dibooking' (duanya valid pre-approval state).
        // Hanya tolak kalau kamar 'terisi' (ditempati user lain) atau 'perbaikan' (sedang diperbaiki).
        $kamar = $this->kamarModel->find($sewa['id_kamar']);
        if (!$kamar) {
            return redirect()->to('/admin/sewa')->with('error', 'Data kamar tidak ditemukan. Tolak pengajuan ini.');
        }
        $kamarStatusValid = in_array($kamar['status'], ['tersedia', 'dibooking'], true);
        if (!$kamarStatusValid) {
            return redirect()->to('/admin/sewa')
                             ->with('error', 'Kamar No. ' . $kamar['nomor_kamar'] . ' berstatus "' . ucfirst($kamar['status']) . '" — tidak bisa disetujui. '
                                      . 'Tolak pengajuan ini & minta user pilih kamar lain.');
        }

        // 3. Cek kamar tidak ditempati sewa aktif lain (race condition safety)
        $sewaAktifLain = $this->sewaModel->where('id_kamar', $sewa['id_kamar'])
                                         ->where('id_sewa !=', $id)
                                         ->whereIn('status', ['aktif', 'disetujui'])
                                         ->countAllResults();
        if ($sewaAktifLain > 0) {
            return redirect()->to('/admin/sewa')->with('error', 'Kamar sudah ditempati sewa aktif lain. Tolak pengajuan ini.');
        }

        // 4. Validasi durasi_bulan — kalau 0/negatif, tidak ada tagihan dibuat (user gratis tinggal selamanya)
        if ((int)($sewa['durasi_bulan'] ?? 0) < 1) {
            return redirect()->to('/admin/sewa')
                             ->with('error', 'durasi_bulan tidak valid (' . ($sewa['durasi_bulan'] ?? 'null') . '). Tolak pengajuan ini & minta user ajukan ulang.');
        }

        // === SEMUA VALIDASI LOLAS — sekarang atomic update status ===
        $db = \Config\Database::connect();
        $builder = $db->table('sewa');
        $builder->where('id_sewa', $id);
        $builder->where('status', 'menunggu');
        $builder->update(['status' => 'aktif']);

        $affected = $db->affectedRows();
        if ($affected < 1) {
            log_message('warning', '[Sewa::setujui] Atomic guard trigger. id_sewa=' . $id . ' affected=' . $affected);
            return redirect()->to('/admin/sewa')->with('error', 'Pengajuan ini sudah diproses admin lain (status bukan menunggu lagi). Tidak bisa disetujui 2x.');
        }

        $depositNominal = $deposit['jumlah_bayar'];
        $tanggal_mulai = !empty($sewa['tanggal_mulai']) ? $sewa['tanggal_mulai'] : date('Y-m-d');

        // FIX: kalau tanggal_mulai sudah lewat saat admin approve, pakai hari ini sebagai mulai
        if ($tanggal_mulai < date('Y-m-d')) {
            $tanggal_mulai = date('Y-m-d');
        }

        $tanggal_selesai = date('Y-m-d', strtotime("{$tanggal_mulai} +{$sewa['durasi_bulan']} months"));

        // === TRANSACTION: update data lengkap + buat tagihan bulanan ===
        $db->transStart();

        try {
            // FIX C8延伸: pakai builder() karena 'status' & 'deposit_dikembalikan' gak di $allowedFields.
            $this->sewaModel->builder()->where('id_sewa', $id)->update([
                'status'             => 'aktif',
                'tanggal_mulai'      => $tanggal_mulai,
                'tanggal_selesai'    => $tanggal_selesai,
                'deposit'            => $depositNominal,
                'status_kunci'       => 'siap_diambil',
                'lokasi_ambil_kunci' => 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)',
            ]);

            $this->kamarModel->builder()->where('id_kamar', $sewa['id_kamar'])->update(['status' => 'terisi']);

            // FIX: Jatuh tempo tagihan bulanan pakai 'batas_tanggal_bayar' dari pengaturan.
            // Mis. batas tanggal 5 -> tagihan bulan ke-1 jatuh tempo tanggal 5 bulan ke-1 sewa.
            $pengaturanModel = new \App\Models\PengaturanModel();
            $batasTanggal = (int) $pengaturanModel->get('batas_tanggal_bayar') ?: 5;
            $batasTanggal = min(max($batasTanggal, 1), 28); // batasi 1-28 supaya valid semua bulan

            for ($i = 1; $i <= $sewa['durasi_bulan']; $i++) {
                // Hitung bulan ke-i dari tanggal_mulai, lalu set tanggal ke batasTanggal
                $bulanKe = date('Y-m', strtotime($tanggal_mulai . " +{$i} months"));
                $jatuhTempo = $bulanKe . '-' . str_pad($batasTanggal, 2, '0', STR_PAD_LEFT);
                $this->pembayaranModel->save([
                    'id_sewa'             => $id,
                    'bulan_ke'            => $i,
                    'jumlah_bayar'        => $kamar['harga_sewa'] ?? 0,
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'status'              => 'belum_bayar',
                    'keterangan'          => 'Sewa bulan ke-' . $i,
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

        } catch (\Exception $e) {
            $db->transRollback();
            // Rollback status ke menunggu karena gagal
            $this->sewaModel->builder()->where('id_sewa', $id)->update(['status' => 'menunggu']);
            return redirect()->to('/admin/sewa')->with('error', 'Gagal menyetujui sewa: ' . $e->getMessage() . '. Status dikembalikan ke menunggu.');
        }

        $this->notifikasiModel->kirim(
            $sewa['id_user'],
            'Pengajuan Sewa Disetujui!',
            "Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. " . $kamar['nomor_kamar'] . " siap diambil di Office Rumah Kos.",
            'sewa'
        );

        $this->notifikasiModel->kirim(
            $sewa['id_user'],
            'Kunci Kamar Siap Diambil',
            "Kunci kamar No. " . $kamar['nomor_kamar'] . " siap diambil di Office Rumah Kos (Jam 08:00-17:00 WIB). Bawa KTP & bukti pembayaran deposit.",
            'info'
        );

        return redirect()->to('/admin/sewa')->with('success', 'Pengajuan sewa disetujui! Tagihan bulanan telah dibuat.');
    }

    public function kunciDiambil($id)
    {
        $sewa = $this->sewaModel->find($id);

        if (!$sewa) {
            return redirect()->back()->with('error', 'Data sewa tidak ditemukan.');
        }

        // === FIX BUG #17: Cegah tandai "sudah diambil" kalau kunci belum siap atau deposit belum lunas. ===
        // User bisa ambil kunci HANYA jika status_kunci='siap_diambil' (admin sudah siapkan)
        // DAN semua tagihan deposit (bulan_ke=0) sudah LUNAS.
        if ($sewa['status_kunci'] !== 'siap_diambil') {
            return redirect()->back()->with('error', 'Tidak bisa tandai "Sudah Diambil"! Status kunci saat ini: "' . str_replace('_', ' ', $sewa['status_kunci']) . '". Kunci harus berstatus "Siap Diambil" dulu.');
        }

        $depositBelumLunas = $this->pembayaranModel->where('id_sewa', $id)
                                                   ->where('bulan_ke', 0)
                                                   ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
                                                   ->countAllResults();
        if ($depositBelumLunas > 0) {
            return redirect()->back()->with('error', 'Tidak bisa tandai "Sudah Diambil"! User masih punya ' . $depositBelumLunas . ' tagihan deposit yang belum LUNAS. Minta user bayar & verifikasi dulu di menu Pembayaran.');
        }

        $userId = $sewa['id_user'];

        $this->sewaModel->builder()->where('id_sewa', $id)->update([
            'status_kunci'        => 'sudah_diambil',
            'tanggal_ambil_kunci' => date('Y-m-d H:i:s'),
        ]);

        $this->notifikasiModel
            ->where('id_user', $userId)
            ->where('tipe', 'info')
            ->where('judul', 'Kunci Kamar Siap Diambil')
            ->delete();

        $this->notifikasiModel->kirim(
            $userId,
            'Kunci Telah Diambil',
            "Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.",
            'info'
        );

        return redirect()->back()->with('success', 'Status kunci berhasil diupdate menjadi "Sudah Diambil".');
    }

    public function setKunci($id, $status)
    {
        $sewa = $this->sewaModel->find($id);
        if (!$sewa) {
            return redirect()->to('/admin/sewa')->with('error', 'Data sewa tidak ditemukan.');
        }

        $allowedStatus = ['belum_siap', 'siap_diambil', 'sudah_diambil', 'sudah_dikembalikan'];
        if (!in_array($status, $allowedStatus)) {
            return redirect()->to('/admin/sewa')->with('error', 'Status kunci tidak valid.');
        }

        // === FIX BUG #17: Cegah set "siap_diambil" atau "sudah_diambil" kalau deposit belum lunas. ===
        // Ini safety net supaya admin gak bisa "skip" syarat deposit lunas.
        // Berlaku untuk semua sewa (sewa awal maupun pindah kamar dengan selisih deposit).
        if (in_array($status, ['siap_diambil', 'sudah_diambil'], true)) {
            $depositBelumLunas = $this->pembayaranModel->where('id_sewa', $id)
                                                       ->where('bulan_ke', 0)
                                                       ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
                                                       ->countAllResults();
            if ($depositBelumLunas > 0) {
                return redirect()->to('/admin/sewa')
                                 ->with('error', 'TIDAK BISA set kunci ke "' . str_replace('_', ' ', $status) . '"! User masih punya ' . $depositBelumLunas . ' tagihan deposit yang belum LUNAS (kemungkinan selisih deposit pindah kamar). Verifikasi pembayaran deposit dulu di menu Pembayaran, baru set kunci.');
            }
        }

        $dataUpdate = ['status_kunci' => $status];
        if ($status === 'sudah_diambil') {
            $dataUpdate['tanggal_ambil_kunci'] = date('Y-m-d H:i:s');
        }

        $this->sewaModel->builder()->where('id_sewa', $id)->update($dataUpdate);

        return redirect()->to('/admin/sewa')->with('success', 'Status kunci berhasil diubah menjadi ' . str_replace('_', ' ', $status) . '.');
    }

    public function tolak($id)
    {
        $sewa = $this->sewaModel->find($id);
        if (!$sewa) {
            return redirect()->to('/admin/sewa')->with('error', 'Data sewa tidak ditemukan.');
        }

        // === ANTI DOBEL: Atomic update ===
        $db = \Config\Database::connect();
        $builder = $db->table('sewa');
        $builder->where('id_sewa', $id);
        $builder->where('status', 'menunggu');
        $builder->update(['status' => 'ditolak', 'keterangan' => 'DITOLAK: ' . ($this->request->getPost('alasan') ?: 'Pengajuan ditolak.')]);

        // FIX: gunakan affectedRows(), bukan return value update(). Pakai < 1 untuk robustness.
        if ($db->affectedRows() < 1) {
            return redirect()->to('/admin/sewa')->with('error', 'Pengajuan ini sudah diproses. Tidak bisa ditolak lagi.');
        }

        // FIX H5: hapus tagihan belum_bayar DAN menunggu_verifikasi.
        // Kalau cuma hapus belum_bayar, tagihan menunggu_verifikasi jadi yatim
        // (id_sewa ke sewa ditolak, blok user dari hapus).
        $this->pembayaranModel->where('id_sewa', $id)
                              ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
                              ->delete();

        // === FIX BUG KRITIS: Reset kamar kembali ke 'tersedia' setelah penolakan. ===
        // Saat user ajukan sewa, kamar berubah dari 'tersedia' → 'dibooking' (lihat User\Sewa::ajukan).
        // Kalau admin tolak sewa, kamar HARUS balik ke 'tersedia' supaya user lain bisa booking.
        // Sebelumnya: kamar tetap 'dibooking' → kamar "hilang" dari pasar selamanya!
        $kamar = $this->kamarModel->find($sewa['id_kamar']);
        if ($kamar && $kamar['status'] === 'dibooking') {
            $this->kamarModel->builder()->where('id_kamar', $sewa['id_kamar'])->update(['status' => 'tersedia']);
        }

        $alasan = $this->request->getPost('alasan') ?: 'Pengajuan sewa ditolak oleh admin.';

        $deposit = $this->pembayaranModel->where('id_sewa', $id)
                                         ->where('bulan_ke', 0)
                                         ->first();
        $depositLunas = $deposit && $deposit['status'] === 'lunas';

        if ($depositLunas) {
            $this->notifikasiModel->kirim(
                $sewa['id_user'],
                '❌ Pengajuan Sewa Ditolak',
                "Maaf, pengajuan sewa Anda ditolak. Alasan: " . $alasan . ". Karena Anda sudah membayar Deposit sebesar Rp " . number_format($deposit['jumlah_bayar'], 0, ',', '.') . ", admin akan menghubungi Anda untuk proses pengembalian dana. Terima kasih atas pengertiannya. 🙏",
                'sewa'
            );
            
            $userModel = new \App\Models\UserModel();
            $admins = $userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $this->notifikasiModel->kirim(
                    $admin['id_user'],
                    '⚠️ Perlu Refund Deposit',
                    "Pengajuan sewa #" . $id . " ditolak, tapi user sudah bayar deposit Rp " . number_format($deposit['jumlah_bayar'], 0, ',', '.') . ". Harap lakukan refund manual ke user.",
                    'info'
                );
            }
        } else {
            $this->notifikasiModel->kirim(
                $sewa['id_user'],
                '❌ Pengajuan Sewa Ditolak',
                "Maaf, pengajuan sewa Anda ditolak. Alasan: " . $alasan . ". Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏",
                'sewa'
            );
        }

        return redirect()->to('/admin/sewa')->with('success', 'Pengajuan sewa telah ditolak.' . ($depositLunas ? ' ⚠️ User sudah bayar deposit - lakukan refund manual!' : ''));
    }

    /**
     * BATALKAN PENOLAKAN (Undo Reject)
     *
     * Untuk kasus admin salah tekan TOLAK padahal seharusnya SETUJU.
     * Mengembalikan status sewa dari 'ditolak' ke 'menunggu' supaya admin
     * bisa klik SETUJUI kembali.
     *
     * Catatan penting:
     * - Kamar di-reset ke 'tersedia' (karena tolak() sudah fix untuk reset).
     *   Kalau undo berhasil & kamar masih 'tersedia', kamar di-set balik ke 'dibooking'.
     * - Tagihan bulanan belum dibuat saat ditolak (baru dibuat saat setujui), jadi aman.
     * - Deposit (bulan_ke=0) yang sudah lunas tetap ada (tolak hanya hapus 'belum_bayar').
     *
     * @param int $id ID sewa
     */
    public function batalkanTolak($id)
    {
        $sewa = $this->sewaModel->find($id);
        if (!$sewa) {
            return redirect()->to('/admin/sewa')->with('error', 'Data sewa tidak ditemukan.');
        }

        // Hanya bisa batalkan kalau status = ditolak
        if ($sewa['status'] !== 'ditolak') {
            return redirect()->to('/admin/sewa/detail/'.$id)
                             ->with('error', 'Hanya pengajuan berstatus DITOLAK yang bisa dibatalkan penolakannya. Status sekarang: ' . ucfirst($sewa['status']) . '.');
        }

        // === ANTI RACE CONDITION: atomic update ===
        // SET status='menunggu' WHERE id=X AND status='ditolak'
        $db = \Config\Database::connect();
        $builder = $db->table('sewa');
        $builder->where('id_sewa', $id);
        $builder->where('status', 'ditolak');
        $builder->update([
            'status'    => 'menunggu',
            'keterangan' => null,
        ]);

        // FIX: gunakan affectedRows(). Pakai < 1 untuk robustness.
        if ($db->affectedRows() < 1) {
            return redirect()->to('/admin/sewa')
                             ->with('error', 'Gagal membatalkan penolakan. Mungkin sudah diproses admin lain.');
        }

        // === CEK KAMAR MASIH TERSEDIA? ===
        // (Bisa jadi setelah ditolak, kamar ditempati user lain via pengajuan sewa baru)
        $kamar = $this->kamarModel->find($sewa['id_kamar']);
        $kamarTersedia = $kamar && $kamar['status'] === 'tersedia';

        // Cek juga tidak ada sewa aktif lain di kamar yang sama
        $sewaAktifLain = 0;
        if ($kamarTersedia) {
            $sewaAktifLain = $this->sewaModel->where('id_kamar', $sewa['id_kamar'])
                                             ->where('id_sewa !=', $id)
                                             ->whereIn('status', ['aktif', 'disetujui'])
                                             ->countAllResults();
        }

        // === FIX BUG: Set kamar balik ke 'dibooking' kalau undo berhasil & kamar masih tersedia. ===
        // Setelah tolak() reset kamar ke 'tersedia' (fix sebelumnya), kalau admin undo tolak,
        // sewa balik ke 'menunggu' → kamar harus balik ke 'dibooking' supaya konsisten
        // & user lain tidak bisa booking kamar yang sama.
        if ($kamarTersedia && $sewaAktifLain === 0) {
            $this->kamarModel->builder()->where('id_kamar', $sewa['id_kamar'])->update(['status' => 'dibooking']);
        }

        // === FIX BUG: Buat ulang tagihan deposit kalau tidak ada (dihapus saat tolak). ===
        // Saat admin tolak, tagihan 'belum_bayar' & 'menunggu_verifikasi' dihapus.
        // Saat undo tolak, tagihan deposit harus dibuat ulang supaya user bisa bayar
        // & admin bisa setujui. Tanpa ini, user lihat pengajuan 'menunggu' tapi tidak
        // ada tagihan deposit → admin tidak bisa setujui (setujui() cek deposit lunas).
        $depositExists = $this->pembayaranModel->where('id_sewa', $id)
                                               ->where('bulan_ke', 0)
                                               ->countAllResults();
        if ($depositExists == 0) {
            // Buat ulang tagihan deposit
            $pengaturanModel = new \App\Models\PengaturanModel();
            $kaliDeposit = (int) $pengaturanModel->get('default_deposit_kali') ?: 2;
            $kamarForDeposit = $this->kamarModel->find($sewa['id_kamar']);
            $nominalDeposit = ($kamarForDeposit['harga_sewa'] ?? 0) * $kaliDeposit;
            $batasTanggal = (int) $pengaturanModel->get('batas_tanggal_bayar') ?: 5;
            $jatuhTempo = date('Y-m') . '-' . str_pad(min($batasTanggal, 28), 2, '0', STR_PAD_LEFT);

            $this->pembayaranModel->save([
                'id_sewa'             => $id,
                'bulan_ke'            => 0,
                'jumlah_bayar'        => $nominalDeposit,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'status'              => 'belum_bayar',
                'keterangan'          => 'Deposit Awal Sewa (dibuat ulang saat undo tolak)',
            ]);
        }

        // === NOTIFIKASI KE USER ===
        $this->notifikasiModel->kirim(
            $sewa['id_user'],
            '✅ Penolakan Sewa Dibatalkan',
            "Permohonan maaf, penolakan pengajuan sewa Anda telah DIBATALKAN oleh admin (kemungkinan salah tekan). Status pengajuan Anda kembali ke MENUNGGU. Admin akan meninjau kembali dan menyetujui jika semua syarat terpenuhi. Mohon tunggu konfirmasi selanjutnya. Terima kasih. 🙏",
            'sewa'
        );

        // Pesan sukses / warning
        if (!$kamarTersedia || $sewaAktifLain > 0) {
            return redirect()->to('/admin/sewa/detail/'.$id)
                             ->with('warning', '⚠️ Penolakan dibatalkan, status kembali ke MENUNGGU. TAPI kamar No. ' . ($kamar['nomor_kamar'] ?? '?') . ' sudah tidak tersedia (mungkin sudah ditempati user lain). Anda bisa tolak lagi, atau minta user pilih kamar lain.');
        }

        return redirect()->to('/admin/sewa/detail/'.$id)
                         ->with('success', '✅ Penolakan dibatalkan! Status pengajuan kembali ke MENUNGGU. Sekarang Anda bisa klik SETUJUI pada halaman ini.');
    }

    /**
     * FIX BUG #3: Upload bukti refund deposit untuk sewa yang DITOLAK
     * (setelah user sudah bayar deposit lunas).
     *
     * Sebelumnya: tidak ada tracking refund untuk sewa ditolak. Admin harus
     * transfer manual & tidak ada kolom bukti_refund di tabel sewa. Sekarang:
     * admin bisa upload bukti transfer & user bisa download di halaman detail sewa.
     *
     * @param int $id ID sewa
     */
    public function refundDeposit($id)
    {
        $sewa = $this->sewaModel->find($id);
        if (!$sewa) {
            return redirect()->to('/admin/sewa')->with('error', 'Data sewa tidak ditemukan.');
        }

        // Hanya sewa berstatus 'ditolak' yang bisa di-refund
        if ($sewa['status'] !== 'ditolak') {
            return redirect()->to('/admin/sewa/detail/'.$id)
                             ->with('error', 'Refund hanya bisa untuk sewa berstatus DITOLAK. Status sekarang: ' . ucfirst($sewa['status']) . '.');
        }

        // Cek apakah deposit sudah lunas (kalau belum, tidak ada yang perlu di-refund)
        $deposit = $this->pembayaranModel->where('id_sewa', $id)
                                         ->where('bulan_ke', 0)
                                         ->first();
        if (!$deposit || $deposit['status'] !== 'lunas') {
            return redirect()->to('/admin/sewa/detail/'.$id)
                             ->with('error', 'Tidak bisa refund: deposit user belum LUNAS. Status deposit: ' . ($deposit['status'] ?? 'TIDAK ADA') . '.');
        }

        // Validasi: cek apakah refund sudah pernah diupload
        if ($sewa['refund_status'] === 'selesai') {
            return redirect()->to('/admin/sewa/detail/'.$id)
                             ->with('error', 'Refund untuk sewa ini sudah pernah diupload. Tidak bisa upload ulang. Hubungi developer jika perlu koreksi.');
        }

        // Validasi file upload
        $file = $this->request->getFile('bukti_refund');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Bukti transfer refund WAJIB diupload!')->withInput();
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        $allowedExt  = ['jpg', 'jpeg', 'png', 'pdf'];
        $ext = strtolower($file->getExtension());
        if (!in_array($ext, $allowedExt, true) || !in_array($file->getMimeType(), $allowedMime, true)) {
            return redirect()->back()->with('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.')->withInput();
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB.')->withInput();
        }

        // Upload file dengan nama aman (extension dari whitelist)
        try {
            $namaBukti = $file->getRandomName();
            $namaBukti = pathinfo($namaBukti, PATHINFO_FILENAME) . '.' . $ext;
            $file->move(ROOTPATH . 'public/uploads/', $namaBukti);
        } catch (\Throwable $e) {
            log_message('error', '[Sewa::refundDeposit] Gagal upload bukti refund: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal upload bukti refund: ' . $e->getMessage())->withInput();
        }

        // Ambil metode refund dari input admin (cash / transfer bank / e-wallet)
        $metodeRefund = $this->request->getPost('metode_refund') ?: 'Transfer Bank';
        $totalRefund  = (float) $this->request->getPost('total_refund');
        if ($totalRefund <= 0) {
            $totalRefund = (float) $deposit['jumlah_bayar'];
        }

        // Update sewa dengan info refund
        // FIX C8: pakai builder() karena field admin-only gak di $allowedFields SewaModel.
        $this->sewaModel->builder()->where('id_sewa', $id)->update([
            'bukti_refund'   => $namaBukti,
            'tanggal_refund' => date('Y-m-d'),
            'total_refund'   => $totalRefund,
            'refund_status'  => 'selesai',
            'refund_metode'  => $metodeRefund,
        ]);

        // Notif ke user
        $userModel = new \App\Models\UserModel();
        $userPenerima = get_user_with_rekening($sewa['id_user']);

        $pesan = "\xe2\x9c\x85 Refund Deposit Pengajuan Sewa\n\n";
        $pesan .= "Pengajuan sewa Anda ditolak, dan deposit Anda sebesar Rp " . number_format($totalRefund, 0, ',', '.') . " sudah dikembalikan.\n\n";
        $pesan .= "Metode refund: " . $metodeRefund . "\n";
        if (!empty($userPenerima) && !empty($userPenerima['nomor_rekening'])) {
            $pesan .= "Transfer dikirim ke: Bank " . ($userPenerima['nama_bank'] ?? '-') . " " . $userPenerima['nomor_rekening'] . " a.n. " . ($userPenerima['nama_pemilik_rek'] ?? '-') . "\n";
        } elseif (!empty($userPenerima) && !empty($userPenerima['ewallet_number'])) {
            $pesan .= "Transfer dikirim ke e-wallet: " . ($userPenerima['ewallet_type'] ?? '-') . " " . $userPenerima['ewallet_number'] . "\n";
        }
        $pesan .= "\nBukti transfer sudah diupload admin. Cek halaman detail sewa untuk download bukti.\n\n";
        $pesan .= "Terima kasih atas pengertiannya. \xf0\x9f\x99\x8f";

        $this->notifikasiModel->kirim(
            $sewa['id_user'],
            "\xe2\x9c\x85 Refund Deposit Pengajuan Sewa",
            $pesan,
            'sewa'
        );

        return redirect()->to('/admin/sewa/detail/'.$id)
                         ->with('success', 'Bukti refund deposit berhasil diupload! Notifikasi dikirim ke user dengan rincian rekening tujuan.');
    }
}