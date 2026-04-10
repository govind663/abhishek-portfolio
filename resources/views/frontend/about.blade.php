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
<style>
    .page-title .heading .page-title-desc b {
        color: green !important;
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

                        <h1>
                            {{ $pageTitle->page_name ?? 'About Us' }}
                        </h1>

                        <p class="mb-0 page-title-desc" style="text-align: justify; font-size: 20px;">
                            {!! $pageTitle->description ?? '' !!}
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
    {{-- End Page Title --}}

    {{-- Start About Section --}}
    <section id="about" class="about section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4 justify-content-center">

                <div class="col-lg-4">
                    <img src="{{ $about->profile_image ? asset($about->profile_image) : asset('frontend/assets/img/Abhishek_profile_pic.webp') }}"
                        class="img-fluid rounded"
                        alt="{{ $about->name ?? 'Profile' }}"
                        data-aos="fade-up"
                        data-aos-delay="200"
                        title="{{ $about->subtitle ?? '' }}">
                </div>

                <div class="col-lg-8 content">

                    <h2>{{ $about->name ?? 'Developer' }}</h2>

                    {{-- <p class="fst-italic py-1" style="text-align: justify !important;">
                        {{ $about->subtitle ?? '' }}
                    </p> --}}

                    <p class="py-1" style="text-align: justify !important;">
                        {{ $about->description }}
                    </p>

                    <div class="row">

                        <div class="col-lg-6">
                            <ul>
                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Experience:</strong>
                                    <span>{{ $about->experience }}</span>
                                </li>

                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Specialization:</strong>
                                    <span>{{ $about->specialization }}</span>
                                </li>

                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Phone:</strong>
                                    <span>{{ $about->phone }}</span>
                                </li>

                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Location:</strong>
                                    <span>{{ $about->location }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-6">
                            <ul>
                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Role:</strong>
                                    <span>{{ $about->role }}</span>
                                </li>

                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Database:</strong>
                                    <span>{{ $about->database }}</span>
                                </li>

                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Email:</strong>
                                    <span>{{ $about->email }}</span>
                                </li>

                                <li><i class="bi bi-chevron-right"></i>
                                    <strong>Freelance:</strong>
                                    <span>{{ $about->freelance }}</span>
                                </li>
                            </ul>
                        </div>

                    </div>                    

                    <p class="py-1" style="text-align: justify !important;">
                        {{ $about->extra_description }}
                    </p>

                </div>
            </div>

        </div>
    </section>
    {{-- End About Section --}}

    {{-- Start Stats Section --}}
    <section id="stats" class="stats section">

        <div class="container section-title" data-aos="fade-up">
            <h2>Stats</h2>
            <div><span>My</span> <span class="description-title">Stats</span></div>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                @foreach($stats as $stat)
                    <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                        <i class="{{ $stat->icon ?? 'bi bi-emoji-smile' }}"></i>

                        <div class="stats-item">
                            <span
                                data-purecounter-start="0"
                                data-purecounter-end="{{ $stat->count }}"
                                data-purecounter-duration="1"
                                class="purecounter">
                            </span>

                            <p>{{ $stat->title }}</p>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    </section>
    {{-- End Stats Section --}}

    {{-- Start Skills Section (LEFT / RIGHT GROUPED) --}}
    <section id="skills" class="skills section">

        <div class="container section-title" data-aos="fade-up">
            <h2>Skills</h2>
            <div><span>My</span> <span class="description-title">Expertise</span></div>
        </div>

        <div class="container" data-aos="fade-up">

            <div class="row skills-content skills-animation">

                {{-- LEFT SIDE --}}
                <div class="col-lg-6">

                    @foreach($skills as $skill)

                        @if($skill->group == 'left')

                            <div class="progress">
                                <span class="skill">
                                    <span>{{ $skill->name }}</span>
                                    <i class="val">{{ $skill->percentage }}%</i>
                                </span>

                                <div class="progress-bar-wrap">
                                    <div class="progress-bar" style="width: {{ $skill->percentage }}%"></div>
                                </div>
                            </div>

                        @endif

                    @endforeach

                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-lg-6">

                    @foreach($skills as $skill)

                        @if($skill->group == 'right')

                            <div class="progress">
                                <span class="skill">
                                    <span>{{ $skill->name }}</span>
                                    <i class="val">{{ $skill->percentage }}%</i>
                                </span>

                                <div class="progress-bar-wrap">
                                    <div class="progress-bar" style="width: {{ $skill->percentage }}%"></div>
                                </div>
                            </div>

                        @endif

                    @endforeach

                </div>

            </div>

        </div>

    </section>
    {{-- End Skills Section --}}

    {{-- Start Features Section --}}
    <section id="interests" class="interests section">

        <div class="container section-title" data-aos="fade-up">
            <h2>Features</h2>
            <div><span>I'm</span> <span class="description-title">Specialized in</span></div>
        </div>

        <div class="container">

            <div class="row gy-4">

                @foreach($features as $feature)
                    <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                        <div class="features-item">

                            <i class="{{ $feature->icon }}" style="color: {{ $feature->color ?? '#ffbb2c' }};"></i>

                            <h3>
                                <a href="#" class="stretched-link">
                                    {{ $feature->title }}
                                </a>
                            </h3>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </section>
    {{-- End Features Section --}}
@endsection

@push('scripts')
@endpush
