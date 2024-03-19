@extends('layouts.master_classScheduler')

@section('title')
COAS - V1.0 || Classes Enrolled
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
            <li class="breadcrumb-item active mt-1">Classes Enrolled</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('courseEnroll_list_search') }}" id="classEnroll">
                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Classes Enrolled</h4>
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
                <form method="post" action="{{ route('classEnrolledAdd') }}" enctype="multipart/form-data" id="classEnrollAdd">
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
                                <label><span class="badge badge-secondary">Programs</span></label>
                                <select class="form-control form-control-sm" name="class" id="programSelect">
                                    <option disabled selected>Select</option>
                                    @foreach ($program as $programs)
                                        <option value="{{ $programs->code }}" @if (old('course') == $programs->id) {{ 'selected' }} @endif>
                                            {{ $programs->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="prog_id" id="selectedProgramId">
                            
                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Year & Section</span></label>
                                <input type="text" name="class_section" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
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
                            <th>Program</th>
                            <th>Year&Section</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $class)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $class->class }}</td>
                                <td>{{ $class->class_section }}</td>
                                <td>
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">
                                            </button>
                                            <div class="dropdown-menu">
                                                <button class="dropdown-item btn-editclassenroll" data-toggle="modal" data-target="#editModal" 
                                                        data-id="{{ $class->id }}" data-class="{{ $class->class }}" 
                                                        data-class-section="{{ $class->class_section }}" data-program-code="{{ $class->prog_id }}">
                                                    <i class="fas fa-edit"></i> Edit
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


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Class</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="post" action="{{ route('classEnrolledUpdate') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="edit_id" value="">

                    <div class="form-group">
                        <label for="edit_program">Program</label>
                        <select class="form-control form-control-sm" name="edit_class" id="edit_class" onchange="editClassenrol(this.value)">
                            <option disabled>Select</option>
                            @foreach ($program as $programs)
                                <option value="{{ $programs->code }}" @if ($class->prog_id == $programs->id) {{ 'selected' }} @endif>
                                    {{ $programs->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="prog_id" id="selectedProgramIdEdit">

                    <div class="form-group">
                        <label for="edit_class_section">Class Section</label>
                        <input type="text" class="form-control" id="edit_class_section" name="edit_class_section" required>
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


@endsection

@section('script')