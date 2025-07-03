<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS - Home</title>

    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/admission-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/sched-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <style>
        .toast-top-right {
            margin-top: 50px;
        }
        .folder-icon {
            transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        .folder-icon:hover {
            transform: scale(1.1);  /* Enlarge the icon on hover */
            color: #000;  /* Change color when hovered */
        }
        .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
            color: #495057;
            /*background-color: #c9c2c2;*/
            border-color: #32ac71 #32ac71 #ffffff;
        }
        .sticky-column {
          position: sticky;
          top: 50px;
          height: 5vh;
        }
    </style>
</head>

<body>
    <header class="header" id="header">
        <div class="header__container">
            <a href="#" class="header__logo">
                <i class="fas fa-diagram-predecessor"></i>
                <span>CISS</span>
            </a>

            <button class="btn btn-default btn-sm" id="header-toggle" style="background-color: rgb(218, 218, 218);">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="d-block d-md-none" style="z-index: -999">
            <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
        </div>
    </header>

    

    <!--=============== SIDEBAR ===============-->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar__container">
            <div class="sidebar__user">
                <div class="sidebar__img">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" alt="image" />
                </div>

                <div class="sidebar__info">
                    <h3 style="margin-top: 10px;">
						@auth('faculty')
                            @if(Auth::guard('faculty')->user()->role == '943')
                                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                            @endif
                        @endauth <br>
						<span>Faculty</span>
					</h3>
                </div>
            </div>

            <div class="sidebar__content">
                <div>
                    @include('partials.control_grade_sidebar')
                </div>
            </div>

            <div class="sidebar__actions">
                <button style="all: unset; cursor: pointer;">
                    <i class="fas fa-moon sidebar__link sidebar__theme" id="theme-button">
                        <span>Dark Mode</span>
                    </i>
                </button>

                <button style="all: unset; cursor: pointer;">
                    <i class="fas fa-power-off sidebar__link sidebar__logout" id="theme-logout">
                        <span>Logout</span>
                    </i>
                </button>
            </div>
        </div>
    </nav>

    <!--=============== MAIN ===============-->
    <main class="main" id="main">
        <div class="carddashsection">
            @section('workspace')
            @show
        </div>
    </main>
    
    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    {{-- <script src="{{ asset('js/basic/madapak.js') }}"></script> --}}
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    {{-- <script src="{{ asset('js/ajax/settngs/dark-mode.js') }}"></script> --}}
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- DataTables  & Plugins -->
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
    <!-- ChartJS -->
    <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('js/validation/grading/gradingAttendanceValidation.js') }}"></script>

    @if(request()->routeIs('homefaculty'))
        <script>
            var semesteractive = {!! json_encode($semesteractive) !!};
            var schlyearActive = {!! json_encode($schlyearactiveYear) !!};
            var progyerlevCounts = {!! json_encode($countstudsubfac) !!};
            var undercolors = {!! json_encode($underproglevsecname) !!};
        </script>
        <script src="{{ asset('js/chart/enbarchartperyearlevfaculty.js') }}?v={{ time() }}"></script>
    @endif

    <script>
        @if(Session::has('error'))
            toastr.options = {
                "closeButton":true,
                "progressBar":true,
                'positionClass': 'toast-top-right'
            }
            toastr.error("{{ session('error') }}")
        @endif

        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false, 
                "autoWidth": true,
                "searching": false,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script type="text/javascript">
        function updateGrade(id, grade){
             $.ajax({
                url: '{{ route('save_grades') }}',
                method: 'POST',
                data: { id: id, grade: grade, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    console.log(data.gradeCount);
                    if(data.gradeCount > 0){
                        $('#submitgradeid').prop('disabled', false);
                    }else{
                        $('#submitgradeid').prop('disabled', true);
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    </script>

    <script type="text/javascript">
        function updateGradeComp(id, grade){
             $.ajax({
                url: '{{ route('save_gradesComp') }}',
                method: 'POST',
                data: { id: id, grade: grade, _token: '{{ csrf_token() }}' },
                success: function (data) {
                    console.log(data.gradeCount);
                    if(data.gradeCount > 0){
                        $('#submitgradeid').prop('disabled', false);
                    }else{
                        $('#submitgradeid').prop('disabled', true);
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $('#submitBtn').click(function () {
                $('#confirmationForm').submit();
            });
        });
    </script>

    @if(request()->routeIs('schedulefac_searchview'))
        @include('grading.gradesheet.faculty.facultyschedscript')
    @endif
</body>
</html>
   