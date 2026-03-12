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
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">

    <style>
        .bottom-nav {
            display: none;
        }

        @media (max-width: 991px) {
            .bottom-nav {
                position: fixed;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(37, 37, 37, 0);
                backdrop-filter: blur(3px);
                width: 90%;
                max-width: 400px;
                padding: 10px 0;
                border-radius: 20px;
                display: flex;
                justify-content: space-around;
                box-shadow: 0 6px 16px rgba(128, 128, 128, 0.404);
                z-index: 999;
            }

            .bottom-nav a {
                text-decoration: none !important;
                color: inherit;
                /* keep text/icon color the same */
            }

            .bottom-nav a:visited,
            .bottom-nav a:active,
            .bottom-nav a:focus {
                text-decoration: none !important;
            }

            .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                font-size: 7pt;
                color: #377858;
                cursor: pointer;
                transition: 0.2s;
                padding: 5px;
            }

            .nav-item .icon {
                font-size: 18px;
                margin-bottom: 1px;
                margin-top: 3px;
            }
        }

        @media (max-width: 991px) {
            .main-sidebar.sidebar-style-2 {
                display: none !important;
            }

            .togglebar {
                display: none !important;
            }
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .btn-app {
            border-radius: 3px !important;
            background-color: #f8f9fa !important;
            border: 1px solid #ddd !important;
            color: #1a5f3d !important;
            font-size: 12px !important;
            height: 60px !important;
            margin: 0 0 10px 10px !important;
            min-width: 80px !important;
            padding: 15px 5px !important;
            position: relative !important;
            text-align: center !important;
            font-family: "Poppins", sans-serif !important;
        }
        .btn-app:hover{
            background-color: #1f794c !important;
            border: 1px solid #ddd !important;
            color: #ffffff !important;
        }
        .btn-app>.fa, .btn-app>.fab, .btn-app>.fad, .btn-app>.fal, .btn-app>.far, .btn-app>.fas, .btn-app>.ion, .btn-app>.svg-inline--fa {
            display: block;
            font-size: 20px;
        }
        .center-top {
            position: fixed !important;
            top: 10px !important;
            left: 50% !important;
            transform: translateX(-50%);
        }

        .services-menu{
            position: fixed;
            bottom: 90px;
            left: 50%;
            transform: translateX(-50%) scale(.8);
            
            background: rgba(37, 37, 37, 0);
            backdrop-filter: blur(3px);
            color: #377858;

            display:flex;
            gap:25px;
            padding:12px 25px;
            border-radius:20px;

            box-shadow:0 10px 25px rgba(0,0,0,0.3);

            opacity:0;
            pointer-events:none;
            transition:0.25s;

            z-index:998;
        }

        .services-menu.active{
            opacity:1;
            transform:translateX(-50%) scale(1);
            pointer-events:auto;
        }

        .service-item{
            display:flex;
            flex-direction:column;
            align-items:center;
            font-size:11px;
            cursor:pointer;
        }

        .service-item i{
            font-size:18px;
            margin-bottom:4px;
        }
        ::-webkit-scrollbar {
            width: 3px !important;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1 !important;
        }
        ::-webkit-scrollbar-thumb {
            background: #888 !important;
            border-radius: 3px !important;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555 !important;
        }

        .menu-container{
            margin-top:56px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .menu-grid{
            display:flex;
            flex-wrap:wrap;
            gap:15px;
            justify-content:center;
        }

        .menu-item{
            width:88px;
            height:58px;
            background:#f5f6f7;
            border:1px solid #ddd;
            border-radius:8px;
            text-decoration:none;
            color:#2c3e50;

            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;

            transition:all 0.2s ease;
        }

        .menu-item i{
            font-size:20px;
            color:#04401f;
            margin-top: 5px;
        }

        .menu-item span{
            font-size:12px;
            margin-top:3px;
        }

        .menu-item:hover{
            background:#eaf4ee;
            border-color:#04401f;
            transform:translateY(-2px);
        }
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top px-3" style="background-color: #04401f !important;">
        <div id="toggleBtn" class="text-light">
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
                                    <h4 class="mb-0 small">Admin Level</h4>
                                    <p class="mb-0 small">@cpsu.edu.ph</p>
                                </div>
                            </div>
                            <div class="p-3 d-flex flex-column gap-1 small lh-lg">
                                <a href="#!" class="">
                                    <span> Activity</span>
                                </a>
                                <a href="#!" class="">
                                    <span> Sign Out</span>
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
    <main id="content" class="py-9">
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
            <a href="">
                <div class="nav-item" data-label="Dashboard">
                    <i class="fas fa-th icon"></i>
                    <span>Dashboard</span>
                </div>
            </a>

            <a href="">
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

            <a href="#">
                <div class="nav-item" data-label="Grade Sheet">
                    <i class="fas fa-cog icon"></i>
                    <span>Settings</span>
                </div>
            </a>
            <a href="#">
                <div class="nav-item" data-label="Grade Sheet">
                    <i class="fas fa-sign-out icon"></i>
                    <span>Signout</span>
                </div>
            </a>
        </div>
        <div class="services-menu" id="servicesMenu">
            <div class="service-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Schedule</span>
            </div>

            <div class="service-item">
                <i class="fas fa-receipt"></i>
                <span>Assess</span>
            </div>

            <div class="service-item">
                <i class="fas fa-calculator"></i>
                <span>Cashier</span>
            </div>

            <div class="service-item">
                <i class="fas fa-laptop"></i>
                <span>Kiosk</span>
            </div>

            <div class="service-item">
                <i class="fas fa-book-open"></i>
                <span>Queue</span>
            </div>
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