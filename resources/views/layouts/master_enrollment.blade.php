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
        #liveSearchResultsContainer {
            max-height: 200px; /* Adjust the max height as needed */
            overflow-y: auto;
        }

        .live-search-result {
            cursor: pointer;
            padding: 8px;
            border-bottom: 1px solid #eee; /* Add border between results for better separation */
        }

        .live-search-result:hover {
            background-color: #f0f0f0; /* Add a subtle background color on hover */
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
                                @if(in_array(Auth::guard('web')->user()->isAdmin, [0, 1, 2, 3, 4, 5]))
                                    Logged as: {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}
                                @endif
                            @endauth

                            @auth('faculty')
                                @if(Auth::guard('faculty')->user()->isAdmin == '8')
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
                                            <h1 class="fas fa-mug-hot fa-7x" style="color: #04401f"></h1>
                                            <h1><span style="color:#ffff66;font-size: 70px;">Eey!</span> Grab a coffee before doing something.</h1>
                                            <p>  <i class="fas fa-quote-left fa-2x fa-pull-left"></i>
                                                Gatsby believed in the green light, the orgastic future that year by year recedes before us.
                                                It eluded us then, but that’s no matter — tomorrow we will run faster, stretch our arms further...
                                                And one fine morning — So we beat on, boats against the current, borne back ceaselessly into the past.
                                            </p>
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
    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}"></script>
    <script src="{{ asset('js/basic/yearscript.js') }}"></script>
    <script src="{{ asset('js/basic/schoolyear.js') }}"></script>
    <script src="{{ asset('js/basic/subjects.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('js/validation/enroll/enrollValidation.js') }}"></script>
    <script src="{{ asset('js/validation/enroll/gradesheetValidation.js') }}"></script>

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

    @include('script.gradeScript')

    <script type="text/javascript">
        function updateGrade(id, grade){
            alert(id);
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

    <script>
        $(document).ready(function () {
            $('#submitBtn').click(function () {
                $('#confirmationForm').submit();
            });
        });
    </script>


    <script>
    $(document).ready(function() {
    // Function to perform the live search
    function performLiveSearch() {
        var searchValue = $('#liveSearchInput').val();
        var studentType = $('#studentType').val();

        $.ajax({
            url: '{{ route("liveSearchStudent") }}',
            type: 'GET',
            data: { search: searchValue, en_status: studentType },
            success: function(response) {
                displayLiveSearchResults(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // Initial setup
    $('#liveSearchInput').on('input', function() {
        performLiveSearch();
    });

    // Trigger live search when changing the student type
    $('#studentType').on('change', function() {
        // Clear the selected student info
        $('#selectedStudentInfo').val('');

        // Clear and hide the live search results container
        var resultsContainer = $('#liveSearchResultsContainer');
        resultsContainer.empty().hide();

        // Trigger live search
        performLiveSearch();
    });

    // Function to display live search results
    function displayLiveSearchResults(students) {
        var selectedStudentDropdown = $('#selectedStudentInfo');
        selectedStudentDropdown.empty().append('<option></option>');

        var resultsContainer = $('#liveSearchResultsContainer');
        resultsContainer.empty();

        if (students.length > 0) {
            $.each(students, function(index, student) {
                resultsContainer.append('<div class="live-search-result" data-student-id="' + student.id + '">' + student.stud_id + ' - ' + student.lname + ' ' + student.mname + '. ' + student.fname + '</div>');
                selectedStudentDropdown.append('<option value="' + student.id + '">' + student.stud_id + ' - ' + student.lname + ' ' + student.mname + '. ' + student.fname + '</option>');
            });

            // Show the live search results container
            resultsContainer.show();
        } else {
            resultsContainer.html('<p>No results found</p>');
        }
    }

    // Handle click on live search result
    $(document).on('click', '.live-search-result', function() {
        var selectedStudentId = $(this).data('student-id');
        var selectedStudentInfo = $(this).text();

        $('#selectedStudentInfo').val(selectedStudentId);

        // Clear and hide the live search results container
        var resultsContainer = $('#liveSearchResultsContainer');
        resultsContainer.empty().hide();

        // Clear the search input
        $('#liveSearchInput').val('');

        console.log(selectedStudentId);
    });
});

</script>
    
</body>
</html>
   