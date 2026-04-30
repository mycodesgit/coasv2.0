@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Accepted Applicant
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">Accepted Applicants</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search Accepted Applicants
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('accepted.store') }}">
                                    @csrf

                                    <div class="form-group mt-1">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label>Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" id="year" name="year">
                                                    @foreach($curryear as $datacurryear)
                                                        <option>{{ $datacurryear->adyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label>Strand: <span class="text-danger">*</span></label>
                                                <select class="form-control  form-control-sm" name="strand">
                                                    <option value=""> --Select-- </option>
                                                    @foreach($strand as $datastrand)
                                                        <option value="{{ $datastrand->code }}">{{ $datastrand->strand }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <hr>

                                <div class="table-responsive mt-3 p-2">
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
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i>  Yes!, Push to Registrar for Enrollment</button>
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
        var allAppAcceptedRoute = "{{ route('accepted.show') }}";
        var appidEncryptRoute = "{{ route('idFacCrypt') }}";
        var pushtoEnrollmentRoute = "{{ route('save_enroll_applicant',  ['id' => ':id']) }}";

        var isCampus = "{{ Auth::guard('faculty')->user()->campus }}";
        var requestedCampus = "{{ Auth::guard('faculty')->user()->campus }}";
    </script>
@endsection
