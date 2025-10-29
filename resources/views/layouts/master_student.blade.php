<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('template/student/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/sched-style.css') }}" media="(min-width: 768px)">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/student/style.css') }}">
    

    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/fullcalendar/fullcalendar.css') }}">

</head>

<body class="text-sm">
    <div style="height: 25px; background: #f7f7f7; position: fixed; top: 0; left: 0; right: 0; z-index: 998;"></div>
    <nav id="sidebar">
        @include('partials.control_student_sidebar')
    </nav>
    
    <main>

        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-custom" style="border-radius: 15px; background-color: #ffffff !important; margin-top: -15px;">
            <div class="container-fluid">
                <a class="navbar-brand text-gray" href="#"><i class="fas fa-diagram-predecessor" style="color: #666"></i> CISS</a>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li> -->
                    </ul>
                </div>
                <form class="">
                    <a href="{{ route('destory.logout') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-power-off"></i> Sign Out
                    </a>
                </form>
            </div>
            <div class="d-block" style="z-index: 999">
                <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
            </div>
        </nav>
        
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-custom d-lg-none" style="border-radius: 15px; background-color: #198754 !important; margin-top: -15px;">
            <div class="container-fluid">
                <a class="navbar-brand text-light" href="#"><i class="fas fa-diagram-predecessor" style="color: #e9ecef"></i> CISS</a>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li> -->
                    </ul>
                </div>
                <form class="">
                    <a href="{{ route('destory.logout') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-power-off"></i> Sign Out
                    </a>
                </form>
            </div>
            <div class="d-block d-md-none" style="z-index: 999">
                <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
            </div>
        </nav>

        @yield('body')
    </main>

    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/student/app.js') }}" defer></script>

    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>

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

    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/plugins/fullcalendar/fullcalendar.js') }}"></script>

    @if(request()->routeIs('schedstudentclassShow'))
        <script>
            var days = @json($days);
            var times = @json($times);
        </script>
        @include('student.scheds.viewscheduleresultscript')
    @endif
</body>

</html>