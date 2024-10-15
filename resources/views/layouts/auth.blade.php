<!DOCTYPE html>
<html lang="en" class="h-100">


<!-- Mirrored from yashadmin.dexignzone.com/xhtml/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 Apr 2024 09:33:06 GMT -->
<head>
   <!--Title-->
	<title>@yield('title') | {{ env('APP_NAME', 'Systemdigits') }}</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/logo.png') }}">
	<link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
	<link class="main-css" href="{{ asset('css/style.css') }}" rel="stylesheet">
	<link class="main-css" href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>

<body style="background-image:url('{{ asset('images/bg.png') }}'); background-position:center;">
    <div class="authincation fix-wrapper">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--**********************************
	Scripts
***********************************-->
<!-- Required vendors -->
  <script src="{{ asset('vendor/global/global.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
  <script src="{{ asset('js/deznav-init.js') }}"></script>
  <script src="{{ asset('js/custom.min.js') }}"></script>
  <script src="{{ asset('js/demo.js') }}"></script>
  <script src="{{ asset('js/styleSwitcher.js') }}"></script>
</body>
</html>