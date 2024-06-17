<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>

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
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/admission-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/custom.css') }}">
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
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /*.highlight {
            background-color: #b2b9bf;
        }*/

        /* Allow text selection within the schedule grid */
        #schedule-grid {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }

        /* Prevent text selection for time labels */
        .time-label {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        #schedule-grid {
            border-collapse: collapse; /* Collapse borders */
            width: 100%; /* Set table width to 100% */
        }

        #schedule-grid th, #schedule-grid td {
            border: 1px solid #ccc; /* Example border style */
            padding: 8px; /* Adjust padding as needed */
            text-align: center; /* Center text within cells */
            height: 10px; /* Set height of table rows */
            line-height: 10px; /* Adjust line-height to vertically center content */
        }

        #schedule-grid th {
            background-color: #83a986; /* Header background color */
        }

        #schedule-grid .time-label {
            /*background-color: #b2b9bf;*/ /* Time label background color */
        }

        #schedule-grid .highlight {
            background-color: #b2b9bf; /* Highlighted cell background color */
        }

    </style>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <div href="" class="" style="color: #fff;font-family: Courier;">
                    CPSU COAS V.2.0
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
                                    @include('partials.control_sched_sidebar')
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
                                            <li class="breadcrumb-item active mt-1">Scheduler</li>
                                        </ol>
                                        <div class="workspace-top" style="text-align: center;">
                                            @if(request()->routeIs('scheduler-index'))
                                            <div class="row">
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #00bc8c !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $colCount }}</h3>
                                                                <p>Colleges</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-building"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #1b9173 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $enunprogCount }}</h3>
                                                                <p>Undergrad Programs</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-graduation-cap"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #467e70 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $engradprogCount }}</h3>
                                                                <p>Graduates Programs</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-graduation-cap"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-6">
                                                    <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #6f7473 !important">
                                                        <div class="text-left">
                                                            <div class="inner">
                                                                <h3>{{ $roomCount }}</h3>
                                                                <p>Rooms</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="icon">
                                                                <i class="fa fa-hotel"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
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
            <i class="text-light">CPSU - COAS V.2.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
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
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}"></script>
    <script src="{{ asset('js/basic/yearscript.js') }}"></script>
    <script src="{{ asset('js/basic/schoolyear.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <!-- Ajax -->
    @if(request()->routeIs('collegeRead'))
        <script src="{{ asset('js/ajax/schedclass/collegeSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('programsRead'))
        <script src="{{ asset('js/ajax/schedclass/programsSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('roomsRead'))
        <script src="{{ asset('js/ajax/schedclass/roomSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('courseEnroll_list_search'))
        <script src="{{ asset('js/ajax/schedclass/classEnrollSerialize.js') }}"></script>
    @endif
    @if(request()->routeIs('subjectsOffered_search'))
        <script src="{{ asset('js/ajax/schedclass/subjectOfferedSerialize.js') }}"></script>
    @endif

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('js/validation/schedule/classenrollValidation.js') }}"></script>
    <script src="{{ asset('js/validation/schedule/facdegValidation.js') }}"></script>
    <script src="{{ asset('js/validation/schedule/roomValidation.js') }}"></script>

    <script type="text/javascript">
        setTimeout(function () {
            $("#alert").delay(2500).fadeOut(5000);
        }, 0); 
    </script>

    <script>
        $(document).ready(function() {
            $('.btn-editclassenroll').click(function() {
                var id = $(this).data('id');
                var classVal = $(this).data('class');
                var classSection = $(this).data('class-section');
                var programID = $(this).data('program-code');

                $('#edit_id').val(id);
                $('#edit_class').val(classVal);
                $('#edit_class_section').val(classSection);
                $('#selectedProgramIdEdit').val(programID);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.btn-edit-facdesig').click(function() {
                var id = $(this).data('id');
                var facdept = $(this).data('facdept');
                var fac_id = $(this).data('fac_id');
                var designation = $(this).data('designation');
                var dunit = $(this).data('dunit');

                // Populate the modal form fields with the retrieved data
                $('#editFacDesigModal #edit_id').val(id);
                $('#editFacDesigModal #edit_facdept').val(facdept);
                $('#editFacDesigModal #edit_fac_id').val(fac_id);
                $('#editFacDesigModal #edit_designation').val(designation);
                $('#editFacDesigModal #edit_dunit').val(dunit);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize the grid with time slots and days
            let days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            let times = [
                '7:30-8:00', '8:00-8:30', '8:30-9:00', '9:00-9:30', '9:30-10:00', '10:00-10:30', '10:30-11:00', '11:00-11:30', '11:30-12:00',
                '12:00-12:30', '12:30-1:00', '1:00-1:30', '1:30-2:00', '2:00-2:30'
            ];

            let grid = '<table class="table table-bordered" style="height: 5px"><thead><tr><th style="background-color: #83a986; border: 1px solid #000; text-align: center">Time</th>';
            days.forEach(day => {
                grid += `<th class="text-center" style="background-color: #e9ecef; border: 1px solid #000"">${day}</th>`;
            });
            grid += '</tr></thead><tbody>';

            times.forEach(time => {
                grid += `<tr><td class="time-label text-left" width="10%" style="background-color: #e9ecef; border: 1px solid #000"">${time}</td>`;
                days.forEach(day => {
                    grid += `<td class="time-slot" style="border: 1px solid #8f8f8f"" data-day="${day}" data-time="${time}"></td>`;
                });
                grid += '</tr>';
            });

            grid += '</tbody></table>';
            $('#schedule-grid').html(grid);

            let isDragging = false;
            let startDay, startTime, endDay, endTime;

            $('.time-slot').mousedown(function() {
                isDragging = true;
                clearHighlights();
                $(this).addClass('highlight');
                startDay = $(this).data('day');
                startTime = $(this).data('time');
                endDay = startDay;
                endTime = startTime;
            });

            $('.time-slot').mousemove(function() {
                if (isDragging) {
                    let currentDay = $(this).data('day');
                    let currentTime = $(this).data('time');
                    highlightCells(startDay, startTime, currentDay, currentTime);
                    endDay = currentDay;
                    endTime = currentTime;
                }
            });

            $(document).mouseup(function() {
                if (isDragging) {
                    isDragging = false;
                    $('#day').val(startDay);
                    $('#start_time').val(startTime);
                    $('#end_time').val(endTime);
                    
                    // Display the selected time range and day in the modal
                    $('#selected-time-range').html(`Selected Time: ${startTime} - ${endTime}<br>Day: ${startDay}`);

                    $('#scheduleModal').modal('show');
                }
            });

            function highlightCells(startDay, startTime, endDay, endTime) {
                clearHighlights();
                let dayIndexStart = days.indexOf(startDay);
                let dayIndexEnd = days.indexOf(endDay);
                let timeIndexStart = times.indexOf(startTime);
                let timeIndexEnd = times.indexOf(endTime);

                for (let i = Math.min(dayIndexStart, dayIndexEnd); i <= Math.max(dayIndexStart, dayIndexEnd); i++) {
                    for (let j = Math.min(timeIndexStart, timeIndexEnd); j <= Math.max(timeIndexStart, timeIndexEnd); j++) {
                        $(`.time-slot[data-day="${days[i]}"][data-time="${times[j]}"]`).addClass('highlight');
                    }
                }
            }

            function clearHighlights() {
                $('.time-slot').removeClass('highlight');
            }

            $('.time-slot').click(function() {
                clearHighlights();
                startDay = $(this).data('day');
                startTime = $(this).data('time');
                endDay = startDay;
                endTime = startTime;
                highlightCells(startDay, startTime, endDay, endTime);
                $('#day').val(startDay);
                $('#start_time').val(startTime);
                $('#end_time').val(endTime);
                
                // Display the selected time range and day in the modal
                $('#selected-time-range').html(`Selected Time: ${startTime} - ${endTime}<br>Day: ${startDay}`);

                $('#scheduleModal').modal('show');
            });

            $('#saveSchedule').click(function() {
                // Save the schedule via AJAX
                let formData = $('#scheduleForm').serialize();
                $.ajax({
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Schedule saved successfully.');
                        $('#scheduleModal').modal('hide');
                        clearHighlights();
                    },
                    error: function(response) {
                        alert('Error saving schedule: ' + response.responseJSON.error);
                    }
                });
            });
        });
    </script>


</body>
</html>
   