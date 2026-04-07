@php
    $email = config('site.email') ?? 'codingthunder1997@gmail.com';
    $phone = config('site.phone') ?? '+919004763926';
    $city = config('site.city') ?? 'Navi Mumbai';
    $state = config('site.state') ?? 'Maharashtra';
    $country = config('site.country') ?? 'India';

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Person",
        "name" => "Abhishek Jha",
        "url" => route('frontend.home'),
        "image" => asset('frontend/assets/img/Abhishek_profile_pic.webp'),
        "jobTitle" => "Full Stack Laravel Developer",
        "description" => "Experienced Laravel Developer specializing in scalable web applications, APIs, CRM, ERP, and eCommerce solutions.",
        "email" => "mailto:$email",
        "telephone" => $phone,
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => $city,
            "addressRegion" => $state,
            "addressCountry" => $country
        ],
        "sameAs" => [
            config('social.github') ?? 'https://github.com/govind663',
            config('social.linkedin') ?? 'https://www.linkedin.com/in/abhishek-laravel-developer/',
            config('social.facebook') ?? 'https://www.facebook.com/abhishek.govind.jha',
            config('social.instagram') ?? 'https://www.instagram.com/abhivom108/'
        ],
        "knowsAbout" => [
            "Laravel",
            "PHP",
            "API Development",
            "E-commerce Development",
            "CRM",
            "ERP",
            "Payment Gateway Integration"
        ],
        "contactPoint" => [
            "@type" => "ContactPoint",
            "telephone" => $phone,
            "contactType" => "customer support",
            "areaServed" => "IN",
            "availableLanguage" => ["English", "Hindi"]
        ]
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>