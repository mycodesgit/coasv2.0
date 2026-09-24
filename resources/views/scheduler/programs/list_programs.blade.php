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
                            <li class="breadcrumb-item active mt-1">Programs</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Programs</h1>
                        <p class="text-muted small mb-0">Manage Programs</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> List of Programs Section
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    <table id="classProg" class="table table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Program Code</th>
                                                <th>Program Acronym</th>
                                                <th>Program Name</th>
                                                <th>Campus</th>
                                                <th>Action</th>
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

    <div class="modal fade mt-6" id="editProgramModal" tabindex="-1" role="dialog" aria-labelledby="editProgramModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProgramModalLabel"><i class="ti ti-pencil"></i> Edit Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProgramForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editProgramId">

                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">College: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="college" name="progCollege">
                                        <option disabled selected>--Select--</option>
                                        @foreach($col as $datacol)
                                            <option value="{{ $datacol->college_abbr }}">{{ $datacol->college_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Department: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="department" name="progDep">
                                        <option disabled selected>--Select--</option>
                                        @foreach($dept as $datadept)
                                            <option value="{{ $datadept->deptCod }}">{{ $datadept->deptName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Program Code: <span class="text-danger">*</span></label>
                                    <input type="text" id="editprogCod" name="progCod" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Program Account: <span class="text-danger">*</span></label>
                                    <input type="text" id="progaccount" name="progAccount" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold"><span class="badge badge-warning">Progam Name:</span></label>
                                    <input type="text" id="progName" name="progName" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold"><span class="badge badge-danger">Progam Acronym:</span></label>
                                    <input type="text" id="progAcronym" name="progAcronym" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Program Level: <span class="text-danger">*</span></label>
                                    <select name="progLev" id="editprogLev" class="form-control form-control-sm">
                                        <option disabled selected> --Select-- </option>
                                        @foreach ($lev as $datalev)
                                            <option value="{{ $datalev->id }}">{{ $datalev->studLevel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold" for="editCampAbbr">Belongs to: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="editCampAbbr" name="campus" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm select2" multiple="multiple" name="campus[]">
                                        <option value="MC">Main</option>
                                        <option value="VC">Victorias</option>
                                        <option value="SCC">San Carlos</option>
                                        <option value="MP">Moises Padilla</option>
                                        <option value="HC">Hinigaran</option>
                                        <option value="IC">Ilog</option>
                                        <option value="CA">Candoni</option>
                                        <option value="CC">Cauayan</option>
                                        <option value="SC">Sipalay</option>
                                        <option value="HinC">Hinobaan</option>
                                    </select>
                                </div>
                            </div>
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
        var progReadRoute = "{{ route('getprogramsRead') }}";
        var progDeptRoute = "{{ route('getDepartmentsByCollege') }}";
        var progCodeRoute = "{{ route('getNextProgramNumber') }}";
        var progUpdateRoute = "{{ route('programUpdate', ['id' => ':id']) }}";
        var idEncryptRoute = "{{ route('idcrypt') }}";
    </script>
@endsection
