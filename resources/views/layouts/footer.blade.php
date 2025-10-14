<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ now()->format('Y') }}&copy;</span>
            <a href="https://indotechconsulting.com" target="_blank"
                class="text-gray-800 text-hover-primary">Indotechconsulting.com</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        {{-- <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
            </li>
            <li class="menu-item">
                <a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
            </li>
            <li class="menu-item">
                <a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
            </li>
        </ul> --}}
        <!--end::Menu-->
    </div>
    <!--end::Footer container-->
</div>
<!--end::Footer-->
</div>
<!--end:::Main-->
</div>
<!--end::Wrapper-->
</div>
<!--end::Page-->
</div>
<!--end::App-->
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
        <span class="path1"></span>
        <span class="path2"></span>
    </i>
</div>
<!--end::Scrolltop-->
<!--begin::Modals-->
<!--end::Modals-->
<!--begin::Javascript-->
<script>
    var hostUrl = "assets/";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('/') }}assets/plugins/global/plugins.bundle.js"></script>
<script src="{{ asset('/') }}assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
{{-- <script src="{{ asset('/') }}assets/plugins/custom/datatables/datatables.bundle.js"></script> --}}
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
{{-- <script src="{{ asset('/') }}assets/js/custom/apps/user-management/users/list/table.js"></script>
<script src="{{ asset('/') }}assets/js/custom/apps/user-management/users/list/export-users.js"></script>
<script src="{{ asset('/') }}assets/js/custom/apps/user-management/users/list/add.js"></script>
<script src="{{ asset('/') }}assets/js/widgets.bundle.js"></script>
<script src="{{ asset('/') }}assets/js/custom/widgets.js"></script>
<script src="{{ asset('/') }}assets/js/custom/apps/chat/chat.js"></script>
<script src="{{ asset('/') }}assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="{{ asset('/') }}assets/js/custom/utilities/modals/create-app.js"></script>
<script src="{{ asset('/') }}assets/js/custom/utilities/modals/users-search.js"></script> --}}
<!--end::Custom Javascript-->
<!--end::Javascript-->
@stack('footer')
</body>
<!--end::Body-->

</html>
