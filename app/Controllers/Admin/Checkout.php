<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CheckoutModel;
use App\Models\SewaModel;
use App\Models\KamarModel;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;

class Checkout extends BaseController
{
    protected $checkoutModel;
    protected $sewaModel;
    protected $kamarModel;
    protected $pembayaranModel;
    protected $notifikasiModel;

    public function __construct()
    {
        $this->checkoutModel   = new CheckoutModel();
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

        $pengajuan = $this->checkoutModel->getAllWithDetail();

        // Filter di PHP (lebih simpel daripada ubah query model)
        if (!empty($statusFilter)) {
            $pengajuan = array_filter($pengajuan, fn($p) => $p['status'] === $statusFilter);
        }
        if (!empty($searchNama)) {
            $searchLower = strtolower($searchNama);
            $pengajuan = array_filter($pengajuan, function($p) use ($searchLower) {
                return strpos(strtolower($p['nama'] ?? ''), $searchLower) !== false
                    || strpos(strtolower($p['nomor_kamar'] ?? ''), $searchLower) !== false;
            });
        }

        // Statistik dari SEMUA data (sebelum filter) supaya angka stat tidak berubah
        $semua = $this->checkoutModel->getAllWithDetail();
        $total = count($semua);
        $menunggu = count(array_filter($semua, fn($p) => $p['status'] == 'menunggu'));
        $disetujui = count(array_filter($semua, fn($p) => $p['status'] == 'disetujui'));
        $ditolak = count(array_filter($semua, fn($p) => $p['status'] == 'ditolak'));

        $data = [
            'title'         => 'Persetujuan Checkout Penghuni',
            'pengajuan'     => $pengajuan,
            'filter_status' => $statusFilter,
            'search_nama'   => $searchNama,
            'total'         => $total,
            'menunggu'      => $menunggu,
            'disetujui'     => $disetujui,
            'ditolak'       => $ditolak,
        ];
        return view('admin/checkout/index', $data);
    }

    public function formInspeksi($id)
    {
        $dataPengajuan = $this->checkoutModel->getDetailForRefund($id);

        if (!$dataPengajuan) {
            return redirect()->to('/admin/checkout')->with('error', 'Data tidak ditemukan.');
        }

        // Bisa dibuka kalau status menunggu (untuk inspeksi) atau disetujui (untuk lihat detail)
        if (!in_array($dataPengajuan['status'], ['menunggu', 'disetujui'])) {
            return redirect()->to('/admin/checkout')->with('error', 'Pengajuan ini tidak bisa diproses.');
        }

        // === HITUNG SISA SEWA (HANYA dari tagihan yang SUDAH LUNAS) ===
        $today = new \DateTime(date('Y-m-d'));
        // Null safety: kalau tanggal_mulai null, fallback ke tanggal hari ini
        $tglMulai = $dataPengajuan['tanggal_mulai'] ?? date('Y-m-d');
        $mulai = new \DateTime($tglMulai);
        $totalBulan = $dataPengajuan['durasi_bulan'];
        
        $intervalDihuni = $mulai->diff($today);
        $bulanDihuni = ($intervalDihuni->y * 12) + $intervalDihuni->m;
        if ($intervalDihuni->d > 0) $bulanDihuni += 1;
        $bulanDihuni = max($bulanDihuni, 1);
        
        $sisaBulan = max($totalBulan - $bulanDihuni, 0);

        // Hitung refund dari tagihan yang SUDAH LUNAS untuk bulan depan
        $tagihanLunasDepan = $this->pembayaranModel
            ->where('id_sewa', $dataPengajuan['id_sewa'])
            ->where('status', 'lunas')
            ->where('bulan_ke >', 0)
            ->where('bulan_ke >', $bulanDihuni)
            ->findAll();
        
        $refundSewa = array_sum(array_column($tagihanLunasDepan, 'jumlah_bayar'));

        // === DETEKSI EARLY CHECKOUT (checkout SEBELUM kontrak berakhir) ===
        // Kebijakan: deposit dipotong 50% jika user checkout sebelum tanggal_selesai kontrak
        // Null safety: cek dulu tanggalnya ada, kalau null anggap BUKAN early checkout
        $tglSelesai = $dataPengajuan['tanggal_selesai'] ?? null;
        $tglCheckout = $dataPengajuan['tanggal_checkout_diajukan'] ?? null;

        if (!empty($tglSelesai) && !empty($tglCheckout)) {
            $tanggalSelesaiKontrak   = new \DateTime($tglSelesai);
            $tanggalCheckoutDiajukan = new \DateTime($tglCheckout);
            $isEarlyCheckout = ($tanggalCheckoutDiajukan < $tanggalSelesaiKontrak);
        } else {
            // Fallback kalau salah satu tanggal null (hindari crash DateTime)
            $isEarlyCheckout = false;
            $tanggalSelesaiKontrak   = null;
            $tanggalCheckoutDiajukan = null;
        }

        $deposit                 = (int) ($dataPengajuan['deposit'] ?? 0);
        $potonganEarlyCheckout   = $isEarlyCheckout ? (int) floor($deposit * 0.5) : 0;
        $maxPotonganKerusakan    = max($deposit - $potonganEarlyCheckout, 0);

        // Cek apakah ini view read-only (sudah disetujui)
        $isReadOnly = ($dataPengajuan['status'] == 'disetujui');

        $data = [
            'title'                  => 'Inspeksi & Setujui Checkout',
            'p'                      => $dataPengajuan,
            'bulanDihuni'            => $bulanDihuni,
            'sisaBulan'              => $sisaBulan,
            'refundSewa'             => $refundSewa,
            'hargaSewa'              => $dataPengajuan['harga_sewa'] ?? 0,
            'isReadOnly'             => $isReadOnly,
            'isEarlyCheckout'        => $isEarlyCheckout,
            'potonganEarlyCheckout'  => $potonganEarlyCheckout,
            'maxPotonganKerusakan'   => $maxPotonganKerusakan,
            'tanggalSelesaiKontrak'  => $dataPengajuan['tanggal_selesai'],
        ];
        return view('admin/checkout/inspeksi', $data);
    }

    public function setujui($id)
    {
        $pengajuan = $this->checkoutModel->find($id);
        if (!$pengajuan) {
            return redirect()->to('/admin/checkout')->with('error', 'Pengajuan tidak ditemukan.');
        }

        $sewa = $this->sewaModel->find($pengajuan['id_sewa']);
        if (!$sewa) {
            return redirect()->to('/admin/checkout')->with('error', 'Data sewa tidak ditemukan.');
        }

        // === FIX BUG: Validasi SEMUA input (file upload + form) SEBELUM atomic update. ===
        // Sebelumnya: status diubah ke 'disetujui' dulu, baru validasi.
        // Akibatnya: kalau validasi gagal, status stuck 'disetujui' & admin gak bisa retry
        // (form kirim lagi ketolak atomic guard WHERE status='menunggu').
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

        // Validasi potongan kerusakan dulu (sebelum atomic update)
        $potonganKerusakan = (int) $this->request->getPost('potongan_kerusakan');
        $catatanInspeksi   = $this->request->getPost('catatan_inspeksi');
        $deposit           = (int) ($sewa['deposit'] ?? 0);

        // === DETEKSI EARLY CHECKOUT (checkout SEBELUM kontrak berakhir) ===
        $tglSelesai  = $sewa['tanggal_selesai'] ?? null;
        $tglCheckout = $pengajuan['tanggal_checkout_diajukan'] ?? null;
        if (!empty($tglSelesai) && !empty($tglCheckout)) {
            $tanggalSelesaiKontrak   = new \DateTime($tglSelesai);
            $tanggalCheckoutDiajukan = new \DateTime($tglCheckout);
            $isEarlyCheckout         = ($tanggalCheckoutDiajukan < $tanggalSelesaiKontrak);
        } else {
            $isEarlyCheckout = false;
        }
        $potonganEarlyCheckout = $isEarlyCheckout ? (int) floor($deposit * 0.5) : 0;
        $maxPotonganKerusakan  = max($deposit - $potonganEarlyCheckout, 0);
        if ($potonganKerusakan < 0 || $potonganKerusakan > $maxPotonganKerusakan) {
            return redirect()->back()->with('error', 'Potongan kerusakan tidak valid. Maksimal Rp ' . number_format($maxPotonganKerusakan, 0, ',', '.') . ' (sisa deposit setelah potongan 50% early checkout).')->withInput();
        }

        // === FIX H3: ANTI DOBEL — atomic update WHERE status='menunggu'. ===
        // Hanya dijalankan SETELAH semua validasi lolos, supaya kalau ada error,
        // status tidak berubah & admin bisa perbaiki input tanpa reload page.
        $db = \Config\Database::connect();
        $builder = $db->table('pengajuan_checkout');
        $builder->where('id_checkout', $id);
        $builder->where('status', 'menunggu');
        $builder->update(['status' => 'disetujui']);

        if ($db->affectedRows() < 1) {
            return redirect()->to('/admin/checkout')->with('error', 'Pengajuan ini sudah diproses admin lain, atau status bukan menunggu. Tidak bisa disetujui 2x.');
        }

        // === HITUNG SISA SEWA (HANYA dari tagihan LUNAS) ===
        $today = new \DateTime(date('Y-m-d'));
        // Null safety: kalau tanggal_mulai null, fallback ke tanggal hari ini
        $tglMulai = $sewa['tanggal_mulai'] ?? date('Y-m-d');
        $mulai = new \DateTime($tglMulai);
        $totalBulan = $sewa['durasi_bulan'];
        $intervalDihuni = $mulai->diff($today);
        $bulanDihuni = ($intervalDihuni->y * 12) + $intervalDihuni->m;
        if ($intervalDihuni->d > 0) $bulanDihuni += 1;
        $bulanDihuni = max($bulanDihuni, 1);

        // === FIX: Hitung refund dari tagihan yang SUDAH LUNAS untuk bulan depan ===
        $tagihanLunasDepan = $this->pembayaranModel
            ->where('id_sewa', $sewa['id_sewa'])
            ->where('status', 'lunas')
            ->where('bulan_ke >', 0)
            ->where('bulan_ke >', $bulanDihuni)
            ->findAll();
        
        $refundSewa = array_sum(array_column($tagihanLunasDepan, 'jumlah_bayar'));

        // Refund deposit = deposit - potonganEarlyCheckout (50%) - potonganKerusakan (inspeksi)
        $refundDeposit = max($deposit - $potonganEarlyCheckout - $potonganKerusakan, 0);
        $totalRefund   = $refundSewa + $refundDeposit;

        // Upload bukti refund — pakai extension dari whitelist (bukan getRandomName yang jaga extension asli)
        // FIX BUG: $file->move() throw HTTPException saat gagal — bukan return false.
        // Bungkus try-catch supaya kalau gagal, atomic update bisa di-rollback & admin bisa retry.
        try {
            $namaBukti = $file->getRandomName();
            $namaBukti = pathinfo($namaBukti, PATHINFO_FILENAME) . '.' . $ext;
            $file->move(ROOTPATH . 'public/uploads/', $namaBukti);
        } catch (\Throwable $e) {
            // Rollback atomic update supaya status tidak stuck 'disetujui'
            $this->checkoutModel->builder()->where('id_checkout', $id)->update(['status' => 'menunggu']);
            log_message('error', '[Checkout::setujui] Gagal upload bukti refund: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal upload bukti refund: ' . $e->getMessage())->withInput();
        }

        // === TRANSACTION: Semua operasi multi-tabel dalam 1 transaksi ===
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update sewa
            // FIX C8: pakai builder() karena 'status' & 'deposit_dikembalikan' gak di $allowedFields.
            $this->sewaModel->builder()->where('id_sewa', $sewa['id_sewa'])->update([
                'status'               => 'selesai',
                'tanggal_selesai'      => $pengajuan['tanggal_checkout_diajukan'],
                'deposit_dikembalikan' => $refundDeposit,
                'status_kunci'         => 'sudah_dikembalikan',
            ]);

            // Update kamar
            $this->kamarModel->builder()->where('id_kamar', $pengajuan['id_kamar'])->update(['status' => 'tersedia']);

            // === FIX: JANGAN HAPUS tagihan menunggu_verifikasi ===
            // Hanya hapus tagihan belum_bayar (yang user belum transfer)
            $this->pembayaranModel
                ->where('id_sewa', $sewa['id_sewa'])
                ->where('status', 'belum_bayar')
                ->delete();

            // Update checkout
            $keteranganAdmin = $catatanInspeksi;
            if ($isEarlyCheckout) {
                $keteranganAdmin .= ' | Early Checkout -50% Deposit: Rp ' . number_format($potonganEarlyCheckout, 0, ',', '.');
            }
            $keteranganAdmin .= ' | Potongan Kerusakan: Rp ' . number_format($potonganKerusakan, 0, ',', '.');

            // FIX C8: pakai builder() langsung karena field admin-only gak di $allowedFields.
            $this->checkoutModel->builder()->where('id_checkout', $id)->update([
                'tanggal_proses'  => date('Y-m-d'),
                'keterangan_admin'=> $keteranganAdmin,
                'bukti_refund'    => $namaBukti,
                'tanggal_refund'  => date('Y-m-d'),
                'total_refund'    => $totalRefund,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/admin/checkout')->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }

        $kamar = $this->kamarModel->find($sewa['id_kamar']);

        // === FIX BUG: ambil data user untuk sebutkan rekening tujuan di notifikasi ===
        $userModel = new \App\Models\UserModel();
        $userPenerima = get_user_with_rekening($pengajuan['id_user']);

        // Notif ke user
        $pesan = "Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. " . $kamar['nomor_kamar'] . ".\n\n";
        $pesan .= "📊 Rincian Pengembalian Dana:\n";
        $pesan .= "• Lama Huni: " . $bulanDihuni . " bulan (dari " . $totalBulan . " bulan kontrak)\n";
        $pesan .= "• Refund Sisa Sewa (dari tagihan lunas): Rp " . number_format($refundSewa,0,',','.') . "\n";
        $pesan .= "• Deposit: Rp " . number_format($deposit,0,',','.') . "\n";
        if ($isEarlyCheckout) {
            $pesan .= "• Potongan Early Checkout (50% Deposit): -Rp " . number_format($potonganEarlyCheckout,0,',','.') . "\n";
        }
        if ($potonganKerusakan > 0) {
            $pesan .= "• Potongan Kerusakan: -Rp " . number_format($potonganKerusakan,0,',','.') . "\n";
        }
        $pesan .= "• Refund Deposit: Rp " . number_format($refundDeposit,0,',','.') . "\n\n";
        $pesan .= "💰 TOTAL DIKEMBALIKAN: Rp " . number_format($totalRefund,0,',','.') . "\n\n";
        $pesan .= "Bukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\n";
        // FIX: Sebutkan rekening tujuan supaya user bisa verifikasi
        // FIX null safety: $userPenerima bisa null kalau user sudah dihapus
        if (!empty($userPenerima) && !empty($userPenerima['nomor_rekening'])) {
            $pesan .= "Transfer dikirim ke: Bank " . ($userPenerima['nama_bank'] ?? '-') . " " . $userPenerima['nomor_rekening'] . " a.n. " . ($userPenerima['nama_pemilik_rek'] ?? '-') . "\n";
        } elseif (!empty($userPenerima) && !empty($userPenerima['ewallet_number'])) {
            $pesan .= "Transfer dikirim ke e-wallet: " . ($userPenerima['ewallet_type'] ?? '-') . " " . $userPenerima['ewallet_number'] . "\n";
        } else {
            $pesan .= "Mohon konfirmasi no rekening Anda ke admin jika belum menerima refund dalam 1x24 jam.\n";
        }
        $pesan .= "\n";
        $pesan .= "Catatan Inspeksi: " . $catatanInspeksi . "\n\n";
        $pesan .= "Terima kasih. 🙏";

        $this->notifikasiModel->kirim(
            $pengajuan['id_user'],
            '✅ Checkout Disetujui - Pengembalian Dana',
            $pesan,
            'checkout'
        );

        return redirect()->to('/admin/checkout')->with('success', 'Checkout disetujui! Total refund Rp ' . number_format($totalRefund,0,',','.') . ' ke user.');
    }

    public function tolak($id)
    {
        $pengajuan = $this->checkoutModel->find($id);
        if (!$pengajuan) {
            return redirect()->to('/admin/checkout')->with('error', 'Pengajuan tidak ditemukan.');
        }

        // === FIX BUG: gunakan atomic update pattern (sama seperti setujui), ===
        // bukan if-check-then-update. Race condition: 2 admin klik Tolak bersamaan.
        $db = \Config\Database::connect();
        $builder = $db->table('pengajuan_checkout');
        $builder->where('id_checkout', $id);
        $builder->where('status', 'menunggu');
        $builder->update([
            'status'         => 'ditolak',
            'tanggal_proses' => date('Y-m-d'),
        ]);

        if ($db->affectedRows() < 1) {
            return redirect()->to('/admin/checkout')->with('error', 'Pengajuan ini sudah diproses admin lain. Tidak bisa ditolak lagi.');
        }

        $alasan = $this->request->getPost('alasan') ?: 'Pengajuan check-out ditolak oleh admin.';

        // FIX C8: pakai builder() karena field admin-only gak di $allowedFields.
        $this->checkoutModel->builder()->where('id_checkout', $id)->update([
            'keterangan_admin'=> 'DITOLAK: ' . $alasan,
        ]);

        $this->notifikasiModel->kirim(
            $pengajuan['id_user'],
            '❌ Check-Out Ditolak',
            "Pengajuan check-out Anda ditolak. Alasan: " . $alasan . ". Hubungi admin untuk informasi lebih lanjut.",
            'checkout'
        );

        return redirect()->to('/admin/checkout')->with('success', 'Pengajuan check-out ditolak.');
    }
}