@php
    $route = request()->route()->getName();
@endphp

{{-- HOME --}}
@if($route === 'frontend.home')
    <script src="{{ asset('frontend/assets/vendor/typed.js/typed.umd.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/purecounter/purecounter_vanilla.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/swiper/swiper-bundle.min.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/aos/aos.js') }}" defer></script>
@endif

{{-- COMMON AOS --}}
@if(in_array($route, [
    'frontend.about',
    'frontend.resume',
    'frontend.contact',
    'frontend.under-construction',
    'frontend.thank-you',
    'frontend.404',
]) || str_starts_with($route, 'frontend.services.'))
    <script src="{{ asset('frontend/assets/vendor/aos/aos.js') }}" defer></script>
@endif

{{-- PORTFOLIO --}}
@if(str_contains($route, 'frontend.portfolio'))
    <script src="{{ asset('frontend/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/glightbox/js/glightbox.min.js') }}" defer></script>
    {{-- 🔥 ADD THIS --}}
    <script src="{{ asset('frontend/assets/vendor/aos/aos.js') }}" defer></script>
@endif

{{-- MAIN --}}
<script src="{{ asset('frontend/assets/js/main.js') }}" defer></script>