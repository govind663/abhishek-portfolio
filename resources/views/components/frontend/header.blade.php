<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        {{-- ===== Logo (Optimized + Cached) ===== --}}
        @php
            use Illuminate\Support\Facades\Cache;
            use App\Models\BrandDescription;

            $brand = Cache::remember('brand_logo', 60 * 60, function () {
                return BrandDescription::active()->latestId()->first();
            });

            $logoPath = $brand && $brand->logo
                ? asset('storage/' . $brand->logo)
                : asset('/backend/assets/img/logo/abhishek-potfolio_black_logo.webp');
        @endphp

        <a href="{{ route('frontend.home') }}" 
           class="logo d-flex align-items-center" 
           title="{{ $brand->title ?? 'Home Page' }}"
           aria-label="Go to homepage">

            {{-- 🚀 FINAL OPTIMIZED LOGO --}}
            <img 
                src="{{ $logoPath }}" 

                {{-- ✅ Better responsive (mobile-first) --}}
                srcset="
                    {{ $logoPath }} 120w,
                    {{ $logoPath }} 200w,
                    {{ $logoPath }} 300w
                "
                sizes="(max-width: 576px) 100px, (max-width: 992px) 140px, 180px"

                alt="{{ $brand->title ?? 'Website Logo' }}" 
                title="{{ $brand->title ?? 'Portfolio Logo' }}"

                width="180"
                height="80"

                {{-- 🚀 LCP optimization --}}
                loading="eager"
                fetchpriority="high"
                decoding="async"

                style="max-height: 80px; width: auto;">
        </a>

        {{-- ===== Navigation ===== --}}
        <nav id="navmenu" class="navmenu" role="navigation" aria-label="Main Navigation">
            <ul>

                <li>
                    <a href="{{ route('frontend.home') }}"
                       title="Home Page"
                       aria-label="Go to Home Page"
                       class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                        <b>Home</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.about') }}"
                       title="About Abhishek - Laravel Developer"
                       aria-label="About Abhishek"
                       class="{{ request()->routeIs('frontend.about') ? 'active' : '' }}">
                        <b>About Us</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.resume') }}"
                       title="View Resume"
                       aria-label="View Resume"
                       class="{{ request()->routeIs('frontend.resume') ? 'active' : '' }}">
                        <b>Resume</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.services.list') }}"
                       title="Laravel Development Services"
                       aria-label="View Services"
                       class="{{ request()->routeIs('frontend.services.*') ? 'active' : '' }}">
                        <b>Services</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.portfolio.list') }}"
                       title="Portfolio Projects"
                       aria-label="View Portfolio"
                       class="{{ request()->routeIs('frontend.portfolio.*') ? 'active' : '' }}">
                        <b>Portfolio</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.contact') }}"
                       title="Contact Abhishek"
                       aria-label="Contact Page"
                       class="{{ request()->routeIs('frontend.contact') ? 'active' : '' }}">
                        <b>Contact Us</b>
                    </a>
                </li>

            </ul>

            {{-- 🚀 Mobile Toggle (Accessibility fix) --}}
            <button 
                class="mobile-nav-toggle d-xl-none bi bi-list"
                aria-label="Toggle Navigation Menu"
                aria-expanded="false"
                type="button">
            </button>
        </nav>

    </div>
</header>