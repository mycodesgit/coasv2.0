<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>@yield('title') - CPSU Queueing System</title>

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
            height: calc(100vh - 60px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Vertical single column scrollable list for counters */
        .counter-vertical-list {
            display: flex;
            flex-direction: column;
            gap: 0.50rem;
            max-height: calc(100vh - 220px);
            overflow-y: auto;
            padding-right: 6px;
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
        .marquee-wrapper {
            max-height: 800px; /* Adjust according to your container layout height */
            overflow: hidden;
            position: relative;
            /* Hardware acceleration & smooth clipping */
            -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 5%, black 95%, transparent 100%);
            mask-image: linear-gradient(to bottom, transparent 0%, black 5%, black 95%, transparent 100%);
        }

        .marquee-content {
            display: flex;
            flex-direction: column;
            animation: scrollContinuous 15s linear infinite;
            will-change: transform;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        /* Pause scroll when mouse hovers so operators can inspect counter cards */
        .marquee-wrapper:hover .marquee-content {
            animation-play-state: paused;
        }

        @keyframes scrollContinuous {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-50%); /* Moves exactly half way down (first copy) before repeating */
            }
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
            <div class="d-flex align-items-center gap-2">
                <span class="p-1 rounded-circle bg-success d-inline-block" style="width: 8px; height: 8px;"></span>
                <span class="small fw-semibold text-muted">Live Sync</span>
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
    <main id="content" class="content py-10 full">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">

                        <div class="row g-3">
                            <div class="card-body d-flex flex-column justify-content-between w-100">
                                <div class="row g-3 align-items-center">

                                    <!-- Left Column: Main Serving Window (Free-standing, no card) -->
                                    <div class="col-md-7 border-end pe-4">
                                        <!-- Top: Current No. -->
                                        <div class="text-center">
                                            <span id="window-numbercurr" class="badge bg-success-subtle text-success mb-2 px-4 py-2 fs-2 fw-bold">Counter --</span>
                                            <h2 id="queue-numbercurr" class="display-serving-number my-2 text-success">-</h2>
                                            <p class="text-muted fs-6 mb-0 mt-2 fw-bold uppercase" style="letter-spacing: 1px;">NOW SERVING TICKET</p>
                                        </div>
                                        <br><br><br><br><br>
                                        <hr class="my-3">
                                        <br><br><br><br><br>
                                        <!-- Bottom: Calling No. -->
                                        <div class="text-center py-4">
                                            <span class="badge bg-warning-subtle text-warning-subtle text-warning mb-2 px-4 py-2 fs-2 fw-bold">CALLING NEXT</span>
                                            <h2 id="queue-number" class="display-calling-number my-2 text-dark">-</h2>
                                            <p id="window-number" class="text-muted fs-6 mb-0 fw-semibold">Proceed to Counter --</p>
                                        </div>
                                    </div>

                                    <!-- Right Column: List of Counter Cards Vertically Aligned in One Column -->
                                    <div class="col-5 ps-4">
                                        <div id="counterCardsGrid" class="counter-vertical-list">

                                            <!-- Default Card Template -->
                                            <div class="card rounded-3">
                                                <div class="card-body">
                                                    <i class="ti ti-user counter-icon"></i>
                                                    <div class="counter-title font-weight-bold text-muted small">COUNTER 1</div>
                                                    <div class="counter-token fs-2 font-weight-bold text-dark">---</div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="row">
                <div class="col-12">
                    <footer class="text-center py-2 mt-4 text-secondary fixed-bottom bg-white border-top" style="z-index: 99">
                        <p class="mb-0 small">Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</p>
                    </footer>
                </div>
            </div>

        </div>
    </main>

    <!-- STARTUP INTERACTION MODAL -->
    <div id="interactionModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 9999;">
        <button id="initInteraction" class="btn btn-warning btn-lg px-5 py-3 font-weight-bold" style="border-radius: 50px; font-size: 1.25rem; background-color: #f59e0b; border: none; color: #ffffff; cursor: pointer;">
            <i class="fas fa-play me-2"></i> Launch Queue Monitor
        </button>
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
    <!-- Validation JS -->
    <script src="{{ asset('uilibs/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery-validation/additional-methods.min.js') }}"></script>

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

        $(document).ready(function () {
            // Launch Monitor
            $('#initInteraction').on('click', function () {
                $('#interactionModal').fadeOut();
                announceWelcome();

                // Sort helper for counters (Counter 1 to highest)
                function parseCounterNumber(windowVal) {
                    if (!windowVal) return 999;
                    const match = windowVal.toString().match(/\d+/);
                    return match ? parseInt(match[0], 10) : 999;
                }

                // Render dynamic cards vertically inside card and card-body
                // Keep track of total item count to avoid unnecessarily resetting the CSS animation
let previousCardCount = 0;

function renderCounterCards(data) {
    const gridContainer = $('#counterCardsGrid');

    if (!data || data.length === 0) {
        gridContainer.html('<div class="text-center py-4 text-muted">No active counter records found</div>');
        previousCardCount = 0;
        return;
    }

    // Sort Data from Counter 1 to highest
    const sortedData = [...data].sort((a, b) => {
        return parseCounterNumber(a.window) - parseCounterNumber(b.window);
    });

    let cardsHTML = '';
    sortedData.forEach(function(item) {
        const rawWindow = item.window ? item.window.toString().trim() : '';
        const windowLabel = rawWindow.toUpperCase().includes('COUNTER')
            ? rawWindow.toUpperCase()
            : `COUNTER ${rawWindow || '--'}`;

        const numberLabel = (item.number && item.number.trim() !== '') ? item.number : '---';

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
                    break;
                default:
                    statusText = dbStatusUpper;
                    statusClass = 'badge-waiting';
            }
        } else {
            if (numberLabel !== '---') {
                statusText = 'NOW SERVING';
                statusClass = 'badge-now-serving';
            } else {
                statusText = 'WAITING';
                statusClass = 'badge-waiting';
            }
        }

        cardsHTML += `
            <div class="card bg-light shadow-sm rounded-4 mb-2">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <i class="ti ti-device-laptop counter-icon fs-2 text-success me-1"></i>
                        <span class="counter-title fs-3 font-weight-bold text-muted">${windowLabel}</span>
                        <div class="mt-1">
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                    </div>
                    <div class="counter-token fs-1 font-weight-bold text-dark">${numberLabel}</div>
                </div>
            </div>
        `;
    });

    const isScrollingNeeded = sortedData.length >= 7;

    if (isScrollingNeeded) {
        // Calculate duration based on card count to keep scroll speed steady (3.5 seconds per card)
        const duration = Math.max(12, sortedData.length * 3.5);

        const activeMarquee = gridContainer.find('.marquee-content');

        // If marquee already exists and card count hasn't changed, update inner content to prevent scroll jumping
        if (activeMarquee.length > 0 && previousCardCount === sortedData.length) {
            activeMarquee.html(cardsHTML + cardsHTML);
        } else {
            // Re-render container when scrolling starts or list length changes
            const marqueeMarkup = `
                <div class="marquee-wrapper">
                    <div class="marquee-content" style="animation-duration: ${duration}s;">
                        ${cardsHTML}
                        ${cardsHTML}
                    </div>
                </div>
            `;
            gridContainer.html(marqueeMarkup);
        }
    } else {
        gridContainer.html(cardsHTML);
    }

    previousCardCount = sortedData.length;
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
                        $('#window-numbercurr').text('Counter ' + (data.window || '--'));
                    } else {
                        $('#queue-numbercurr').text('-');
                        $('#window-numbercurr').text('Counter --');
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

                            announceVoice(`Queue number ${queueNumber}. Please proceed to counter ${windowNumber}.`);

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
