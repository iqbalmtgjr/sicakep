<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Periode;
use App\Models\RealisasiKinerja;
use App\Models\TargetKinerja;
use Livewire\Component;

class Dashboard extends Component
{
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
            // Dashboard untuk Atasan
            $data = [
                'realisasiMenunggu' => RealisasiKinerja::where('status', 'submitted')->count(),
                'realisasiVerified' => RealisasiKinerja::where('status', 'verified')->count(),
                'realisasiRejected' => RealisasiKinerja::where('status', 'rejected')->count(),
                'myRealisasi' => RealisasiKinerja::where('user_id', $user->id)->count(),
                'recentRealisasi' => RealisasiKinerja::with(['user', 'targetKinerja.indikatorKinerja'])
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
                $chartData['labels'][] = $target->indikatorKinerja->nama_indikator;
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
