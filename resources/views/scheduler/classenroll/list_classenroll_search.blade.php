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
                            <li class="breadcrumb-item active mt-1">Class Enrolled</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Classes Enrolled</h1>
                        <p class="text-muted small mb-0">Manage Classes Enrolled & Sections</p>
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
                                <form method="GET" action="{{ route('courseEnroll_list_search') }}" id="classEnroll">
                                    @csrf

                                    <div class="mt-2">
                                        <div class="form-group">
                                            <div class="row g-3">
                                                <div class="col-md-2">
                                                    <label class="form-label fw-semibold">Academic Year: <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="schlyear">
                                                        @foreach($sy as $datasy)
                                                            <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear || ($loop->first && !request('schlyear')) ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                    <select class="form-control  form-control-sm" name="semester">
                                                        <option disabled selected>---Select---</option>
                                                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>First Semester</option>
                                                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Second Semester</option>
                                                        <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Summer</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="d-flex flex-column h-100">
                                                        <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-plus"></i> Add New
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{ route('classEnrollCreate') }}"  id="classEnrollAdd">
                                    @csrf

                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Academic Year</label>
                                                <input type="text" name="schlyear" class="form-control  form-control-sm" value="{{ request('schlyear') }}" readonly>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Semester</label>
                                                <input type="text" name="semester" class="form-control form-control-sm" value="{{ request('semester') }}" readonly>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Programs</label>
                                                <select class="form-control form-control-sm" name="progCode" id="">
                                                    <option disabled selected>Select</option>
                                                    @foreach ($program as $programs)
                                                        <option value="{{ $programs->progCod }}">
                                                            {{ $programs->progAcronym }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Year & Section</label>
                                                <input type="text" name="classSection" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" placeholder="e.g., 1-A">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Est. Number of Student</label>
                                                <input type="number" name="classno" class="form-control form-control-sm" min="0">
                                            </div>

                                            @if(Auth::guard('web')->user()->lname == 'Gargoles' || Auth::guard('web')->user()->lname == 'Level')
                                                <div class="col-md-12">
                                                    <label class="form-label fw-semibold">Add On</label>
                                                    <select class="form-control form-control-sm" name="progType" id="">
                                                        <option disabled selected>Select</option>
                                                        <option value="SIKAT - CAMI">SIKAT - CAMI</option>
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="col-md-12">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> List of Classes Enrolled
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    <table id="classenroll" class="table table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Program</th>
                                                <th>Year&Section</th>
                                                <th>Est. No. of Student</th>
                                                <th width="10%">Action</th>
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
    </div>

    <div class="modal fade mt-6" id="editClassEnModal" tabindex="-1" role="dialog" aria-labelledby="editStudFeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel"><i class="ti ti-pencil"></i> Edit Class Enroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editClassEnForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editclassenId">
                        <div class="form-group">
                            <label class="form-label fw-semibold" for="editclassen">Program: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="editclassen" name="progCode">
                                <option disabled selected>Select</option>
                                @foreach ($program as $programs)
                                    <option value="{{ $programs->progCod }}">
                                        {{ $programs->progAcronym }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label fw-semibold" for="editsection">Year & Section: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editsection" name="classSection">
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label fw-semibold" for="editclassno">Est. No. of Student: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="editclassno" name="classno">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i> Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var classEnReadRoute = "{{ Auth::guard('web')->user()->role == 15 ? route('getGradclassEnRead') : route('getclassEnRead') }}";
        var classEnCreateRoute = "{{ route('classEnrollCreate') }}";
        var classEnUpdateRoute = "{{ route('classEnrolledUpdate', ['id' => ':id']) }}";
        var classEnDeleteRoute = "{{ route('classEnrolledDelete', ['id' => ':id']) }}";
    </script>
@endsection
