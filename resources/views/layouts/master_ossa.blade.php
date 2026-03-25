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
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fullcalendar/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/sched-style.css') }}">

    <style>
        /* Scale ID card to fit page exactly */
        .id-frontcard, .id-backcard {
            width: 85.6mm;
            height: 54mm;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            background: linear-gradient(135deg,#d8f3dc,#f1f5d6);
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif
        }

        /* Header */
        .id-header {
            background: #0f766e;
            color: white;
            padding: 2px 10px 10px 10px;
        }

        .id-header h6 {
            margin: 0;
            font-weight: 700;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
        }

        .id-header small {
            font-size: 8px;
            opacity: .9;
            margin-top: -10px;
            padding-left: 34px;
        }

        /* Body */
        .id-body {
            padding: 6px 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex: 1;
        }

        .student-photo {
            margin-top: -15px;
            width: 75px;
            height: 90px;
            border: 2px solid #2c7a7b;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-info {
            margin-top: -17px !important;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .student-info h5 {
            font-weight: 700;
            color: #0f766e;
            margin-bottom: 4px;
            font-size: 12px;
        }

        .info-row {
            display: flex;
            font-size: 9px;
            margin-bottom: 2px;
        }

        .info-label {
            width: 60px;
            font-weight: 600;
            color: #333;
        }

        .barcode {
            margin-top: 4px;
            height: 25px;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                transparent 2px,
                transparent 4px
            );
        }

        /* Footer */
        .id-footer {
            background: #0f766e;
            height: 18px;
        }

        img {
            max-width: 100%;
        }


        .id-body-back {
            padding: 16px 18px;
            font-family: Arial, sans-serif;
        }

        /* Top text */
        .emergency-text {
            font-size: 7px;
            font-style: italic;
            margin-bottom: 2px;
        }

        /* Grid layout */
        .back-grid {
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .back-col {
            flex: 1;
        }

        .back-col label {
            font-weight: 300;
            font-size: 5pt;
        }

        /* Lines */
        .line {
            border-bottom: 1px solid linear-gradient(135deg,#d8f3dc,#f1f5d6);
            height: 1px;
            margin-bottom: 10px;
        }

        /* Green divider */
        .green-line {
            height: 2px;
            background: #2f855a;
            margin: 1px 0;
        }

        /* Bottom note */
        .note-text {
            margin-top: 5px;
            text-align: center;
            line-height: 1.2;
            font-size: 5pt;
        }


        .btn-block {
            display: block;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top px-3" style="background-color: #04401f !important; z-index: 9999">
        {{-- <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm ">
            <i class="fas fa-bars"></i>
        </button> --}}

        <!-- MOBILE -->
        {{-- <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2 d-none d-md-block">
            <i class="ti ti-layout-sidebar-left-expand"></i>
        </button> --}}

        <div id="s" class="text-light">
            CISS v.1.0 
        </div>

        <div class="d-md-none">
            <div class="d-flex align-items-center gap-3">
                {{-- <div class="d-inline-flex">
                    <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="24">
                    <span class="logo-text ms-2" style="font-weight: bold">Faculty Portal</span>
                </div> --}}
            </div>
        </div>

        <div>
            <!-- Navbar nav -->
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
                <!-- MOBILE -->
                <button id="mobileBtn" class="btn btn-outline-light btn-icon btn-sm d-lg-none me-2">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Dropdown -->
                <li class="ms-3 dropdown d-none d-md-block">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="text-light">
                        <img src="{{ asset('uilibs/images/usergreen.png') }}" alt="" class="avatar avatar-sm rounded-circle" />
                        @auth('web')
                            @if(in_array(Auth::guard('web')->user()->role, range(0, 21)))
                                Logged as: {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }} - 
                                @if (Auth::guard('web')->user()->campus == 'MC') Main 
                                    @elseif (Auth::guard('web')->user()->campus == 'VC') Victorias 
                                    @elseif (Auth::guard('web')->user()->campus == 'SCC') San Carlos 
                                    @elseif (Auth::guard('web')->user()->campus == 'HC') Hinigaran 
                                    @elseif (Auth::guard('web')->user()->campus == 'MP') Moises Padilla 
                                    @elseif (Auth::guard('web')->user()->campus == 'IC') Ilog 
                                    @elseif (Auth::guard('web')->user()->campus == 'CA') Candoni 
                                    @elseif (Auth::guard('web')->user()->campus == 'CC') Cauayan 
                                    @elseif (Auth::guard('web')->user()->campus == 'SC') Sipalay  
                                    @elseif (Auth::guard('web')->user()->campus == 'HinC') Hinobaan 
                                @endif
                            @endif
                        @endauth
                    </a>
                </li>
                <li class="ms-3 dropdown d-md-none">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="text-light">
                        <img src="{{ asset('uilibs/images/usergreen.png') }}" alt="" class="avatar avatar-sm rounded-circle" />
                        @auth('web')
                            @if(in_array(Auth::guard('web')->user()->role, range(0, 21)))
                                @if (Auth::guard('web')->user()->campus == 'MC') Main 
                                    @elseif (Auth::guard('web')->user()->campus == 'VC') Victorias 
                                    @elseif (Auth::guard('web')->user()->campus == 'SCC') San Carlos 
                                    @elseif (Auth::guard('web')->user()->campus == 'HC') Hinigaran 
                                    @elseif (Auth::guard('web')->user()->campus == 'MP') Moises Padilla 
                                    @elseif (Auth::guard('web')->user()->campus == 'IC') Ilog 
                                    @elseif (Auth::guard('web')->user()->campus == 'CA') Candoni 
                                    @elseif (Auth::guard('web')->user()->campus == 'CC') Cauayan 
                                    @elseif (Auth::guard('web')->user()->campus == 'SC') Sipalay  
                                    @elseif (Auth::guard('web')->user()->campus == 'HinC') Hinobaan 
                                @endif
                            @endif
                        @endauth
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar overflow-y-auto overflow-x-hidden">
        <div class="logo-area border-0">
            <div class="d-inline-flex">
                <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="24">
                <span class="logo-text ms-2" style="font-weight: bold">CISS</span>
            </div>
        </div>
        @include('partials.control_ossa_sidebar')

    </aside>

    <!-- MAINmainCONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div style="z-index: 9999">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
                </div>
            </div>
            @section('workspace')
            @show

            <div class="row d-none d-md-block">
                <div class="col-12">
                    <footer class="text-center py-2 mt-6 text-secondary fixed-bottom bg-white" style="z-index: 99">
                        <p class="mb-0">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</p>
                    </footer>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->

    <script type="text/javascript" src="{{ asset('uilibs/js/main.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('uilibs/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('uilibs/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/fullcalendar/fullcalendar.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('uilibs/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('uilibs/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('uilibs/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Validation JS -->
    <script src="{{ asset('uilibs/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    @if(request()->routeIs('rfid.store'))
        @include('script.ossas.rfidstudjs')
    @endif
    @if(request()->routeIs('verifyStudentIDrfid'))
        @include('script.ossas.verifyrfidjs')
    @endif
</body>

</html>