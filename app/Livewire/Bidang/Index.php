<?php
// File: app/Livewire/Bidang/Index.php

namespace App\Livewire\Bidang;

use App\Models\Bidang;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editing = false;
    public $bidangId;

    public $nama_bidang;
    public $kode_bidang;
    public $deskripsi;

    protected $rules = [
        'nama_bidang' => 'required|string|max:255',
        'kode_bidang' => 'required|string|max:50|unique:bidang,kode_bidang',
        'deskripsi' => 'nullable|string',
    ];

    protected $messages = [
        'nama_bidang.required' => 'Nama bidang wajib diisi.',
        'kode_bidang.required' => 'Kode bidang wajib diisi.',
        'kode_bidang.unique' => 'Kode bidang sudah digunakan.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $bidangs = Bidang::where('nama_bidang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_bidang', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.bidang.index', compact('bidangs'));
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
        $bidang = Bidang::findOrFail($id);
        $this->bidangId = $id;
        $this->nama_bidang = $bidang->nama_bidang;
        $this->kode_bidang = $bidang->kode_bidang;
        $this->deskripsi = $bidang->deskripsi;
        $this->showModal = true;
        $this->editing = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        if ($this->editing) {
            $bidang = Bidang::findOrFail($this->bidangId);

            // Check kode uniqueness excluding current bidang
            if ($this->kode_bidang !== $bidang->kode_bidang && Bidang::where('kode_bidang', $this->kode_bidang)->exists()) {
                $this->addError('kode_bidang', 'Kode bidang sudah digunakan.');
                return;
            }

            $this->validate([
                'nama_bidang' => 'required|string|max:255',
                'kode_bidang' => 'required|string|max:50',
                'deskripsi' => 'nullable|string',
            ]);

            $bidang->update([
                'nama_bidang' => $this->nama_bidang,
                'kode_bidang' => $this->kode_bidang,
                'deskripsi' => $this->deskripsi,
            ]);

            flash('Bidang berhasil diperbarui.', 'success', [], 'Berhasil');
        } else {
            if (Bidang::where('kode_bidang', $this->kode_bidang)->exists()) {
                $this->addError('kode_bidang', 'Kode bidang sudah digunakan.');
                return;
            }

            $this->validate();

            Bidang::create([
                'nama_bidang' => $this->nama_bidang,
                'kode_bidang' => $this->kode_bidang,
                'deskripsi' => $this->deskripsi,
            ]);

            flash('Bidang berhasil ditambahkan.', 'success', [], 'Berhasil');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->bidangId = $id;
        $this->dispatch('confirm-delete', bidangId: $id);
    }

    public function delete()
    {
        $bidang = Bidang::findOrFail($this->bidangId);

        // Check if bidang has users
        if ($bidang->users()->count() > 0) {
            flash('Bidang tidak dapat dihapus karena masih memiliki pegawai.', 'error', [], 'Gagal');
            return;
        }

        $bidang->delete();
        flash('Bidang berhasil dihapus.', 'success', [], 'Berhasil');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->bidangId = null;
        $this->nama_bidang = '';
        $this->kode_bidang = '';
        $this->deskripsi = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
