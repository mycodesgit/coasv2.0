@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Faculty Designation
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
            <li class="breadcrumb-item active mt-1">Faculty Designation</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('faculty_design_search') }}" id="classEnroll">
                {{ csrf_field() }}

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Faculty Designation</h4>
                </div>

                <div class="mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" name="schlyear">
                                    @foreach($sy as $datasy)
                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <select class="form-control  form-control-sm" name="semester">
                                    <option disabled selected>---Select---</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Summer</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Campus</span></label>
                                <select class="form-control form-control-sm" name="campus">
                                    <option value="{{Auth::user()->campus}}">
                                        @if (Auth::user()->campus == 'MC') Main 
                                            @elseif(Auth::user()->campus == 'VC') Victorias 
                                            @elseif(Auth::user()->campus == 'SCC') San Carlos 
                                            @elseif(Auth::user()->campus == 'HC') Hinigaran 
                                            @elseif(Auth::user()->campus == 'MP') Moises Padilla 
                                            @elseif(Auth::user()->campus == 'IC') Ilog 
                                            @elseif(Auth::user()->campus == 'CA') Candoni 
                                            @elseif(Auth::user()->campus == 'CC') Cauayan 
                                            @elseif(Auth::user()->campus == 'SC') Sipalay  
                                            @elseif(Auth::user()->campus == 'HinC') Hinobaan 
                                        @endif
                                    </option>
                                </select>
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

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="mt-3 row">
            <div class="col-md-3 mt-3 card" style="">
                <form method="post" action="{{ route('facdesignationCreate') }}" id="facdegAdd">
                    @csrf
                    <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                        <h5>Add</h5>
                    </div>

                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                    <div class="form-group mt-2">
                        <div class="form-row">
                            <div class="col-md-12">
                                <input type="hidden" name="schlyear" class="form-control  form-control-sm" value="{{ request('schlyear') }}">
                            </div>

                            <div class="mt-2 col-md-12">
                                <input type="hidden" name="semester" class="form-control  form-control-sm" value="{{ request('semester') }}" readonly>
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Dept</span></label>
                                <select class="form-control form-control-sm" name="facdept">
                                    <option disabled selected>Select</option>
                                    <option value="ADM">ADM</option>
                                    <option value="CAF">CAF</option>
                                    <option value="CAS">CAS</option>
                                    <option value="CBM">CBM</option>
                                    <option value="CCS">CCS</option>
                                    <option value="CJE">CJE</option>
                                    <option value="COE">COE</option>
                                    <option value="COTED">COTED</option>
                                </select>
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Faculty</span></label>
                                <select class="form-control form-control-sm select2bs4" name="fac_id">
                                    @foreach($faclist as $itemfac)
                                        <option value="{{ $itemfac->id }}">{{ $itemfac->lname }}, {{ $itemfac->fname }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Addresse</span></label>
                                <input type="text" name="rankcomma" class="form-control form-control-sm">
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Designation</span></label>
                                <select class="form-control form-control-sm" name="designation">
                                    <option value="Dean">Dean</option>
                                    <option value="Registrar">Registrar</option>
                                    <option value="Assessment">Assessment</option>
                                    <option value="CampusAdmin">Campus Admin</option>
                                </select>
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Unit</span></label>
                                <input type="number" name="dunit" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-12">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-9 mt-3 pl-3 pr-3 pt-3">
                <table id="designationTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Faculty</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editDesignationModal" role="dialog" aria-labelledby="editDesignationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDesignationModalLabel">Edit Designation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDesignationForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editDesignationId">
                    <div class="form-group">
                        <label for="editDesignationName">Name</label>
                        <select class="form-control form-control-sm select2bs4" name="fac_id" id="editDesignationName">
                            @foreach($faclist as $itemfac)
                                <option value="{{ $itemfac->id }}">{{ $itemfac->lname }}, {{ $itemfac->fname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDesignationDept">Department</label>
                        <select class="form-control form-control-sm" name="facdept" id="editDesignationDept">
                            <option disabled selected>Select</option>
                            <option value="ADM">ADM</option>
                            <option value="CAF">CAF</option>
                            <option value="CAS">CAS</option>
                            <option value="CBM">CBM</option>
                            <option value="CCS">CCS</option>
                            <option value="CJE">CJE</option>
                            <option value="COE">COE</option>
                            <option value="COTED">COTED</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDesignationDesignation">Designation</label>
                        <select class="form-control form-control-sm" id="editDesignationDesignation" name="designation">
                            <option value="Dean">Dean</option>
                            <option value="Registrar">Registrar</option>
                            <option value="CampusAdmin">Campus Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDesignationUnit">Unit</label>
                        <input type="text" class="form-control" id="editDesignationUnit" name="dunit">
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
    var designationReadRoute = "{{ route('getfacultyDesigRead') }}";
    var designationCreateRoute = "{{ route('facdesignationCreate') }}";
    var designationUpdateRoute = "{{ route('facdesignationUpdate', ['id' => ':id']) }}";
    var designationDeleteRoute = "{{ route('designationDelete', ['id' => ':id']) }}";
</script>

@endsection

@section('script')