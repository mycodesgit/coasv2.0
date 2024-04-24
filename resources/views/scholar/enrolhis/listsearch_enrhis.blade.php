@extends('layouts.master_scholarship')

@section('title')
COAS - V2.0 || Student Enrollment History
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
                            {{-- <th>No.</th> --}}
                            <th>Student ID Number</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Extension</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($results as $res)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $res->stud_id }}</td>
                            <td>{{ $res->lname }}</td>
                            <td>{{ $res->fname }}</td>
                            <td>{{ $res->mname }}</td>
                            <td>{{ $res->ext }}</td>
                            <td style="text-align:center;">
                                <a href="#" class="btn btn-primary btn-sm btn-studhisview" data-id="{{ $res->id }}" data-studhislname="{{ $res->lname }}">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach --}}
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
                <input type="hidden" name="id" id="viewStudHisId">
                <h5 class="modal-title" id="viewStudHisModalLabel">
                    Enrollment History of <input type="text" class="" style="border: none; background-color: none !important;" id="viewStudHisName" disabled>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Semester</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- <form id="viewStudHisForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="viewStudHisId">
                    <div class="form-group">
                        <label for="viewStudHisName">Name</label>
                        <input type="text" class="form-control form-control-sm" name="chedsch_name" id="viewStudHisName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form> --}}
        </div>
    </div>
</div>

<script>
    var studhistoryReadRoute = "{{ route('searchStudHistory') }}";
</script>

@endsection

@section('script')
