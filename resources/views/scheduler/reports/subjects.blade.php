@extends('layouts.master_classScheduler')

@section('title')
COAS - V2.0 || Subjects
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
            <li class="breadcrumb-item active mt-1">Subjects</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Subjects</h4>
        </div>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="mt-1 row">
            <div class="col-md-12">
                <table id="example1" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($subject as $data)
                        <tr id="tr-{{ $data->id}}">
                            <td>{{ $no++ }}</td>
                            <td>{{ $data->sub_code }}</td>
                            <td>{{ $data->sub_name }}</td>
                            <td>{{ $data->sub_desc }}</td>
                            <td>{{ $data->sub_unit }}</td>
                            <td>
                                <a href="" type="button" class="btn btn-primary btn-sm">
                                    <i class="fas fa-cog"></i>
                                </a>
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