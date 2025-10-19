<?php

namespace App\Observers;

use App\Models\PenilaianKinerja;
use App\Models\Notifikasi;

class PenilaianKinerjaObserver
{
    /**
     * Handle the PenilaianKinerja "created" event.
     */
    public function created(PenilaianKinerja $penilaian): void
    {
        $this->createNotification($penilaian);
    }

    /**
     * Handle the PenilaianKinerja "updated" event.
     */
    public function updated(PenilaianKinerja $penilaian): void
    {
        // Jika penilaian diupdate, buat notifikasi baru
        // Atau update notifikasi yang ada (tergantung kebutuhan)
        $this->createNotification($penilaian, true);
    }

    /**
     * Create notification for penilaian kinerja
     */
    private function createNotification(PenilaianKinerja $penilaian, bool $isUpdate = false): void
    {
        // Tentukan kategori berdasarkan nilai
        $kategori = $this->getKategori($penilaian->nilai_kinerja);

        // Siapkan data untuk pesan notifikasi (dalam format JSON)
        $pesanData = [
            'nilai' => $penilaian->nilai_kinerja,
            'persentase' => $penilaian->persentase_capaian,
            'periode' => $penilaian->periode->nama_periode,
            'predikat' => $penilaian->predikat,
            'catatan' => $penilaian->catatan,
            'tanggal' => $penilaian->tanggal_penilaian->format('d F Y'),
            'penilai' => $penilaian->penilai->name ?? 'Atasan',
        ];

        // Judul notifikasi
        $judul = $isUpdate
            ? "Penilaian Kinerja Anda Telah Diperbarui!"
            : "Penilaian Kinerja Anda Telah Tersedia!";

        // Jika update, tandai notifikasi lama sebagai sudah dibaca
        if ($isUpdate) {
            Notifikasi::where('user_id', $penilaian->user_id)
                ->where('tipe', 'penilaian')
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        // Buat notifikasi baru
        Notifikasi::create([
            'user_id' => $penilaian->user_id,
            'judul' => $judul,
            'pesan' => json_encode($pesanData),
            'tipe' => 'penilaian',
            'is_read' => false,
        ]);
    }

    /**
     * Get kategori based on nilai
     */
    private function getKategori($nilai): string
    {
        if ($nilai >= 91 && $nilai <= 100) {
            return 'Sangat Baik';
        } elseif ($nilai >= 76 && $nilai <= 90) {
            return 'Baik';
        } elseif ($nilai >= 61 && $nilai <= 75) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }
}
