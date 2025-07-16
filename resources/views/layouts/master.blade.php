<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CISS - Home</title>

    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/admission-style.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <style>
        .toast-top-right {
            margin-top: 50px;
        }
        .small-box .icon {
            color: #04401f;
            opacity: 0.29;
            z-index: 0;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed layout-footer-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-dark" style="background-color: #04401f; height: 60px">
            <div class="container-fluid">
                <div href="" class="" style="color: #fff;font-family: Courier;">
                    CISS V.1.0
                </div>

                <div class="" style="z-index: 999 !important">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:80px;" class="center-top">
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link d-none d-md-block" data-widget="control-sidebar" data-slide="true"
                            href="#" role="button" style="color: #fff">
                            @auth('web')
                                @if (in_array(Auth::guard('web')->user()->role, range(0, 20)))
                                    Logged as: {{ Auth::guard('web')->user()->fname }}
                                    {{ Auth::guard('web')->user()->lname }} -
                                    @if (Auth::guard('web')->user()->campus == 'MC')
                                        Main
                                    @elseif (Auth::guard('web')->user()->campus == 'VC')
                                        Victorias
                                    @elseif (Auth::guard('web')->user()->campus == 'SCC')
                                        San Carlos
                                    @elseif (Auth::guard('web')->user()->campus == 'HC')
                                        Hinigaran
                                    @elseif (Auth::guard('web')->user()->campus == 'MP')
                                        Moises Padilla
                                    @elseif (Auth::guard('web')->user()->campus == 'IC')
                                        Ilog
                                    @elseif (Auth::guard('web')->user()->campus == 'CA')
                                        Candoni
                                    @elseif (Auth::guard('web')->user()->campus == 'CC')
                                        Cauayan
                                    @elseif (Auth::guard('web')->user()->campus == 'SC')
                                        Sipalay
                                    @elseif (Auth::guard('web')->user()->campus == 'HinC')
                                        Hinobaan
                                    @endif
                                @endif
                            @endauth

                            @auth('faculty')
                                @if (Auth::guard('faculty')->user()->role == '943')
                                    Logged as: {{ Auth::guard('faculty')->user()->fname }}
                                    {{ Auth::guard('faculty')->user()->lname }}
                                @endif
                            @endauth
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white elevation-1" style="margin-top: 45px; z-index: 999;">
            <div class="container-fluid">

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse" style="padding-top: 20px;">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        @include('partials.control')
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid" style="padding-top: 20px; overflow-x: auto;">
                    <div class="col-lg-12">
                        {{-- <div class="card" style="min-width: 350px; width: max-content;">
                            @include('partials.control')
                        </div> --}}
                        {{-- <div class="card" style="min-width: 450px; width: 1330px;">
                            @include('partials.control')
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="" style="padding-top: 70px;">
                        <div class="row">
                            @section('sidemenu')
                            @show
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer text-sm text-center" style="background-color: #04401f;">
            <div class="float-right d-none d-sm-inline "></div>
            <i class="text-light">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO)
                under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <script src="{{ asset('js/basic/madapak.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/ajax/settngs/dark-mode.js') }}"></script>

    <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>
    
    <script>
        var previousSchlyearYear = {!! json_encode($previousSchlyearYear) !!};
        var semesteractive = {!! json_encode($semesteractive) !!};
        var collbar1Route = {!! json_encode($collegesCurrentSemester) !!}; 
        var groupedCollegeData = {!! json_encode($collegesCurrentSemester) !!};
        var schlyearActive = {!! json_encode($schlyearactiveYear) !!};
    </script>
    @include('script.masterBarScript')
    <script>
        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                'positionClass': 'toast-top-right'
            }
            toastr.error("{{ session('error') }}")
        @endif
    </script>

</body>

</html>
