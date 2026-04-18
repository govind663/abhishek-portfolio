@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Services
@endsection

@section('meta_description')
Professional web development services by Abhishek Jha — including Laravel development, REST APIs, Admin Panels, E-commerce Systems, HRMS, CRM, and custom web applications.
@endsection

@section('meta_keywords')
Laravel Development Services, API Development, Website Development, E-commerce Developer, Custom Web App Services, PHP Developer Services
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
<style>
.text-justify { text-align: justify; }
</style>
@endpush

@section('content')

<!-- Page Title -->
<div class="page-title" data-aos="fade">
    <div class="heading">
        <div class="container">
            <div class="row d-flex justify-content-center text-center">
                <div class="col-lg-8">
                    <h1>Services</h1>

                    <p class="mb-0 text-justify fs-5">
                        I provide professional web development services specializing in PHP, Laravel,
                        custom web applications, REST APIs, admin dashboards, CMS systems, and full-stack 
                        scalable solutions. My goal is to deliver clean, secure, high-performance applications
                        tailored to your business needs.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <nav class="breadcrumbs" aria-label="breadcrumb">
        <div class="container">
            <ol>
                <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                <li class="current">Services</li>
            </ol>
        </div>
    </nav>
</div>
<!-- End Page Title -->

<!-- Services Section -->
<section id="services" class="services section">

    <div class="container">
        <div class="row gy-4">

            <!-- Laravel -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="bi bi-code-slash"></i>
                    </div>

                    <h2>Laravel Web Development</h2>

                    <p class="text-justify">
                        High-performance, secure, and scalable Laravel applications including custom modules, 
                        backend systems, authentication, roles/permissions, and integrated business workflows.
                    </p>
                </div>
            </div>

            <!-- API -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>

                    <h2>REST API Development</h2>

                    <p class="text-justify">
                        Creation of fast, secure, and well-structured REST APIs for mobile apps, 
                        third-party systems, and microservice architecture including token-based authentication.
                    </p>
                </div>
            </div>

            <!-- Ecommerce -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="bi bi-cart-check"></i>
                    </div>

                    <h2>E-commerce Application Development</h2>

                    <p class="text-justify">
                        Full-featured e-commerce platforms including product management, cart, checkout,
                        order management, coupons, stock control, and payment gateway integration.
                    </p>
                </div>
            </div>

            <!-- Admin -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>

                    <h2>Custom Admin Panel & CMS</h2>

                    <p class="text-justify">
                        Powerful, user-friendly admin dashboards tailored to business workflows, 
                        including analytics, reports, role-based access, and automated processes.
                    </p>
                </div>
            </div>

            <!-- Payment -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="bi bi-credit-card"></i>
                    </div>

                    <h2>Payment Gateway Integration</h2>

                    <p class="text-justify">
                        Integration with Razorpay, PayPal, Stripe, Easebuzz, Paytm and all major payment platforms 
                        with secure transaction flow and webhook handling.
                    </p>
                </div>
            </div>

            <!-- Optimization -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="service-item position-relative">
                    <div class="icon">
                        <i class="bi bi-bug"></i>
                    </div>

                    <h2>Bug Fixing & Performance Optimization</h2>

                    <p class="text-justify">
                        Fixing complex backend issues, SQL optimization, API performance tuning, security updates, 
                        migration fixes, and enhancing overall system reliability.
                    </p>
                </div>
            </div>

        </div>
    </div>

</section>
<!-- /Services Section -->

@endsection

@push('scripts')
@endpush