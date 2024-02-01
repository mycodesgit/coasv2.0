<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Page not found</title>

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
                            CPSU - COAS V1.0
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
                            <div>
                                <img src="{{ asset('template/img/cpsulogo.png') }}" class="img-circle" width="100px" height="100px">
                            </div>
                            
                        </div>

                        <div class="text-light">
                            <center>
                                <div style="font-size: 16pt;">Ouch! Something went wrong</div>
                                <h1>404</h1>
                                <h3>Page not found!</h3>
                                <div>---------------------------------------</div>
                                <div>Contact Support Administrator</div>
                            </center>
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

    <script>
        window.addEventListener("load", () => {
            const loader = document.querySelector(".loader");
                loader.classList.add("loader--hidden");
                loader.addEventListener("transitioned", () => {
                    document.body.removeChild(loader);
            });
        });
</script>
</body>
</html>