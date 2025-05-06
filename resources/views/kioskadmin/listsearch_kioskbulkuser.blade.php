@extends('layouts.master_adminkiosk')

@section('title')
CISS V.1.0 || Kiosk User
@endsection

@section('sideheader')
<h4>Kiosk Admin</h4>
@endsection

@yield('sidemenu')

@section('workspace')

<style>
    .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
        opacity: 1;
    }
</style>
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Kiosk Admin</li>
            <li class="breadcrumb-item active mt-1">Bulk Generation Kiosk Account</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="page-header mt-3">
            <div class="col-md-12">
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
                        <a class="nav-link active text-dark text-bold" id="student-list-tab" data-toggle="tab" href="#student-list" role="tab" aria-controls="student-list" aria-selected="true">Student List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark text-bold" id="pdf-view-tab" data-toggle="tab" href="#pdf-view" role="tab" aria-controls="pdf-view" aria-selected="false">PDF View</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="student-list" role="tabpanel" aria-labelledby="student-list-tab">
                        <button id="generateAllPasswords" class="btn btn-primary mt-3">Generate All Passwords</button>
                        <button id="saveAllPasswords" class="btn btn-success mt-3">Save All Passwords</button>
                        <table class="table table-bordered mt-3" id="exampleme" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Student ID No.</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Semester</th>
                                    <th>Password</th>
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
    var courseEnrollReadRoute = @if(Auth::guard('web')->user()->role == 15)
        "{{ route('getstudCurrSearchGradSchool') }}";
    @else
        "{{ route('getstudCurrBulkSearch') }}";
    @endif
    var studentcourseEnrollReadRoute = "{{ route('fetchStudEnrollmentlist') }}";
    var studentcourseEnrollPDFReadRoute = "{{ route('exportEnrollmentKioskPassPDF') }}";
    var savebulkPassRoute = "{{ route('adminkioskCreateBatch') }}";
</script>

@endsection
