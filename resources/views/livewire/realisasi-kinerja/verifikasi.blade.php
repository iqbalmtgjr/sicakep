<!-- File: resources/views/livewire/realisasi-kinerja/verifikasi.blade.php -->
<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Verifikasi Realisasi Kinerja
                    </h1>
                    <span class="text-muted fs-6">Verifikasi realisasi kinerja pegawai</span>
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
                                                wire:model.live.debounce.300ms="search" placeholder="Cari pegawai...">
                                        </div>
                                        <select class="form-select form-select-solid w-200px"
                                            wire:model.live="filterPeriode">
                                            <option value="">Semua Periode</option>
                                            @foreach ($periodes as $periode)
                                                <option value="{{ $periode->id }}">{{ $periode->nama_periode }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select class="form-select form-select-solid w-150px"
                                            wire:model.live="filterStatus">
                                            <option value="">Semua Status</option>
                                            <option value="submitted">Diajukan</option>
                                            <option value="verified">Terverifikasi</option>
                                            <option value="rejected">Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body py-4">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Pegawai</th>
                                                <th>Tanggal</th>
                                                <th>Periode</th>
                                                <th>Indikator</th>
                                                <th>Target</th>
                                                <th>Realisasi</th>
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            @forelse($realisasis as $realisasi)
                                                @php
                                                    $target = $realisasi->targetKinerja;
                                                    $indikator = $target->indikatorKinerja;

                                                    $statusBadge = [
                                                        'submitted' => ['class' => 'warning', 'text' => 'Diajukan'],
                                                        'verified' => ['class' => 'success', 'text' => 'Terverifikasi'],
                                                        'rejected' => ['class' => 'danger', 'text' => 'Ditolak'],
                                                    ];

                                                    $badge = $statusBadge[$realisasi->status] ?? [
                                                        'class' => 'secondary',
                                                        'text' => $realisasi->status,
                                                    ];
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="symbol symbol-circle symbol-35px overflow-hidden me-3">
                                                                <div class="symbol-label fs-6 bg-light-info text-info">
                                                                    {{ $realisasi->user->initials() }}
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span
                                                                    class="text-gray-800 fw-bold">{{ $realisasi->user->name }}</span>
                                                                <span
                                                                    class="text-muted fs-7">{{ $realisasi->user->jabatan ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $realisasi->tanggal_realisasi->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-primary">{{ $target->periode->nama_periode }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="text-gray-800 fw-bold mb-1">{{ $indikator->kode_indikator }}</span>
                                                            <span
                                                                class="text-muted fs-7">{{ Str::limit($indikator->indikator_program, 40) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-info">{{ number_format($target->target, 2, ',', '.') }}
                                                            {{ $indikator->satuan }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-success">{{ number_format($realisasi->realisasi, 2, ',', '.') }}
                                                            {{ $indikator->satuan }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $badge['class'] }}">{{ $badge['text'] }}</span>
                                                        @if ($realisasi->verified_by)
                                                            <div class="text-muted fs-7 mt-1">
                                                                oleh: {{ $realisasi->verifiedBy->name }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($realisasi->keterangan)
                                                            <button type="button"
                                                                class="btn btn-sm btn-icon btn-text-info rounded-pill"
                                                                data-bs-toggle="tooltip"
                                                                title="{{ $realisasi->keterangan }}">
                                                                <i class="ki-duotone ki-notepad fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                    <span class="path4"></span>
                                                                    <span class="path5"></span>
                                                                </i>
                                                            </button>
                                                        @endif

                                                        @if ($realisasi->bukti_file)
                                                            <a href="{{ Storage::url($realisasi->bukti_file) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                                title="Lihat Bukti">
                                                                <i class="ki-duotone ki-file fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </a>
                                                        @endif

                                                        @if ($realisasi->status == 'submitted')
                                                            <button type="button"
                                                                class="btn btn-sm btn-icon btn-text-success rounded-pill"
                                                                wire:click="showVerifyModal({{ $realisasi->id }})"
                                                                title="Verifikasi">
                                                                <i class="ki-duotone ki-check-circle fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        <div class="py-10">
                                                            <i class="ki-duotone ki-file-deleted fs-5x text-muted mb-5">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <p class="fs-5 fw-bold">Tidak ada data realisasi</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $realisasis->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi -->
    <div wire:ignore.self class="modal fade" id="verify-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Realisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan_verifikasi" class="form-label">Catatan Verifikasi</label>
                        <textarea class="form-control" id="catatan_verifikasi" wire:model="catatan_verifikasi" rows="4"
                            placeholder="Tambahkan catatan (opsional untuk verifikasi, wajib untuk penolakan)"></textarea>
                        @error('catatan_verifikasi')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeModal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="reject">Tolak</button>
                    <button type="button" class="btn btn-success" wire:click="verify">Verifikasi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showModal', () => {
                $('#verify-modal').modal('show');
            });

            Livewire.on('closeModal', () => {
                $('#verify-modal').modal('hide');
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</div>
