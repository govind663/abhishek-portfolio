@extends('backend.layouts.master')

@section('title')
Create SEO Record
@endsection

@section('content')
<div class="pd-ltr-20 xs-pd-10-10">
    <div class="min-height-200px">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Create SEO Record</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('seo-settings.index') }}">Manage SEO</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Create SEO
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Form  --}}
        <form action="{{ route('seo-settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-box pd-20 mb-30">
                {{-- PAGE --}}
                <h5 class="text-danger"><b>Page Info</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Page Name : <span class="text-danger">*</span></b></label>
                            <input type="text" name="page_name"
                                class="form-control @error('page_name') is-invalid @enderror"
                                value="{{ old('page_name') }}"
                                placeholder="e.g. Home Page / About Us / Contact Page">
                            @error('page_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- BASIC SEO --}}
                <h5 class="text-danger mt-4"><b>Basic SEO</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Title : <span class="text-danger">*</span></b></label>
                            <input type="text" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                placeholder="Best Digital Agency in India | YourBrand">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Keywords : <span class="text-danger">*</span></b></label>
                            <input type="text" name="keywords"
                                class="form-control @error('keywords') is-invalid @enderror"
                                value="{{ old('keywords') }}"
                                placeholder="seo, digital marketing, web development">
                            @error('keywords')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Canonical URL : <span class="text-danger">*</span></b></label>
                            <input type="text" name="canonical"
                                class="form-control @error('canonical') is-invalid @enderror"
                                value="{{ old('canonical') }}"
                                placeholder="https://example.com/page-url">
                            @error('canonical')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Description : <span class="text-danger">*</span></b></label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Write a compelling meta description (150-160 characters recommended)...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>                    
                </div>

                {{-- OPEN GRAPH --}}
                <h5 class="text-danger mt-4"><b>Open Graph</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>OG Title : <span class="text-danger">*</span></b></label>
                            <input type="text" name="og_title"
                                class="form-control @error('og_title') is-invalid @enderror"
                                value="{{ old('og_title') }}"
                                placeholder="Best Digital Agency in India">
                            @error('og_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>OG URL : <span class="text-danger">*</span></b></label>
                            <input type="text" name="og_url"
                                class="form-control @error('og_url') is-invalid @enderror"
                                value="{{ old('og_url') }}"
                                placeholder="https://example.com/page-url">
                            @error('og_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>OG Type : <span class="text-danger">*</span></b></label>
                            <input type="text" name="og_type"
                                class="form-control @error('og_type') is-invalid @enderror"
                                value="{{ old('og_type','website') }}"
                                placeholder="website / article / product">
                            @error('og_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>OG Image : <span class="text-danger">*</span></b></label>
                            <input type="file" name="og_image" id="og_image"
                                class="form-control @error('og_image') is-invalid @enderror"
                                onchange="previewImage('og_image','og-preview')"
                                accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                            <br>
                            <small class="text-secondary"><b>Note : Only files in .jpg, .jpeg, .png, .webp format can be uploaded .</b></small>
                            <br>
                            @error('og_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <br>
                            <div id="og-preview" style="margin-top:10px;"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>OG Description : <span class="text-danger">*</span></b></label>
                            <textarea name="og_description"
                                class="form-control @error('og_description') is-invalid @enderror"
                                placeholder="Short description for social media preview...">{{ old('og_description') }}</textarea>
                            @error('og_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>                    
                </div>

                {{-- TWITTER --}}
                <h5 class="text-danger mt-4"><b>Twitter Card</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Twitter Card : <span class="text-danger">*</span></b></label>
                            <input type="text" name="twitter_card" id="twitter_card"
                                class="form-control @error('twitter_card') is-invalid @enderror"
                                value="{{ old('twitter_card','summary_large_image') }}"
                                placeholder="summary / summary_large_image">
                            @error('twitter_card')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Twitter Title : <span class="text-danger">*</span></b></label>
                            <input type="text" name="twitter_title" id="twitter_title"
                                class="form-control @error('twitter_title') is-invalid @enderror"
                                value="{{ old('twitter_title') }}"
                                placeholder="Twitter optimized title">
                            @error('twitter_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Twitter Image : <span class="text-danger">*</span></b></label>
                            <input type="file" name="twitter_image" id="twitter_image"
                                class="form-control @error('twitter_image') is-invalid @enderror"
                                onchange="previewImage('twitter_image','twitter-preview')"
                                accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                            <br>
                            <small class="text-secondary"><b>Note : Only files in .jpg, .jpeg, .png, .webp format can be uploaded .</b></small>
                            <br>
                            @error('twitter_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <br>
                            <div id="twitter-preview" style="margin-top:10px;"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Twitter Description : <span class="text-danger">*</span></b></label>
                            <textarea name="twitter_description" id="twitter_description"
                                class="form-control @error('twitter_description') is-invalid @enderror"
                                placeholder="Short engaging description for Twitter...">{{ old('twitter_description') }}</textarea>
                            @error('twitter_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>                    
                </div>

                {{-- STATUS --}}
                <h5 class="text-danger mt-4"><b>Status</b></h5>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
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
                    <a href="{{ route('seo-settings.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-success">Save SEO</button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(inputId, previewId) {
    const fileInput = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewId);
    previewContainer.innerHTML = '';

    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const ext = file.name.split('.').pop().toLowerCase();

        if (['jpg','jpeg','png','webp'].includes(ext)) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <img src="${e.target.result}" 
                    style="width:100px; height:100px; border-radius:8px; object-fit:cover;">
                `;
            }

            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = `<p class="text-danger">Unsupported file type</p>`;
        }
    }
}
</script>
@endpush