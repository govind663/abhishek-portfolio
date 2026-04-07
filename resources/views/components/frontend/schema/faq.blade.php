@php
    $email = config('site.email') ?? 'codingthunder1997@gmail.com';
    $phone = config('site.phone') ?? '+919004763926';

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "FAQPage",
        "mainEntity" => [
            [
                "@type" => "Question",
                "name" => "Who is Abhishek Jha?",
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => "Abhishek Jha is a Full Stack Laravel Developer with 4+ years of experience."
                ]
            ],
            [
                "@type" => "Question",
                "name" => "What services do you offer?",
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => "Laravel development, API development, CRM, ERP, eCommerce solutions."
                ]
            ],
            [
                "@type" => "Question",
                "name" => "How can I contact you?",
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => "Email: $email | Phone: $phone"
                ]
            ]
        ]
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>