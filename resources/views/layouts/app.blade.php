@include('layouts.header')
@include('layouts.navbar')
@include('layouts.sidebar')

<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        @yield('content')
        {{ isset($slot) ? $slot : null }}
    </div>
    <!--end::Content wrapper-->
    @include('layouts.footer')
