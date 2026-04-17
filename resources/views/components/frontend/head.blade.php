{{-- ===============================
    BASIC META
================================ --}}
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

{{-- ===============================
    SEO COMPONENT
================================ --}}
@php
    $seoTitle = trim($__env->yieldContent('title')) ?: 'Abhishek Jha | Laravel Developer';
    $seoDescription = trim($__env->yieldContent('meta_description')) ?: 'Laravel Developer Portfolio of Abhishek Jha';
    $seoKeywords = trim($__env->yieldContent('meta_keywords')) ?: 'Laravel, PHP, Web Developer, Portfolio';
    $seoCanonical = trim($__env->yieldContent('canonical')) ?: url()->current();
@endphp

<x-frontend.seo
    :title="$seoTitle"
    :description="$seoDescription"
    :keywords="$seoKeywords"
    :canonical="$seoCanonical"
/>

{{-- ===============================
    CSRF
================================ --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- ===============================
    FAVICON
================================ --}}
<link rel="icon" type="image/png" href="{{ asset('/backend/assets/favicon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('/backend/assets/favicon.png') }}">

{{-- ===============================
    PERFORMANCE (CRITICAL ONLY)
================================ --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- ===============================
    FONT (SINGLE + FAST)
================================ --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    html, body {
        font-family: 'Inter', sans-serif;
    }

    html {
        font-display: swap;
    }
</style>

{{-- ===============================
    CRITICAL CSS (NO DELAY)
================================ --}}
<link rel="stylesheet" href="{{ asset('/frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('/frontend/assets/css/main.css') }}">

{{-- ===============================
    NON-CRITICAL CSS (DEFER)
================================ --}}
<link rel="preload" href="{{ asset('/frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="{{ asset('/frontend/assets/vendor/aos/aos.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="{{ asset('/frontend/assets/vendor/swiper/swiper-bundle.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="{{ asset('/frontend/assets/vendor/glightbox/css/glightbox.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">

<noscript>
    <link rel="stylesheet" href="{{ asset('/frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/frontend/assets/vendor/aos/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('/frontend/assets/vendor/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/frontend/assets/vendor/glightbox/css/glightbox.min.css') }}">
</noscript>

{{-- ===============================
    PAGE CSS
================================ --}}
@stack('styles')