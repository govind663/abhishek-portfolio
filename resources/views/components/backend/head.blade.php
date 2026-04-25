
{{-- ================= BASIC ================= --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- ================= SEO ================= --}}
<meta name="description" content="Manage and control your website efficiently with the Admin Dashboard of Abhishek Portfolio. Update content, view analytics, and monitor performance.">
<meta name="keywords" content="Admin Dashboard, Abhishek Portfolio Admin, Website Management, Dashboard Analytics, Abhishek, Portfolio Control Panel">
<meta name="author" content="Abhishek Jha">
<meta name="robots" content="index, follow">

{{-- Canonical --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Title --}}
<title>@yield('title') | {{ config('app.name', 'Abhishek Portfolio') }}</title>

{{-- CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- ================= FAVICON ================= --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/assets/favicon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('backend/assets/favicon.png') }}">

{{-- ================= CSS (CRITICAL FIRST) ================= --}}
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/core.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/icon-font.min.css') }}">

{{-- ================= NON-CRITICAL CSS ================= --}}
<link rel="preload" href="{{ asset('backend/assets/vendors/styles/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/style.css') }}">
</noscript>

{{-- Plugins CSS --}}
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/jquery-steps/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

{{-- DataTables CSS --}}
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">

{{-- ================= GOOGLE FONTS (OPTIMIZED) ================= --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link 
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&family=Poppins:wght@400;500;600&display=swap"
    rel="stylesheet"
>

{{-- ================= TOASTR ================= --}}
<link rel="stylesheet" href="{{ asset('backend/assets/toastr/css/toastr.min.css') }}">

{{-- ================= JS (CRITICAL ONLY) ================= --}}
<script src="{{ asset('backend/assets/toastr/js/toastr.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/toastr/js/toastr.min.js') }}" defer></script>
