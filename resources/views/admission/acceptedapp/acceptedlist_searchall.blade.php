@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Admission
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
                            <li class="breadcrumb-item mt-1">Admission</li>
                            <li class="breadcrumb-item active mt-1">Accepted Applicantss</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Accepted Applicants</h1>
                        <p class="text-muted small mb-0">View, manage, and process accepted applicants.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('srchacceptedList') }}">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" id="year" name="year">
                                                    @foreach($curryear as $datacurryear)
                                                        <option>{{ $datacurryear->adyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="campus" id="campus">
                                                    <option value="{{Auth::user()->campus}}">
                                                        @if (Auth::user()->campus == 'MC') Main
                                                            @elseif(Auth::user()->campus == 'VC') Victorias
                                                            @elseif(Auth::user()->campus == 'SCC') San Carlos
                                                            @elseif(Auth::user()->campus == 'HC') Hinigaran
                                                            @elseif(Auth::user()->campus == 'MP') Moises Padilla
                                                            @elseif(Auth::user()->campus == 'IC') Ilog
                                                            @elseif(Auth::user()->campus == 'CA') Candoni
                                                            @elseif(Auth::user()->campus == 'CC') Cauayan
                                                            @elseif(Auth::user()->campus == 'SC') Sipalay
                                                            @elseif(Auth::user()->campus == 'HinC') Hinobaan
                                                            @elseif(Auth::user()->campus == 'VE') Valladolid
                                                        @endif
                                                    </option>
                                                    @if(Auth::user()->role == 0 || (Auth::user()->campus == 'MC' && Auth::user()->role == 1))
                                                        <option value="MC">Main</option>
                                                        <option value="VC">Victorias</option>
                                                        <option value="SCC">San Carlos</option>
                                                        <option value="HC">Hinigaran</option>
                                                        <option value="MP">Moises Padilla</option>
                                                        <option value="IC">Ilog</option>
                                                        <option value="CA">Candoni</option>
                                                        <option value="CC">Cauayan</option>
                                                        <option value="SC">Sipalay</option>
                                                        <option value="HinC">Hinobaan</option>
                                                        <option value="VE">Valladolid</option>
                                                    @else
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Strand: <span class="text-danger">*</span></label>
                                                <select class="form-control  form-control-sm" name="strand">
                                                    <option value=""> --Select-- </option>
                                                    @foreach($strand as $datastrand)
                                                        <option value="{{ $datastrand->code }}">{{ $datastrand->strand }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-users"></i> List of Accepted Applicants Section
                                </h6>
                            </div>
                            <div class="card-body">
                                <table id="acceptedTableapp" class="table table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>App ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Interviewer</th>
                                            <th>Approved Course</th>
                                            <th>Stud ID No.</th>
                                            <th>Status</th>
                                            <th>Campus</th>
                                            <th>Strand</th>
                                            <th id="actionColumnHeader" style="display: none;">Action</th>
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

    <div class="modal fade" id="pushtoEnrollmentModal" role="dialog" aria-labelledby="pushtoEnrollmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pushtoEnrollmentModalLabel">Are you sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pushtoEnrollmentForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="pushtoEnrollmentId">
                        <div class="form-group">
                            <center>
                                <h3>Push the Accepted Applicant to the Registrar for Enrollment</h3>
                                <br>
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>  Yes!, Push to Registrar for Enrollment</button>
                            </center>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var viewallAppAcceptedRoute = "{{ route('getsrchacceptedListappAll') }}";
        var appidEncryptRoute = "{{ route('idcrypt') }}";
        var pushtoEnrollmentRoute = "{{ route('save_enroll_applicant',  ['id' => ':id']) }}";

        var isCampus = "{{ Auth::guard('web')->user()->campus }}";
        var isAdmin = "{{ Auth::guard('web')->user()->role == 0 }}";
        var requestedCampus = "{{ request('campus') }}";
    </script>
@endsection
