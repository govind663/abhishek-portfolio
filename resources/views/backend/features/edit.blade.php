@extends('backend.layouts.master')

@section('title')
Update Feature
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Update Feature</h4>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('features.index') }}">Manage Features</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Update Feature
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('features.update', $feature->id) }}" method="POST" class="form-horizontal" id="feature-form" enctype="multipart/form-data" data-parsley-validate novalidate>
            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">

                {{-- BASIC INFO --}}
                <h5 class="text-danger"><b>Feature Info</b></h5>
                <hr>

                <div class="row">

                    {{-- Title --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Title : <span class="text-danger">*</span></b></label>
                            <input type="text" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $feature->title) }}"
                                placeholder="Enter feature title">
                            @error('title') 
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Icon : <span class="text-danger">*</span></b></label>
                            
                            <input type="text" name="icon" id="icon"
                                class="form-control @error('icon') is-invalid @enderror"
                                value="{{ old('icon', $feature->icon) }}"
                                placeholder="e.g. bi bi-code-slash OR fa fa-html5">

                            <small class="text-secondary">
                                Use Bootstrap Icons or FontAwesome
                            </small>

                            {{-- 🔥 Live Preview --}}
                            <div id="icon-preview" class="mt-3" style="font-size:30px;"></div>

                            @error('icon') 
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    {{-- Color --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Color : <span class="text-danger">*</span></b></label>
                            
                            <input type="color" name="color"
                                class="form-control @error('color') is-invalid @enderror"
                                value="{{ old('color', $feature->color ?? '#000000') }}">

                            {{-- 🎨 Live Color Preview --}}
                            <div id="color-preview" class="mt-2" 
                                 style="width:50px;height:50px;border-radius:6px;border:1px solid #ccc;">
                            </div>

                            @error('color') 
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Status : <span class="text-danger">*</span></b></label>
                            <select name="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', $feature->status)=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ old('status', $feature->status)=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                            @error('status') 
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- SUBMIT --}}
                <div class="text-right mt-4">
                    <a href="{{ route('features.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Feature</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

{{-- 🔥 SCRIPTS --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');

    const colorInput = document.querySelector('input[name="color"]');
    const colorPreview = document.getElementById('color-preview');

    // 🔥 Icon Preview
    function updateIconPreview() {
        if (!iconInput || !iconPreview) return;

        const iconClass = iconInput.value.trim();
        iconPreview.innerHTML = iconClass 
            ? `<i class="${iconClass}"></i>` 
            : '';
    }

    // 🎨 Color Preview
    function updateColorPreview() {
        if (!colorInput || !colorPreview) return;

        colorPreview.style.backgroundColor = colorInput.value;
    }

    // Events
    iconInput.addEventListener('input', updateIconPreview);
    colorInput.addEventListener('input', updateColorPreview);

    // Load on page
    updateIconPreview();
    updateColorPreview();

});
</script>
@endpush