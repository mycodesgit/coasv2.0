<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CPSU || COAS - Portal</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/track-style.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <a href="" class="" style="color: #fff;font-family: Courier;">
                    COAS V.2.0
                </a>
                <div class="" style="z-index: 999">
                    <img src="{{ asset('template/img/CPSU_L.png') }}" style="width:80px;" class="center-top">
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" style="color: #fff">
                             
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="content">
                <div class="container">
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('admission-portal') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active mt-1">Track Admission</li>
                                    </ol>

                                    <p>
                                        @if(Session::has('success'))
                                            <div class="alert alert-success">{{ Session::get('success')}}</div>
                                        @elseif (Session::has('fail'))
                                            <div class="alert alert-danger">{{Session::get('fail')}}</div>
                                        @endif
                                    </p>

                                    <div class="breadcrumb">
                                        <form class="mt-2" style="margin-bottom: -8px;" method="POST" action="{{ route('admission_track_status') }}">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-5">
                                                        <input type="text" name="lname" placeholder="Enter Last Name" class="form-control" oninput="this.value = this.value.toUpperCase()">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" name="fname" placeholder="Enter First Name" class="form-control" oninput="this.value = this.value.toUpperCase()">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-primary form-control form-control-md">
                                                            Search
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="card" style="border: 3px solid #1f5036;">
                                                <div class="card-body box-profile">
                                                    <div class="text-center">
                                                        <img class="img-fluid img-circle" width="30%" alt="Logo" src="{{ asset('template/img/CPSU_L.png') }}">
                                                    </div>
                                                    <ul class="list-group list-group-unbordered mb-3 mt-3">
                                                        <li class="list-group-item">
                                                            <h4>Admission ID: <span id="p1"></span></h4>
                                                            <h6 class="">Name:</h6>
                                                            <h6 class="">Preferred Campus:</h6>
                                                            <h6 class="">Date of Birth:</h6>
                                                            <h6 class="">Email Address:</h6>
                                                            <h6 class="">Contact No.:</h6>
                                                            <h6 class="">Present Address:</h6>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="card" style="border: 3px solid #1f5036;">
                                                <div class="card-body">
                                                    <ul class="timeline">
                                                        <li>
                                                            <div class="timeline-badge success">
                                                                <span class="fas fa-check"></span>
                                                            </div>
                                                            <div class="timeline-date">
                                                                <h5 style="font-size: 10px;">{{ date('M d, Y') }}</h5>
                                                            </div>
                                                            <div class="timeline-panel">
                                                                <div class="timeline-heading">
                                                                    <div class="timeline-title">
                                                                        <p>Application</p>
                                                                        <h5>Submitted/Recorded Applicant Information</h5>
                                                                        <h6>Status: Applied for Admission</h6>
                                                                        <h6><i>Date: {{ date('m-d-Y') }}</i></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="timeline-body">
                                                                    <div class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="timeline-badge warning">
                                                                <span class="fas fa-clock"></span>
                                                            </div>
                                                            <div class="timeline-date">
                                                                <h5 style="font-size: 10px;">Waiting</h5>
                                                            </div>
                                                            <div class="timeline-panel">
                                                                <div class="timeline-heading">
                                                                    <div class="timeline-title">
                                                                        <p>Examination Schedule</p>
                                                                        <h5>Exam Schedule set! <b>Date</b>: , <b>Time</b>: , <b>Venue</b>: </h5>
                                                                        <h6><i>Status: Waiting</i></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="timeline-body">
                                                                    <div class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="timeline-badge warning">
                                                                <span class="fas fa-clock"></span>
                                                            </div>
                                                            <div class="timeline-date">
                                                                <h5 style="font-size: 10px;">Waiting</h5>
                                                            </div>
                                                            <div class="timeline-panel">
                                                                <div class="timeline-heading">
                                                                    <div class="timeline-title">
                                                                        <p>Examination Results</p>
                                                                        <h5>Results is out! Your <b>score</b>: , <b>Percentile</b>: </h5>
                                                                        <h6><i>Status: Waiting</i></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="timeline-body">
                                                                    <div class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="timeline-badge warning">
                                                                <span class="fas fa-clock"></span>
                                                            </div>
                                                            <div class="timeline-date">
                                                                <h5 style="font-size: 10px;">Waiting</h5>
                                                            </div>
                                                            <div class="timeline-panel">
                                                                <div class="timeline-heading">
                                                                    <div class="timeline-title">
                                                                        <p>Confirmation</p>
                                                                        <h5>Waiting for confirmation on Pre-enrolment!</h5>
                                                                        <h6><i>Status: Waiting</i></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="timeline-body">
                                                                    <div class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="timeline-badge warning">
                                                                <span class="fas fa-clock"></span>
                                                            </div>
                                                            <div class="timeline-date">
                                                                <h5 style="font-size: 10px;">Waiting</h5>
                                                            </div>
                                                            <div class="timeline-panel">
                                                                <div class="timeline-heading">
                                                                    <div class="timeline-title">
                                                                        <p>Acceptance</p>
                                                                        <h5>Accepted for the program: <b></b></h5>
                                                                        <h6><i>Status: Waiting</i></h6>
                                                                    </div>
                                                                </div>
                                                                <div class="timeline-body">
                                                                    <div class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer text-sm text-center" style="background-color: #04401f;">
            <div class="float-right d-none d-sm-inline "></div>
            <i class="text-light">CPSU - COAS V.1.0 is built through O-S Technology, a Shukerz-Based product. Copyright © 2023 CPSU, All Rights Reserved.</i>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Context -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

</body>
</html>
   