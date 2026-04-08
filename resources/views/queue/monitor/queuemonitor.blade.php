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
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    
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
        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
                color: #007bff
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
    </nav>

    <!-- MAINmainCONTENT -->
    <main id="content" class="py-10">
        <div class="container-fluid">
            <div class="row">
                <div style="z-index: 9999">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <table id="queueMonitor" class="table table-hover">
                        <thead style="font-weight: bold; font-size: 50px; text-align: center;">
                            <tr>
                                <th width="10%">Window</th>
                                <th>Number</th>
                            </tr>
                        </thead>
                        <tbody style="font-weight: bold; font-size: 45px; text-align: center;">

                        </tbody>
                    </table>
                </div>
                <div class="col-lg-7">
                    <center style="background-color: #93cda0; border-radius: 25px; margin-top: -50px;">
                        <span id="number-displaycurr" style="font-weight: bold; font-size: 190px;">
                            <p id="queue-numbercurr" style="margin-top: 100px;"></p>
                            <p id="window-numbercurr" style="font-weight: bold; font-size: 40px; margin-top: -50px;" class="text-danger">Current Window</p>
                        </span>
                    </center>
                    <center style="background-color: #ffe28c; border-radius: 25px; margin-top: -50px;">
                        <span id="number-displaycall" style="font-weight: bold; font-size: 190px;">
                            <p id="queue-number" style="margin-top: 100px; animation: blink 2s infinite;"></p>
                            <p id="window-number" style="font-weight: bold; font-size: 40px; margin-top: -50px;" class="text-danger">Current Window</p>
                        </span>
                    </center>
                    <div class="mt-3 text-center">
                        <div class="clock" id="time">--:--:-- --</div>
                        <div class="date" id="date">Loading date...</div>
                    </div>
                    {{-- <iframe width="100%" height="560" src="https://images.app.goo.gl/8mjuVP4YWazjrqny8" frameborder="0" referrerpolicy="strict-origin-when-cross-origin"></iframe> --}}
                    {{-- <img src="{{ asset('template/img/queueimg.jpg') }}" width="100%" height="560"> --}}
                </div>
            </div>

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
    <!-- Validation JS -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll('.card-animate');

            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show');
                }, index * 100); // stagger effect
            });
        });
    </script>

    <!-- Ajax -->
    @if(request()->routeIs('queue-monitor'))
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

                $('#initInteraction').on('click', function () {
                    $('#interactionModal').fadeOut();
                    sound.play(); 
                    console.log('User interaction completed. Audio is ready.');

                    // Initialize DataTable
                    var dataTable = $('#queueMonitor').DataTable({
                        "columnDefs": [{ "orderable": false, "targets": [0, 1] }],
                        destroy: true,
                        info: false,
                        responsive: false,
                        lengthChange: false,
                        searching: false,
                        paging: false,
                        data: [],
                        "columns": [{ data: 'window' }, { data: 'number' }]
                    });

                    var previousData = [];

                    // Use a function to fetch the data periodically
                    function fetchQueueData() {
                        $.ajax({
                            url: "{{ route('queue.stream') }}", // Endpoint to fetch data
                            method: 'GET',
                            success: function (response) {
                                var hasChanges = JSON.stringify(previousData) !== JSON.stringify(response.data);
                                
                                // Clear and redraw DataTable with new data
                                dataTable.clear();
                                dataTable.rows.add(response.data).draw();

                                // Play sound and speech for each window's data if it has changed
                                response.data.forEach(function(queueData, index) {
                                    // Check if the data for this window has changed
                                    if (previousData[index]?.number !== queueData.number || previousData[index]?.window !== queueData.window) {
                                        // Play sound
                                        sound.play().catch(e => console.warn('Audio playback issue:', e));

                                        // Create the speech message
                                        const queueNumber = queueData.number || 'No queue number';
                                        const windowNumber = queueData.window || 'N/A';
                                        const message = `Queue number ${queueNumber}. Please proceed to window ${windowNumber}.`;

                                        // Create speech synthesis instance
                                        const speech = new SpeechSynthesisUtterance(message);
                                        speech.lang = 'en-US'; // Set language to English
                                        speech.volume = 1; // Full volume
                                        speech.rate = 1; // Normal speaking rate
                                        speech.pitch = 1; // Normal pitch

                                        // Speak the message
                                        window.speechSynthesis.speak(speech);
                                    }
                                });

                                previousData = response.data;
                            },
                            error: function () {
                                console.error("Error fetching queue data.");
                            }
                        });
                    }

                    // Call the function initially and then periodically (every 2 seconds)
                    fetchQueueData();
                    setInterval(fetchQueueData, 2000); // Update every 2 seconds
                });
            });
        </script>

        <script>
            let lastQueueNumbercurr = null;  // Store the last spoken queue number
            let lastWindowNumbercurr = null; // Store the last spoken window number

            function fetchQueueStatus() {
                $.ajax({
                    url: "{{ route('queue.stream.current') }}",
                    method: "GET",
                    success: function(data) {
                        if (data) {
                            // Update the displayed queue and window numbers
                            $('#queue-numbercurr').text(data.number || ' ');
                            $('#window-numbercurr').text('Current Serving proceed Window' + (data.window || 'N/A'));

                            // Check if the queue or window number has changed
                            const queueNumber = data.number || 'No queue number';
                            const windowNumber = data.window || 'N/A';

                            // Trigger speech and sound if there's a change
                            if (queueNumber !== lastQueueNumbercurr || windowNumber !== lastWindowNumbercurr) {
                                lastQueueNumbercurr = queueNumber;
                                lastWindowNumbercurr = windowNumber;
                            }

                        } else {
                            $('#queue-numbercurr').text('');
                            $('#window-numbercurr').text('');
                        }
                    },
                    error: function() {
                        console.error('Failed to fetch queue status.');
                    }
                });
            }

            // Call the function every 2 seconds
            setInterval(fetchQueueStatus, 2000);

            // Fetch initially on page load
            fetchQueueStatus();
        </script>

        <script>
            let lastQueueNumbercall = null;  // Store the last spoken queue number
            let lastWindowNumbercall = null; // Store the last spoken window number

            function fetchQueueStatus() {
                $.ajax({
                    url: "{{ route('queue.stream.call') }}",
                    method: "GET",
                    success: function(data) {
                        if (data) {
                            // Update the displayed queue and window numbers
                            $('#queue-number').text(data.number || ' ');
                            $('#window-number').text('Calling to Window ' + (data.window || 'N/A'));

                            // Check if the queue or window number has changed
                            const queueNumber = data.number || 'No queue number';
                            const windowNumber = data.window || 'N/A';

                            // Trigger speech and sound if there's a change
                            if (queueNumber !== lastQueueNumbercall || windowNumber !== lastWindowNumbercall) {
                                // Play sound
                                const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
                                sound.play().catch(e => console.warn('Audio playback issue:', e));

                                // Create the speech message
                                const message = `Queue number ${queueNumber}. Please proceed to window ${windowNumber}.`;

                                // Create speech synthesis instance
                                const speech = new SpeechSynthesisUtterance(message);
                                speech.lang = 'en-US'; // Set language to English
                                speech.volume = 1; // Full volume
                                speech.rate = 1; // Normal speaking rate
                                speech.pitch = 1; // Normal pitch

                                // Speak the message
                                window.speechSynthesis.speak(speech);

                                // Update the last spoken queue and window number
                                lastQueueNumbercall = queueNumber;
                                lastWindowNumbercall = windowNumber;
                            }

                        } else {
                            $('#queue-number').text('');
                            $('#window-number').text('');
                        }
                    },
                    error: function() {
                        console.error('Failed to fetch queue status.');
                    }
                });
            }

            // Call the function every 2 seconds
            setInterval(fetchQueueStatus, 2000);

            // Fetch initially on page load
            fetchQueueStatus();
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