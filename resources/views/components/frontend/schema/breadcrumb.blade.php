@php
    $title = $title ?? 'Home';
    $url = $url ?? url()->current();

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "Home",
                "item" => route('frontend.home')
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => $title,
                "item" => $url
            ]
        ]
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>