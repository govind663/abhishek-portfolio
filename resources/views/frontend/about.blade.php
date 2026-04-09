@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | About Us
@endsection

@section('meta_description')
Learn more about Abhishek Jha — a skilled Laravel & Full Stack Developer with 4+ years of experience in 
building secure, scalable and high-performance web applications.
@endsection

@section('meta_keywords')
Abhishek Jha, About Abhishek, Laravel Developer, PHP Developer, Web Developer, Software Engineer,
Full Stack Developer Portfolio, Abhishek Profile, Developer Story, Career Journey
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
                        <h1>About Us</h1>
                        <p class="mb-0" style="text-align: justify !important; font-size: 20px;">
                            I am a dedicated and detail-oriented <strong style="color: green;">Sr. PHP / Laravel Developer</strong> with 
                            4+ years of professional experience in developing secure, scalable and high-performance 
                            web applications. I specialize in crafting efficient backends, REST APIs, custom dashboards, 
                            and full-stack web solutions tailored to business needs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                    <li class="current">About Us</li>
                </ol>
            </div>
        </nav>
    </div>
    <!-- End Page Title -->

    <!-- About Section -->
    <section id="about" class="about section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4 justify-content-center">
                <div class="col-lg-4">
                    <img src="{{ asset('frontend/assets/img/Abhishek_profile_pic.webp') }}" class="img-fluid rounded" alt="Abhishek Jha">
                </div>
                <div class="col-lg-8 content">
                    <h2>Sr. PHP Laravel Developer & Web Application Builder</h2>
                    <p class="fst-italic py-3" style="text-align: justify !important;">
                        Over the years, I have built end-to-end applications including 
                        e-commerce platforms, HRMS systems, real-time dashboards,
                        multi-vendor systems and fully customized backend solutions.
                    </p>

                    <div class="row">
                        <div class="col-lg-6">
                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <strong>Experience:</strong> <span>4+ Years</span></li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Specialization:</strong> <span>PHP, Laravel, REST API</span></li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Phone:</strong> <span>+91 9004763926</span></li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Location:</strong> <span>India</span></li>
                            </ul>
                        </div>

                        <div class="col-lg-6">
                            <ul>
                                <li><i class="bi bi-chevron-right"></i> <strong>Role:</strong> <span>Sr. PHP Developer</span></li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Database:</strong> <span>MySQL, MariaDB</span></li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Email:</strong> <span>codingthunder1997@gmail.com</span></li>
                                <li><i class="bi bi-chevron-right"></i> <strong>Freelance:</strong> <span>Available</span></li>
                            </ul>
                        </div>
                    </div>

                    <p class="py-3" style="text-align: justify !important;">
                        I create business-oriented applications that deliver performance, security, 
                        and exceptional user experience. With strong problem-solving skills and hands-on 
                        experience in backend architecture, API integration, payment gateways, 
                        and database optimization — I aim to develop scalable digital solutions that add real value.
                    </p>
                </div>
            </div>

        </div>

    </section>
    <!-- /About Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-emoji-smile"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="80" data-purecounter-duration="1" class="purecounter"></span>
                        <p>Happy Clients</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-journal-richtext"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="1" class="purecounter"></span>
                        <p>Total Projects Completed</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-headset"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="2500" data-purecounter-duration="1" class="purecounter"></span>
                        <p>Hours Of Support</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="bi bi-people"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="1" class="purecounter"></span>
                        <p>Team Collaborations</p>
                    </div>
                </div>

            </div>

        </div>

    </section>
    <!-- /Stats Section -->

    <!-- Skills Section -->
    <section id="skills" class="skills section">

        <div class="container section-title" data-aos="fade-up">
            <h2>Skills</h2>
            <div><span>My</span> <span class="description-title">Expertise</span></div>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row skills-content skills-animation">

                <div class="col-lg-6">
                    {{-- HTML/CSS --}}
                    <div class="progress">
                        <span class="skill"><span>HTML5 / CSS3</span> <i class="val">80%</i></span>
                        <div class="progress-bar-wrap"><div class="progress-bar" style="width: 80%"></div></div>
                    </div>

                    {{-- Bootstrap 5 --}}
                    <div class="progress">
                        <span class="skill"><span>Bootstrap 5</span> <i class="val">90%</i></span>
                        <div class="progress-bar-wrap"><div class="progress-bar" style="width: 90%"></div></div>
                    </div>

                    {{-- AJAX --}}
                    <div class="progress">
                        <span class="skill"><span>AJAX</span> <i class="val">75%</i></span>
                        <div class="progress-bar-wrap"><div class="progress-bar" style="width: 75%"></div></div>
                    </div>
                </div>

                <div class="col-lg-6">
                    {{-- PHP --}}
                    <div class="progress">
                        <span class="skill"><span>PHP</span> <i class="val">95%</i></span>
                        <div class="progress-bar-wrap"><div class="progress-bar" style="width: 95%"></div></div>
                    </div>                    
                
                    {{-- Javascript --}}
                    <div class="progress">
                        <span class="skill"><span>jQuery / JS</span> <i class="val">80%</i></span>
                        <div class="progress-bar-wrap"><div class="progress-bar" style="width: 80%"></div></div>
                    </div>

                    {{-- Version Control (Git) --}}
                    <div class="progress">
                        <span class="skill"><span>Version Control (Git)</span> <i class="val">85%</i></span>
                        <div class="progress-bar-wrap"><div class="progress-bar" style="width: 85%"></div></div>
                    </div>

                </div>

            </div>

        </div>

    </section>
    <!-- /Skills Section -->

    <!-- Interests / Features Section -->
    <section id="interests" class="interests section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Features</h2>
            <div><span>I'm</span> <span class="description-title">Specialized in</span></div>
        </div>

        <div class="container">
            <div class="row gy-4">

                {{-- Custom Web Apps --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="features-item">
                        <i class="bi bi-code-slash" style="color: #ffbb2c;"></i>
                        <h3><a href="#" class="stretched-link">Custom Web Apps</a></h3>
                    </div>
                </div>

                {{-- Database Architecture --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="features-item">
                        <i class="bi bi-database" style="color: #5578ff;"></i>
                        <h3><a href="#" class="stretched-link">Database Architecture</a></h3>
                    </div>
                </div>

                {{-- API Integrations --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="features-item">
                        <i class="bi bi-cloud-arrow-up" style="color: #e80368;"></i>
                        <h3><a href="#" class="stretched-link">API Integrations</a></h3>
                    </div>
                </div>

                {{-- E-commerce Systems --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="features-item">
                        <i class="bi bi-cart-check" style="color: #e361ff;"></i>
                        <h3><a href="#" class="stretched-link">E-commerce Systems</a></h3>
                    </div>
                </div>

                {{-- Payment Gateway Integrations --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="features-item">
                        <i class="bi bi-credit-card-2-front" style="color: #47aeff;"></i>
                        <h3><a href="#" class="stretched-link">Payment Gateway Integrations</a></h3>
                    </div>
                </div>

                {{-- HR Management Systems --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="features-item">
                        <i class="bi bi-people" style="color: #ffa76e;"></i>
                        <h3><a href="#" class="stretched-link">HR Management Systems</a></h3>
                    </div>
                </div>

                {{-- Deployment and Maintenance --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="700">
                    <div class="features-item">
                        <i class="bi bi-cloud-upload" style="color: #11dbcf;"></i>
                        <h3><a href="#" class="stretched-link">Deployment and Maintenance</a></h3>
                    </div>
                </div>

                {{-- Performance Optimization --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="800">
                    <div class="features-item">
                        <i class="bi bi-speedometer2" style="color: #4233ff;"></i>
                        <h3><a href="#" class="stretched-link">Performance Optimization</a></h3>
                    </div>
                </div>

                {{-- Security Measures --}}
                <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="900">
                    <div class="features-item">
                        <i class="bi bi-shield-check" style="color: #b10dc9;"></i>
                        <h3><a href="#" class="stretched-link">Security Measures</a></h3>
                    </div>
                </div>

                
            </div>

        </div>

    </section>
    <!-- /Interests Section -->

@endsection

@push('scripts')
@endpush
