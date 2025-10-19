<?php

namespace App\Livewire\RealisasiKinerja;

use App\Models\RealisasiKinerja;
use App\Models\Periode;
use App\Models\Notifikasi;
use Livewire\Component;
use Livewire\WithPagination;

class Verifikasi extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPeriode = '';
    public $filterStatus = 'submitted';
    public $showModal = false;
    public $realisasiId;
    public $catatan_verifikasi = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPeriode()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = RealisasiKinerja::with([
            'targetKinerja.indikatorKinerja',
            'targetKinerja.periode',
            'user',
            'verifiedBy'
        ]);

        if ($this->filterPeriode) {
            $query->whereHas('targetKinerja', function ($q) {
                $q->where('periode_id', $this->filterPeriode);
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $realisasis = $query->latest()->paginate(10);
        $periodes = Periode::active()->orderBy('tahun', 'desc')->get();

        return view('livewire.realisasi-kinerja.verifikasi', compact('realisasis', 'periodes'));
    }

    public function showVerifyModal($id)
    {
        $this->realisasiId = $id;
        $this->catatan_verifikasi = '';
        $this->showModal = true;
        $this->dispatch('showModal');
    }

    public function verify()
    {
        $realisasi = RealisasiKinerja::findOrFail($this->realisasiId);

        $realisasi->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $this->catatan_verifikasi,
        ]);

        // Buat notifikasi untuk user yang mengajukan realisasi
        Notifikasi::create([
            'user_id' => $realisasi->user_id,
            'judul' => 'Realisasi Kinerja Diterima',
            'pesan' => json_encode([
                'status' => 'verified',
                'indikator' => $realisasi->targetKinerja->indikatorKinerja->nama_indikator,
                'realisasi' => $realisasi->realisasi,
                'tanggal' => $realisasi->tanggal_realisasi->format('d F Y'),
                'verifikator' => auth()->user()->name,
                'catatan' => $this->catatan_verifikasi ?: null,
            ]),
            'tipe' => 'verifikasi',
            'is_read' => false,
        ]);

        flash('Realisasi berhasil diverifikasi.', 'success', [], 'Berhasil');
        $this->closeModal();
    }

    public function reject()
    {
        if (empty($this->catatan_verifikasi)) {
            $this->addError('catatan_verifikasi', 'Catatan penolakan wajib diisi.');
            return;
        }

        $realisasi = RealisasiKinerja::findOrFail($this->realisasiId);

        $realisasi->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $this->catatan_verifikasi,
        ]);

        // Buat notifikasi untuk user yang mengajukan realisasi
        Notifikasi::create([
            'user_id' => $realisasi->user_id,
            'judul' => 'Realisasi Kinerja Ditolak',
            'pesan' => json_encode([
                'status' => 'rejected',
                'indikator' => $realisasi->targetKinerja->indikatorKinerja->nama_indikator,
                'realisasi' => $realisasi->realisasi,
                'tanggal' => $realisasi->tanggal_realisasi->format('d F Y'),
                'verifikator' => auth()->user()->name,
                'catatan' => $this->catatan_verifikasi,
            ]),
            'tipe' => 'verifikasi',
            'is_read' => false,
        ]);

        flash('Realisasi ditolak.', 'warning', [], 'Informasi');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->realisasiId = null;
        $this->catatan_verifikasi = '';
        $this->resetValidation();
        $this->dispatch('closeModal');
    }
}
