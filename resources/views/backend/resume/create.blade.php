@extends('backend.layouts.master')

@section('title')
    Create Resume
@endsection

@push('styles')
<style>
/* ===============================
   INPUT / TEXTAREA
================================ */
.form-control.is-invalid {
    border: 1px solid #dc3545 !important;
    box-shadow: none !important;
    transition: none !important;
}

/* ===============================
   ERROR MESSAGE
================================ */
.invalid-feedback {
    display: block !important;
    color: #dc3545 !important;
    font-weight: 600;
    margin-top: 5px;
}

/* ===============================
   SELECT2 FIX
================================ */
.select2-container--default .select2-selection--single {
    border: 1px solid #ced4da;
}

.select2-container--default .select2-selection--single.is-invalid {
    border: 1px solid #dc3545 !important;
    box-shadow: none !important;
    transition: none !important;
}

/* ===============================
   TEXTAREA EDITOR FIX
================================ */

/* Summernote */
.note-editor.is-invalid {
    border: 1px solid #dc3545 !important;
}

/* TinyMCE */
.tox.is-invalid {
    border: 1px solid #dc3545 !important;
}

/* CKEditor */
.cke.is-invalid {
    border: 1px solid #dc3545 !important;
}

/* remove inner double border */
.note-editor.is-invalid .note-editable,
.tox.is-invalid .tox-edit-area,
.cke.is-invalid .cke_inner {
    border: none !important;
}

/* remove animation */
.note-editor,
.tox,
.cke {
    transition: none !important;
    box-shadow: none !important;
}

/* FORCE BORDER */
.note-editor.is-invalid,
.tox.is-invalid,
.cke.is-invalid {
    border: 1px solid #dc3545 !important;
}

/* IMPORTANT: prevent override */
.note-editor,
.tox,
.cke {
    border: 1px solid #ced4da !important;
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
                {{-- NAV TABS --}}
                <ul class="nav nav-tabs customtab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#step1">
                            <span class="step-icon"><i class="fa fa-user"></i></span>
                            Personal Information
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#step2">
                            <span class="step-icon"><i class="fa fa-graduation-cap"></i></span>
                            Education
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#step3">
                            <span class="step-icon"><i class="fa fa-laptop"></i></span>
                            Technical Skills
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#step4">
                            <span class="step-icon"><i class="fa fa-briefcase"></i></span>
                            Professional Experience
                        </a>
                    </li>

                </ul>

                {{-- TAB CONTENT --}}
                <form id="resumeForm" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="tab-content">

                        {{-- STEP 1 --}}
                        <div class="tab-pane fade show active" id="step1">
                            <div class="pd-20">
                                @include('backend.resume.partials.personal-info')

                                <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                    <button type="submit" value="1" class="btn btn-primary nextBtn" data-step="1">
                                        Save & Next
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2 --}}
                        <div class="tab-pane fade" id="step2">
                            <div class="pd-20">
                                @include('backend.resume.partials.education')

                                <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                    <button type="submit" value="2" class="btn btn-primary nextBtn" data-step="2">
                                        Save & Next
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 3 --}}
                        <div class="tab-pane fade" id="step3">
                            <div class="pd-20">
                                @include('backend.resume.partials.technical-skills')

                                <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                    <button type="submit" value="3" class="btn btn-primary nextBtn" data-step="3">
                                        Save & Next
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 4 --}}
                        <div class="tab-pane fade" id="step4">
                            <div class="pd-20">
                                @include('backend.resume.partials.professional-experience')

                                <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                    <button type="submit" value="4" class="btn btn-success nextBtn" data-step="4">
                                        Final Submit
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <x-backend.footer />
    </div>
@endsection


@push('scripts')
{{-- Sweetalert --}}
<script src="{{ asset('backend/assets/sweetalert/sweetalert2.all.min.js') }}"></script>
{{-- ✅ CUSTOM JS --}}
<script src="{{ asset('backend/assets/scripts/resume-wizard.js') }}" ></script>

<script>
    window.resumeRoutes = {
        step1: "{{ route('resume.step1') }}",

        // ✅ DUMMY ID FIX
        step2: "{{ route('resume.step2', ['id' => '__ID__']) }}",
        step3: "{{ route('resume.step3', ['id' => '__ID__']) }}",
        step4: "{{ route('resume.step4', ['id' => '__ID__']) }}",

        // ✅ DRAFT ROUTE FIX
        draft: "{{ route('resume.draft', ['id' => '__ID__']) }}",

        index: "{{ route('resume.index') }}"
    };
</script>

@endpush