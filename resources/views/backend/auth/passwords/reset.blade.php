<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    {{-- SEO --}}
    <meta name="description" content="Reset Password to your Abhishek Portfolio account to manage your profile, projects, and settings securely.">
    <meta name="keywords" content="Abhishek Portfolio Reset Password, User Reset Password, Secure Reset Password, Abhishek Account Access, Portfolio Reset Password">
    <meta name="author" content="Abhishek Jha">
    <meta name="robots" content="noindex, nofollow">

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/assets/favicon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/assets/favicon.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/assets/favicon.png') }}" />

    <!-- Title -->
    <title>{{ config('app.name', 'Abhishek Portfolio') }} | Reset Password</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/assets/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/assets/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/assets/vendors/styles/style.css') }}" />

    <!-- Toaster Message -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('admin.login') }}">
                    <img src="{{ asset('/backend/assets/img/logo/abhishek-potfolio_white_logo.webp') }}" alt="" />
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><a href="{{ route('admin.login') }}">{{ __('Login') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('backend/assets/vendors/images/register-page-img.png') }}" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title d-flex flex-column align-items-center justify-content-center">
                            <img src="{{ asset('/backend/assets/img/logo/abhishek-potfolio_white_logo.webp') }}"
                                alt="logo"
                                style="width: 280px !important; height: 100px !important;" />
                            <h2 class="text-primary mt-3">Reset Password</h2>
                        </div>
                        <form method="POST" action="{{ route('admin.password.update') }}" aria-label="{{ __('Register') }}" enctype="multipart/form">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="input-group custom">
                                <input id="text" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Username">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user"></i></span>
                                </div>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="input-group custom">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email Id">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-email"></i></span>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="input-group custom">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="password" placeholder="Password">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="input-group custom">
                                <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="password_confirmation" placeholder="Confirm Password">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <button class="btn btn-primary w-100" type="submit">
                                            {{ __('Reset Password') }}
                                        </button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">I have an account ?
                                            <a href="{{ route('admin.login') }}" class="fw-semibold text-primary">
                                                <b>
                                                    {{ __('Sign In') }}
                                                </b>
                                            </a>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="{{ asset('/backend/assets/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('/backend/assets/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('/backend/assets/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('/backend/assets/vendors/scripts/layout-settings.js') }}"></script>

    <script>
        @if (Session::has('message'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.success("{{ session('message') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
</body>

</html>
