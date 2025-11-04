<?php

namespace App\Livewire\HasilKinerja;

use Livewire\Component;
use App\Models\Hasilkinerja;
use App\Models\IndikatorKinerja;
use Livewire\WithPagination;
use App\Models\RealisasiKinerja;
use App\Models\PenilaianKategori;
use App\Models\PenilaianKinerja;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $notifikasiPenilaian = null;

    protected $rules = [];

    public function getPesanPenilaian($nilai)
    {
        return PenilaianKategori::where('min_nilai', '<=', $nilai)
            ->where('max_nilai', '>=', $nilai)
            ->first();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function exportPdf($id)
    {
        $penilaian = PenilaianKinerja::with(['user', 'periode', 'penilai'])->findOrFail($id);
        // dd($penilaian);

        // Check if user has permission to view this penilaian
        $user = auth()->user();
        if (!$user->isKepalaDinas() && !$user->isAdmin()) {
            $bawahanIds = $user->getAllBawahan()->pluck('id')->toArray();
            $bawahanIds[] = $user->id;
            if (!in_array($penilaian->user_id, $bawahanIds)) {
                abort(403, 'Unauthorized');
            }
        }

        $nilai = $penilaian->nilai_kinerja ?? 0;
        $kategoriData = $this->getPesanPenilaian($nilai);
        $indikators = IndikatorKinerja::where('user_id', $penilaian->user_id)->get();

        $data = [
            'penilaian' => $penilaian,
            'nilai' => $nilai,
            'kategoriData' => $kategoriData,
            'indikators' => $indikators,
        ];

        // dd($data['penilaian']);

        $pdf = Pdf::loadView('pdf.hasil-kinerja', $data);

        return Response::streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'hasil-kinerja-' . $penilaian->user->name . '-' . $penilaian->periode->nama_periode . '.pdf');
    }

    public function render()
    {
        $user = auth()->user();

        if ($user->isKepalaDinas() || $user->isAdmin()) {
            // Kepala Dinas atau Admin bisa lihat semua penilaian kinerja
            $query = PenilaianKinerja::with(['user', 'periode']);
        } else {
            // Atasan hanya bisa lihat penilaian bawahannya
            $bawahanIds = $user->getAllBawahan()->pluck('id')->toArray();
            $bawahanIds[] = $user->id; // Include self
            $query = PenilaianKinerja::whereIn('user_id', $bawahanIds)->with(['user', 'periode']);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('periode', function ($periodeQuery) {
                    $periodeQuery->where('nama_periode', 'like', '%' . $this->search . '%');
                });
            });
        }

        $datas = $query->latest()->paginate(10);

        return view('livewire.hasil-kinerja.index', compact('datas'));
    }
}
