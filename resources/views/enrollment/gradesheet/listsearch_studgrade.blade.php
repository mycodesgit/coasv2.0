@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style="background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Gradesheet</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Student Gradesheet</h1>
                        <p class="text-muted small mb-0">Encode and view student grades.</p>
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
                                <form method="GET" action="{{ route('studgrade_searchlist') }}" id="gradeSht">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">School Year : <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Semester : <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="semester">
                                                    <option disabled selected>Select</option>
                                                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>First Semester</option>
                                                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Second Semester</option>
                                                    <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Summer</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="btn btn-success btn-sm">OK</button>
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
                                    <i class="ti ti-server"></i> List of Subjects Offer Section
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    <table id="madapak" class="table table-striped" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject Name</th>
                                                <th>Descriptive Title</th>
                                                <th>Year&Section</th>
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

    <script>
        var studsubgradeoffered = @if(Auth::guard('web')->user()->role == 15)
            "{{ route('studgradegrad_searchlistajax') }}";
        @else
            "{{ route('studgrade_searchlistajax') }}";
        @endif

        var schlyear = "{{ request('schlyear') }}";
        var semester = "{{ request('semester') }}";
        var routeTemplate = "{{ route('geneStudent1', ['id' => ':id', 'schlyear' => ':schlyear', 'semester' => ':semester']) }}";
    </script>
@endsection
