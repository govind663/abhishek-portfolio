@extends('backend.layouts.master')

@section('title')
Update Hero Section
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Update Hero Section</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('hero.index') }}">Manage Hero</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Update Hero
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <form action="{{ route('hero.update', $hero->id) }}" method="POST" class="form-horizontal" id="hero-form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" accept-charset="UTF-8" data-toggle="validator') }}">
            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">

                {{-- BASIC INFO --}}
                <h5 class="text-danger"><b>Basic Info</b></h5>
                <hr>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Name : <span class="text-danger">*</span></b></label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $hero->name) }}"
                                placeholder="Enter your name">
                            @error('name') 
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Subtitle : <span class="text-danger">*</span></b></label>
                            <input type="text" name="subtitle" id="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', $hero->subtitle) }}"
                                placeholder="Enter subtitle">
                            @error('subtitle') 
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Description : <span class="text-danger">*</span></b></label>
                            <textarea name="description" id="description" rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Write a compelling meta description (150-160 characters recommended)...">{{ old('description', $hero->description) }}</textarea>
                            @error('description') 
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- TYPED ITEMS --}}
                <h5 class="text-danger mt-4"><b>Typing Animation</b></h5>
                <hr>

                <div class="form-group col-md-6">
                    <label><b>Typed Items : <span class="text-danger">*</span></b></label>
                    <input type="text" name="typed_items" id="typed_items"
                        class="form-control @error('typed_items') is-invalid @enderror"
                        value="{{ old('typed_items', isset($hero) ? implode(', ', $hero->typed_items ?? []) : '') }}"
                        placeholder="Developer, Designer, Freelancer, etc.">
                    <small class="text-secondary">Comma separated values</small>
                    @error('typed_items') 
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span> 
                    @enderror
                </div>

                {{-- FILE UPLOADS --}}
                <h5 class="text-danger mt-4"><b>Files Upload</b></h5>
                <hr>

                <div class="row">

                    {{-- Profile Image --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Profile Image : <span class="text-danger">*</span></b></label>
                            <input type="file" name="profile_image" id="profile_image"
                                class="form-control @error('profile_image') is-invalid @enderror"
                                onchange="previewImage('profile_image','profile-preview')"
                                value="{{ old('profile_image', $hero->profile_image) }}"
                                accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                            <br>
                            <small class="text-secondary"><b>Note : Only files in .jpg, .jpeg, .png, .webp format can be uploaded .</b></small>
                            <br>
                            @error('profile_image') 
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                            <br>
                            {{-- ✅ Image Preview --}}
                            <div id="profile-preview">
                                @if($hero->profile_image)
                                    <img src="{{ $hero->profile_image }}" 
                                    style="width:100px;height:100px;border-radius:8px;object-fit:cover;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Background Image --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Background Image : <span class="text-danger">*</span></b></label>
                            <input type="file" name="background_image" id="background_image"
                                class="form-control @error('background_image') is-invalid @enderror"
                                onchange="previewImage('background_image','bg-preview')"
                                accept=".mp4">
                            <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                            <br>
                            <small class="text-secondary"><b>Note : Only files in .mp4 format can be uploaded .</b></small>
                            <br>
                            @error('background_image') 
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                            <br>
                            @php use Illuminate\Support\Str; @endphp
                            {{-- ✅ Image Preview --}}
                            <div id="bg-preview">
                                @if($hero->background_image)
                                    @if(Str::endsWith($hero->getRawOriginal('background_image'), '.mp4'))

                                        {{-- ✅ VIDEO --}}
                                        <video width="100%" height="200"
                                            autoplay muted loop playsinline
                                            style="border-radius:8px; object-fit:cover;">
                                            <source src="{{ asset('storage/' . $hero->getRawOriginal('background_image')) }}" type="video/mp4">
                                        </video>

                                    @else

                                        {{-- ✅ IMAGE --}}
                                        <img src="{{ $hero->background_image }}" 
                                            style="width:100px;height:100px;border-radius:8px;object-fit:cover;">

                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Resume --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Resume (PDF) : <span class="text-danger">*</span></b></label>
                            <input type="file" name="resume_file" id="resume_file"
                                class="form-control @error('resume_file') is-invalid @enderror"
                                onchange="previewFile('resume_file','resume-preview')"
                                accept=".pdf">
                            <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                            <br>
                            <small class="text-secondary"><b>Note : Only files in .pdf format can be uploaded .</b></small>
                            <br>
                            @error('resume_file') 
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                            <br>
                            {{-- ✅ PDF Preview --}}
                            <div id="resume-preview">
                                @if($hero->resume_file)
                                    <div style="margin-top:10px;">
                                        <a href="{{ asset('storage/' . $hero->resume_file) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i> View Current Resume
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- STATUS --}}
                <h5 class="text-danger mt-4"><b>Status</b></h5>
                <hr>

                <div class="form-group col-md-6">
                    <select name="status" id="status"
                        class="form-control custom-select2 @error('status') is-invalid @enderror">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $hero->status)=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status', $hero->status)=='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    @error('status') 
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span> 
                    @enderror
                </div>

                {{-- SUBMIT --}}
                <div class="text-right mt-4">
                    <a href="{{ route('hero.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Hero</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(inputId, previewId) {
    const file = document.getElementById(inputId).files[0];
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (file) {

        // ✅ IMAGE CASE
        if (file.type.startsWith('image/')) {

            const reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" 
                    style="width:100px;height:100px;border-radius:8px;object-fit:cover;">
                `;
            };

            reader.readAsDataURL(file);
        }

        // ✅ VIDEO (MP4)
        else if (file.type === "video/mp4") {

            const fileURL = URL.createObjectURL(file);

            preview.innerHTML = `
                <video width="100%" height="200" controls style="border-radius:8px;">
                    <source src="${fileURL}" type="video/mp4">
                </video>
            `;
        }
    }
}

function previewFile(inputId, previewId) {
    const fileInput = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];

        if (file.type === "application/pdf") {
            const fileURL = URL.createObjectURL(file);

            preview.innerHTML = `
                <div style="margin-top:10px;">
                    <a href="${fileURL}" target="_blank" 
                       class="btn btn-sm btn-primary">
                       <i class="fa fa-eye"></i> View PDF
                    </a>
                </div>
            `;
        } else {
            preview.innerHTML = `<p class="text-danger">Only PDF file allowed</p>`;
        }
    }
}
</script>
@endpush