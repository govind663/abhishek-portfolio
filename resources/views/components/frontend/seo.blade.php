{{-- ===============================
    TITLE
================================ --}}
<title>{{ $title ?? config('app.name') }}</title>

{{-- ===============================
    BASIC SEO
================================ --}}
<meta name="description" content="{{ $description ?? '' }}">
<meta name="keywords" content="{{ $keywords ?? '' }}">
<link rel="canonical" href="{{ $canonical ?? url()->current() }}">

{{-- ===============================
    OPEN GRAPH (Facebook, WhatsApp, LinkedIn)
================================ --}}
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? '' }}">
<meta property="og:url" content="{{ $canonical ?? url()->current() }}">
<meta property="og:type" content="website">

{{-- 👉 OPTIONAL BUT IMPORTANT (Add later if image available) --}}
{{-- <meta property="og:image" content="{{ asset('path-to-image.jpg') }}"> --}}

{{-- ===============================
    TWITTER SEO
================================ --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $description ?? '' }}">

{{-- 👉 OPTIONAL --}}
{{-- <meta name="twitter:image" content="{{ asset('path-to-image.jpg') }}"> --}}