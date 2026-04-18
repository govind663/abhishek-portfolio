@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Resume
@endsection

@section('meta_description')
Explore the professional resume of Abhishek Jha — expert Laravel Developer with strong skills in APIs, backend systems, payment gateways, HRMS, CRM, ERP and enterprise web development.
@endsection

@section('meta_keywords')
Abhishek Resume, Laravel Developer Resume, PHP Developer CV, Full Stack Developer Resume, Software Engineer Resume, Web Developer CV
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
                    <h1>Resume</h1>
                    <p class="mb-0 text-justify fs-5">
                        Here is a summary of my professional experience, education, and skills.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <nav class="breadcrumbs" aria-label="breadcrumb">
        <div class="container">
            <ol>
                <li><a href="{{ route('frontend.home') }}" title="Home Page">Home</a></li>
                <li class="current">Resume</li>
            </ol>
        </div>
    </nav>
</div>
<!-- End Page Title -->

<!-- Resume Section -->
<section id="resume" class="resume section">

    <div class="container">
        <div class="row">

            <!-- Left Column -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">

                <h2 class="resume-title">Summary</h2>

                <div class="resume-item pb-0">
                    <h3>Abhishek Jha</h3>

                    <p class="text-justify">
                        <em>
                            I am a PHP / Laravel Developer with 4 years of professional experience. 
                            I have worked on building web applications, REST APIs, and database-driven solutions using PHP, Laravel, and MySQL. 
                            I enjoy learning new technologies, improving application performance, and writing clean and maintainable code.
                        </em>
                    </p>

                    <ul>
                        <li>Navi Mumbai, Maharashtra, India</li>
                        <li>+91 XXXXX XXXXX</li>
                        <li>yourmail@example.com</li>
                    </ul>
                </div>

                <h2 class="resume-title">Education</h2>

                <!-- BE -->
                <div class="resume-item" style="position: relative;">
                    <h3>
                        <span style="margin-right: 6px;">
                            <svg width="22" height="22" fill="#4e73df" viewBox="0 0 24 24">
                                <path d="M12 2L1 7l11 5 9-4.09V17h2V7L12 2z"></path>
                                <path d="M11 13.03L3.5 9.5v5.97L11 19l7.5-3.53V9.5L11 13.03z"></path>
                            </svg>
                        </span>

                        Bachelor of Engineering (B.E.) – Information Technology

                        <span style="background:#083087; color:#edeef3; padding:3px 8px; border-radius:5px; font-size:12px; margin-left:8px;">
                            2014 – 2020
                        </span>
                    </h3>

                    <p class="text-justify mb-1">
                        <strong>University:</strong> Savitribai Phule Pune University
                    </p>

                    <p class="text-justify mb-1">
                        <strong>College:</strong> D.Y. Patil Institute of Engineering and Technology
                    </p>

                    <p class="text-justify">
                        Ambi, Pune, Maharashtra, India
                    </p>
                </div>

                <!-- HSC -->
                <div class="resume-item mt-4">
                    <h3>
                        <span style="margin-right: 6px;">
                            <svg width="22" height="22" fill="#1cc88a" viewBox="0 0 24 24">
                                <path d="M3 4h18v12H3z"></path>
                                <path d="M7 20h10v-2H7z"></path>
                            </svg>
                        </span>

                        Higher Secondary Certificate (HSC) – Science

                        <span style="background:#0e7f45; color:#fafafa; padding:3px 8px; border-radius:5px; font-size:12px; margin-left:8px;">
                            2012 – 2014
                        </span>
                    </h3>

                    <p class="text-justify mb-1">
                        <strong>College:</strong> TILAK Jr. College of Science & Commerce
                    </p>

                    <p class="text-justify">
                        Seawoods, Navi Mumbai, Maharashtra, India
                    </p>
                </div>

                <!-- SSC -->
                <div class="resume-item mt-4">
                    <h3>
                        <span style="margin-right: 6px;">
                            <svg width="22" height="22" fill="#f6c23e" viewBox="0 0 24 24">
                                <path d="M4 4h16v14H4z"></path>
                            </svg>
                        </span>

                        Secondary School Certificate (SSC)

                        <span style="background:#b8801f; color:#f2f2f2; padding:3px 8px; border-radius:5px; font-size:12px; margin-left:8px;">
                            2002 – 2012
                        </span>
                    </h3>

                    <p class="text-justify mb-1">
                        <strong>School:</strong> National Public High School
                    </p>

                    <p class="text-justify">
                        Nerul, Navi Mumbai, Maharashtra, India
                    </p>
                </div>

                <h2 class="resume-title">Technical Skills</h2>

                <div class="resume-item mt-4">

                    <p class="fw-bold mt-2">Programming Languages</p>
                    <p>
                        <span class="badge bg-secondary">PHP</span>
                        <span class="badge bg-secondary">JavaScript</span>
                        <span class="badge bg-secondary">HTML</span>
                        <span class="badge bg-secondary">CSS</span>
                        <span class="badge bg-secondary">Ajax</span>
                    </p>

                    <p class="fw-bold mt-2">Frameworks</p>
                    <p>
                        <span class="badge bg-success">Laravel</span>
                        <span class="badge bg-success">CodeIgniter</span>
                    </p>

                    <p class="fw-bold mt-2">Databases</p>
                    <p>
                        <span class="badge bg-warning text-dark">MySQL</span>
                        <span class="badge bg-warning text-dark">PostgreSQL</span>
                        <span class="badge bg-warning text-dark">SQLite</span>
                    </p>

                    <p class="fw-bold mt-2">Tools & DevOps</p>
                    <p>
                        <span class="badge bg-dark">Git</span>
                        <span class="badge bg-dark">Composer</span>
                        <span class="badge bg-dark">VS Code</span>
                        <span class="badge bg-dark">Postman</span>
                    </p>

                    <p class="fw-bold mt-2">API Development</p>
                    <p>
                        <span class="badge bg-danger">REST APIs</span>
                        <span class="badge bg-danger">Payment APIs</span>
                    </p>

                </div>

            </div>

            <!-- Right Column -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">

                <h2 class="resume-title">Professional Experience</h2>

                <div class="resume-item">
                    <h3>Senior PHP / Laravel Developer</h3>
                    <p class="text-justify"><em>Notion Technologies, Navi Mumbai</em></p>
                    <h5>May 2024 - September 2025</h5>
                </div>

                <div class="resume-item">
                    <h3>Mid-Level PHP / Laravel Developer</h3>
                    <p class="text-justify"><em>Coreocean Solutions LLP</em></p>
                    <h5>Oct 2021 - May 2024</h5>
                </div>

                <div class="resume-item">
                    <h3>Junior Developer Intern</h3>
                    <p class="text-justify"><em>Speed TechServe Pvt. Ltd.</em></p>
                    <h5>Apr 2020 - Jul 2020</h5>
                </div>

            </div>

        </div>
    </div>

</section>

@endsection

@push('scripts')
@endpush