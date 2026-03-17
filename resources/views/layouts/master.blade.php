<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uilibs/images/cpsulogov4.png') }}">

    <link rel="stylesheet" href="{{ asset('uilibs/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/css/custom.css') }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">

    <style>
        
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top px-3" style="background-color: #04401f !important;">
        <div id="toggleBtn" class="text-light" style="padding-left: 25px">
            CISS v.1.0
        </div>
        <div>
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
                <li class="ms-3 dropdown">
                    <a href="#" role="button" class="text-light" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('uilibs/images/usergreen.png') }}" alt="" class="avatar avatar-sm rounded-circle" />
                        @auth('web')
                            @if (in_array(Auth::guard('web')->user()->role, range(0, 21)))
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
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 200px;">
                        <div>
                            <div class="d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3">
                                <img src="{{ asset('uilibs/images/user.png') }}" alt=""
                                    class="avatar avatar-md rounded-circle" />
                                <div>
                                    <h4 class="mb-0 small">{{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}</h4>
                                    <p class="mb-0 small text-success">
                                        @if (Auth::guard('web')->user()->role == 0) Administrator 
                                            @elseif(Auth::guard('web')->user()->role == 1) Guidance Officer 
                                            @elseif(Auth::guard('web')->user()->role == 2) Guidance Staff 
                                            @elseif(Auth::guard('web')->user()->role == 3) Registrar 
                                            @elseif(Auth::guard('web')->user()->role == 4) Registrar Staff 
                                            @elseif(Auth::guard('web')->user()->role == 5) College Dean 
                                            @elseif(Auth::guard('web')->user()->role == 6) Program Head 
                                            @elseif(Auth::guard('web')->user()->role == 7) College Staff 
                                            @elseif(Auth::guard('web')->user()->role == 8) Scholarship Head
                                            @elseif(Auth::guard('web')->user()->role == 9) Scholarship Staff
                                            @elseif(Auth::guard('web')->user()->role == 10) Assessment Head
                                            @elseif(Auth::guard('web')->user()->role == 11) Assessment Staff
                                            @elseif(Auth::guard('web')->user()->role == 12) MIS Staff
                                            @elseif(Auth::guard('web')->user()->role == 13) MIS Director
                                            @elseif(Auth::guard('web')->user()->role == 14) MIS Officer
                                            @elseif(Auth::guard('web')->user()->role == 15) Graduate School Staff
                                            @elseif(Auth::guard('web')->user()->role == 16) OSSA Staff
                                            @elseif(Auth::guard('web')->user()->role == 17) Cashier
                                            @elseif(Auth::guard('web')->user()->role == 18) Cashier Staff
                                            @elseif(Auth::guard('web')->user()->role == 19) Encoder
                                            @elseif(Auth::guard('web')->user()->role == 20) Dean of Instruction
                                            @elseif(Auth::guard('web')->user()->role == 21) YearBook Staff
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="p-3 d-flex flex-column gap-1 small lh-lg">
                                <a href="#!" class="">
                                    <i class="ti ti-activity"></i> <span> Activity</span>
                                </a>
                                <a href="#" id="logout-url" class="text-danger">
                                    <i class="ti ti-logout"></i> <span> Sign Out</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- DASHBOARD MENU -->
    @include('partials.control')

    <!-- MAIN CONTENT -->
    <main id="content" class="py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="" style="z-index: 9999">
                    <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" style="width:70px;" class="center-top">
                </div>
            </div>

            @section('sidemenu')
            @show

            <div class="row d-none d-md-block">
                <div class="col-12">
                    <footer class="text-center py-2 mt-6 text-secondary fixed-bottom">
                        <p class="mb-0">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</p>
                    </footer>
                </div>

            </div>

        </div>
        <div class="bottom-nav">
            <a href="{{ route('home') }}">
                <div class="nav-item" data-label="Dashboard">
                    <i class="fas fa-th icon"></i>
                    <span>Dashboard</span>
                </div>
            </a>

            <a href="{{ route('enrollment-index') }}">
                <div class="nav-item" data-label="Attendance">
                    <i class="fas fa-graduation-cap icon"></i>
                    <span>Enrollment</span>
                </div>
            </a>

            <a href="#">
                <div class="nav-item" id="servicesBtn" data-label="Schedule">
                    <i class="fas fa-server icon"></i>
                    <span>Others</span>
                </div>
            </a>

            <a href="{{ route('settings-index') }}">
                <div class="nav-item" data-label="Grade Sheet">
                    <i class="fas fa-cog icon"></i>
                    <span>Settings</span>
                </div>
            </a>
            <a href="{{ route('logout') }}">
                <div class="nav-item" data-label="Grade Sheet">
                    <i class="fas fa-sign-out icon"></i>
                    <span>Signout</span>
                </div>
            </a>
        </div>
        <div class="services-menu" id="servicesMenu">
            <a href="{{ route('admission-index') }}">
                <div class="service-item">
                    <i class="fas fa-id-card"></i>
                    <span>Admission</span>
                </div>
            </a>

            <a href="{{ route('scheduler-index') }}">
                <div class="service-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Schedule</span>
                </div>
            </a>

            <a href="{{ route('assessment-index') }}">
                <div class="service-item">
                    <i class="fas fa-receipt"></i>
                    <span>Assess</span>
                </div>
            </a>

            <a href="{{ route('cashiering-index') }}">
                <div class="service-item">
                    <i class="fas fa-calculator"></i>
                    <span>Cashier</span>
                </div>
            </a>

            <a href="{{ route('adminkioskRead') }}">
                <div class="service-item">
                    <i class="fas fa-laptop"></i>
                    <span>Kiosk</span>
                </div>
            </a>
        </div>
    </main>

    <!-- Bootstrap JS -->

    <!-- jQuery -->
    <script type="text/javascript" src="{{ asset('uilibs/js/main.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <script src="{{ asset('js/basic/madapak.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- ChartJs -->
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
    
    <script>
        const btn = document.getElementById("servicesBtn");
        const menu = document.getElementById("servicesMenu");

        btn.addEventListener("click", () => {
            menu.classList.toggle("active");
        });
    </script>

</body>

</html>