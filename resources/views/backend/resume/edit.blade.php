@extends('backend.layouts.master')

@section('title')
    Update Resume
@endsection

@push('styles')
{{-- SAME CSS (NO CHANGE) --}}
<style>
.form-control.is-invalid {
    border: 1px solid #dc3545 !important;
}
.invalid-feedback {
    display: block !important;
    color: #dc3545 !important;
    font-weight: 600;
}
.select2-container--default .select2-selection--single.is-invalid {
    border: 1px solid #dc3545 !important;
}
.note-editor.is-invalid,
.tox.is-invalid,
.cke.is-invalid {
    border: 1px solid #dc3545 !important;
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
        <form id="resumeForm" enctype="multipart/form-data">
            @csrf

            {{-- IMPORTANT --}}
            <input type="hidden" id="resume_id" value="{{ $resume->id }}">

            <div class="tab-content">

                {{-- STEP 1 --}}
                <div class="tab-pane fade show active" id="step1">
                    <div class="pd-20">

                        @include('backend.resume.partials.personal-info', ['resume' => $resume])

                        <button type="submit" value="1" class="btn btn-primary nextBtn">
                            Update & Next
                        </button>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="tab-pane fade" id="step2">
                    <div class="pd-20">

                        @include('backend.resume.partials.education', ['resume' => $resume])

                        <button type="submit" value="2" class="btn btn-primary nextBtn">
                            Update & Next
                        </button>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="tab-pane fade" id="step3">
                    <div class="pd-20">

                        @include('backend.resume.partials.technical-skills', ['resume' => $resume])

                        <button type="submit" value="3" class="btn btn-primary nextBtn">
                            Update & Next
                        </button>
                    </div>
                </div>

                {{-- STEP 4 --}}
                <div class="tab-pane fade" id="step4">
                    <div class="pd-20">

                        @include('backend.resume.partials.professional-experience', ['resume' => $resume])

                        <button type="submit" value="4" class="btn btn-success nextBtn">
                            Final Update
                        </button>
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
const resumeId = "{{ $resume->id }}";

/*
|--------------------------------------------------------------------------
| ROUTES (EDIT MODE - FINAL FIX)
|--------------------------------------------------------------------------
*/
window.resumeRoutes = {

    // =========================
    // UPDATE FLOW
    // =========================
    step1: "{{ route('resume.update.step1', ['id' => '__ID__']) }}",
    step2: "{{ route('resume.update.step2', ['id' => '__ID__']) }}",
    step3: "{{ route('resume.update.step3', ['id' => '__ID__']) }}",
    step4: "{{ route('resume.update.step4', ['id' => '__ID__']) }}",

    // =========================
    // DRAFT (FIXED)
    // =========================
    draft: "{{ route('resume.draft', ['id' => '__ID__']) }}",
    getDraft: "{{ route('resume.draft.get', ['id' => '__ID__']) }}",

    // =========================
    index: "{{ route('resume.index') }}"
};


/*
|--------------------------------------------------------------------------
| FORCE RESUME ID (CRITICAL)
|--------------------------------------------------------------------------
*/
localStorage.setItem('resume_id', resumeId);


/*
|--------------------------------------------------------------------------
| PREVENT DOUBLE CLICK (IMPROVED)
|--------------------------------------------------------------------------
*/
$(document).on('click', '.nextBtn', function () {

    let btn = $(this);

    if (btn.data('loading')) return;

    btn.data('loading', true);
    btn.prop('disabled', true);

    setTimeout(() => {
        btn.data('loading', false);
        btn.prop('disabled', false);
    }, 1500);
});


/*
|--------------------------------------------------------------------------
| EXTRA SAFETY (FORM SUBMIT LOCK)
|--------------------------------------------------------------------------
*/
$('#resumeForm').on('submit', function () {
    $('.nextBtn').prop('disabled', true);
});
</script>
@endpush