@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Contact Us
@endsection

@section('meta_description')
Contact Abhishek Jha for professional Laravel development, API development, full-stack solutions, and high-quality web application development services.
@endsection

@section('meta_keywords')
Contact Abhishek, Hire Laravel Developer, Hire PHP Developer, Hire Web Developer, Hire Full Stack Developer, Developer Contact Form, Work With Abhishek
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
<style>
.text-justify { text-align: justify; }

/* Input + Textarea */
.enhanced-contact-form input[type=text],
.enhanced-contact-form input[type=email],
.enhanced-contact-form textarea {
    color: var(--default-color);
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.25);
    font-size: 15px;
    padding: 12px 15px 12px 45px;
    border-radius: 10px !important;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    width: 100%;
}

/* Icon */
.form-icon {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    font-size: 18px;
    color: #b7b7b7;
}

/* Focus */
.enhanced-contact-form input:focus,
.enhanced-contact-form textarea:focus {
    border-color: #4e73df;
    box-shadow: 0 0 10px rgba(78, 115, 223, 0.45);
}

/* Button */
.contact-btn {
    background: #18d26e;
    color: #fff;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.contact-btn:hover {
    transform: translateY(-2px);
}

/* Validation */
.invalid-feedback {
    display: block !important;
    font-size: 14px;
    color: #fff !important;
}

.form-control { line-height: 1.8; }

/* Map */
.map-responsive {
    position: relative;
    height: 400px;
    border-radius: 12px;
    overflow: hidden;
}

.map-responsive iframe {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 0;
}
</style>
@endpush

@section('content')

{{-- Start Page Title --}}
<div class="page-title" data-aos="fade">
    <div class="heading">
        <div class="container">
            <div class="row d-flex justify-content-center text-center">
                <div class="col-lg-8">

                    <h1>Contact Us</h1>

                    <p class="mb-0 text-justify fs-5">
                        If you have any questions, suggestions, or inquiries, don’t hesitate to contact me. 
                        I would love to hear from you and provide the best possible support. 
                        Feel free to reach out using the contact details below or the contact form.
                    </p>

                </div>
            </div>
        </div>
    </div>

    <nav class="breadcrumbs" aria-label="breadcrumb">
        <div class="container">
            <ol>
                <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                <li class="current">Contact Us</li>
            </ol>
        </div>
    </nav>
</div>
{{-- End Page Title --}}

{{-- Start Contact Section --}}
<section id="contact" class="contact section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

            <div class="col-md-6">
                <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                    <i class="icon bi bi-geo-alt"></i>
                    <div>
                        <h2>Address</h2>
                        <p>Kamothe, Navi Mumbai, Maharashtra, India.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="300">
                    <i class="icon bi bi-telephone"></i>
                    <div>
                        <h2>Call Me</h2>
                        <p><a href="tel:+919004763926" class="text-white">(+91) 9004763926</a></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="400">
                    <i class="icon bi bi-envelope"></i>
                    <div>
                        <h2>Email</h2>
                        <p><a href="mailto:codingthunder1997@gmail.com" class="text-white">codingthunder1997@gmail.com</a></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="500">
                    <i class="icon bi bi-share"></i>
                    <div>
                        <h2>Social</h2>
                        <div class="social-links">
                            <a href="#"><i class="bi bi-gitlab"></i></a>
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Contact Form Section --}}
        @include('partials.contact-form')

        {{-- Google Map Section --}}
        <div class="row mt-5" data-aos="fade-up">
            <div class="col-lg-12">

                <h2 class="mb-4">My Location</h2>

                <div class="map-responsive">
                    <iframe
                        src="https://www.google.com/maps?q=Mansarovar%20Complex%20Sector%2034%20Kamothe%20Panvel%20Navi%20Mumbai%20410209&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </div>

    </div>

</section>

@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush