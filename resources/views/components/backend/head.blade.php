<!-- Basic Page Info -->
<meta charset="utf-8" />

{{-- SEO --}}
<meta name="description" content="Manage and control your website efficiently with the Admin Dashboard of Abhishek Portfolio. Update content, view analytics, and monitor performance.">
<meta name="keywords" content="Admin Dashboard, Abhishek Portfolio Admin, Website Management, Dashboard Analytics, Abhishek, Portfolio Control Panel">
<meta name="author" content="Abhishek Jha">
<meta name="robots" content="noindex, nofollow">


<!-- Title -->
<title>{{ config('app.name', 'Abhishek Portfolio') }} | @yield('title')</title>

<!-- CSRF Token -->
<meta name="csrf-token" content="content">
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

<!-- Site favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/assets/favicon.png') }}" />
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/assets/favicon.png') }}" />
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/assets/favicon.png') }}" />

<!-- Toaster Message -->
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/jquery-steps/jquery.steps.css') }}" type="text/css"/>

<!-- Mobile Specific Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/core.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/icon-font.min.css') }}" type="text/css"/>

<!-- Datatable CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" type="text/css"/>

<!-- bootstrap-tagsinput css -->
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/style.css') }}" type="text/css"/>


<!-- Toaster CSS / JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
