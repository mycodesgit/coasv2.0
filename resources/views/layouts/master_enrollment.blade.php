<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>

    <link rel="shortcut icon" sizes="180x180" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uilibs/images/cpsulogov4.png') }}">

    <link rel="stylesheet" href="{{ asset('uilibs/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/css/custom.css') }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fullcalendar/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/sched-style.css') }}">
    
    <style>
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
        
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top px-3" style="background-color: #04401f !important; z-index: 9995">

        <div id="s" class="text-light">
            CISS v.1.0 
        </div>

        <div class="d-md-none">
            <div class="d-flex align-items-center gap-3">
            </div>
        </div>

        <div>
            <!-- Navbar nav -->
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
                <!-- MOBILE -->
                <button id="mobileBtn" class="btn btn-outline-light btn-icon btn-sm d-lg-none me-2">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Dropdown -->
                <li class="ms-3 dropdown d-none d-md-block">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="text-light">
                        <img src="{{ asset('uilibs/images/usergreen.png') }}" alt="" class="avatar avatar-sm rounded-circle" />
                        @auth('web')
                            @if(in_array(Auth::guard('web')->user()->role, range(0, 21)))
                                Logged as: {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }} - 
                                @if (Auth::guard('web')->user()->campus == 'MC') Main 
                                    @elseif (Auth::guard('web')->user()->campus == 'VC') Victorias 
                                    @elseif (Auth::guard('web')->user()->campus == 'SCC') San Carlos 
                                    @elseif (Auth::guard('web')->user()->campus == 'HC') Hinigaran 
                                    @elseif (Auth::guard('web')->user()->campus == 'MP') Moises Padilla 
                                    @elseif (Auth::guard('web')->user()->campus == 'IC') Ilog 
                                    @elseif (Auth::guard('web')->user()->campus == 'CA') Candoni 
                                    @elseif (Auth::guard('web')->user()->campus == 'CC') Cauayan 
                                    @elseif (Auth::guard('web')->user()->campus == 'SC') Sipalay  
                                    @elseif (Auth::guard('web')->user()->campus == 'HinC') Hinobaan 
                                @endif
                            @endif
                        @endauth
                    </a>
                </li>
                <li class="ms-3 dropdown d-md-none">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="text-light">
                        <img src="{{ asset('uilibs/images/usergreen.png') }}" alt="" class="avatar avatar-sm rounded-circle" />
                        @auth('web')
                            @if(in_array(Auth::guard('web')->user()->role, range(0, 21)))
                                @if (Auth::guard('web')->user()->campus == 'MC') Main 
                                    @elseif (Auth::guard('web')->user()->campus == 'VC') Victorias 
                                    @elseif (Auth::guard('web')->user()->campus == 'SCC') San Carlos 
                                    @elseif (Auth::guard('web')->user()->campus == 'HC') Hinigaran 
                                    @elseif (Auth::guard('web')->user()->campus == 'MP') Moises Padilla 
                                    @elseif (Auth::guard('web')->user()->campus == 'IC') Ilog 
                                    @elseif (Auth::guard('web')->user()->campus == 'CA') Candoni 
                                    @elseif (Auth::guard('web')->user()->campus == 'CC') Cauayan 
                                    @elseif (Auth::guard('web')->user()->campus == 'SC') Sipalay  
                                    @elseif (Auth::guard('web')->user()->campus == 'HinC') Hinobaan 
                                @endif
                            @endif
                        @endauth
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar overflow-y-auto overflow-x-hidden" style="height: 95vh">
        <div class="logo-area border-0">
            <div class="d-inline-flex">
                <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="24">
                <span class="logo-text ms-2" style="font-weight: bold">CISS</span>
            </div>
        </div>
        @include('partials.control_en_sidebar')

    </aside>

    <!-- MAINmainCONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div style="z-index: 9999">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
                </div>
            </div>
            @section('workspace')
            @show

            <div class="row d-none d-md-block">
                <div class="col-12">
                    <footer class="text-center py-2 mt-6 text-secondary fixed-bottom bg-white" style="z-index: 99">
                        <p class="mb-0">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</p>
                    </footer>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->

    <script type="text/javascript" src="{{ asset('uilibs/js/main.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('uilibs/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('uilibs/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/fullcalendar/fullcalendar.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('uilibs/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('uilibs/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('uilibs/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Validation JS -->
    <script src="{{ asset('uilibs/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/basic/yearscript.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/basic/schoolyear.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/basic/subjects.js') }}?v={{ time() }}"></script>

    <script src="{{ asset('js/validation/enroll/addStudValidation.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/validation/enroll/enrollValidation.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/validation/enroll/gradesheetValidation.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/validation/enroll/subjectValidation.js') }}?v={{ time() }}"></script>

    @if(Auth::guard('web')->check() && in_array(Auth::guard('web')->user()->role, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10, 11, 12, 13, 14, 16, 17, 18, 19, 20]))
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
            <script src="{{ asset('js/chart/enbarchart.js') }}?v={{ time() }}"></script>
            <script src="{{ asset('js/chart/enbarchartperyearlev.js') }}?v={{ time() }}"></script>
            <script>
                $(document).ready(function() {
                    $('#regularModal').on('show.bs.modal', function () {
                        let iframe = $('#regularPdfFrame');
                        let loading = $('#loadingText');
                        
                        // Show loading message
                        loading.show();
                        iframe.hide();

                        // Load PDF only when modal opens
                        iframe.attr('src', "{{ route('regular.students.pdf') }}");

                        // Once iframe is loaded, hide loading message
                        iframe.on('load', function() {
                            loading.hide();
                            iframe.show();
                        });
                    });

                    // Optional: clear iframe when modal closes to free memory
                    $('#regularModal').on('hidden.bs.modal', function () {
                        $('#regularPdfFrame').attr('src', '');
                    });
                });
            </script>
            <script>
                $(document).ready(function() {
                    $('#irregularModal').on('show.bs.modal', function () {
                        let iframe = $('#irregularPdfFrame');
                        let loading = $('#loadingText');
                        
                        // Show loading message
                        loading.show();
                        iframe.hide();

                        // Load PDF only when modal opens
                        iframe.attr('src', "{{ route('irregular.students.pdf') }}");

                        // Once iframe is loaded, hide loading message
                        iframe.on('load', function() {
                            loading.hide();
                            iframe.show();
                        });
                    });

                    // Optional: clear iframe when modal closes to free memory
                    $('#irregularModal').on('hidden.bs.modal', function () {
                        $('#irregularPdfFrame').attr('src', '');
                    });
                });
            </script>
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
            <script src="{{ asset('js/chart/gradenbarchart.js') }}?v={{ time() }}"></script>
        @endif
    @endif

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

    <script>
        $(document).ready(function () {
            $('#searchDropdown').select2({
                placeholder: '-- Search and Select --',
                allowClear: true,
                minimumInputLength: 6
            });
        });
    </script>

    <!-- Ajax -->
    @if(request()->routeIs('subjectsRead'))
        <script src="{{ asset('js/ajax/enrolment/subjectSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studentCreate'))
        <script src="{{ asset('js/ajax/enrolment/studentAddSerialize.js') }}?v={{ time() }}"></script>
        @include('enrollment.students.addressesScript')
    @endif
    @if(request()->routeIs('searchStudEnroll', 'loadstudsub_searchview'))
        <script src="{{ asset('js/ajax/enrolment/enrollmentSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('loadstudsub'))
        @include('script.enrllmnt.preenrolEvalSerialize')
    @endif
    @if(request()->routeIs('searchStud', 'editsearchStud'))
        @include('script.enrllmnt.studqueueSerialize')
    @endif
    @if(request()->routeIs('searchStud', 'editsearchStud'))
        @include('script.queuetransacScript')
    @endif
    @if(request()->routeIs('editsearchStudRead', 'editcrosstudsearchRead'))
        <script src="{{ asset('js/ajax/enrolment/editEnrollmentSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('loadstudsubpreenrol_searchview'))
        @include('script.enrllmnt.evalEnrolmentSerialize')
        @include('script.enrllmnt.chatmessageSerialize')
    @endif
    @if(request()->routeIs('dupapprslSearch_listresult'))
        <script src="{{ asset('js/ajax/enrolment/editDupAppEnrollmentSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('viewsearchenStudHistory'))
        <script src="{{ asset('js/ajax/enrolment/studenHistorySerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studCurrsearch'))
        <script src="{{ asset('js/ajax/enrolment/enrollmentCourseSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studAttendCurrsearch'))
        <script src="{{ asset('js/ajax/enrolment/enrollmentAttendCourseSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studgrade_searchlist', 'studgradegrad_searchlist', 'studgradecorrection_resultsearch'))
        <script src="{{ asset('js/ajax/enrolment/gradesheetSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('list_trans'))
        <script src="{{ asset('js/ajax/enrolment/transferSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('listsearch_studsubjectsRead', 'gradschoolgetlistsearch_studsubjectsRead'))
        <script src="{{ asset('js/ajax/enrolment/studenrollAttendanceSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('geneStudent1', 'geneStudentcorrectiongrades'))
        <script src="{{ asset('js/ajax/enrolment/passwordGrade.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('elpl_listsearch'))
        <script src="{{ asset('js/ajax/enrolment/elplSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studInfo_search'))
        <script src="{{ asset('js/ajax/enrolment/studentinfoSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studInfograduated_search'))
        <script src="{{ asset('js/ajax/enrolment/studentinfograduatedSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('elpl_list', 'elpl_listsearch','ranking_list'))
        <script src="{{ asset('js/ajax/enrolment/getcourseSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studnoenrollee'))
        <script src="{{ asset('js/ajax/enrolment/enrolleescountSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('studnoNSTPenrollee'))
        <script src="{{ asset('js/ajax/enrolment/enrolleesnstpcountSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('logbook_search'))
        <script src="{{ asset('js/ajax/enrolment/gradesheetLogbookSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('search_uptadeEnrlmntlogsRead'))
        <script src="{{ asset('js/ajax/enrolment/updatedEnrollmentLogsSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('searchEncode_gradeRead'))
        <script src="{{ asset('js/ajax/enrolment/gradesheetLogsSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('search_delenrlmntlogsRead'))
        <script src="{{ asset('js/ajax/enrolment/deletedEnrollmentLogsSerialize.js') }}?v={{ time() }}"></script>
    @endif
    @if(request()->routeIs('search_studenrollRead'))
        <script src="{{ asset('js/ajax/enrolment/studenrollSerialize.js') }}?v={{ time() }}"></script>
    @endif

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

    <script>
        const sidebar = document.getElementById("sidebar");
        const activeItem = sidebar.querySelector(".active");

        if (activeItem) {
            const offset = activeItem.offsetTop - sidebar.clientHeight / 2;
            sidebar.scrollTo({
                top: offset,
                behavior: "smooth"
            });
        }
    </script>
</body>

</html>