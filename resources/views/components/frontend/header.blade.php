<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        {{-- ===== Logo (Optimized + Cached) ===== --}}
        @php
            use Illuminate\Support\Facades\Cache;
            use App\Models\BrandDescription;

            $brand = Cache::remember('brand_logo', 60 * 60, function () {
                return BrandDescription::active()->latestId()->first();
            });
        @endphp

        <a href="{{ route('frontend.home') }}" 
        class="logo d-flex align-items-center" 
        title="{{ $brand->title ?? 'Home Page' }}"
        aria-label="Go to homepage">

            @if($brand && $brand->logo)
                <img 
                    src="{{ asset('storage/' . $brand->logo) }}" 
                    alt="{{ $brand->title ?? 'Website Logo' }}" 
                    title="{{ $brand->title ?? 'Portfolio Logo' }}"
                    width="200"
                    height="100"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                    style="max-height: 100px; width: 200px;">
            @else
                <img 
                    src="{{ asset('/backend/assets/img/logo/abhishek-potfolio_black_logo.webp') }}" 
                    alt="Default Website Logo" 
                    title="Default Portfolio Logo"
                    width="200"
                    height="100"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                    style="max-height: 100px; width: 200px;">
            @endif

        </a>

        {{-- ===== Navigation ===== --}}
        <nav id="navmenu" class="navmenu">
            <ul>

                {{-- Home --}}
                <li>
                    <a href="{{ route('frontend.home') }}"
                       title="Home Page"
                       class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                        <b>Home</b>
                    </a>
                </li>

                {{-- About --}}
                <li>
                    <a href="{{ route('frontend.about') }}"
                       title="About Abhishek - Laravel Developer"
                       class="{{ request()->routeIs('frontend.about') ? 'active' : '' }}">
                        <b>About Us</b>
                    </a>
                </li>

                {{-- Resume --}}
                <li>
                    <a href="{{ route('frontend.resume') }}"
                       title="View Resume"
                       class="{{ request()->routeIs('frontend.resume') ? 'active' : '' }}">
                        <b>Resume</b>
                    </a>
                </li>

                {{-- Services --}}
                <li>
                    <a href="{{ route('frontend.services.list') }}"
                       title="Laravel Development Services"
                       class="{{ request()->routeIs('frontend.services.*') ? 'active' : '' }}">
                        <b>Services</b>
                    </a>
                </li>

                {{-- Portfolio --}}
                <li>
                    <a href="{{ route('frontend.portfolio.list') }}"
                       title="Portfolio Projects"
                       class="{{ request()->routeIs('frontend.portfolio.*') ? 'active' : '' }}">
                        <b>Portfolio</b>
                    </a>
                </li>

                {{-- Contact --}}
                <li>
                    <a href="{{ route('frontend.contact') }}"
                       title="Contact Abhishek"
                       class="{{ request()->routeIs('frontend.contact') ? 'active' : '' }}">
                        <b>Contact Us</b>
                    </a>
                </li>

            </ul>

            {{-- Mobile Toggle --}}
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

    </div>
</header>