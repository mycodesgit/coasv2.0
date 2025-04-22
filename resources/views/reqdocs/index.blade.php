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
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('template/plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/track-style.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

    <style>
        .bs-stepper-header {
            background-color: #e9ecef;
            border-radius: 0.25rem;
        }
        .active .bs-stepper-circle {
            background-color: #ffc107;
        }
        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0;
            }
        }
        .icheck-success > input:first-child + label::before,
        .icheck-success > input:first-child + input[type="hidden"] + label::before {
            border: 2px solid #28a745; /* Always show green border */
        }

        /* This is your original checked state (keep this) */
        .icheck-success > input:first-child:checked + label::before,
        .icheck-success > input:first-child:checked + input[type="hidden"] + label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-control.is-green {
            border-color: #28a745;
        }
    </style>
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
                                            
                                        </div>
                                        <div class="col-12">
                                            <div class="bs-stepper">
                                                <div class="bs-stepper-header" role="tablist">
                                                    <!-- your steps here -->
                                                    <div class="step" data-target="#guidelines-part">
                                                        <button type="button" class="step-trigger" role="tab" aria-controls="guidelines-part" id="guidelines-part-trigger">
                                                            <span class="bs-stepper-circle">1</span>
                                                            <span class="bs-stepper-label">Guidelines</span>
                                                        </button>
                                                    </div>

                                                    <div class="line text-bold"></div>

                                                    <div class="step" data-target="#information-part">
                                                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                                                            <span class="bs-stepper-circle">2</span>
                                                            <span class="bs-stepper-label">Various information</span>
                                                        </button>
                                                    </div>

                                                    <div class="line text-bold"></div>

                                                    <div class="step" data-target="#verification-part">
                                                        <button type="button" class="step-trigger" role="tab" aria-controls="verification-part" id="verification-part-trigger">
                                                            <span class="bs-stepper-circle">3</span>
                                                            <span class="bs-stepper-label">Verification</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="bs-stepper-content">
                                                    <!-- your steps content here -->
                                                    <div id="guidelines-part" class="content" role="tabpanel" aria-labelledby="guidelines-part-trigger">
                                                        <hr>
                                                            <h4>Guidelines for Requesting School Documents</h4>
                                                            <span class="text-normal">Please be guided by the following instructions when requesting school documents such as Transcript of Records (TOR), Certificate of Grades, Honorable Dismissal, and others:</span>
                                                        <hr>
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
                                                        <hr>
                                                        <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                                                    </div>
                                                    <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                                                        <form id="emailForm">
                                                            <hr>
                                                            <div class="alert alert-secondary alert-dismissible">
                                                                <span>
                                                                    <i class="icon fas fa-exclamation-circle text-warning" style="font-size: 1rem; animation: blink 1s infinite;"></i>
                                                                    <span style="font-size: 12pt">
                                                                        Ensure that the email address you provide is active, as this will be your primary point of contact throughout the entire document request process. An active email is essential for receiving important updates, notifications, and instructions related to your request.
                                                                    </span>
                                                                </span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Email:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                                                    </div>
                                                                    <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" id="email" name="email" required>
                                                                </div>
                                                                <small id="emailHelp" class="form-text text-muted"></small>
                                                            </div>
                                                            <div>
                                                                <h3>Choose the document(s) to request and specify the number of copies you need.</h3>
                                                                <table class="table table-bordered" id="document-table">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th style="width: 5%">Select</th>
                                                                            <th>Documents</th>
                                                                            <th style="width: 15%">Copies</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($docs as $itemdoc)
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    <div class="icheck-success d-inline">
                                                                                        <input type="checkbox" id="checkboxTORbachelor">
                                                                                        <label for="checkboxTORbachelor"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>{{ $itemdoc->docname }}</td>
                                                                                <td>
                                                                                    <label for="inputSuccess"></label>
                                                                                    <input type="number" class="form-control is-green" id="inputSuccess" placeholder="No. of Copies" name="tor_copies" min="1">
                                                                                </td>
                                                                            </tr>    
                                                                        @endforeach
                                                                    </tbody>
                                                                    {{-- <tfoot>
                                                                        <tr>
                                                                            <td colspan="2" class="text-center"><strong>Total Copies: </strong><span id="total-copies">3</span></td>
                                                                        </tr>   
                                                                    </tfoot> --}}
                                                                </table>
                                                            </div>
                                                            <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
                                                        </form>
                                                    </div>
                                                    <div id="verification-part" class="content" role="tabpanel" aria-labelledby="verification-part-trigger">
                                                        <div class="form-group">
                                                            <label for="exampleInputFile">File input</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                </div>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Upload</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">OK</button>
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
    {{-- <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script> --}}

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <!-- BS-Stepper -->
    <script src="{{ asset('template/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>

    <script>
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>

<script>
    $('#email').on('input', function () {
        let email = $(this).val();

        if (email.length > 5 && email.includes('@')) {
            $.ajax({
                url: '{{ route('checkEmail') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email
                },
                success: function (response) {
                    if (response.valid) {
                        $('#emailHelp')
                            .text('Email is valid')
                            .removeClass('text-danger')
                            .addClass('text-success');
                        $('#submitBtn').prop('disabled', false);
                    } else {
                        $('#emailHelp')
                            .text("Email doesn't exist or is undeliverable")
                            .removeClass('text-success')
                            .addClass('text-danger');
                        $('#submitBtn').prop('disabled', true);
                    }
                },
                error: function () {
                    $('#emailHelp')
                        .text("Error validating email")
                        .removeClass('text-success')
                        .addClass('text-danger');
                    $('#submitBtn').prop('disabled', true);
                }
            });
        } else {
            $('#emailHelp')
                .text('Enter a valid email format')
                .removeClass('text-success')
                .addClass('text-muted');
            $('#submitBtn').prop('disabled', true);
        }
    });
</script>


</body>
</html>
   