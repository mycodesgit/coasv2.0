@extends('layouts.master_enrollment')

@section('title')
COAS - V2.0 || Student List per Curriculum
@endsection

@section('sideheader')
<h4>Enrollment</h4>
@endsection

@yield('sidemenu')

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Student List per Curriculum</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <h4>Student List per Curriculum</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="" id="enrollStud">
                        @csrf   

                        <div class="form-group mt-2" style="padding: 10px">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">School Year</span></label>
                                    <select class="form-control form-control-sm" name="schlyear">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Semester</span></label>
                                    <select class="form-control form-control-sm" name="semester">
                                        <option disabled selected>Select</option>
                                        <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>First Semester</option>
                                        <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Second Semester</option>
                                        <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Summer</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-12 mt-3">
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

<div class="modal fade" id="viewStudEnrollModal" role="dialog" aria-labelledby="viewStudEnrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" name="id" id="viewStudEnrollId" hidden>
                <h5 class="modal-title" id="viewStudEnrollModalLabel">Students Enroll</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="student-list-tab" data-toggle="tab" href="#student-list" role="tab" aria-controls="student-list" aria-selected="true">Student List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pdf-view-tab" data-toggle="tab" href="#pdf-view" role="tab" aria-controls="pdf-view" aria-selected="false">PDF View</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="student-list" role="tabpanel" aria-labelledby="student-list-tab">
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
                    <div class="tab-pane fade" id="pdf-view" role="tabpanel" aria-labelledby="pdf-view-tab">
                        <iframe id="pdfIframe" src="" style="width: 100%; height: 500px;" frameborder="0" class="mt-3"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var courseEnrollReadRoute  = "{{ route('getstudCurrSearch') }}";
    var studentcourseEnrollReadRoute = "{{ route('fetchStudEnrollmentlist') }}";
    var studentcourseEnrollPDFReadRoute = "{{ route('exportEnrollmentPDF') }}";
</script>

@endsection
