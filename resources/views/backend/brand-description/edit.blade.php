@extends('backend.layouts.master')

@section('title')
Update Brand Description
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Update Brand Description</h4>
                    </div>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('brand-description.index') }}">Manage Brand Description</a>
                            </li>
                            <li class="breadcrumb-item active">Update</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('brand-description.update', $brandDescription->id) }}" method="POST" class="form-horizontal" id="hero-form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" accept-charset="UTF-8" data-toggle="validator') }}">
            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">

                <h5 class="text-danger"><b>Brand Info</b></h5>
                <hr>

                <div class="row">

                    {{-- LOGO --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Brand Logo : <span class="text-danger">*</span></b></label>
                            <input type="file" name="logo" id="logo" 
                                class="form-control @error('logo') is-invalid @enderror" 
                                value="{{ old('logo', $brandDescription->logo) }}" 
                                onchange="previewImage('logo','logo-preview')" 
                                accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                            <br>
                            <small class="text-secondary"><b>Note : Only files in .pdf format can be uploaded .</b></small>
                            <br>
                            @error('logo')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <br>
                            {{-- ✅ Image Preview --}}
                            <div id="logo-preview">
                                @if($brandDescription->logo)
                                    <img src="{{ asset('storage/' . $brandDescription->logo) }}" alt="Brand Logo"
                                    style="width:100px;height:100px;border-radius:8px;object-fit:cover;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- TITLE --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Title : <span class="text-danger">*</span></b></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $brandDescription->title) }}" placeholder="Enter title">
                            @error('title')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Status : <span class="text-danger">*</span></b></label>
                            <select name="status" class="form-control custom-select2 @error('status') is-invalid @enderror">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', $brandDescription->status)=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ old('status', $brandDescription->status)=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Description : <span class="text-danger">*</span></b></label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Enter description">{{ old('description', $brandDescription->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="text-right mt-3">
                    <a href="{{ route('brand-description.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Brand Info</button>
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
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML = `
                <img src="${e.target.result}"
                     style="width:100px;height:100px;object-fit:cover;border-radius:8px;">
            `;
            };

            reader.readAsDataURL(file);
        }
    }

</script>
@endpush
