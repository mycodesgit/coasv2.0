@extends('layouts.master_classScheduler')

@section('title')
COAS - V1.0 || Classes Enrolled
@endsection

@section('sideheader')
<h4>Scheduler</h4>
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
                                <select class="form-control form-control-sm" name="class">
                                    <option disabled selected>Select</option>
                                    @foreach ($program as $programs)
                                    <option value="{{ $programs->code }}" @if (old('course') == "{{ $programs->code }}") {{ 'selected' }} @endif>
                                        {{ $programs->code }}
                                    </option>
                                    @endforeach
                                    </select>
                            </div>

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
                                                <a href="" class="dropdown-item btn-edit" href="#"><i class="fas fa-exclamation-circle"></i> Edit</a>
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

@endsection

@section('script')