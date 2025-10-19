<!-- File: resources/views/livewire/realisasi-kinerja/index.blade.php -->
<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Realisasi Kinerja Saya
                    </h1>
                    <span class="text-muted fs-6">Input dan kelola realisasi kinerja Anda</span>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="col-12">
                <!-- Alert Notifikasi Penilaian dari Database -->
                @if ($notifikasiPenilaian)
                    @php
                        // Parse pesan JSON dari notifikasi
                        $pesanData = json_decode($notifikasiPenilaian->pesan, true);
                        $nilai = $pesanData['nilai'] ?? 0;
                        $kategoriData = $this->getPesanPenilaian($nilai);
                    @endphp
                    <div class="alert alert-dismissible bg-light-{{ $kategoriData['badge'] }} border border-{{ $kategoriData['badge'] }} border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fade show"
                        wire:transition>
                        <i
                            class="ki-duotone {{ $kategoriData['icon'] }} fs-3x text-{{ $kategoriData['badge'] }} me-4 mb-5 mb-sm-0">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h4 class="fw-bold mb-2">{{ $notifikasiPenilaian->judul }}</h4>
                            <div class="mb-3">
                                <span
                                    class="badge badge-{{ $kategoriData['badge'] }} fs-5 me-2">{{ $kategoriData['kategori'] }}</span>
                                <span class="badge badge-light-{{ $kategoriData['badge'] }} fs-5">Nilai:
                                    {{ number_format($nilai, 2) }}</span>
                                <span class="badge badge-light-info fs-6 ms-2">{{ $pesanData['periode'] ?? '-' }}</span>
                            </div>
                            <span class="fs-6 fw-semibold text-gray-700 mb-3">
                                <strong>{{ $kategoriData['kategori'] }}:</strong> {{ $kategoriData['pesan'] }}
                            </span>
                            @if (!empty($pesanData['catatan']))
                                <div class="alert alert-info p-3 mt-2">
                                    <strong><i class="ki-duotone ki-message-text fs-5 me-1"><span
                                                class="path1"></span><span class="path2"></span><span
                                                class="path3"></span></i>Catatan Penilai:</strong>
                                    <p class="mb-0 mt-2">{{ $pesanData['catatan'] }}</p>
                                </div>
                            @endif
                            <div class="text-muted fs-7 mt-2">
                                <i class="ki-duotone ki-calendar fs-6 me-1"><span class="path1"></span><span
                                        class="path2"></span></i>
                                Dinilai pada:
                                {{ $pesanData['tanggal'] ?? $notifikasiPenilaian->created_at->format('d F Y') }}
                                @if (!empty($pesanData['penilai']))
                                    oleh {{ $pesanData['penilai'] }}
                                @endif
                            </div>
                        </div>
                        <button type="button"
                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                            wire:click="dismissNotifikasi({{ $notifikasiPenilaian->id }})">
                            <i class="ki-duotone ki-cross fs-1 text-{{ $kategoriData['badge'] }}">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                @endif

                <!-- Alert Notifikasi Verifikasi -->
                @if ($notifikasiVerifikasi)
                    @php
                        // Parse pesan JSON dari notifikasi
                        $pesanData = json_decode($notifikasiVerifikasi->pesan, true);
                    @endphp
                    <div class="alert alert-dismissible bg-light-{{ $pesanData['status'] == 'verified' ? 'success' : 'danger' }} border border-{{ $pesanData['status'] == 'verified' ? 'success' : 'danger' }} border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fade show"
                        wire:transition>
                        <i
                            class="ki-duotone {{ $pesanData['status'] == 'verified' ? 'ki-check-circle' : 'ki-cross-circle' }} fs-3x text-{{ $pesanData['status'] == 'verified' ? 'success' : 'danger' }} me-4 mb-5 mb-sm-0">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h4 class="fw-bold mb-2">{{ $notifikasiVerifikasi->judul }}</h4>
                            <div class="mb-3">
                                <span
                                    class="badge badge-{{ $pesanData['status'] == 'verified' ? 'success' : 'danger' }} fs-5 me-2">{{ $pesanData['status'] == 'verified' ? 'Diterima' : 'Ditolak' }}</span>
                                <span
                                    class="badge badge-light-info fs-6 ms-2">{{ $pesanData['tanggal'] ?? '-' }}</span>
                            </div>
                            <span class="fs-6 fw-semibold text-gray-700 mb-3">
                                <strong>Indikator:</strong> {{ $pesanData['indikator'] ?? '-' }}<br>
                                <strong>Realisasi:</strong>
                                {{ number_format($pesanData['realisasi'] ?? 0, 2, ',', '.') }}<br>
                                <strong>Verifikator:</strong> {{ $pesanData['verifikator'] ?? '-' }}
                            </span>
                            @if (!empty($pesanData['catatan']))
                                <div class="alert alert-info p-3 mt-2">
                                    <strong><i class="ki-duotone ki-message-text fs-5 me-1"><span
                                                class="path1"></span><span class="path2"></span><span
                                                class="path3"></span></i>Catatan Verifikator:</strong>
                                    <p class="mb-0 mt-2">{{ $pesanData['catatan'] }}</p>
                                </div>
                            @endif
                            <div class="text-muted fs-7 mt-2">
                                <i class="ki-duotone ki-calendar fs-6 me-1"><span class="path1"></span><span
                                        class="path2"></span></i>
                                Diberitahu pada: {{ $notifikasiVerifikasi->created_at->format('d F Y H:i') }}
                            </div>
                        </div>
                        <button type="button"
                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                            wire:click="dismissNotifikasiVerifikasi({{ $notifikasiVerifikasi->id }})">
                            <i
                                class="ki-duotone ki-cross fs-1 text-{{ $pesanData['status'] == 'verified' ? 'success' : 'danger' }}">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                @endif

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
                                        <select class="form-select form-select-solid w-150px"
                                            wire:model.live="filterStatus">
                                            <option value="">Semua Status</option>
                                            <option value="draft">Draft</option>
                                            <option value="submitted">Diajukan</option>
                                            <option value="verified">Terverifikasi</option>
                                            <option value="rejected">Ditolak</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" wire:click="create()">
                                            <i class="ki-duotone ki-plus fs-2"></i>
                                            Input Realisasi
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body py-4">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
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
                                                        'draft' => ['class' => 'secondary', 'text' => 'Draft'],
                                                        'submitted' => ['class' => 'warning', 'text' => 'Diajukan'],
                                                        'verified' => ['class' => 'success', 'text' => 'Terverifikasi'],
                                                        'rejected' => ['class' => 'danger', 'text' => 'Ditolak'],
                                                    ];

                                                    $badge = $statusBadge[$realisasi->status];
                                                @endphp
                                                <tr>
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
                                                                class="text-muted fs-7">{{ Str::limit($indikator->nama_indikator, 40) }}</span>
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
                                                        @if ($realisasi->status == 'rejected' && $realisasi->catatan_verifikasi)
                                                            <i class="ki-duotone ki-information-5 text-danger fs-2"
                                                                data-bs-toggle="tooltip"
                                                                title="{{ $realisasi->catatan_verifikasi }}">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                            </i>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
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

                                                        @if ($realisasi->status == 'draft')
                                                            <button type="button"
                                                                class="btn btn-sm btn-icon btn-text-primary rounded-pill"
                                                                wire:click="submit({{ $realisasi->id }})"
                                                                wire:confirm="Yakin ingin mengajukan realisasi ini?"
                                                                title="Ajukan">
                                                                <i class="ki-duotone ki-send fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </button>
                                                        @endif

                                                        @if (in_array($realisasi->status, ['draft', 'rejected']))
                                                            <button type="button"
                                                                class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                                wire:click="edit({{ $realisasi->id }})"
                                                                title="Edit">
                                                                <i class="ki-duotone ki-pencil fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                                wire:click="confirmDelete({{ $realisasi->id }})"
                                                                title="Hapus">
                                                                <i class="ki-duotone ki-trash fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                    <span class="path4"></span>
                                                                    <span class="path5"></span>
                                                                </i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        <div class="py-10">
                                                            <i
                                                                class="ki-duotone ki-file-deleted fs-5x text-muted mb-5">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <p class="fs-5 fw-bold">Belum ada realisasi kinerja</p>
                                                            <p class="text-muted">Klik tombol "Input Realisasi" untuk
                                                                menambahkan</p>
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

    <!-- Modal Input/Edit Realisasi -->
    <div wire:ignore.self class="modal fade" id="realisasi-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editing ? 'Edit Realisasi Kinerja' : 'Input Realisasi Kinerja' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="alert alert-info d-flex align-items-center p-5 mb-5">
                            <i class="ki-duotone ki-information-5 fs-2hx text-info me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h5 class="mb-1">Informasi</h5>
                                <span>Pastikan data realisasi yang Anda input sesuai dengan target yang telah
                                    ditetapkan.</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="target_kinerja_id" class="form-label">Pilih Target Kinerja <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="target_kinerja_id" wire:model="target_kinerja_id"
                                    required>
                                    <option value="">Pilih Target</option>
                                    @foreach ($targets as $target)
                                        <option value="{{ $target->id }}">
                                            {{ $target->periode->nama_periode }} -
                                            {{ $target->indikatorKinerja->indikator_program }}
                                            (Target: {{ $target->target }} {{ $target->indikatorKinerja->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                                @if (count($targets) == 0)
                                    <small class="text-danger">Anda belum memiliki target kinerja aktif</small>
                                @endif
                                @error('target_kinerja_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_realisasi" class="form-label">Tanggal Realisasi <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_realisasi"
                                    wire:model="tanggal_realisasi" required>
                                @error('tanggal_realisasi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="realisasi" class="form-label">Nilai Realisasi <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="realisasi"
                                    wire:model="realisasi"
                                    placeholder="Masukkan nilai realisasi, contoh: 100 atau 19,5" required>
                                @error('realisasi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" wire:model="keterangan" rows="3"
                                    placeholder="Keterangan atau deskripsi realisasi..."></textarea>
                                @error('keterangan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="bukti_file" class="form-label">Upload Bukti Pendukung (Opsional)</label>
                                <input type="file" class="form-control" id="bukti_file" wire:model="bukti_file"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                <small class="text-muted">Format: PDF, JPG, PNG, DOC, DOCX (Maks. 2MB)</small>
                                @if ($bukti_file)
                                    <div class="mt-2">
                                        <span class="badge badge-light-success">File dipilih:
                                            {{ $bukti_file->getClientOriginalName() }}</span>
                                    </div>
                                @endif
                                @error('bukti_file')
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" wire:model="status">
                                    <option value="draft">Simpan sebagai Draft</option>
                                    <option value="submitted">Ajukan untuk Verifikasi</option>
                                </select>
                                <small class="text-muted">Draft dapat diedit kembali, sedangkan yang diajukan akan
                                    menunggu verifikasi atasan</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="closeModal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">{{ $editing ? 'Perbarui' : 'Simpan' }}</span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Menyimpan...
                            </span>
                        </button>
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
                    text: "Data realisasi akan dihapus permanen!",
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
                $('#realisasi-modal').modal('show');
            });

            Livewire.on('closeModal', () => {
                $('#realisasi-modal').modal('hide');
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
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
                if (event.target.id === 'realisasi') {
                    convertCommaToDot(event);
                }
            });

            // Also handle on form submit
            document.addEventListener('submit', function(event) {
                const realisasiInput = document.getElementById('realisasi');
                if (realisasiInput && realisasiInput.value.includes(',')) {
                    realisasiInput.value = realisasiInput.value.replace(',', '.');
                }
            });
        });
    </script>
</div>
