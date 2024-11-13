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

        <div class="content-wrapper" style="background-color: #daf1ea !important">
            <div class="content">
                <div class="">
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-lg-6 offset-lg-3 col-lg-offset-4 col-lg-center px-3">
                            <br>

                            <form id="fileUploadForm" method="post" action="{{ route('uploadDocuments') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Search Applicant</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="search_lastname">Last Name</label>
                                                <input type="text" id="search_lastname" class="form-control form-control-sm" name="lname" placeholder="Enter Last Name" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="search_firstname">First Name</label>
                                                <input type="text" id="search_firstname" class="form-control form-control-sm" name="fname" placeholder="Enter First Name" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Preferred Campus <i style="color: red">*</i></label>
                                                <select class="form-control form-control-sm" name="campus" id="campus">
                                                    <option disabled selected>Select</option>
                                                    <option value="MC">Main</option>
                                                    <option value="VC">Victorias</option>
                                                    <option value="SCC">San Carlos</option>
                                                    <option value="MP">Moises Padilla</option>
                                                    <option value="HC">Hinigaran</option>
                                                    <option value="IC">Ilog</option>
                                                    <option value="CA">Candoni</option>
                                                    <option value="CC">Cauayan</option>
                                                    <option value="SC">Sipalay</option>
                                                    <option value="HinC">Hinobaan</option>
                                                    <option value="VE">Valladolid</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" id="searchApplicant" class="btn btn-info mt-3">Search</button>
                                    </div>
                                </div>

                                <!-- Auto-filled Applicant Information -->
                                <div id="card-1" style="display: none;">
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <label for="admissionid">Your Admission ID</label>
                                            <input type="text" id="admissionid" name="admissionid" class="form-control" readonly>
                                            <input type="hidden" id="fname" name="fname" class="form-control" readonly>
                                            <input type="hidden" id="lname" name="lname" class="form-control" readonly>
                                            <input type="hidden" id="primaryid" name="id" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        @if(Session::has('success'))
                                            <div class="alert alert-success" id="alert">{{ Session::get('success')}} {{ Session::get('admission_id')}}</div>
                                        @elseif (Session::has('fail'))
                                            <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                                        @endif
                                    </p>

                                
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title m-0">Documents Information</h5>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Upload School ID <i style="color: red">*</i></label>
                                                        <input type="file" name="studiddoc_image" class="form-control form-control-sm" id="fileInput" accept="image/*" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Upload Proof/Evidence of Disadvantage Situation <i style="color: red">*</i></label>
                                                        <input type="file" name="proofdoc_image" class="form-control form-control-sm" accept="image/*" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress-section d-flex align-items-center justify-content-between mt-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                                <br><br>
                            </form>
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
        document.getElementById('searchApplicant').addEventListener('click', function () {
            let lastname = document.getElementById('search_lastname').value.trim();
            let firstname = document.getElementById('search_firstname').value.trim();
            let campus = document.getElementById('campus').value;

            if (!lastname || !firstname || campus === 'Select') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Fields',
                    text: 'Please fill all search fields.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Perform AJAX request to search for the applicant
            fetch('{{ route("searchApplicant") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ lastname, firstname, campus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Auto-fill the fields with the retrieved data
                    document.getElementById('admissionid').value = data.applicant.admission_id;
                    document.getElementById('primaryid').value = data.applicant.primaryid;
                    document.getElementById('lname').value = data.applicant.lname;
                    document.getElementById('fname').value = data.applicant.fname;
                    document.getElementById('card-1').style.display = 'block';
                    Swal.fire({
                        icon: 'success',
                        title: 'Applicant Found',
                        text: 'The applicant data has been retrieved successfully.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found',
                        text: 'No applicant found with the provided details.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while searching. Please try again later.',
                    confirmButtonText: 'OK'
                });
            });
        });

        $(document).on('submit', '#fileUploadForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('uploadDocuments') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        console.log('Updated files:', response.data);
                        $(document).trigger('schedExamUpdated');

                        // Show SweetAlert with countdown timer and redirect
                        let countdown = 5; // Countdown in seconds
                        Swal.fire({
                            icon: 'success',
                            title: 'Successful',
                            html: `Your documents have been uploaded successfully.<br><b>Redirecting in <span id="countdown">${countdown}</span> seconds...</b>`,
                            timer: countdown * 1000,
                            showConfirmButton: false,
                            didOpen: () => {
                                // Update countdown every second
                                const countdownElement = Swal.getHtmlContainer().querySelector('#countdown');
                                const interval = setInterval(() => {
                                    countdown--;
                                    countdownElement.textContent = countdown;
                                    if (countdown <= 0) {
                                        clearInterval(interval);
                                    }
                                }, 1000);
                            },
                            willClose: () => {
                                // Redirect after countdown
                                window.location.href = "{{ route('repupredirectexpire') }}"; // Change this to your target route
                            }
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.error || 'An error occurred. Please try again.'
                    });
                }
            });
        });


    </script>
</body>
</html>
   