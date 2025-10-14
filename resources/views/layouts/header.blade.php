<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="{{ url('/') }}" />
    <title>{{ env('APP_NAME') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="description"
        content="SICAKEP adalah aplikasi e-kinerja yang digunakan untuk mengelola dan melaporkan kinerja pegawai secara digital. Aplikasi ini memudahkan pemantauan, evaluasi, dan pelaporan kinerja untuk meningkatkan produktivitas organisasi." />
    <meta name="keywords" content="" />
    <meta name="keywords" content="SICAKEP, e-kinerja, aplikasi, kinerja pegawai, laporan, evaluasi" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="SICAKEP - Aplikasi e-kinerja untuk mengelola kinerja pegawai secara digital" />
    <meta property="og:url" content="https://indotechconsulting.com" />
    <meta property="og:site_name" content="SICAKEP" />
    <link rel="shortcut icon" href="{{ asset('/logo.png') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('/') }}assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('/') }}assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
    @stack('header')
</head>
<!--end::Head-->
