@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Student Per Degree</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Per Degree</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <form method="GET" action="{{ route('studCurrsearch') }}" id="enrollStud">
                                            @csrf   

                                            <div class="form-group mt-2">
                                                <div class="row g-3">
                                                    @if(Auth::guard('web')->user()->role == '0' || Auth::guard('web')->user()->lname == 'Arlos')
                                                    <div class="col-md-2">
                                                        <label>Campus: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="campus" id="campus">
                                                            <option value="MC">Main</option>
                                                            <option value="SCC">San Carlos</option>
                                                            <option value="VC">Victorias</option>
                                                            <option value="HC">Hinigaran</option>
                                                            <option value="MP">Moises Padilla</option>
                                                            <option value="HinC">Hinobaan</option>
                                                            <option value="SC">Sipalay</option>
                                                            <option value="IC">Ilog</option>
                                                            <option value="CC">Cauayan</option>
                                                        </select>
                                                    </div>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <label>School Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="semester">
                                                            <option disabled selected>Select</option>
                                                            <option value="1">First Semester</option>
                                                            <option value="2">Second Semester</option>
                                                            <option value="3">Summer</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="table-responsive mt-3">
                                            <table id="courseEn" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th width="30%">Program Name</th>
                                                        <th>Acronym</th>
                                                        <th>Year&Section</th>
                                                        <th>No. of Stud</th>
                                                        <th>Male</th>
                                                        <th>Female</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @foreach($data as $claEn)
                                                        <tr>
                                                            <td>{{ $claEn->progCod }}</td>
                                                            <td>{{ $claEn->progName }}</td>
                                                            <td>{{ $claEn->progAcronym }}</td>
                                                            <td>{{ $claEn->studYear }}-{{ $claEn->studSec }}</td>
                                                            <td><strong>{{ $claEn->studentCount }}</strong></td>
                                                            <td>{{ $claEn->maleCount }}</td>
                                                            <td>{{ $claEn->femaleCount }}</td> 
                                                        </tr>
                                                    @endforeach --}}
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
        </div>
    </div>

    <div class="modal fade mt-6" id="viewStudEnrollModal" role="dialog" aria-labelledby="viewStudEnrollModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="text" name="id" id="viewStudEnrollId" hidden>
                    <h5 class="modal-title" id="viewStudEnrollModalLabel">Student Per Degree</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills bg-light p-2 rounded-2 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-list-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-list" type="button" role="tab"
                                aria-controls="pills-list" aria-selected="true">
                                Student List
                            </button>
                        </li>
                        &nbsp;
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-view-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-view" type="button" role="tab"
                                aria-controls="pills-view" aria-selected="false" tabindex="-1">
                                PDF View
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-1" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-list" role="tabpanel" aria-labelledby="pills-list-tab" tabindex="0">
                            <table class="table table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th>Student ID No.</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Semester</th>
                                    </tr>
                                </thead>
                                <tbody id="studentEnrolledTable">
                                    <!-- Enrollment history will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-view" role="tabpanel" aria-labelledby="pills-view-tab" tabindex="0">
                            <iframe id="pdfIframe" src="" style="width: 100%; height: 500px;" frameborder="0" class="mt-3"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var courseEnrollReadRoute = @if(Auth::guard('web')->user()->role == 15)
            "{{ route('getstudCurrSearchGradSchool') }}";
        @else
            "{{ route('getstudCurrSearch') }}";
        @endif
        var studentcourseEnrollReadRoute = "{{ route('fetchStudEnrollmentlist') }}";
        var studentcourseEnrollPDFReadRoute = "{{ route('exportEnrollmentPDF') }}";
    </script>
@endsection
