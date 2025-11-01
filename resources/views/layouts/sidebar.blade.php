<!--begin::Wrapper-->
<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <!--begin::Sidebar-->
    <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
        data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
        data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
        <!--begin::Logo-->
        <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
            <!--begin::Logo image-->
            <a href="{{ route('home') }}">
                <img alt="Logo" src="{{ asset('/logo.png') }}" class="h-25px app-sidebar-logo-default" />
                <img alt="Logo" src="{{ asset('/logo.png') }}" class="h-20px app-sidebar-logo-minimize" />
            </a>
            <div id="kt_app_sidebar_toggle"
                class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
                data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                data-kt-toggle-name="app-sidebar-minimize">
                <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <!--end::Sidebar toggle-->
        </div>
        <!--end::Logo-->
        <!--begin::sidebar menu-->
        <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
            <!--begin::Menu wrapper-->
            <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
                <!--begin::Scroll wrapper-->
                <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                    data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                    data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                    data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                    data-kt-scroll-save-state="true">
                    <!--begin::Menu-->
                    <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                        id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                        <!--begin:Menu item-->
                        <div class="menu-item pt-5">
                            <!--begin:Menu content-->
                            <div class="menu-content">
                                <span class="menu-heading fw-bold text-uppercase fs-7">Menu</span>
                            </div>
                            <!--end:Menu content-->
                        </div>
                        <!--end:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->is('dashboard') || request()->is('dashboard') ? 'active' : '' }}"
                                href="{{ url('dashboard') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-abstract-26 fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('pengguna*') ? 'active' : '' }}"
                                    href="{{ route('pengguna.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-people fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Pegawai</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('bidang*') ? 'active' : '' }}"
                                    href="{{ route('bidang.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-award fs-2"></i>
                                    </span>
                                    <span class="menu-title">Bidang</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('periode*') ? 'active' : '' }}"
                                    href="{{ route('periode.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-calendar fs-2"></i>
                                    </span>
                                    <span class="menu-title">Periode</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('indikator*') ? 'active' : '' }}"
                                    href="{{ route('indikator.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-graph-up fs-2"></i>
                                    </span>
                                    <span class="menu-title">Indikator Kinerja</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('target*') ? 'active' : '' }}"
                                    href="{{ route('target.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-bullseye fs-2"></i>
                                    </span>
                                    <span class="menu-title">Target Kinerja</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('sasaran-strategis*') ? 'active' : '' }}"
                                    href="{{ route('sasaran-strategis.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-compass fs-2"></i>
                                    </span>
                                    <span class="menu-title">Sasaran Strategis</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        @if (auth()->check() && in_array(auth()->user()->role, ['pegawai']))
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('realisasi*') ? 'active' : '' }}"
                                    href="{{ route('realisasi.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-check-circle fs-2"></i>
                                    </span>
                                    <span class="menu-title">Realisasi Kinerja</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        @if (auth()->check() && in_array(auth()->user()->role, ['pegawai', 'atasan']))
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('hasil-kinerja*') ? 'active' : '' }}"
                                    href="{{ route('hasil.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-award fs-2"></i>
                                    </span>
                                    <span class="menu-title">Hasil Kinerja</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                        {{-- @if (auth()->user()->isAdmin() || auth()->user()->isAtasan())
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('laporan.hierarki') ? 'active' : '' }}"
                                    href="{{ route('laporan.hierarki') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-chart-simple-3 fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Laporan Hierarki Kinerja</span>
                                </a>
                            </div>
                        @endif --}}
                        @if (auth()->check() && in_array(auth()->user()->role, ['atasan']))
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('verifikasi*') ? 'active' : '' }}"
                                    href="{{ route('verifikasi.index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-check fs-2"></i>
                                    </span>
                                    <span class="menu-title">Verifikasi Kinerja</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                        @endif
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Scroll wrapper-->
            </div>
            <!--end::Menu wrapper-->
        </div>
        <!--end::sidebar menu-->
    </div>
    <!--end::Sidebar-->
