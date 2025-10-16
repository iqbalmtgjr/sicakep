<?php
// File: app/Livewire/RealisasiKinerja/Index.php

namespace App\Livewire\RealisasiKinerja;

use App\Models\RealisasiKinerja;
use App\Models\TargetKinerja;
use App\Models\Periode;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterPeriode = '';
    public $filterStatus = '';
    public $showModal = false;
    public $editing = false;
    public $realisasiId;

    public $target_kinerja_id;
    public $tanggal_realisasi;
    public $realisasi;
    public $keterangan;
    public $bukti_file;
    public $status = 'draft';

    protected $rules = [
        'target_kinerja_id' => 'required|exists:target_kinerja,id',
        'tanggal_realisasi' => 'required|date',
        'realisasi' => 'required|numeric|min:0',
        'keterangan' => 'nullable|string',
        'bukti_file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png,doc,docx',
    ];

    protected $messages = [
        'target_kinerja_id.required' => 'Target kinerja wajib dipilih.',
        'tanggal_realisasi.required' => 'Tanggal realisasi wajib diisi.',
        'realisasi.required' => 'Nilai realisasi wajib diisi.',
        'bukti_file.max' => 'Ukuran file maksimal 2MB.',
        'bukti_file.mimes' => 'File harus berformat: pdf, jpg, jpeg, png, doc, docx.',
    ];

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
        $query = RealisasiKinerja::with(['targetKinerja.indikatorKinerja', 'targetKinerja.periode', 'user'])
            ->where('user_id', auth()->id());

        if ($this->filterPeriode) {
            $query->whereHas('targetKinerja', function ($q) {
                $q->where('periode_id', $this->filterPeriode);
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->search) {
            $query->whereHas('targetKinerja.indikatorKinerja', function ($q) {
                $q->where('nama_indikator', 'like', '%' . $this->search . '%');
            });
        }

        $realisasis = $query->latest()->paginate(10);
        $periodes = Periode::active()->orderBy('tahun', 'desc')->get();

        // Get available targets for logged in user
        $targets = TargetKinerja::with(['indikatorKinerja', 'periode'])
            ->where('user_id', auth()->id())
            ->whereHas('periode', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        return view('livewire.realisasi-kinerja.index', compact('realisasis', 'periodes', 'targets'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editing = false;
        $this->tanggal_realisasi = date('Y-m-d');
        $this->dispatch('showModal');
    }

    public function edit($id)
    {
        $realisasi = RealisasiKinerja::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Only allow edit if status is draft or rejected
        if (!in_array($realisasi->status, ['draft', 'rejected'])) {
            flash('Hanya realisasi dengan status Draft atau Ditolak yang dapat diedit.', 'error', [], 'Gagal');
            return;
        }

        $this->realisasiId = $id;
        $this->target_kinerja_id = $realisasi->target_kinerja_id;
        $this->tanggal_realisasi = $realisasi->tanggal_realisasi->format('Y-m-d');
        $this->realisasi = $realisasi->realisasi;
        $this->keterangan = $realisasi->keterangan;
        $this->status = $realisasi->status;
        $this->showModal = true;
        $this->editing = true;
        $this->dispatch('showModal');
    }

    public function save()
    {
        $this->validate();

        $data = [
            'target_kinerja_id' => $this->target_kinerja_id,
            'user_id' => auth()->id(),
            'tanggal_realisasi' => $this->tanggal_realisasi,
            'realisasi' => $this->realisasi,
            'keterangan' => $this->keterangan,
            'status' => $this->status,
        ];

        // Handle file upload
        if ($this->bukti_file) {
            $filename = time() . '_' . $this->bukti_file->getClientOriginalName();
            $path = $this->bukti_file->storeAs('bukti-realisasi', $filename, 'public');
            $data['bukti_file'] = $path;

            // Delete old file if editing
            if ($this->editing) {
                $oldRealisasi = RealisasiKinerja::find($this->realisasiId);
                if ($oldRealisasi && $oldRealisasi->bukti_file) {
                    \Storage::disk('public')->delete($oldRealisasi->bukti_file);
                }
            }
        }

        if ($this->editing) {
            $realisasi = RealisasiKinerja::where('id', $this->realisasiId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $realisasi->update($data);
            flash('Realisasi kinerja berhasil diperbarui.', 'success', [], 'Berhasil');
        } else {
            RealisasiKinerja::create($data);
            flash('Realisasi kinerja berhasil ditambahkan.', 'success', [], 'Berhasil');
        }

        $this->closeModal();
    }

    public function submit($id)
    {
        $realisasi = RealisasiKinerja::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();

        $realisasi->update(['status' => 'submitted']);
        flash('Realisasi berhasil diajukan untuk verifikasi.', 'success', [], 'Berhasil');
    }

    public function confirmDelete($id)
    {
        $this->realisasiId = $id;
        $this->dispatch('confirm-delete', realisasiId: $id);
    }

    public function delete()
    {
        $realisasi = RealisasiKinerja::where('id', $this->realisasiId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Only allow delete if status is draft or rejected
        if (!in_array($realisasi->status, ['draft', 'rejected'])) {
            flash('Hanya realisasi dengan status Draft atau Ditolak yang dapat dihapus.', 'error', [], 'Gagal');
            return;
        }

        // Delete file if exists
        if ($realisasi->bukti_file) {
            \Storage::disk('public')->delete($realisasi->bukti_file);
        }

        $realisasi->delete();
        flash('Realisasi kinerja berhasil dihapus.', 'success', [], 'Berhasil');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->realisasiId = null;
        $this->target_kinerja_id = '';
        $this->tanggal_realisasi = '';
        $this->realisasi = '';
        $this->keterangan = '';
        $this->bukti_file = null;
        $this->status = 'draft';
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
