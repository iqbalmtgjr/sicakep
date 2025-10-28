<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Periode;
use App\Models\RealisasiKinerja;
use App\Models\TargetKinerja;
use App\Models\Notifikasi;
use Livewire\Component;

class Dashboard extends Component
{
    public $notifikasiVerifikasi = null;

    public function mount()
    {
        if (auth()->user()->isPegawai()) {
            $this->checkNotifikasiVerifikasi();
        }
    }

    public function checkNotifikasiVerifikasi()
    {
        // Ambil notifikasi verifikasi yang belum dibaca
        $this->notifikasiVerifikasi = Notifikasi::where('user_id', auth()->id())
            ->where('tipe', 'verifikasi')
            ->unread()
            ->latest()
            ->first();
    }

    public function dismissNotifikasiVerifikasi($notifikasiId)
    {
        $notifikasi = Notifikasi::where('id', $notifikasiId)
            ->where('user_id', auth()->id())
            ->first();

        if ($notifikasi) {
            $notifikasi->markAsRead();
            $this->notifikasiVerifikasi = null;
            flash('Notifikasi telah ditandai sebagai dibaca.', 'success', [], 'Berhasil');
        }
    }

    public function render()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Dashboard untuk Admin
            $data = [
                'totalPegawai' => User::where('role', '!=', 'admin')->count(),
                'totalPeriode' => Periode::count(),
                'periodeAktif' => Periode::where('is_active', true)->count(),
                'realisasiMenunggu' => RealisasiKinerja::where('status', 'submitted')->count(),
                'realisasiVerified' => RealisasiKinerja::where('status', 'verified')->count(),
                'realisasiRejected' => RealisasiKinerja::where('status', 'rejected')->count(),
                'recentRealisasi' => RealisasiKinerja::with(['user', 'targetKinerja.indikatorKinerja'])
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
        } elseif ($user->isAtasan()) {
            // Dashboard untuk Atasan - hanya melihat realisasi bawahan
            $bawahanIds = $user->getAllBawahan()->pluck('id')->toArray();

            $data = [
                'realisasiMenunggu' => RealisasiKinerja::whereIn('user_id', $bawahanIds)
                    ->where('status', 'submitted')->count(),
                'realisasiVerified' => RealisasiKinerja::whereIn('user_id', $bawahanIds)
                    ->where('status', 'verified')->count(),
                'realisasiRejected' => RealisasiKinerja::whereIn('user_id', $bawahanIds)
                    ->where('status', 'rejected')->count(),
                'myRealisasi' => RealisasiKinerja::where('user_id', $user->id)->count(),
                'recentRealisasi' => RealisasiKinerja::with(['user', 'targetKinerja.indikatorKinerja'])
                    ->whereIn('user_id', $bawahanIds)
                    ->where('status', 'submitted')
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
        } else {
            // Dashboard untuk Pegawai
            $periodeAktif = Periode::where('is_active', true)->first();

            $myTargets = TargetKinerja::where('user_id', $user->id)
                ->when($periodeAktif, function ($q) use ($periodeAktif) {
                    $q->where('periode_id', $periodeAktif->id);
                })
                ->with('indikatorKinerja')
                ->get();

            $totalTarget = $myTargets->sum('target');
            $totalRealisasi = 0;

            foreach ($myTargets as $target) {
                $totalRealisasi += $target->getTotalRealisasi();
            }

            $persentaseCapaian = $totalTarget > 0 ? ($totalRealisasi / $totalTarget) * 100 : 0;

            // Data untuk chart
            $chartData = [
                'labels' => [],
                'series' => [],
            ];

            foreach ($myTargets as $target) {
                $chartData['labels'][] = $target->indikatorKinerja->indikator_program;
                $chartData['series'][] = round($target->getPersentaseCapaian(), 2);
            }

            $data = [
                'totalTarget' => $myTargets->count(),
                'realisasiDraft' => RealisasiKinerja::where('user_id', $user->id)
                    ->where('status', 'draft')->count(),
                'realisasiSubmitted' => RealisasiKinerja::where('user_id', $user->id)
                    ->where('status', 'submitted')->count(),
                'realisasiVerified' => RealisasiKinerja::where('user_id', $user->id)
                    ->where('status', 'verified')->count(),
                'totalRealisasi' => $totalRealisasi,
                'totalTarget' => $totalTarget,
                'persentaseCapaian' => $persentaseCapaian,
                'myTargets' => $myTargets,
                'periodeAktif' => $periodeAktif,
                'chartData' => $chartData,
            ];
        }

        return view('livewire.dashboard', $data);
    }
}
