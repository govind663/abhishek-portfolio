{{-- Footer CSS --}}
<link rel="stylesheet" href="{{ asset('frontend/assets/css/footer.css') }}">

<footer id="footer" class="footer dark-background py-5 text-white">

    <div id="bubbles"></div>

    @php
    use Illuminate\Support\Facades\Cache;
    use App\Models\BrandDescription;
    use App\Models\ContactInfo;
    use App\Models\SocialLink;
    use App\Models\Copyright;

    // ✅ Cache Data
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

    // Default colors
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

                <a href="{{ route('frontend.home') }}" class="logo d-inline-block mb-3" title="Home Page">

                    @if($brand && $brand->logo)
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="logo" title="{{ $brand->title }}" style="max-height: 75px; width: 200px;">
                    @else
                    <img src="{{ asset('/backend/assets/img/logo/default-logo.png') }}" alt="logo" title="logo" style="max-height: 75px; width: 200px;">
                    @endif

                </a>

                <p style="text-align: justify; font-size: 19px;">
                    {{ $brand->description ?? 'I create smart, scalable, and user-focused web solutions.' }}
                </p>

            </div>

            <!-- Quick Links -->
            <div class="col-md-2 mb-4 quick-links">

                <h5 class="mb-3" style="font-size:23px; font-weight:600;">Quick Links</h5>

                <ul class="list-unstyled">

                    <li><a href="{{ route('frontend.home') }}" title="Go to Home Page">Home</a></li>
                    <li><a href="{{ route('frontend.about') }}" title="Learn more about us">About Us</a></li>
                    <li><a href="{{ route('frontend.resume') }}" title="Download our resume">Resume</a></li>
                    <li><a href="{{ route('frontend.services.list') }}" title="Explore our services">Services</a></li>
                    <li><a href="{{ route('frontend.portfolio.list') }}" title="View our portfolio">Portfolio</a></li>
                    <li><a href="{{ route('frontend.contact') }}" title="Get in touch with us">Contact Us</a></li>

                </ul>

            </div>

            <!-- Contact Info -->
            @if($contact)
            <div class="col-md-4 mb-4 contact-information" itemscope itemtype="https://schema.org/Person">

                <h5 class="mb-3" style="font-size:23px; font-weight:600;">Contact Information</h5>

                @if($contact->phone)
                <p>
                    <i class="bi bi-telephone-fill me-1"></i>
                    <a href="tel:{{ $contact->phone }}" itemprop="telephone" title="Call {{ $contact->phone }}">
                        {{ $contact->phone }}
                    </a>
                </p>
                @endif

                @if($contact->email)
                <p>
                    <i class="bi bi-envelope-fill me-1"></i>
                    <a href="mailto:{{ $contact->email }}" itemprop="email" title="Email {{ $contact->email }}">
                        {{ $contact->email }}
                    </a>
                </p>
                @endif

                @if($contact->address)
                <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    <span itemprop="streetAddress">{{ $contact->address }}</span>
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
                <h5 class="mb-3" style="font-size: 23px; font-weight: 600;">Social Profiles</h5>

                <div class="social-links">
                    @forelse($socialLinks as $social)

                    @php
                    $platformKey = str_replace(' Profile', '', $social->platform);
                    $iconColor = $social->color ?? ($defaultColors[$platformKey] ?? 'inherit');
                    @endphp

                    <a href="{{ $social->url ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social->platform ?? 'Social Link' }}">
                        <i class="{{ $social->icon ?? 'bi bi-link' }}" style="color: {{ $iconColor }} !important;"></i>
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
            <a href="{{ route('frontend.home') }}" class="text-success" title="Go to Home Page">
                <b>Coding Thunder</b>
            </a>.
            All Rights Reserved
            @endif

        </div>
    </div>
</footer>

<script>
    const bubbleContainer = document.getElementById('bubbles');

    function createBubble() {
        const bubble = document.createElement('div');
        bubble.classList.add('bubble');

        const size = Math.random() * 20 + 10 + 'px';
        bubble.style.width = size;
        bubble.style.height = size;
        bubble.style.left = Math.random() * 100 + '%';
        bubble.style.animationDuration = Math.random() * 5 + 5 + 's';
        bubble.style.opacity = Math.random();

        bubbleContainer.appendChild(bubble);

        setTimeout(() => bubble.remove(), 10000);
    }

    setInterval(createBubble, 300);

</script>

