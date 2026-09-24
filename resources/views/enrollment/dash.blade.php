@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
@endsection

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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Enrollment Dashboard Overview</h1>
                        <p class="text-muted small mb-0">System metrics, student enrollment data and daily activity logs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    @if(Auth::guard('web')->check() && in_array(Auth::guard('web')->user()->role, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16, 17, 18, 19, 20]))
                        <div class="col-lg-3 col-12">
                            <div class="card card-animate">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $enrlstudcountfirst }}</h3>
                                            <span>1st Stud. Enrolled this Sem</span>
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
                                            <span>2nd Stud. Enrolled this Sem</span>
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
                                            <span>3rd Stud. Enrolled this Sem</span>
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
                                            <span>4th Stud. Enrolled this Sem</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-users fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="card card-animate" data-bs-toggle="modal" data-bs-target="#transfereeModal">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $enrlstudcountTransferee }}</h3>
                                            <span>Transferee Stud. Enrolled this Sem</span>
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
                                            <h3 class="fw-bold h1">{{ $enrlstudcountShiftee }}</h3>
                                            <span>Shiftee Stud. Enrolled this Sem</span>
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
                                            <h3 class="fw-bold h1">{{ $enrlstudcountContinuing }}</h3>
                                            <span>Continuing Stud. Enrolled this Sem</span>
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
                                    <div class="d-flex justify-content-between pb-2" data-bs-toggle="modal" data-bs-target="#returneeModal">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $enrlstudcountReturning }}</h3>
                                            <span>Returnee Stud. Enrolled this Sem</span>
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

                        <div class="col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="chart-responsive pt-1">
                                        <canvas id="prevSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="chart-responsive pt-1">
                                        <canvas id="currSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="chart-responsive pt-1">
                                        <canvas id="currSemesterunderprogBarChart" style="height:330px; min-height:330px"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card card-animate">
                                <div class="card-header pt-3">
                                    <h6 class="card-title">Enrollment for this current Semester in All Campuses</h6>
                                </div>
                                <div class="card-body">
                                    <div class="position-relative mb-4">
                                        <canvas id="enrlmntpercamp-chart"
                                                data-main="{!! $MainEnrollmentCount !!}"
                                                data-victorias="{!! $VcEnrollmentCount !!}"
                                                data-sancarlos="{!! $SccEnrollmentCount !!}"
                                                data-hinigaran="{!! $HcEnrollmentCount !!}"
                                                data-moises="{!! $MpEnrollmentCount !!}"
                                                data-ilog="{!! $IcEnrollmentCount !!}"
                                                data-candoni="{!! $CaEnrollmentCount !!}"
                                                data-cauayan="{!! $CcEnrollmentCount !!}"
                                                data-siplay="{!! $ScEnrollmentCount !!}"
                                                data-hinobaan="{!! $HinCEnrollmentCount !!}"
                                                height="200">
                                        </canvas>
                                    </div>
                                    <div class="d-flex flex-row justify-content-end">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-6">
                            <div class="card card-animate mb-3" data-bs-toggle="modal" data-bs-target="#regularModal">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $enrlstudRegularcount }}</h3>
                                            <span>Regular Students Enrolled this Sem</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-users fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-6">
                            <div class="card card-animate mb-3" data-bs-toggle="modal" data-bs-target="#irregularModal">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $enrlstudIrregularcount }}</h3>
                                            <span>Irregular Students Enrolled this Sem</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-users fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::guard('web')->user()->role == 15)
                        <div class="col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="chart-responsive pt-1">
                                        <canvas id="prevSemestergradBarChart" style="height:330px; min-height:330px"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="chart-responsive pt-1">
                                        <canvas id="currSemestergradBarChart" style="height:330px; min-height:330px"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="chart-responsive pt-1">
                                        <canvas id="currSemestergradprogBarChart" style="height:330px; min-height:330px"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Modal -->
                    @if(Auth::guard('web')->user()->campus != 'MC')
                    <div class="modal fade mt-6" id="regularModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Regular Students List</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe id="regularPdfFrame" src="{{ route('regular.students.pdf') }}" width="100%" height="600px" frameborder="0"></iframe>
                                    <div id="loadingText" class="text-center" style="display:none;">
                                        <p>Loading PDF, please wait...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(Auth::guard('web')->check() && in_array(Auth::guard('web')->user()->role, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10, 11, 12, 13, 14, 16, 17, 18, 19, 20]))
                    <div class="modal fade mt-6" id="irregularModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Irregular Students List</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- <iframe src="{{ route('irregular.students.pdf') }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                                    <iframe id="irregularPdfFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
                                    <div id="loadingText" class="text-center" style="display:none;">
                                        <p>Loading PDF, please wait...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(Auth::guard('web')->check() && in_array(Auth::guard('web')->user()->role, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10, 11, 12, 13, 14, 16, 17, 18, 19, 20]))
                    <div class="modal fade mt-6" id="transfereeModal" tabindex="-1" role="dialog" aria-labelledby="transfereeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Transferee Students List</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- <iframe src="{{ route('irregular.students.pdf') }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                                    <iframe id="transfereePdfFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
                                    <div id="loadingText" class="text-center" style="display:none;">
                                        <p>Loading PDF, please wait...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(Auth::guard('web')->check() && in_array(Auth::guard('web')->user()->role, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10, 11, 12, 13, 14, 16, 17, 18, 19, 20]))
                    <div class="modal fade mt-6" id="returneeModal" tabindex="-1" role="dialog" aria-labelledby="returneeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Returnee Students List</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- <iframe src="{{ route('irregular.students.pdf') }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                                    <iframe id="returneePdfFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
                                    <div id="loadingText" class="text-center" style="display:none;">
                                        <p>Loading PDF, please wait...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
