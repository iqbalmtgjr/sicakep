<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Daftar Pegawai
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0 pt-6">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <!--begin::Search-->
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                class="path1"></span><span class="path2"></span></i>
                                        <input data-kt-docs-table-filter="search" type="text"
                                            class="form-control form-control-solid w-250px ps-15"
                                            wire:model.live.debounce.300ms="search" placeholder="Cari ">
                                        @error('search')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!--end::Search-->
                                </div>
                                <!--begin::Card title-->
                                <!--begin::Card toolbar-->
                                {{-- copas sementara --}}
                                <div class="card-toolbar">
                                    <!--begin::Toolbar-->
                                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                        <!--begin::Add user-->
                                        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_user">
                                            <i class="ki-outline ki-plus fs-2"></i>Tambah Pengguna</button> --}}
                                        <button class="btn btn-primary ms-3" wire:click="create()">
                                            <i class="ki-duotone ki-plus fs-2"></i>
                                            Tambah Pegawai
                                        </button>
                                        <!--end::Add user-->
                                    </div>
                                    <!--end::Toolbar-->
                                    <!--begin::Group actions-->
                                    <div class="d-flex justify-content-end align-items-center d-none"
                                        data-kt-docs-table-toolbar="selected">
                                        <div class="fw-bold me-5">
                                            <span class="me-2" data-kt-docs-table-select="selected_count"></span>
                                            Selected
                                        </div>

                                        <button type="button" class="btn btn-danger" data-bs-toggle="tooltip"
                                            title="Coming Soon">
                                            Selection Action
                                        </button>
                                    </div>
                                    <!--end::Group actions-->
                                </div>
                                <!--end::Toolbar-->

                            </div>
                            <!--begin::Card body-->
                            <div class="card-body py-4">
                                <!--begin::Wrapper-->
                                <!--begin::Datatable-->
                                <div class="table-responsive">
                                    <table id="kt_datatable_example_1"
                                        class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Level Jabatan</th> <!-- TAMBAHAN -->
                                                <th>Atasan Langsung</th> <!-- TAMBAHAN -->
                                                <th>Pangkat/Golongan</th>
                                                <th>Jabatan</th>
                                                <th>Bidang</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            @forelse($users as $index => $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                                <div
                                                                    class="symbol-label fs-3 bg-light-danger text-danger">
                                                                    {{ $user->initials() }}
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a href="#"
                                                                    class="text-gray-800 text-hover-primary mb-1">{{ $user->name }}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->nip }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @php
                                                            $roleColors = [
                                                                'admin' => 'danger',
                                                                'atasan' => 'warning',
                                                                'pegawai' => 'info',
                                                            ];
                                                        @endphp
                                                        <span
                                                            class="badge badge-light-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                                            {{ ucfirst($user->role) }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($user->level_jabatan)
                                                            <span class="badge badge-light-primary">
                                                                {{ ucfirst(str_replace('_', ' ', $user->level_jabatan)) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($user->atasan)
                                                            <div class="d-flex align-items-center">
                                                                <div
                                                                    class="symbol symbol-circle symbol-25px overflow-hidden me-2">
                                                                    <div
                                                                        class="symbol-label fs-8 bg-light-success text-success">
                                                                        {{ $user->atasan->initials() }}
                                                                    </div>
                                                                </div>
                                                                <span>{{ $user->atasan->name }}</span>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->pangkat_golongan ?? '-' }}</td>
                                                    <td>{{ $user->jabatan ?? '-' }}</td>
                                                    <td>{{ $user->bidang->nama_bidang ?? '-' }}</td>
                                                    <td class="text-end">
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="edit({{ $user->id }})" title="Edit">
                                                            <i class="ki-duotone ki-pencil fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="confirmDelete({{ $user->id }})"
                                                            title="Hapus">
                                                            <i class="ki-duotone ki-trash fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                                <span class="path4"></span>
                                                                <span class="path5"></span>
                                                            </i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">Tidak ada data
                                                        pengguna</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{ $users->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="user-modal" tabindex="-1" aria-labelledby="user-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="user-modal-label">
                        {{ $editing ? 'Edit Pegawai' : 'Tambah Pegawai' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" wire:model="name"
                                    required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" wire:model="nip" required>
                                @error('nip')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" wire:model="email"
                                    required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" wire:model="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="atasan">Atasan</option>
                                    <option value="pegawai">Pegawai</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="level_jabatan" class="form-label">Level Jabatan</label>
                                    <select class="form-select" id="level_jabatan" wire:model="level_jabatan">
                                        <option value="">Pilih Level Jabatan</option>
                                        <option value="kepala_dinas">Kepala Dinas</option>
                                        <option value="sekretaris">Sekretaris</option>
                                        <option value="kasubbag">Kasubbag</option>
                                        <option value="kabid">Kepala Bidang</option>
                                        <option value="kepala_upt">Kepala UPT</option>
                                        <option value="kasubag_upt">Kasubbag UPT</option>
                                        <option value="staff">Staff/Pelaksana</option>
                                    </select>
                                    @error('level_jabatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="atasan_id" class="form-label">Atasan Langsung</label>
                                    <select class="form-select" id="atasan_id" wire:model="atasan_id">
                                        <option value="">Pilih Atasan</option>
                                        @foreach ($atasans as $atasan)
                                            <option value="{{ $atasan->id }}">
                                                {{ $atasan->name }} - {{ $atasan->jabatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('atasan_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pangkat_golongan" class="form-label">Pangkat/Golongan</label>
                                <input type="text" class="form-control" id="pangkat_golongan"
                                    wire:model="pangkat_golongan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" wire:model="jabatan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bidang_id" class="form-label">Bidang</label>
                                <select class="form-select" id="bidang_id" wire:model="bidang_id">
                                    <option value="">Pilih Bidang</option>
                                    @foreach ($bidangs as $bidang)
                                        <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
                                    @endforeach
                                </select>
                                @error('bidang_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if (!$editing)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" wire:model="password"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        wire:model="password_confirmation" required>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password Baru (Opsional)</label>
                                    <input type="password" class="form-control" id="password"
                                        wire:model="password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password
                                        Baru</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        wire:model="password_confirmation">
                                </div>
                            </div>
                        @endif
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="closeModal">Batal</button>
                        <button type="submit"
                            class="btn btn-primary">{{ $editing ? 'Perbarui' : 'Simpan' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirm-delete', (data) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pengguna akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete();
                    }
                });
            });

            // Modal show/hide listeners
            Livewire.on('showModal', () => {
                $('#user-modal').modal('show');
            });

            Livewire.on('closeModal', () => {
                $('#user-modal').modal('hide');
            });
        });
    </script>
</div>
