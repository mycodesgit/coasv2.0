<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - CPSU Queueing System</title>

    <link rel="shortcut icon" sizes="180x180" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uilibs/images/cpsulogov4.png') }}">

    <link rel="stylesheet" href="{{ asset('uilibs/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">

    <style>
        :root {
            --cpsu-dark-green: #034527;
            --cpsu-mid-green: #005a33;
            --cpsu-mint-bg: #e8f5e9;
            --cpsu-light-mint: #f1f8f5;
            --cpsu-card-mint: #e2f1e7;
            --cpsu-gray-bg: #eef2f1;
            --cpsu-gold: #f59e0b;
            --badge-blue: #90caf9;
            --badge-gray: #b0bec5;
            --header-height: clamp(65px, 8vh, 90px);
            --footer-height: clamp(45px, 6vh, 60px);
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        html, body {
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: var(--cpsu-gray-bg);
            font-family: "Poppins", sans-serif, system-ui, -apple-system, Roboto;
            color: #1a202c;
        }

        /* HEADER BANNER */
        .app-header {
            height: 85px;
            background: linear-gradient(135deg, var(--cpsu-dark-green) 0%, var(--cpsu-mid-green) 65%, #086b3e 100%);
            color: #ffffff;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .app-header::after {
            content: '';
            position: absolute;
            right: 280px;
            top: -20px;
            width: 120px;
            height: 140px;
            background: rgba(255, 255, 255, 0.08);
            transform: skewX(-25deg);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: clamp(0.5rem, 1.5vw, 1.25rem);
            z-index: 2;
        }

        .header-logo {
            width: clamp(38px, 4.5vw, 58px);
            height: clamp(38px, 4.5vw, 58px);
            object-fit: contain;
        }

        .brand-title {
            font-size: clamp(1.1rem, 2vw, 1.6rem);
            font-weight: 900;
            letter-spacing: 0.5px;
            line-height: 1.1;
            margin: 0;
            text-transform: uppercase;
        }

        .header-datetime {
            text-align: right;
            z-index: 2;
        }

        .header-date {
            font-size: clamp(0.75rem, 1.1vw, 1.05rem);
            font-weight: 600;
            opacity: 0.9;
        }

        .header-time {
            font-size: clamp(1.2rem, 2.2vw, 2.1rem);
            font-weight: 900;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        /* MAIN LAYOUT CONTAINER */
        .main-viewport {
            min-height: calc(100vh - var(--header-height) - var(--footer-height));
            padding: clamp(0.75rem, 1.5vw, 1.25rem);
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: clamp(0.75rem, 1.5vw, 1.25rem);
        }

        /* LEFT DISPLAY PANEL */
        .left-display-container {
            display: flex;
            flex-direction: column;
            gap: clamp(0.5rem, 1vw, 0.85rem);
            height: 100%;
        }

        /* TWO STACKED CARDS WRAPPER */
        .stacked-cards-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: clamp(0.5rem, 1vw, 0.85rem);
        }

        /* CARD BASE */
        .status-hero-card {
            background-color: var(--cpsu-light-mint);
            border-radius: 12px;
            border: 1px solid #d0e3d5;
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 8px rgba(0,0,0,0.03);
            will-change: transform;
        }

        /* HEADERS */
        .header-serving, .header-calling {
            color: #ffffff;
            padding: clamp(0.4rem, 0.8vw, 0.65rem) clamp(0.75rem, 1.5vw, 1.25rem);
            font-size: clamp(0.85rem, 1.3vw, 1.15rem);
            font-weight: 800;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-transform: uppercase;
        }

        .header-serving { background-color: var(--cpsu-mid-green); }
        .header-calling { background-color: var(--cpsu-gold); }

        .card-inner-body {
            background-color: var(--cpsu-card-mint);
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(0.5rem, 1vw, 1rem);
            text-align: center;
        }

        .token-num-serving {
            font-size: clamp(3rem, 6vw, 5.8rem);
            font-weight: 900;
            color: var(--cpsu-dark-green);
            line-height: 0.95;
            letter-spacing: -1px;
        }

        .token-num-calling {
            font-size: clamp(3rem, 6vw, 5.8rem);
            font-weight: 900;
            color: #d97706;
            line-height: 0.95;
            letter-spacing: -1px;
        }

        .sub-instruction {
            font-size: clamp(1.1rem, 2.2vw, 2.15rem);
            font-weight: 700;
            color: #334155;
            margin-top: 0.25rem;
            margin-bottom: 0;
        }

        /* ACTIVE COUNTER GRID */
        .counter-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: clamp(0.5rem, 1vw, 0.85rem);
            min-height: clamp(80px, 11vh, 105px);
        }

        .mini-counter-card {
            background-color: var(--cpsu-card-mint);
            border: 1px solid #c3decb;
            border-radius: 10px;
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        .mini-counter-card .counter-icon {
            font-size: clamp(0.8rem, 1.2vw, 1rem);
            color: var(--cpsu-dark-green);
            margin-bottom: 0.1rem;
        }

        .mini-counter-card .counter-title {
            font-size: clamp(0.65rem, 0.9vw, 0.75rem);
            font-weight: 800;
            color: var(--cpsu-dark-green);
            text-transform: uppercase;
        }

        .mini-counter-card .counter-token {
            font-size: clamp(1.1rem, 1.8vw, 1.6rem);
            font-weight: 900;
            color: var(--cpsu-dark-green);
            line-height: 1;
            margin-top: 0.15rem;
        }

        /* RIGHT PANEL: QUEUE LIST */
        .right-queue-panel {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #d0e3d5;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 8px rgba(0,0,0,0.04);
            height: 100%;
            max-height: calc(100vh - var(--header-height) - var(--footer-height) - 2.5rem);
        }

        .queue-table-container {
            flex: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .queue-table {
            width: 100%;
            border-collapse: collapse;
        }

        .queue-table th {
            background-color: var(--cpsu-card-mint);
            color: var(--cpsu-dark-green);
            font-size: clamp(0.7rem, 0.9vw, 0.85rem);
            font-weight: 800;
            text-transform: uppercase;
            padding: clamp(0.5rem, 1vw, 0.75rem);
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .queue-table td {
            padding: clamp(0.4rem, 0.8vw, 0.65rem);
            text-align: center;
            font-weight: 900;
            font-size: clamp(1.1rem, 2vw, 2rem);
            color: #2d3748;
            border-bottom: 1px solid #edf2f7;
            content-visibility: auto;
        }

        .queue-table tr:nth-child(even) {
            background-color: #fcfdfe;
        }

        /* STATUS BADGES */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: clamp(0.65rem, 0.8vw, 0.75rem);
            font-weight: 800;
            text-transform: uppercase;
            width: clamp(80px, 100%, 120px);
        }

        .badge-now-serving { background-color: var(--cpsu-dark-green); color: #ffffff; }
        .badge-calling-next { background-color: var(--cpsu-gold); color: #ffffff; }
        .badge-next { background-color: var(--badge-blue); color: #0d47a1; }
        .badge-waiting { background-color: var(--badge-gray); color: #ffffff; }

        /* FOOTER BANNER */
        .app-footer {
            height: var(--footer-height);
            background-color: var(--cpsu-dark-green);
            color: #ffffff;
            padding: 0 clamp(1rem, 2vw, 2rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 3px solid var(--cpsu-mid-green);
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .footer-icon {
            font-size: clamp(1.2rem, 1.8vw, 1.8rem);
            transform: rotate(-15deg);
        }

        .footer-divider {
            width: 2px;
            height: 25px;
            background-color: rgba(255,255,255,0.3);
        }

        .footer-msg-title {
            font-size: clamp(0.8rem, 1vw, 1rem);
            font-weight: 800;
            margin: 0;
            line-height: 1.2;
        }

        .footer-msg-sub {
            font-size: clamp(0.65rem, 0.8vw, 0.8rem);
            margin: 0;
            opacity: 0.85;
        }

        .footer-brand-signature {
            font-family: 'Georgia', serif;
            font-size: clamp(0.9rem, 1.3vw, 1.3rem);
            font-style: italic;
            font-weight: bold;
            color: #ffffff;
        }

        /* PULSE ANIMATION */
        @keyframes soft-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(0.99); }
        }

        .pulse-card {
            animation: soft-pulse 2s infinite ease-in-out;
        }

        /* RESPONSIVE BREAKPOINTS */

        /* TV & Large Monitors (1600px+) */
        @media screen and (min-width: 1600px) {
            .main-viewport {
                max-width: 1920px;
                margin: 0 auto;
            }
        }

        /* Tablets & Small Desktops (<= 992px) */
        @media screen and (max-width: 992px) {
            html, body {
                overflow-y: auto;
            }

            .main-viewport {
                grid-template-columns: 1fr;
                height: auto;
                min-height: auto;
            }

            .right-queue-panel {
                max-height: 500px;
            }
        }

        /* Mobile Devices (<= 576px) */
        @media screen and (max-width: 576px) {
            .app-header {
                padding: 0 0.75rem;
            }

            .brand-title {
                font-size: 1.1rem;
            }

            .counter-cards-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .right-queue-panel {
                max-height: 400px;
            }

            .footer-msg-sub, .footer-divider {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER NAVBAR -->
    <header class="app-header">
        <div class="header-brand">
            <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="CPSU Logo" class="header-logo">
            <div>
                <h1 class="brand-title">CISS</h1>
            </div>
        </div>
        <div class="header-datetime">
            <div id="date" class="header-date">Loading date...</div>
            <div id="time" class="header-time">--:-- --</div>
        </div>
    </header>

    <!-- MAIN VIEWPORT -->
    <main class="main-viewport">

        <!-- LEFT PANEL -->
        <div class="left-display-container">

            <!-- STACKED CARDS (NOW SERVING & CALLING NEXT) -->
            <div class="stacked-cards-wrapper">

                <!-- NOW SERVING CARD -->
                <div class="status-hero-card">
                    <div class="header-serving">
                        <i class="fas fa-user-check"></i> NOW SERVING
                    </div>
                    <div class="card-inner-body">
                        <div id="queue-numbercurr" class="token-num-serving">-</div>
                        <p id="window-numbercurr" class="sub-instruction">Proceed to Counter --</p>
                    </div>
                </div>

                <!-- CALLING NEXT CARD -->
                <div class="status-hero-card pulse-card">
                    <div class="header-calling">
                        <i class="fas fa-bullhorn"></i> CALLING NEXT
                    </div>
                    <div class="card-inner-body">
                        <div id="queue-number" class="token-num-calling">-</div>
                        <p id="window-number" class="sub-instruction">Proceed to Counter --</p>
                    </div>
                </div>

            </div>

            <!-- ACTIVE COUNTER GRID AT THE BOTTOM -->
            <div id="windowGridContainer" class="counter-cards-grid">
                <div class="mini-counter-card">
                    <i class="ti ti-user counter-icon"></i>
                    <div class="counter-title">COUNTER 1</div>
                    <div class="counter-token">---</div>
                </div>
                <div class="mini-counter-card">
                    <i class="ti ti-user counter-icon"></i>
                    <div class="counter-title">COUNTER 2</div>
                    <div class="counter-token">---</div>
                </div>
                <div class="mini-counter-card">
                    <i class="ti ti-user counter-icon"></i>
                    <div class="counter-title">COUNTER 3</div>
                    <div class="counter-token">---</div>
                </div>
                <div class="mini-counter-card">
                    <i class="ti ti-user counter-icon"></i>
                    <div class="counter-title">COUNTER 4</div>
                    <div class="counter-token">---</div>
                </div>
            </div>

        </div>

        <!-- RIGHT PANEL: QUEUE LIST -->
        <div class="right-queue-panel">
            <div class="header-serving" style="background-color: var(--cpsu-mid-green);">
                <i class="fas fa-clock"></i> QUEUE LIST
            </div>
            <div class="queue-table-container">
                <table class="table table-striped queue-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">TRANSACTION</th>
                            <th style="width: 25%;">COUNTER</th>
                            <th style="width: 40%;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="queueTableBody">
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Loading queue data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- FOOTER BANNER -->
    <footer class="app-footer">
        <div class="footer-left">
            <i class="fas fa-bullhorn footer-icon"></i>
            <div class="footer-divider"></div>
            <div>
                <p class="footer-msg-title">Thank you for your patience!</p>
                <p class="footer-msg-sub">We are committed to serve you better.</p>
            </div>
        </div>
        <div class="footer-brand-signature">
            CPSU
        </div>
    </footer>

    <!-- JS SCRIPTS -->
    <script src="{{ asset('uilibs/js/main.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    @if(request()->routeIs('queue-monitor'))
        <!-- Startup Modal -->
        <div id="interactionModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(3, 69, 39, 0.92); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 9999;">
            <button id="initInteraction" class="btn btn-warning btn-lg px-5 py-3 font-weight-bold shadow-lg" style="border-radius: 50px; font-size: 1.25rem; background-color: #f59e0b; border: none; color: #ffffff; cursor: pointer;">
                <i class="fas fa-play mr-2"></i> Launch Queue Monitor
            </button>
        </div>

        <script>
            let lastQueueNumbercall = null;
            let lastWindowNumbercall = null;
            let previousData = [];
            let isFirstLoad = true; // Flag to silence initial table load announcements

            // 1. GENERIC VOICE ANNOUNCEMENT FUNCTION
            function announceVoice(message) {
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel(); // Clear any pending speech queue
                    const speech = new SpeechSynthesisUtterance(message);
                    speech.lang = 'en-US';
                    speech.volume = 1;
                    speech.rate = 0.9;
                    speech.pitch = 1;
                    window.speechSynthesis.speak(speech);
                }
            }

            // 2. SEPARATE WELCOME ANNOUNCEMENT FUNCTION
            function announceWelcome() {
                const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
                sound.play().then(() => {
                    setTimeout(() => {
                        announceVoice("Welcome to Central Philippines State University.");
                    }, 600);
                }).catch(e => console.warn('Audio play restricted:', e));
            }

            $(document).ready(function () {$('#initInteraction').on('click', function () {
                    $('#interactionModal').fadeOut();

                    // TRIGGER WELCOME ONLY ONCE UPON LAUNCH
                    announceWelcome();

                    function renderWindowCardsAndTable(data) {
                        const grid = $('#windowGridContainer');
                        const tableBody = $('#queueTableBody');

                        grid.empty();
                        tableBody.empty();

                        if (!data || data.length === 0) {
                            grid.html('<div class="text-center w-100 py-3 text-muted">No active counters</div>');
                            tableBody.html('<tr><td colspan="3" class="text-center py-4 text-muted">No queue records found</td></tr>');
                            return;
                        }

                        // Render Active Counter Cards (First 4)
                        const activeCounters = data.slice(0, 4);
                        let gridFragment = '';
                        activeCounters.forEach(function(item) {
                            const windowLabel = item.window ? `COUNTER ${item.window}` : 'COUNTER --';
                            const numberLabel = item.number || '---';

                            gridFragment += `
                                <div class="mini-counter-card">
                                    <i class="ti ti-user counter-icon"></i>
                                    <div class="counter-title">${windowLabel}</div>
                                    <div class="counter-token">${numberLabel}</div>
                                </div>
                            `;
                        });
                        grid.append(gridFragment);

                        // Render Queue List Table
                        let tableFragment = '';
                        data.forEach(function(item) {
                            const windowLabel = item.window || '-';
                            const numberLabel = item.number || '';
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
                                if (numberLabel && numberLabel !== '---' && numberLabel.trim() !== '') {
                                    statusText = 'NOW SERVING';
                                    statusClass = 'badge-now-serving';
                                } else {
                                    statusText = 'WAITING';
                                    statusClass = 'badge-waiting';
                                }
                            }

                            const displayToken = (numberLabel && numberLabel.trim() !== '') ? numberLabel : '---';

                            tableFragment += `
                                <tr>
                                    <td class="font-weight-bold" style="color: var(--cpsu-dark-green);">${displayToken}</td>
                                    <td>${windowLabel}</td>
                                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                                </tr>
                            `;
                        });
                        tableBody.append(tableFragment);
                    }

                    function fetchQueueData() {
                        $.ajax({
                            url: "{{ route('queue.stream') }}",
                            method: 'GET',
                            success: function (response) {
                                if (!response || !response.data) return;

                                renderWindowCardsAndTable(response.data);

                                // Speak ONLY when data changes AFTER initial load
                                if (!isFirstLoad) {
                                    response.data.forEach(function(queueData, index) {
                                        if (previousData[index]?.number !== queueData.number || previousData[index]?.window !== queueData.window) {
                                            const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
                                            sound.play().catch(e => console.warn('Audio playback issue:', e));

                                            const queueNumber = queueData.number || 'No queue number';
                                            const windowNumber = queueData.window || 'N/A';
                                            announceVoice(`Queue number ${queueNumber}. Please proceed to counter ${windowNumber}.`);
                                        }
                                    });
                                }

                                previousData = response.data;
                                isFirstLoad = false; // Initial sync completed
                            },
                            error: function () {
                                console.error("Error fetching queue data.");
                            }
                        });
                    }

                    fetchQueueData();
                    setInterval(fetchQueueData, 2000);
                });
            });

            // 3. SEPARATE LIVE STREAM CALL TRACKER
            function fetchLiveStatuses() {
                // Fetch Current Status
                $.ajax({
                    url: "{{ route('queue.stream.current') }}",
                    method: "GET",
                    success: function(data) {
                        if (data && data.number) {
                            $('#queue-numbercurr').text(data.number);
                            $('#window-numbercurr').text('Proceed to Counter ' + (data.window || '--'));
                        } else {
                            $('#queue-numbercurr').text('-');
                            $('#window-numbercurr').text('Counter --');
                        }
                    }
                });

                // Fetch Call Status
                $.ajax({
                    url: "{{ route('queue.stream.call') }}",
                    method: "GET",
                    success: function(data) {
                        if (data && data.number) {
                            $('#queue-number').text(data.number);
                            $('#window-number').text('Proceed to Counter ' + (data.window || '--'));

                            const queueNumber = data.number;
                            const windowNumber = data.window || 'N/A';

                            // Save baseline call on launch so it doesn't speak existing data immediately
                            if (lastQueueNumbercall === null && lastWindowNumbercall === null) {
                                lastQueueNumbercall = queueNumber;
                                lastWindowNumbercall = windowNumber;
                                return;
                            }

                            // TRIGGER ANNOUNCEMENT ONLY IF A CHANGE DETECTED AT WINDOW/COUNTER
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
        </script>
    @endif

    <script>
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
