<?php

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
    public $sasaran_strategis;
    public $nama_indikator;
    public $sasaran_program;
    public $indikator_program;
    public $satuan;
    public $target;
    // public $bobot;
    public $deskripsi;
    public $is_active = true;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'kode_indikator' => 'required|string|max:50|unique:indikator_kinerja,kode_indikator',
        'sasaran_strategis' => 'required|string|max:255',
        'nama_indikator' => 'required|string',
        'sasaran_program' => 'required|string|max:255',
        'indikator_program' => 'required|string',
        'satuan' => 'required|string|max:50',
        'target' => 'required|numeric|min:0',
        // 'bobot' => 'required|numeric|min:0|max:100',
        'deskripsi' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'user_id.required' => 'Pegawai wajib dipilih.',
        'kode_indikator.required' => 'Kode indikator wajib diisi.',
        'kode_indikator.unique' => 'Kode indikator sudah digunakan.',
        'sasaran_strategis.required' => 'Sasaran strategis wajib diisi.',
        'nama_indikator.required' => 'Nama indikator wajib diisi.',
        'sasaran_program.required' => 'Sasaran program wajib diisi.',
        'indikator_program.required' => 'Indikator program wajib diisi.',
        'satuan.required' => 'Satuan wajib diisi.',
        'target.required' => 'Target wajib diisi.',
        // 'bobot.required' => 'Bobot wajib diisi.',
        // 'bobot.max' => 'Bobot maksimal 100.',
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
                $q->where('kode_indikator', 'like', '%' . $this->search . '%')->orWhere('nama_indikator', 'like', '%' . $this->search . '%')->orWhere('sasaran_strategis', 'like', '%' . $this->search . '%');
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
        $this->sasaran_strategis = $indikator->sasaran_strategis;
        $this->nama_indikator = $indikator->nama_indikator;
        $this->sasaran_program = $indikator->sasaran_program;
        $this->indikator_program = $indikator->indikator_program;
        $this->satuan = $indikator->satuan;
        $this->target = $indikator->target;
        // $this->bobot = $indikator->bobot;
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
            // if ($this->kode_indikator !== $indikator->kode_indikator && IndikatorKinerja::where('kode_indikator', $this->kode_indikator)->exists()) {
            //     $this->addError('kode_indikator', 'Kode indikator sudah digunakan.');
            //     return;
            // }

            $this->validate([
                'user_id' => 'required|exists:users,id',
                'kode_indikator' => 'required|string|max:50',
                'sasaran_strategis' => 'required|string|max:255',
                'nama_indikator' => 'required|string',
                'sasaran_program' => 'required|string|max:255',
                'indikator_program' => 'required|string',
                'satuan' => 'required|string|max:50',
                'target' => 'required|numeric|min:0',
                // 'bobot' => 'required|numeric|min:0|max:100',
                'deskripsi' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $indikator->update([
                'user_id' => $this->user_id,
                'kode_indikator' => $this->kode_indikator,
                'sasaran_strategis' => $this->sasaran_strategis,
                'nama_indikator' => $this->nama_indikator,
                'sasaran_program' => $this->sasaran_program,
                'indikator_program' => $this->indikator_program,
                'satuan' => $this->satuan,
                'target' => $this->target,
                // 'bobot' => $this->bobot,
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
                'sasaran_strategis' => $this->sasaran_strategis,
                'nama_indikator' => $this->nama_indikator,
                'sasaran_program' => $this->sasaran_program,
                'indikator_program' => $this->indikator_program,
                'satuan' => $this->satuan,
                'target' => $this->target,
                'deskripsi' => $this->deskripsi,
                'is_active' => $this->is_active,
                // 'bobot' => $this->bobot,
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
        $this->sasaran_strategis = '';
        $this->nama_indikator = '';
        $this->sasaran_program = '';
        $this->indikator_program = '';
        $this->satuan = '';
        $this->target = '';
        // $this->bobot = '';
        $this->deskripsi = '';
        $this->is_active = true;
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
