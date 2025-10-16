<?php
// File: app/Livewire/IndikatorKinerja/Index.php

namespace App\Livewire\IndikatorKinerja;

use App\Models\IndikatorKinerja;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterUser = '';
    public $showModal = false;
    public $editing = false;
    public $indikatorId;

    public $user_id;
    public $kode_indikator;
    public $nama_indikator;
    public $satuan;
    public $target;
    public $bobot;
    public $deskripsi;
    public $is_active = true;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'kode_indikator' => 'required|string|max:50|unique:indikator_kinerja,kode_indikator',
        'nama_indikator' => 'required|string',
        'satuan' => 'required|string|max:50',
        'target' => 'required|numeric|min:0',
        'bobot' => 'required|integer|min:0|max:100',
        'deskripsi' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'user_id.required' => 'Pegawai wajib dipilih.',
        'kode_indikator.required' => 'Kode indikator wajib diisi.',
        'kode_indikator.unique' => 'Kode indikator sudah digunakan.',
        'nama_indikator.required' => 'Nama indikator wajib diisi.',
        'satuan.required' => 'Satuan wajib diisi.',
        'target.required' => 'Target wajib diisi.',
        'bobot.required' => 'Bobot wajib diisi.',
        'bobot.max' => 'Bobot maksimal 100.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterUser()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = IndikatorKinerja::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('kode_indikator', 'like', '%' . $this->search . '%')->orWhere('nama_indikator', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        $indikators = $query->latest()->paginate(10);
        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();

        return view('livewire.indikator-kinerja.index', compact('indikators', 'users'));
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
        $indikator = IndikatorKinerja::findOrFail($id);
        $this->indikatorId = $id;
        $this->user_id = $indikator->user_id;
        $this->kode_indikator = $indikator->kode_indikator;
        $this->nama_indikator = $indikator->nama_indikator;
        $this->satuan = $indikator->satuan;
        $this->target = $indikator->target;
        $this->bobot = $indikator->bobot;
        $this->deskripsi = $indikator->deskripsi;
        $this->is_active = $indikator->is_active;
        $this->showModal = true;
        $this->editing = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        if ($this->editing) {
            $indikator = IndikatorKinerja::findOrFail($this->indikatorId);

            // Check kode uniqueness excluding current indikator
            if ($this->kode_indikator !== $indikator->kode_indikator && IndikatorKinerja::where('kode_indikator', $this->kode_indikator)->exists()) {
                $this->addError('kode_indikator', 'Kode indikator sudah digunakan.');
                return;
            }

            $this->validate([
                'user_id' => 'required|exists:users,id',
                'kode_indikator' => 'required|string|max:50',
                'nama_indikator' => 'required|string',
                'satuan' => 'required|string|max:50',
                'target' => 'required|numeric|min:0',
                'bobot' => 'required|integer|min:0|max:100',
                'deskripsi' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $indikator->update([
                'user_id' => $this->user_id,
                'kode_indikator' => $this->kode_indikator,
                'nama_indikator' => $this->nama_indikator,
                'satuan' => $this->satuan,
                'target' => $this->target,
                'bobot' => $this->bobot,
                'deskripsi' => $this->deskripsi,
                'is_active' => $this->is_active,
            ]);

            flash('Indikator kinerja berhasil diperbarui.', 'success', [], 'Berhasil');
        } else {
            if (IndikatorKinerja::where('kode_indikator', $this->kode_indikator)->exists()) {
                $this->addError('kode_indikator', 'Kode indikator sudah digunakan.');
                return;
            }

            $this->validate();

            IndikatorKinerja::create([
                'user_id' => $this->user_id,
                'kode_indikator' => $this->kode_indikator,
                'nama_indikator' => $this->nama_indikator,
                'satuan' => $this->satuan,
                'target' => $this->target,
                'bobot' => $this->bobot,
                'deskripsi' => $this->deskripsi,
                'is_active' => $this->is_active,
            ]);

            flash('Indikator kinerja berhasil ditambahkan.', 'success', [], 'Berhasil');
        }

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $indikator = IndikatorKinerja::findOrFail($id);
        $indikator->update(['is_active' => !$indikator->is_active]);

        $status = $indikator->is_active ? 'diaktifkan' : 'dinonaktifkan';
        flash("Indikator kinerja berhasil {$status}.", 'success', [], 'Berhasil');
    }

    public function confirmDelete($id)
    {
        $this->indikatorId = $id;
        $this->dispatch('confirm-delete', indikatorId: $id);
    }

    public function delete()
    {
        $indikator = IndikatorKinerja::findOrFail($this->indikatorId);

        // Check if indikator has target kinerja
        if ($indikator->targetKinerja()->count() > 0) {
            flash('Indikator tidak dapat dihapus karena sudah memiliki target kinerja.', 'error', [], 'Gagal');
            return;
        }

        $indikator->delete();
        flash('Indikator kinerja berhasil dihapus.', 'success', [], 'Berhasil');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->indikatorId = null;
        $this->user_id = '';
        $this->kode_indikator = '';
        $this->nama_indikator = '';
        $this->satuan = '';
        $this->target = '';
        $this->bobot = '';
        $this->deskripsi = '';
        $this->is_active = true;
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
