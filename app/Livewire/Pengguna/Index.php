<?php

namespace App\Livewire\Pengguna;

use App\Models\Bidang;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $bidang_filter = '';
    public $showModal = false;
    public $editing = false;
    public $userId;

    public $name;
    public $nip;
    public $email;
    public $role;
    public $roles = [];       // TAMBAHAN: multiple roles
    public $pangkat_golongan;
    public $jabatan;
    public $password;
    public $password_confirmation;
    public $bidang_id;
    public $bidang_ids = [];  // TAMBAHAN: multiple bidang
    public $jabatan_ids = []; // TAMBAHAN: multiple jabatan
    public $atasan_id;        // TAMBAHAN
    public $level_jabatan;    // TAMBAHAN

    protected $rules = [
        'name' => 'required|string|max:255',
        'nip' => 'required|string|max:255|unique:users,nip',
        'email' => 'required|string|email|max:255|unique:users,email',
        'role' => 'required|string|in:admin,pegawai,atasan',
        'roles' => 'nullable|array',
        'roles.*' => 'in:admin,pegawai,atasan',
        'pangkat_golongan' => 'nullable|string|max:255',
        'jabatan' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'bidang_id' => 'nullable|exists:bidang,id',
        'bidang_ids' => 'nullable|array',
        'bidang_ids.*' => 'exists:bidang,id',
        'jabatan_ids' => 'nullable|array',
        'jabatan_ids.*' => 'exists:jabatan,id',
        'atasan_id' => 'nullable|exists:users,id',
        'level_jabatan' => 'nullable|string',
    ];

    protected $messages = [
        'nip.unique' => 'NIP sudah digunakan.',
        'email.unique' => 'Email sudah digunakan.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingBidangFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('bidang', 'bidangs', 'jabatans')
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nip', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        // Filter berdasarkan bidang utama atau bidang tambahan
        if ($this->bidang_filter) {
            $query->where(function ($q) {
                $q->where('bidang_id', $this->bidang_filter)
                    ->orWhereHas('bidangs', function ($subQ) {
                        $subQ->where('bidang_id', $this->bidang_filter);
                    });
            });
        }

        $users = $query->paginate(10);
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $jabatans = \App\Models\Jabatan::orderBy('nama_jabatan')->get();

        // Untuk dropdown atasan (hanya user dengan role atasan atau admin)
        $atasans = User::whereIn('role', ['atasan', 'admin'])
            ->orderBy('name')
            ->get();

        return view('livewire.pengguna.index', compact('users', 'bidangs', 'jabatans', 'atasans'));
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
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->nip = $user->nip;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->roles = $user->roles ?? [];             // TAMBAHAN
        $this->pangkat_golongan = $user->pangkat_golongan;
        $this->jabatan = $user->jabatan;
        $this->bidang_id = $user->bidang_id;
        $this->bidang_ids = $user->bidangs->pluck('id')->toArray(); // TAMBAHAN
        $this->jabatan_ids = $user->jabatans->pluck('id')->toArray(); // TAMBAHAN
        $this->atasan_id = $user->atasan_id;           // TAMBAHAN
        $this->level_jabatan = $user->level_jabatan;   // TAMBAHAN
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
        $this->editing = true;
        $this->dispatch('showModal');

        // Update validation rules for edit
        $this->rules['nip'] = 'required|string|max:255|unique:users,nip,' . $this->userId;
        $this->rules['email'] = 'required|string|email|max:255|unique:users,email,' . $this->userId;
        $this->rules['password'] = 'nullable|string|min:8|confirmed';
    }

    public function save()
    {
        // Manual validation
        $this->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:admin,pegawai,atasan',
            'roles' => 'nullable|array',
            'roles.*' => 'in:admin,pegawai,atasan',
            'pangkat_golongan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bidang_id' => 'nullable|exists:bidang,id',
            'atasan_id' => 'nullable|exists:users,id',
            'level_jabatan' => 'nullable|string',
        ]);

        if ($this->editing) {
            // Manual validation for edit
            $user = User::findOrFail($this->userId);

            // Check NIP uniqueness excluding current user
            if ($this->nip !== $user->nip && User::where('nip', $this->nip)->exists()) {
                $this->addError('nip', 'NIP sudah digunakan.');
                return;
            }

            // Check email uniqueness excluding current user
            if ($this->email !== $user->email && User::where('email', $this->email)->exists()) {
                $this->addError('email', 'Email sudah digunakan.');
                return;
            }

            // Validate password if provided
            if ($this->password) {
                $this->validate([
                    'password' => 'string|min:8|confirmed',
                ]);
            }

            $user->update([
                'name' => $this->name,
                'nip' => $this->nip,
                'email' => $this->email,
                'role' => $this->role,
                'roles' => $this->roles,                   // TAMBAHAN
                'pangkat_golongan' => $this->pangkat_golongan,
                'jabatan' => $this->jabatan,
                'bidang_id' => $this->bidang_id,
                'atasan_id' => $this->atasan_id,           // TAMBAHAN
                'level_jabatan' => $this->level_jabatan,   // TAMBAHAN
            ]);

            // Update many-to-many relationships
            $user->bidangs()->sync($this->bidang_ids);
            $user->jabatans()->sync($this->jabatan_ids);

            if ($this->password) {
                $user->update(['password' => bcrypt($this->password)]);
            }

            flash('Pengguna berhasil diperbarui.', 'success', [], 'Berhasil');
        } else {
            // Manual validation for create
            if (User::where('nip', $this->nip)->exists()) {
                $this->addError('nip', 'NIP sudah digunakan.');
                return;
            }

            if (User::where('email', $this->email)->exists()) {
                $this->addError('email', 'Email sudah digunakan.');
                return;
            }

            $this->validate([
                'nip' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $this->name,
                'nip' => $this->nip,
                'email' => $this->email,
                'role' => $this->role,
                'roles' => $this->roles,                   // TAMBAHAN
                'pangkat_golongan' => $this->pangkat_golongan,
                'jabatan' => $this->jabatan,
                'bidang_id' => $this->bidang_id,
                'atasan_id' => $this->atasan_id,           // TAMBAHAN
                'level_jabatan' => $this->level_jabatan,   // TAMBAHAN
                'password' => bcrypt($this->password),
            ]);

            // Attach many-to-many relationships
            $user->bidangs()->attach($this->bidang_ids);
            $user->jabatans()->attach($this->jabatan_ids);

            flash('Pengguna berhasil ditambahkan.', 'success', [], 'Berhasil');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->userId = $id;
        $this->dispatch('confirm-delete', userId: $id);
    }

    public function delete()
    {
        $user = User::findOrFail($this->userId);
        $user->delete();

        flash('Pengguna berhasil dihapus.', 'success', [], 'Berhasil');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->nip = '';
        $this->email = '';
        $this->role = 'pegawai';
        $this->roles = [];                 // TAMBAHAN
        $this->pangkat_golongan = '';
        $this->jabatan = '';
        $this->bidang_id = null;
        $this->bidang_ids = [];           // TAMBAHAN
        $this->jabatan_ids = [];          // TAMBAHAN
        $this->atasan_id = null;           // TAMBAHAN
        $this->level_jabatan = null;       // TAMBAHAN
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
