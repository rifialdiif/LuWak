<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from shreyu.coderthemes.com/pages-404.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 11 Oct 2023 02:22:43 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Page Not Found | 404 Error Page | Shreyu - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{ asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" disabled />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"
        disabled />
    <!-- icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body class="authentication-bg">

    <div class="account-pages my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-8">
                    <div class="text-center">

                        <div>
                            <img src="{{ asset('assets/images/404_crop.png') }}" alt="" class="img-fluid" />
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <h3 class="mt-3">We couldn’t connect the dots</h3>
                    <p class="text-muted mb-4">This page was not found. <br /> You may have mistyped the address or the
                        page may have moved.</p>

                    <a href="{{ route('dashboard') }}" class="btn btn-lg btn-primary">Take me back to Home</a>
                </div>
            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end account-pages -->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

<!-- Mirrored from shreyu.coderthemes.com/pages-404.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 11 Oct 2023 02:22:43 GMT -->

</html>
