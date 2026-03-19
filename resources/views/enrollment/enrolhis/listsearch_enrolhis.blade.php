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
                            <li class="breadcrumb-item active mt-1">Enrollment History</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Enrollment History</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <form method="GET" action="{{ route('viewsearchenStudHistory') }}" id="studscholar">
                                            @csrf

                                            <div class="">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label><span class="badge badge-secondary">Search Student Lastname or Student ID No.</span></label>
                                                            <input type="text" name="query" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" placeholder="Search Student Lastname or Student ID No." required>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-4" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="table-responsive mt-3">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="viewStudHisModal" role="dialog" aria-labelledby="viewStudHisModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="text" name="id" id="viewStudHisId" hidden>
                    <h5 class="modal-title" id="viewStudHisModalLabel">Enrollment History of <span id="studentName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        var studenhistoryReadRoute = "{{ route('searchenStudHistory') }}";
        var studenhistoryClickReadRoute = "{{ route('fetchStudEnrollmentHistory') }}";
    </script>
@endsection
