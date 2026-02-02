<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Login - Presensi KEMNAKER</title>
    <link rel="apple-touch-icon" href="{{ asset('assets') }}/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets') }}/app-assets/images/logo/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/extensions/toastr.min.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/plugins/extensions/ext-component-toastr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/css/pages/page-auth.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-v2">
                    <div class="auth-inner row m-0">
                        <!-- Brand logo--><a class="brand-logo" href="javascript:void(0);">
                            <img src="{{ asset('assets/app-assets/images/logo/logo-text.png') }}" alt="" srcset="" style="width: 200px">
                        </a>
                        <!-- /Brand logo-->
                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="{{ asset('assets') }}/app-assets/images/pages/login-v2.svg" alt="Login V2" /></div>
                        </div>
                        <!-- /Left Text-->
                        <!-- Login-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <h2 class="card-title font-weight-bold mb-1">Presensi KEMNAKER <small>V 2.0.0</small></h2>
                                <form class="auth-login-form mt-2" action="{{ url('/login') }}" method="POST" autocomplete="off">
                                    @csrf
                                    <div id="login-input">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control" id="email" type="text" name="email" placeholder="Masukkan Email" aria-describedby="email" autofocus="" tabindex="1" />
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="password">Password</label>
                                            </div>
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="password" type="password" name="password" placeholder="············" aria-describedby="password" tabindex="2" />
                                                <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="remember-me" type="checkbox" tabindex="3" />
                                                <label class="custom-control-label" for="remember-me"> Remember Me</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-block" tabindex="4" type="submit">Login</button>
                                    </div>
                                    <button class="btn btn-primary btn-block" tabindex="4" type="button" id="btnLoginAdmin">Login sebagai Admin</button>
                                    <a href="{{$sso_url}}" class="btn btn-primary btn-block" tabindex="4" type="button" id="btnLoginTu">Login sebagai Tata Usaha</a>
                                </form>
                                {{-- <p class="text-center mt-2"><a href="{{ $sso_url }}"><span>&nbsp;Masuk menggunakan akun KEMNAKER</span></a></p> --}}
                                <p class="text-center mt-2"><a href="https://account.kemnaker.go.id/auth/forgot-password" target="_blank"><span>Lupa Password?</span></a></p>
                                <p class="text-center"><a href="{{ url('kebijakan-privasi') }}" target="_blank"><span>&nbsp;Kebijakan Privasi</span></a></p>
                            </div>
                        </div>
                        <!-- /Login-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('assets') }}/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('assets') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('assets') }}/app-assets/js/core/app-menu.js"></script>
    <script>
        let assetPath = '{{ asset("assets/app-assets") }}/'
    </script>
    <script src="{{ asset('assets') }}/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('assets') }}/app-assets/js/scripts/pages/page-auth-login.js"></script>

    <script src="{{ asset('assets') }}/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page JS-->

    <script>
        $(document).ready(function () {
            $('#login-input').hide()
            $('#btnLoginAdmin').on('click',function(){
                $('#login-input').show()
                $(this).hide()
            })
            $('#btnLoginTu').on('click',function(){
                $('#login-input').hide()
                $('#btnLoginAdmin').show()
            })
        });
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
            @if (Session::get('error-login'))
                toastr['error'](
                    'Anda tidak memiliki akses untuk login!',
                    'Login gagal',
                    {
                        closeButton: true,
                        tapToDismiss: false,
                    }
                );
            @endif
            @if (Session::get('error'))
                toastr['error'](
                    '{{ Session::get('error') }}',
                    'Login gagal',
                    {
                        closeButton: true,
                        tapToDismiss: false,
                    }
                );
            @endif

        })
    </script>
</body>
<!-- END: Body-->

</html>
