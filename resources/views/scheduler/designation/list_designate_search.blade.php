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
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
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
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Faculty Designation</h1>
                        <p class="text-muted small mb-0">Manage Faculty Designation every academic year.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('faculty_design_search') }}" id="classEnroll">
                                    @csrf

                                    <div class="mt-2">
                                        <div class="form-group">
                                            <div class="row g-3">
                                                <div class="col-md-2">
                                                    <label class="form-label fw-semibold">Academic Year: <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="schlyear">
                                                        @foreach($sy as $datasy)
                                                            <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear || ($loop->first && !request('schlyear')) ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                    <select class="form-control  form-control-sm" name="semester">
                                                        <option disabled selected>---Select---</option>
                                                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>First Semester</option>
                                                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Second Semester</option>
                                                        <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Summer</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
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
                                                    <div class="d-flex flex-column h-100">
                                                        <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-plus"></i> Add New
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{ route('facdesignationCreate') }}" id="facdegAdd">
                                    @csrf

                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">
                                    <input type="hidden" name="schlyear" class="form-control  form-control-sm" value="{{ request('schlyear') }}">
                                    <input type="hidden" name="semester" class="form-control  form-control-sm" value="{{ request('semester') }}" readonly>

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">College <span class="text-danger">*</span></label>
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

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Faculty <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2bs4" name="fac_id">
                                                    @foreach($faclist as $itemfac)
                                                        <option value="{{ $itemfac->id }}">{{ $itemfac->lname }}, {{ $itemfac->fname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
                                                <select name="designation" id="designation" class="form-control select2bs4 form-control-sm">
                                                    <optgroup label="Most Selected">
                                                        <option value="Dean of Instruction">Dean of Instruction</option>
                                                        <option value="Dean">Dean</option>
                                                        <option value="Division Chair">Division Chair</option>
                                                        <option value="Program Head">Program Head</option>
                                                        <option value="Registrar">Registrar</option>
                                                        <option value="Assessment">Assessment</option>
                                                        <option value="CampusAdmin">Campus Administrator</option>
                                                    </optgroup>

                                                    <optgroup label="A. University Wide">
                                                        <option value="Vice President">Vice President</option>
                                                        <option value="Dean">Dean</option>
                                                        <option value="Quality Assurance">Quality Assurance</option>
                                                        <option value="Research & Extension">Research & Extension</option>
                                                        <option value="Planning Officer">Planning Officer</option>
                                                        <option value="Director (by category)">Director (by category)</option>
                                                        <option value="CampusAdmin">Campus Administrator</option>
                                                        <option value="Coordinator">Coordinator</option>
                                                        <option value="Internal Lead Auditor">Internal Lead Auditor</option>
                                                        <option value="ISO/ISA Secretariat">ISO/ISA Secretariat</option>
                                                        <option value="Faculty Regent">Faculty Regent</option>
                                                        <option value="Editor-in-Chief/Managing Editor">Editor-in-Chief/Managing Editor</option>
                                                    </optgroup>

                                                    <optgroup label="B. Campus/College Level">
                                                        <option value="Division Chair">Division Chair</option>
                                                        <option value="Program Head">Program Head</option>
                                                        <option value="Collecting and Disbursing Officer">Collecting and Disbursing Officer</option>
                                                        <option value="Library Services/Librarian">Library Services/Librarian</option>
                                                        <option value="Registrar">Registrar</option>
                                                        <option value="Assessment">Assessment</option>
                                                        <option value="MIS">Network Administrator/Management Information System (MIS) In-charge</option>
                                                        <option value="Data Privacy Act Officer">Data Privacy Act Officer</option>
                                                        <option value="Guidance and Counseling/Health Services">Guidance and Counseling/Health Services</option>
                                                        <option value="Property Custodian/Supply Officer/Records Officer/Campus-HR">Property Custodian/Supply Officer/Records Officer/Campus-HR</option>
                                                        <option value="OSSA Coordinator">OSSA Coordinator</option>
                                                        <option value="NBC Local Evaluator">NBC Local Evaluator</option>
                                                        <option value="Coordinator, Research/Extension">Coordinator, Research/Extension</option>
                                                        <option value="Coordinator, Landscaping and Beautification">Coordinator, Landscaping and Beautification</option>
                                                        <option value="Coordinator, Sports and Cultural">Coordinator, Sports and Cultural</option>
                                                        <option value="Adviser, Student Organization (FLP/SSG)">Adviser, Student Organization (FLP/SSG)</option>
                                                        <option value="Adviser, School Publication/Journal">Adviser, School Publication/Journal</option>
                                                        <option value="Laboratory/AVR In-charge">Laboratory/AVR In-charge</option>
                                                        <option value="Yearbook In-charge">Yearbook In-charge</option>
                                                        <option value="Sports and Cultural Coach (Varsity)">Sports and Cultural Coach (Varsity)</option>
                                                        <option value="Project In-charge">Project In-charge</option>
                                                        <option value="Faculty with approved Research/Extension/Production proposal">Faculty with approved Research/Extension/Production proposal</option>
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                                                <input type="number" name="dunit" class="form-control form-control-sm">
                                            </div>

                                            <div class="col-md-12 d-flex justify-content-between">
                                                <button type="reset" class="btn btn-light"><i class="ti ti-restore"></i> Clear</button>
                                                <button type="button" class="btn btn-success"><i class="ti ti-device-floppy"></i>  Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-users"></i> List of Faculty Designation
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    <table id="designationTable" class="table table-hover" style="width: 100%">
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

    <div class="modal fade mt-6" id="editDesignationModal" role="dialog" aria-labelledby="editDesignationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDesignationModalLabel"><i class="ti ti-pencil"></i> Edit Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDesignationForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editDesignationId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editDesignationName">Name: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm select2" name="fac_id" id="editDesignationName">
                                    @foreach($faclist as $itemfac)
                                        <option value="{{ $itemfac->id }}">{{ $itemfac->lname }}, {{ $itemfac->fname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editDesignationDept">Department: <span class="text-danger">*</span></label>
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
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editDesignationDesignation">Designation: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="editDesignationDesignation" name="designation">
                                    <option value="Dean">Dean</option>
                                    <option value="Division Chair">Division Chair</option>
                                    <option value="Program Head">Program Head</option>
                                    <option value="Registrar">Registrar</option>
                                    <option value="Assessment">Assessment</option>
                                    <option value="CampusAdmin">Campus Admin</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editDesignationUnit">Unit: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editDesignationUnit" name="dunit">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i> Save changes</button>
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
