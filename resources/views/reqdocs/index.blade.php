<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS V.1.0 - Request for Documents</title>

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
                    CISS V.1.0
                </a>
                <div class="" style="z-index: 999">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:80px;" class="center-top">
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
                                            <a href="{{ route('main') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active mt-1">Request for Documents</li>
                                    </ol>

                                    <p>
                                        @if(Session::has('success'))
                                            <div class="alert alert-success">{{ Session::get('success')}}</div>
                                        @elseif (Session::has('fail'))
                                            <div class="alert alert-danger">{{Session::get('fail')}}</div>
                                        @endif
                                    </p>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr>
                                                <h4>Guidelines for Requesting School Documents</h4>
                                                <span class="text-normal">Please be guided by the following instructions when requesting school documents such as Transcript of Records (TOR), Certificate of Grades, Honorable Dismissal, and others:</span>
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <div class="post text-dark">
                                                <ol>
                                                    <li>
                                                        All document requests will be treated like a reservation/booking. This means that documents will only be processed after your request has been successfully received and scheduled.
                                                    </li>
                                                    <li>
                                                        Standard processing time is 3 to 5 working days, depending on the type of document.
                                                    </li>
                                                    <li>
                                                        After submitting your request, you will receive a confirmation message or pick-up date.<br>
                                                        <span class="ms-4">Do not go directly to the office without confirmation, as unbooked walk-ins may not be accommodated.</span>
                                                    </li>
                                                    <li>
                                                        Authorized Representatives<br>
                                                        <span class="ms-4">If someone else is claiming your documents, provide them with:</span>
                                                        <ul class="ms-5">
                                                            <li>A signed authorization letter</li>
                                                            <li>Photocopy of your valid ID</li>
                                                            <li>Their valid ID</li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        For updates or concerns, please contact the Registrar’s Office via <strong>[insert contact info/email/phone]</strong>.
                                                    </li>
                                                </ol>
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
   