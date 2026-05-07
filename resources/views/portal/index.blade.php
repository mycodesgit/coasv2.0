<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Login Design -->
    <link rel="stylesheet" href="{{ asset('uilibs/css/login-style.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">
    <!-- fontawesome -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Logo -->
    <link rel="shortcut icon" type="" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <style>
        .btn-secondary {
            --bs-btn-color: #000;
            --bs-btn-bg: #adbfcd;
            --bs-btn-border-color: #f8f9fa;
            --bs-btn-hover-color: #000;
            --bs-btn-hover-bg: #046706;
            --bs-btn-hover-color: #f8f9fa;
            --bs-btn-hover-border-color: #c6c7c8;
            --bs-btn-focus-shadow-rgb: 211, 212, 213;
            --bs-btn-active-color: #000;
            --bs-btn-active-bg: #c6c7c8;
            --bs-btn-active-border-color: #babbbc;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #000;
            --bs-btn-disabled-bg: #f8f9fa;
            --bs-btn-disabled-border-color: #f8f9fa;
        }
        .btn.disabled, .btn:disabled, fieldset:disabled .btn {
            color: var(--bs-btn-disabled-color);
            pointer-events: none;
            background-color: #adbfcd;
            border-color: #c6c7c8;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-icons d-none d-md-flex">
            <i class="fas fa-book-open"></i>
            <i class="fas fa-building"></i>
            <i class="fas fa-graduation-cap"></i>
            <i class="fas fa-book-open"></i>
            <i class="fas fa-building"></i>
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #04401f;">
                {{-- <div id="particles-js"></div> --}}
                <div class="featured-image mb-3">
                    <center><img src="{{ asset('template/img/cpsulogov4.png') }}" class="img-fluid" id="" style="width: 100px; padding-top: 0px;"></center>
                    <p class="text-white text-center" style="font-family: 'Courier New', Courier, monospace; font-weight: 600; font-size: 1.5em !important">CISS</p>
                </div>
                <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">CPSU Integrated System Solution</br></small>
                {{-- <center><img src="{{ asset('template/img/cpsulogov4.png') }}" class="img-fluid" id="cpsulogoleftsideImage" style="width: 80%; padding-top: 0px;"></center> --}}
            </div> 
        
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4 text-center">
                        <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:100px; margin-top: -250px" id="cpsulogoImage">
                        <h2>Hi, Cenphilian</h2>
                        <p>Select Transaction</p>

                    </div>
                    <div class="">
                        <div>
                            @if ($admissionmode->statusadmission == 'On')
                                <a href="{{ route('admission-apply') }}" class="btn btn-success btn-lg w-100 fs-6 text-bold mb-2">Apply for Admission</a>
                            @else
                                <a href="#" class="btn btn-success btn-lg w-100 fs-6 text-bold mb-2 disabled">Apply for Admission</a>
                            @endif
                            <a href="{{ route('admission_track') }}" class="btn btn-success btn-lg w-100 fs-6 text-bold mb-2">Track your Admission</a>
                            <a href="#" class="btn btn-success btn-lg w-100 fs-6 text-bold mb-2 disabled">Re-upload Documents</a>
                            <a href="{{ route('main') }}" class="btn btn-outline-warning btn-lg w-100 fs-6 text-bold mb-2 text-dark">Back</a>
                        </div>
                    </div>
                </div>
            </div> 
            <span style="font-size: 9pt; text-align: center; margin-top: 10px;">Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</span>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}?v={{ time() }}"></script>
    <!-- Moment -->
    <script src="{{ asset('uilibs/plugins/moment/moment.min.js') }}?v={{ time() }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}?v={{ time() }}"></script>
    {{-- <script src="{{ asset('particles/particles.js') }}"></script>
    <script src="{{ asset('particles/app.js') }}"></script> --}}
    <!-- Context -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    

    <script>
        $(document).ready(function() {
            @if(session('error'))
                toastr.error("{{ session('error') }}", "Error", {
                    closeButton: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 10000
                });
            @endif

            @if(session('success'))
                toastr.success("{{ session('success') }}", "Success", {
                    closeButton: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 10000
                });
            @endif
        });
    </script>
    <script>
        document.addEventListener("mousemove", (e) => {
            const icons = document.querySelectorAll(".bg-icons i");

            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;

            icons.forEach((icon, index) => {
                const speed = (index + 1) * 0.5;

                icon.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
                icon.style.transition = "transform 0.2s ease-out";
            });
        });
    </script>
    
</body>
</html>