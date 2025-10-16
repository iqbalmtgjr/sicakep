<?php
// File: app/Livewire/TargetKinerja/Index.php

namespace App\Livewire\TargetKinerja;

use App\Models\TargetKinerja;
use App\Models\User;
use App\Models\Periode;
use App\Models\IndikatorKinerja;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterUser = '';
    public $filterPeriode = '';
    public $showModal = false;
    public $editing = false;
    public $targetId;

    public $user_id;
    public $periode_id;
    public $indikator_kinerja_id;
    public $target;
    public $keterangan;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'periode_id' => 'required|exists:periode,id',
        'indikator_kinerja_id' => 'required|exists:indikator_kinerja,id',
        'target' => 'required|numeric|min:0',
        'keterangan' => 'nullable|string',
    ];

    protected $messages = [
        'user_id.required' => 'Pegawai wajib dipilih.',
        'periode_id.required' => 'Periode wajib dipilih.',
        'indikator_kinerja_id.required' => 'Indikator wajib dipilih.',
        'target.required' => 'Target wajib diisi.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterUser()
    {
        $this->resetPage();
    }

    public function updatingFilterPeriode()
    {
        $this->resetPage();
    }

    public function updatedUserId()
    {
        // Reset indikator when user changes
        $this->indikator_kinerja_id = '';
    }

    public function render()
    {
        $query = TargetKinerja::with(['user', 'periode', 'indikatorKinerja']);

        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        if ($this->filterPeriode) {
            $query->where('periode_id', $this->filterPeriode);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $targets = $query->latest()->paginate(10);
        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $periodes = Periode::active()->orderBy('tahun', 'desc')->get();

        // Get indikators based on selected user for modal
        $indikators = [];
        if ($this->user_id) {
            $indikators = IndikatorKinerja::where('user_id', $this->user_id)
                ->where('is_active', true)
                ->get();
        }

        return view('livewire.target-kinerja.index', compact('targets', 'users', 'periodes', 'indikators'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editing = false;
        $this->dispatch('showModal');
    }

    public function edit($id)
    {
        $target = TargetKinerja::findOrFail($id);
        $this->targetId = $id;
        $this->user_id = $target->user_id;
        $this->periode_id = $target->periode_id;
        $this->indikator_kinerja_id = $target->indikator_kinerja_id;
        $this->target = $target->target;
        $this->keterangan = $target->keterangan;
        $this->showModal = true;
        $this->editing = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            $target = TargetKinerja::findOrFail($this->targetId);

            // Check if combination already exists (excluding current)
            $exists = TargetKinerja::where('user_id', $this->user_id)
                ->where('periode_id', $this->periode_id)
                ->where('indikator_kinerja_id', $this->indikator_kinerja_id)
                ->where('id', '!=', $this->targetId)
                ->exists();

            if ($exists) {
                flash('Target untuk pegawai, periode, dan indikator ini sudah ada.', 'error', [], 'Gagal');
                return;
            }

            $target->update([
                'user_id' => $this->user_id,
                'periode_id' => $this->periode_id,
                'indikator_kinerja_id' => $this->indikator_kinerja_id,
                'target' => $this->target,
                'keterangan' => $this->keterangan,
            ]);

            flash('Target kinerja berhasil diperbarui.', 'success', [], 'Berhasil');
        } else {
            // Check if combination already exists
            $exists = TargetKinerja::where('user_id', $this->user_id)
                ->where('periode_id', $this->periode_id)
                ->where('indikator_kinerja_id', $this->indikator_kinerja_id)
                ->exists();

            if ($exists) {
                flash('Target untuk pegawai, periode, dan indikator ini sudah ada.', 'error', [], 'Gagal');
                return;
            }

            TargetKinerja::create([
                'user_id' => $this->user_id,
                'periode_id' => $this->periode_id,
                'indikator_kinerja_id' => $this->indikator_kinerja_id,
                'target' => $this->target,
                'keterangan' => $this->keterangan,
            ]);

            flash('Target kinerja berhasil ditambahkan.', 'success', [], 'Berhasil');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->targetId = $id;
        $this->dispatch('confirm-delete', targetId: $id);
    }

    public function delete()
    {
        $target = TargetKinerja::findOrFail($this->targetId);

        // Check if target has realisasi
        if ($target->realisasiKinerja()->count() > 0) {
            flash('Target tidak dapat dihapus karena sudah memiliki realisasi.', 'error', [], 'Gagal');
            return;
        }

        $target->delete();
        flash('Target kinerja berhasil dihapus.', 'success', [], 'Berhasil');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->targetId = null;
        $this->user_id = '';
        $this->periode_id = '';
        $this->indikator_kinerja_id = '';
        $this->target = '';
        $this->keterangan = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
