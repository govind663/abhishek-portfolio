@extends('backend.layouts.master')

@section('title')
    Update Resume
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

.custom-toast {
    font-family: sans-serif;
}

.toast-progress {
    height: 3px;
    background: #fff;
    margin-top: 6px;
    width: 100%;
    animation: toastProgress 2.5s linear;
}

@keyframes toastProgress {
    from { width: 100%; }
    to { width: 0%; }
}

.disabled-tab {
    pointer-events: none !important;
    opacity: 0.5;
    cursor: not-allowed;
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
                    <h4>Update Resume</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('resume.index') }}">Manage Resumes</a>
                        </li>
                        <li class="breadcrumb-item active">Update Resume</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="pd-20 card-box mb-30">

        {{-- NAV --}}
        <ul class="nav nav-tabs customtab">

            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#step1">Personal</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#step2">Education</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#step3">Skills</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#step4">Experience</a>
            </li>

        </ul>

        {{-- FORM --}}
        <form id="resumeForm" enctype="multipart/form-data" novalidate>
            @csrf

            <input type="hidden" id="resume_id" value="{{ $resume->id }}">

            <div class="tab-content">

                {{-- STEP 1 --}}
                <div class="tab-pane fade show active" id="step1">
                    <div class="pd-20">

                        @include('backend.resume.partials.personal-info', ['resume' => $resume])

                        <div class="d-flex justify-content-between mt-3">
                            <div></div>
                            <button type="button" class="btn btn-primary nextBtn" data-step="1">
                                Update & Next
                            </button>
                        </div>

                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="tab-pane fade" id="step2">
                    <div class="pd-20">

                        @include('backend.resume.partials.education', ['resume' => $resume])

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary prevBtn" data-step="2">
                                ← Back
                            </button>

                            <button type="button" class="btn btn-primary nextBtn" data-step="2">
                                Update & Next
                            </button>
                        </div>

                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="tab-pane fade" id="step3">
                    <div class="pd-20">

                        @include('backend.resume.partials.technical-skills', ['resume' => $resume])

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary prevBtn" data-step="3">
                                ← Back
                            </button>

                            <button type="button" class="btn btn-primary nextBtn" data-step="3">
                                Update & Next
                            </button>
                        </div>

                    </div>
                </div>

                {{-- STEP 4 --}}
                <div class="tab-pane fade" id="step4">
                    <div class="pd-20">

                        @include('backend.resume.partials.professional-experience', ['resume' => $resume])

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary prevBtn" data-step="4">
                                ← Back
                            </button>

                            <button type="button" class="btn btn-success nextBtn" data-step="4">
                                Final Update
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
<script src="{{ asset('backend/assets/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('backend/assets/scripts/resume-wizard.js') }}"></script>

<script>
    window.resumeConfig = {
        id: "{{ $resume->id ?? '' }}",
        mode: "{{ isset($resume) ? 'update' : 'create' }}"
    };

    // 🔥 always use dynamic getter (important for autosave updates)
    window.getResumeId = () => window.resumeConfig.id;

    window.resumeRoutes = {

        /*
        |--------------------------------------------------------------------------
        | STEP ROUTES (CREATE + UPDATE)
        |--------------------------------------------------------------------------
        */
        get(step) {

            const id = window.getResumeId();
            const mode = window.resumeConfig.mode;

            const routes = {
                create: {
                    step1: "{{ route('resume.create.step1') }}",
                    step2: "{{ route('resume.create.step2', ['id' => '__ID__']) }}",
                    step3: "{{ route('resume.create.step3', ['id' => '__ID__']) }}",
                    step4: "{{ route('resume.create.step4', ['id' => '__ID__']) }}",
                },
                update: {
                    step1: "{{ route('resume.update.step1', ['id' => '__ID__']) }}",
                    step2: "{{ route('resume.update.step2', ['id' => '__ID__']) }}",
                    step3: "{{ route('resume.update.step3', ['id' => '__ID__']) }}",
                    step4: "{{ route('resume.update.step4', ['id' => '__ID__']) }}",
                }
            };

            let url = routes[mode][`step${step}`];

            // 🔥 Step1 doesn't need ID
            if (step !== 1 && !id) {
                console.warn(`⚠️ Resume ID missing for step ${step}`);
                return null;
            }

            if (url && url.includes('__ID__')) {
                url = url.replace('__ID__', id);
            }

            return url;
        },

        /*
        |--------------------------------------------------------------------------
        | DRAFT SAVE
        |--------------------------------------------------------------------------
        */
        draft() {

            const id = window.getResumeId();

            if (!id) {
                console.warn("⚠️ Draft skipped (No Resume ID)");
                return null;
            }

            return "{{ route('resume.draft', ['id' => '__ID__']) }}"
                .replace('__ID__', id);
        },

        /*
        |--------------------------------------------------------------------------
        | GET DRAFT
        |--------------------------------------------------------------------------
        */
        getDraft() {

            const id = window.getResumeId();

            if (!id) return null;

            return "{{ route('resume.draft.get', ['id' => '__ID__']) }}"
                .replace('__ID__', id);
        },

        /*
        |--------------------------------------------------------------------------
        | INDEX REDIRECT
        |--------------------------------------------------------------------------
        */
        index: "{{ route('resume.index') }}"
    };
</script>
@endpush