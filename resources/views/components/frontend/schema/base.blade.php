@props([
    'title' => 'Abhishek Jha Portfolio',
    'url' => null,
])

@php
    $finalUrl = $url ?? url()->current();

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => $title,
        "url" => $finalUrl,
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => route('frontend.home') . "?search={search_term_string}",
            "query-input" => "required name=search_term_string",
        ],
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>