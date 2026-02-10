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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/login.css">
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
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body>
    <div class="login-wrapper">
        <!-- Full Background -->
        <div class="login-background">
            <img src="{{ asset('assets') }}/app-assets/images/pages/login-v3.png" alt="Office Background" class="bg-image">
        </div>
        
        <!-- Centered Modal -->
        <div class="login-modal">
            <div class="modal-content">
                <!-- Logo Section -->
                <div class="modal-logo">
                    <img src="{{ asset('assets/app-assets/images/logo/logo-text.png') }}" alt="Kementerian Ketenagakerjaan" class="logo-img">
                </div>
                
                <!-- Title -->
                <h1 class="modal-title">Presensi KEMNAKER</h1>
                
                <!-- Login Form -->
                <form action="{{ url('/login') }}" method="POST" autocomplete="off" class="modal-form">
                    @csrf
                    
                    <!-- Login Input Fields -->
                    <div id="login-input" class="login-fields">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input 
                                class="form-control" 
                                id="email" 
                                type="email" 
                                name="email" 
                                placeholder="Masukkan Email Anda" 
                                tabindex="1"
                                autofocus 
                            />
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input 
                                class="form-control" 
                                id="password" 
                                type="password" 
                                name="password" 
                                placeholder="Masukkan Password" 
                                tabindex="2"
                            />
                        </div>
                        
                        <div class="form-group">
                            <label style="margin-bottom: 0;">
                                <input type="checkbox" name="remember" id="remember-me" tabindex="3" style="margin-right: 8px;">
                                <span style="font-weight: 400; font-size: 13px;">Ingat Saya</span>
                            </label>
                        </div>
                        
                        <button class="btn-modal btn-submit" tabindex="4" type="submit">
                            Log In
                        </button>
                    </div>
                    
                    <!-- Role Selection Buttons -->
                    <div class="modal-buttons">
                        <button class="btn-modal btn-admin" tabindex="4" type="button" id="btnLoginAdmin">
                            Log In Sebagai Admin
                        </button>
                        <button class="btn-modal btn-tata-usaha" tabindex="4" id="btnLoginTu" onclick="window.location.href='{{$sso_url}}'">
                            Log In Sebagai Tata Usaha
                        </button>
                    </div>
                </form>
                
                <!-- Footer Links -->
                <div class="modal-footer">
                    <a href="https://account.kemnaker.go.id/auth/forgot-password" target="_blank">Lupa Kata Sandi?</a>
                    <a href="{{ url('kebijakan-privasi') }}" target="_blank">Kebijakan dan Privasi</a>
                </div>
            </div>
        </div>
    </div>


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
            $('#login-input').hide();
            
            $('#btnLoginAdmin').on('click', function(e){
                e.preventDefault();
                $('#login-input').show();
                $('.modal-buttons').hide();
                $('#email').focus();
            });
            
            $('#btnLoginTu').on('click', function(e){
                e.preventDefault();
                // This button links directly to SSO
            });
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
        });
    </script>
</body>
<!-- END: Body-->

</html>
