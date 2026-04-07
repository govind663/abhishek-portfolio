@extends('backend.layouts.master')

@section('title')
Update Contact Info
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Update Contact Info</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('contacts.index') }}">Manage Contacts</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Update Contact
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('contacts.update', $contact->id) }}" 
            method="POST" 
            class="form-horizontal" 
            id="contact-form" 
            enctype="multipart/form-data"
            data-parsley-validate 
            novalidate>
            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">
                {{-- CONTACT INFO --}}
                <h5 class="text-danger"><b>Contact Information</b></h5>
                <hr>

                <div class="row">
                    {{-- Phone --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Phone : <span class="text-danger">*</span></b></label>
                            <input type="text" name="phone" id="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $contact->phone) }}"
                                placeholder="Enter 10-digit phone number">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Email Id : <span class="text-danger">*</span></b></label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $contact->email) }}"
                                placeholder="Enter email address">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Address <span class="text-danger">*</span></b></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter full address">{{ old('address', $contact->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Working Hours --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Working Hours <span class="text-danger">*</span></b></label>
                            <input type="text" name="working_hours" id="working_hours"
                                class="form-control @error('working_hours') is-invalid @enderror"
                                value="{{ old('working_hours', $contact->working_hours) }}"
                                placeholder="e.g., Mon-Fri 9AM - 6PM">
                            @error('working_hours')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Status <span class="text-danger">*</span></b></label>
                            <select name="status"  id="status" class="form-control custom-select2 @error('status') is-invalid @enderror">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', $contact->status)=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ old('status', $contact->status)=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="text-right mt-4">
                    <a href="{{ route('contacts.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Contact</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection