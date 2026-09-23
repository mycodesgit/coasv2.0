@extends('layouts.master_assessment')

@section('title')
CISS V.1.0 || Assessment
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
                            <li class="breadcrumb-item mt-1">Assessment</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Assessment Dashboard Overview</h1>
                        <p class="text-muted small mb-0">Student fees, accounts, and billings every academic year.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $enrlstudcountfirst }}</h3>
                                        <span>1st Stud Enrolled this Sem</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $enrlstudcountsecond }}</h3>
                                        <span>2nd Stud Enrolled this Sem</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $enrlstudcountthird }}</h3>
                                        <span>3rd Stud Enrolled this Sem</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $enrlstudcountfourth }}</h3>
                                        <span>4th Stud Enrolled this Sem</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="chart-responsive pt-1">
                                    <canvas id="firstSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="chart-responsive pt-1">
                                    <canvas id="secondSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">List of Degrees with Encoded Appraisals</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="setconfUpEncodedtable" class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Degree</th>
                                                <th>Schlyear</th>
                                                <th>Semester</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($encod as $dataencod)
                                                <tr>
                                                    <td>{{ $dataencod->schlyear }}</td>
                                                    <td>{{ $dataencod->semester }}</td>
                                                    <td>{{ $dataencod->progAcronym }} {{ $dataencod->classSection }}</td>
                                                    <td>{{ $dataencod->schlyear }}</td>
                                                </tr>
                                            @endforeach --}}
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
@endsection
