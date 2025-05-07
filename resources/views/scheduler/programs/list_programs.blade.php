@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Programs
@endsection

@section('sideheader')
<h4>Option</h4>
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
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Option</li>
            <li class="breadcrumb-item active mt-1">Programs</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Programs</h4>
        </div>

        <div class="mt-3">
            <table id="classProg" class="table table-hover">
                <thead>
                    <tr>
                        <th>Program Code</th>
                        <th>Program Acronym</th>
                        <th>Program Name</th>
                        <th>Campus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editProgramModal" tabindex="-1" role="dialog" aria-labelledby="editProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProgramModalLabel">Edit Program</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editProgramForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editProgramId">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">College:</span></label>
                                <select class="form-control form-control-sm" id="college" name="progCollege">
                                    <option disabled selected>--Select--</option>
                                    @foreach($col as $datacol)
                                        <option value="{{ $datacol->college_abbr }}">{{ $datacol->college_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Department:</span></label>
                                <select class="form-control form-control-sm" id="department" name="deptCod">
                                    <option disabled selected>--Select--</option>
                                    @foreach($dept as $datadept)
                                        <option value="{{ $datadept->deptCod }}">{{ $datadept->deptName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Subject Code:</span></label>
                                <input type="text" id="sub_code" name="sub_code" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var progReadRoute = "{{ route('getprogramsRead') }}";
</script>

@endsection
