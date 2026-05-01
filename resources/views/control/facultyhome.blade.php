@extends('layouts.master_faculty')

@section('title')
    CISS V.1.0 || Faculty Dashboard
@endsection

@section('workspace')
    <style>
        .blink-status {
        font-size: 12px;
        animation: blinkPulse 1.2s infinite;
    }

    @keyframes blinkPulse {
        0% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: .3;
            transform: scale(1.3);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    #calendar {
        font-family: 'Poppins', sans-serif;
    }

    /* Main container */
    .fc {
        background: transparent;
        border: none;
    }

    /* Remove all borders */
    .fc-theme-standard td,
    .fc-theme-standard th,
    .fc-scrollgrid {
        border: none !important;
    }

    /* Header */
    .fc-toolbar {
        margin-bottom: 15px !important;
        align-items: center;
    }

    .fc-toolbar-title {
        font-size: 18px !important;
        font-weight: 700;
        color: #2e3440;
    }

    /* Arrows */
    .fc-button {
        background: transparent !important;
        border: none !important;
        color: #8c94a6 !important;
        box-shadow: none !important;
        padding: 4px !important;
    }

    .fc-button:hover {
        background: #f5f5f5 !important;
        border-radius: 50%;
    }

    .fc-icon {
        font-size: 18px !important;
    }

    /* Weekday */
    .fc-col-header-cell {
        padding: 8px 0;
    }

    .fc-col-header-cell-cushion {
        font-size: 13px;
        color: #6c757d;
        text-decoration: none !important;
        font-weight: 600;
    }

    /* Dates */
    .fc-daygrid-day {
        height: 42px !important;
    }

    .fc-daygrid-day-number {
        text-decoration: none !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        margin: 0 auto;
        border-radius: 50%;
        font-size: 14px;
        padding: 0 !important;
        transition: 0.3s;
    }

    /* Hover */
    .fc-daygrid-day-number:hover {
        background: none;
    }

    /* Today / active */
    .fc-day-today {
        background: transparent !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        background: #65ac86 !important;
        color: white !important;
        font-weight: 600;
    }

    /* Remove event line */
    .fc-daygrid-day-events {
        display: none;
    }

    /* More spacing */
    .fc-daygrid-body-natural .fc-daygrid-day-events {
        margin-bottom: 0;
    }
    </style>
    
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1>

                <div class="row g-4 mb-5">
                    <!-- Left Main Content -->
                    <div class="col-lg-9">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="card card-animate border-0 shadow-sm rounded-3 text-white h-100"
                                    style="background: linear-gradient(135deg, #65ac86, #58886e);">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-4">
                                                <div class="bg-white rounded-4 p-3 opacity-75">
                                                    <i class="ti ti-user text-success fs-1"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <small>Welcome Back</small>
                                                <h2 class="fw-bold mb-2">
                                                    @auth('faculty')
                                                        {{ Auth::guard('faculty')->user()->fname }}
                                                        {{ Auth::guard('faculty')->user()->lname }}
                                                    @endauth
                                                </h2>
                                                <p class="mb-3 opacity-75">
                                                    Welcome, here you can quickly access your class attendance, class schedules, and Submission of student grades. Everything you need to streamline your academic tasks is right here.
                                                </p>
                                                <button class="btn btn-light btn-sm text-primary rounded-pill px-4 opacity-75">
                                                    <i class="ti ti-circle-dot-filled text-success blink-status"></i> Faculty
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="card card-animate border-1 rounded-3 p-3 bg-light">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <small>Enrollment Status:</small>
                                                    <h6 class="mt-2">
                                                        {{ $enrolledStatus->statusenroll == 'On' ? 'Open' : 'Close' }}
                                                    </h6>
                                                </div>
                                                <i class="ti ti-device-laptop text-success fs-3"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-animate border-1 rounded-3 p-3 bg-light">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <small>Faculty Evaluation:</small>
                                                    <h6 class="mt-2">
                                                        {{ $faculevalStatus->statuseval == 'On' ? 'Open' : 'Close' }}
                                                    </h6>
                                                </div>
                                                <i class="ti ti-target text-success fs-3"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-animate border-1 rounded-3 p-3 bg-light">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <small>Submission of Grades:</small>
                                                    <h6 class="mt-2">On</h6>
                                                </div>
                                                <i class="ti ti-numbers text-success fs-3"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-animate border-1 rounded-3 p-3 bg-light">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <small>Your Overall Students</small>
                                                    <h6 class="mt-2">...</h6>
                                                </div>
                                                <i class="ti ti-users text-success fs-3"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-lg-7">
                                <div class="card card-animate border-1 rounded-3">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3">No. of Students</h5>
                                        <div class="chart-responsive pt-1">
                                            <canvas id="currSemesterunderprogBarChart" style="height:330px; min-height:330px"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card card-animate border-1 rounded-3">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3">Subjects</h5>
                                        <table id="countstuddash" class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Students</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right Sidebar -->
                    <div class="col-lg-3">
                        <div class="card card-animate border-1 rounded-3 mb-4">
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>

                        <div class="card card-animate border-1 rounded-3">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Announcements</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">...</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var studCountRoute = "{{ route('dashcountstud') }}";
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let calendarEl = document.getElementById('calendar');

            let calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap5',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'prev,next'
                },
                height: 320,
                fixedWeekCount: false,
                showNonCurrentDates: true,
                dayHeaderFormat: { weekday: 'short' },
                selectable: true,
                dayMaxEvents: false
            });

            calendar.render();
        });
    </script>
@endsection