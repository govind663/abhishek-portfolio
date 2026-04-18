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

            {{-- 🚀 Responsive + Correct Image Loading --}}
            <img 
                src="{{ $logoPath }}" 

                {{-- ✅ REAL responsive sizes --}}
                srcset="
                    {{ $logoPath }} 120w,
                    {{ $logoPath }} 200w,
                    {{ $logoPath }} 300w
                "
                sizes="(max-width: 768px) 120px, (max-width: 1200px) 150px, 200px"

                alt="{{ $brand->title ?? 'Website Logo' }}" 
                title="{{ $brand->title ?? 'Portfolio Logo' }}"

                width="200"
                height="100"

                {{-- 🚀 Mobile optimization --}}
                loading="eager"
                fetchpriority="high"
                decoding="async"

                style="max-height: 100px; width: auto;">
        </a>

        {{-- ===== Navigation ===== --}}
        <nav id="navmenu" class="navmenu">
            <ul>

                <li>
                    <a href="{{ route('frontend.home') }}"
                       title="Home Page"
                       class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                        <b>Home</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.about') }}"
                       title="About Abhishek - Laravel Developer"
                       class="{{ request()->routeIs('frontend.about') ? 'active' : '' }}">
                        <b>About Us</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.resume') }}"
                       title="View Resume"
                       class="{{ request()->routeIs('frontend.resume') ? 'active' : '' }}">
                        <b>Resume</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.services.list') }}"
                       title="Laravel Development Services"
                       class="{{ request()->routeIs('frontend.services.*') ? 'active' : '' }}">
                        <b>Services</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.portfolio.list') }}"
                       title="Portfolio Projects"
                       class="{{ request()->routeIs('frontend.portfolio.*') ? 'active' : '' }}">
                        <b>Portfolio</b>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.contact') }}"
                       title="Contact Abhishek"
                       class="{{ request()->routeIs('frontend.contact') ? 'active' : '' }}">
                        <b>Contact Us</b>
                    </a>
                </li>

            </ul>

            {{-- Mobile Toggle --}}
            <i class="mobile-nav-toggle d-xl-none bi bi-list" aria-label="Toggle Menu"></i>
        </nav>

    </div>
</header>