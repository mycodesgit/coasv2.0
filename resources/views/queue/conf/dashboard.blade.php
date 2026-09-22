@extends('layouts.master_queue')

@section('title')
CISS V.1.0 || Queueing Dashboard
@endsection

@yield('sidemenu')

@section('workspace')
    <style>
        .tracking-tight {
            letter-spacing: -1px;
        }
        .style-label {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }
        .style-dot {
            font-size: 0.45rem;
            vertical-align: middle;
        }
        .max-w-100 {
            max-width: 100%;
        }
        @keyframes pulse-blink {
            0% { opacity: 1; }
            50% { opacity: 0.2; }
            100% { opacity: 1; }
        }

        .blink-dot {
            animation: pulse-blink 1.2s infinite ease-in-out;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <!-- Breadcrumb -->
                <div class="card mb-3" style="background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Queueing</li>
                            <li class="breadcrumb-item active mt-1">Queueing Dashboard Overview</li>
                        </ol>
                    </div>
                </div>

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Queueing Dashboard Overview</h1>
                        <p class="text-muted small mb-0">Real-time counter overview, analytics, and live display board.</p>
                    </div>
                </div>

                <!-- Summary Analytics -->
                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <!-- SIMPLE LIVE QUEUE DISPLAY CARD -->
                        <div class="card card-animate" id="liveQueueWindow">
                            <div class="card-header pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="">
                                        {{-- <span class="spinner-grow spinner-grow-sm text-success me-2" role="status"></span> --}}
                                        <i class="fas fa-tv text-success me-2"></i>Live Queue Display
                                    </h6>
                                    <button class="btn btn-sm btn-outline-secondary" id="toggleZoomBtn" onclick="toggleZoomDisplay()">
                                        <i class="fas fa-expand me-1" id="zoomIcon"></i> <span id="zoomText">Zoom / Fullscreen</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Serving Counters Section -->
                                    <div class="col-md-9">
                                        <div class="row g-3" id="countersContainer">
                                            @forelse($counters as $counter)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="card h-100 shadow-sm rounded-3 overflow-hidden">
                                                        <!-- Window Title Header -->
                                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                                            <span class="fw-bold small text-truncate"><i class="ti ti-app-window"></i> {{ $counter->windowname }}</span>
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small">
                                                                <i class="fas fa-circle me-1 style-dot blink-dot"></i>Live
                                                            </span>
                                                        </div>

                                                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                                            <div>
                                                                <span class="badge bg-light text-secondary border fw-normal mb-2 text-truncate max-w-100">
                                                                    {{ $counter->category }}
                                                                </span>

                                                                <!-- Call ID Display Box -->
                                                                <div class="bg-light rounded-3 py-2 border border-1 my-1">
                                                                    <small class="text-uppercase text-warning fw-semibold d-block style-label">Serving No.</small>
                                                                    <h5 class="fw-bolder text-dark mb-0 lh-1 tracking-tight">
                                                                        {{ $customerTickets[$counter->currentid] ?? $customerTickets[$counter->callid] ?? '---' }}
                                                                    </h5>
                                                                </div>
                                                            </div>

                                                            <!-- Footer Metadata -->
                                                            <div class="pt-2 mt-2 border-top d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                                                                <span><i class="fas fa-building me-1 opacity-50"></i>{{ $counter->campus }}</span>
                                                                <span><i class="fas fa-user-circle me-1 opacity-50"></i>{{ $counter->user_info->lname ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-center py-4 text-muted">
                                                    <i class="fas fa-desktop mb-2 fs-3 d-block opacity-25"></i>
                                                    No active counters found.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Next in Line Section -->
                                    <div class="col-lg-3">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <h6 class="mb-3 border-bottom pb-2">Next In Line</h6>
                                                <div id="waitingListContainer">
                                                    @if(!empty($waitingCustomers) && count($waitingCustomers) > 0)
                                                        <ul class="list-group list-group-flush">
                                                            @foreach($waitingCustomers as $categoryName => $customer)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                                    <div>
                                                                        <strong class="text-dark">{{ $customer->queue_number }}</strong>
                                                                        <br><small class="text-muted">{{ $categoryName }}</small>
                                                                    </div>
                                                                    <span class="badge bg-warning text-dark">Next</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="text-center text-muted py-3">
                                                            No customers waiting
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <span class="text-muted small fw-bold text-uppercase">Total In Queue</span>
                                        <h3 class="fw-bold my-1" id="statWaiting">{{ $totalWaiting ?? 0 }}</h3>
                                        <small class="text-muted"><i class="fas fa-clock text-primary me-1"></i> Waiting</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <span class="text-muted small fw-bold text-uppercase">Currently Serving</span>
                                        <h3 class="fw-bold my-1" id="statServing">{{ $totalServing ?? 0 }}</h3>
                                        <small class="text-muted"><i class="fas fa-user-check text-success me-1"></i> At counters</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <span class="text-muted small fw-bold text-uppercase">Active Counters</span>
                                        <h3 class="fw-bold my-1" id="statCounters">{{ $counters->count() }}</h3>
                                        <small class="text-muted"><i class="fas fa-desktop text-info me-1"></i> Online</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <span class="text-muted small fw-bold text-uppercase">Completed Today</span>
                                        <h3 class="fw-bold my-1" id="statCompleted">{{ $totalCompleted ?? 0 }}</h3>
                                        <small class="text-muted"><i class="fas fa-check-circle text-secondary me-1"></i> Total served</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-animate">
                                    <div class="card-header">
                                        <h2 class="h6 fw-bold mb-0">Recent Queue Activity Log</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Counter</th>
                                                        <th>Category</th>
                                                        <th>Name</th>
                                                        <th>No. Catered</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="activityLogBody">
                                                    @forelse($counters as $counter)
                                                        <tr>
                                                            <td class="fw-bold text-primary">{{ $counter->windowname }}</td>
                                                            <td><span class="badge bg-light text-dark border">{{ $counter->category }}</span></td>
                                                            <td class="fw-semibold">{{ $counter->user_lname }}</td>
                                                            <td>
                                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                                                    {{ $counter->catered_today_count }} Served
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center text-muted py-4">No recent activity.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
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

    <script>
        function updateDashboard() {
            fetch("{{ route('queue.live-data') }}")
                .then(response => response.json())
                .then(data => {
                    // 1. Update Metrics
                    const setEl = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.innerText = val ?? 0;
                    };

                    setEl('statWaiting', data.totalWaiting);
                    setEl('statServing', data.totalServing);
                    setEl('statCounters', data.totalCounters);
                    setEl('statCompleted', data.totalCompleted);

                    // 2. Update Counter Cards
                    const countersContainer = document.getElementById('countersContainer');
                    if (countersContainer && Array.isArray(data.counters)) {
                        let countersHtml = '';
                        if (data.counters.length > 0) {
                            data.counters.forEach(counter => {
                                let ticket = (data.customerTickets && (data.customerTickets[counter.currentid] || data.customerTickets[counter.callid])) || '---';
                                let userName = counter.user_info ? counter.user_info.lname : (counter.user_lname ?? 'N/A');

                                countersHtml += `
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 shadow-sm rounded-3 overflow-hidden">
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                                <span class="fw-bold small text-truncate"><i class="ti ti-app-window"></i> ${counter.windowname ?? ''}</span>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small">
                                                    <i class="fas fa-circle me-1 style-dot blink-dot"></i>Live
                                                </span>
                                            </div>
                                            <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                                <div>
                                                    <span class="badge bg-light text-secondary border fw-normal mb-2 text-truncate max-w-100">
                                                        ${counter.category ?? ''}
                                                    </span>
                                                    <div class="bg-light rounded-3 py-2 border border-1 my-1">
                                                        <small class="text-uppercase text-warning fw-semibold d-block style-label">Serving No.</small>
                                                        <h5 class="fw-bolder text-dark mb-0 lh-1 tracking-tight">${ticket}</h5>
                                                    </div>
                                                </div>
                                                <div class="pt-2 mt-2 border-top d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                                                    <span><i class="fas fa-building me-1 opacity-50"></i>${counter.campus ?? ''}</span>
                                                    <span><i class="fas fa-user-circle me-1 opacity-50"></i> ${userName}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                            });
                        } else {
                            countersHtml = `
                                <div class="col-12 text-center py-4 text-muted">
                                    <i class="fas fa-desktop mb-2 fs-3 d-block opacity-25"></i>
                                    No active counters found.
                                </div>`;
                        }
                        countersContainer.innerHTML = countersHtml;
                    }

                    // 3. Update Waiting List
                    const waitingListContainer = document.getElementById('waitingListContainer');
                    if (waitingListContainer) {
                        let waitingHtml = '';
                        if (data.waitingCustomers && Object.keys(data.waitingCustomers).length > 0) {
                            waitingHtml += '<ul class="list-group list-group-flush">';
                            for (const [categoryName, customer] of Object.entries(data.waitingCustomers)) {
                                waitingHtml += `
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <div>
                                            <strong class="text-dark">${customer.queue_number}</strong>
                                            <br><small class="text-muted">${categoryName}</small>
                                        </div>
                                        <span class="badge bg-warning text-dark">Next</span>
                                    </li>`;
                            }
                            waitingHtml += '</ul>';
                        } else {
                            waitingHtml = '<div class="text-center text-muted py-3">No customers waiting</div>';
                        }
                        waitingListContainer.innerHTML = waitingHtml;
                    }

                    // 4. Update Recent Queue Activity Log Table
                    const activityLogBody = document.getElementById('activityLogBody') || document.getElementById('activityTableBody');
                    if (activityLogBody && Array.isArray(data.counters)) {
                        let activityHtml = '';

                        if (data.counters.length > 0) {
                            data.counters.forEach(counter => {

                                let staffName = counter.user_lname || (counter.user_info ? counter.user_info.lname : 'Unassigned');
                                let cateredCount = counter.catered_today_count ?? 0;

                                activityHtml += `
                                    <tr>
                                        <td class="fw-bold text-primary">${counter.windowname ?? ''}</td>
                                        <td><span class="badge bg-light text-dark border">${counter.category ?? ''}</span></td>
                                        <td class="fw-semibold">${staffName}</td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                                ${cateredCount} Served
                                            </span>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            activityHtml = '<tr><td colspan="5" class="text-center text-muted py-4">No recent activity.</td></tr>';
                        }

                        activityLogBody.innerHTML = activityHtml;
                    }
                })
                .catch(error => console.error('Error fetching live queue data:', error));
        }

        // Poll every 3 seconds (5000 ms)
        setInterval(updateDashboard, 5000);

        // Zoom Functions
        function toggleZoomDisplay() {
            const element = document.getElementById('liveQueueWindow');
            const icon = document.getElementById('zoomIcon');
            const text = document.getElementById('zoomText');

            if (!element) return;

            if (!document.fullscreenElement) {
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                }
                if (icon) icon.className = 'fas fa-compress me-1';
                if (text) text.innerText = 'Exit Zoom';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                if (icon) icon.className = 'fas fa-expand me-1';
                if (text) text.innerText = 'Zoom / Fullscreen';
            }
        }

        document.addEventListener('fullscreenchange', () => {
            const icon = document.getElementById('zoomIcon');
            const text = document.getElementById('zoomText');
            if (!document.fullscreenElement) {
                if (icon) icon.className = 'fas fa-expand me-1';
                if (text) text.innerText = 'Zoom / Fullscreen';
            }
        });
    </script>
@endsection
