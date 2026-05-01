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
        .nav-link {
            font-size: 14px;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        .collapse .nav-link {
            color: #555;
        }
        .sidebar .nav-link.active {
            color: #000000 !important;
            background-color: #65ac86 !important;
        }
        .sidebar.collapsed .nav-link.active,
        .sidebar.collapsed .nav-link:hover {
            background-color: transparent !important;
            color: inherit !important;
        }
        /* main {
            background-color: #f4f6f9;
        } */
        .fc-event {
            border-color: #198754; background-color: #198754;
        }
        @media (max-width: 768px) {
            .fc .fc-daygrid-day-frame {
                min-height: 45px;
            }
        }
        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-hover:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .sidebar .nav-link .fa {
            font-size: 18px !important;
        }
        .fa {
            font-family: tabler-icons !important;
            speak: none;
            font-style: normal;
            font-weight: 400;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .bottom-nav {
            display: none;
        }
        @media (max-width: 991px) {
            .bottom-nav {
                position: fixed;
                bottom: 7px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(12, 135, 84, 0.9);
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
                /* color: #377858; */
                color: #ffffff;
                cursor: pointer;
                transition: 0.2s;
                padding: 5px;
            }
            .nav-item .icon {
                font-size: 18px;
                margin-bottom: 1px;
                margin-top: 3px;
            }
            .nav-item.active {
                /* color: #377858; */
                color: #ffffff;
                font-weight: normal;
                background: rgba(37, 37, 37, 0.2);
                backdrop-filter: blur(10px);
                border-radius: 10px;
                padding: 5px;
                /* width: 60px;
                height: 47px; */
            }
        }
        @media (max-width: 991px) {
            .main-sidebar.sidebar-style-2 {
                display: none !important;
            }
            .togglebar{
                display: none !important;
            }
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }
        .radio-group input[type="radio"] {
            width: 22px;
            height: 22px;
            accent-color: black;
            cursor: pointer;
            vertical-align: middle; 
        }
        .radio-group a {
            display: flex;
            align-items: center;
            gap: 2px; 
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            color: black;
        }
        .radio-group a span {
            display: inline-block;
            margin-left: 5px;
        }
        .radio-group input[type="radio"] {
            display: none;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px; 
            height: 35px;
            border-radius: 50%;
            border: 2px solid #999; 
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            position: relative;
        }
        @media (max-width: 768px) {
            .radio-group label {
                width: 28px;
                height: 28px;
            }
            #topbar {
                border-bottom: 0 !important;
            }
        }
        .radio-group input[type="radio"]:checked + label {
            background-color: #28a745; 
            color: white; 
            border-color: #28a745;
        }
        .textbold{
            font-weight: bold;
        }
        .card-animate {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
            transition: all 0.3s ease;
        }
        .card-animate.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar px-3">
        <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm ">
            <i class="fas fa-bars"></i>
        </button>

        <!-- MOBILE -->
        <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2 d-none d-md-block">
            <i class="ti ti-layout-sidebar-left-expand"></i>
        </button>

        <div class="d-md-none">
            <div class="d-flex align-items-center gap-3">
            <div class="d-inline-flex">
                <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="24">
                <span class="logo-text ms-2" style="font-weight: bold">Faculty Portal</span>
            </div>
            </div>
        </div>

        <div>
            <!-- Navbar nav -->
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
                <!-- Dropdown -->
                <li class="ms-3 dropdown">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('uilibs/images/user.png') }}" alt="" class="avatar avatar-sm rounded-circle" /> 
                        @auth('faculty')
                            @if(Auth::guard('faculty')->user()->role == '943')
                                {{ substr(Auth::guard('faculty')->user()->fname, 0, 1) }}. {{ Auth::guard('faculty')->user()->lname }}
                            @endif
                        @endauth 
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 200px;">
                        <div>
                            <div class="d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3">
                                <img src="{{ asset('uilibs/images/user.png') }}" alt="" class="avatar avatar-md rounded-circle" />
                                <div>
                                    <h5 class="mb-0 small">
                                        @auth('faculty')
                                            @if(Auth::guard('faculty')->user()->role == '943')
                                                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                                            @endif
                                        @endauth 
                                    </h5>
                                    <p class="mb-0 small text-success"></p>
                                </div>
                            </div>
                            <div class="p-3 d-flex flex-column gap-1 medium lh-lg">
                                <a href="#!" class="text-secondary">
                                    <i class="ti ti-id"></i> <span>ID No. </span>
                                </a>
                                <div style="border-bottom: 1px solid #dddddd;" class="mb-2"></div>
                                <a href="{{ route('logoutfac') }}" class="text-danger">
                                    <i class="ti ti-logout"></i><span> Signout</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </nav>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div class="logo-area">
            <div class="d-inline-flex">
                <img src="{{ asset('uilibs/images/cpsulogov4.webp') }}" alt="logo" width="24">
                <span class="logo-text ms-2" style="font-weight: bold">Faculty Portal</span>
            </div>
        </div>
        @include('partials.control_faculty_sidebar')

    </aside>

    <!-- MAINmainCONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
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
        @include('partials.control_mobilefac_bottomenu')
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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    @if(request()->routeIs('homefaculty'))
        <script>
            var semesteractive = {!! json_encode($semesteractive) !!};
            var schlyearActive = {!! json_encode($schlyearactiveYear) !!};
            var progyerlevCounts = {!! json_encode($countstudsubfac) !!};
            var undercolors = {!! json_encode($underproglevsecname) !!};
        </script>
        <script src="{{ asset('js/chart/enbarchartperyearlevfaculty.js') }}?v={{ time() }}"></script>
        @include('script.faculty.dashSerialize')
    @endif
    
    <script>
        $(function () {
            $('.select2').select2();

            $('.select2bs4').select2({
                theme: 'bootstrap4',
                //height: '150'
            })
        });

        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll('.card-animate');

            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show');
                }, index * 90); // stagger effect
            });
        });

        $(document).ready(function() {
            @if(session('error'))
                toastr.error("{{ session('error') }}", "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-bottom-left",
                    timeOut: 5000
                });
            @endif

            @if(session('success'))
                toastr.success("{{ session('success') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-bottom-left",
                    timeOut: 10000
                });
            @endif
        });
    </script>

    @if(request()->routeIs('virtual_facultysubjectclass'))
        <script type="text/javascript">
            function updateGrade(id, grade){
                $.ajax({
                    url: '{{ route('save_grades') }}',
                    method: 'POST',
                    data: { id: id, grade: grade, _token: '{{ csrf_token() }}' },
                    success: function (data) {
                        console.log(data.gradeCount);
                        if(data.gradeCount > 0){
                            $('#submitgradeid').prop('disabled', false);
                        }else{
                            $('#submitgradeid').prop('disabled', true);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        </script>

        <script type="text/javascript">
            function updateGradeComp(id, grade){
                $.ajax({
                    url: '{{ route('save_gradesComp') }}',
                    method: 'POST',
                    data: { id: id, grade: grade, _token: '{{ csrf_token() }}' },
                    success: function (data) {
                        console.log(data.gradeCount);
                        if(data.gradeCount > 0){
                            $('#submitgradeid').prop('disabled', false);
                        }else{
                            $('#submitgradeid').prop('disabled', true);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        </script>

        <script>
            $(document).ready(function () {
                $('#submitBtn').click(function () {
                    $('#confirmationForm').submit();
                });
            });
        </script>
    @endif

    @if(request()->routeIs('schedulefac_searchview'))
        @include('grading.gradesheet.faculty.facultyschedscript')
    @endif

    @if (request()->routeIs('supfacevalrate'))
        @include('script.studnts.evaluation.ratecardnextbutton')
    @endif
    
    @if (request()->routeIs('confirm.store'))
        <script src="{{ asset('js/ajax/admssion/examresult/exconfirmSerialize.js') }}"></script>
    @endif

    @if(request()->routeIs('accepted.store'))
        <script src="{{ asset('js/ajax/admssion/examresult/acceptedSerialize.js') }}"></script>
    @endif
</body>

</html>