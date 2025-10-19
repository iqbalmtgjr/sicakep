<?php

namespace App\Livewire\Notifikasi\Penilaian;

use App\Models\Notifikasi;
use App\Models\PenilaianKategori;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $notifikasiPenilaian = null;

    protected $rules = [];

    public function mount()
    {
        $this->checkNotifikasiPenilaian();
    }

    public function checkNotifikasiPenilaian()
    {
        // Ambil notifikasi penilaian yang belum dibaca (tipe = 'penilaian')
        $this->notifikasiPenilaian = Notifikasi::where('user_id', auth()->id())
            ->where('tipe', 'penilaian')
            ->unread()
            ->latest()
            ->first();
    }

    public function dismissNotifikasi($notifikasiId)
    {
        $notifikasi = Notifikasi::find($notifikasiId);

        if ($notifikasi && $notifikasi->user_id === auth()->id()) {
            $notifikasi->markAsRead();
            $this->notifikasiPenilaian = null;
            flash('Notifikasi telah ditandai sebagai dibaca.', 'success', [], 'Berhasil');
        }
    }

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
        $query = Notifikasi::where('user_id', auth()->id())
            ->where('tipe', 'penilaian')
            ->with(['user']);

        if ($this->filterStatus) {
            if ($this->filterStatus === 'read') {
                $query->where('is_read', true);
            } elseif ($this->filterStatus === 'unread') {
                $query->where('is_read', false);
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('pesan', 'like', '%' . $this->search . '%');
            });
        }

        $notifikasis = $query->latest()->paginate(10);

        return view('livewire.notifikasi.penilaian.index', compact('notifikasis'));
    }

    public function markAsRead($notifikasiId)
    {
        $notifikasi = Notifikasi::find($notifikasiId);

        if ($notifikasi && $notifikasi->user_id === auth()->id()) {
            $notifikasi->markAsRead();
            flash('Notifikasi telah ditandai sebagai dibaca.', 'success', [], 'Berhasil');
        }
    }

    public function markAsUnread($notifikasiId)
    {
        $notifikasi = Notifikasi::find($notifikasiId);

        if ($notifikasi && $notifikasi->user_id === auth()->id()) {
            $notifikasi->update(['is_read' => false, 'read_at' => null]);
            flash('Notifikasi telah ditandai sebagai belum dibaca.', 'success', [], 'Berhasil');
        }
    }
}
