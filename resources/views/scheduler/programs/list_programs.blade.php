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
    <div class="modal-dialog modal-xl" role="document">
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
                                <select class="form-control form-control-sm" id="department" name="progDep">
                                    <option disabled selected>--Select--</option>
                                    @foreach($dept as $datadept)
                                        <option value="{{ $datadept->deptCod }}">{{ $datadept->deptName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Program Code:</span></label>
                                <input type="text" id="editprogCod" name="progCod" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Program Account:</span></label>
                                <input type="text" id="progaccount" name="progAccount" class="form-control form-control-sm" readonly>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-warning">Progam Name:</span></label>
                                <input type="text" id="progName" name="progName" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-danger">Progam Acronym:</span></label>
                                <input type="text" id="progAcronym" name="progAcronym" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Program Level:</span></label>
                                <select name="progLev" id="editprogLev" class="form-control form-control-sm">
                                    <option disabled selected> --Select-- </option>
                                    @foreach ($lev as $datalev)
                                        <option value="{{ $datalev->id }}">{{ $datalev->studLevel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="editCampAbbr"><span class="badge badge-secondary">Belongs to:</span></label>
                                <input type="text" class="form-control form-control-sm" id="editCampAbbr" name="campus" readonly>
                            </div>
                            <div class="col-md-8">
                                <label><span class="badge badge-secondary">Campus:</span></label>
                                <select class="form-control form-control-sm select2" multiple="multiple" name="campus[]">
                                    <option value="MC">Main</option>
                                    <option value="VC">Victorias</option>
                                    <option value="SCC">San Carlos</option>
                                    <option value="MP">Moises Padilla</option>
                                    <option value="HC">Hinigaran</option>
                                    <option value="IC">Ilog</option>
                                    <option value="CA">Candoni</option>
                                    <option value="CC">Cauayan</option>
                                    <option value="SC">Sipalay</option>
                                    <option value="HinC">Hinobaan</option>
                                </select>
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
    var progDeptRoute = "{{ route('getDepartmentsByCollege') }}";
    var progCodeRoute = "{{ route('getNextProgramNumber') }}";
    var progUpdateRoute = "{{ route('programUpdate', ['id' => ':id']) }}";
    var idEncryptRoute = "{{ route('idcrypt') }}";
</script>

@endsection
