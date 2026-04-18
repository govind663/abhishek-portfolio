@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Under Construction
@endsection

@section('meta_description')
This page is currently under construction. Stay tuned for updates from Abhishek Jha.
@endsection

@section('meta_keywords')
Under Construction Page, Website Maintenance, Coming Soon, Abhishek Jha
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
<style>
.text-justify { text-align: justify; }

.under-const-sec {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
    background: #000;
    color: #fff;
    text-align: center;
}

.under-const-content-sec {
    max-width: 600px;
    width: 100%;
}

.under-const-content-sec img {
    max-width: 100%;
    height: auto;
    margin-bottom: 20px;
}

.under-const-content-sec h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.under-const-content-sec p {
    font-size: 1.2rem;
    margin-bottom: 30px;
}

#button-2 {
    position: relative;
    display: inline-block;
    overflow: hidden;
    border-radius: 5px;
}

#button-2 a {
    position: relative;
    z-index: 1;
    padding: 12px 30px;
    background: #0b984a;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
}

#button-2 #slide {
    position: absolute;
    width: 100%;
    height: 100%;
    background: #0056b3;
    top: 0;
    left: -100%;
    transition: left 0.3s;
}

#button-2:hover #slide {
    left: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .under-const-content-sec h1 { font-size: 2rem; }
    .under-const-content-sec p { font-size: 1rem; }
}

@media (max-width: 480px) {
    .under-const-content-sec h1 { font-size: 1.6rem; }
    .under-const-content-sec p { font-size: 0.95rem; }
}
</style>
@endpush

@section('content')

<!-- Page Title -->
<div class="page-title" data-aos="fade">
    <div class="heading">
        <div class="container">
            <div class="row d-flex justify-content-center text-center">
                <div class="col-lg-8">

                    <h1>Under Construction</h1>

                    <p class="mb-0 text-justify">
                        This page is currently under development. Please check back soon for updates and new content.
                    </p>

                </div>
            </div>
        </div>
    </div>

    <nav class="breadcrumbs" aria-label="breadcrumb">
        <div class="container">
            <ol>
                <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                <li class="current">Under Construction</li>
            </ol>
        </div>
    </nav>
</div>
<!-- End Page Title -->

<div class="under-const-sec">
    <div class="under-const-content-sec">

        <img 
            src="{{ asset('frontend/assets/img/Under_Construction.webp') }}" 
            alt="Under Construction Page"
            loading="lazy"
            width="500"
            height="400"
        >

        <h2>Page Under Construction</h2>

        <p>
            This page is currently being worked on. Please check back soon!
        </p>

        <div id="button-2">
            <div id="slide"></div>
            <a href="{{ route('frontend.home') }}" title="Go Back Home">Back to Home</a>
        </div>

    </div>
</div>

@endsection

@push('scripts')
@endpush