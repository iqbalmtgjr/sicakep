<?php
// app/Livewire/Laporan/HierarkiKinerja.php

namespace App\Livewire\Laporan;

use App\Models\User;
use App\Models\Periode;
use App\Models\PenilaianKinerja;
use App\Models\PenilaianKategori;
use Livewire\Component;

class HierarkiKinerja extends Component
{
    public $periode_id;
    public $selectedUser;
    public $showDetail = false;

    public function mount()
    {
        $periodeAktif = Periode::where('is_active', true)->first();
        $this->periode_id = $periodeAktif?->id;
    }

    public function render()
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();

        // Tentukan root hierarki berdasarkan user yang login
        $user = auth()->user();
        if ($user->isKepalaDinas() || $user->isAdmin()) {
            // Kepala Dinas atau Admin bisa lihat semua hierarki
            $rootUser = User::with(['bawahan' => function ($query) {
                $query->with(['bawahan.bawahan']); // Nested loading sampai 3 level
            }])->where('level_jabatan', 'kepala_dinas')->first();
        } else {
            // Atasan hanya bisa lihat hierarki bawahannya
            $rootUser = User::with(['bawahan' => function ($query) {
                $query->with(['bawahan.bawahan']); // Nested loading sampai 3 level
            }])->find($user->id);
        }

        // Ambil penilaian kinerja untuk periode yang dipilih
        $penilaians = [];
        if ($this->periode_id) {
            if ($user->isKepalaDinas() || $user->isAdmin()) {
                // Kepala Dinas atau Admin bisa lihat semua penilaian
                $penilaians = PenilaianKinerja::where('periode_id', $this->periode_id)
                    ->with('user')
                    ->get()
                    ->keyBy('user_id');
            } else {
                // Atasan hanya bisa lihat penilaian bawahannya
                $bawahanIds = $user->getAllBawahan()->pluck('id')->toArray();
                $bawahanIds[] = $user->id; // Include self
                $penilaians = PenilaianKinerja::where('periode_id', $this->periode_id)
                    ->whereIn('user_id', $bawahanIds)
                    ->with('user')
                    ->get()
                    ->keyBy('user_id');
            }
        }

        return view('livewire.laporan.hierarki-kinerja', compact('periodes', 'rootUser', 'penilaians'));
    }

    public function showUserDetail($userId)
    {
        $this->selectedUser = User::with([
            'targetKinerja' => function ($query) {
                $query->where('periode_id', $this->periode_id)
                    ->with(['indikatorKinerja', 'realisasiKinerja']);
            },
            'penilaianKinerja' => function ($query) {
                $query->where('periode_id', $this->periode_id);
            }
        ])->findOrFail($userId);

        $this->showDetail = true;
        $this->dispatch('showDetailModal');
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedUser = null;
        $this->dispatch('closeDetailModal');
    }

    public function getBadgeClass($nilai)
    {
        if (!$nilai) {
            return 'secondary';
        }

        $kategori = PenilaianKategori::where('min_nilai', '<=', $nilai)
            ->where('max_nilai', '>=', $nilai)
            ->first();

        return $kategori ? $kategori->badge : 'secondary';
    }
}
