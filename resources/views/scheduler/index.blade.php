@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Class Scheduler
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Scheduler Dashboard Overview</h1>
                        <p class="text-muted small mb-0">Subject offering, plotting of student, faculty, room schedule</p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate mb-3">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $colCount }}</h3>
                                        <span>Colleges</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-buildings fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate mb-3">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $enunprogCount }}</h3>
                                        <span>Undergrad Programs</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-book fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate mb-3">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $engradprogCount }}</h3>
                                        <span>Graduates Programs</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-books fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate mb-3">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $roomCount }}</h3>
                                        <span>Rooms</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-route-square fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-9">
                        <div class="card card-animate p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">Recent Schedule plotted</h6>
                                    <small class="text-muted">for A.Y. {{ $acadyear }} - {{ $acadsem == 1 ? '1st Sem' : ($acadsem == 2 ? '2nd Sem' : ($acadsem == 3 ? 'Summer' : $acadsem)) }} (Year {{ $currentYear }} )</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="rcntschd" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Program Yr&Sec</th>
                                                <th>Faculty</th>
                                                <th>Room</th>
                                                <th>Sched</th>
                                                <th>PostedBy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Dynamic Calendar Widget -->
                    <div class="col-md-3">
                        <div class="card card-animate p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="ti ti-calendar me-1"></i> Calendar</h6>
                                <small class="text-muted fw-semibold" id="calendarMonthYear"></small>
                            </div>
                            <div class="shadcn-calendar">
                                <div class="row g-1 text-center text-muted small fw-semibold mb-2">
                                    <div class="col">Su</div><div class="col">Mo</div><div class="col">Tu</div>
                                    <div class="col">We</div><div class="col">Th</div><div class="col">Fr</div><div class="col">Sa</div>
                                </div>
                                <div id="calendarDaysContainer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
