<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/admission-style.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        .toast-top-right {
            margin-top: 50px;
        }
        #sidebar {
            transition: transform 0.3s ease;
        }
        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                top: 88px;
                left: 0;
                height: 95%;
                width: 250px;
                max-width: 80vw;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                z-index: 999;
                transform: translateX(-100%);
                display: block !important;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
            #sidebar.active {
                transform: translateX(0);
            }
            #sidebarOverlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.3);
                z-index: 1039;
            }
            #sidebarOverlay.active {
                display: block;
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-dark" style="background-color: #04401f">
            <div class="container-fluid">
                <div href="" class="" style="color: #fff;font-family: Courier;">
                    CISS V.1.0
                </div>

                <div class="" style="z-index: 999">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:80px;" class="center-top">
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link d-none d-md-block" data-widget="control-sidebar" data-slide="true" href="#" role="button" style="color: #fff">
                            @auth('web')
                                @if(in_array(Auth::guard('web')->user()->role, range(0, 19)))
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

                            @auth('faculty')
                                @if(Auth::guard('faculty')->user()->role == '943')
                                    Logged as: {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                                @endif
                            @endauth
                        </a>
                        <button id="sidebarToggle" class="btn btn-primary d-lg-none mb-2" style="position: fixed; top: 7px; right: 60px; z-index: 1050;">
                            <i class="fas fa-bars"></i>
                        </button>
                    </li>
                </ul>
                
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid" style="padding-top: 20px"></div>
            </div>
            <div class="content">
                <div class="container-fluid1">
                    <div class="row" style="padding-top: 0px;">
                        <div id="sidebar" class="col-lg-2 sidebar-custom d-none d-lg-block">
                            <div class="card">
                                <div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
                                    @section('sideheader')
                                    @show
                                </div>
                                @section('sidemenu')
                                    @include('partials.control_nstp_sidebar')
                                @show
                            </div>
                        </div>
                        <div class="col-lg-10">
                            @section('workspace')
                                <div class="card">
                                    <div class="card-body">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item active mt-1">Nstp</li>
                                        </ol>
                                        <div class="workspace-top" style="text-align: center;">
                                            @if(request()->routeIs('nstp-index'))
                                            <div class="row">
                                                <div class="col-lg-4 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #00bc8c !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $cwtscount }}</h3>
                                                                <p>CWTS</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-building"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #1b9173 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $ltscount }}</h3>
                                                                <p>LTS</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-graduation-cap"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #467e70 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $rotccount }}</h3>
                                                                <p>ROTC</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-graduation-cap"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                                        </div>
                                    </div>
                                </div>
                            @show
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer text-sm text-center" style="background-color: #04401f;">
            <div class="float-right d-none d-sm-inline "></div>
            <i class="text-light">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

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
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/ajax/settngs/dark-mode.js') }}"></script>

    <script src="{{ asset('js/validation/settings/setconfValidation.js') }}"></script>

    @if(request()->routeIs('cwts_nstpresult'))
        <script src="{{ asset('js/ajax/nstp/cwtsSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('lts_nstpresult'))
        <script src="{{ asset('js/ajax/nstp/ltsSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('rotc_nstpresult'))
        <script src="{{ asset('js/ajax/nstp/rotcSerialize.js') }}"></script>
    @endif

    <script>
        $(document).ready(function () {
            $('#searchDropdown').select2({
                placeholder: '-- Search and Select --',
                allowClear: true,
                minimumInputLength: 6
            });
        });
    </script>

    <script type="text/javascript">
        setTimeout(function () {
            $("#alert").delay(2500).fadeOut(5000);
        }, 0); 
    </script>
    
    <script>
        $(function() {
            function closeSidebar() {
                $('#sidebar').removeClass('active');
                $('#sidebarOverlay').removeClass('active');
            }
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
            });
            $('#sidebarOverlay').on('click', closeSidebar);
            // Optional: Hide sidebar on resize to lg and up
            $(window).on('resize', function() {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>
   