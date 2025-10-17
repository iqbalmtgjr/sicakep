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
    public $showModal = false;
    public $editing = false;
    public $userId;

    public $name;
    public $nip;
    public $email;
    public $role;
    public $pangkat_golongan;
    public $jabatan;
    public $password;
    public $password_confirmation;
    public $bidang_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'nip' => 'required|string|max:255|unique:users,nip',
        'email' => 'required|string|email|max:255|unique:users,email',
        'role' => 'required|string|in:admin,pegawai',
        'pangkat_golongan' => 'nullable|string|max:255',
        'jabatan' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'bidang_id' => 'nullable|exists:bidang,id',
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

    public function render()
    {
        $users = User::with('bidang')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nip', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('livewire.pengguna.index', compact('users', 'bidangs'));
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
        $this->pangkat_golongan = $user->pangkat_golongan;
        $this->jabatan = $user->jabatan;
        $this->bidang_id = $user->bidang_id;
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
            'pangkat_golongan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bidang_id' => 'nullable|exists:bidang,id',
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
                'pangkat_golongan' => $this->pangkat_golongan,
                'jabatan' => $this->jabatan,
                'bidang_id' => $this->bidang_id,
            ]);

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

            User::create([
                'name' => $this->name,
                'nip' => $this->nip,
                'email' => $this->email,
                'role' => $this->role,
                'pangkat_golongan' => $this->pangkat_golongan,
                'jabatan' => $this->jabatan,
                'bidang_id' => $this->bidang_id,
                'password' => bcrypt($this->password),
            ]);

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
        $this->pangkat_golongan = '';
        $this->jabatan = '';
        $this->bidang_id = null;
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }
}
