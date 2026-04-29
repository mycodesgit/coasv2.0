@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Class Scheduler
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
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Faculty Designation</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Faculty Designation</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('faculty_design_search') }}" id="classEnroll">
                                            @csrf

                                            <div class="mt-2">
                                                <div class="form-group">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
                                                            <label>Academic Year: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="schlyear">
                                                                @foreach($sy as $datasy)
                                                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Semester: <span class="text-danger">*</span></label>
                                                            <select class="form-control  form-control-sm" name="semester">
                                                                <option disabled selected>---Select---</option>
                                                                <option value="1">First Semester</option>
                                                                <option value="2">Second Semester</option>
                                                                <option value="3">Summer</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Campus: <span class="text-danger">*</span></label>
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
                                                            <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="row mt-3 p-2">
                                            <div class="col-md-3 mt-3 card" style="">
                                                <form method="post" action="{{ route('facdesignationCreate') }}" id="facdegAdd">
                                                    @csrf
                                                    <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                                        <h5>Add</h5>
                                                    </div>

                                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                    <div class="form-group mt-2">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <input type="hidden" name="schlyear" class="form-control  form-control-sm" value="{{ request('schlyear') }}">
                                                            </div>

                                                            <div class="mt-2 col-md-12">
                                                                <input type="hidden" name="semester" class="form-control  form-control-sm" value="{{ request('semester') }}" readonly>
                                                            </div>

                                                            <div class="mt-2 col-md-12">
                                                                <label>College <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="facCollege">
                                                                    <option disabled selected>Select</option>
                                                                    <option value="ADM">ADM</option>
                                                                    <option value="CAF">CAF</option>
                                                                    <option value="CAS">CAS</option>
                                                                    <option value="CBM">CBM</option>
                                                                    <option value="CCS">CCS</option>
                                                                    <option value="CJE">CJE</option>
                                                                    <option value="COE">COE</option>
                                                                    <option value="CTE">COTED</option>
                                                                </select>
                                                            </div>

                                                            <div class="mt-3 col-md-12">
                                                                <label>Faculty <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm select2bs4" name="fac_id">
                                                                    @foreach($faclist as $itemfac)
                                                                        <option value="{{ $itemfac->id }}">{{ $itemfac->lname }}, {{ $itemfac->fname }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mt-3 col-md-12">
                                                                <label>Addresse <span class="text-danger">*</span></label>
                                                                <input type="text" name="rankcomma" class="form-control form-control-sm">
                                                            </div>

                                                            <div class="mt-3 col-md-12">
                                                                <label>Designation <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="designation">
                                                                    <option value="Dean">Dean</option>
                                                                    <option value="Division Chair">Division Chair</option>
                                                                    <option value="Program Head">Program Head</option>
                                                                    <option value="Registrar">Registrar</option>
                                                                    <option value="Assessment">Assessment</option>
                                                                    <option value="CampusAdmin">Campus Admin</option>
                                                                </select>
                                                            </div>

                                                            <div class="mt-3 col-md-12">
                                                                <label>Unit <span class="text-danger">*</span></label>
                                                                <input type="number" name="dunit" class="form-control form-control-sm">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
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
                                                            <th>College</th>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="editDesignationModal" role="dialog" aria-labelledby="editDesignationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="editDesignationModalLabel">Edit Designation</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDesignationForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editDesignationId">
                        <div class="form-group">
                            <label for="editDesignationName">Name: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm select2" name="fac_id" id="editDesignationName">
                                @foreach($faclist as $itemfac)
                                    <option value="{{ $itemfac->id }}">{{ $itemfac->lname }}, {{ $itemfac->fname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editDesignationDept">Department: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="facCollege" id="editDesignationDept">
                                <option disabled selected>Select</option>
                                <option value="ADM">ADM</option>
                                <option value="CAF">CAF</option>
                                <option value="CAS">CAS</option>
                                <option value="CBM">CBM</option>
                                <option value="CCS">CCS</option>
                                <option value="CJE">CJE</option>
                                <option value="COE">COE</option>
                                <option value="CTE">COTED</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editDesignationDesignation">Designation: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="editDesignationDesignation" name="designation">
                                <option value="Dean">Dean</option>
                                <option value="Division Chair">Division Chair</option>
                                <option value="Program Head">Program Head</option>
                                <option value="Registrar">Registrar</option>
                                <option value="Assessment">Assessment</option>
                                <option value="CampusAdmin">Campus Admin</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editDesignationUnit">Unit: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editDesignationUnit" name="dunit">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
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
