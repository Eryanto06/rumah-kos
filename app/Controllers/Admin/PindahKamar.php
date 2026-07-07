<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PindahKamarModel;
use App\Models\SewaModel;
use App\Models\KamarModel;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;
use App\Models\PengaturanModel;

class PindahKamar extends BaseController
{
    protected $pindahModel;
    protected $sewaModel;
    protected $kamarModel;
    protected $pembayaranModel;
    protected $notifikasiModel;

    public function __construct()
    {
        $this->pindahModel     = new PindahKamarModel();
        $this->sewaModel       = new SewaModel();
        $this->kamarModel      = new KamarModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function index()
    {
        // FIX: Ambil filter status & search nama dari query string
        $statusFilter = $this->request->getGet('status');
        $searchNama   = trim($this->request->getGet('search') ?? '');

        $allowedStatus = ['menunggu', 'disetujui', 'ditolak'];
        if (!in_array($statusFilter, $allowedStatus)) {
            $statusFilter = '';
        }

        $pengajuan = $this->pindahModel->getAllWithDetail();

        // Filter di PHP
        if (!empty($statusFilter)) {
            $pengajuan = array_filter($pengajuan, fn($p) => $p['status'] === $statusFilter);
        }
        if (!empty($searchNama)) {
            $searchLower = strtolower($searchNama);
            $pengajuan = array_filter($pengajuan, function($p) use ($searchLower) {
                return strpos(strtolower($p['nama_user'] ?? ''), $searchLower) !== false
                    || strpos(strtolower($p['nomor_kamar_lama'] ?? ''), $searchLower) !== false
                    || strpos(strtolower($p['nomor_kamar_baru'] ?? ''), $searchLower) !== false;
            });
        }

        // Statistik dari SEMUA data
        $semua = $this->pindahModel->getAllWithDetail();
        $total = count($semua);
        $menunggu = count(array_filter($semua, fn($p) => $p['status'] == 'menunggu'));
        $disetujui = count(array_filter($semua, fn($p) => $p['status'] == 'disetujui'));
        $ditolak = count(array_filter($semua, fn($p) => $p['status'] == 'ditolak'));

        $data = [
            'title'         => 'Pengajuan Pindah Kamar',
            'pengajuan'     => $pengajuan,
            'filter_status' => $statusFilter,
            'search_nama'   => $searchNama,
            'total'         => $total,
            'menunggu'      => $menunggu,
            'disetujui'     => $disetujui,
            'ditolak'       => $ditolak,
        ];
        return view('admin/pindah-kamar/index', $data);
    }

    public function formInspeksi($id)
    {
        $pengajuan = $this->pindahModel->getAllWithDetail();
        $dataPengajuan = null;
        foreach ($pengajuan as $p) {
            if ($p['id_pindah'] == $id) {
                $dataPengajuan = $p;
                break;
            }
        }

        if (!$dataPengajuan) {
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Data tidak ditemukan.');
        }

        $sewaLama = $this->sewaModel->find($dataPengajuan['id_sewa_lama']);

        $pengaturanModel = new PengaturanModel();
        $kaliDeposit = (int) $pengaturanModel->get('default_deposit_kali') ?: 2;
        $depositBaru = $dataPengajuan['harga_baru'] * $kaliDeposit;

        // FIX: fallback depositLama kalau sewa.deposit NULL/0.
        // Beberapa sewa lama mungkin belum set deposit (dibuat sebelum fitur
        // deposit, atau ada bug di flow approve). Hitung ulang dari
        // harga kamar lama * kaliDeposit supaya inspeksi & validasi jalan.
        $kamarLama = $this->kamarModel->find($dataPengajuan['id_kamar_lama']);
        $depositLama = $sewaLama['deposit'] ?? 0;
        if ($depositLama <= 0 && $kamarLama) {
            $depositLama = $kamarLama['harga_sewa'] * $kaliDeposit;
        }
        $selisihDeposit = $depositBaru - $depositLama;

        $isReadOnly = $dataPengajuan['status'] !== 'menunggu';
        $title = $isReadOnly
            ? 'Detail Pengajuan Pindah Kamar'
            : 'Inspeksi & Setujui Pindah Kamar';

        return view('admin/pindah-kamar/inspeksi', [
            'title'          => $title,
            'p'              => $dataPengajuan,
            'sewaLama'       => $sewaLama,
            'depositBaru'    => $depositBaru,
            'depositLama'    => $depositLama,
            'selisihDeposit' => $selisihDeposit,
            'isReadOnly'     => $isReadOnly,
        ]);
    }

    public function setujui($id)
    {
        $pengajuan = $this->pindahModel->find($id);
        if (!$pengajuan) {
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Pengajuan tidak ditemukan.');
        }

        // === FIX BUG: Validasi SEMUA input SEBELUM atomic update & transaksi. ===
        // Sebelumnya: atomic update ke 'disetujui' dulu, baru cek kamar/sewa/upload.
        // Akibatnya: kalau validasi gagal, status stuck 'disetujui' & banyak side effect
        // (sewa lama selesai, kamar lama tersedia, dst) tidak di-rollback.
        //
        // Sekarang: cek semua dulu. Kalau ada error, redirect->back() dengan input lama,
        // status TIDAK berubah, admin bisa perbaiki & retry.

        $kamarBaru = $this->kamarModel->find($pengajuan['id_kamar_baru']);
        if (!$kamarBaru || $kamarBaru['status'] !== 'tersedia') {
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Kamar baru sudah tidak tersedia.');
        }

        $sewaLama = $this->sewaModel->find($pengajuan['id_sewa_lama']);
        if (!$sewaLama) {
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Data sewa lama tidak ditemukan.');
        }

        // FIX: User HARUS lunasi semua tagihan dulu sebelum admin bisa setujui pindah.
        // Jangan cuma cek deposit — user yang masih punya tunggakan sewa bulanan
        // tidak boleh pindah kamar (bakal bikin tagihan yatim di sewa lama).
        $tagihanBelumLunas = $this->pembayaranModel
            ->where('id_sewa', $sewaLama['id_sewa'])
            ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
            ->countAllResults();
        if ($tagihanBelumLunas > 0) {
            return redirect()->to('/admin/pindah-kamar')
                             ->with('error', 'User masih punya ' . $tagihanBelumLunas . ' tagihan belum lunas di kamar lama. User WAJIB melunasi semua tagihan dulu sebelum pindah kamar bisa disetujui.');
        }

        // Hitung sisa bulan (null safety)
        $tanggalSelesai = $sewaLama['tanggal_selesai'] ?? null;
        $today = date('Y-m-d');
        $sisaBulan = 0;
        if (!empty($tanggalSelesai) && $tanggalSelesai > $today) {
            $datetime1 = new \DateTime($today);
            $datetime2 = new \DateTime($tanggalSelesai);
            $interval = $datetime1->diff($datetime2);
            $sisaBulan = $interval->y * 12 + $interval->m;
            if ($interval->d > 0) $sisaBulan += 1;
        }
        $sisaBulan = max($sisaBulan, 1);

        // Hitung deposit
        $pengaturanModel = new PengaturanModel();
        $kaliDeposit = (int) $pengaturanModel->get('default_deposit_kali') ?: 2;
        $depositBaruNominal = $kamarBaru['harga_sewa'] * $kaliDeposit;

        // FIX: fallback depositLama kalau sewa.deposit NULL/0.
        $kamarLama = $this->kamarModel->find($pengajuan['id_kamar_lama']);
        $depositLama = $sewaLama['deposit'] ?? 0;
        if ($depositLama <= 0 && $kamarLama) {
            $depositLama = $kamarLama['harga_sewa'] * $kaliDeposit;
        }

        $potongan = (int) $this->request->getPost('potongan_kerusakan');
        $catatanInspeksi = $this->request->getPost('catatan_inspeksi');

        // FIX: pesan error lebih jelas kalau potongan melebihi deposit.
        if ($potongan < 0) {
            return redirect()->back()->with('error', 'Potongan tidak boleh negatif.')->withInput();
        }
        if ($potongan > $depositLama) {
            return redirect()->back()->with('error', 'Potongan Rp ' . number_format($potongan,0,',','.') . ' melebihi deposit lama Rp ' . number_format($depositLama,0,',','.') . '.')->withInput();
        }

        $depositDipindah = $depositLama - $potongan;
        $selisihDeposit = $depositBaruNominal - $depositDipindah;

        // Hitung dulu apakah ada refund ke user (untuk validasi file upload).
        $totalRefundKeUser = ($selisihDeposit < 0) ? abs($selisihDeposit) : 0;
        // FIX BUG #1 (review): JANGAN tambah abs($selisihDeposit) lagi di bawah,
        // karena sudah dihitung di sini. Sebelumnya dobel → admin transfer 2x lipat.

        // === VALIDASI FILE UPLOAD SEBELUM transaksi ===
        // Kalau totalRefundKeUser > 0, bukti_refund WAJIB diupload.
        $file = $this->request->getFile('bukti_refund');
        $namaBuktiRefund = null;
        $extFile = null;

        if ($totalRefundKeUser > 0) {
            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'Bukti transfer refund WAJIB diupload karena ada uang kembalian ke user (Rp ' . number_format($totalRefundKeUser,0,',','.') . ').')->withInput();
            }

            $allowedMime = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $allowedExt  = ['jpg', 'jpeg', 'png', 'pdf'];
            $extFile = strtolower($file->getExtension());
            if (!in_array($extFile, $allowedExt, true) || !in_array($file->getMimeType(), $allowedMime, true)) {
                return redirect()->back()->with('error', 'Format bukti refund tidak didukung. Gunakan JPG, PNG, atau PDF.')->withInput();
            }
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran bukti refund maksimal 2MB.')->withInput();
            }
        } elseif ($file && $file->isValid() && !$file->hasMoved()) {
            // Optional upload (kalau gak ada refund, tapi admin tetap upload bukti)
            $allowedMime = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $allowedExt  = ['jpg', 'jpeg', 'png', 'pdf'];
            $extFile = strtolower($file->getExtension());
            if (!in_array($extFile, $allowedExt, true) || !in_array($file->getMimeType(), $allowedMime, true)) {
                return redirect()->back()->with('error', 'Format bukti refund tidak didukung. Gunakan JPG, PNG, atau PDF.')->withInput();
            }
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran bukti refund maksimal 2MB.')->withInput();
            }
        }

        // === SEMUA VALIDASI LOLAS — sekarang atomic update status ===
        $db = \Config\Database::connect();
        $builder = $db->table('pengajuan_pindah');
        $builder->where('id_pindah', $id);
        $builder->where('status', 'menunggu');
        $builder->update(['status' => 'disetujui']);

        $affected = $db->affectedRows();
        if ($affected < 1) {
            log_message('warning', '[PindahKamar::setujui] Atomic guard trigger. id_pindah=' . $id . ' affected=' . $affected);
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Pengajuan ini sudah diproses admin lain, atau status bukan menunggu. Tidak bisa disetujui 2x.');
        }

        // === UPLOAD FILE (kalau ada) SEBELUM transaksi DB ===
        // Supaya kalau move() gagal, status bisa di-rollback tanpa side effect DB.
        // FIX BUG: $file->move() mengembalikan string (nama file baru) saat sukses,
        // dan throw HTTPException saat gagal — BUKAN return false.
        // Jadi pakai try-catch untuk deteksi kegagalan.
        if ($file && $file->isValid() && !$file->hasMoved() && $extFile) {
            try {
                $namaBuktiRefund = $file->getRandomName();
                $namaBuktiRefund = pathinfo($namaBuktiRefund, PATHINFO_FILENAME) . '.' . $extFile;
                $file->move(ROOTPATH . 'public/uploads/', $namaBuktiRefund);
            } catch (\Throwable $e) {
                // Rollback atomic update
                $this->pindahModel->builder()->where('id_pindah', $id)->update(['status' => 'menunggu']);
                log_message('error', '[PindahKamar::setujui] Gagal upload bukti refund: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal upload bukti refund: ' . $e->getMessage())->withInput();
            }
        }

        // === LOGIKA TAGIHAN: TAGIHAN DIPINDAH KE SEWA BARU ===
        // (User sudah bayar bulan berjalan & bulan depan di kamar lama -> ikut dipindah ke sewa baru.
        //  Tidak ada lagi penggandaan tagihan 'Sewa Bulan Berjalan' - user hanya bayar selisih deposit.)

        $tanggalMulaiBaru = $today;
        $tanggalSelesaiBaru = date('Y-m-d', strtotime("+{$sisaBulan} months"));

        // === FIX BUG #17: Tentukan status_kunci sewa baru berdasarkan ada/tidaknya selisih deposit. ===
        // - Kalau selisihDeposit > 0 (kamar baru lebih mahal): user WAJIB bayar selisih deposit dulu
        //   sebelum bisa ambil kunci. Set status_kunci='belum_siap' supaya admin gak bisa klik
        //   "Set Siap Kunci" sebelum deposit lunas (lihat validasi di Admin\Sewa::setKunci).
        // - Kalau selisihDeposit <= 0 (refund atau sama): tidak ada tagihan tambahan,
        //   kunci langsung 'siap_diambil' (user bisa langsung ambil kunci).
        $statusKunciBaru = ($selisihDeposit > 0) ? 'belum_siap' : 'siap_diambil';

        // === TRANSACTION: Semua operasi multi-step dalam 1 transaksi ===
        $db->transStart();

        try {
            // 1. Update sewa lama → selesai
            $this->sewaModel->builder()->where('id_sewa', $sewaLama['id_sewa'])->update([
                'status'               => 'selesai',
                'tanggal_selesai'      => $today,
                'deposit_dikembalikan' => $depositDipindah,
                'status_kunci'         => 'sudah_dikembalikan',
            ]);

            // 2. Update kamar lama → tersedia
            $this->kamarModel->builder()->where('id_kamar', $pengajuan['id_kamar_lama'])->update(['status' => 'tersedia']);

            // 3. Buat sewa baru
            $this->sewaModel->save([
                'id_user'            => $pengajuan['id_user'],
                'id_kamar'           => $pengajuan['id_kamar_baru'],
                'tanggal_pengajuan'  => $today,
                'tanggal_mulai'      => $tanggalMulaiBaru,
                'tanggal_selesai'    => $tanggalSelesaiBaru,
                'durasi_bulan'       => $sisaBulan,
                'status'             => 'aktif',
                'deposit'            => $depositBaruNominal,
                'status_kunci'       => $statusKunciBaru,
                'lokasi_ambil_kunci' => 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)',
                'keterangan'         => 'Pindah kamar dari sewa #' . $sewaLama['id_sewa'],
            ]);
            $idSewaBaru = $this->sewaModel->getInsertID();

            // 4. Update kamar baru → terisi
            $this->kamarModel->builder()->where('id_kamar', $pengajuan['id_kamar_baru'])->update(['status' => 'terisi']);

            // 5. Pindahkan SEMUA tagihan bulanan (bukan deposit) dari sewa lama ke sewa baru.
            //    FIX LOGIKA: Hitung selisih harga untuk tagihan LUNAS juga.
            //    - Tagihan LUNAS (sudah dibayar di kamar lama) -> pindah + update harga ke kamar baru.
            //      Kalau kamar baru lebih mahal: buat tagihan baru 'Selisih Sewa' (belum_bayar).
            //      Kalau kamar baru lebih murah: tambah ke refund ke user.
            //    - Tagihan BELUM BAYAR (bulan ke-3 dst) -> pindah & update harga ke kamar baru.
            $tagihanDipindah = $this->pembayaranModel
                ->where('id_sewa', $sewaLama['id_sewa'])
                ->where('bulan_ke >', 0)
                ->findAll();

            $selisihHargaPerBulan = $kamarBaru['harga_sewa'] - $kamarLama['harga_sewa'];
            $totalSelisihHargaLunas = 0; // akumulasi selisih untuk tagihan LUNAS
            $jumlahBulanLunasDipindah = 0;

            foreach ($tagihanDipindah as $tagihan) {
                $updateData = ['id_sewa' => $idSewaBaru];

                if ($tagihan['status'] === 'belum_bayar') {
                    // Tagihan belum dibayar: update ke harga kamar baru
                    $updateData['jumlah_bayar'] = $kamarBaru['harga_sewa'];
                } elseif ($tagihan['status'] === 'lunas') {
                    // Tagihan sudah dibayar di kamar lama: update harga ke kamar baru
                    // & akumulasi selisih ( positif = user perlu bayar tambahan,
                    //                          negatif = user dapat refund )
                    $updateData['jumlah_bayar'] = $kamarBaru['harga_sewa'];
                    $totalSelisihHargaLunas += $selisihHargaPerBulan;
                    $jumlahBulanLunasDipindah++;
                }

                $this->pembayaranModel->builder()->where('id_pembayaran', $tagihan['id_pembayaran'])->update($updateData);
            }

            // 6. Proses selisih harga untuk tagihan LUNAS
            if ($totalSelisihHargaLunas > 0) {
                // Kamar baru lebih mahal: user perlu bayar selisih untuk bulan yang sudah dibayar
                // FIX: pakai bulan_ke = -1 (BUKAN 0=deposit) supaya tidak tertukar dengan deposit
                $this->pembayaranModel->save([
                    'id_sewa'      => $idSewaBaru,
                    'bulan_ke'     => -1, // -1 = Selisih Sewa (bukan deposit, bukan bulan reguler)
                    'jumlah_bayar' => $totalSelisihHargaLunas,
                    'status'       => 'belum_bayar',
                    'keterangan'   => 'Selisih Sewa ' . $jumlahBulanLunasDipindah . ' bulan (kamar baru lebih mahal)',
                ]);
            } elseif ($totalSelisihHargaLunas < 0) {
                // Kamar baru lebih murah: user dapat refund selisih untuk bulan yang sudah dibayar
                $totalRefundKeUser += abs($totalSelisihHargaLunas);
            }

            // 7. Cek selisih deposit untuk refund / tagihan tambahan
            // FIX BUG #1: JANGAN += lagi, $totalRefundKeUser sudah dihitung di atas (line 189)
            if ($selisihDeposit > 0) {
                $this->pembayaranModel->save([
                    'id_sewa'      => $idSewaBaru,
                    'bulan_ke'     => 0,
                    'jumlah_bayar' => $selisihDeposit,
                    'status'       => 'belum_bayar',
                    'keterangan'   => 'Selisih Deposit Kamar Baru',
                ]);
            }
            // elseif ($selisihDeposit < 0) sudah di-handle di inisialisasi line 189

            // 8. Update pengajuan pindah → disetujui
            // FIX C8: pakai builder() karena field admin-only gak di $allowedFields.
            $this->pindahModel->builder()->where('id_pindah', $id)->update([
                'status'          => 'disetujui',
                'tanggal_proses'  => $today,
                'keterangan_admin'=> $catatanInspeksi . ' | Potongan: Rp ' . number_format($potongan,0,',','.') . ' | Refund ke User: Rp ' . number_format($totalRefundKeUser,0,',','.'),
                'tanggal_refund'  => $today,
                'total_refund'    => $totalRefundKeUser,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

        } catch (\Exception $e) {
            $db->transRollback();
            // Rollback status pengajuan ke menunggu karena gagal
            $this->pindahModel->builder()->where('id_pindah', $id)->update(['status' => 'menunggu']);
            // Hapus file bukti refund yang baru diupload (kalau ada) supaya gak jadi orphan
            if ($namaBuktiRefund) {
                $path = ROOTPATH . 'public/uploads/' . $namaBuktiRefund;
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
            log_message('error', '[PindahKamar::setujui] Gagal transaksi. id_pindah=' . $id . ' Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->to('/admin/pindah-kamar')
                             ->with('error', 'Gagal memproses pindah kamar: ' . $e->getMessage() . '. Status dikembalikan ke menunggu. Detail error sudah di-log, hubungi developer.');
        }

        // === UPDATE BUKTI REFUND KE DB (kalau ada) ===
        // File sudah di-move di langkah sebelumnya, sekarang tinggal update DB.
        if ($namaBuktiRefund) {
            $this->pindahModel->builder()->where('id_pindah', $id)->update(['bukti_refund' => $namaBuktiRefund]);
        }

        // === FIX BUG: ambil data user untuk sebutkan rekening tujuan di notifikasi refund ===
        $userModelTmp = new \App\Models\UserModel();
        $userPenerima = get_user_with_rekening($pengajuan['id_user']);

        // === NOTIFIKASI KE USER ===
        $pesanNotif = 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. ' . $kamarBaru['nomor_kamar'] . '.';
        $pesanNotif .= "\n\nHasil inspeksi kamar lama: " . $catatanInspeksi;
        $pesanNotif .= "\n\nRincian Keuangan:";
        $pesanNotif .= "\n• Deposit lama: Rp " . number_format($depositLama,0,',','.');
        if ($potongan > 0) {
            $pesanNotif .= "\n• Potongan kerusakan: -Rp " . number_format($potongan,0,',','.');
        }
        $pesanNotif .= "\n• Deposit dipindah: Rp " . number_format($depositDipindah,0,',','.');

                // === HITUNG TAGIHAN TAMBAHAN / REFUND ===
        // $totalSelisihHargaLunas: selisih harga untuk bulan-bulan yang sudah dibayar (dihitung di transaksi)
        // $selisihDeposit: selisih deposit (deposit baru - deposit lama)
        $totalTagihanTambahan = 0;
        if ($totalSelisihHargaLunas > 0) {
            $totalTagihanTambahan += $totalSelisihHargaLunas;
        }
        if ($selisihDeposit > 0) {
            $totalTagihanTambahan += $selisihDeposit;
        }

        if ($totalTagihanTambahan > 0) {
            // User perlu bayar tambahan
            $pesanNotif .= "\n\n\xe2\x9a\xa0\xef\xb8\x8f WAJIB BAYAR TAMBAHAN: Rp " . number_format($totalTagihanTambahan,0,',','.');
            if ($totalSelisihHargaLunas > 0) {
                $pesanNotif .= "\n  \xe2\x80\xa2 Selisih sewa " . $jumlahBulanLunasDipindah . " bulan (kamar baru lebih mahal): Rp " . number_format($totalSelisihHargaLunas,0,',','.');
            }
            if ($selisihDeposit > 0) {
                $pesanNotif .= "\n  \xe2\x80\xa2 Selisih deposit kamar baru: Rp " . number_format($selisihDeposit,0,',','.');
            }
            $pesanNotif .= "\n\n\xf0\x9f\x94\x92 KUNCI KAMAR BARU BELUM BISA DIAMBIL!";
            $pesanNotif .= "\nAnda WAJIB melunasi tagihan tambahan di menu Pembayaran terlebih dahulu.";
            $pesanNotif .= "\nSetelah admin verifikasi, kunci akan disiapkan & Anda bisa ambil di Office.";
        } elseif ($totalRefundKeUser > 0) {
            // User dapat refund
            $pesanNotif .= "\n\n\xe2\x9c\x85 ANDA DAPAT REFUND: Rp " . number_format($totalRefundKeUser,0,',','.');
            if ($totalSelisihHargaLunas < 0) {
                $pesanNotif .= "\n  \xe2\x80\xa2 Selisih sewa " . $jumlahBulanLunasDipindah . " bulan (kamar baru lebih murah): Rp " . number_format(abs($totalSelisihHargaLunas),0,',','.');
            }
            if ($selisihDeposit < 0) {
                $pesanNotif .= "\n  \xe2\x80\xa2 Selisih deposit (kamar baru lebih murah): Rp " . number_format(abs($selisihDeposit),0,',','.');
            }
            if ($namaBuktiRefund) {
                $pesanNotif .= "\n\n\xe2\x9c\x85 Bukti transfer refund sudah diupload admin. Cek halaman Pindah Kamar Anda untuk download bukti.";
            } else {
                $pesanNotif .= "\n\nMohon ambil uang refund di Office Rumah Kos.";
            }
            // Sebutkan rekening tujuan supaya user bisa verifikasi
            if (!empty($userPenerima) && !empty($userPenerima['nomor_rekening'])) {
                $pesanNotif .= "\n\nTransfer dikirim ke: Bank " . ($userPenerima['nama_bank'] ?? '-') . " " . $userPenerima['nomor_rekening'] . " a.n. " . ($userPenerima['nama_pemilik_rek'] ?? '-') . "\n";
            } elseif (!empty($userPenerima) && !empty($userPenerima['ewallet_number'])) {
                $pesanNotif .= "\nTransfer dikirim ke e-wallet: " . ($userPenerima['ewallet_type'] ?? '-') . " " . $userPenerima['ewallet_number'] . "\n";
            }
            $pesanNotif .= "\n\n\xf0\x9f\x94\x91 Kunci kamar baru siap diambil di Office Rumah Kos (Jam 08:00-17:00 WIB).";
        } else {
            // Tidak ada tagihan tambahan, tidak ada refund
            $pesanNotif .= "\n\n\xf0\x9f\x94\x91 Kunci kamar baru siap diambil di Office Rumah Kos (Jam 08:00-17:00 WIB).";
        }

        $pesanNotif .= "\n\n\xe2\x9c\x85 Kebijakan Pindah Kamar:";
        $pesanNotif .= "\n\xe2\x80\xa2 Tagihan bulan berjalan & bulan depan yang sudah Anda bayar di kamar lama DIPINDAHKAN ke sewa kamar baru (tidak hangus).";
        if ($totalSelisihHargaLunas > 0) {
            $pesanNotif .= "\n\xe2\x80\xa2 Karena kamar baru lebih mahal, Anda wajib bayar SELISIH HARGA untuk " . $jumlahBulanLunasDipindah . " bulan yang sudah dibayar.";
        } elseif ($totalSelisihHargaLunas < 0) {
            $pesanNotif .= "\n\xe2\x80\xa2 Karena kamar baru lebih murah, selisih harga untuk " . $jumlahBulanLunasDipindah . " bulan yang sudah dibayar DIKEMBALIKAN ke Anda.";
        }
        $pesanNotif .= "\n\xe2\x80\xa2 Tagihan bulan ke-3 dst yang belum dibayar dipindah dengan HARGA KAMAR BARU.";
        $pesanNotif .= "\n\nKunci kamar lama harap dikembalikan. Terima kasih. \xf0\x9f\x99\x8f";

                // === FIX BUG #17: Notif ke admin kalau ada selisih deposit yang perlu dibayar user. ===
        if ($selisihDeposit > 0) {
            $userModel = new \App\Models\UserModel();
            $userPindah = $userModel->find($pengajuan['id_user']);
            $namaUser = $userPindah['nama'] ?? ('User #' . $pengajuan['id_user']);
            $admins = $userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $this->notifikasiModel->kirim(
                    $admin['id_user'],
                    '⚠️ Pindah Kamar - User Wajib Bayar Selisih Deposit',
                    $namaUser . ' pindah kamar ke No. ' . $kamarBaru['nomor_kamar'] . '. '
                    . 'Ada tagihan selisih deposit Rp ' . number_format($selisihDeposit,0,',','.') . ' yang WAJIB dibayar user sebelum kunci disiapkan. '
                    . 'Setelah user upload bukti & Anda verifikasi di menu Pembayaran, klik "Set Siap Kunci" di menu Sewa untuk mengaktifkan kunci.',
                    'pindah'
                );
            }
        }

        $successMsg = 'Pindah kamar disetujui!';
        if ($totalSelisihHargaLunas > 0) {
            $successMsg .= ' ⚠️ User WAJIB bayar SELISIH SEWA Rp ' . number_format($totalSelisihHargaLunas,0,',','.')
                         . ' untuk ' . $jumlahBulanLunasDipindah . ' bulan yang sudah dibayar (kamar baru lebih mahal).';
        }
        if ($selisihDeposit > 0) {
            $successMsg .= ' ⚠️ User WAJIB bayar SELISIH DEPOSIT Rp ' . number_format($selisihDeposit,0,',','.')
                         . ' sebelum kunci disiapkan. Setelah user bayar & Anda verifikasi, klik "Set Siap Kunci" di menu Sewa.';
        }
        if ($totalRefundKeUser > 0) {
            $successMsg .= ' Jangan lupa transfer refund Rp ' . number_format($totalRefundKeUser,0,',','.') . ' ke user.';
        }

        return redirect()->to('/admin/pindah-kamar')
                         ->with('success', $successMsg);
    }

    public function tolak($id)
    {
        $pengajuan = $this->pindahModel->find($id);
        if (!$pengajuan) {
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Pengajuan tidak ditemukan.');
        }

        // ANTI DOBEL: atomic update
        $db = \Config\Database::connect();
        $builder = $db->table('pengajuan_pindah');
        $builder->where('id_pindah', $id);
        $builder->where('status', 'menunggu');
        $builder->update(['status' => 'ditolak', 'tanggal_proses' => date('Y-m-d')]);

        // FIX: gunakan affectedRows(). Pakai < 1 untuk robustness.
        if ($db->affectedRows() < 1) {
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Pengajuan ini sudah diproses.');
        }

        $alasan = $this->request->getPost('alasan') ?: 'Pengajuan pindah kamar ditolak oleh admin.';

        // FIX C8: pakai builder karena field admin-only gak di $allowedFields.
        $this->pindahModel->builder()->where('id_pindah', $id)->update([
            'keterangan_admin'=> 'DITOLAK: ' . $alasan,
        ]);

        $this->notifikasiModel->kirim(
            $pengajuan['id_user'],
            '❌ Pindah Kamar Ditolak',
            'Maaf, pengajuan pindah kamar Anda ditolak. Alasan: ' . $alasan . '. Hubungi admin untuk info lebih lanjut.',
            'pindah'
        );

        return redirect()->to('/admin/pindah-kamar')->with('success', 'Pengajuan pindah kamar ditolak.');
    }

    /**
     * BATALKAN PENOLAKAN PINDAH KAMAR (Undo Reject)
     *
     * Untuk kasus admin salah tekan TOLAK padahal seharusnya SETUJUI/inspeksi.
     * Mengembalikan status dari 'ditolak' ke 'menunggu'.
     */
    public function batalkanTolak($id)
    {
        $pengajuan = $this->pindahModel->find($id);
        if (!$pengajuan) {
            return redirect()->to('/admin/pindah-kamar')->with('error', 'Pengajuan tidak ditemukan.');
        }

        if ($pengajuan['status'] !== 'ditolak') {
            return redirect()->to('/admin/pindah-kamar')
                             ->with('error', 'Hanya pengajuan berstatus DITOLAK yang bisa dibatalkan. Status sekarang: ' . ucfirst($pengajuan['status']) . '.');
        }

        // === FIX R3: CEK KAMAR SEBELUM revert status ===
        // Sebelumnya: revert status ke 'menunggu' dulu, baru cek kamar.
        // Kalau kamar unavailable, status stuck 'menunggu' (zombie) — admin gak bisa approve.
        // Fix: cek dulu, kalau unavailable tetap 'ditolak' + warning.
        $kamarBaru = $this->kamarModel->find($pengajuan['id_kamar_baru']);
        $kamarTersedia = $kamarBaru && $kamarBaru['status'] === 'tersedia';

        if (!$kamarTersedia) {
            // Tetap 'ditolak' — jangan revert ke menunggu supaya gak zombie.
            return redirect()->to('/admin/pindah-kamar')
                             ->with('warning', '⚠️ Tidak bisa batalkan penolakan: kamar tujuan (No. ' . ($kamarBaru['nomor_kamar'] ?? '?') . ') sudah tidak tersedia. Status tetap DITOLAK. User harus pilih kamar lain & ajukan pindah ulang.');
        }

        // === ANTI RACE CONDITION: atomic update (kamar sudah verified tersedia) ===
        $db = \Config\Database::connect();
        $builder = $db->table('pengajuan_pindah');
        $builder->where('id_pindah', $id);
        $builder->where('status', 'ditolak');
        $builder->update([
            'status'           => 'menunggu',
            'tanggal_proses'   => null,
            'keterangan_admin' => null,
        ]);

        // FIX: gunakan affectedRows(). Pakai < 1 untuk robustness.
        if ($db->affectedRows() < 1) {
            return redirect()->to('/admin/pindah-kamar')
                             ->with('error', 'Gagal membatalkan penolakan. Mungkin sudah diproses admin lain.');
        }

        // === NOTIFIKASI KE USER ===
        $this->notifikasiModel->kirim(
            $pengajuan['id_user'],
            '✅ Penolakan Pindah Kamar Dibatalkan',
            "Permohonan maaf, penolakan pengajuan pindah kamar Anda telah DIBATALKAN admin (kemungkinan salah tekan). Status kembali ke MENUNGGU. Admin akan inspeksi & meninjau kembali. Mohon tunggu. Terima kasih. 🙏",
            'pindah'
        );

        return redirect()->to('/admin/pindah-kamar')
                         ->with('success', '✅ Penolakan pindah kamar dibatalkan! Status kembali ke MENUNGGU. Anda bisa inspeksi & setujui kembali.');
    }
}