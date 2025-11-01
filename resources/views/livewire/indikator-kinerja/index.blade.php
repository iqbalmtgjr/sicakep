<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Indikator Kinerja Pegawai
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
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <input type="text" class="form-control form-control-solid w-250px ps-15"
                                                wire:model.live.debounce.300ms="search" placeholder="Cari indikator...">
                                        </div>
                                        <select class="form-select form-select-solid w-200px"
                                            wire:model.live="filterUser">
                                            <option value="">Semua Pegawai</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" wire:click="create()">
                                            <i class="ki-duotone ki-plus fs-2"></i>
                                            Tambah Indikator
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body py-4">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th>Sasaran Strategis</th>
                                            <th>Nama Indikator</th>
                                            <th>Pegawai</th>
                                            <th>Target</th>
                                            {{-- <th>Bobot</th> --}}
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        @forelse($indikators as $indikator)
                                            <tr>
                                                <td>
                                                    <span
                                                        class="text-gray-800 fw-bold">{{ $indikator->sasaranStrategis->sasaran_strategis ?? '-'}}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="text-gray-800 fw-bold mb-1">{{ $indikator->nama_indikator }}</span>
                                                        @if ($indikator->deskripsi)
                                                            <span
                                                                class="text-muted fs-7">{{ Str::limit($indikator->deskripsi, 50) }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="symbol symbol-circle symbol-35px overflow-hidden me-3">
                                                            <div class="symbol-label fs-6 bg-light-info text-info">
                                                                {{ $indikator->user->initials() }}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="text-gray-800 mb-1">{{ $indikator->user->name }}</span>
                                                            <span
                                                                class="text-muted fs-7">{{ $indikator->user->jabatan ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-light-success">{{ number_format($indikator->target, 2, ',', '.') }}
                                                        {{ $indikator->satuan }}</span>
                                                </td>
                                                {{-- <td>
                                                    <span
                                                        class="badge badge-light-warning">{{ $indikator->bobot }}%</span>
                                                </td> --}}
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:click="toggleStatus({{ $indikator->id }})"
                                                            {{ $indikator->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            <span
                                                                class="badge badge-light-{{ $indikator->is_active ? 'success' : 'danger' }}">
                                                                {{ $indikator->is_active ? 'Aktif' : 'Nonaktif' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                        wire:click="edit({{ $indikator->id }})" title="Edit">
                                                        <i class="ki-duotone ki-pencil fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                        wire:click="confirmDelete({{ $indikator->id }})"
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
                                                    indikator kinerja</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $indikators->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="indikator-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editing ? 'Edit Indikator Kinerja' : 'Tambah Indikator Kinerja' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="user_id" class="form-label">Sasaran Strategis <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="user_id" wire:model="sasaran_strategis" required>
                                    <option value="">Pilih Sasaran Strategis</option>
                                    @foreach ($sasaran as $item)
                                        <option value="{{ $item->id }}">{{ $item->sasaran_strategis }} -
                                            {{ $item->target }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                            </div>
                            <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="user_id" class="form-label">Pegawai <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="user_id" wire:model.live="user_id" required>
                                    <option value="">Pilih Pegawai</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} -
                                            {{ $user->jabatan }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="sasaran_program" class="form-label">Sasaran Program/Kegiatan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sasaran_program"
                                    wire:model="sasaran_program"
                                    placeholder="Contoh: Meningkatkan produktivitas kerja" required>
                                @error('sasaran_program')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="indikator_program" class="form-label">Indikator Program/Kegiatan <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="indikator_program" wire:model="indikator_program" rows="2"
                                    placeholder="Contoh: Jumlah laporan yang diselesaikan" required></textarea>
                                @error('indikator_program')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" wire:model="deskripsi" rows="3"
                                    placeholder="Keterangan tambahan..."></textarea>
                                @error('deskripsi')
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
                                        Aktifkan Indikator
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
    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirm-delete', (data) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data indikator kinerja akan dihapus permanen!",
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
                $('#indikator-modal').modal('show');
            });

            Livewire.on('closeModal', () => {
                $('#indikator-modal').modal('hide');
            });

            // Function to convert comma to dot for decimal inputs
            function convertCommaToDot(event) {
                const input = event.target;
                const value = input.value;
                if (value.includes(',')) {
                    input.value = value.replace(',', '.');
                }
            }

            // Add event listeners for decimal inputs
            document.addEventListener('input', function(event) {
                if (event.target.id === 'target') {
                    convertCommaToDot(event);
                }
            });

            // Also handle on form submit
            document.addEventListener('submit', function(event) {
                const targetInput = document.getElementById('target');
                // const bobotInput = document.getElementById('bobot');

                if (targetInput && targetInput.value.includes(',')) {
                    targetInput.value = targetInput.value.replace(',', '.');
                }
                // if (bobotInput && bobotInput.value.includes(',')) {
                //     bobotInput.value = bobotInput.value.replace(',', '.');
                // }
            });

            // Initialize Select2 only for pegawai filter
            function initializeSelect2() {
                $('.select2-pegawai').select2({
                    placeholder: 'Semua Pegawai',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Initialize on page load
            initializeSelect2();

            // Re-initialize after Livewire updates
            Livewire.on('showModal', () => {
                setTimeout(() => {
                    initializeSelect2();
                }, 100);
            });
        });
    </script>
</div>
