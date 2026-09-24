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
                            <li class="breadcrumb-item active mt-1">Colleges</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Colleges</h1>
                        <p class="text-muted small mb-0">Manage colleges</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> List of Colleges Section
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    <table id="collegeProg" class="table table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Acronym</th>
                                                <th>College Name</th>
                                                <th>Campus</th>
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

    <div class="modal fade mt-6" id="editCollegeModal" tabindex="-1" role="dialog" aria-labelledby="editCollegeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCollegeModalLabel"><i class="ti ti-pencil"></i> Edit College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCollegeForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editCollegeId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editCollegeAbbr">Acronym: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editCollegeAbbr" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editCollegeName">College Name: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editCollegeName" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editCampAbbr">Belongs to: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editCampAbbr" name="campus" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editCollegeName">Campus: <span class="text-danger">*</span></label>
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
                                    <option value="VE">Valladolid</option>
                                </select>
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
        var collegeReadRoute = "{{ route('getcollegeRead') }}";
        var collegeUpdateRoute = "{{ route('collegeUpdate', ['id' => ':id']) }}";
        var idEncryptRoute = "{{ route('idcrypt') }}";
    </script>
@endsection
