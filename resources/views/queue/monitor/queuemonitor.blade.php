<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            gap: 1.25rem;
            z-index: 2;
        }

        .header-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 0.5px;
            line-height: 1.1;
            margin: 0;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin: 0;
            color: #e2e8f0;
        }

        .brand-motto {
            font-size: 0.8rem;
            font-style: italic;
            opacity: 0.85;
            margin-top: 2px;
        }

        .header-datetime {
            text-align: right;
            z-index: 2;
        }

        .header-date {
            font-size: 1.05rem;
            font-weight: 600;
            opacity: 0.9;
        }

        .header-time {
            font-size: 2.1rem;
            font-weight: 900;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        /* MAIN LAYOUT CONTAINER */
        .main-viewport {
            height: calc(100vh - 145px);
            padding: 1rem 1.5rem;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 1.25rem;
        }

        /* LEFT DISPLAY PANEL */
        .left-display-container {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            height: 100%;
        }

        /* TWO STACKED CARDS WRAPPER */
        .stacked-cards-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
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
        }

        /* NOW SERVING CARD HEADER */
        .header-serving {
            background-color: var(--cpsu-mid-green);
            color: #ffffff;
            padding: 0.5rem 1.25rem;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-transform: uppercase;
        }

        /* CALLING NEXT CARD HEADER */
        .header-calling {
            background-color: var(--cpsu-gold);
            color: #ffffff;
            padding: 0.5rem 1.25rem;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-transform: uppercase;
        }

        .card-inner-body {
            background-color: var(--cpsu-card-mint);
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            text-align: center;
        }

        .token-num-serving {
            font-size: clamp(4.5rem, 5vw, 5.8rem);
            font-weight: 900;
            color: var(--cpsu-dark-green);
            line-height: 0.95;
            letter-spacing: -1px;
        }

        .token-num-calling {
            font-size: clamp(4.5rem, 5vw, 5.8rem);
            font-weight: 900;
            color: #d97706;
            line-height: 0.95;
            letter-spacing: -1px;
        }

        .sub-instruction {
            font-size: 2.15rem;
            font-weight: 700;
            color: #334155;
            margin-top: 0.25rem;
            margin-bottom: 0;
        }

        /* ACTIVE COUNTER GRID BELOW */
        .counter-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.85rem;
            height: 105px;
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
            font-size: 1rem;
            color: var(--cpsu-dark-green);
            margin-bottom: 0.1rem;
        }

        .mini-counter-card .counter-title {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--cpsu-dark-green);
            text-transform: uppercase;
        }

        .mini-counter-card .counter-token {
            font-size: 1.6rem;
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
        }

        .queue-table-container {
            flex: 1;
            overflow-y: auto;
        }

        .queue-table {
            width: 100%;
            border-collapse: collapse;
        }

        .queue-table th {
            background-color: var(--cpsu-card-mint);
            color: var(--cpsu-dark-green);
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .queue-table td {
            padding: 0.65rem 1rem;
            text-align: center;
            font-weight: 900;
            font-size: 2rem;
            color: #2d3748;
            border-bottom: 1px solid #edf2f7;
        }

        .queue-table tr:nth-child(even) {
            background-color: #fcfdfe;
        }

        /* STATUS BADGES */
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.85rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            width: 120px;
        }

        .badge-now-serving {
            background-color: var(--cpsu-dark-green);
            color: #ffffff;
        }

        .badge-calling-next {
            background-color: var(--cpsu-gold);
            color: #ffffff;
        }

        .badge-next {
            background-color: var(--badge-blue);
            color: #0d47a1;
        }

        .badge-waiting {
            background-color: var(--badge-gray);
            color: #ffffff;
        }

        /* FOOTER BANNER */
        .app-footer {
            height: 60px;
            background-color: var(--cpsu-dark-green);
            color: #ffffff;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 3px solid var(--cpsu-mid-green);
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .footer-icon {
            font-size: 1.8rem;
            transform: rotate(-15deg);
        }

        .footer-divider {
            width: 2px;
            height: 30px;
            background-color: rgba(255,255,255,0.3);
        }

        .footer-msg-title {
            font-size: 1rem;
            font-weight: 800;
            margin: 0;
            line-height: 1.2;
        }

        .footer-msg-sub {
            font-size: 0.8rem;
            margin: 0;
            opacity: 0.85;
        }

        .footer-brand-signature {
            font-family: 'Georgia', serif;
            font-size: 1.3rem;
            font-style: italic;
            font-weight: bold;
            color: #ffffff;
        }

        /* PULSE ANIMATION FOR CALLING NEXT */
        @keyframes soft-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(0.99); }
        }

        .pulse-card {
            animation: soft-pulse 2s infinite ease-in-out;
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
                            <th style="width: 30%;">STATUS</th>
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
            CPSU <span style="font-size: 0.9em; font-weight: normal;">Always for you</span>
        </div>
    </footer>

    <!-- JS SCRIPTS -->
    <script src="{{ asset('uilibs/js/main.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    @if(request()->routeIs('queue-monitor'))
        <!-- Startup Modal -->
        <div id="interactionModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(3, 69, 39, 0.92); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 9999;">
            <button id="initInteraction" class="btn btn-warning btn-lg px-5 py-3 font-weight-bold shadow-lg" style="border-radius: 50px; font-size: 1.25rem; background-color: #f59e0b; border: none; color: #ffffff;">
                <i class="fas fa-play mr-2"></i> Launch Queue Monitor
            </button>
        </div>

        <script>
            $(document).ready(function () {
                var sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");

                $('#initInteraction').on('click', function () {
                    $('#interactionModal').fadeOut();
                    sound.play();

                    var previousData = [];

                    function renderWindowCardsAndTable(data) {
                        var grid = $('#windowGridContainer');
                        var tableBody = $('#queueTableBody');

                        grid.empty();
                        tableBody.empty();

                        if (!data || data.length === 0) {
                            grid.html('<div class="text-center w-100 py-3 text-muted">No active counters</div>');
                            tableBody.html('<tr><td colspan="3" class="text-center py-4 text-muted">No queue records found</td></tr>');
                            return;
                        }

                        // Render Active Counter Cards (First 4)
                        var activeCounters = data.slice(0, 4);
                        activeCounters.forEach(function(item) {
                            var windowLabel = item.window ? `COUNTER ${item.window}` : 'COUNTER --';
                            var numberLabel = item.number || '---';

                            var cardHtml = `
                                <div class="mini-counter-card">
                                    <i class="ti ti-user counter-icon"></i>
                                    <div class="counter-title">${windowLabel}</div>
                                    <div class="counter-token">${numberLabel}</div>
                                </div>
                            `;
                            grid.append(cardHtml);
                        });

                        // Render Queue List Table
                        data.forEach(function(item, index) {
                            var windowLabel = item.window || '-';
                            var numberLabel = item.number || '';

                            var statusText = '';
                            var statusClass = '';

                            // Direct DB Status evaluation logic with fallbacks
                            if (item.status) {
                                var dbStatusUpper = item.status.toString().toUpperCase().trim();
                                if (dbStatusUpper === 'NOW SERVING' || dbStatusUpper === 'SERVING') {
                                    statusText = 'NOW SERVING';
                                    statusClass = 'badge-now-serving';
                                } else if (dbStatusUpper === 'CALLING' || dbStatusUpper === 'CALLING NEXT') {
                                    statusText = 'CALLING';
                                    statusClass = 'badge-calling-next';
                                } else if (dbStatusUpper === 'NEXT') {
                                    statusText = 'NEXT';
                                    statusClass = 'badge-next';
                                } else if (dbStatusUpper === 'WAITING') {
                                    statusText = 'WAITING';
                                    statusClass = 'badge-waiting';
                                } else {
                                    statusText = dbStatusUpper;
                                    statusClass = 'badge-waiting';
                                }
                            } else {
                                // Fallback: Has transaction number = NOW SERVING, empty = WAITING
                                if (numberLabel && numberLabel !== '---' && numberLabel.trim() !== '') {
                                    statusText = 'NOW SERVING';
                                    statusClass = 'badge-now-serving';
                                } else {
                                    statusText = 'WAITING';
                                    statusClass = 'badge-waiting';
                                }
                            }

                            var displayToken = (numberLabel && numberLabel.trim() !== '') ? numberLabel : '---';

                            var rowHtml = `
                                <tr>
                                    <td class="font-weight-bold" style="color: var(--cpsu-dark-green);">${displayToken}</td>
                                    <td>${windowLabel}</td>
                                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                                </tr>
                            `;
                            tableBody.append(rowHtml);
                        });
                    }

                    function fetchQueueData() {
                        $.ajax({
                            url: "{{ route('queue.stream') }}",
                            method: 'GET',
                            success: function (response) {
                                renderWindowCardsAndTable(response.data);

                                response.data.forEach(function(queueData, index) {
                                    if (previousData[index]?.number !== queueData.number || previousData[index]?.window !== queueData.window) {
                                        sound.play().catch(e => console.warn('Audio playback issue:', e));

                                        const queueNumber = queueData.number || 'No queue number';
                                        const windowNumber = queueData.window || 'N/A';
                                        const message = `Queue number ${queueNumber}. Please proceed to counter ${windowNumber}.`;

                                        const speech = new SpeechSynthesisUtterance(message);
                                        speech.lang = 'en-US';
                                        speech.volume = 1;
                                        speech.rate = 1;
                                        speech.pitch = 1;
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

                    fetchQueueData();
                    setInterval(fetchQueueData, 2000);
                });
            });
        </script>

        <script>
            function fetchQueueStatusCurr() {
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
                    },
                    error: function() {
                        console.error('Failed to fetch current queue status.');
                    }
                });
            }

            setInterval(fetchQueueStatusCurr, 2000);
            fetchQueueStatusCurr();
        </script>

        <script>
            let lastQueueNumbercall = null;
            let lastWindowNumbercall = null;

            function fetchQueueStatusCall() {
                $.ajax({
                    url: "{{ route('queue.stream.call') }}",
                    method: "GET",
                    success: function(data) {
                        if (data && data.number) {
                            $('#queue-number').text(data.number);
                            $('#window-number').text('Proceed to Counter ' + (data.window || '--'));

                            const queueNumber = data.number;
                            const windowNumber = data.window || 'N/A';

                            if (queueNumber !== lastQueueNumbercall || windowNumber !== lastWindowNumbercall) {
                                const sound = new Audio("{{ asset('template/sound/announcement-sound-effect.wav') }}");
                                sound.play().catch(e => console.warn('Audio playback issue:', e));

                                const message = `Queue number ${queueNumber}. Please proceed to counter ${windowNumber}.`;
                                const speech = new SpeechSynthesisUtterance(message);
                                speech.lang = 'en-US';
                                speech.volume = 1;
                                speech.rate = 1;
                                speech.pitch = 1;
                                window.speechSynthesis.speak(speech);

                                lastQueueNumbercall = queueNumber;
                                lastWindowNumbercall = windowNumber;
                            }
                        } else {
                            $('#queue-number').text('-');
                            $('#window-number').text('Proceed to Counter --');
                        }
                    },
                    error: function() {
                        console.error('Failed to fetch call queue status.');
                    }
                });
            }

            setInterval(fetchQueueStatusCall, 2000);
            fetchQueueStatusCall();
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
