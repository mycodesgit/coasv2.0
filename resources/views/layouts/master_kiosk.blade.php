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
    <link rel="stylesheet" href="{{ asset('assets/css/stylesonlinegrading.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <style>
        .toast-top-right {
            margin-top: 50px;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }

        .bg-secondary {
            background-color: #e9ecef !important;
            color: #252525 !important;
        }
        .navbar-nav .nav-item .nav-link.active {
            background-color: #ffcc00 !important; /* Highlight color */
            color: black !important; /* Change text color */
            font-weight: bold;
            border-radius: 5px;
        }

    </style>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <header class="header" id="header">
        <div class="header__container" style="background: linear-gradient(90deg, #1f5036 0%, #2e6b4a 30%, #3a7d5c 100%); transition: background 0.5s ease;">
            <a href="#" class="header__logo">
                <i class="fas fa-diagram-predecessor" style="color: #e9ecef"></i>
                <span style="color: #e9ecef">CISS V.1.0</span>
            </a>

            <button class="btn btn-default btn-sm" id="header-toggle" style="background-color: rgb(218, 218, 218);">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="d-block d-md-none" style="z-index: -999">
            <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
        </div>
    </header>

    <nav class="sidebar" id="sidebar" style="background: linear-gradient(135deg, #1f5036 0%, #3a7d5c 100%);">
        <div class="sidebar__container">
            <div class="sidebar__user">
                <div class="sidebar__img">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" alt="image" />
                </div>

                <div class="sidebar__info">
                    <h3 style="margin-top: 10px; margin-left: -10px">
						<span style="font-weight: bold;">{{ $studauth->lname }}, {{ $studauth->fname }} {{ substr($studauth->mname,0,1) }}.</span><br>
						<span style="font-size: 10pt; font-weight: bold;">ID No. <span style="font-size: 10pt; font-weight: bold; font-style: italic">2021-1016-K</span></span>
					</h3>
                </div>
            </div>

            <div class="sidebar__content">
                <div>
                    <div class="sidebar__list">
                        <a href="{{ route('kioskhome') }}" class="sidebar__link {{ request()->routeIs('kioskhome') ? 'active-link' : '' }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span>View Grades</span>
                        </a>

                        <a href="{{ route('kioskaccount') }}" class="sidebar__link {{ request()->routeIs('kioskaccount') ? 'active-link' : '' }}">
                            <i class="fas fa-file-invoice"></i>
                            <span>View Accounts</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sidebar__actions">
                <button style="all: unset; cursor: pointer;">
                    <i class="fas fa-moon sidebar__link sidebar__theme" id="theme-button">
                        <span>Dark Mode</span>
                    </i>
                </button>

                <a href="{{ route('logout') }}" style="all: unset; cursor: pointer;">
                    <i class="fas fa-power-off sidebar__link sidebar__logout" id="theme-logout">
                        <span>Logout</span>
                    </i>
                </a>
            </div>
        </div>
    </nav>

    <main class="main" id="main">
        <div class="carddashsection">
            @yield('body')
        </div>
    </main>


    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <script src="{{ asset('js/basic/madapak.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script>
        @if(Session::has('error'))
            toastr.options = {
                "closeButton":true,
                "progressBar":true,
                'positionClass': 'toast-top-right'
            }
            toastr.error("{{ session('error') }}")
        @endif
    </script>

</body>
</html>
   