@extends('frontend.layouts.master')

@section('title')
Abhishek Jha | Resume
@endsection

@section('meta_description')
Explore the professional resume of Abhishek Jha — expert Laravel Developer with strong skills in APIs, 
backend systems, payment gateways, HRMS, CRM, ERP and enterprise web development.
@endsection

@section('meta_keywords')
Abhishek Resume, Laravel Developer Resume, PHP Developer CV, Full Stack Developer Resume, 
Software Engineer Resume, Web Developer CV, Professional Resume Abhishek
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
                        <h1>Resume</h1>
                        <p class="mb-0" style="text-align: justify !important; font-size: 20px;">
                            Here is a summary of my professional experience, education, and skills.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
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

                <!-- Left Column: Summary & Education -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="resume-title">Summary</h3>

                    <div class="resume-item pb-0">
                        <h4>Abhishek Jha</h4>
                        <p style="text-align: justify !important;">
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

                    <h3 class="resume-title">Education</h3>

                    <!-- BE in IT -->
                    <div class="resume-item" style="position: relative;">
                        <h4>
                            <!-- University Logo -->
                            <span style="margin-right: 6px;">
                                <svg width="22" height="22" fill="#4e73df" viewBox="0 0 24 24">
                                    <path d="M12 2L1 7l11 5 9-4.09V17h2V7L12 2z"></path>
                                    <path d="M11 13.03L3.5 9.5v5.97L11 19l7.5-3.53V9.5L11 13.03z"></path>
                                </svg>
                            </span>

                            Bachelor of Engineering (B.E.) – Information Technology

                            <!-- Year Badge -->
                            <span style="background:#083087; color:#edeef3; padding:3px 8px; border-radius:5px; font-size:12px; margin-left:8px;">
                                2014 – 2020
                            </span>
                        </h4>

                        <!-- University Name -->
                        <p style="text-align: justify !important; margin-bottom:4px;">
                            <strong>University:</strong> Savitribai Phule Pune University
                        </p>

                        <!-- College Name -->
                        <p style="text-align: justify !important; margin-bottom:4px;">
                            <strong>College:</strong> D.Y. Patil Institute of Engineering and Technology
                        </p>

                        <!-- Location with Icon -->
                        <p style="text-align: justify !important;">
                            <span style="margin-right:6px;">
                                <svg width="16" height="16" fill="#ff4444" viewBox="0 0 24 24">
                                    <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                                </svg>
                            </span>
                            Ambi, Pune, Maharashtra, India
                        </p>
                    </div>

                    <!-- HSC -->
                    <div class="resume-item" style="position: relative; margin-top:20px;">
                        <h4>
                            <!-- College Logo -->
                            <span style="margin-right: 6px;">
                                <svg width="22" height="22" fill="#1cc88a" viewBox="0 0 24 24">
                                    <path d="M3 4h18v12H3z"></path>
                                    <path d="M7 20h10v-2H7z"></path>
                                </svg>
                            </span>

                            Higher Secondary Certificate (HSC) – Science

                            <!-- Year -->
                            <span style="background:#0e7f45; color:#fafafa; padding:3px 8px; border-radius:5px; font-size:12px; margin-left:8px;">
                                2012 – 2014
                            </span>
                        </h4>

                        <!-- College Name -->
                        <p style="text-align: justify !important; margin-bottom:4px;">
                            <strong>College:</strong> TILAK Jr. College of Science & Commerce
                        </p>

                        <!-- Location -->
                        <p style="text-align: justify !important;">
                            <span style="margin-right:6px;">
                                <svg width="16" height="16" fill="#ff4444" viewBox="0 0 24 24">
                                    <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                                </svg>
                            </span>
                            Seawoods, Navi Mumbai, Maharashtra, India
                        </p>
                    </div>

                    <!-- SSC -->
                    <div class="resume-item" style="position: relative; margin-top:20px;">
                        <h4>
                            <!-- School Logo -->
                            <span style="margin-right: 6px;">
                                <svg width="22" height="22" fill="#f6c23e" viewBox="0 0 24 24">
                                    <path d="M4 4h16v14H4z"></path>
                                    <circle cx="8" cy="10" r="2"></circle>
                                    <path d="M14 9h5v2h-5zM14 13h5v2h-5z"></path>
                                </svg>
                            </span>

                            Secondary School Certificate (SSC)

                            <!-- Year -->
                            <span style="background:#b8801f; color:#f2f2f2; padding:3px 8px; border-radius:5px; font-size:12px; margin-left:8px;">
                                2002 – 2012
                            </span>
                        </h4>

                        <!-- School Name -->
                        <p style="text-align: justify !important; margin-bottom:4px;">
                            <strong>School:</strong> National Public High School
                        </p>

                        <!-- Location Icon -->
                        <p style="text-align: justify !important;">
                            <span style="margin-right:6px;">
                                <svg width="16" height="16" fill="#ff4444" viewBox="0 0 24 24">
                                    <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                                </svg>
                            </span>
                            Nerul, Navi Mumbai, Maharashtra, India
                        </p>
                    </div>

                    <h3 class="resume-title">
                        Technical Skills
                    </h3>
                    {{-- SKILLS --}}
                    <div class="resume-item" style="position: relative; margin-top:25px;">
                        <!-- Programming Languages -->
                        <p style="margin-top:10px; font-weight:600;">
                            <svg width="18" height="18" fill="#4e73df" viewBox="0 0 24 24" style="margin-right:5px;">
                                <path d="M3 4h18v2H3zm0 6h18v2H3zm0 6h18v2H3z"></path>
                            </svg>
                            Programming Languages
                        </p>
                        <p>
                            <span class="badge bg-secondary">PHP</span>
                            <span class="badge bg-secondary">JavaScript</span>
                            <span class="badge bg-secondary">HTML</span>
                            <span class="badge bg-secondary">CSS</span>
                            <span class="badge bg-secondary">Ajax</span>
                        </p>

                        <!-- Frameworks -->
                        <p style="margin-top:10px; font-weight:600;">
                            <svg width="18" height="18" fill="#1cc88a" viewBox="0 0 24 24" style="margin-right:5px;">
                                <path d="M12 2L1 21h22L12 2z"></path>
                            </svg>
                            Frameworks
                        </p>
                        <p>
                            <span class="badge bg-success">Laravel</span>
                            <span class="badge bg-success">CodeIgniter</span>
                        </p>

                        <!-- Databases -->
                        <p style="margin-top:10px; font-weight:600;">
                            <svg width="18" height="18" fill="#f6c23e" viewBox="0 0 24 24" style="margin-right:5px;">
                                <path d="M12 2C7 2 2 3.79 2 6v12c0 2.21 5 4 10 4s10-1.79 10-4V6c0-2.21-5-4-10-4z"></path>
                            </svg>
                            Databases
                        </p>
                        <p>
                            <span class="badge bg-warning text-dark">MySQL</span>
                            <span class="badge bg-warning text-dark">PostgreSQL</span>
                            <span class="badge bg-warning text-dark">SQLite</span>
                        </p>

                        <!-- Tools / DevOps -->
                        <p style="margin-top:10px; font-weight:600;">
                            <svg width="18" height="18" fill="#858796" viewBox="0 0 24 24" style="margin-right:5px;">
                                <path d="M9 2l1 5h4l1-5h2l-1 5h3v2h-3l-1 5h3v2h-3l1 5h-2l-1-5h-4l-1 5H7l1-5H5v-2h3l1-5H5V7h3L7 2h2z"></path>
                            </svg>
                            Tools & DevOps
                        </p>
                        <p>
                            <span class="badge bg-dark">Git</span>
                            <span class="badge bg-dark">Composer</span>
                            <span class="badge bg-dark">VS Code</span>
                            <span class="badge bg-dark">Postman</span>
                        </p>

                        <!-- API Skills -->
                        <p style="margin-top:10px; font-weight:600;">
                            <svg width="18" height="18" fill="#e74a3b" viewBox="0 0 24 24" style="margin-right:5px;">
                                <path d="M3 12l18-9-9 18-2-7-7-2z"></path>
                            </svg>
                            API Development & Integration
                        </p>
                        <p>
                            <span class="badge bg-danger">RESTful APIs</span>
                            <span class="badge bg-danger">SMS APIs</span>
                            <span class="badge bg-danger">Payment Gateway APIs</span>
                            <span class="badge bg-danger">3rd-Party Integrations</span>
                        </p>

                        <!-- Deployment -->
                        <p style="margin-top:10px; font-weight:600;">
                            <svg width="18" height="18" fill="#36b9cc" viewBox="0 0 24 24" style="margin-right:5px;">
                                <path d="M12 22l-8-4V6l8-4 8 4v12l-8 4z"></path>
                            </svg>
                            Deployment & Hosting
                        </p>
                        <p>
                            <span class="badge bg-info text-dark">GCP</span>
                            <span class="badge bg-info text-dark">DigitalOcean</span>
                            <span class="badge bg-info text-dark">cPanel</span>
                            <span class="badge bg-info text-dark">Hostinger</span>
                        </p>
                    </div>

                </div>

                <!-- Right Column: Professional Experience -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="resume-title">Professional Experience</h3>

                    <!-- SENIOR LEVEL -->
                    <div class="resume-item">
                        <h4>
                            <span class="badge bg-success">
                                <!-- Senior SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                    fill="currentColor" class="bi bi-person-badge-fill" viewBox="0 0 16 16">
                                    <path d="M6.5 2a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0"/>
                                    <path d="M14 7c0 1.105-.672 2-1.5 2h-9C2.672 9 2 8.105 2 7V3c0-1.105.672-2 1.5-2h9C13.328 1 14 1.895 14 3v4z"/>
                                    <path d="M11 10H5c-1.657 0-3 1.567-3 3.5V15h12v-1.5c0-1.933-1.343-3.5-3-3.5"/>
                                </svg> 
                                Senior PHP / Laravel Developer
                            </span>
                        </h4>
                        
                        <p style="text-align: justify !important;">
                            <em>Notion Technologies, Navi Mumbai, Maharashtra</em>
                        </p>
                        <h5>May 2024 - September 2025</h5>

                        <ul>
                            <li>Built advanced RESTful APIs with authentication (Sanctum/JWT) and third-party integrations.</li>
                            <li>Refactored old PHP code into modern Laravel structure (Service Container, Repository Pattern).</li>
                            <li>Implemented caching using Redis and optimized MySQL queries for high performance.</li>
                            <li>Built role & permission system for secure access control.</li>
                            <li>Collaborated closely with frontend team (Vue/React developers) for seamless API integration.</li>
                            <li>Managed database migrations and schema changes efficiently.</li>
                            <li>Delivered feature-rich web applications with a focus on user experience and performance.</li>
                        </ul>
                    </div>

                    <!-- MID LEVEL -->
                    <div class="resume-item">
                        <h4>
                            <span class="badge bg-primary">
                                <!-- Mid-Level SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                    fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                    <path d="M1 14s1-1 5-1 5 1 5 1-1 1-5 1-5-1-5-1m11-4.5a.5.5 0 0 1 .5-.5H15
                                            a.5.5 0 0 1 0 1h-2.5a.5.5 0 0 1-.5-.5m.5-2.5a.5.5 
                                            0 0 0 0 1H15a.5.5 0 0 0 0-1zm0-3a.5.5 
                                            0 0 0 0 1H15a.5.5 0 0 0 0-1z"/>
                                </svg>
                                Mid-Level PHP / Laravel Developer
                            </span>
                        </h4>
                        
                        <p style="text-align: justify !important;">
                            <em>Coreocean Solutions LLP, Thane, Navi Mumbai</em>
                        </p>
                        <h5>Oct 2021 - May 2024</h5>

                        <ul>
                            <li>Started as a junior and grew into a mid-level Laravel developer by handling complete modules independently.</li>

                            <li>Developed admin dashboards, multi-role authentication systems & reusable Laravel components.</li>

                            <li>Created controllers, models, migrations, resource collections, and optimized Eloquent queries.</li>

                            <li>Integrated major APIs (Payment Gateways, SMS, OTP, Google APIs).</li>

                            <li>Improved database performance using indexing, query builder optimization, and caching.</li>

                            <li>Worked with queues, jobs & scheduler (Cron) for background tasks.</li>
                        </ul>
                    </div>

                    <!-- JUNIOR INTERN -->
                    <div class="resume-item">
                        <h4>
                            <span class="badge bg-secondary">
                                <!-- Intern SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                    fill="currentColor" class="bi bi-lightbulb-fill" viewBox="0 0 16 16">
                                    <path d="M2 6a6 6 0 1 1 12 0c0 2.577-1.636 4.773-3.608 
                                            5.708-.182.088-.292.271-.292.471v.821c0 
                                            .448-.295.849-.732.98-.99.298-2.068.297-3.058 
                                            0a1.016 1.016 0 0 1-.732-.98v-.821c0-.2-.11-.383-.292-.471A6.745 
                                            6.745 0 0 1 2 6"/>
                                </svg>
                                Junior Developer Intern
                            </span>
                        </h4>
                        
                        <p style="text-align: justify !important;">
                            <em>Speed TechServe Pvt. Ltd. (OPC), Pune</em>
                        </p>
                        <h5>Apr 2020 - Jul 2020</h5>

                        <ul>
                            <li>Assisted senior developers with Laravel features (Routing, Controllers, Models, Views).</li>
                            <li>Worked on CRUD operations and basic MySQL database structure.</li>
                            <li>Fixed bugs, improved small UI components, and performed testing tasks.</li>
                            <li>Learned API creation, Postman testing, Git commands, and project flow.</li>
                            <li>Participated in code reviews and provided constructive feedback to fellow interns.</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </section>
    <!-- /Resume Section --> 
@endsection

@push('scripts')
@endpush
