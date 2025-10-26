<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Laporan Kinerja Berdasarkan Hierarki Organisasi
                    </h1>
                    <span class="text-muted fs-6">Visualisasi kinerja berdasarkan struktur organisasi</span>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid mb-10">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!-- Filter Periode -->
            <div class="card mb-5">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pilih Periode:</label>
                            <select class="form-select" wire:model.live="periode_id">
                                <option value="">Pilih Periode</option>
                                @foreach ($periodes as $periode)
                                    <option value="{{ $periode->id }}">
                                        {{ $periode->nama_periode }} - {{ $periode->tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <span class="badge badge-light-success p-3">
                                    <i class="ki-duotone ki-check-circle fs-2 text-success me-1"></i>
                                    ≥ 90: Sangat Baik
                                </span>
                                <span class="badge badge-light-info p-3">
                                    <i class="ki-duotone ki-information-5 fs-2 text-info me-1"></i>
                                    76-89: Baik
                                </span>
                                <span class="badge badge-light-warning p-3">
                                    <i class="ki-duotone ki-information-5 fs-2 text-warning me-1"></i>
                                    61-75: Cukup
                                </span>
                                <span class="badge badge-light-danger p-3">
                                    <i class="ki-duotone ki-cross-circle fs-2 text-danger me-1"></i>
                                    &lt; 61: Kurang
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($rootUser && $periode_id)
                <!-- Struktur Organisasi -->
                <div class="card">
                    <div class="card-body">
                        <div class="hierarchy-container">
                            <!-- Level 1: Root User (Kepala Dinas atau Atasan yang Login) -->
                            <div class="text-center mb-10">
                                @php
                                    $penilaianRoot = $penilaians[$rootUser->id] ?? null;
                                    $badgeRoot = $this->getBadgeClass($penilaianRoot?->nilai_kinerja);
                                @endphp
                                <div class="card border border-{{ $badgeRoot }} shadow-sm d-inline-block"
                                    style="min-width: 300px; cursor: pointer;"
                                    wire:click="showUserDetail({{ $rootUser->id }})">
                                    <div class="card-body p-5">
                                        <div class="symbol symbol-circle symbol-75px mb-3 mx-auto">
                                            <div class="symbol-label fs-2 bg-light-primary text-primary">
                                                {{ $rootUser->initials() }}
                                            </div>
                                        </div>
                                        <h4 class="fw-bold mb-1">{{ $rootUser->name }}</h4>
                                        <div class="text-muted fs-7 mb-2">{{ $rootUser->jabatan }}</div>
                                        @if ($penilaianRoot)
                                            <div class="mt-3">
                                                <span class="badge badge-{{ $badgeRoot }} fs-5">
                                                    Nilai: {{ number_format($penilaianRoot->nilai_kinerja, 2) }}
                                                </span>
                                                <div class="text-muted fs-8 mt-1">{{ $penilaianRoot->predikat }}</div>
                                            </div>
                                        @else
                                            <span class="badge badge-light-secondary">Belum Dinilai</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Garis ke bawah -->
                                <div class="hierarchy-line-vertical"
                                    style="height: 50px; width: 2px; background: #e1e3ea; margin: 0 auto;"></div>
                            </div>

                            <!-- Level 2: Bawahan dari Root User -->
                            <div class="row justify-content-center g-5 mb-10">
                                @foreach ($rootUser->bawahan as $level2)
                                    <div class="col-md-4">
                                        @php
                                            $penilaianL2 = $penilaians[$level2->id] ?? null;
                                            $badgeL2 = $this->getBadgeClass($penilaianL2?->nilai_kinerja);
                                        @endphp
                                        <div class="card border border-{{ $badgeL2 }} shadow-sm h-100"
                                            style="cursor: pointer;" wire:click="showUserDetail({{ $level2->id }})">
                                            <div class="card-body p-4 text-center">
                                                <div class="symbol symbol-circle symbol-50px mb-3 mx-auto">
                                                    <div class="symbol-label fs-3 bg-light-info text-info">
                                                        {{ $level2->initials() }}
                                                    </div>
                                                </div>
                                                <h5 class="fw-bold mb-1">{{ $level2->name }}</h5>
                                                <div class="text-muted fs-8 mb-2">{{ $level2->jabatan }}</div>
                                                <span class="badge badge-light-primary fs-8 mb-2">
                                                    {{ ucfirst(str_replace('_', ' ', $level2->level_jabatan)) }}
                                                </span>
                                                @if ($penilaianL2)
                                                    <div class="mt-2">
                                                        <span class="badge badge-{{ $badgeL2 }} fs-6">
                                                            {{ number_format($penilaianL2->nilai_kinerja, 2) }}
                                                        </span>
                                                        <div class="text-muted fs-9">{{ $penilaianL2->predikat }}</div>
                                                    </div>
                                                @else
                                                    <span class="badge badge-light-secondary fs-8">Belum Dinilai</span>
                                                @endif

                                                <!-- Jumlah bawahan -->
                                                @if ($level2->bawahan->count() > 0)
                                                    <div class="mt-3 pt-3 border-top">
                                                        <span class="badge badge-light-success">
                                                            <i class="ki-duotone ki-people fs-5 me-1"></i>
                                                            {{ $level2->bawahan->count() }} Bawahan
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Level 3: Bawahan dari Level 2 -->
                                        @if ($level2->bawahan->count() > 0)
                                            <div class="hierarchy-line-vertical"
                                                style="height: 30px; width: 2px; background: #e1e3ea; margin: 0 auto;">
                                            </div>
                                            <div class="row g-3 mt-2">
                                                @foreach ($level2->bawahan as $level3)
                                                    <div class="col-12">
                                                        @php
                                                            $penilaianL3 = $penilaians[$level3->id] ?? null;
                                                            $badgeL3 = $this->getBadgeClass(
                                                                $penilaianL3?->nilai_kinerja,
                                                            );
                                                        @endphp
                                                        <div class="card border border-{{ $badgeL3 }} shadow-sm"
                                                            style="cursor: pointer;"
                                                            wire:click="showUserDetail({{ $level3->id }})">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="symbol symbol-circle symbol-40px me-3">
                                                                        <div
                                                                            class="symbol-label fs-6 bg-light-warning text-warning">
                                                                            {{ $level3->initials() }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="fw-bold">{{ $level3->name }}</div>
                                                                        <div class="text-muted fs-9">
                                                                            {{ $level3->jabatan }}</div>
                                                                    </div>
                                                                    @if ($penilaianL3)
                                                                        <span class="badge badge-{{ $badgeL3 }}">
                                                                            {{ number_format($penilaianL3->nilai_kinerja, 2) }}
                                                                        </span>
                                                                    @else
                                                                        <span class="badge badge-light-secondary fs-9">
                                                                            Belum Dinilai
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-15">
                        <i class="ki-duotone ki-information-5 fs-5x text-muted mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <h3 class="text-muted">
                            {{ $periode_id ? 'Data struktur organisasi tidak ditemukan' : 'Silakan pilih periode terlebih dahulu' }}
                        </h3>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail User -->
    <div wire:ignore.self class="modal fade" id="user-detail-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                @if ($selectedUser)
                    <div class="modal-header">
                        <div>
                            <h3 class="modal-title">Detail Kinerja: {{ $selectedUser->name }}</h3>
                            <div class="text-muted">{{ $selectedUser->jabatan }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Info Penilaian -->
                        @php
                            $penilaian = $selectedUser->penilaianKinerja->first();
                        @endphp
                        @if ($penilaian)
                            <div class="card bg-light-{{ $this->getBadgeClass($penilaian->nilai_kinerja) }} mb-5">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3 text-center">
                                            <h1
                                                class="display-4 fw-bold text-{{ $this->getBadgeClass($penilaian->nilai_kinerja) }}">
                                                {{ number_format($penilaian->nilai_kinerja, 2) }}
                                            </h1>
                                            <div class="fs-5 fw-bold">{{ $penilaian->predikat }}</div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="fw-bold text-muted">Total Target</div>
                                                    <div class="fs-4">
                                                        {{ number_format($penilaian->total_target, 2) }}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="fw-bold text-muted">Total Realisasi</div>
                                                    <div class="fs-4">
                                                        {{ number_format($penilaian->total_realisasi, 2) }}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="fw-bold text-muted">Persentase Capaian</div>
                                                    <div class="fs-4">
                                                        {{ number_format($penilaian->persentase_capaian, 2) }}%</div>
                                                </div>
                                            </div>
                                            @if ($penilaian->catatan)
                                                <div class="alert alert-info mt-3 mb-0">
                                                    <strong>Catatan:</strong> {{ $penilaian->catatan }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Detail Target & Realisasi -->
                        <h5 class="mb-4">Rincian Target dan Realisasi</h5>
                        <div class="table-responsive">
                            <table class="table table-row-bordered align-middle">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th>Indikator</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>Capaian</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($selectedUser->targetKinerja as $target)
                                        @php
                                            $totalRealisasi = $target->getTotalRealisasi();
                                            $persentase = $target->getPersentaseCapaian();
                                            $badge =
                                                $persentase >= 100
                                                    ? 'success'
                                                    : ($persentase >= 75
                                                        ? 'warning'
                                                        : 'danger');
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-bold">
                                                    {{ $target->indikatorKinerja->indikator_program }}</div>
                                                <div class="text-muted fs-8">
                                                    {{ $target->indikatorKinerja->kode_indikator }}</div>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-info">
                                                    {{ number_format($target->target, 2) }}
                                                    {{ $target->indikatorKinerja->satuan }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-{{ $badge }}">
                                                    {{ number_format($totalRealisasi, 2) }}
                                                    {{ $target->indikatorKinerja->satuan }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress h-8px w-100px me-2">
                                                        <div class="progress-bar bg-{{ $badge }}"
                                                            style="width: {{ min($persentase, 100) }}%"></div>
                                                    </div>
                                                    <span class="fw-bold">{{ number_format($persentase, 1) }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($persentase >= 100)
                                                    <span class="badge badge-success">Target Tercapai</span>
                                                @elseif($persentase >= 75)
                                                    <span class="badge badge-warning">Mendekati Target</span>
                                                @else
                                                    <span class="badge badge-danger">Perlu Perhatian</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                Tidak ada target kinerja untuk periode ini
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('footer')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('showDetailModal', () => {
                    $('#user-detail-modal').modal('show');
                });

                Livewire.on('closeDetailModal', () => {
                    $('#user-detail-modal').modal('hide');
                });
            });
        </script>
    @endpush
</div>
