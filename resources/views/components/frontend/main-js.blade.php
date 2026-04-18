{{-- Bootstrap (always needed) --}}
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>

{{-- Home Page Only --}}
@if(request()->routeIs('frontend.home'))
    <script src="{{ asset('frontend/assets/vendor/typed.js/typed.umd.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/aos/aos.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/purecounter/purecounter_vanilla.js') }}" defer></script>
@endif

{{-- Portfolio Page --}}
@if(request()->routeIs('frontend.portfolio.*'))
    <script src="{{ asset('frontend/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('frontend/assets/vendor/glightbox/js/glightbox.min.js') }}" defer></script>
@endif

{{-- Slider --}}
@if(request()->routeIs('frontend.home') || request()->routeIs('frontend.services.*'))
    <script src="{{ asset('frontend/assets/vendor/swiper/swiper-bundle.min.js') }}" defer></script>
@endif

{{-- ✅ MAIN JS (NO DELAY, ONLY DEFER) --}}
<script src="{{ asset('frontend/assets/js/main.js') }}" defer></script>