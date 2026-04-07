<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    {{-- =========================
        HEAD SECTION
    ========================== --}}
    <x-frontend.head />

    @php
        $pageTitle = trim($__env->yieldContent('title')) ?: config('app.name');
        $currentUrl = url()->current();
    @endphp

    {{-- =========================
        GLOBAL SEO SCHEMA
    ========================== --}}
    <x-frontend.schema.base :title="$pageTitle" :url="$currentUrl" />

    @if(!request()->routeIs('frontend.home'))
        <x-frontend.schema.breadcrumb :title="$pageTitle" :url="$currentUrl" />
    @endif

    {{-- HOME PAGE EXTRA SCHEMA --}}
    @if(request()->routeIs('frontend.home'))
        <x-frontend.schema.person />
        <x-frontend.schema.faq />
    @endif

    <style>
        body {
            font-family: 'Rajdhani', sans-serif;
        }
    </style>

    @stack('styles')

</head>

<body class="@yield('body_class', 'index-page')" data-aos-easing="ease-in-out" data-aos-duration="800">

    {{-- HEADER --}}
    <x-frontend.header />

    {{-- MAIN CONTENT --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <x-frontend.footer />

    {{-- BACK TO TOP --}}
    <x-frontend.back-to-top />

    {{-- LOADER (ONLY HOME) --}}
    @if(request()->routeIs('frontend.home'))
        <x-frontend.loader />
    @endif

    {{-- MAIN JS --}}
    <x-frontend.main-js />

    {{-- PAGE SCRIPTS --}}
    @stack('scripts')

</body>
</html>