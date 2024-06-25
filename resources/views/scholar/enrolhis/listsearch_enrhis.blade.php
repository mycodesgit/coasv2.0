@extends('layouts.master_scholarship')

@section('title')
CISS V.1.0 || Student Enrollment History
@endsection

@section('sideheader')
<h4>Scholarship</h4>
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
            <li class="breadcrumb-item mt-1">Scholarship</li>
            <li class="breadcrumb-item active mt-1">Student Enrollment History</li>
        </ol>
        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
        <div class="page-header mt-2">
            <form method="GET" action="{{ route('viewsearchStudHistory') }}" id="studscholar">
                @csrf

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Search Student Lastname or Student ID No.</span></label>
                                <input type="text" name="query" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" placeholder="Search Student Lastname or Student ID No.">
                            </div>


                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="mt-5">
            <div class="">
                <table id="studhisTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student ID Number</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Extension</th>
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

<div class="modal fade" id="viewStudHisModal" role="dialog" aria-labelledby="viewStudHisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" name="id" id="viewStudHisId" hidden>
                <h5 class="modal-title" id="viewStudHisModalLabel">Enrollment History of <span id="studentName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="ss" class="table table-striped">
                    <thead>
                        <tr>
                            <th>StudentID</th>
                            <th>School Year</th>
                            <th>Semester</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody id="enrollmentHistoryTable">
                        <!-- Enrollment history will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    var studhistoryReadRoute = "{{ route('searchStudHistory') }}";
    var studhistoryClickReadRoute = "{{ route('fetchEnrollmentHistory') }}";
</script>

@endsection

@section('script')
