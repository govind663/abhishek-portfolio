@extends('backend.layouts.master')

@section('title')
Create Stat
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Create Stat</h4>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('stats.index') }}">Manage Stats</a>
                            </li>
                            <li class="breadcrumb-item active">Create Stat</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('stats.store') }}" method="POST">
            @csrf

            <div class="card-box pd-20 mb-30">

                {{-- BASIC INFO --}}
                <h5 class="text-danger"><b>Basic Info</b></h5>
                <hr>

                <div class="row">

                    {{-- TITLE --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Title : <span class="text-danger">*</span></b></label>
                            <input type="text" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                placeholder="Enter title">
                            @error('title')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- COUNT --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Count : <span class="text-danger">*</span></b></label>
                            <input type="number" name="count"
                                class="form-control @error('count') is-invalid @enderror"
                                value="{{ old('count') }}"
                                placeholder="Enter count">
                            @error('count')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- ICON --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Icon (Class Name) : <span class="text-danger">*</span></b></label>
                            <input type="text" name="icon" id="icon"
                                class="form-control @error('icon') is-invalid @enderror"
                                value="{{ old('icon') }}"
                                placeholder="e.g. bi bi-people">
                            <small class="text-secondary">Use Bootstrap Icons or FontAwesome</small>

                            {{-- 🔥 Live Preview --}}
                            <div id="icon-preview" class="mt-3" style="font-size:30px;"></div>

                            @error('icon')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- STATUS --}}
                <h5 class="text-danger mt-4"><b>Status</b></h5>
                <hr>

                <div class="form-group col-md-6">
                    <select name="status"
                        class="form-control custom-select2 @error('status') is-invalid @enderror">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status')=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- SUBMIT --}}
                <div class="text-right mt-4">
                    <a href="{{ route('stats.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Stat</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const iconInput = document.getElementById('icon');
    const preview = document.getElementById('icon-preview');

    function updatePreview() {
        if (!iconInput || !preview) return;

        const iconClass = iconInput.value.trim();

        preview.innerHTML = iconClass 
            ? `<i class="${iconClass}"></i>` 
            : '';
    }

    // ✅ Live preview (typing + paste)
    iconInput.addEventListener('input', updatePreview);

    // ✅ Page load pe preview (old value ke liye)
    updatePreview();

});
</script>
@endpush