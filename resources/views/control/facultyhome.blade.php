@extends('layouts.master_faculty')

@section('title')
    CISS V.1.0 || Faculty Dashboard
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1>

                <div class="card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2 mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-md bg-success text-white rounded-2 d-none d-md-block">
                                <i class="ti ti-user fs-4"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fs-2">Welcome back,
                                    @auth('faculty')
                                        @if(Auth::guard('faculty')->user()->role == '943')
                                            {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                                        @endif
                                    @endauth  !
                                </h1>
                                <p class="text-secondary mb-0 small">
                                    Welcome, here you can quickly access your class attendance, class schedules, and stay updated with important announcements. Everything you need to streamline your academic tasks is right here.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-lg-4 col-12">
                        <div class="card card-animate p-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-2 h-100">
                            <div class="d-flex gap-3">
                                <div class="icon-shape icon-md bg-success text-white rounded-2 p-2">
                                    <i class="ti ti-eye fs-4"></i>
                                </div>
                                <div>
                                    <h1 class="mb-2 fs-3">Vision</h1>
                                    <h6 class="fw-normal mb-0">CPSU as the leading technology-driven multi-disciplinary University by 2030.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="card card-animate p-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-2 h-100">
                            <div class="d-flex gap-3">
                                <div class="icon-shape icon-md bg-success text-white rounded-2 p-2">
                                    <i class="ti ti-eye fs-4"></i>
                                </div>
                                <div>
                                    <h1 class="mb-2 fs-3">Mission</h1>
                                    <h6 class="fw-normal mb-0">CPSU is committed to produce competent graduates who can generate and extend technologies in multi-disciplinary areas beneficial to the community.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="card card-animate p-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-2 h-100">
                            <div class="d-flex gap-3">
                                <div class="icon-shape icon-md bg-success text-white rounded-2 p-2">
                                    <i class="ti ti-eye fs-4"></i>
                                </div>
                                <div>
                                    <h1 class="mb-2 fs-3">Goal</h1>
                                    <h6 class="fw-normal mb-0">To provide efficient, quality, technology-driven and Gender-sensitive Products and Services.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="chart-responsive pt-1">
                                    <canvas id="currSemesterunderprogBarChart" style="height:330px; min-height:330px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-animate">
                            <div class="card-body">
                                <table id="countstuddash" class="table table-striped styled-table" style="font-size: 10pt; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>No. of Stud</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
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
@endsection