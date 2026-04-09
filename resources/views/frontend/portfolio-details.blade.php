@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Portfolio Details
@endsection

@section('meta_description')
Detailed information about the selected portfolio will be displayed here.
@endsection

@section('meta_keywords')
Portfolio Details, Project Showcase, Web Development Portfolio, Abhishek Jha Portfolio, Laravel Portfolio,
Web Application Showcase, Full Stack Developer Portfolio, PHP Portfolio, API Portfolio, E-commerce Portfolio,
HRMS Portfolio, CRM Portfolio, ERP Portfolio, Payment Gateway Integration Portfolio.
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
                        <h1>Portfolio Details</h1>
                        <p class="mb-0" style="text-align: justify !important; font-size: 20px;">
                            Detailed information about the selected portfolio will be displayed here.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                    <li><a href="{{ route('frontend.portfolio') }}" title="Portfolio">Portfolio</a></li>
                    <li class="current">Portfolio Details</li>
                </ol>
            </div>
        </nav>
    </div>
    <!-- End Page Title -->
    <!-- Portfolio Details Section -->
    <section id="portfolio-details" class="portfolio-details section">
    </section>
    <!-- End Portfolio Details Section -->
@endsection 

@push('scripts')
@endpush