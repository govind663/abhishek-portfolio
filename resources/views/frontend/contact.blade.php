@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Contact Us
@endsection

@section('meta_description')
Contact Abhishek Jha for professional Laravel development, API development, full-stack solutions, 
and high-quality web application development services.
@endsection

@section('meta_keywords')
Contact Abhishek, Hire Laravel Developer, Hire PHP Developer, Hire Web Developer, 
Hire Full Stack Developer, Developer Contact Form, Work With Abhishek
@endsection

@section('canonical')
{{ url()->current() }}
@endsection

@push('styles')
<style>
    /* ------------------------------
    Enhanced Contact Form Styling
    --------------------------------*/

    /* Input + Textarea Base Design */
    .enhanced-contact-form input[type=text],
    .enhanced-contact-form input[type=email],
    .enhanced-contact-form textarea {
        color: var(--default-color);
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.25);
        font-size: 15px;
        padding: 12px 15px 12px 45px; /* Space for icon */
        border-radius: 10px !important;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        width: 100%;
    }

    /* Icons Inside Inputs */
    .form-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        font-size: 18px;
        color: #b7b7b7;
        transition: 0.3s ease;
        pointer-events: none;
    }

    /* Textarea Icon Position Adjustment */
    /* .position-relative textarea {
        padding-left: 45px !important;
        padding-top: 15px;
    } */

    /* Focus State Effects */
    .enhanced-contact-form input:focus,
    .enhanced-contact-form textarea:focus {
        border-color: #4e73df;
        box-shadow: 0 0 10px rgba(78, 115, 223, 0.45);
    }

    /* Icon color changes on focus */
    .position-relative input:focus ~ .form-icon,
    .position-relative textarea:focus ~ .form-icon {
        color: #4e73df;
    }

    /* Submit Button */
    .contact-btn {
        background: #18d26e;
        color: #fff;
        padding: 12px 28px;
        border-radius: 10px;
        border: none;
        font-size: 16px;
        transition: 0.3s ease-in-out;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .contact-btn:hover {
        background: #18d26e;
        transform: translateY(-3px);
        box-shadow: 0 8px 18px rgba(78, 115, 223, 0.4);
    }

    /* Status Messages */
    .loading,
    .sent-message,
    .error-message {
        font-size: 14px;
        margin-bottom: 10px;
        display: none;
    }

    .sent-message {
        color: #1cc88a;
        font-weight: 600;
    }

    .error-message {
        color: #e74a3b;
        font-weight: 600;
    }

    .loading {
        color: #4e73df;
    }

    /* ---------------------------------
    RESPONSIVE STYLING
    ----------------------------------*/
    @media (max-width: 768px) {
        /* Adjust font + padding for small tablets */
        .enhanced-contact-form input,
        .enhanced-contact-form textarea {
            font-size: 14px;
            padding: 12px 12px 12px 42px;
        }

        .form-icon {
            font-size: 16px;
            left: 12px;
        }

        .contact-btn {
            padding: 12px 24px;
            font-size: 15px;
        }
    }

    @media (max-width: 576px) {
        /* Mobile Optimized */
        .enhanced-contact-form input,
        .enhanced-contact-form textarea {
            font-size: 14px;
            padding-left: 40px;
        }

        .form-icon {
            font-size: 15px;
            left: 10px;
        }

        .contact-btn {
            width: 100%;
            padding: 14px 0;
            font-size: 15px;
        }
    }
</style>
<style>
    .invalid-feedback {
        display: block !important;
        width: 100%;
        margin-top: .25rem;
        font-size: 16px !important;
        color: rgb(240, 250, 255) !important;
    }

    .form-control.is-invalid, .was-validated .form-control:invalid {
        border-color: #e8ebe9 !important;
        padding-right: calc(1.5em + .75rem);
        background-image: none !important;
        background-repeat: no-repeat;
        background-position: right calc(.375em + .1875rem) center;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }
    .form-control {
        line-height: 2.0;
    }  
</style>
<style>
    /* Responsive Google Map */
    .map-responsive {
        overflow: hidden;
        padding-bottom: 0;
        position: relative;
        height: 400px;
        border-radius: 12px;
    }

    .map-responsive iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
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
                        <h1>Contact Us</h1>
                        <p class="mb-0" style="text-align: justify !important; font-size: 20px;">
                            If you have any questions, suggestions, or inquiries, 
                            don't hesitate to contact us. We would love to hear from you and 
                            provide you with the best possible support. 
                            For any inquiries or updates, please feel free to reach out to us using the 
                            contact information provided below or by filling out the contact form. 
                            We value your feedback and look forward to hearing from you!
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('frontend.home') }}">Home</a></li>
                    <li class="current">Contact Us</li>
                </ol>
            </div>
        </nav>
    </div>
    <!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                        <i class="icon bi bi-geo-alt"></i>
                        <div>
                            <h3>Address</h3>
                            <p>
                                Kamothe, Navi Mumbai, Maharashtra, India.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End Info Item -->

                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="300">
                        <i class="icon bi bi-telephone"></i>
                        <div>
                            <h3>Call Me</h3>
                            <p>
                                <a href="tel:+91 9004763926" class="text-white">(+91) 9004763926</a>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End Info Item -->

                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="400">
                        <i class="icon bi bi-envelope"></i>
                        <div>
                            <h3>Email Us</h3>
                            <p>
                                <a href="mailto:codingthunder1997@gmail.com" class="text-white">codingthunder1997@gmail.com</a>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End Info Item -->

                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="500">
                        <i class="icon bi bi-share"></i>
                        <div>
                            <h3>Social Profiles</h3>
                            <div class="social-links">
                                <a href="#"><i class="bi bi-gitlab" style="color: rgb(205, 79, 1);"></i></a>
                                <a href="#"><i class="bi bi-facebook" style="color: rgb(5, 93, 193);"></i></a>
                                <a href="#"><i class="bi bi-instagram" style="color: rgb(243, 16, 122)"></i></a>
                                <a href="#"><i class="bi bi-linkedin" style="color: rgb(70, 70, 237);"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Info Item -->

            </div>

            <!-- Contact Form -->
            <form action="{{ route('frontend.contact.store') }}" method="post" data-aos="fade-up" data-aos-delay="600">
                @csrf

                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="mb-4">Send Us A Message</h2>
                    </div>
                </div>

                <div class="row gy-4">
                    <!-- Name -->
                    <div class="col-md-6 position-relative">
                        <label for="name" class="mb-2">
                            <strong>
                                <i class="bi bi-person-fill me-1"></i>
                                Name : * 
                            </strong>
                        </label>
                        <input type="text" name="name" class="form-control icon-input @error('name') is-invalid @enderror" placeholder="Enter your name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 position-relative">
                        <label for="email" class="mb-2">
                            <strong>
                                <i class="bi bi-envelope-fill me-1"></i>
                                Email  Id : *
                            </strong>
                        </label>
                        <input type="email" name="email" class="form-control icon-input @error('email') is-invalid @enderror" placeholder="Enter your email id" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 position-relative">
                        <label for="phone" class="mb-2">
                            <strong>
                                <i class="bi bi-telephone-fill me-1"></i>
                                Phone Number : * 
                            </strong>
                        </label>
                        <input type="text" name="phone" class="form-control icon-input @error('phone') is-invalid @enderror" placeholder="Enter your phone number" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div class="col-md-6 position-relative">
                        <label for="subject" class="mb-2">
                            <strong>
                                <i class="bi bi-chat-dots-fill me-1"></i>
                                Subject : * 
                            </strong>
                        </label>
                        <input type="text" name="subject" class="form-control icon-input @error('subject') is-invalid @enderror" placeholder="How can I help you..?" value="{{ old('subject') }}">
                        @error('subject')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="col-md-12 position-relative">
                        <label for="message" class="mb-2">
                            <strong>
                                <i class="bi bi-chat-text-fill me-1"></i>
                                Message * : 
                            </strong>
                        </label>
                        <textarea name="message" rows="6" class="form-control icon-textarea @error('message') is-invalid @enderror" placeholder="Write your message..." value="{{ old('message') }}">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- reCAPTCHA -->
                    {{-- <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div> --}}

                    <!-- Submit Button -->
                    <div class="col-md-12 text-center">
                        <button type="submit" class="contact-btn">
                            <i class="bi bi-send"></i>
                            Send Message
                        </button>
                    </div>

                </div>
            </form>
            <!-- End Contact Form -->

            <!-- Google Map Location -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-lg-12">
                    <h2 class="mb-4">My Location</h2>

                    <div class="map-responsive">
                        <iframe
                            src="https://www.google.com/maps?q=Mansarovar%20Complex%20Sector%2034%20Kamothe%20Panvel%20Navi%20Mumbai%20410209&output=embed"
                            width="100%"
                            height="400"
                            style="border:0; border-radius:12px;"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <!-- /Contact Section -->
@endsection

@push('scripts')
<!-- Include Google reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
