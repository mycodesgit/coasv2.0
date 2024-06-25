<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
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
        .sticky-column {
          position: sticky;
          top: 50px;
          height: 5vh;
        }
        .scrolling-column {
          overflow-y: auto;
        }
        .my-custom-show-animation {
            animation: myShowAnimation 0.2s ease forwards;
        }
        @keyframes myShowAnimation {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .toast-top-right {
            margin-top: 50px;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <div href="" class="" style="color: #fff;font-family: Courier;">
                    CISS V.1.0
                </div>

                <div class="" style="z-index: 999">
                    <img src="{{ asset('template/img/CPSU_L.png') }}" style="width:80px;" class="center-top">
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" style="color: #fff">
                            @auth('web')
                                @if(in_array(Auth::guard('web')->user()->isAdmin, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]))
                                    Logged as: {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}
                                @endif
                            @endauth

                            @auth('faculty')
                                @if(Auth::guard('faculty')->user()->isAdmin == '943')
                                    Logged as: {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                                @endif
                            @endauth
                        </a>
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
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
                                    @section('sideheader')
                                    @show
                                </div>
                                @section('sidemenu')
                                    @include('partials.control_scholar_sidebar')
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
                                            <li class="breadcrumb-item active mt-1">Scholarship</li>
                                        </ol>
                                        <div class="workspace-top" style="text-align: center;">
                                            <div class="row">
                                                @if(request()->routeIs('scholarship-index'))
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #00bc8c !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $enrlstudcountfirst }}</h3>
                                                                <p>1st Stud Enrolled this Sem</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-users"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #89c9b6 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $enrlstudcountsecond }}</h3>
                                                                <p>2nd Stud Enrolled this Sem</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-users"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #9dcda8 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $enrlstudcountthird }}</h3>
                                                                <p>3rd Stud Enrolled this Sem</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-users"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #008b51 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $enrlstudcountfourth }}</h3>
                                                                <p>4th Stud Enrolled this Sem</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-users"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="chart-responsive pt-1">
                                                                <canvas id="firstSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="chart-responsive pt-1">
                                                                <canvas id="secondSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
            <i class="text-light">CPSU - COAS V.2.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
        </footer>
    </div>
    @include('portal.modal-terms')
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
    <!-- ChartJS -->
    <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}"></script>
    <script src="{{ asset('js/basic/yearscript.js') }}"></script>
    <script src="{{ asset('js/basic/schoolyear.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('js/validation/scholars/scholarValidation.js') }}"></script>
    <script src="{{ asset('js/validation/scholars/studscholarValidation.js') }}"></script>

    <!-- Basic -->
    @if(request()->routeIs('scholarship-index'))
        <script> 
            var collbar1Route = {!! json_encode($collegesFirstSemester) !!}; 
            var collbar2Route = {!! json_encode($collegesSecondSemester) !!}; 
            var collbarprevYearRoute = {!! json_encode($previousYear) !!}; 
            var collbarnowYearRoute = {!! json_encode($currentYear) !!}; 
        </script>
        <script src="{{ asset('js/chart/enbarchart.js') }}"></script>
    @endif
    @if(request()->routeIs('chedscholarlist'))
        <script src="{{ asset('js/ajax/scholars/chedScholarSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('unischolarlist'))
        <script src="{{ asset('js/ajax/scholars/uniScholarSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('allscholarlist'))
        <script src="{{ asset('js/ajax/scholars/allScholarSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studscholar_searchRead'))
        <script src="{{ asset('js/ajax/scholars/scholarSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('viewsearchStudHistory'))
        <script src="{{ asset('js/ajax/scholars/studHistorySerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('countstudnoenrollee'))
        <script src="{{ asset('js/ajax/enrolment/enrolleescountSerialize.js') }}"></script>
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
        @if(session('error'))
            Swal.fire({
                icon: 'warning',
                // title: 'Waring',
                html: '{!! session('error') !!}',
                showClass: {
                    popup: 'my-custom-show-animation'
                },
                hideClass: {
                    popup: ''
                }
            });
        @endif
    </script>

</body>
</html>
   