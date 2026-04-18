@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Thank You
@endsection

@section('meta_description')
Thank you for contacting Abhishek Jha. Your message has been received successfully. I will get back to you shortly.
@endsection

@section('meta_keywords')
Thank You Page, Contact Success, Form Submitted, Abhishek Jha
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
    min-height: 100vh;
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

                    <h1>Thank You</h1>

                    <p class="mb-0 text-justify">
                        Your message has been successfully submitted. I appreciate you reaching out and will get back to you as soon as possible.
                    </p>

                </div>
            </div>
        </div>
    </div>

    <nav class="breadcrumbs" aria-label="breadcrumb">
        <div class="container">
            <ol>
                <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                <li class="current">Thank You</li>
            </ol>
        </div>
    </nav>
</div>
<!-- End Page Title -->

<div class="under-const-sec">
    <div class="under-const-content-sec">

        <img 
            src="{{ asset('frontend/assets/img/Thank_You.webp') }}" 
            alt="Thank You Message"
            loading="lazy"
            width="500"
            height="400"
        >

        <h2>Thank You</h2>

        <p>
            For any further queries or updates, feel free to get in touch anytime.
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