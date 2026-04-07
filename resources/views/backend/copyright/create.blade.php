@extends('backend.layouts.master')

@section('title')
Create Copyright
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h4>Create Copyright</h4>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('copyrights.index') }}">Manage Copyright</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('copyrights.store') }}" method="POST">
            @csrf

            <div class="card-box pd-20 mb-30">

                <h5 class="text-danger"><b>Copyright Info</b></h5>
                <hr>

                <div class="row">

                    {{-- COPYRIGHT TEXT --}}
                    <div class="col-md-8">
                        <div class="form-group">
                            <label><b>Copyright Text : <span class="text-danger">*</span></b></label>
                            <input type="text" name="copyright_text" class="form-control @error('copyright_text') is-invalid @enderror" value="{{ old('copyright_text') }}" placeholder="Enter copyright text">
                            @error('copyright_text')
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
                                <option value="active" {{ old('status')=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="text-right mt-3">
                    <a href="{{ route('copyrights.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Copyright</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
