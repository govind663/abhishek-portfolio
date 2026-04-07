@php
    $social = array_values(array_filter([
        config('social.github'),
        config('social.linkedin'),
        config('social.facebook'),
        config('social.instagram'),
    ]));

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => "Abhishek Jha Portfolio",
        "url" => route('frontend.home'),
        "logo" => asset('frontend/assets/img/logo.png'),
        "sameAs" => $social
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>