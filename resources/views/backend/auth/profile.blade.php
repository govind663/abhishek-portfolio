@extends('backend.layouts.master')

@section('title')
    Edit Profile
@endsection

@push('styles')
@endpush

@section('content')
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Edit Profile</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Edit Profile
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                    <div class="pd-20 card-box height-100-p">
                        <div class="profile-photo">
                            <img src="{{ asset('/backend/assets/uploads/profile/' . Auth::user()->profile_image) }}" alt="" class="avatar-photo" style="width: 170px !important; height: 174px !important;"/>
                            
                        </div>
                        <h5 class="text-center h5 mb-0">
                            {{ Auth::user()->name }}
                        </h5>
                        <p class="text-center text-muted font-14">
                            @if (Auth::user()->role == 'admin')
                                <span class="badge badge-danger">Admin</span>

                            @elseif (Auth::user()->role == 'user')
                                <span class="badge badge-primary">User</span>
                            @endif
                        </p>
                        <div class="profile-info">
                            <h5 class="mb-20 h5 text-blue">Contact Information</h5>
                            <ul>
                                <li>
                                    <span>Email Address:</span>
                                    {{ Auth::user()->email }}
                                </li>
                                <li>
                                    <span>Phone Number:</span>
                                    {{ Auth::user()->phone }}
                                </li>
                                {{-- <li>
                                    <span>Country:</span>
                                    India
                                </li>
                                <li>
                                    <span>Address:</span>
                                    1807 Holden Street<br />
                                    San Diego, CA 92115
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
                    <div class="card-box height-100-p overflow-hidden">
                        <div class="profile-tab height-100-p">
                            <div class="tab height-100-p">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#setting"  role="tab">
                                            Update Profile
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active height-100-p" id="setting" role="tabpanel">
                                        <div class="profile-setting">

                                            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <ul class="profile-edit-list row">

                                                    <!-- LEFT SIDE -->
                                                    <li class="weight-500 col-md-12">

                                                        <div class="row">
                                                            <!-- Full Name -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Full Name : <span class="text-danger">*</span></b></label>
                                                                <input class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                                    type="text"
                                                                    name="name"
                                                                    id="name"
                                                                    value="{{ Auth::user()->name }}">
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Email Id : <span class="text-danger">*</span></b></label>
                                                                <input class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                                    type="email"
                                                                    name="email"
                                                                    id="email"
                                                                    value="{{ Auth::user()->email }}">
                                                                @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <!-- Phone -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Phone : <span class="text-danger">*</span></b></label>
                                                                <input class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                                                    type="text"
                                                                    name="phone"
                                                                    id="phone"
                                                                    value="{{ Auth::user()->phone }}">
                                                                @error('phone')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <!-- Profile Image -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Profile Image : </label>
                                                                <input class="form-control form-control-lg @error('profile_image') is-invalid @enderror" type="file" name="profile_image" id="profile_image" onchange="agentPreviewFile()" accept=".png, .jpg, .jpeg, .webp" value="{{ old('profile_image') }}">
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
                                                                <div id="preview-container">
                                                                    <div id="file-preview"></div>
                                                                </div>
                                                            </div>  
                                                        </div>

                                                        <div class="form-group mb-0">
                                                            <input type="submit" class="btn btn-primary" value="Update Information">
                                                        </div>

                                                    </li>

                                                </ul>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Start -->
        <x-backend.footer />
        <!-- Footer Start -->
    </div>
@endsection

@push('scripts')
{{-- preview both image and PDF --}}
<script>
    function agentPreviewFile() {
        const fileInput = document.getElementById('profile_image');
        const previewContainer = document.getElementById('preview-container');
        const filePreview = document.getElementById('file-preview');
        const file = fileInput.files[0];

        if (file) {
            const fileType = file.type;
            const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            const validPdfTypes = ['application/pdf'];

            if (validImageTypes.includes(fileType)) {
                // Image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    filePreview.innerHTML = `<img src="${e.target.result}" alt="File Preview" width="50%" height="50">`;
                };
                reader.readAsDataURL(file);
            } else if (validPdfTypes.includes(fileType)) {
                // PDF preview using an embed element
                filePreview.innerHTML =
                    `<embed src="${URL.createObjectURL(file)}" type="application/pdf" width="100%" height="150px" />`;
            } else {
                // Unsupported file type
                filePreview.innerHTML = '<p>Unsupported file type</p>';
            }

            previewContainer.style.display = 'block';
        } else {
            // No file selected
            previewContainer.style.display = 'none';
        }

    }

</script>
@endpush
