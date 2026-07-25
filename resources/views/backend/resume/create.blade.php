@extends('backend.layouts.master')

@section('title')
    Create Resume
@endsection

@push('styles')
<style>
    .resume-section-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f1f5ff;
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .resume-section-icon svg {
        width: 28px;
        height: 28px;
    }

    .resume-section-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .education-icon {
        background: #eef6ff;
        color: #2563eb;
    }

    .resume-section-icon svg {
        width: 28px;
        height: 28px;
    }

    .skills-icon {
        background: #ecfdf5;
        color: #0eab48;
    }

    .experience-icon {
        background: #fff7ed;
        color: #f97316;
    }
    
</style>
@endpush

@section('content')
    <div class="pd-ltr-20 xs-pd-10-10">
        <div class="min-height-200px">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Create Resume</h4>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('resume.index') }}">Manage Resumes</a>
                                </li>
                                <li class="breadcrumb-item active">Create Resume</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="pd-20 card-box mb-30">

                <form id="resumeForm" action="{{ route('resume.store') }}" method="POST" enctype="multipart/form-data" novalidate>

                    @csrf

                    {{-- Personal Information --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between bg-white">
    
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <h5 class="mb-0 fw-semibold text-primary">
                                        Personal Information
                                    </h5>
                                    <small class="text-muted text-2xl text-secondary">
                                        Add your basic personal details
                                    </small>
                                </div>
                            </div>

                            <!-- Right SVG Icon -->
                            <div class="resume-section-icon">
                                <svg width="42" height="42" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"/>

                                    <path d="M3 22C3 18.134 6.13401 15 10 15H14C17.866 15 21 18.134 21 22"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"/>

                                </svg>
                            </div>

                        </div>
                        <div class="card-body">
                            @include('backend.resume.partials.personal-info')
                        </div>
                    </div>

                    {{-- Education --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between bg-white">

                            <div>
                                <h5 class="mb-0 fw-semibold text-primary">
                                    Education
                                </h5>
                                <small class="text-muted">
                                    Add your academic qualifications
                                </small>
                            </div>

                            <!-- Education SVG Icon -->
                            <div class="resume-section-icon education-icon">

                                <svg width="42" height="42" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <!-- Graduation Cap -->
                                    <path d="M12 3L2 8L12 13L22 8L12 3Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linejoin="round"/>

                                    <path d="M6 10.5V15C6 17.2091 8.68629 19 12 19C15.3137 19 18 17.2091 18 15V10.5"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"/>

                                    <path d="M22 8V14"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"/>

                                </svg>

                            </div>

                        </div>
                        <div class="card-body">
                            @include('backend.resume.partials.education')
                        </div>
                    </div>

                    {{-- Technical Skills --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between bg-white">

                            <div>
                                <h5 class="mb-0 fw-semibold text-primary">
                                    Technical Skills
                                </h5>

                                <small class="text-muted">
                                    Add your professional and technical expertise
                                </small>
                            </div>


                            <!-- Technical Skills SVG Icon -->
                            <div class="resume-section-icon skills-icon">

                                <svg width="42" height="42" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <!-- Code Brackets -->
                                    <path d="M8 9L5 12L8 15"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"/>

                                    <path d="M16 9L19 12L16 15"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"/>

                                    <!-- Slash -->
                                    <path d="M14 7L10 17"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"/>

                                </svg>

                            </div>

                        </div>
                        <div class="card-body">
                            @include('backend.resume.partials.technical-skills')
                        </div>
                    </div>

                    {{-- Professional Experience --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between bg-white">

                            <div>
                                <h5 class="mb-0 fw-semibold text-primary">
                                    Professional Experience
                                </h5>

                                <small class="text-muted">
                                    Add your previous work experience and achievements
                                </small>
                            </div>


                            <!-- Professional Experience SVG Icon -->
                            <div class="resume-section-icon experience-icon">

                                <svg width="42" height="42" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <!-- Briefcase -->
                                    <path d="M9 7V5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"/>

                                    <path d="M4 7H20C21.1046 7 22 7.89543 22 9V17C22 18.1046 21.1046 19 20 19H4C2.89543 19 2 18.1046 2 17V9C2 7.89543 2.89543 7 4 7Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"/>

                                    <path d="M2 12H22"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"/>

                                    <path d="M10 12V13C10 13.5523 10.4477 14 11 14H13C13.5523 14 14 13.5523 14 13V12"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"/>

                                </svg>

                            </div>

                        </div>
                        <div class="card-body">
                            @include('backend.resume.partials.professional-experience')
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="text-right mt-4">
                        <a href="{{ route('resume.index') }}" class="btn btn-danger">Cancel</a>
                        <button type="submit" class="btn btn-success">Save Resume</button>
                    </div>
                </form>
            </div>

        </div>

        <x-backend.footer />
    </div>
@endsection


@push('scripts')
@endpush