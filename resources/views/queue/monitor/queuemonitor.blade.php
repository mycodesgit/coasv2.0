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
        #tblegend td {
            border: 1px solid #e9ecef;
            padding: 5px;
            font-size: 10pt;
        } 
        #tblegend th {
            border: 1px solid #e9ecef;
            padding: 5px;
        }
        .clock {
            font-size: 50px;
            font-weight: bold;
            color: teal;
        }
        .date {
            font-size: 20px;
            color: gray;
        }
        #queueMonitor th.sorting::after,
        #queueMonitor th.sorting_asc::after,
        #queueMonitor th.sorting_desc::after {
            display: none !important;
        }
        #queueMonitor th.sorting::before,
        #queueMonitor th.sorting_asc::before,
        #queueMonitor th.sorting_desc::before {
            display: none !important;
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
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:80px;" class="center-top">
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <table id="queueMonitor" class="table table-hover table-bordered">
                                <thead style="font-weight: bold; font-size: 50px; text-align: center;">
                                    <tr>
                                        <th>Window</th>
                                        <th>Number</th>
                                    </tr>
                                </thead>
                                <tbody style="font-weight: bold; font-size: 45px; text-align: center;">
                                    {{-- @foreach($countersArray as $datacounter)
                                        <tr>
                                            <td>{{ $datacounter['window'] }}</td>
                                            <td>{{ $datacounter['number'] ?? '' }}</td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <center>
                                <span id="number-display" style="font-weight: bold; font-size: 100px;"></span>
                            </center>
                            {{-- <iframe width="100%" height="560" src="https://images.app.goo.gl/8mjuVP4YWazjrqny8" frameborder="0" referrerpolicy="strict-origin-when-cross-origin"></iframe> --}}
                            {{-- <img src="{{ asset('template/img/queueimg.jpg') }}" width="100%" height="560"> --}}
                        </div>
                        <div class="col-md-12">
                            <div class="mt-2 text-center">
                                <div class="clock" id="time">--:--:-- --</div>
                                <div class="date" id="date">Loading date...</div>
                            </div>
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
    <!-- Basic -->
    <script src="{{ asset('js/basic/tablescript.js') }}"></script>
    <script src="{{ asset('js/basic/yearscript.js') }}"></script>
    <script src="{{ asset('js/basic/schoolyear.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    @if(request()->routeIs('queue-monitor'))
        <!-- Modal for initial interaction -->
        <div id="interactionModal" style="
            position: fixed; 
            top: 0; left: 0; width: 100%; height: 100%; 
            background-color: rgba(0, 0, 0, 0.7);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;">
            <button id="initInteraction" style="
                padding: 10px 20px; 
                font-size: 18px; 
                background-color: #28a745; 
                color: white; border: none; 
                border-radius: 5px; cursor: pointer;">
                Start Queueing
            </button>
        </div>

        <script>
            $(document).ready(function () {
                var sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");

                // Require user interaction
                $('#initInteraction').on('click', function () {
                    $('#interactionModal').fadeOut(); // Hide the modal
                    sound.play(); // Play sound to "unlock" audio
                    console.log('User interaction completed. Audio is ready.');

                    // Connect to SSE stream
                    var dataTable = $('#queueMonitor').DataTable({
                        "columnDefs": [{ "orderable": false, "targets": [0, 1] }],
                        destroy: true, info: false, responsive: false,
                        lengthChange: false, searching: false, paging: false,
                        data: [],
                        "columns": [{ data: 'window' }, { data: 'number' }]
                    });

                    var previousData = [];

                    var eventSource = new EventSource("{{ route('queue.stream') }}");
                    eventSource.onmessage = function (event) {
                        var response = JSON.parse(event.data);
                        var hasChanges = JSON.stringify(previousData) !== JSON.stringify(response.data);

                        dataTable.clear();
                        dataTable.rows.add(response.data).draw();

                        if (hasChanges) {
                            sound.play().catch(e => console.warn('Audio playback issue:', e));
                        }
                        previousData = response.data;
                    };

                    eventSource.onerror = function () {
                        console.error("EventSource error occurred.");
                        eventSource.close();
                    };
                });
            });
        </script>
        @endif












    <script>
        function updateTime() {
            const now = new Date();

            // Format time
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            const formattedTime = [
                hours % 12 || 12, // Convert 24-hour format to 12-hour format
                minutes.toString().padStart(2, '0'),
                seconds.toString().padStart(2, '0')
            ].join(':') + ` ${ampm}`;

            // Format date
            const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            const formattedDate = `${days[now.getDay()]}, ${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;

            // Set content
            document.getElementById('time').textContent = formattedTime;
            document.getElementById('date').textContent = formattedDate;
        }

        setInterval(updateTime, 1000); // Update every second
        updateTime(); // Initialize immediately
    </script>
</body>
</html>
   