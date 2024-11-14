<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/admission-style.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        input[readonly] {
            background-color: #fff !important;
        }
        .toast-top-right {
            margin-top: 50px;
        }
        .sticky-column {
          position: sticky;
          top: 50px;
          height: 5vh;
        }
        .scrolling-column {
          overflow-y: auto;
        }
        .my-custom-show-animation {
            animation: myShowAnimation 0.2s ease forwards;
        }
        @keyframes myShowAnimation {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }

        .bg-secondary {
            background-color: #e9ecef !important;
            color: #252525 !important;
        }
    </style>
    
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <div href="" class="" style="color: #fff;font-family: Courier;">
                    CISS V.1.0
                </div>

                <div class="" style="z-index: 999">
                    <img src="{{ asset('template/img/CPSU_L.png') }}" style="width:80px;" class="center-top">
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" style="color: #fff">
                            @auth('web')
                                @if(in_array(Auth::guard('web')->user()->isAdmin, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]))
                                    Logged as: {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}
                                @endif
                            @endauth

                            @auth('faculty')
                                @if(Auth::guard('faculty')->user()->isAdmin == '943')
                                    Logged as: {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                                @endif
                            @endauth
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid" style="padding-top: 20px"></div>
            </div>
            <div class="content">
                <div class="container-fluid1">
                    <div class="row" style="padding-top: 0px;">
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
                                    @section('sideheader')
                                    @show
                                </div>
                                @section('sidemenu')
                                    @include('partials.control_en_sidebar')
                                @show
                            </div>
                        </div>
                        <div class="col-lg-10">
                            @section('workspace')
                                <div class="card">
                                    <div class="card-body">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item active mt-1">Enrollment</li>
                                        </ol>
                                        <div class="workspace-top" style="text-align: center;">
                                            <div class="row">
                                                @if(Auth::guard('web')->check() && Auth::guard('web')->user()->role >= 0 && Auth::guard('web')->user()->role <= 14)
                                                    @if(request()->routeIs('enrollment-index'))
                                                        <div class="col-lg-3 col-6">
                                                            <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #00bc8c !important">
                                                                <div class="text-left">
                                                                    <div class="inner">
                                                                        <h3>{{ $enrlstudcountfirst }}</h3>
                                                                        <p>1st Stud Enrolled this Sem</p>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="icon">
                                                                        <i class="fa fa-users"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #89c9b6 !important">
                                                                <div class="text-left">
                                                                    <div class="inner">
                                                                        <h3>{{ $enrlstudcountsecond }}</h3>
                                                                        <p>2nd Stud Enrolled this Sem</p>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="icon">
                                                                        <i class="fa fa-users"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #9dcda8 !important">
                                                                <div class="text-left">
                                                                    <div class="inner">
                                                                        <h3>{{ $enrlstudcountthird }}</h3>
                                                                        <p>3rd Stud Enrolled this Sem</p>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="icon">
                                                                        <i class="fa fa-users"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #008b51 !important">
                                                                <div class="text-left">
                                                                    <div class="inner">
                                                                        <h3>{{ $enrlstudcountfourth }}</h3>
                                                                        <p>4th Stud Enrolled this Sem</p>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="icon">
                                                                        <i class="fa fa-users"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="chart-responsive pt-1">
                                                                        <canvas id="firstSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="chart-responsive pt-1">
                                                                        <canvas id="secondSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="chart-responsive pt-1">
                                                                        <canvas id="prevSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="chart-responsive pt-1">
                                                                        <canvas id="currSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="chart-responsive pt-1">
                                                                        <canvas id="currSemesterunderprogBarChart" style="height:330px; min-height:330px"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h3 class="card-title">Enrollment for this current Semester in All Campuses</h3>
                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="position-relative mb-4">
                                                                        <canvas id="enrlmntpercamp-chart"
                                                                                data-main="{!! $MainEnrollmentCount !!}"
                                                                                data-victorias="{!! $VcEnrollmentCount !!}" 
                                                                                data-sancarlos="{!! $SccEnrollmentCount !!}"  
                                                                                data-hinigaran="{!! $HcEnrollmentCount !!}"  
                                                                                data-moises="{!! $MpEnrollmentCount !!}"  
                                                                                data-ilog="{!! $IcEnrollmentCount !!}"  
                                                                                data-candoni="{!! $CaEnrollmentCount !!}"  
                                                                                data-cauayan="{!! $CcEnrollmentCount !!}"  
                                                                                data-siplay="{!! $ScEnrollmentCount !!}"  
                                                                                data-hinobaan="{!! $HinCEnrollmentCount !!}"  
                                                                                height="200">
                                                                        </canvas>
                                                                    </div>
                                                                    <div class="d-flex flex-row justify-content-end">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif

                                                @if(Auth::guard('web')->user()->role == 15)
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="chart-responsive pt-1">
                                                                    <canvas id="prevSemestergradBarChart" style="height:330px; min-height:330px"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="chart-responsive pt-1">
                                                                    <canvas id="currSemestergradBarChart" style="height:330px; min-height:330px"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="chart-responsive pt-1">
                                                                    <canvas id="currSemestergradprogBarChart" style="height:330px; min-height:330px"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                                        </div>
                                    </div>
                                </div>
                            @show
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer text-sm text-center" style="background-color: #04401f;">
            <div class="float-right d-none d-sm-inline "></div>
            <i class="text-light">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

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
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    
    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}"></script>
    <script src="{{ asset('js/basic/yearscript.js') }}"></script>
    <script src="{{ asset('js/basic/schoolyear.js') }}"></script>
    <script src="{{ asset('js/basic/subjects.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->role >= 0 && Auth::guard('web')->user()->role <= 14)
        <!-- Basic -->
        @if(request()->routeIs('enrollment-index'))
            <script>
                // Convert PHP data to JavaScript variables
                var collbar1Route = {!! json_encode($collegesFirstSemester) !!}; // Previous school year data
                var collbar2Route = {!! json_encode($collegesSecondSemester) !!}; // Current school year data
                var semesteractive = {!! json_encode($semesteractive) !!};
                var prevsemesteractive = {!! json_encode($prevsemesteractive) !!};
                var schlyearActive = {!! json_encode($schlyearactiveYear) !!}; // Current active school year
                var previousSchlyearYear = {!! json_encode($previousSchlyearYear) !!}; // Previous school year
                var prevenrolmentCounts = {!! json_encode($prevenrolmentCounts) !!};
                var currenrolmentCounts = {!! json_encode($currenrolmentCounts) !!};
                var currunderprogramenrolmentCounts = {!! json_encode($currunderprogramenrolmentCounts) !!};
                var underprogramAcronyms = {!! json_encode($underprogramAcronyms) !!};
                var undercolors = {!! json_encode($underprogramAcronyms) !!};
            </script>
            <script src="{{ asset('js/chart/enbarchart.js') }}"></script>
            <script src="{{ asset('js/chart/enbarchartperyearlev.js') }}"></script>
        @endif
    @endif

    @if(Auth::guard('web')->user()->role == 15)
        <!-- Basic -->
        @if(request()->routeIs('enrollment-index'))
            <script>
                // Convert PHP data to JavaScript variables
                var semesteractive = {!! json_encode($semesteractive) !!};
                var prevsemesteractive = {!! json_encode($prevsemesteractive) !!};
                var schlyearActive = {!! json_encode($schlyearactiveYear) !!}; // Current active school year
                var previousSchlyearYear = {!! json_encode($previousSchlyearYear) !!}; // Previous school year
                var prevgradenrolmentCounts = {!! json_encode($prevgradenrolmentCounts) !!};
                var currgradenrolmentCounts = {!! json_encode($currgradenrolmentCounts) !!};
                var currgradProgenrolmentCounts = {!! json_encode($currgradprogramenrolmentCounts) !!};
                var programAcronyms = {!! json_encode($programAcronyms) !!};
                var colors = {!! json_encode($programAcronyms) !!};
            </script>
            <script src="{{ asset('js/chart/gradenbarchart.js') }}"></script>
        @endif
    @endif

    <!-- Ajax -->
    @if(request()->routeIs('subjectsRead'))
        <script src="{{ asset('js/ajax/enrolment/subjectSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studentCreate'))
        <script src="{{ asset('js/ajax/enrolment/studentAddSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('searchStudEnroll'))
        <script src="{{ asset('js/ajax/enrolment/enrollmentSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('editsearchStudRead'))
        <script src="{{ asset('js/ajax/enrolment/editEnrollmentSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('viewsearchenStudHistory'))
        <script src="{{ asset('js/ajax/enrolment/studenHistorySerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studCurrsearch'))
        <script src="{{ asset('js/ajax/enrolment/enrollmentCourseSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studgrade_searchlist', 'studgradegrad_searchlist'))
        <script src="{{ asset('js/ajax/enrolment/gradesheetSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('listsearch_studsubjectsRead'))
        <script src="{{ asset('js/ajax/enrolment/studenrollAttendanceSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('geneStudent1'))
        <script src="{{ asset('js/ajax/enrolment/passwordGrade.js') }}"></script>
    @endif
    @if(request()->routeIs('elpl_listsearch'))
        <script src="{{ asset('js/ajax/enrolment/elplSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studInfo_search'))
        <script src="{{ asset('js/ajax/enrolment/studentinfoSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studInfograduated_search'))
        <script src="{{ asset('js/ajax/enrolment/studentinfograduatedSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('elpl_list', 'elpl_listsearch'))
        <script src="{{ asset('js/ajax/enrolment/getcourseSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studnoenrollee'))
        <script src="{{ asset('js/ajax/enrolment/enrolleescountSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('studnoNSTPenrollee'))
        <script src="{{ asset('js/ajax/enrolment/enrolleesnstpcountSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('searchEncode_gradeRead'))
        <script src="{{ asset('js/ajax/enrolment/gradesheetLogsSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('search_delenrlmntlogsRead'))
        <script src="{{ asset('js/ajax/enrolment/deletedEnrollmentLogsSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('search_studenrollRead'))
        <script src="{{ asset('js/ajax/enrolment/studenrollSerialize.js') }}"></script>
    @endif

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('js/validation/enroll/addStudValidation.js') }}"></script>
    <script src="{{ asset('js/validation/enroll/enrollValidation.js') }}"></script>
    <script src="{{ asset('js/validation/enroll/gradesheetValidation.js') }}"></script>
    <script src="{{ asset('js/validation/enroll/subjectValidation.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#searchDropdown').select2({
                placeholder: '-- Search and Select --',
                allowClear: true,
                minimumInputLength: 6
            });
        });
    </script>

    <script type="text/javascript">
        setTimeout(function () {
            $("#alert").delay(2500).fadeOut(5000);
        }, 0); 
    </script>

    <script type="text/javascript">
        function updateGrade(id, grade){
            //alert(id);
             $.ajax({
                url: '{{ route('registrarsave_grades') }}',
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
        function updateGradeComp(id, grade){
            //alert(id);
             $.ajax({
                url: '{{ route('registrarsave_gradesComp') }}',
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
        @if(Session::has('success'))
            toastr.options = {
                "closeButton":true,
                "progressBar":true,
                'positionClass': 'toast-top-right'
            }
            toastr.success("{{ session('success') }}")
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'warning',
                // title: 'Waring',
                html: '{!! session('error') !!}',
                showClass: {
                    popup: 'my-custom-show-animation'
                },
                hideClass: {
                    popup: ''
                }
            });
        @endif
    </script>
    
    @if(request()->routeIs('editsearchStudRead'))
    <script>
        document.getElementById('deleteButton').addEventListener('click', function() {
            var programEnHistoryId = document.querySelector('input[name="id"][value="{{ $programEnHistory->id }}"]').value;
            var studentAppraisalIds = document.querySelector('input[name="id"][value="{{ $primIDsString }}"]').value;
            var stuGradesIds = document.querySelector('input[name="id"][value="{{ $studsubenrollIdsprimID }}"]').value;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover the Enrollment",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('deleteAllRecords') }}',
                        type: 'DELETE',
                        data: {
                            programEnHistoryId: programEnHistoryId,
                            studentAppraisalIds: studentAppraisalIds,
                            stuGradesIds: stuGradesIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    window.location.href = response.redirect_url;
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Deletion failed.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'An error occurred while processing your request.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
    @endif
</body>
</html>
   