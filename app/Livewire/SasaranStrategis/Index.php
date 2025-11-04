<?php

namespace App\Livewire\SasaranStrategis;

use App\Models\SasaranStrategis;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editing = false;
    public $sasaranStrategisId;

    public $sasaran_strategis;
    public $iku;
    public $target;

    protected $rules = [
        'sasaran_strategis' => 'required|string|max:255',
        'iku' => 'required|string|max:255',
        'target' => 'required|string|max:255',
    ];

    protected $messages = [
        'sasaran_strategis.required' => 'Sasaran Strategis wajib diisi.',
        'iku.required' => 'IKU wajib diisi.',
        'target.required' => 'Target wajib diisi.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SasaranStrategis::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('sasaran_strategis', 'like', '%' . $this->search . '%')
                    ->orWhere('iku', 'like', '%' . $this->search . '%')
                    ->orWhere('target', 'like', '%' . $this->search . '%');
            });
        }

        $sasaranStrategis = $query->paginate(10);

        return view('livewire.sasaran-strategis.index', compact('sasaranStrategis'));
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
        $sasaran = SasaranStrategis::findOrFail($id);
        $this->sasaranStrategisId = $id;
        $this->sasaran_strategis = $sasaran->sasaran_strategis;
        $this->iku = $sasaran->iku;
        $this->target = $sasaran->target;
        $this->showModal = true;
        $this->editing = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            $sasaran = SasaranStrategis::findOrFail($this->sasaranStrategisId);
            $sasaran->update([
                'sasaran_strategis' => $this->sasaran_strategis,
                'iku' => $this->iku,
                'target' => $this->target,
            ]);
            flash('Sasaran Strategis berhasil diperbarui.', 'success', [], 'Berhasil');
        } else {
            SasaranStrategis::create([
                'sasaran_strategis' => $this->sasaran_strategis,
                'iku' => $this->iku,
                'target' => $this->target,
            ]);
            flash('Sasaran Strategis berhasil ditambahkan.', 'success', [], 'Berhasil');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->sasaranStrategisId = $id;
        $this->dispatch('confirm-delete', sasaranStrategisId: $id);
    }

    public function delete()
    {
        $sasaran = SasaranStrategis::findOrFail($this->sasaranStrategisId);
        $sasaran->delete();
        flash('Sasaran Strategis berhasil dihapus.', 'success', [], 'Berhasil');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->sasaranStrategisId = null;
        $this->sasaran_strategis = '';
        $this->iku = '';
        $this->target = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
