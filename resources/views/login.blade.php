<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CPSU || COAS - Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/trans-style.css') }}">
    <!-- Logo for demo purposes -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

    <style type="text/css">

    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card custom-card" style="border: 3px solid #04401f;">
            <div class="card-body login-card-body">
                <div class="row">
                    <div class="col-md-7 d-none d-md-block">
                        <h3 style="font-weight: bold;color:#04401f;" class="card-footer">
                            CPSU - COAS V2.0
                        </h3>
                        <hr>
                        <div class="">
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <p class="lead" alt="First slide">Consolidated Online Access System (COAS) is an advanced application system for the University Frontline Services. </p>
                                        <p class="lead" alt="First slide">CPSU - COAS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</p>
                                    </div>
                                    <div class="carousel-item">
                                        <p class="lead" alt="Second slide">Consolidated Online Access System (COAS) is an advanced application system for the University Frontline Services. </p>
                                        <p class="lead" alt="Second slide">CPSU - COAS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</p>
                                    </div>
                                    <div class="carousel-item">
                                        <p class="lead" alt="Third slide">Consolidated Online Access System (COAS) is an advanced application system for the University Frontline Services. </p>
                                        <p class="lead" alt="Third slide">CPSU - COAS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>Visit <a href="https://cpsu.edu.ph" target="_blank" style="color:#04401f;"><b>Official Website</b></a> for more information.</p>
                    </div>

                    <div class="col-md-5 col-sm-12 pr-4 pl-4 pt-2 pb-2 w-100 col-12" style="background-color: #04401f; border-radius: 5px;">
                        <div class="login-logo mt-2">
                            <a href="{{ route('main') }}">
                                <img src="{{ asset('template/img/cpsulogo.png') }}" class="img-circle" width="100px" height="100px">
                            </a>
                            
                        </div>
                        <h5 class="login-box-msg text-light">LOGIN</h5>

                        <div>
                            <form action="{{ route('emp_login') }}" method="post">
                                @csrf

                                @if(session('error'))
                                    <div class="alert alert-danger" style="font-size: 12pt;">
                                        <i class="fas fa-exclamation-triangle "></i> {{session('error')}}
                                    </div>
                                @endif

                                @if(session('success'))
                                    <div class="alert alert-success" style="font-size: 10pt;">
                                    <i class="fas fa-check"></i> {{session('success')}}
                                    </div>
                                @endif

                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="Email" autofocus="" required="">
                                    <div class="input-group-append" style="background-color: #fff; border-radius: 5px 5px 5px 5px">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>
                                <span style="color: #FF0000; font-size: 8pt;" class="form-text text-center">@error('email') {{ $message }} @enderror</span>

                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="myInput" placeholder="Password" required="">
                                    <div class="input-group-append" style="background-color: #fff; border-radius: 5px 5px 5px 5px">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                                <span id="password" style="color: #FF0000; font-size: 8pt;" class="form-text text-center">@error('password') {{ $message }} @enderror</span>
                                
                                <div class="row mt-4">
                                    <div class="col-md-7">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="remember" onclick="myFunction()">
                                            <label for="remember" style="color: #fff">Show Password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <button type="submit" class="btn btn-warning float-right">
                                            <i class="fas fa-sign-in-alt"></i> Sign In
                                        </button>
                                    </div>
                                </div>
                            </form> 
                        </div>
                    </div>   
                </div> 
            </div>
        </div>
        <div class="loader"></div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Context -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    <script>
        window.addEventListener("load", () => {
            const loader = document.querySelector(".loader");
                loader.classList.add("loader--hidden");
                loader.addEventListener("transitioned", () => {
                    document.body.removeChild(loader);
            });
        });
    </script>

    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>
</html>