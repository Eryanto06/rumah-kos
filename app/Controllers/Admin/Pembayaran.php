<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;

class Pembayaran extends BaseController
{
    protected $pembayaranModel;
    protected $notifikasiModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function index()
    {
        $semua = $this->pembayaranModel->getPembayaranWithDetail();

        $grouped = [];
        foreach ($semua as $p) {
            $key = $p['id_sewa'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'nama'           => $p['nama'] ?? $p['nama_user'] ?? '-',
                    'kode_kamar'     => $p['kode_kamar'] ?? '-',
                    'nomor_kamar'    => $p['nomor_kamar'] ?? '-',
                    'items'          => [],
                    'total_lunas'    => 0,
                    'total_belum'    => 0,
                    'total_menunggu' => 0,
                ];
            }
            $grouped[$key]['items'][] = $p;
            if ($p['status'] === 'lunas') {
                $grouped[$key]['total_lunas']++;
            } elseif ($p['status'] === 'belum_bayar') {
                $grouped[$key]['total_belum']++;
            } else {
                $grouped[$key]['total_menunggu']++;
            }
        }

        $totalLunas = $totalBelum = $totalMenunggu = 0;
        foreach ($grouped as $g) {
            $totalLunas    += $g['total_lunas'];
            $totalBelum    += $g['total_belum'];
            $totalMenunggu += $g['total_menunggu'];
        }

        $data = [
            'title'          => 'Data Pembayaran',
            'grouped'        => $grouped,
            'total_lunas'    => $totalLunas,
            'total_belum'    => $totalBelum,
            'total_menunggu' => $totalMenunggu,
        ];
        return view('admin/pembayaran/index', $data);
    }

    public function detail($id)
    {
        $db = \Config\Database::connect();

        $pembayaran = $db->table('pembayaran p')
            ->select('p.*, u.nama, u.email, u.no_hp' . rekening_select_clause('u') . ', k.kode_kamar, k.nomor_kamar, k.harga_sewa, s.tanggal_mulai, s.tanggal_selesai')
            ->join('sewa s', 's.id_sewa = p.id_sewa', 'left')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('p.id_pembayaran', $id)
            ->get()->getRowArray();

        if (!$pembayaran) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $relatedPayments = [];
        if (!empty($pembayaran['bukti_bayar']) && $pembayaran['status'] === 'menunggu_verifikasi') {
            $relatedPayments = $db->table('pembayaran')
                ->where('id_sewa', $pembayaran['id_sewa'])
                ->where('status', 'menunggu_verifikasi')
                ->where('bukti_bayar', $pembayaran['bukti_bayar'])
                ->orderBy('bulan_ke', 'ASC')
                ->get()->getResultArray();
        }

        $data = [
            'title'           => 'Detail Pembayaran',
            'pembayaran'      => $pembayaran,
            'relatedPayments' => $relatedPayments,
        ];
        return view('admin/pembayaran/detail', $data);
    }

    public function verifikasi($id)
    {
        $status          = $this->request->getPost('status');
        $keterangan      = $this->request->getPost('keterangan');
        $verifikasiSemua = $this->request->getPost('verifikasi_semua');

        // === FIX Bug #5: WHITELIST status yang diizinkan ===
        $allowedStatus = ['lunas', 'belum_bayar', 'menunggu_verifikasi'];
        if (!in_array($status, $allowedStatus)) {
            return redirect()->to('/admin/pembayaran')
                             ->with('error', 'Status tidak valid! Pilihan: Lunas, Belum Bayar, atau Menunggu Verifikasi.');
        }

        $pembayaran = $this->pembayaranModel->find($id);
        if (!$pembayaran) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // === Anti-dobel: cek status saat ini ===
        if ($pembayaran['status'] === 'lunas' && $status !== 'lunas') {
            return redirect()->to('/admin/pembayaran')
                             ->with('error', 'Pembayaran ini sudah LUNAS. Tidak bisa diubah ke status lain. Hubungi developer jika perlu koreksi data.');
        }

        $idsToUpdate = [$id];

        if ($verifikasiSemua && !empty($pembayaran['bukti_bayar'])) {
            $related = $this->pembayaranModel
                ->where('id_sewa', $pembayaran['id_sewa'])
                ->where('status', 'menunggu_verifikasi')
                ->where('bukti_bayar', $pembayaran['bukti_bayar'])
                ->findAll();
            $idsToUpdate = array_column($related, 'id_pembayaran');
        }

        $db = \Config\Database::connect();
        $sewa = $db->table('sewa')->where('id_sewa', $pembayaran['id_sewa'])->get()->getRowArray();
        $idUser = $sewa['id_user'] ?? null;

        $totalDiperbarui = 0;
        $totalJumlah = 0;
        $allLabels = [];
        $fileToDelete = []; // kumpulkan file yang akan dihapus di luar transaction

        // === TRANSACTION: Update semua tagihan batch dalam 1 transaksi ===
        $db->transStart();

        try {
            foreach ($idsToUpdate as $updateId) {
                $pay = $this->pembayaranModel->find($updateId);
                if (!$pay) continue;

                $updateData = [
                    'status'     => $status,
                    'keterangan' => $keterangan,
                ];

                if ($status === 'belum_bayar') {
                    if (!empty($pay['bukti_bayar'])) {
                        $others = $this->pembayaranModel
                            ->where('bukti_bayar', $pay['bukti_bayar'])
                            ->where('id_pembayaran !=', $updateId)
                            ->countAllResults();
                        if ($others == 0) {
                            $fileToDelete[] = $pay['bukti_bayar']; // simpan, hapus nanti
                        }
                    }
                    $updateData['bukti_bayar']   = null;
                    $updateData['tanggal_bayar'] = null;
                } elseif ($status === 'lunas') {
                    // FIX H4: jangan overwrite tanggal_bayar yang sudah ada (tanggal user upload bukti).
                    // Hanya set kalau masih null/empty.
                    // FIX BUG: pakai format DATETIME (kolom tanggal_bayar adalah DATETIME).
                    if (empty($pay['tanggal_bayar'])) {
                        $updateData['tanggal_bayar'] = date('Y-m-d H:i:s');
                    }
                }

                $this->pembayaranModel->builder()->where('id_pembayaran', $updateId)->update($updateData);
                $totalDiperbarui++;
                $totalJumlah += $pay['jumlah_bayar'];

                $isDep = ($pay['bulan_ke'] == 0);
                $allLabels[] = $isDep ? 'Deposit' : 'Bulan ' . $pay['bulan_ke'];
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[Pembayaran::verifikasi] Gagal: ' . $e->getMessage());
            return redirect()->to('/admin/pembayaran')
                             ->with('error', 'Gagal verifikasi pembayaran: ' . $e->getMessage() . '. Tidak ada data yang berubah.');
        }

        // === Hapus file bukti bayar (di luar transaction, file system) ===
        foreach ($fileToDelete as $bukti) {
            $path = ROOTPATH . 'public/uploads/' . $bukti;
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // === KIRIM NOTIFIKASI (di luar transaction) ===
        if ($idUser) {
            $jumlahTagihan = count($allLabels);
            $labelText = $jumlahTagihan == 1
                ? ($pembayaran['bulan_ke'] == 0 ? 'Deposit' : 'Sewa Bulan ke-' . $pembayaran['bulan_ke'])
                : $jumlahTagihan . ' tagihan (' . implode(', ', $allLabels) . ')';

            $terimaKasih = "\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos";

            if ($status === 'lunas') {
                $pesanNotif = 'Pembayaran ' . $labelText . ' sebesar Rp ' . number_format($totalJumlah, 0, ',', '.') . ' telah DIVERIFIKASI dan berstatus LUNAS.';
                if ($keterangan) {
                    $pesanNotif .= "\n\nCatatan admin: " . $keterangan;
                }
                $pesanNotif .= $terimaKasih;

                $this->notifikasiModel->kirim($idUser, '✅ Pembayaran Diverifikasi (Lunas)', $pesanNotif, 'pembayaran');
            } elseif ($status === 'belum_bayar') {
                $pesanNotif = 'Pembayaran ' . $labelText . ' sebesar Rp ' . number_format($totalJumlah, 0, ',', '.') . ' DITOLAK oleh admin. Status tagihan kembali menjadi Belum Bayar.';
                if ($keterangan) {
                    $pesanNotif .= "\n\nAlasan: " . $keterangan;
                }
                $pesanNotif .= "\n\nSilakan upload ulang bukti pembayaran yang jelas. Terima kasih atas pengertiannya. - Admin Rumah Kos";

                $this->notifikasiModel->kirim($idUser, '❌ Pembayaran Ditolak', $pesanNotif, 'pembayaran');
            } elseif ($status === 'menunggu_verifikasi') {
                $this->notifikasiModel->kirim($idUser, '⏳ Pembayaran Dalam Proses Verifikasi',
                    'Pembayaran ' . $labelText . ' sebesar Rp ' . number_format($totalJumlah, 0, ',', '.') . ' sedang menunggu verifikasi admin.' . $terimaKasih,
                    'pembayaran');
            }
        }

        $statusLabel = ucfirst(str_replace('_', ' ', $status));
        $infoBatch = $totalDiperbarui > 1 ? ' (' . $totalDiperbarui . ' tagihan sekaligus)' : '';
        return redirect()->to('/admin/pembayaran')->with('success', 'Status pembayaran diupdate menjadi "' . $statusLabel . '"' . $infoBatch . ' & notifikasi dikirim ke user.');
    }
}