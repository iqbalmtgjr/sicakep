<!-- File: resources/views/livewire/notifikasi/penilaian/index.blade.php -->
<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                    Hasil Kinerja
                </h1>
                <span class="text-muted fs-6">Hasil kinerja pegawai</span>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="col-12">
                <!-- Alert Notifikasi Penilaian Terbaru -->
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0 pt-6">
                                {{-- <div class="card-title">
                                    <div class="d-flex align-items-center gap-3">
                                        <select class="form-select form-select-solid w-150px"
                                            wire:model.live="filterStatus">
                                            <option value="">Semua Status</option>
                                            <option value="unread">Belum Dibaca</option>
                                            <option value="read">Sudah Dibaca</option>
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end">
                                        <div class="input-group w-300px">
                                            <span class="input-group-text">
                                                <i class="ki-duotone ki-magnifier fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                            <input type="text" class="form-control form-control-solid"
                                                placeholder="Cari hasil kinerja..."
                                                wire:model.live.debounce.300ms="search">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body py-4">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Periode</th>
                                                <th>Nilai</th>
                                                <th>Kategori</th>
                                                <th>Pesan</th>
                                                <th>Dinilai oleh</th>
                                                <th>Tanggal</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            @forelse($datas as $data)
                                                @php
                                                    $nilai = $data->nilai_kinerja ?? 0;
                                                    $kategoriData = $this->getPesanPenilaian($nilai);
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-info">{{ $data->periode->nama_periode ?? '-' }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-light-primary">{{ number_format($nilai, 2) }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($kategoriData)
                                                            <span
                                                                class="badge badge-{{ $kategoriData['badge'] }}">{{ $kategoriData['kategori'] }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $kategoriData->pesan }}</td>
                                                    <td>{{ $data->penilai->name }} ({{ $data->penilai->jabatan }})
                                                    </td>
                                                    <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>
                                                    <td class="text-end">
                                                        <button type="button"
                                                            class="btn btn-lg btn-icon btn-text-success rounded-pill"
                                                            wire:click="exportPdf({{ $data->id }})"
                                                            title="Export PDF">
                                                            <i class="ki-duotone ki-printer">
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
                                                    <td colspan="7" class="text-center text-muted">
                                                        <div class="py-10">
                                                            <i
                                                                class="ki-duotone ki-notification-bing fs-5x text-muted mb-5">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <p class="fs-5 fw-bold">Belum ada hasil kinerja</p>
                                                            <p class="text-muted">Hasil kinerja akan muncul
                                                                setelah atasan melakukan penilaian</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $datas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
