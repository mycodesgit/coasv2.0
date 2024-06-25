@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Number of Enrollees
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
            <li class="breadcrumb-item active mt-1">Number of Enrollees</li>
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
                <h4>Number of Enrollees</h4>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12">
                <form id="enrollStud" method="POST" action="{{ route('studnoenrollee_searchList') }}" >
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
                                <button type="submit" id="submitForm" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="resultModal" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Number of Enrolled Students</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@endsection
