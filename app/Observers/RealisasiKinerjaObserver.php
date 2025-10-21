<?php

namespace App\Observers;

use App\Models\RealisasiKinerja;
use App\Models\PenilaianKinerja;
use App\Models\Periode;
use App\Models\PenilaianKategori;
use App\Models\Notifikasi;
use App\Models\User;

class RealisasiKinerjaObserver
{
    /**
     * Handle the RealisasiKinerja "updated" event.
     */
    public function updated(RealisasiKinerja $realisasi): void
    {
        // Cek jika status berubah menjadi 'verified'
        if ($realisasi->status === 'verified' && $realisasi->wasChanged('status')) {
            $this->createOrUpdatePenilaianKinerja($realisasi);
        }

        // Cek jika status berubah menjadi 'submitted'
        if ($realisasi->status === 'submitted' && $realisasi->wasChanged('status')) {
            $this->notifyAtasanAdmin($realisasi);
        }
    }

    /**
     * Create or update penilaian kinerja otomatis
     */
    private function createOrUpdatePenilaianKinerja(RealisasiKinerja $realisasi): void
    {
        $userId = $realisasi->user_id;
        $periodeId = $realisasi->targetKinerja->periode_id;
        $targetKinerjaId = $realisasi->target_kinerja_id;

        // Hitung total realisasi dan target untuk periode ini
        $totalRealisasi = RealisasiKinerja::where('user_id', $userId)
            ->whereHas('targetKinerja', function ($query) use ($periodeId) {
                $query->where('periode_id', $periodeId);
            })
            ->where('status', 'verified')
            ->sum('realisasi');

        $totalTarget = RealisasiKinerja::where('realisasi_kinerja.user_id', $userId)
            ->whereHas('targetKinerja', function ($query) use ($periodeId) {
                $query->where('periode_id', $periodeId);
            })
            ->join('target_kinerja', 'realisasi_kinerja.target_kinerja_id', '=', 'target_kinerja.id')
            ->sum('target_kinerja.target');

        // Hitung persentase capaian
        $persentaseCapaian = $totalTarget > 0 ? ($totalRealisasi / $totalTarget) * 100 : 0;

        // Tentukan nilai kinerja (menggunakan persentase capaian)
        $nilaiKinerja = round($persentaseCapaian, 2);

        // Tentukan predikat berdasarkan kategori
        $kategori = PenilaianKategori::where('min_nilai', '<=', $nilaiKinerja)
            ->where('max_nilai', '>=', $nilaiKinerja)
            ->first();

        $predikat = $kategori ? $kategori->kategori : 'Tidak Diketahui';

        // Cari atau buat penilaian kinerja
        $penilaian = PenilaianKinerja::where('user_id', $userId)
            ->where('periode_id', $periodeId)
            ->first();

        $data = [
            'user_id' => $userId,
            'periode_id' => $periodeId,
            'target_kinerja_id' => $targetKinerjaId,
            'nilai_kinerja' => $nilaiKinerja,
            'persentase_capaian' => $persentaseCapaian,
            'predikat' => $predikat,
            'tanggal_penilaian' => now(),
            'dinilai_oleh' => auth()->id() ?? 1, // Atasan/admin yang memverifikasi (fallback ke ID 1 jika null)
            'catatan' => $kategori ? $kategori->catatan : 'Tidak Ada Catatan',
        ];

        if ($penilaian) {
            // Update penilaian yang ada
            $penilaian->update($data);
        } else {
            // Buat penilaian baru
            PenilaianKinerja::create($data);
        }
    }

    /**
     * Notify atasan and admin when realisasi is submitted
     */
    private function notifyAtasanAdmin(RealisasiKinerja $realisasi): void
    {
        // Ambil semua user dengan role atasan atau admin
        $atasanAdminUsers = User::whereIn('role', ['atasan', 'admin'])->get();

        // Siapkan data untuk pesan notifikasi
        $pesanData = [
            'realisasi_id' => $realisasi->id,
            'user_name' => $realisasi->user->name,
            'indikator' => $realisasi->targetKinerja->indikatorKinerja->nama_indikator,
            'periode' => $realisasi->targetKinerja->periode->nama_periode,
            'realisasi' => $realisasi->realisasi,
            'tanggal_realisasi' => $realisasi->tanggal_realisasi->format('d F Y'),
            'keterangan' => $realisasi->keterangan,
        ];

        // Judul notifikasi
        $judul = "Realisasi Kinerja Menunggu Verifikasi";

        // Buat notifikasi untuk setiap atasan/admin
        foreach ($atasanAdminUsers as $user) {
            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => $judul,
                'pesan' => json_encode($pesanData),
                'tipe' => 'verifikasi',
                'is_read' => false,
            ]);
        }
    }
}
