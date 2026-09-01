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

    <link rel="stylesheet" href="{{ asset('uilibs/css/main.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('uilibs/css/custom.css') }}?v={{ time() }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
            const theme = savedTheme || (systemPrefersLight ? 'light' : 'dark');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <style>
        a.disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top px-3" style="background-color: #04401f !important; z-index: 9995">
        <div id="toggleBtn" class="text-light" style="padding-left: 25px">
            CISS v.1.0
        </div>
        <div>
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1 d-none d-md-flex">
                <li>
                    <button id="themeToggleBtn" class="btn btn-light btn-sm rounded-circle" title="Toggle theme">
                        <i id="themeIcon" class="ti ti-sun"></i>
                    </button>
                </li>
                <li class="ms-3 dropdown">
                    <a href="#" role="button" class="text-light" data-bs-toggle="dropdown" aria-expanded="false">
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
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1 d-md-none">
                <li class="ms-3 dropdown">
                    <a href="#" role="button" class="text-light">
                        @auth('web')
                            @if (in_array(Auth::guard('web')->user()->role, range(0, 21)))
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
                </li>
            </ul>
        </div>
    </nav>
    @php
        $user = Auth::user();
        $buttonAccess = $user->buttonAccess;
        $buttons = $buttonAccess ? $buttonAccess->buttons : [];
    @endphp
    <!-- DASHBOARD MENU -->
    @include('partials.control')

    <!-- MAIN CONTENT -->
    <main id="content" class="py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="" style="z-index: 9999">
                    <img src="{{ asset('uilibs/images/cpsulogov4.webp') }}" style="width:70px;" class="center-top">
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
            <a id="bottomhome-url">
                <div class="nav-item" data-label="Dashboard">
                    <i class="fas fa-th icon"></i>
                    <span>Dashboard</span>
                </div>
            </a>

            <a id="bottomenrollment-url" class="{{ in_array('enrollment-url', $buttons) ? '' : 'disabled' }}">
                <div class="nav-item" data-label="Enrollment">
                    <i class="fas fa-graduation-cap icon"></i>
                    <span>Enrollment</span>
                </div>
            </a>

            <a href="#">
                <div class="nav-item" id="servicesBtn" data-label="Others">
                    <i class="fas fa-server icon"></i>
                    <span>Others</span>
                </div>
            </a>

            <a id="bottomsetting-url" class="{{ in_array('setting-url', $buttons) ? '' : 'disabled' }}">
                <div class="nav-item">
                    <i class="fas fa-cog icon"></i>
                    <span>Settings</span>
                </div>
            </a>
            <a href="#" id="bottomlogout-url">
                <div class="nav-item" data-label="Sign Out">
                    <i class="fas fa-sign-out icon"></i>
                    <span>Sign Out</span>
                </div>
            </a>
        </div>
        <div class="services-menu" id="servicesMenu">
            <a id="bottomadmission-url" class="{{ in_array('admission-url', $buttons) ? '' : 'disabled' }}">
                <div class="service-item">
                    <i class="fas fa-id-card"></i>
                    <span>Admission</span>
                </div>
            </a>

            <a id="bottomscheduler-url" class="{{ in_array('scheduler-url', $buttons) ? '' : 'disabled' }}">
                <div class="service-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Schedule</span>
                </div>
            </a>

            <a id="bottomassessment-url" class="{{ in_array('assessment-url', $buttons) ? '' : 'disabled' }}">
                <div class="service-item">
                    <i class="fas fa-receipt"></i>
                    <span>Assess</span>
                </div>
            </a>

            <a id="bottomcashiering-url" class="{{ in_array('cashiering-url', $buttons) ? '' : 'disabled' }}">
                <div class="service-item">
                    <i class="fas fa-calculator"></i>
                    <span>Cashier</span>
                </div>
            </a>

            <a id="bottomkiosk-url" class="{{ in_array('kiosk-url', $buttons) ? '' : 'disabled' }}">
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
    <script src="{{ asset('js/basic/themejs.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}"></script>
    <!-- ChartJs -->
    <script src="{{ asset('uilibs/plugins/chart.js/Chart.min.js') }}"></script>

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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll('.card-animate');

            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show');
                }, index * 100); // stagger effect
            });
        });
    </script>

    <script>
        var homeRoute = "{{ route('home') }}";
        var admissionRoute = "{{ route('admission-index') }}";
        var enrollmentRoute = "{{ route('enrollment-index') }}";
        var schedulerRoute = "{{ route('scheduler-index') }}";
        var assessmentRoute = "{{ route('assessment-index') }}";
        var cashierRoute = "{{ route('cashiering-index') }}";
        var scholarshipRoute = "{{ route('scholarship-index') }}";
        var gradingRoute = "{{ route('grading-index') }}";
        var yearbookRoute = "{{ route('yearbook-index') }}";
        var kioskRoute = "{{ route('kioskReport') }}";
        var queueRoute = "{{ route('queue-index') }}";
        var nstpRoute = "{{ route('nstp-index') }}";
        var ossaRoute = "{{ route('ossa-index') }}";
        var requestRoute = "{{ route('request-index') }}";
        var settingRoute = "{{ route('settings-index') }}";
        var logoutRoute = "{{ route('logout') }}";
    </script>
</body>

</html>