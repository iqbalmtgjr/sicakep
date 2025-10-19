<div>
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 pb-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Dashboard
                    </h1>
                    <span class="text-muted fs-6">Selamat datang, {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            @if (auth()->user()->isPegawai() && $notifikasiVerifikasi)
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
                            <span class="badge badge-light-info fs-6 ms-2">{{ $pesanData['tanggal'] ?? '-' }}</span>
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

            @if (auth()->user()->isAdmin())
                {{-- Dashboard Admin --}}
                <div class="row g-5 g-xl-10 mb-5">
                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Total Pegawai</div>
                                    <div class="symbol symbol-50px">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-people fs-2x text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </span>
                                    </div>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $totalPegawai }}</div>
                                <div class="text-muted fs-7">Pegawai Aktif</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Periode</div>
                                    <div class="symbol symbol-50px">
                                        <span class="symbol-label bg-light-info">
                                            <i class="ki-duotone ki-calendar fs-2x text-info">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $periodeAktif }}</div>
                                <div class="text-muted fs-7">Periode Aktif dari {{ $totalPeriode }} Total</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Menunggu Verifikasi</div>
                                    <div class="symbol symbol-50px">
                                        <span class="symbol-label bg-light-warning">
                                            <i class="ki-duotone ki-time fs-2x text-warning">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $realisasiMenunggu }}</div>
                                <div class="text-muted fs-7">Realisasi</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Terverifikasi</div>
                                    <div class="symbol symbol-50px">
                                        <span class="symbol-label bg-light-success">
                                            <i class="ki-duotone ki-check-circle fs-2x text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $realisasiVerified }}</div>
                                <div class="text-muted fs-7">Realisasi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Realisasi Terbaru</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase">
                                        <th>Pegawai</th>
                                        <th>Indikator</th>
                                        <th>Tanggal</th>
                                        <th>Realisasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRealisasi as $realisasi)
                                        <tr>
                                            <td>{{ $realisasi->user->name }}</td>
                                            <td>{{ Str::limit($realisasi->targetKinerja->indikatorKinerja->indikator_program, 40) }}
                                            </td>
                                            <td>{{ $realisasi->tanggal_realisasi->format('d/m/Y') }}</td>
                                            <td>{{ number_format($realisasi->realisasi, 2, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $badges = [
                                                        'draft' => 'secondary',
                                                        'submitted' => 'warning',
                                                        'verified' => 'success',
                                                        'rejected' => 'danger',
                                                    ];
                                                @endphp
                                                <span class="badge badge-{{ $badges[$realisasi->status] }}">
                                                    {{ ucfirst($realisasi->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada realisasi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->isAtasan())
                {{-- Dashboard Atasan --}}
                <div class="row g-5 g-xl-10 mb-5">
                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6">Menunggu Verifikasi</div>
                                    <i class="ki-duotone ki-time fs-2x text-warning">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $realisasiMenunggu }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6">Terverifikasi</div>
                                    <i class="ki-duotone ki-check-circle fs-2x text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $realisasiVerified }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6">Ditolak</div>
                                    <i class="ki-duotone ki-cross-circle fs-2x text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $realisasiRejected }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6">Realisasi Saya</div>
                                    <i class="ki-duotone ki-chart-simple fs-2x text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </div>
                                <div class="fw-bold fs-2x pt-4">{{ $myRealisasi }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Realisasi Menunggu Verifikasi</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('verifikasi.index') }}" class="btn btn-sm btn-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase">
                                        <th>Pegawai</th>
                                        <th>Indikator</th>
                                        <th>Tanggal</th>
                                        <th>Realisasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRealisasi as $realisasi)
                                        <tr>
                                            <td>{{ $realisasi->user->name }}</td>
                                            <td>{{ Str::limit($realisasi->targetKinerja->indikatorKinerja->indikator_program, 40) }}
                                            </td>
                                            <td>{{ $realisasi->tanggal_realisasi->format('d/m/Y') }}</td>
                                            <td>{{ number_format($realisasi->realisasi, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Tidak ada realisasi
                                                menunggu verifikasi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                {{-- Dashboard Pegawai --}}
                <div class="row g-5 g-xl-10 mb-5">
                    <div class="col-md-12">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <h3 class="card-title mb-5">Capaian Kinerja</h3>
                                @if ($periodeAktif)
                                    <div class="text-muted mb-3">Periode:
                                        <strong>{{ $periodeAktif->nama_periode }}</strong>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-grow-1 me-5">
                                            <div class="progress h-20px">
                                                <div class="progress-bar bg-{{ $persentaseCapaian >= 100 ? 'success' : ($persentaseCapaian >= 75 ? 'warning' : 'danger') }}"
                                                    role="progressbar"
                                                    style="width: {{ min($persentaseCapaian, 100) }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fw-bold fs-2">{{ number_format($persentaseCapaian, 1) }}%</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="text-muted">Target:</span>
                                            <span
                                                class="fw-bold">{{ number_format($totalTarget, 2, ',', '.') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-muted">Realisasi:</span>
                                            <span
                                                class="fw-bold">{{ number_format($totalRealisasi, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">Belum ada periode aktif</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-10 mb-5">
                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="text-gray-700 fw-semibold fs-6 mb-2">Draft</div>
                                <div class="fw-bold fs-2x">{{ $realisasiDraft }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="text-gray-700 fw-semibold fs-6 mb-2">Diajukan</div>
                                <div class="fw-bold fs-2x text-warning">{{ $realisasiSubmitted }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="text-gray-700 fw-semibold fs-6 mb-2">Terverifikasi</div>
                                <div class="fw-bold fs-2x text-success">{{ $realisasiVerified }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="text-gray-700 fw-semibold fs-6 mb-2">Total Target</div>
                                <div class="fw-bold fs-2x text-primary">{{ $totalTarget }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Target Kinerja Saya</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('realisasi.index') }}" class="btn btn-sm btn-primary">
                                Input Realisasi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase">
                                        <th>Indikator</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>Capaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myTargets as $target)
                                        @php
                                            $realisasi = $target->getTotalRealisasi();
                                            $persentase = $target->getPersentaseCapaian();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div>
                                                    <span
                                                        class="badge badge-light-primary mb-1">{{ $target->indikatorKinerja->kode_indikator }}</span>
                                                    <div>{{ $target->indikatorKinerja->indikator_program }}</div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($target->target, 2, ',', '.') }}
                                                {{ $target->indikatorKinerja->satuan }}</td>
                                            <td>{{ number_format($realisasi, 2, ',', '.') }}
                                                {{ $target->indikatorKinerja->satuan }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress h-6px w-100px me-2">
                                                        <div class="progress-bar bg-{{ $persentase >= 100 ? 'success' : ($persentase >= 75 ? 'warning' : 'danger') }}"
                                                            style="width: {{ min($persentase, 100) }}%"></div>
                                                    </div>
                                                    <span>{{ number_format($persentase, 1) }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada target kinerja
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if (!auth()->user()->isAdmin() && !auth()->user()->isAtasan() && $periodeAktif)
                {{-- Chart Capaian Kinerja Pegawai --}}
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Capaian Kinerja per Indikator</h3>
                    </div>
                    <div class="card-body">
                        @if (!empty($chartData['labels']))
                            <div id="capaianKinerjaChart"></div>
                        @else
                            <div class="text-center text-muted">Data capaian belum cukup untuk menampilkan grafik.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if (!auth()->user()->isAdmin() && !auth()->user()->isAtasan() && $periodeAktif && !empty($chartData['labels']))
    @push('footer')
        <script>
            document.addEventListener('livewire:navigated', () => {
                initCapaianChart();
            });

            function initCapaianChart() {
                const chartData = @json($chartData);

                var options = {
                    series: [{
                        name: 'Capaian',
                        data: chartData.series
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: true,
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val + "%";
                        }
                    },
                    xaxis: {
                        categories: chartData.labels,
                        labels: {
                            formatter: function(val) {
                                return val + "%"
                            }
                        },
                        max: 100
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + "%"
                            }
                        }
                    },
                    colors: ['#00E396'],
                };

                // Hancurkan chart lama jika ada untuk mencegah duplikasi
                if (window.capaianChart) {
                    window.capaianChart.destroy();
                }

                const chartElement = document.querySelector("#capaianKinerjaChart");
                if (chartElement) {
                    window.capaianChart = new ApexCharts(chartElement, options);
                    window.capaianChart.render();
                }
            }

            // Inisialisasi pertama kali
            initCapaianChart();
        </script>
    @endpush
@endif
