{{-- ===============================
    VENDOR JS FILES (OPTIMIZED)
================================ --}}

@php
    $vendorScripts = [
        'frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
        'frontend/assets/vendor/aos/aos.js',
        'frontend/assets/vendor/typed.js/typed.umd.js',
        'frontend/assets/vendor/purecounter/purecounter_vanilla.js',
        'frontend/assets/vendor/waypoints/noframework.waypoints.js',
        'frontend/assets/vendor/swiper/swiper-bundle.min.js',
        'frontend/assets/vendor/glightbox/js/glightbox.min.js',
        'frontend/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js',
        'frontend/assets/vendor/isotope-layout/isotope.pkgd.min.js',
    ];
@endphp

@foreach ($vendorScripts as $script)
    <script src="{{ asset($script) }}" defer></script>
@endforeach

{{-- ===============================
    MAIN JS
================================ --}}
<script src="{{ asset('frontend/assets/js/main.js') }}" defer></script>