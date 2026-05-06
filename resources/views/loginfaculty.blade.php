<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS - Login</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/bootstrap.min.css') }}" crossorigin="anonymous">
    <!-- Login Design -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/login-style.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- Logo -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .btn-light {
            --bs-btn-color: #000;
            --bs-btn-bg: #d3d4d5;
            --bs-btn-border-color: #f8f9fa;
            --bs-btn-hover-color: #000;
            --bs-btn-hover-bg: #d3d4d5;
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
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-icons">
            <i class="bi bi-book-fill"></i>
            <i class="bi bi-building"></i>
            <i class="bi bi-mortarboard-fill"></i>
            <i class="bi bi-book-fill"></i>
            <i class="bi bi-building"></i>
            <i class="bi bi-mortarboard-fill"></i>
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
                        <p>Sign in to start session</p>

                    </div>
                    <div class="">
                        {{-- <button class="btn btn-lg btn-success w-100 fs-6">Login</button> --}}
                        <a href="{{ route('google.login') }}" class="btn btn-lg btn-light w-100 fs-8"><img src="{{ asset('template/img/googlelogo.png') }}" style="width:25px" class="me-2">
                            <small>Sign In with Google</small>
                        </a>
                        {{-- <form action="{{ route('fac_login') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email" id="empEmailInput" autofocus>
                            </div>
                            <div class="input-group mb-1">
                                <input type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" id="studentPassInput">
                            </div>
                            <div class="input-group mb-4 d-flex justify-content-between">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="formCheck" onclick="myFunction()">
                                    <label for="formCheck" class="form-check-label text-secondary"><small>Show Password</small></label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <button class="btn btn-lg btn-success w-100 fs-6">Login</button>
                            </div>
                        </form> --}}
                        {{-- <div class="col-12">
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                <div>
                                    Faculty Portal is currently unavailable. Please check back later.
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div> 
            <span style="font-size: 9pt; text-align: center; margin-top: 10px;">Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</span>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
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

        function myFunction() {
            var x = document.getElementById("studentPassInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
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