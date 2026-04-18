{{-- Footer CSS (non-blocking) --}}
<link rel="stylesheet" href="{{ asset('frontend/assets/css/footer.css') }}" media="print" onload="this.media='all'">
<noscript>
<link rel="stylesheet" href="{{ asset('frontend/assets/css/footer.css') }}">
</noscript>

<footer id="footer" class="footer dark-background py-5 text-white" role="contentinfo">

    <div id="bubbles" aria-hidden="true"></div>

    @php
    use Illuminate\Support\Facades\Cache;
    use App\Models\BrandDescription;
    use App\Models\ContactInfo;
    use App\Models\SocialLink;
    use App\Models\Copyright;

    $brand = Cache::remember('brand_description', 3600, function () {
        return BrandDescription::active()->latestId()->first();
    });

    $contact = Cache::remember('contact_info', 3600, function () {
        return ContactInfo::active()->latestId()->first();
    });

    $socialLinks = Cache::remember('social_links', 3600, function () {
        return SocialLink::active()->latestId()->get();
    });

    $copyright = Cache::remember('copyright', 3600, function () {
        return Copyright::active()->latestId()->first();
    });

    $logoPath = $brand && $brand->logo
        ? asset('storage/' . $brand->logo)
        : asset('/backend/assets/img/logo/default-logo.png');

    $defaultColors = [
        'GitHub' => 'rgb(205, 79, 1)',
        'GitLab' => 'rgb(205, 79, 1)',
        'Facebook' => 'rgb(5, 93, 193)',
        'Instagram' => 'rgb(243, 16, 122)',
        'LinkedIn' => 'rgb(70, 70, 237)',
    ];
    @endphp

    <div class="container">
        <div class="row text-start">

            <!-- Logo -->
            <div class="col-md-3 mb-4 footer-logo">

                <a href="{{ route('frontend.home') }}" 
                   class="logo d-inline-block mb-3" 
                   title="Home Page"
                   aria-label="Go to homepage">

                    <img 
                        src="{{ $logoPath }}" 

                        {{-- Better responsive --}}
                        srcset="
                            {{ $logoPath }} 120w,
                            {{ $logoPath }} 200w,
                            {{ $logoPath }} 300w
                        "
                        sizes="(max-width: 768px) 120px, 180px"

                        alt="{{ $brand->title ?? 'Website Logo' }}" 
                        title="{{ $brand->title ?? 'Logo' }}"
                        width="180"
                        height="70"
                        loading="lazy"
                        decoding="async"
                        style="max-height: 70px; width: auto;">
                </a>

                <p style="text-align: justify; font-size: 16px;">
                    {{ $brand->description ?? 'I create smart, scalable, and user-focused web solutions.' }}
                </p>

            </div>

            <!-- Quick Links -->
            <div class="col-md-2 mb-4 quick-links">
                <h5 class="mb-3" style="font-size:20px; font-weight:600;">Quick Links</h5>

                <ul class="list-unstyled">
                    <li><a href="{{ route('frontend.home') }}" aria-label="Home Page">Home</a></li>
                    <li><a href="{{ route('frontend.about') }}" aria-label="About Page">About Us</a></li>
                    <li><a href="{{ route('frontend.resume') }}" aria-label="Resume Page">Resume</a></li>
                    <li><a href="{{ route('frontend.services.list') }}" aria-label="Services Page">Services</a></li>
                    <li><a href="{{ route('frontend.portfolio.list') }}" aria-label="Portfolio Page">Portfolio</a></li>
                    <li><a href="{{ route('frontend.contact') }}" aria-label="Contact Page">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            @if($contact)
            <div class="col-md-4 mb-4 contact-information" itemscope itemtype="https://schema.org/Person">

                <h5 class="mb-3" style="font-size:20px; font-weight:600;">Contact Information</h5>

                @if($contact->phone)
                <p>
                    <i class="bi bi-telephone-fill me-1"></i>
                    <a href="tel:{{ $contact->phone }}" aria-label="Call {{ $contact->phone }}">
                        {{ $contact->phone }}
                    </a>
                </p>
                @endif

                @if($contact->email)
                <p>
                    <i class="bi bi-envelope-fill me-1"></i>
                    <a href="mailto:{{ $contact->email }}" aria-label="Email {{ $contact->email }}">
                        {{ $contact->email }}
                    </a>
                </p>
                @endif

                @if($contact->address)
                <p>
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    {{ $contact->address }}
                </p>
                @endif

                @if($contact->working_hours)
                <p>
                    <i class="bi bi-clock-fill me-1"></i>
                    {{ $contact->working_hours }}
                </p>
                @endif

            </div>
            @endif

            <!-- Social Icons -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3" style="font-size:20px; font-weight:600;">Social Profiles</h5>

                <div class="social-links">
                    @forelse($socialLinks as $social)

                    @php
                    $platformKey = str_replace(' Profile', '', $social->platform);
                    $iconColor = $social->color ?? ($defaultColors[$platformKey] ?? 'inherit');
                    @endphp

                    <a href="{{ $social->url ?? '#' }}" 
                       target="_blank" 
                       rel="noopener noreferrer nofollow"
                       aria-label="Visit {{ $social->platform }}">

                        <i class="{{ $social->icon ?? 'bi bi-link' }}" 
                           style="color: {{ $iconColor }} !important;"></i>
                    </a>

                    @empty
                    <p>No social links found.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <hr class="border-top border-light">

        <!-- Copyright -->
        <div class="text-center text-bold pt-3">
            @if($copyright && $copyright->copyright_text)
                {!! str_replace('{year}', date('Y'), $copyright->copyright_text) !!}
            @else
                © {{ date('Y') }} Abhishek Jha | Designed By
                <a href="{{ route('frontend.home') }}" class="text-success" aria-label="Go to Home Page">
                    <b>Coding Thunder</b>
                </a>.
                All Rights Reserved
            @endif
        </div>
    </div>
</footer>

{{-- 🚀 Optimized Bubble Script (CPU SAFE) --}}
<script defer>
document.addEventListener("DOMContentLoaded", function () {

    const bubbleContainer = document.getElementById('bubbles');

    // ❌ mobile पर animation बंद (performance boost)
    if (window.innerWidth < 768) return;

    function createBubble() {
        const bubble = document.createElement('div');
        bubble.classList.add('bubble');

        const size = Math.random() * 15 + 8 + 'px';
        bubble.style.width = size;
        bubble.style.height = size;
        bubble.style.left = Math.random() * 100 + '%';
        bubble.style.animationDuration = Math.random() * 4 + 4 + 's';
        bubble.style.opacity = Math.random();

        bubbleContainer.appendChild(bubble);
        setTimeout(() => bubble.remove(), 8000);
    }

    // 🚀 कम bubbles = कम CPU usage
    setInterval(createBubble, 800);
});
</script>