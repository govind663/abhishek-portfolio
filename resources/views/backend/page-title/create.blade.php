@extends('backend.layouts.master')

@section('title')
Create Page Title
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Create Page Title</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('page-titles.index') }}">Manage Page Titles</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Create Page Title
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('page-titles.store') }}" method="POST">
            @csrf

            <div class="card-box pd-20 mb-30">

                {{-- BASIC INFO --}}
                <h5 class="text-danger"><b>Basic Info</b></h5>
                <hr>

                <div class="row">

                    {{-- Page Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Page Name : <span class="text-danger">*</span></b></label>
                            <input type="text" name="page_name" id="page_name"
                                class="form-control @error('page_name') is-invalid @enderror"
                                value="{{ old('page_name') }}"
                                placeholder="Enter page name">
                            @error('page_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Title : <span class="text-danger">*</span></b></label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                placeholder="Enter title">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Description : <span class="text-danger">*</span></b></label>
                            <textarea name="description" id="description" rows="4"
                                class="textarea_editor form-control border-radius-0 @error('description') is-invalid @enderror"
                                placeholder="Enter page description">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Status : <span class="text-danger">*</span></b></label>
                            <select name="status" id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status')=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- SUBMIT --}}
                <div class="text-right mt-4">
                    <a href="{{ route('page-titles.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Page Title</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
@endpush