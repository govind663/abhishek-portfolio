{{-- resources/views/backend/social/edit.blade.php --}}
@extends('backend.layouts.master')

@section('title')
Update Social Link
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Update Social Link</h4>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('social-links.index') }}">Manage Social Links</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Update Social Link
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('social-links.update', $social['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">

                {{-- BASIC INFO --}}
                <h5 class="text-danger"><b>Basic Info</b></h5>
                <hr>

                <div class="row">

                    {{-- Platform --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Platform : <span class="text-danger">*</span></b></label>
                            <input type="text" name="platform"
                                class="form-control @error('platform') is-invalid @enderror"
                                value="{{ old('platform', $social['platform']) }}"
                                placeholder="e.g., Facebook, LinkedIn">

                            @error('platform') 
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
                                value="{{ old('icon', $social['icon']) }}"
                                placeholder="e.g., bi bi-facebook OR fa fa-facebook">

                            <small class="text-secondary">
                                Use Bootstrap Icons or FontAwesome classes
                            </small>

                            {{-- 🔥 Live + Stored Preview --}}
                            <div id="icon-preview" class="mt-3" style="font-size:30px;"></div>

                            @error('icon') 
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    {{-- URL --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>URL : <span class="text-danger">*</span></b></label>
                            <input type="url" name="url"
                                class="form-control @error('url') is-invalid @enderror"
                                value="{{ old('url', $social['url']) }}"
                                placeholder="https://example.com/username">

                            @error('url') 
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
                        <option value="active" {{ old('status', $social['status'])=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status', $social['status'])=='inactive'?'selected':'' }}>Inactive</option>
                    </select>

                    @error('status') 
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span> 
                    @enderror
                </div>

                {{-- SUBMIT --}}
                <div class="text-right mt-4">
                    <a href="{{ route('social-links.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Social Link</button>
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
    const preview = document.getElementById('icon-preview');

    function updatePreview() {
        if (!iconInput || !preview) return;

        const iconClass = iconInput.value.trim();

        preview.innerHTML = iconClass 
            ? `<i class="${iconClass}"></i>` 
            : '';
    }

    // ✅ Live typing + paste
    iconInput.addEventListener('input', updatePreview);

    // ✅ Load stored value on page load
    updatePreview();

});
</script>
@endpush