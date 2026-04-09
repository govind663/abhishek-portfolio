@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Portfolio
@endsection

@section('meta_description')
Browse the portfolio of Abhishek Jha — showcasing real world Laravel projects, HRMS, E-commerce platforms, 
CMS dashboards, API integrations, and enterprise level web applications.
@endsection

@section('meta_keywords')
Abhishek Portfolio, Web Development Projects, Laravel Projects, PHP Projects, HRMS Portfolio,
CRM Project Developer, E-commerce Portfolio, Full Stack Developer Works
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
@endpush

@section('content')
    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>My Work Portfolio</h1>
                        <p class="mb-0" style="text-align: justify !important; font-size: 20px;">
                            A showcase of professional projects I’ve worked on — including web applications,
                            mobile apps, branding designs, dashboards, product websites, and more.
                            Each project reflects my experience as a Freelance Full-Stack Developer with
                            a focus on clean UI, optimized performance, and scalable development.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                    <li class="current">Portfolio</li>
                </ol>
            </div>
        </nav>
    </div>
    <!-- End Page Title -->

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section">

        <div class="container">

            <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                    <li data-filter="*" class="filter-active">All Projects</li>
                    <li data-filter=".filter-app">Web Apps</li>
                    <li data-filter=".filter-product">Product Sites</li>
                    <li data-filter=".filter-branding">Branding</li>
                    <li data-filter=".filter-books">Case Studies</li>
                </ul>
                <!-- End Portfolio Filters -->

                <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                    <!-- Web App Project -->
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-1.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Appointment Booking Web App</h4>
                                <p>Complete web-based booking system with admin dashboard.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-1.webp') }}"
                                   title="Appointment Booking App"
                                   data-gallery="portfolio-gallery-app"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html"
                                   title="More Details"
                                   class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Product Website -->
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-10.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>E-Commerce Product Page</h4>
                                <p>Modern product display page with filtering & dynamic gallery.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-10.webp') }}"
                                   title="E-commerce Product"
                                   data-gallery="portfolio-gallery-product"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html" class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Branding Work -->
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-7.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding Kit & Logo Design</h4>
                                <p>Professional brand identity with icons, typography & color palette.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-7.webp') }}"
                                   title="Branding Kit"
                                   data-gallery="portfolio-gallery-branding"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html" class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Case Study -->
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-4.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Project Case Study</h4>
                                <p>Detailed breakdown of UI/UX decisions & technology stack.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-4.webp') }}"
                                   title="Case Study"
                                   data-gallery="portfolio-gallery-book"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html" class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Repeat Pattern (Changed Titles + Descriptions) -->
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-2.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>CRM Dashboard</h4>
                                <p>Custom CRM dashboard with analytics & role permissions.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-2.webp') }}"
                                   data-gallery="portfolio-gallery-app"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html" class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- You can continue same pattern for all remaining items (kept visually same) -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-11.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Landing Page UI</h4>
                                <p>High-conversion responsive landing page.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-11.webp') }}"
                                   data-gallery="portfolio-gallery-product"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html" class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-8.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Corporate Branding</h4>
                                <p>Logo + typography + business card design.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-8.webp') }}"
                                   data-gallery="portfolio-gallery-branding"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html" class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('frontend/assets/img/portfolio/portfolio-5.webp') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>UI/UX Wireframe Case Study</h4>
                                <p>Wireframe to final UI transformation.</p>
                                <a href="{{ asset('frontend/assets/img/portfolio/portfolio-5.webp') }}"
                                   data-gallery="portfolio-gallery-book"
                                   class="glightbox preview-link">
                                   <i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="portfolio-details.html" class="details-link">
                                   <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Continue updating similarly... -->

                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
{{-- Include imagesloaded.js --}}
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

{{-- Include Isotope.js --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

    let grid = document.querySelector('.isotope-container');

    imagesLoaded(grid, function () {

        let iso = new Isotope(grid, {
            itemSelector: '.portfolio-item',
            layoutMode: 'masonry'
        });

    });

});
</script>
@endpush
