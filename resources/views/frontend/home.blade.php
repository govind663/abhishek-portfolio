@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Full Stack Laravel Developer Portfolio
@endsection

@section('meta_description')
Abhishek Jha is a Full Stack Laravel Developer with 4+ years of experience in PHP, APIs, and scalable web applications.
Explore portfolio, services, and projects.
@endsection

@section('meta_keywords')
Abhishek Jha, Laravel Developer India, PHP Developer Mumbai, Full Stack Developer, API Developer, Portfolio, Web Development Services
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
{{-- CSS (Non-blocking) --}}
<link rel="stylesheet" href="{{ asset('frontend/assets/css/hero.css') }}" media="print" onload="this.media='all'">
<noscript>
<link rel="stylesheet" href="{{ asset('frontend/assets/css/hero.css') }}">
</noscript>

{{-- Critical CSS --}}
<style>
/* =====================================================
   HERO SECTION ULTRA OPTIMIZED
   Optimized For:
   - Hard Refresh Stretch Fix
   - CLS Prevention
   - Mobile Performance
   - Desktop Stability
   - Lighthouse Optimization
===================================================== */


/* =====================================================
   HERO SECTION
===================================================== */

.hero{
    position:relative;
    min-height:100vh;
    height:100vh;
    overflow:hidden;
    isolation:isolate;
    background:#000;
}


/* Mobile Safe Height */
@media (max-width:768px){

    .hero{
        min-height:100svh;
        height:auto;
        padding-top:90px;
        padding-bottom:50px;
    }

}


/* =====================================================
   HERO BACKGROUND
===================================================== */

.hero-bg{
    position:absolute;
    inset:0;

    width:100%;
    height:100%;

    object-fit:cover;
    object-position:center center;

    display:block;

    z-index:-2;

    /* Stretch Fix */
    transform:translateZ(0);

    /* Performance */
    will-change:transform;
    backface-visibility:hidden;
}


/* =====================================================
   PICTURE TAG FIX
===================================================== */

picture{
    position:absolute;
    inset:0;

    width:100%;
    height:100%;

    display:block;
    overflow:hidden;
}


/* =====================================================
   VIDEO BACKGROUND FIX
===================================================== */

.hero video{
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
}


/* =====================================================
   HERO OVERLAY
===================================================== */

.hero-overlay{
    position:absolute;
    inset:0;

    background:rgba(0,0,0,0.45);

    z-index:-1;
}


/* =====================================================
   CONTAINER FIX
===================================================== */

.hero .container{
    position:relative;
    z-index:2;
}


/* =====================================================
   PROFILE IMAGE
===================================================== */

.profile-photo{

    width:140px;
    height:140px;

    border-radius:50%;

    object-fit:cover;
    object-position:center;

    display:block;

    flex-shrink:0;

    aspect-ratio:1/1;

    /* Prevent Stretch */
    max-width:140px;
    max-height:140px;
}


/* Desktop */
@media (min-width:768px){

    .profile-photo{

        width:400px;
        height:400px;

        max-width:400px;
        max-height:400px;
    }

}


/* =====================================================
   GLOBAL IMAGE FIX
===================================================== */

img{
    max-width:100%;
    height:auto;
}


/* =====================================================
   TEXT OPTIMIZATION
===================================================== */

.text-overlay{
    position:relative;
    z-index:5;
}


/* =====================================================
   PERFORMANCE OPTIMIZATION
===================================================== */

section{
    content-visibility:auto;
    contain-intrinsic-size:1px 1000px;
}


/* =====================================================
   MOBILE PERFORMANCE BOOST
===================================================== */

@media (max-width:768px){

    .hero-bg{
        will-change:auto;
    }

}


/* =====================================================
   SAFARI / CHROME HARD REFRESH FIX
===================================================== */

.hero-bg,
picture img,
video{
    image-rendering:auto;
}


/* =====================================================
   PREVENT LAYOUT SHIFT
===================================================== */

.row,
.col-md-4,
.col-md-8{
    min-width:0;
}
</style>
@endpush

@section('content')

@php
use Illuminate\Support\Str;

$bgPath = $hero->getRawOriginal('background_image');
$profilePath = $hero->getRawOriginal('profile_image');

$bgUrl = $bgPath ? asset('storage/' . $bgPath) : null;
$profileUrl = $profilePath ? asset('storage/' . $profilePath) : $hero->profile_image;

/* version cache */
$bgVersion = ($bgPath && file_exists(public_path('storage/' . $bgPath)))
? filemtime(public_path('storage/' . $bgPath))
: time();

$profileVersion = ($profilePath && file_exists(public_path('storage/' . $profilePath)))
? filemtime(public_path('storage/' . $profilePath))
: time();
@endphp

{{-- 🚀 LCP BOOST --}}
@if($bgUrl && !Str::endsWith(strtolower($bgPath), '.mp4'))
<link rel="preload" as="image" href="{{ $bgUrl }}?v={{ $bgVersion }}" fetchpriority="high">
@endif

<section id="hero" class="hero position-relative">

    {{-- Background --}}
    @if($bgPath && Str::endsWith(strtolower($bgPath), '.mp4'))

    <video class="hero-bg"
        autoplay muted loop playsinline preload="metadata"
        width="1920" height="1080">
        <source src="{{ $bgUrl }}?v={{ $bgVersion }}" type="video/mp4">
    </video>

    @elseif($bgUrl)

    <picture>
        {{-- 📱 MOBILE (LIGHT IMAGE REQUIRED) --}}
        <source 
            media="(max-width: 768px)" 
            srcset="{{ asset('storage/mobile-hero.webp') }} 600w">

        {{-- 💻 DESKTOP --}}
        <source 
            media="(min-width: 769px)" 
            srcset="{{ $bgUrl }}?v={{ $bgVersion }} 1920w">

        <img 
            src="{{ $bgUrl }}?v={{ $bgVersion }}" 
            class="hero-bg"
            alt="Background"
            fetchpriority="high"
            decoding="async"
            loading="eager"
            width="1920"
            height="1080">
    </picture>

    @endif

    <div class="hero-overlay"></div>

    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100 align-items-center justify-content-center">

            {{-- PROFILE IMAGE --}}
            <div class="col-md-4 d-flex justify-content-center mb-4 mb-md-0">

                <img 
                    src="{{ $profileUrl }}?v={{ $profileVersion }}" 

                    {{-- 🔥 RESPONSIVE IMAGE --}}
                    srcset="
                        {{ $profileUrl }}?v={{ $profileVersion }} 400w,
                        {{ $profileUrl }}?v={{ $profileVersion }} 200w
                    "
                    sizes="(max-width: 768px) 140px, 400px"

                    alt="{{ $hero->name }} Profile Photo" 
                    title="{{ $hero->name }} Profile Photo"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                    class="img-fluid profile-photo rounded-circle"
                    width="400"
                    height="400"
                    style="aspect-ratio:16/16;">

            </div>

            {{-- CONTENT --}}
            <div class="col-md-8 text-md-start text-overlay">

                <h1>{{ $hero->name }}</h1>

                <p class="intro-line">
                    I'm
                    <span class="typed" data-typed-items="{{ implode(',', $hero->typed_items ?? []) }}"></span>
                </p>

                <p class="mt-3" style="color:#ddd;font-size:20px;text-align:justify !important;">
                    {{ $hero->description }}
                </p>

                {{-- CTA --}}
                <div class="mt-3">
                    <a href="{{ route('frontend.contact') }}" class="btn btn-success">
                        <i class="bi bi-envelope-fill me-1"></i> Contact Me
                    </a>

                    <a href="{{ asset('storage/' . $hero->getRawOriginal('resume_file')) }}" class="btn btn-primary" target="_blank">
                        <i class="bi bi-download me-1"></i> Resume
                    </a>
                </div>

                <hr class="bg-light">

                {{-- SOCIAL --}}
                @php
                use Illuminate\Support\Facades\Cache;
                use App\Models\SocialLink;

                $socialLinks = Cache::remember('header_social_links', 3600, function () {
                    return SocialLink::active()->latestId()->get();
                });

                $defaultColors = [
                    'GitHub' => 'rgb(205,79,1)',
                    'GitLab' => 'rgb(205,79,1)',
                    'Facebook' => 'rgb(5,93,193)',
                    'Instagram' => 'rgb(243,16,122)',
                    'LinkedIn' => 'rgb(70,70,237)',
                ];
                @endphp

                <div class="social-links mt-3">

                    @forelse($socialLinks as $social)

                    @php
                    $platformKey = str_replace(' Profile', '', $social->platform);
                    $iconColor = $social->color ?? ($defaultColors[$platformKey] ?? '#fff');
                    @endphp

                    <a href="{{ $social->url ?? '#' }}" 
                       target="_blank" 
                       rel="noopener noreferrer nofollow"
                       aria-label="Visit {{ $social->platform }}">

                        <i class="{{ $social->icon ?? 'bi bi-link' }}" style="color: {{ $iconColor }}"></i>

                    </a>

                    @empty
                    <p class="text-white">No social links available</p>
                    @endforelse

                </div>

            </div>
        </div>
    </div>
</section>

@include('partials.chatbot')

@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/hero.js') }}" defer></script>
@endpush