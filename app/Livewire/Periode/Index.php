<?php
// File: app/Livewire/Periode/Index.php

namespace App\Livewire\Periode;

use App\Models\Periode;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editing = false;
    public $periodeId;

    public $nama_periode;
    public $tahun;
    public $jenis;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $is_active = true;

    protected $rules = [
        'nama_periode' => 'required|string|max:255',
        'tahun' => 'required|integer|min:2020|max:2100',
        'jenis' => 'required|in:Bulanan,Triwulan,Semester,Tahunan',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'nama_periode.required' => 'Nama periode wajib diisi.',
        'tahun.required' => 'Tahun wajib diisi.',
        'jenis.required' => 'Jenis periode wajib dipilih.',
        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
        'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $periodes = Periode::where('nama_periode', 'like', '%' . $this->search . '%')
            ->orWhere('tahun', 'like', '%' . $this->search . '%')
            ->orderBy('tahun', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(10);

        return view('livewire.periode.index', compact('periodes'));
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
        $periode = Periode::findOrFail($id);
        $this->periodeId = $id;
        $this->nama_periode = $periode->nama_periode;
        $this->tahun = $periode->tahun;
        $this->jenis = $periode->jenis;
        $this->tanggal_mulai = $periode->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $periode->tanggal_selesai->format('Y-m-d');
        $this->is_active = $periode->is_active;
        $this->showModal = true;
        $this->editing = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            $periode = Periode::findOrFail($this->periodeId);

            $periode->update([
                'nama_periode' => $this->nama_periode,
                'tahun' => $this->tahun,
                'jenis' => $this->jenis,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'is_active' => $this->is_active,
            ]);

            flash('Periode berhasil diperbarui.', 'success', [], 'Berhasil');
        } else {
            Periode::create([
                'nama_periode' => $this->nama_periode,
                'tahun' => $this->tahun,
                'jenis' => $this->jenis,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'is_active' => $this->is_active,
            ]);

            flash('Periode berhasil ditambahkan.', 'success', [], 'Berhasil');
        }

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $periode = Periode::findOrFail($id);
        $periode->update(['is_active' => !$periode->is_active]);

        $status = $periode->is_active ? 'diaktifkan' : 'dinonaktifkan';
        flash("Periode berhasil {$status}.", 'success', [], 'Berhasil');
    }

    public function confirmDelete($id)
    {
        $this->periodeId = $id;
        $this->dispatch('confirm-delete', periodeId: $id);
    }

    public function delete()
    {
        $periode = Periode::findOrFail($this->periodeId);

        // Check if periode has target kinerja
        if ($periode->targetKinerja()->count() > 0) {
            flash('Periode tidak dapat dihapus karena sudah memiliki target kinerja.', 'error', [], 'Gagal');
            return;
        }

        $periode->delete();
        flash('Periode berhasil dihapus.', 'success', [], 'Berhasil');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->periodeId = null;
        $this->nama_periode = '';
        $this->tahun = date('Y');
        $this->jenis = 'Bulanan';
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->is_active = true;
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
