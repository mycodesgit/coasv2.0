<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
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
    <link rel="stylesheet" href="{{ asset('template/dist/css/upload-image.css') }}">
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
    </style>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <div href="" class="" style="color: #fff;font-family: Courier;">
                    CPSU COAS V.2.0
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
                                    @include('partials.control_ad_sidebar')
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
                                            <li class="breadcrumb-item active mt-1">Admission</li>
                                        </ol>
                                        <div class="workspace-top" style="text-align: center;">
                                            @php $curr_route = request()->route()->getName(); @endphp
                                            @if($curr_route == 'admission-index')
                                                <div class="">
                                                    <div class="card-header">
                                                        <h3 class="card-title">No. of Applicants in every campus in this year {{ $currentYear }}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="chart">
                                                            <div class="position-relative mb-4">
                                                                <canvas id="sales-chart"
                                                                        data-main="{!! $MainRegCount !!}"
                                                                        data-main-sched="{!! $MainSchedCount !!}"
                                                                        data-ilog="{!! $IlogRegCount !!}"
                                                                        data-ilog-sched="{!! $IlogSchedCount !!}" 
                                                                        data-cauayan="{!! $CauayanRegCount !!}"
                                                                        data-cauayan-sched="{!! $CauayanSchedCount !!}" 
                                                                        data-siplay="{!! $SipalayRegCount !!}"
                                                                        data-siplay-sched="{!! $SipalaySchedCount !!}" 
                                                                        data-hinobaan="{!! $HinobaanRegCount !!}"
                                                                        data-hinobaan-sched="{!! $HinobaanSchedCount !!}" 
                                                                        data-hinigaran="{!! $HinigaranRegCount !!}"
                                                                        data-hinigaran-sched="{!! $HinigaranSchedCount !!}" 
                                                                        data-moises="{!! $MoisesRegCount !!}"
                                                                        data-moises-sched="{!! $MoisesSchedCount !!}" 
                                                                        data-sancarlos="{!! $SancarlosRegCount !!}"
                                                                        data-sancarlos-sched="{!! $SancarlosSchedCount !!}" 
                                                                        data-victorias="{!! $VictoriasRegCount !!}"
                                                                        data-victorias-sched="{!! $VictoriasSchedCount !!}" 
                                                                        height="350">
                                                                </canvas>
                                                            </div>
                                                            <div class="d-flex flex-row justify-content-end">
                                                                <span class="mr-5">
                                                                    <i class="fas fa-square" style="color: #90ee90"></i> Registered Applicant: No schedule yet
                                                                </span>

                                                                <span class="mr-2">
                                                                    <i class="fas fa-square" style="color: #00a65a"></i> Registered Applicant: Scheduled
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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

    @include('modal.examinee-modal')
    @include('modal.examineeConfirm-modal')
    @include('modal.applicantAccept-modal')
    


    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- App -->
    <script src="{{ asset('app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>

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
    <script src="{{ asset('js/basic/yearscript.js') }}"></script>
    <script src="{{ asset('js/basic/dateselected.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <!-- Ajax -->
    @if(request()->routeIs('srchappList'))
        <script src="{{ asset('js/ajax/applicant/applicantSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('applicant_edit'))
        <script src="{{ asset('js/ajax/applicant/appAssignSchedSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('srchexamineeList'))
        <script src="{{ asset('js/ajax/examineeajax/examineeSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('examinee_edit'))
        <script src="{{ asset('js/ajax/examineeajax/examAssignRateSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('srchexamineeResultList'))
        <script src="{{ asset('js/ajax/examresult/exresultSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('srchconfirmList'))
        <script src="{{ asset('js/ajax/examresult/exconfirmSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('srchacceptedList'))
        <script src="{{ asset('js/ajax/examresult/acceptedSerialize.js') }}"></script>
    @endif

    @php $curr_route = request()->route()->getName(); @endphp
        @if($curr_route == 'admission-index')
        <!-- ChartJS -->
        <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('js/chart/dashall.js') }}"></script>
    @endif
    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('js/validation/apply/applyValidation.js') }}"></script>
    <script src="{{ asset('js/validation/applicant/applicantValidation.js') }}"></script>
    <script src="{{ asset('js/validation/configureAd/progCon.js') }}"></script>
    <script src="{{ asset('js/validation/configureAd/strandCon.js') }}"></script>
    <script src="{{ asset('js/validation/configureAd/dateCon.js') }}"></script>
    <script src="{{ asset('js/validation/configureAd/timeCon.js') }}"></script>
    <script src="{{ asset('js/validation/configureAd/venueCon.js') }}"></script>
    <script src="{{ asset('js/validation/report/applicantValidation.js') }}"></script>
    <script src="{{ asset('js/validation/report/schedValidation.js') }}"></script>

    <script type="text/javascript">
        setTimeout(function () {
            $("#alert").delay(4500).fadeOut(5000);
        }, 0); 
    </script>

 

</body>
</html>
   