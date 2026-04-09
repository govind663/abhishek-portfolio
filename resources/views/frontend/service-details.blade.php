@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Service Details
@endsection

@section('meta_description')
Detailed information about our professional web development services by Abhishek Jha,
including Laravel development, REST APIs, Admin Panels, E-commerce Systems, HRMS, CRM,
and custom web applications.
@endsection

@section('meta_keywords')
Laravel Development Services, API Development, Website Development, E-commerce Developer, 
Custom Web App Services, Abhishek Jha Services, PHP Developer Services
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
                        <h1>Service Details</h1>
                        <p class="mb-0" style="text-align: justify !important; font-size: 20px;">
                            Detailed information about the selected service will be displayed here.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                    <li><a href="{{ route('frontend.services') }}" title="Services">Services</a></li>
                    <li class="current">Service Details</li>
                </ol>
            </div>
        </nav>
    </div>
    <!-- End Page Title -->
    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">
    </section>
    <!-- End Service Details Section -->
@endsection

@push('scripts')
@endpush