@extends('backend.layouts.master')

@section('title')
Create About Section
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Create About Section</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('about.index') }}">Manage About</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Create About
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <form action="{{ route('about.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-box pd-20 mb-30">

                {{-- BASIC INFO --}}
                <h5 class="text-danger"><b>Basic Info</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Name : <span class="text-danger">*</span></b></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter name">
                            @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Subtitle : <span class="text-danger">*</span></b></label>
                            <input type="text" name="subtitle" id="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle') }}" placeholder="Enter subtitle">
                            @error('subtitle')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Description : <span class="text-danger">*</span></b></label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Enter description">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- FILE UPLOADS --}}
                <h5 class="text-danger mt-4"><b>Profile Image</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Profile Image : <span class="text-danger">*</span></b></label>
                            <input type="file" name="profile_image" id="profile_image" 
                                class="form-control @error('profile_image') is-invalid @enderror" 
                                onchange="previewImage('profile_image','profile-preview')" 
                                accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                            <br>
                            <small class="text-secondary"><b>Note : Only files in .pdf format can be uploaded .</b></small>
                            <br>
                            @error('profile_image')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div id="profile-preview" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                {{-- EXTRA INFO --}}
                <h5 class="text-danger mt-4"><b>Additional Info</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Experience : <span class="text-danger">*</span></b></label>
                            <input type="text" name="experience" value="{{ old('experience') }}" class="form-control @error('experience') is-invalid @enderror" placeholder="e.g. 5 Years">
                            @error('experience')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Specialization : <span class="text-danger">*</span></b></label>
                            <input type="text" name="specialization" value="{{ old('specialization') }}" class="form-control @error('specialization') is-invalid @enderror" placeholder="e.g. Web Development">
                            @error('specialization')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label><b>Phone : <span class="text-danger">*</span></b></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. +1234567890">
                            @error('phone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label><b>Location : <span class="text-danger">*</span></b></label>
                            <input type="text" name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="e.g. Mumbai, India">
                            @error('location')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label><b>Role : <span class="text-danger">*</span></b></label>
                            <input type="text" name="role" value="{{ old('role') }}" class="form-control @error('role') is-invalid @enderror" placeholder="e.g. Full Stack Developer">
                            @error('role')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label><b>Database : <span class="text-danger">*</span></b></label>
                            <input type="text" name="database" value="{{ old('database') }}" class="form-control @error('database') is-invalid @enderror" placeholder="e.g. MySQL, MongoDB">
                            @error('database')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label><b>Email : <span class="text-danger">*</span></b></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="e.g. example@mail.com">
                            @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label><b>Freelance : <span class="text-danger">*</span></b></label>
                            <input type="text" name="freelance" value="{{ old('freelance') }}" class="form-control @error('freelance') is-invalid @enderror" placeholder="e.g. Available / Not Available">
                            @error('freelance')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <label><b>Extra Description : <span class="text-danger">*</span></b></label>
                            <textarea name="extra_description" rows="4" class="form-control @error('extra_description') is-invalid @enderror" placeholder="Additional info">{{ old('extra_description') }}</textarea>
                            @error('extra_description')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- STATUS --}}
                <h5 class="text-danger mt-4"><b>Status</b></h5>
                <hr>

                <div class="form-group col-md-6">
                    <select name="status" id="status" class="form-control custom-select2 @error('status') is-invalid @enderror">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status')=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    @error('status')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- SUBMIT --}}
                <div class="text-right mt-4">
                    <a href="{{ route('about.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Save About</button>
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

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" 
                style="width:100px;height:100px;border-radius:8px;object-fit:cover;">`;
            };
            reader.readAsDataURL(file);
        }
    }

</script>
@endpush
