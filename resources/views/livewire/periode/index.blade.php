<!-- File: resources/views/livewire/periode/index.blade.php -->
<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Periode Penilaian
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text" class="form-control form-control-solid w-250px ps-15"
                                            wire:model.live.debounce.300ms="search" placeholder="Cari periode...">
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" wire:click="create()">
                                            <i class="ki-duotone ki-plus fs-2"></i>
                                            Tambah Periode
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body py-4">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Nama Periode</th>
                                                <th>Tahun</th>
                                                <th>Jenis</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            @forelse($periodes as $periode)
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="text-gray-800 fw-bold">{{ $periode->nama_periode }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-primary">{{ $periode->tahun }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-info">{{ $periode->jenis }}</span>
                                                    </td>
                                                    <td>{{ $periode->tanggal_mulai->format('d/m/Y') }}</td>
                                                    <td>{{ $periode->tanggal_selesai->format('d/m/Y') }}</td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                wire:click="toggleStatus({{ $periode->id }})"
                                                                {{ $periode->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label">
                                                                <span
                                                                    class="badge badge-light-{{ $periode->is_active ? 'success' : 'danger' }}">
                                                                    {{ $periode->is_active ? 'Aktif' : 'Nonaktif' }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="edit({{ $periode->id }})" title="Edit">
                                                            <i class="ki-duotone ki-pencil fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="confirmDelete({{ $periode->id }})"
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
                                                    <td colspan="7" class="text-center text-muted">Tidak ada data
                                                        periode
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{ $periodes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="periode-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editing ? 'Edit Periode' : 'Tambah Periode' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_periode" class="form-label">Nama Periode <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_periode" wire:model="nama_periode"
                                    placeholder="Contoh: Januari 2024" required>
                                @error('nama_periode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun" class="form-label">Tahun <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tahun" wire:model="tahun"
                                    min="2020" max="2100" required>
                                @error('tahun')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="jenis" class="form-label">Jenis Periode <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="jenis" wire:model="jenis" required>
                                    <option value="Bulanan">Bulanan</option>
                                    <option value="Triwulan">Triwulan</option>
                                    <option value="Semester">Semester</option>
                                    <option value="Tahunan">Tahunan</option>
                                </select>
                                @error('jenis')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_mulai"
                                    wire:model="tanggal_mulai" required>
                                @error('tanggal_mulai')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_selesai"
                                    wire:model="tanggal_selesai" required>
                                @error('tanggal_selesai')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active"
                                        wire:model="is_active">
                                    <label class="form-check-label" for="is_active">
                                        Aktifkan Periode
                                    </label>
                                </div>
                            </div>
                        </div>
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

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirm-delete', (data) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data periode akan dihapus permanen!",
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

            Livewire.on('showModal', () => {
                $('#periode-modal').modal('show');
            });

            Livewire.on('closeModal', () => {
                $('#periode-modal').modal('hide');
            });
        });
    </script>
</div>
