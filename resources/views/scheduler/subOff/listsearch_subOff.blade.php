@extends('layouts.master_classScheduler')

@section('title')
COAS - V1.0 || Subject Offered
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
            <li class="breadcrumb-item active mt-1">Subject Offered</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('subjectsOffered_search') }}" id="classEnroll">
                {{ csrf_field() }}

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Subject Offered</h4>
                </div>

                <div class="container mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" id="schlyear" name="syear"></select>
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

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <h5>Search Results: {{ $totalSearchResults }} 
                <small>
                    <i>Year-<b>{{ request('syear') }}</b>,
                        Semester-<b>{{ request('semester') }}</b>,
                    </i>
                </small>
            </h5>
        </div>

        <div class="mt-3">
            <table id="example1" class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Program</th>
                        <th>Semester</th>
                        <th>Subject</th>
                        <th>Lec</th>
                        <th>Lab</th>
                        <th>Units</th>
                        <th>MaxStud</th>
                        <th>FundAccount</th>
                        <th width="60">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($data as $subjects)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $subjects->subCode }}</td>
                            <td>{{ $subjects->subSec }}</td>
                            <td>
                                @if ($subjects->semester == 1)
                                    1st
                                @elseif ($subjects->semester == 2)
                                    2nd
                                @elseif ($subjects->semester == 3)
                                    Summer
                                @else
                                    Unknown Semester
                                @endif
                            </td>
                            <td>{{ $subjects->sub_name }}</td>
                            <td>{{ $subjects->lecUnit }}</td>
                            <td>{{ $subjects->labUnit }}</td>
                            <td>{{ $subjects->subUnit }}</td>
                            <td>{{ $subjects->maxstud }}</td>
                            <td>{{ !empty($subjects->fundAccount) ? $subjects->fundAccount : 'No Account' }}</td>
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



@endsection
