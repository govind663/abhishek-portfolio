@extends('backend.layouts.master')

@section('title')
Update Skill
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Update Skill</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('skills.index') }}">Manage Skills</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Update Skill
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('skills.update', $skill->id) }}" method="POST" class="form-horizontal" id="stat-form" enctype="multipart/form-data" data-parsley-validate novalidate>
            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">

                {{-- BASIC INFO --}}
                <h5 class="text-danger"><b>Skill Info</b></h5>
                <hr>

                <div class="row">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Name : <span class="text-danger">*</span></b></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $skill->name) }}"
                                placeholder="Enter skill name">
                            @error('name') 
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    {{-- Percentage --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Percentage : <span class="text-danger">*</span></b></label>
                            <input type="number" name="percentage" min="0" max="100"
                                class="form-control @error('percentage') is-invalid @enderror"
                                value="{{ old('percentage', $skill->percentage) }}"
                                placeholder="Enter percentage">
                            @error('percentage') 
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span> 
                            @enderror
                        </div>
                    </div>

                    {{-- Group --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Group : <span class="text-danger">*</span></b></label>
                            <input type="text" name="group"
                                class="form-control @error('group') is-invalid @enderror"
                                value="{{ old('group', $skill->group) }}"
                                placeholder="Enter group name (e.g. Frontend, Backend)">
                            @error('group') 
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
                                <option value="active" {{ old('status', $skill->status)=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ old('status', $skill->status)=='inactive'?'selected':'' }}>Inactive</option>
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
                    <a href="{{ route('skills.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Skill</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

{{-- 🔥 SCRIPTS --}}
@push('scripts')
@endpush