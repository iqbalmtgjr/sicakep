<?php

namespace App\Livewire\HasilKinerja;

use Livewire\Component;
use App\Models\Hasilkinerja;
use Livewire\WithPagination;
use App\Models\RealisasiKinerja;
use App\Models\PenilaianKategori;
use App\Models\PenilaianKinerja;

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

    public function render()
    {
        $query = PenilaianKinerja::where('user_id', auth()->id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('periode', 'like', '%' . $this->search . '%');
            });
        }

        $datas = $query->latest()->paginate(10);

        return view('livewire.hasil-kinerja.index', compact('datas'));
    }
}
