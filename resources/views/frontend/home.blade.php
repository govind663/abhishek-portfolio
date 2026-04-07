@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Full Stack Laravel Developer Portfolio
@endsection

@section('meta_description')
Abhishek Jha is a Full Stack Laravel Developer with 4+ years of experience in PHP, APIs, and scalable web applications.
Explore portfolio, services, and projects.
@endsection

@section('meta_keywords')
Abhishek Jha, Laravel Developer India, PHP Developer Mumbai, Full Stack Developer, API Developer, Portfolio, Web
Development Services
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
{{-- Custom CSS likes --}}
<link rel="stylesheet" href="{{ asset('frontend/assets/css/hero.css') }}" media="print" onload="this.media='all'">
@endpush

@section('content')
{{-- Hero Section --}}
@php
use Illuminate\Support\Str;

$bgPath = $hero->getRawOriginal('background_image');
$profilePath = $hero->getRawOriginal('profile_image');

$bgUrl = $bgPath ? asset('storage/' . $bgPath) : null;
$profileUrl = $profilePath ? asset('storage/' . $profilePath) : $hero->profile_image;

// Cache version (safe)
$bgVersion = ($bgPath && file_exists(public_path('storage/' . $bgPath)))
? filemtime(public_path('storage/' . $bgPath))
: time();

$profileVersion = ($profilePath && file_exists(public_path('storage/' . $profilePath)))
? filemtime(public_path('storage/' . $profilePath))
: time();
@endphp

<section id="hero" class="hero position-relative">

    {{-- Background --}}
    @if($bgPath && Str::endsWith(strtolower($bgPath), '.mp4'))
    <video class="hero-bg" autoplay muted loop playsinline preload="auto" style="width:100%;height:100%;object-fit:cover;">

        <source src="{{ $bgUrl }}?v={{ $bgVersion }}" type="video/mp4">
    </video>
    @elseif($bgUrl)
    <img src="{{ $bgUrl }}?v={{ $bgVersion }}" class="hero-bg" alt="Background" title="Background Image" loading="eager" style="width:100%;height:100%;object-fit:cover;">
    @endif

    <div class="hero-overlay"></div>

    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100 align-items-center justify-content-center">

            {{-- Profile Image with cache --}}
            <div class="col-md-4 d-flex justify-content-center mb-4 mb-md-0">
                <img src="{{ $profileUrl }}?v={{ $profileVersion }}" alt="{{ $hero->name }} Profile Photo" title="{{ $hero->name }} Profile Photo" loading="eager" class="img-fluid profile-photo rounded-circle" width="400" height="400">
            </div>

            {{-- Content --}}
            <div class="col-md-8 text-md-start text-overlay">

                <h1>{{ $hero->name }}</h1>

                <p class="intro-line">
                    I'm
                    <span class="typed" data-typed-items="{{ implode(',', $hero->typed_items ?? []) }}">
                    </span>
                </p>

                <p class="mt-3" style="color: #ddd; font-size: 20px; text-align: justify !important;">
                    {{ $hero->description }}
                </p>

                {{-- CTA --}}
                <div class="mt-3">
                    <a href="{{ route('frontend.contact') }}" class="btn btn-success" title="Contact Me">
                        <i class="bi bi-envelope-fill me-1"></i>
                        Contact Me
                    </a>

                    <a href="{{ asset('storage/' . $hero->getRawOriginal('resume_file')) }}" class="btn btn-primary" title="Download Resume" target="_blank">
                        <i class="bi bi-download me-1"></i>
                        Resume
                    </a>
                </div>

                <hr class="bg-light">
                {{-- Social Links (Dynamic + Cached) --}}
                @php
                use Illuminate\Support\Facades\Cache;
                use App\Models\SocialLink;

                $socialLinks = Cache::remember('header_social_links', 3600, function () {
                return SocialLink::active()->latestId()->get();
                });

                $defaultColors = [
                'GitHub' => 'rgb(205, 79, 1)',
                'GitLab' => 'rgb(205, 79, 1)',
                'Facebook' => 'rgb(5, 93, 193)',
                'Instagram' => 'rgb(243, 16, 122)',
                'LinkedIn' => 'rgb(70, 70, 237)',
                ];
                @endphp

                <div class="social-links mt-3">

                    @forelse($socialLinks as $social)

                    @php
                    $platformKey = str_replace(' Profile', '', $social->platform);
                    $iconColor = $social->color ?? ($defaultColors[$platformKey] ?? '#ffffff');
                    @endphp

                    <a href="{{ $social->url ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="Visit {{ $social->platform ?? 'Social Profile' }}" title="Visit {{ $social->platform ?? 'Social Profile' }}">

                        <i class="{{ $social->icon ?? 'bi bi-link' }}" style="color: {{ $iconColor }};">
                        </i>

                    </a>

                    @empty
                    <p class="text-white">No social links available</p>
                    @endforelse

                </div>

            </div>
        </div>
    </div>
</section>

{{-- Chat Bot Start --}}
@include('partials.chatbot')
{{-- Chat Bot End --}}
@endsection

@push('scripts')
{{-- Custom JS links --}}
<script src="{{ asset('frontend/assets/js/hero.js') }}" defer></script>
@endpush
