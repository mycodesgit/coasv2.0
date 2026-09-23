@extends('layouts.master_scholarship')

@section('title')
CISS V.1.0 || Scholarship
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
                            <li class="breadcrumb-item mt-1">Scholarship</li>
                            <li class="breadcrumb-item active mt-1">Student Scholarship</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Students Scholarhip</h1>
                        <p class="text-muted small mb-0">Manage and assign student scholarship.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('studscholar_searchRead') }}" id="studscholar">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Academic Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
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
                                </form>
                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-list"></i> List of enrolled students
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    <table id="schstud" class="table table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>StudID</th>
                                                <th>Course</th>
                                                <th>Scholarhip</th>
                                                <th>Sponsor</th>
                                                <th>CHEDCategory</th>
                                                <th>CPSUCategory</th>
                                                <th>Tuition</th>
                                                <th>#</th>
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

    <div class="modal fade mt-6" id="editstudSchEnModal" role="dialog" aria-labelledby="editstudSchEnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editstudSchEnModalLabel"><i class="ti ti-pencil"></i> Update Student Scholarship</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editstudSchEnForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editstudSchEnId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editstudSchEnStudID">Student ID No.: <span class="text-danger">*</span></label>
                                <input type="text" id="editstudSchEnStudID" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editstudSchEnStudName">Student Name: <span class="text-danger">*</span></label>
                                <input type="text" id="editstudSchEnStudName" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editstudSchEnSch">Scholarship: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="studSch" id="editstudSchEnSch">
                                    <option disabled selected>--Select--</option>
                                    @foreach($studsch as $datasch)
                                        <option value="{{ $datasch->id }}">{{ $datasch->scholar_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                        <button type="button" class="btn btn-success"><i class="ti ti-device-floppy"></i>  Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var studschReadRoute = "{{ route('getstudscholarSearchRead') }}";
        var studschUpdateRoute = "{{ route('studscholarUpdate', ['id' => ':id']) }}";
        var idStudSchEncryptRoute = "{{ route('idcrypt') }}";
    </script>
@endsection
