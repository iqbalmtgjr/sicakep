<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Target Kinerja Pegawai
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
                                        <select class="form-select form-select-solid w-200px"
                                            wire:model.live="filterPeriode">
                                            <option value="">Semua Periode</option>
                                            @foreach ($periodes as $periode)
                                                <option value="{{ $periode->id }}">{{ $periode->nama_periode }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select class="form-select form-select-solid w-200px select2-pegawai"
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
                                            Tambah Target
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body py-4">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Pegawai</th>
                                                <th>Periode</th>
                                                <th>Indikator Kinerja</th>
                                                <th>Target</th>
                                                <th>Realisasi</th>
                                                <th>Capaian</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            @forelse($targets as $target)
                                                @php
                                                    $totalRealisasi = $target->getTotalRealisasi();
                                                    $persentase = $target->getPersentaseCapaian();
                                                    $badgeClass =
                                                        $persentase >= 100
                                                            ? 'success'
                                                            : ($persentase >= 75
                                                                ? 'warning'
                                                                : 'danger');
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="symbol symbol-circle symbol-35px overflow-hidden me-3">
                                                                <div class="symbol-label fs-6 bg-light-info text-info">
                                                                    {{ $target->user->initials() }}
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span
                                                                    class="text-gray-800 fw-bold">{{ $target->user->name }}</span>
                                                                <span
                                                                    class="text-muted fs-7">{{ $target->user->jabatan ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="text-gray-800 fw-bold">{{ $target->periode->nama_periode }}</span>
                                                            <span
                                                                class="text-muted fs-7">{{ $target->periode->jenis }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="text-gray-800">{{ Str::limit($target->indikatorKinerja->indikator_program, 50) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-info">{{ number_format($target->target, 2, ',', '.') }}
                                                            {{ $target->satuan ?? '' }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-{{ $badgeClass }}">{{ number_format($totalRealisasi, 2, ',', '.') }}
                                                            {{ $target->satuan }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress h-6px w-100px me-2">
                                                                <div class="progress-bar bg-{{ $badgeClass }}"
                                                                    role="progressbar"
                                                                    style="width: {{ min($persentase, 100) }}%"></div>
                                                            </div>
                                                            <span
                                                                class="badge badge-{{ $badgeClass }}">{{ number_format($persentase, 1) }}%</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="edit({{ $target->id }})" title="Edit">
                                                            <i class="ki-duotone ki-pencil fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="confirmDelete({{ $target->id }})"
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
                                                        target kinerja</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $targets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="target-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editing ? 'Edit Target Kinerja' : 'Tambah Target Kinerja' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
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
                            <div class="col-md-6 mb-3">
                                <label for="periode_id" class="form-label">Periode <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="periode_id" wire:model="periode_id" required>
                                    <option value="">Pilih Periode</option>
                                    @foreach ($periodes as $periode)
                                        <option value="{{ $periode->id }}">{{ $periode->nama_periode }}
                                            ({{ $periode->jenis }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('periode_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                {{-- {{ $indikator_kinerja_id }} --}}
                                <label for="indikator_kinerja_id" class="form-label">Indikator Kinerja <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" wire:model.live="indikator_kinerja_id"
                                    id="indikator_kinerja_id" required>
                                    <option value="">Pilih Indikator</option>
                                    @foreach ($indikators as $indikator)
                                        <option value="{{ $indikator->id }}">
                                            {{ $indikator->indikator_program }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (!$user_id)
                                    <small class="text-muted">Pilih pegawai terlebih dahulu</small>
                                @elseif(count($indikators) == 0)
                                    <small class="text-danger">Pegawai belum memiliki indikator kinerja</small>
                                @endif
                                @error('indikator_kinerja_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="target" class="form-label">Target <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="target" wire:model="target"
                                    placeholder="Masukkan nilai target, contoh: 100 atau 19,5" required>
                                @error('target')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="target" class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="satuan" wire:model="satuan"
                                    placeholder="Masukkan satuan target, contoh: unit" required>
                                @error('satuan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" wire:model="keterangan" rows="3"
                                    placeholder="Keterangan tambahan..."></textarea>
                                @error('keterangan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                    text: "Data target kinerja akan dihapus permanen!",
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
                $('#target-modal').modal('show');
            });

            Livewire.on('closeModal', () => {
                $('#target-modal').modal('hide');
            });

            // Force set indikator value saat edit
            Livewire.on('setIndikatorValue', (data) => {
                setTimeout(() => {
                    const indikatorSelect = document.getElementById('indikator_kinerja_id');
                    if (indikatorSelect && data.indikatorId) {
                        indikatorSelect.value = data.indikatorId;
                        // Trigger Livewire update
                        indikatorSelect.dispatchEvent(new Event('change'));
                    }

                    // Re-initialize Select2 for modal pegawai select
                    const pegawaiSelect = document.getElementById('user_id');
                    if (pegawaiSelect) {
                        $(pegawaiSelect).select2();
                    }
                }, 100);
            });

            // Initialize Select2 for pegawai elements
            function initializeSelect2() {
                $('.select2-pegawai').select2({
                    placeholder: 'Semua Pegawai',
                    allowClear: true,
                    width: '100%',
                    height: '100%'
                });

                $('.select2-modal-pegawai').select2({
                    placeholder: 'Pilih Pegawai',
                    allowClear: true,
                    width: '100%',
                    height: '100%'
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
                if (targetInput && targetInput.value.includes(',')) {
                    targetInput.value = targetInput.value.replace(',', '.');
                }
            });
        });
    </script>
</div>
