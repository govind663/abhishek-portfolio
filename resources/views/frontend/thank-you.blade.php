@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Thank You
@endsection

@section('meta_description')
Thank you for contacting Abhishek Jha. Your message has been received. I will get back to you shortly.
@endsection

@section('meta_keywords')
Thank You, Contact Success, Form Submitted, Abhishek Jha Thank You Page
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
<style>
    .under-const-sec {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        /* padding: 20px; */
        background-color: #000;
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
        transition: background 0.3s ease;
    }

    #button-2 #slide {
        position: absolute;
        width: 100%;
        height: 100%;
        background: #0056b3;
        top: 0;
        left: -100%;
        transition: left 0.3s;
        z-index: 0;
    }

    #button-2:hover #slide {
        left: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .under-const-content-sec h1 {
            font-size: 2rem;
        }

        .under-const-content-sec p {
            font-size: 1rem;
        }

        #button-2 a {
            padding: 10px 24px;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .under-const-content-sec h1 {
            font-size: 1.6rem;
        }

        .under-const-content-sec p {
            font-size: 0.95rem;
        }

        #button-2 a {
            padding: 8px 20px;
            font-size: 0.9rem;
        }
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
                        <p class="mb-0" style="text-align: justify !important;">
                            Odio et unde deleniti. Deserunt numquam exercitationem. Officiis quo odio sint
                            voluptas consequatur ut a odio voluptatem. Sit dolorum debitis veritatis natus
                            dolores. Quasi ratione sint. Sit quaerat ipsum dolorem.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('frontend.home') }}">Home</a></li>
                    <li class="current">Thak You</li>
                </ol>
            </div>
        </nav>
    </div>
    <!-- End Page Title -->

    <div class="under-const-sec">
        <div class="under-const-content-sec">
            <img src="{{ asset('frontend/assets/img/Thank_You.webp') }}" alt="Thank You">
            <h1>Thank You</h1>
            <p>For any inquiries or updates, please feel free to contact us.</p>
            <div class="button" id="button-2">
                <div id="slide"></div>
                <a href="{{ route('frontend.home') }}">Back to Home</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
