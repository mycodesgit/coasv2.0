@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Class Scheduler
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
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Class Schedule</li>
                        </ol>
                    </div>
                </div>
                <!-- Dashboard Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1">Class Schedule</h1>
                        <p class="text-muted small mb-0">Manage plotting of Student Class Schedule every academic year & semester.</p>
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
                                <form method="GET" action="{{ route('classSchedSetRead') }}" id="classsched">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Academic Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear" id="schlyear1">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control  form-control-sm" name="semester" id="semester">
                                                    <option disabled selected>---Select---</option>
                                                    <option value="1">First Semester</option>
                                                    <option value="2">Second Semester</option>
                                                    <option value="3">Summer</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Course: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2bs4" name="progCod" id="progCod">
                                                    <option disabled selected>Select a course</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="btn btn-success btn-sm">Search</button>
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

    <script>
        var classenrollyrsecReadRoute = "{{ route('getCoursesyearsec') }}";
    </script>
@endsection
