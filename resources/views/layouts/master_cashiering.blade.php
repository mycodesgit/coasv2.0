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
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fullcalendar/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/sched-style.css') }}">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
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
        .btn-block {
            display: block;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top px-3" style="z-index: 9995">

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
                <li>
                    <button id="themeToggleBtn" class="btn btn-light btn-sm rounded-circle" title="Toggle theme">
                        <i id="themeIcon" class="ti ti-sun"></i>
                    </button>
                </li>
                <!-- Dropdown -->
                <li class="ms-3 dropdown d-none d-md-block">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="text-light">
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
        @include('partials.control_cash_sidebar')
    </aside>

    <!-- MAINmainCONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div style="z-index: 9999">
                    <img src="{{ asset('uilibs/images/cpsulogov4.webp') }}" style="width:70px;" class="center-top">
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
    <!-- date-range-picker -->
    <script src="{{ asset('uilibs/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Validation JS -->
    <script src="{{ asset('uilibs/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/basic/yearscript.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/basic/schoolyear.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <script src="{{ asset('js/basic/themejs.js') }}"></script>

    <script src="{{ asset('js/validation/cash/perDayValidation.js') }}"></script>

    <script>
        $(function () {
            //Date range picker
            $('#reservation').daterangepicker({
                opens: 'right',
                autoApply: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        })
    </script>

    <!-- Ajax -->
    @if(request()->routeIs('cashiering-index'))
    
    @endif
    
    @if(request()->routeIs('list_orRead'))
        <script>
            $(document).ready(function() {
                $('input[name="r3"]').on('change', function() {
                    if ($(this).val() === 'off') {
                        $('input[name="stud_id"]').val('No').prop('readonly', true);
                    } else {
                        $('input[name="stud_id"]').val('').prop('readonly', false);
                    }
                });
            });
        </script>
    @endif
    @if(request()->routeIs('listsearch_orRead', 'listsearchedit_orRead'))
        <script src="{{ asset('js/ajax/cash/orSerialize.js') }}"></script>
        <script>
            $(document).ready(function () {

                $('#account_select').on('change', function () {
                    if ($(this).val() === 'custom') {
                        $('#account_custom').show().prop('required', true);
                    } else {
                        $('#account_custom').hide().prop('required', false).val('');
                    }
                });
                $('#account_custom').on('input', function () {
                    $(this).val($(this).val().toUpperCase());
                });
            });
            
            $(document).ready(function() {
                $('#myname').on('input', function() {
                    let value = $(this).val();
                    $('#myname_display').val(value);
                });
            });
        </script>
    @endif
    @if(request()->routeIs('listall_orRead'))
        <script src="{{ asset('js/ajax/cash/orallSerialize.js') }}"></script>
    @endif

    @if(request()->routeIs('listsearchedit_orRead'))
    <script>
        $(document).on('click', '.studorspec-delete', function(e) {
        e.preventDefault(); // Prevent default behavior

        var orno = $(this).data('orno');
        var schlyear = $(this).data('schlyear');
        var semester = $(this).data('semester');

        var deleteUrl = `{{ route('deletePayment') }}?orno=${orno}&schlyear=${schlyear}&semester=${semester}`;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to recover this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: deleteUrl,
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Successfully Deleted!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $(document).trigger('studOrAdded');
                        if (response.success) {
                            toastr.success(response.message);
                            console.log(response);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
    </script>
    @endif

    @if(request()->routeIs('listsearch_orpermonthRead'))
        <script src="{{ asset('js/ajax/cash/orMonthSerialize.js') }}"></script>
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
</body>

</html>