@extends('layouts.master_classScheduler')

@section('title')
COAS - V1.0 || Rooms
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
            <li class="breadcrumb-item active mt-1">Rooms</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Rooms</h4>
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
                            <th>College</th>
                            <th>Room Name</th>
                            <th>Room Capacity</th>
                            <th>Campus</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($rdata as $room)
                        <tr id="tr-{{ $room->id}}">
                            <td>{{ $no++ }}</td>
                            <td>{{ $room->college_abbr }}</td>
                            <td><strong>{{ $room->room_name }}</strong></td>
                            <td>{{ $room->room_capacity }}</td>
                            <td>{{ $room->campus }}</td>
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
