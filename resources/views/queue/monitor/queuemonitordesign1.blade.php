<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'CPSU Queueing System')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uilibs/images/cpsulogov4.png') }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('uilibs/css/main.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('uilibs/css/custom.css') }}?v={{ time() }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
            background-color: #f8fafc;
            height: 100vh;
            overflow: hidden;
        }

        #content {
            height: calc(100vh - 100px);
            margin-top: 60px;
        }

        /* Text sizes for left side display */
        .display-serving-number {
            font-size: 6.5rem;
            font-weight: 800;
            line-height: 1;
        }

        .display-calling-number {
            font-size: 6.5rem;
            font-weight: 800;
            line-height: 1;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-now-serving { background-color: #d1fae5; color: #065f46; }
        .badge-calling-next { background-color: #fef3c7; color: #92400e; }
        .badge-next { background-color: #dbeafe; color: #1e40af; }
        .badge-waiting { background-color: #f1f5f9; color: #475569; }
        .badge-close { background-color: #e2655c; color: #f1f5f9; }
        .badge-hold { background-color: #475569; color: #f1f5f9; }
        .badge-open { background-color: #d1fae5; color: #065f46; }

        /* Container for 2-column grid listing on right */
        .counter-grid-list {
            max-height: calc(100vh - 180px);
            overflow-y: auto;
            padding-right: 6px;
        }

        /* Next in Line section styling */
        .next-queue-pill {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
        }

        /* Bottom Ticker Styling */
        .ticker-bar {
            background-color: #f5f5f5;
            color: #000000;
            font-size: 0.99rem;
            font-weight: 800;
            padding: 6px 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .ticker-text {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 25s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>

    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar full px-3 justify-content-between">
        <div class="d-flex align-items-center">
            <span class="fw-bold fs-5 text-dark">CPSU Queueing System</span>
        </div>
        <div class="d-flex align-items-center gap-4">
            <!-- Connection Indicator -->
            <div class="d-flex align-items-center gap-2 bg-light px-3 py-1 rounded-pill border">
                <span class="p-1 rounded-circle bg-success d-inline-block" style="width: 10px; height: 10px;"></span>
                <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.5px;">Live Sync</span>
            </div>

            <!-- Date & Time Display -->
            <div class="text-end border-start ps-3">
                <div id="time" class="fw-bold text-dark leading-tight" style="font-size: 1.1rem;">--:-- --</div>
                <div id="date" class="text-muted" style="font-size: 0.75rem;">Loading date...</div>
            </div>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar collapsed">
        <div class="logo-area">
            <div class="d-inline-flex">
                <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="24">
                <span class="logo-text ms-2" style="font-weight: bold"></span>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main id="content" class="content py-2 full">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-stretch">

                <!-- LEFT COLUMN: CALLOUTS & UPCOMING QUEUE (6 Cols) -->
                <div class="col-md-6 border-end pe-4 d-flex flex-column justify-content-between py-2">
                    &nbsp;
                    <!-- Top: NOW CALLING -->
                    <div class="text-center">
                        <span id="window-number" class="badge bg-warning-subtle text-dark mb-2 px-4 py-2 fs-3 fw-bold">Proceed to Counter --</span>
                        <h2 id="queue-number" class="display-calling-number my-1 text-dark">-</h2>
                        <p class="text-muted fs-6 mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">NOW CALLING TICKET</p>
                    </div>

                    <hr class="my-2">

                    <!-- Middle: NOW SERVING -->
                    <div class="text-center">
                        <span id="window-numbercurr" class="badge bg-success-subtle rounded-3 text-dark mb-2 px-4 py-2 fs-3 fw-bold">Proceed to Counter --</span>
                        <h2 id="queue-numbercurr" class="display-serving-number my-1 text-success">-</h2>
                        <p class="text-muted fs-6 mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">NOW SERVING TICKET</p>
                    </div>

                    <hr class="my-2">

                    <!-- Bottom: NEXT IN LINE PREVIEW -->
                    <div class="bg-light p-3 rounded-4 border">
                        <div class="fw-bold text-muted small text-uppercase mb-2"><i class="fas fa-users me-1"></i> Upcoming / Next In Line</div>
                        <div id="nextInLineContainer" class="d-flex gap-2 flex-wrap">
                            <span class="next-queue-pill">---</span>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: 2-COLUMN GRID OF COUNTERS (6 Cols) -->
                <div class="col-md-6 ps-4 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold text-muted text-uppercase small"><i class="ti ti-layout-grid me-1"></i> All Counters Overview</span>
                    </div>

                    <div class="counter-grid-list flex-fill">
                        <!-- 2-Column Grid Wrapper -->
                        <div id="counterCardsGrid" class="row row-cols-2 g-2">
                            <!-- Default Card Template using your card layout -->
                            <div class="col">
                                <div class="card bg-light shadow-sm rounded-4 h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                                        <div>
                                            <i class="ti ti-device-laptop counter-icon fs-4 text-success me-1"></i>
                                            <span class="counter-title fs-5 font-weight-bold text-muted">COUNTER 1</span>
                                            <div class="mt-1">
                                                <span class="status-badge badge-waiting">WAITING</span>
                                            </div>
                                        </div>
                                        <div class="counter-token text-dark" style="font-weight: bolder !important; font-size: 24pt; font-family: 'Poppins', sans-serif !important;">---</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- TICKER & FOOTER CONTAINER -->
    <div class="fixed-bottom bg-white border-top" style="z-index: 99">
        <!-- Scrolling Announcement Ticker -->
        <div class="ticker-bar">
            <div class="ticker-text">
                <i class="fas fa-bullhorn text-warning me-2"></i> Announcement: Please make sure to have your student ID and evaluation forms ready when your queue number is called.
            </div>
        </div>
    </div>

    <!-- STARTUP INTERACTION MODAL -->
    <div id="interactionModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 9999;">
        <div class="text-center bg-white p-5 rounded-4 shadow-lg" style="max-width: 450px;">
            <div class="mb-4 text-warning">
                <i class="fas fa-desktop fa-4x"></i>
            </div>
            <h3 class="fw-bold text-dark mb-2">Queue Display Ready</h3>
            <p class="text-muted small mb-4">Click below to activate voice announcements and live stream updates on this monitor.</p>
            <button id="initInteraction" class="btn btn-warning btn-lg px-5 py-3 fw-bold w-100 shadow" style="border-radius: 50px; background-color: #f59e0b; border: none; color: #ffffff;">
                <i class="fas fa-play me-2"></i> Launch Queue Display
            </button>
        </div>
    </div>

    <!-- Bootstrap JS & Vendor Plugins -->
    <script type="text/javascript" src="{{ asset('uilibs/js/main.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>

    <!-- DataTables & Plugins -->
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
    <!-- SweetAlert2 -->
    <script src="{{ asset('uilibs/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}"></script>

    <!-- QUEUE MONITOR FUNCTIONALITY SCRIPT -->
    <script>
        let lastQueueNumbercall = null;
        let lastWindowNumbercall = null;
        let previousData = [];
        let isFirstLoad = true;

        // Text-to-Speech Voice Announcement
        function announceVoice(message) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const speech = new SpeechSynthesisUtterance(message);
                speech.lang = 'en-US';
                speech.volume = 1;
                speech.rate = 0.9;
                speech.pitch = 1;
                window.speechSynthesis.speak(speech);
            }
        }

        // Welcome Sound Alert & Voice Announcement
        function announceWelcome() {
            const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
            sound.play().then(() => {
                setTimeout(() => {
                    announceVoice("Welcome to Central Philippines State University.");
                }, 600);
            }).catch(e => console.warn('Audio playback restricted:', e));
        }

        $(document).ready(function () {$('#initInteraction').on('click', function () {
                $('#interactionModal').fadeOut();
                announceWelcome();

                function parseCounterNumber(windowVal) {
                    if (!windowVal) return 999;
                    const match = windowVal.toString().match(/\d+/);
                    return match ? parseInt(match[0], 10) : 999;
                }

                // State tracking variables for sound announcements
                let previousCardCount = 0;
                let previousCounterStatuses = {};
                let isFirstRender = true;

                function renderCounterCards(data) {
                    const gridContainer = $('#counterCardsGrid');

                    if (!data || data.length === 0) {
                        gridContainer.html('<div class="col-12 text-center py-4 text-muted">No active counter records found</div>');
                        previousCardCount = 0;
                        previousCounterStatuses = {};
                        return;
                    }

                    // Sort Data from Counter 1 to highest
                    const sortedData = [...data].sort((a, b) => {
                        return parseCounterNumber(a.window) - parseCounterNumber(b.window);
                    });

                    let cardsHTML = '';
                    let waitingTickets = [];

                    sortedData.forEach(function(item) {
                        const rawWindow = item.window ? item.window.toString().trim() : '';
                        const windowLabel = rawWindow.toUpperCase().includes('COUNTER')
                            ? rawWindow.toUpperCase()
                            : `COUNTER ${rawWindow || '--'}`;

                        const numberLabel = (item.number && item.number.trim() !== '') ? item.number : '---';
                        const wincounterstatus = item.windowstatus;

                        // -------------------------------------------------------------
                        // DETECT STATUS CHANGES & PLAY VOICE ANNOUNCEMENT
                        // -------------------------------------------------------------
                        if (previousCounterStatuses[rawWindow] !== undefined) {
                            const oldStatus = previousCounterStatuses[rawWindow];

                            if (oldStatus !== wincounterstatus) {
                                let announcementText = '';

                                if (wincounterstatus == 1) {
                                    announcementText = `${windowLabel} is now Closed.`;
                                } else if (wincounterstatus == 2) {
                                    announcementText = `${windowLabel} is now Open.`;
                                } else if (wincounterstatus == 3) {
                                    announcementText = `${windowLabel} is now on Hold.`;
                                }

                                if (announcementText !== '') {
                                    // Play chime sound first, then speak
                                    const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
                                    sound.play().then(() => {
                                        setTimeout(() => {
                                            announceVoice(announcementText);
                                        }, 500);
                                    }).catch(e => console.warn('Audio play restricted:', e));
                                }
                            }
                        }
                        // Save current status for next check iteration
                        previousCounterStatuses[rawWindow] = wincounterstatus;

                        let statusText = '';
                        let statusClass = '';

                        if (item.status) {
                            const dbStatusUpper = item.status.toString().toUpperCase().trim();
                            switch(dbStatusUpper) {
                                case 'NOW SERVING':
                                case 'SERVING':
                                    statusText = 'NOW SERVING';
                                    statusClass = 'badge-now-serving';
                                    break;
                                case 'CALLING':
                                case 'CALLING NEXT':
                                    statusText = 'CALLING';
                                    statusClass = 'badge-calling-next';
                                    break;
                                case 'NEXT':
                                    statusText = 'NEXT';
                                    statusClass = 'badge-next';
                                    break;
                                case 'WAITING':
                                    statusText = 'WAITING';
                                    statusClass = 'badge-waiting';
                                    if (numberLabel !== '---') waitingTickets.push(numberLabel);
                                    break;
                                case 'CLOSE':
                                    statusText = 'CLOSE';
                                    statusClass = 'badge-close';
                                    break;
                                case 'OPEN':
                                    statusText = 'OPEN';
                                    statusClass = 'badge-open';
                                    break;
                                case 'HOLD':
                                    statusText = 'HOLD';
                                    statusClass = 'badge-hold';
                                    break;
                                default:
                                    statusText = dbStatusUpper;
                                    statusClass = 'badge-waiting';
                            }
                        } else {
                            // Check explicit window status first (1 = CLOSE, 3 = HOLD)
                            if (wincounterstatus == 1) {
                                statusText = 'CLOSE';
                                statusClass = 'badge-close';
                            } 
                            else if (wincounterstatus == 3) {
                                statusText = 'HOLD';
                                statusClass = 'badge-hold';
                            } 
                            // If open and has a active ticket, set to NOW SERVING
                            else if (numberLabel !== '---') {
                                statusText = 'NOW SERVING';
                                statusClass = 'badge-now-serving';
                            } 
                            // Fallback default for OPEN counters with no active ticket
                            else if (wincounterstatus == 2) {
                                statusText = 'OPEN';
                                statusClass = 'badge-open';
                            }
                        }

                        // Preserved YOUR exact card design inside Bootstrap grid column
                        cardsHTML += `
                            <div class="col">
                                <div class="card bg-light shadow-sm rounded-4 h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                                        <div>
                                            <i class="ti ti-device-laptop counter-icon fs-4 text-success me-1"></i>
                                            <span class="counter-title fs-4 font-weight-bold text-muted">${windowLabel}</span>
                                            <div class="mt-1">
                                                <span class="status-badge ${statusClass}">${statusText}</span>
                                            </div>
                                        </div>
                                        <div class="counter-token text-dark" style="font-weight: bolder !important; font-size: 26pt; font-family: 'Poppins', sans-serif !important;">${numberLabel}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    gridContainer.html(cardsHTML);

                    // Render Next In Line Preview
                    if (waitingTickets.length > 0) {
                        const nextHTML = waitingTickets.slice(0, 4).map(t => `<span class="next-queue-pill">${t}</span>`).join('');
                        $('#nextInLineContainer').html(nextHTML);
                    } else {
                        $('#nextInLineContainer').html('<span class="text-muted small">No tickets currently waiting</span>');
                    }
                    // Disable initial render flag so audio alerts run on subsequent stream updates
                    isFirstRender = false;
                }

                // Polling for Queue Data Stream
                function fetchQueueData() {
                    $.ajax({
                        url: "{{ route('queue.stream') }}",
                        method: 'GET',
                        success: function (response) {
                            if (!response || !response.data) return;

                            renderCounterCards(response.data);

                            if (!isFirstLoad) {
                                response.data.forEach(function(queueData, index) {
                                    if (previousData[index]?.number !== queueData.number || previousData[index]?.window !== queueData.window) {
                                        const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
                                        sound.play().catch(e => console.warn('Audio play issue:', e));

                                        const queueNumber = queueData.number || 'No queue number';
                                        const windowNumber = queueData.window || 'N/A';
                                        announceVoice(`Queue number ${queueNumber}. Please proceed to counter ${windowNumber}.`);
                                    }
                                });
                            }

                            previousData = response.data;
                            isFirstLoad = false;
                        },
                        error: function () {
                            console.error("Error fetching queue stream data.");
                        }
                    });
                }

                fetchQueueData();
                setInterval(fetchQueueData, 2000);
            });
        });

        // Polling for Current Serving and Calling Next Panels
        function fetchLiveStatuses() {
            // Stream Current Ticket
            $.ajax({
                url: "{{ route('queue.stream.current') }}",
                method: "GET",
                success: function(data) {
                    if (data && data.number) {
                        $('#queue-numbercurr').text(data.number);
                        $('#window-numbercurr').text('Proceed to Counter ' + (data.window || '--'));
                    } else {
                        $('#queue-numbercurr').text('-');
                        $('#window-numbercurr').text('Proceed to Counter --');
                    }
                }
            });

            // Stream Calling Next Ticket
            $.ajax({
                url: "{{ route('queue.stream.call') }}",
                method: "GET",
                success: function(data) {
                    if (data && data.number) {
                        $('#queue-number').text(data.number);
                        $('#window-number').text('Proceed to Counter ' + (data.window || '--'));

                        const queueNumber = data.number;
                        const windowNumber = data.window || 'N/A';

                        if (lastQueueNumbercall === null && lastWindowNumbercall === null) {
                            lastQueueNumbercall = queueNumber;
                            lastWindowNumbercall = windowNumber;
                            return;
                        }

                        if (queueNumber !== lastQueueNumbercall || windowNumber !== lastWindowNumbercall) {
                            const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
                            sound.play().catch(e => console.warn('Audio playback issue:', e));

                            announceVoice(`Calling Queue number ${queueNumber}. Please proceed to counter ${windowNumber}.`);

                            lastQueueNumbercall = queueNumber;
                            lastWindowNumbercall = windowNumber;
                        }
                    } else {
                        $('#queue-number').text('-');
                        $('#window-number').text('Proceed to Counter --');
                    }
                }
            });
        }

        setInterval(fetchLiveStatuses, 2000);
        fetchLiveStatuses();

        // Clock and Date Updater
        function updateTime() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            const formattedTime = [
                hours % 12 || 12,
                minutes.toString().padStart(2, '0')
            ].join(':') + ` ${ampm}`;

            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            const formattedDate = `${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;

            document.getElementById('time').textContent = formattedTime;
            document.getElementById('date').textContent = formattedDate;
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>