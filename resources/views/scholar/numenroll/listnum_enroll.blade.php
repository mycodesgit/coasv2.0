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
                            <li class="breadcrumb-item active mt-1">Number of Enrollees</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Number of Enrollees</h1>
                        <p class="text-muted small mb-0">Search to show number of student enrollees every academic year.</p>
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
                                <form id="enrollStud" method="POST" action="{{ route('studnoenrollee_searchList') }}">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">School Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear" id="schlyear1">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="semester" id="semester">
                                                    <option disabled selected>Select</option>
                                                    <option value="1">First Semester</option>
                                                    <option value="2">Second Semester</option>
                                                    <option value="3">Summer</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" id="submitForm" class="btn btn-success btn-sm btn-block">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="resultModal" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Number of Enrolled Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-unbordered mt-1">
                        <li class="list-group-item">
                            <span id="studentCountFirst"></span>
                        </li>
                        <li class="list-group-item">
                            <span id="studentCountSecond"></span>
                        </li>
                        <li class="list-group-item">
                            <span id="studentCountThird"></span>
                        </li>
                        <li class="list-group-item">
                            <span id="studentCountFourth"></span>
                        </li>
                        <li class="list-group-item">
                            <span id="studentCount"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
