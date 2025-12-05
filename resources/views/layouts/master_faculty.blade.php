<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('template/faculty/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/faculty/assets/modules/fontawesome-free-V6/css/all.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('template/faculty/assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/faculty/assets/css/components.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/faculty/assets/css/customstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/sched-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/custom.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

    <style>
        ::-webkit-scrollbar {
            width: 6px !important;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1 !important;
        }
        ::-webkit-scrollbar-thumb {
            background: #888 !important;
            border-radius: 5px !important;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555 !important;
        }
        .my-element {
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('template/faculty/assets/img/bg-gradient.jpg') }}");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center; 
        }
        .main-navbar {
            background: linear-gradient(135deg, #3a7d5c 0%, #1f5036 100%);
            color: #000;
        }

        @media (min-width: 992px) {
            .main-navbar {
                background: #ffffff !important;
            }
        }
        .modal-lg {
            max-width: 70% !important;
        }
        .styled-table thead tr {
            border-bottom: 2px solid #009879;
            border-top: 2px solid #009879;
            color: #000;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
            color: #000;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 1px solid #009879;
        }

        .toast-top-right {
            margin-top: 80px;
        }
        .card-widget {
            border: 0;
            position: relative;
        }
        .widget-user .widget-user-header {
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            height: 135px;
            padding: 1rem;
            text-align: center;
        }
        .form-control-sm {
            height: calc(1.8125rem + 2px) !important;
            padding: .25rem .5rem !important;
            font-size: .875rem !important;
            line-height: 1.5 !important;
            border-radius: .2rem !important;
        }
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
                color: inherit; /* keep text/icon color the same */
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
                color: #000000;
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
                color: #000000;
                font-weight: normal;
                background: rgba(170, 165, 165, 0.342);
                backdrop-filter: blur(10px);
                border-radius: 10px;
                padding: 5px;
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
    </style>
</head>

<body class="layout-4">
<!-- Page Loader -->
<!-- <div class="page-loader-wrapper">
    <span class="loader"><span class="loader-inner"></span></span>
</div> -->

    <div id="app" class="">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div> 
            <div style="height: 15px; background: #f0eff1; position: fixed; top: 0; left: 0; right: 0; z-index: 998;"></div>
            
            <!-- Start app top navbar -->
            <nav class="navbar navbar-expand-lg main-navbar" style="position: fixed; margin-top: 15px; border-radius: 20px; margin-left: 15px; margin-right: 20px; z-index: 999">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg text-dark togglebar"><i class="fas fa-bars"></i></a></li>
                    </ul>
                    <div class="text-white">CISS</div>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('template/img/cpsulogov4.png') }}" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">
                                Hi, 
                                @auth('faculty')
                                    @if(Auth::guard('faculty')->user()->role == '943')
                                        {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                                    @endif
                                @endauth 
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logoutfac') }}" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Start main left sidebar menu -->
            <div class="main-sidebar sidebar-style-2" style="border-radius: 20px;">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index-2.html">Faculty</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index-2.html">CP</a>
                    </div>
                    @include('partials.control_grade_sidebar')
                    {{-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                        <a href="#" class="btn btn-success btn-lg btn-block btn-icon-split"><i class="fas fa-rocket"></i> Documentation</a>
                    </div> --}}
                </aside>
            </div>

            <!-- Start app main Content -->
            <div class="main-content">
                @section('workspace')
                @show
                @include('partials.control_grade_bottombar')
            </div>

            <!-- Start app Footer part -->
            <footer class="main-footer">
                <div class="footer-left">
                    <div></div> CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved
                </div>
                <div class="footer-right">
                
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('template/faculty/assets/bundles/lib.vendor.bundle.js') }}"></script>
    <script src="{{ asset('template/faculty/js/CodiePie.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('template/faculty/js/scripts.js') }}"></script>
    @if(request()->routeIs('homefaculty'))
    <script src="{{ asset('template/faculty/js/custom.js') }}"></script>
    @endif
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script> 
    <script src="{{ asset('template/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('js/validation/grading/gradingAttendanceValidation.js') }}"></script>

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
        @if(Session::has('error'))
            toastr.options = {
                "closeButton":true,
                "progressBar":true,
                'positionClass': 'toast-top-right'
            }
            toastr.error("{{ session('error') }}")
        @endif

        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false, 
                "autoWidth": true,
                "searching": false,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>

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

    @if(request()->routeIs('schedulefac_searchview'))
        @include('grading.gradesheet.faculty.facultyschedscript')
    @endif
</body>
</html>