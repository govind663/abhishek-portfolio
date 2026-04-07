{{-- ===============================
    BASIC META
================================ --}}
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

{{-- ===============================
    SEO COMPONENT (FINAL)
================================ --}}
<x-frontend.seo
    :title="trim($__env->yieldContent('title')) ?: 'Abhishek Jha | Laravel Developer'"
    :description="trim($__env->yieldContent('meta_description'))"
    :keywords="trim($__env->yieldContent('meta_keywords'))"
    :canonical="trim($__env->yieldContent('canonical')) ?: url()->current()"
/>

{{-- ===============================
    CSRF
================================ --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- ===============================
    FAVICON
================================ --}}
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/backend/assets/favicon.png') }}" />
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/backend/assets/favicon.png') }}" />
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/backend/assets/favicon.png') }}" />

{{-- ===============================
    PERFORMANCE
================================ --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">

{{-- ===============================
    GOOGLE FONTS
================================ --}}
<link 
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@300;400;500;600;700&display=swap"
    rel="stylesheet"
    media="print"
    onload="this.media='all'"
>
<noscript>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Raleway&display=swap" rel="stylesheet">
</noscript>

{{-- ===============================
    CSS (Lazy Load)
================================ --}}
<link href="{{ asset('/frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
<link href="{{ asset('/frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
<link href="{{ asset('/frontend/assets/vendor/aos/aos.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
<link href="{{ asset('/frontend/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
<link href="{{ asset('/frontend/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">

<noscript>
    <link href="{{ asset('/frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</noscript>

{{-- ===============================
    MAIN CSS
================================ --}}
<link href="{{ asset('/frontend/assets/css/main.css') }}" rel="stylesheet">

{{-- ===============================
    PAGE CSS
================================ --}}
@stack('styles')

{{-- ===============================
    LAZY LOAD SCRIPT
================================ --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll("img:not([loading])")
        .forEach(img => img.setAttribute("loading", "lazy"));

    document.querySelectorAll("iframe:not([loading])")
        .forEach(el => el.setAttribute("loading", "lazy"));

    document.querySelectorAll("video:not([loading])")
        .forEach(el => el.setAttribute("loading", "lazy"));

});
</script>