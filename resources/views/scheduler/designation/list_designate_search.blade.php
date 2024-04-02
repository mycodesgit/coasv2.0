@extends('layouts.master_classScheduler')

@section('title')
COAS - V2.0 || Faculty Designation
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
            <form method="GET" action="{{ route('courseEnroll_list_search') }}" id="classEnroll">
                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Faculty Designation</h4>
                </div>

                <div class="container">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-10">
                                <label>&nbsp;</label>
                                <h5>Search Results: {{ $totalSearchResults }} 
                                    <small>
                                        <i>Year-<b>{{ request('schlyear') }}</b>,
                                            Semester-<b>{{ request('semester') }}</b>,
                                            Campus-<b>{{ request('campus') }}</b>,
                                        </i>
                                    </small>
                                </h5>
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <a href="{{ route('courseEnroll_list') }}" class="form-control form-control-sm btn btn-success btn-sm">New Sem</a>
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
                <form method="post" action="{{ route('faculty_designdAdd') }}" enctype="multipart/form-data" id="facdegAdd">
                    @csrf
                    <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                        <h5>Add</h5>
                    </div>

                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                    <div class="form-group mt-2">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <input type="text" name="schlyear" class="form-control  form-control-sm" value="{{ request('schlyear') }}">
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <input type="text" name="semester" class="form-control  form-control-sm" value="{{ request('semester') }}" readonly>
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Dept</span></label>
                                <select class="form-control form-control-sm" name="facdept">
                                    <option disabled selected>Select</option>
                                    <option value="CAF">CAF</option>
                                    <option value="CAS">CAS</option>
                                    <option value="CBM">CBM</option>
                                    <option value="CCS">CCS</option>
                                    <option value="CCJE">CCJE</option>
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
                <table id="example1" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faculty</th>
                            <th>Dept</th>
                            <th>Designation</th>
                            <th>Addresse</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $facdesig)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $facdesig->lname }}</td>
                                <td>{{ $facdesig->facdept }}</td>
                                <td>{{ $facdesig->designation }}</td>
                                <td>{{ $facdesig->rankcomma }}</td>
                                <td>
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">
                                            </button>
                                            <div class="dropdown-menu">
                                                <button class="dropdown-item btn-edit-facdesig" data-toggle="modal" data-target="#editFacDesigModal"
                                                    data-toggle="modal"
                                                    data-target="#editFacDesigModal"
                                                    data-id="{{ $facdesig->fcdid }}"
                                                    data-facdept="{{ $facdesig->facdept }}"
                                                    data-fac_id="{{ $facdesig->fac_id }}"
                                                    data-designation="{{ $facdesig->designation }}"
                                                    data-dunit="{{ $facdesig->dunit }}">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </button>
                                                <button value="" class="dropdown-item purchase-delete" href="#"><i class="fas fa-trash"></i> Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editFacDesigModal" tabindex="-1" role="dialog" aria-labelledby="editFacDesigModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFacDesigModalLabel">Edit Faculty Designation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('faculty_designdUpdate') }}" enctype="multipart/form-data" id="editFacDesigForm">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">

                    <div class="form-group">
                        <label for="edit_facdept">Department</label>
                        <select class="form-control" name="edit_facdept" id="edit_facdept">
                            <option disabled>Select</option>
                            <option value="CAF" {{ (old('edit_facdept') == 'CAF') ? 'selected' : '' }}>CAF</option>
                            <option value="CAS" {{ (old('edit_facdept') == 'CAS') ? 'selected' : '' }}>CAS</option>
                            <option value="CBM" {{ (old('edit_facdept') == 'CBM') ? 'selected' : '' }}>CBM</option>
                            <option value="CCS" {{ (old('edit_facdept') == 'CCS') ? 'selected' : '' }}>CCS</option>
                            <option value="CCJE" {{ (old('edit_facdept') == 'CCJE') ? 'selected' : '' }}>CCJE</option>
                            <option value="COE" {{ (old('edit_facdept') == 'COE') ? 'selected' : '' }}>COE</option>
                            <option value="COTED" {{ (old('edit_facdept') == 'COTED') ? 'selected' : '' }}>COTED</option>
                            <option value="REG" {{ (old('edit_facdept') == 'REG') ? 'selected' : '' }}>REGISTRAR</option>
                        </select>


                    </div>


                    <div class="form-group">
                        <label for="edit_fac_id">Faculty</label>
                        <select class="form-control" name="edit_fac_id" id="edit_fac_id">
                            @foreach($faclist as $itemfac)
                                <option value="{{ $itemfac->id }}">{{ $itemfac->lname }}, {{ $itemfac->fname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_designation">Designation</label>
                        <select class="form-control" name="edit_designation" id="edit_designation">
                            <option value="Dean" {{ (old('edit_designation', $facdesig->designation) == 'Dean') ? 'selected' : '' }}>Dean</option>
                            <option value="Registrar" {{ (old('edit_designation', $facdesig->designation) == 'Registrar') ? 'selected' : '' }}>Registrar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_dunit">Unit</label>
                        <input type="number" name="edit_dunit" id="edit_dunit" class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')