<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS V.1.0 - Portal</title>

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
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/track-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/upload-image.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <style>
        .progress-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 20px !important;
        }
        .progress-container {
            display: flex;
            align-items: center;
            border-radius: 20px !important;
        }
        .progress-bar {
            transition: width 0.3s ease;
            border-radius: 20px !important;
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

        <div class="content-wrapper" style="background-color: #daf1ea !important">
            <div class="content">
                <div class="">
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-lg-6 offset-lg-3 col-lg-offset-4 col-lg-center px-3">
                            <div class="card card-secondary card-outline">
                                <div class="card-body">
                                    <h6 class="card-title text-dark">
                                        <h1 class="text-success"><strong>Thank you</strong><br><br></h1>
                                        <h3>Your <strong>Application</strong> was submitted successfully!</h3>
                                        <h5>Please check your email for a copy of your application.</h5>
                                    </h6>
                                </div>
                                <form method="post" action="{{ route('sendThankYouEmail') }}">
                                    @csrf
                                    <input type="text" name="email" class="form-control" value="{{ old('email, $email') }}">
                                    <input type="text" name="year" class="form-control" value="{{ old('year, $year') }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer text-sm text-center" style="background-color: #daf1ea; border-top: none;">
            <div class="float-right d-none d-sm-inline "></div>
            <i class="text-dark">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
        </footer>
    </div>

    @include('portal.modal-terms')

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Context -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <script>
        let historyLock = setInterval(() => {
            history.pushState(null, null, location.href);
        }, 100); 

        setTimeout(() => {
            Swal.fire({
                title: 'We received your application!',
                text: 'Thank you for applying.',
                icon: 'success',
                confirmButtonText: 'Okay'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearTimeout(autoSendEmailTimeout);

                    const emailInput = document.querySelector('input[name="email"]'); 
                    if (emailInput) { 
                        const email = emailInput.value;
                        sendEmail(email);
                    } else {
                        console.error('Email input not found');
                    }
                }
            });

            // Set a timeout to automatically send the email if "Okay" is not clicked within 5 seconds
            let autoSendEmailTimeout = setTimeout(() => {
                const emailInput = document.querySelector('input[name="email"]'); 
                if (emailInput) { 
                    const email = emailInput.value;

                    sendEmail(email);
                } else {
                    console.error('Email input not found');
                }
            }, 2000); 
        }, 2000);

        // Function to send the email
        function sendEmail(email) {
            fetch('{{ route('sendThankYouEmail') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
   