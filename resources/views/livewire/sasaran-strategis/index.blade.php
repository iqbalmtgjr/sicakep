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
                        Sasaran Strategis
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
                                <div class="card-toolbar">
                                    <!--begin::Toolbar-->
                                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                        <!--begin::Add user-->
                                        <button class="btn btn-primary ms-3" wire:click="create()">
                                            <i class="ki-duotone ki-plus fs-2"></i>
                                            Tambah Sasaran Strategis
                                        </button>
                                        <!--end::Add user-->
                                    </div>
                                    <!--end::Toolbar-->
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
                                                <th>No</th>
                                                <th>Sasaran Strategis</th>
                                                <th>IKU</th>
                                                <th>Target</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            @forelse($sasaranStrategis as $index => $sasaran)
                                                <tr>
                                                    <td>{{ $sasaranStrategis->firstItem() + $loop->index }}</td>
                                                    <td>{{ $sasaran->sasaran_strategis }}</td>
                                                    <td>{{ $sasaran->iku }}</td>
                                                    <td>{{ $sasaran->target }}</td>
                                                    <td class="text-end">
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="edit({{ $sasaran->id }})" title="Edit">
                                                            <i class="ki-duotone ki-pencil fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                                            wire:click="confirmDelete({{ $sasaran->id }})"
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
                                                    <td colspan="5" class="text-center text-muted">Tidak ada data
                                                        sasaran strategis</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{ $sasaranStrategis->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="sasaran-modal" tabindex="-1" aria-labelledby="sasaran-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sasaran-modal-label">
                        {{ $editing ? 'Edit Sasaran Strategis' : 'Tambah Sasaran Strategis' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="sasaran_strategis" class="form-label">Sasaran Strategis</label>
                                <input type="text" class="form-control" id="sasaran_strategis"
                                    wire:model="sasaran_strategis" required>
                                @error('sasaran_strategis')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="iku" class="form-label">IKU</label>
                                <input type="text" class="form-control" id="iku" wire:model="iku" required>
                                @error('iku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="target" class="form-label">Target</label>
                                <input type="text" class="form-control" id="target" wire:model="target" required>
                                @error('target')
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

    <!-- SweetAlert Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirm-delete', (data) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data sasaran strategis akan dihapus permanen!",
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
                $('#sasaran-modal').modal('show');
            });

            Livewire.on('closeModal', () => {
                $('#sasaran-modal').modal('hide');
            });
        });
    </script>
</div>
